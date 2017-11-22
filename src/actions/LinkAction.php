<?php

namespace ylab\rest\actions;

use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Transaction;
use yii\web\BadRequestHttpException;

/**
 * @inheritdoc
 */
class LinkAction extends LinkableAction
{
    /**
     * @inheritdoc
     */
    protected function link(
        ActiveRecord $model,
        $relationName,
        ActiveRecord $relationModel,
        Transaction $transaction,
        $delete
    ) {
        try {
            $model->link($relationName, $relationModel);
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new BadRequestHttpException(
                "$relationName relation with id=$relationModel->primaryKey already linked."
            );
        }
    }
}
