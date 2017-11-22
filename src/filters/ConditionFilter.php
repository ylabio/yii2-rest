<?php

namespace ylab\rest\filters;

use yii\db\ActiveRecord;
use yii\db\QueryInterface;

/**
 * {@inheritdoc}
 *
 * ConditionFilter for add in `yii\data\DataFilter` filtering through query parameters.
 * Example usage:
 * ```
 * public function behaviors()
 * {
 * return [
 *     'conditionFilter' => [
 *         'class' => ConditionFilter::class,
 *         'only' => ['index'],
 *     ],
 * ];
 * }
 * ```
 *
 * Requested fields may be query parameters as:
 * ```
 * GET http://sitename.com/api/users?name=john
 * ```
 */
class ConditionFilter extends AbstractFilter
{
    /**
     * @var array for keeping conditions
     */
    protected $conditions = [];

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        /** @var ActiveRecord $model */
        $model = new $this->owner->modelClass;
        if (empty($this->fields)) {
            $fields = $model->fields();
            if (empty($fields)) {
                $fields = $model::getTableSchema()->getColumnNames();
            }
            $this->fields = array_merge($fields, $model->extraFields());
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        foreach (\Yii::$app->request->getQueryParams() as $param => $value) {
            if (in_array($param, $this->fields, true)) {
                $this->conditions[$param] = $value;
            }
        }

        return true;
    }

    /**
     * Add conditions to `conditions` field.
     *
     * @param QueryInterface $query
     */
    public function addConditions(QueryInterface $query)
    {
        $query->andFilterWhere($this->conditions);
    }
}
