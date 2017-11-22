<?php

namespace ylab\rest\actions;

use yii\db\ActiveRecord;
use yii\db\Transaction;
use yii\rest\Action;
use yii\web\BadRequestHttpException;

/**
 * {@inheritdoc}
 *
 * Base class for `LinkAction` and `UnlinkAction`.
 * Contain logic for saving model relations.
 * Request must contains body parameters for info about related models as relation name => [related models ids]
 * Example:
 * ```
 * ['comments' => [1, 2, 3], 'tags' => [1, 2]]
 * ```
 */
abstract class LinkableAction extends Action
{
    /**
     * @param int $id
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $links = \Yii::$app->getRequest()->getBodyParams();
        $transaction = \Yii::$app->db->beginTransaction();
        foreach ($links as $relationName => $ids) {
            $relation = $model->getRelation($relationName, false);
            if ($relation === null) {
                $transaction->rollBack();
                throw new BadRequestHttpException("$this->modelClass model has no relation named '$relationName'.");
            }
            if (!is_array($ids)) {
                $ids = [$ids];
            }
            foreach ($ids as $relationId) {
                $linkable = (new $relation->modelClass())->findOne($relationId);
                if ($linkable === null) {
                    $transaction->rollBack();
                    throw new BadRequestHttpException("$relationName relation with id=$relationId not exists.");
                }
                $this->link($model, $relationName, $linkable, $transaction, $relation->via !== null);
            }
        }
        $transaction->commit();
        \Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * @param ActiveRecord $model
     * @param string $relationName
     * @param ActiveRecord $relationModel
     * @param Transaction $transaction
     * @param bool $delete
     */
    abstract protected function link(
        ActiveRecord $model,
        $relationName,
        ActiveRecord $relationModel,
        Transaction $transaction,
        $delete
    );
}
