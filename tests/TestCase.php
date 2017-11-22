<?php

namespace tests;

use League\FactoryMuffin\FactoryMuffin;
use tests\data\Migration;
use tests\data\models\Author;
use yii\db\Connection;
use yii\web\Application;
use yii\web\JsonParser;
use yii\web\Request;
use ylab\rest\ErrorHandler;

/**
 * @inheritdoc
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FactoryMuffin
     */
    protected $fm;
    /**
     * @var string path to factories
     */
    protected $factoriesPath = '@tests/factories';
    /**
     * @var int count of generated models
     */
    protected $modelCount = 5;

    /**
     * @return array
     */
    protected function factories()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        new Application([
            'id' => 'test-app',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'controllerNamespace' => 'tests\data\controllers',
            'components' => [
                'user' => [
                    'identityClass' => Author::class,
                ],
                'db' => [
                    'class' => Connection::class,
                    'dsn' => 'sqlite:' . \Yii::getAlias('@tests/data/db.sqlite3'),
                    'username' => '',
                    'password' => '',
                ],
                'request' => [
                    'class' => Request::class,
                    'cookieValidationKey' => 'njzJd93jkDNidpfkwedwef',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                    'url' => '/test',
                    'enableCsrfValidation' => false,
                    'parsers' => [
                        'application/json' => JsonParser::class,
                    ],
                ],
                'response' => [
                    'class' => \yii\web\Response::class
                ],
                'errorHandler' => [
                    'class' => ErrorHandler::class,
                    'discardExistingOutput' => false,
                ],
            ]
        ]);
        (new Migration())->safeUp();
        $this->fm = new FactoryMuffin();
        $factories = $this->factories();
        if (count($factories) > 0) {
            $this->fm->loadFactories(\Yii::getAlias($this->factoriesPath));
            foreach ($factories as $factory) {
                $this->fm->seed($this->modelCount, $factory);
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        (new Migration())->down();
        unlink('tests/data/db.sqlite3');
        \Yii::$app = null;
    }

    /**
     * @param string $url
     * @param array $params
     * @return Response
     */
    protected function sendGetRequest($url, array $params = [])
    {
        return $this->sendRequest('GET', $url, $params);
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $bodyParams
     * @return Response
     */
    protected function sendPostRequest($url, array $queryParams = [], array $bodyParams = [])
    {
        return $this->sendRequest('POST', $url, $queryParams, $bodyParams);
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $bodyParams
     * @return Response
     */
    protected function sendPutRequest($url, array $queryParams = [], array $bodyParams = [])
    {
        return $this->sendRequest('PUT', $url, $queryParams, $bodyParams);
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $bodyParams
     * @return Response
     */
    protected function sendPatchRequest($url, array $queryParams = [], array $bodyParams = [])
    {
        return $this->sendRequest('PATCH', $url, $queryParams, $bodyParams);
    }

    /**
     * @param string $url
     * @param array $params
     * @return Response
     */
    protected function sendDeleteRequest($url, array $params = [])
    {
        return $this->sendRequest('DELETE', $url, $params);
    }

    /**
     * @param  string$method
     * @param string $url
     * @param array $queryParams
     * @param array $bodyParams
     * @return Response
     */
    protected function sendRequest($method, $url, array $queryParams = [], array $bodyParams = [])
    {
        $_POST['_method'] = $method;

        \Yii::$app->getRequest()->setQueryParams($queryParams);
        \Yii::$app->getRequest()->setBodyParams($bodyParams);

        try {
            $output = \Yii::$app->runAction($url, $queryParams);
        } catch (\Exception $e) {
            \Yii::$app->errorHandler->handleException($e);
            $output = \Yii::$app->getResponse()->data;
        }
        return new Response($output);
    }
}
