<?php

namespace ylab\rest\filters;

use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * {@inheritdoc}
 *
 * FieldsFilter for constraints by requested resource fields.
 * Example usage:
 * ```
 * public function behaviors()
 * {
 * return [
 *     'fieldsFilter' => [
 *         'class' => FieldsFilter::class,
 *         'only' => ['index', 'view'],
 *         'fields' => ['id', 'name'],
 *     ],
 * ];
 * }
 * ```
 *
 * @property array $fields list of fields which allowed to request:
 * - if it's empty, all resource fields are allowed;
 * - if it's not empty, only this resource fields are allowed. If in query are requested others fields,
 * `BadRequestHttpException` will be thrown.
 */
class FieldsFilter extends AbstractFilter
{
    /**
     * @inheritdoc
     */
    public $queryParam = 'fields';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        foreach ($this->fields as $field) {
            if (($pos = mb_strrpos('.', $field)) !== false) {
                $this->addRelation(mb_substr($field, 0, $pos));
            }
        }

        $fields = \Yii::$app->request->getQueryParam($this->queryParam);
        if (!empty($this->fields)) {
            if ($fields === null) {
                \Yii::$app->request->setQueryParams(ArrayHelper::merge(
                    \Yii::$app->request->getQueryParams(),
                    [$this->queryParam => implode(', ', $this->fields)]
                ));
            } else {
                $fields = array_map('trim', explode(',', $fields));
                foreach ($fields as $field) {
                    if (!in_array($field, $this->fields, true)) {
                        throw new BadRequestHttpException("Getting '$field' field is not allowed.");
                    }
                }
            }
        }

        return true;
    }
}
