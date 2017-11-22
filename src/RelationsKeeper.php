<?php

namespace ylab\rest;

use yii\base\BaseObject;

/**
 * {@inheritdoc}
 *
 * Class for keeping relations and returning them if needed
 */
class RelationsKeeper extends BaseObject
{
    /**
     * @var array of relation names
     */
    private $relations = [];

    /**
     * Add relation in `relations` property
     *
     * @param string $relation relation name
     */
    public function addRelation($relation)
    {
        if (!in_array($relation, $this->relations, true)) {
            $this->relations[] = $relation;
        }
    }

    /**
     * Get array of relations names
     *
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }
}
