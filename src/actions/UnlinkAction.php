<?php

namespace ylab\rest\actions;

use yii\db\ActiveRecord;
use yii\db\Transaction;

/**
 * @inheritdoc
 */
class UnlinkAction extends LinkableAction
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
        $model->unlink($relationName, $relationModel, $delete);
    }
}
