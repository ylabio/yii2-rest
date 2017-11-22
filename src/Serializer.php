<?php

namespace ylab\rest;

use yii\base\Arrayable;
use yii\base\Model;
use yii\data\DataProviderInterface;

/**
 * @inheritdoc
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @inheritdoc
     */
    public function __construct(ResponseFormatter $responseFormatter, $config = [])
    {
        $this->responseFormatter = $responseFormatter;

        $controller = \Yii::$app->controller;
        if ($controller instanceof ActiveController) {
            if (isset($controller->getBehavior('fieldsFilter')->queryParam)) {
                $this->fieldsParam = $controller->getBehavior('fieldsFilter')->queryParam;
            }
            if (isset($controller->getBehavior('expandFilter')->queryParam)) {
                $this->expandParam = $controller->getBehavior('expandFilter')->queryParam;
            }
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function serialize($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->responseFormatter->format(false, [], $this->serializeModelErrors($data));
        } elseif ($data instanceof Arrayable) {
            return $this->responseFormatter->format(true, $this->serializeModel($data), []);
        } elseif ($data instanceof DataProviderInterface) {
            return $this->responseFormatter->format(true, $this->serializeDataProvider($data), []);
        } else {
            return $data;
        }
    }
}
