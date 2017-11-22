<?php

namespace ylab\rest\filters;

use yii\web\BadRequestHttpException;
use ylab\rest\ActiveController;

/**
 * {@inheritdoc}
 *
 * ExpandFilter for constraints by requested resource relations fields
 * Example usage:
 * ```
 * public function behaviors()
 * {
 * return [
 *     'expandFilter' => [
 *         'class' => ExpandFilter::class,
 *         'only' => ['index', 'view'],
 *         'fields' => ['comments'],
 *     ],
 * ];
 * }
 * ```
 *
 * @property array $fields list of resource relations which allowed to request
 * - if it's empty, all resource relations are allowed;
 * - if it's not empty, only this resource relations are allowed. If in query are requested others relations,
 * BadRequestHttpException will be thrown.
 */
class ExpandFilter extends AbstractFilter
{
    /**
     * @inheritdoc
     */
    public $queryParam = 'expand';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        foreach ($this->fields as $relation) {
            $this->addRelation($relation);
        }

        $expands = \Yii::$app->request->getQueryParam($this->queryParam);
        if ($expands === null) {
            return true;
        }
        if (!empty($this->fields)) {
            $expands = array_map('trim', explode(',', $expands));
            foreach ($expands as $relation) {
                if (!in_array($relation, $this->fields, true)) {
                    throw new BadRequestHttpException("Getting '$relation' field is not allowed.");
                }
                $this->addRelation($relation);
            }
        }

        return true;
    }
}
