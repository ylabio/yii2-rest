<?php

namespace tests\data\controllers;

use tests\data\models\Post;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Response;
use ylab\rest\ActiveController;

/**
 * @inheritdoc
 */
class SimplePostController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Post::class;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
        ];
    }
}
