<?php

namespace ylab\rest\actions;

use yii\data\DataFilter;
use ylab\rest\ActiveController;
use ylab\rest\filters\ConditionFilter;
use ylab\rest\filters\SortFilter;

/**
 * {@inheritdoc}
 * @property ActiveController $controller
 */
class IndexAction extends \yii\rest\IndexAction
{
    /**
     * @inheritdoc
     */
    protected function prepareDataProvider()
    {
        $dataProvider = parent::prepareDataProvider();
        if ($dataProvider instanceof DataFilter) {
            return $dataProvider;
        }
        $relations = $this->controller->relationsKeeper->getRelations();
        if (!empty($relations)) {
            $dataProvider->query->joinWith($relations);
        }

        /** @var SortFilter $sortFilter */
        $sortFilter = $this->controller->getBehavior('sortFilter');
        if ($sortFilter !== null && !empty($sortFilter->getSortBy())) {
            $dataProvider->query->addOrderBy($this->controller->getBehavior('sortFilter')->getSortBy());
        }

        /** @var ConditionFilter $conditionFilter */
        $conditionFilter = $this->controller->getBehavior('conditionFilter');
        if ($conditionFilter !== null) {
            $conditionFilter->addConditions($dataProvider->query);
        }

        return $dataProvider;
    }
}
