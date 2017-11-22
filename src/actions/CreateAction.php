<?php

namespace ylab\rest\actions;

use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use ylab\rest\ActiveController;
use ylab\rest\RelationSaver;

/**
 * {@inheritdoc}
 * @property ActiveController $controller
 */
class CreateAction extends \yii\rest\CreateAction
{
    use RelationSaver;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

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
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } else {
            $transaction->rollBack();
            if (!$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }
        }

        return $model;
    }
}
