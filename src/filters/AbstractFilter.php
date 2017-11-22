<?php

namespace ylab\rest\filters;

use yii\base\ActionFilter;
use yii\base\InvalidConfigException;
use ylab\rest\ActiveController;

/**
 * {@inheritdoc}
 *
 * Base class for all filters.
 *
 * @property ActiveController $owner
 */
abstract class AbstractFilter extends ActionFilter
{
    /**
     * @var array filter fields configuration
     */
    public $fields = [];
    /**
     * @var string filter param name in query
     */
    public $queryParam;

    /**
     * Add relation in owners `relationKeeper` property
     *
     * @param string $field relation name
     */
    protected function addRelation($field)
    {
        $this->owner->relationsKeeper->addRelation($field);
    }
}
