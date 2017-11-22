<?php

namespace tests;

use tests\data\models\Post;
use tests\data\models\Tag;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * @inheritdoc
 */
class FiltersTest extends TestCase
{
    /**
     * @inheritdoc
     */
    protected function factories()
    {
        return [
            Post::class,
            Tag::class,
        ];
    }

    /**
     * Tests actions with ExpandFilter
     */
    public function testExpandFilter()
    {
        $id = Post::find()->select('id')->scalar();
        $response = $this->sendGetRequest('/post/view', ['id' => $id, 'expand' => 'comments']);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertArrayHasKey('comments', $response->data['data']);

        $response = $this->sendGetRequest('/post/view', ['id' => $id]);
        $this->assertEquals(200, $response->statusCode);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayNotHasKey('comments', $response->data['data']);

        $response = $this->sendGetRequest('/post/index', ['expand' => 'tags']);
        $this->assertEquals(400, $response->statusCode);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertNotEmpty($response->data['errors']);
    }

    /**
     * Tests actions with FieldsFilter
     */
    public function testFieldsFilter()
    {
        $id = Post::find()->select('id')->scalar();
        $response = $this->sendGetRequest('/post/view', ['id' => $id, 'fields' => 'id, text']);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertArrayHasKey('id', $response->data['data']);
        $this->assertArrayHasKey('text', $response->data['data']);
        $this->assertArrayNotHasKey('preview', $response->data['data']);
        $this->assertArrayNotHasKey('author_id', $response->data['data']);
    }

    /**
     * Tests IndexAction with SortFilter
     */
    public function testSortFilter()
    {
        $modelIds = Post::find()->select('id')->with('comments', function (ActiveQuery $query) {
            $query->addOrderBy(['id' => SORT_ASC]);
        })->orderBy(['id' => SORT_DESC])->column();
        $response = $this->sendGetRequest('/post/index', ['sort' => '-id, comment.id']);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertEquals($modelIds, ArrayHelper::getColumn($response->data['data'], 'id'));

        $response = $this->sendGetRequest('/post/index');
        $this->assertEquals(200, $response->statusCode);
    }

    /**
     * Tests IndexAction with ConditionFilter
     */
    public function testConditionFilter()
    {
        $preview = Post::find()->one()->preview;
        $count = (int)Post::find()->where(['preview' => $preview])->count();
        $response = $this->sendGetRequest('/post/index', ['preview' => $preview]);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertCount($count, $response->data['data']);
    }
}
