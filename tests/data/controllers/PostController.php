<?php

namespace tests\data\controllers;

use tests\data\models\Post;
use ylab\rest\ActiveController;

/**
 * @inheritdoc
 */
class PostController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Post::class;
    /**
     * @inheritdoc
     */
    public $fieldsAttributes = [
        'id',
        'text',
        'preview',
    ];
    /**
     * @inheritdoc
     */
    public $expandAttributes = [
        'comments',
    ];
    /**
     * @inheritdoc
     */
    public $sortAttributes = [
        'id',
        'comment.id' => 'comments',
    ];
}
