<?php

namespace ylab\rest;

use yii\data\ActiveDataFilter;
use ylab\rest\actions\CreateAction;
use ylab\rest\actions\DeleteAction;
use ylab\rest\actions\IndexAction;
use ylab\rest\actions\LinkAction;
use ylab\rest\actions\OptionsAction;
use ylab\rest\actions\UnlinkAction;
use ylab\rest\actions\UpdateAction;
use ylab\rest\actions\ViewAction;
use ylab\rest\filters\ConditionFilter;
use ylab\rest\filters\ExpandFilter;
use ylab\rest\filters\FieldsFilter;
use ylab\rest\filters\SortFilter;

/**
 * {@inheritdoc}
 *
 * @mixin FieldsFilter
 * @mixin ExpandFilter
 * @mixin SortFilter
 * @mixin ConditionFilter
 */
class ActiveController extends \yii\rest\ActiveController
{
    /**
     * @inheritdoc
     */
    public $serializer = Serializer::class;
    /**
     * @var array data for `fields` property in `FieldsFilter` class
     * @see FieldsFilter
     */
    public $fieldsAttributes = [];
    /**
     * @var array data for `fields` property in `ExpandFilter` class
     * @see ExpandFilter
     */
    public $expandAttributes = [];
    /**
     * @var array data for `fields` property in `SortFilter` class
     * @see SortFilter
     */
    public $sortAttributes = [];
    /**
     * @var RelationsKeeper
     */
    public $relationsKeeper;

    /**
     * @inheritdoc
     */
    public function __construct($id, $module, RelationsKeeper $relationsKeeper, $config = [])
    {
        $this->relationsKeeper = $relationsKeeper;
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => $this->modelClass,
                ],
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'link' => [
                'class' => LinkAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'unlink' => [
                'class' => UnlinkAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => OptionsAction::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'fieldsFilter' => [
                'class' => FieldsFilter::class,
                'only' => ['index', 'view'],
                'fields' => $this->fieldsAttributes,
            ],
            'expandFilter' => [
                'class' => ExpandFilter::class,
                'only' => ['index', 'view'],
                'fields' => $this->expandAttributes,
            ],
            'sortFilter' => [
                'class' => SortFilter::class,
                'only' => ['index'],
                'fields' => $this->sortAttributes,
            ],
            'conditionFilter' => [
                'class' => ConditionFilter::class,
                'only' => ['index'],
            ],
        ]);
    }
}
