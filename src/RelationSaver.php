<?php

namespace ylab\rest;

use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Transaction;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Trait RelationSaver
 * This one will be save relations with owners in CreateAction and UpdateAction
 */
trait RelationSaver
{
    /**
     * @param string $relName name of relation
     * @param array $relParams relation fields configuration
     * @param ActiveRecord $model owner model
     * @param Transaction $transaction transaction instance
     * @return bool success save or not
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws ServerErrorHttpException
     */
    protected function saveRelation($relName, array $relParams, ActiveRecord $model, Transaction $transaction)
    {
        $relation = $model->getRelation($relName, false);
        if ($relation === null) {
            $transaction->rollBack();
            throw new BadRequestHttpException("Relation '$relation' not exists.");
        }
        $relPk = (new $relation->modelClass)->primaryKey();
        if (count($relPk) !== 1) {
            $relPk = false;
        }
        $relPk = $relPk[0];
        foreach ($relParams as $params) {
            /** @var \yii\db\ActiveRecord $relModel */
            $relModel = new $relation->modelClass;
            if ($relPk !== false && isset($params[$relPk])) {
                $relModel = $relModel::findOne($params[$relPk]);
                if ($relModel === null) {
                    $transaction->rollBack();
                    throw new BadRequestHttpException(
                        "Relation '$relation' with primary key '$params[$relPk]' not found."
                    );
                }
            }
            $relModel->load($params, '');
            if ($relation->via === null) {
                $key = key($relation->link);
                $relModel->$key = $model->{$relation->link[$key]};
            }
            if (!$relModel->save()) {
                $transaction->rollBack();
                $model->addErrors([$relName => $relModel->getErrors()]);
                if (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
                }
                return false;
            }
            try {
                $model->link($relName, $relModel);
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new BadRequestHttpException(
                    "$relName relation with id='$relModel->primaryKey' already linked."
                );
            }
        }
        return true;
    }
}
