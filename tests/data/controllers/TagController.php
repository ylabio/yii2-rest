<?php

namespace tests\data\controllers;

use tests\data\models\Tag;
use ylab\rest\ActiveController;

/**
 * @inheritdoc
 */
class TagController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Tag::class;
}
