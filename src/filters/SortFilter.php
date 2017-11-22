<?php

namespace ylab\rest\filters;

use yii\web\BadRequestHttpException;

/**
 * {@inheritdoc}
 *
 * SortFilter for constraints by requested sort fields
 * Example usage:
 * ```
 * public function behaviors()
 * {
 * return [
 *     'sortFilter' => [
 *         'class' => SortFilter::class,
 *         'only' => ['index'],
 *         'fields' => ['id', 'comment.id' => 'comments'],
 *     ],
 * ];
 * }
 * ```
 *
 * @property array $fields list of fields which allowed to sorting. It may be string of resource field or array, where
 * key is relation field in dot notation, value is relation name.
 * - if it's empty, all resource fields are allowed to sorting;
 * - if it's not empty, only this resource fields are allowed to sorting. If in query are requested others sorting
 * fields, BadRequestHttpException will be thrown.
 */
class SortFilter extends AbstractFilter
{
    /**
     * @inheritdoc
     */
    public $queryParam = 'sort';
    /**
     * @var array of sort attributes
     */
    protected $sortBy = [];

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $sorts = \Yii::$app->request->getQueryParam($this->queryParam);
        if ($sorts === null) {
            return true;
        }
        $this->prepareSortBy($sorts);
        if (!empty($this->fields)) {
            foreach (array_keys($this->getSortBy()) as $sort) {
                if (array_key_exists($sort, $this->fields)) {
                    $this->addRelation($this->fields[$sort]);
                    break;
                }
                if (!in_array($sort, $this->fields, true)) {
                    throw new BadRequestHttpException("Sorting by '$sort' field is not allowed.");
                }
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * Map requested sorts in own `sortBy` property.
     *
     * @param string $sorts
     */
    protected function prepareSortBy($sorts)
    {
        $sorts = array_map('trim', explode(',', $sorts));
        foreach ($sorts as $sort) {
            $descending = false;
            if (strncmp($sort, '-', 1) === 0) {
                $descending = true;
                $sort = substr($sort, 1);
            }
            $this->sortBy[$sort] = $descending ? SORT_DESC : SORT_ASC;
        }
    }
}
