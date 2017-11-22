<?php

namespace ylab\rest;

use yii\web\Response;

/**
 * @inheritdoc
 */
class ErrorHandler extends \yii\web\ErrorHandler
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
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    protected function renderException($exception)
    {
        if (\Yii::$app->has('response')) {
            $response = \Yii::$app->getResponse();
        } else {
            $response = new Response();
        }

        if (isset($exception->statusCode)) {
            $statusCode = $exception->statusCode;
        } else {
            $statusCode = 500;
        }

        $response->data = $this->responseFormatter->format(
            false,
            [],
            ['exception' => $this->convertExceptionToArray($exception)]
        );
        $response->setStatusCode($statusCode);
        $response->send();
    }
}
