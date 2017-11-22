<?php

namespace ylab\rest\actions;

use yii\web\ServerErrorHttpException;
use ylab\rest\ActiveController;
use ylab\rest\RelationSaver;

/**
 * {@inheritdoc}
 * @property ActiveController $controller
 */
class UpdateAction extends \yii\rest\UpdateAction
{
    use RelationSaver;

    /**
     * @inheritdoc
     */
    public function run($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        $params = \Yii::$app->getRequest()->getBodyParams();
        $model->load($params, '');
        $transaction = \Yii::$app->db->beginTransaction();
        if ($model->save()) {
            foreach ($params as $name => $relParams) {
                if (is_array($relParams) && !$this->saveRelation($name, $relParams, $model, $transaction)) {
                    return $model;
                }
            }
            $transaction->commit();
        } else {
            $transaction->rollBack();
            if (!$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        }

        return $model;
    }
}
