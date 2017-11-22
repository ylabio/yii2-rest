<?php

namespace tests;

class Response
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var string
     */
    public $format;
    /**
     * @var int
     */
    public $statusCode;

    /**
     * Response constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->format = \Yii::$app->getResponse()->format;
        $this->statusCode = \Yii::$app->getResponse()->statusCode;
        $this->data = $data;
    }
}
