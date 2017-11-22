<?php

namespace tests;

use tests\data\models\Comment;
use tests\data\models\Post;
use tests\data\models\Tag;
use yii\web\Response;

/**
 * @inheritdoc
 */
class SimpleActionsTest extends TestCase
{
    /**
     * @inheritdoc
     */
    protected function factories()
    {
        return [
            Post::class,
            Tag::class,
            Comment::class,
        ];
    }

    /**
     * Tests IndexAction without filters
     */
    public function testIndex()
    {
        $response = $this->sendGetRequest('/simple-post/index');
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertCount((int)Post::find()->count(), $response->data['data']);
    }

    /**
     * Tests ViewAction without filters
     */
    public function testView()
    {
        $post = Post::find()->one();
        $response = $this->sendGetRequest('/simple-post/view', ['id' => $post->id]);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertEquals($post->toArray(), $response->data['data']);
    }

    /**
     * Tests CreateAction without filters
     */
    public function testCreate()
    {
        $post = $this->fm->instance(Post::class);
        $count = Post::find()->count();
        $response = $this->sendPostRequest('/simple-post/create', [], $post->toArray());
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        unset($response->data['data']['id']);
        $this->assertEquals($post->toArray(), $response->data['data']);
        $this->assertEquals($count + 1, Post::find()->count());

        $post = $this->fm->instance(Post::class);
        unset($post->text);
        $count = Post::find()->count();
        $response = $this->sendPostRequest('/simple-post/create', [], $post->toArray());
        $this->assertEquals(422, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertNotEmpty($response->data['errors']);
        $this->assertEquals($count, Post::find()->count());
    }

    /**
     * Tests UpdateAction without filters
     */
    public function testUpdate()
    {
        $post = Post::find()->one();
        $newPost = $this->fm->instance(Post::class);
        $count = Post::find()->count();
        $response = $this->sendPutRequest('/simple-post/update', ['id' => $post->id], $newPost->toArray());
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertEquals($count, Post::find()->count());
        $this->assertNotEquals($post->toArray(), $response->data['data']);
        $post->setAttributes($newPost->toArray());
        $this->assertEquals($post->toArray(), $response->data['data']);

        $newPost = $this->fm->instance(Post::class);
        $newPost->author_id = 'test';
        $response = $this->sendPutRequest('/simple-post/update', ['id' => $post->id], $newPost->toArray());
        $this->assertEquals(422, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertNotEmpty($response->data['errors']);
    }

    /**
     * Tests DeleteAction without filters
     */
    public function testDelete()
    {
        $post = Post::find()->one();
        $count = Post::find()->count();
        $response = $this->sendDeleteRequest('/simple-post/delete', ['id' => $post->id]);
        $this->assertEquals(204, $response->statusCode);
        $this->assertEquals($count - 1, Post::find()->count());
    }

    /**
     * Tests LinkAction without filters
     */
    public function testLink()
    {
        $post = Post::find()->one();
        $comment = $this->fm->create(Comment::class);
        $comment2 = $this->fm->create(Comment::class);
        $commentsCount = $post->getComments()->count();
        $response = $this->sendPostRequest(
            '/simple-post/link',
            ['id' => $post->id],
            ['comments' => [$comment->toArray(), $comment2->toArray()]]
        );
        $this->assertEquals(204, $response->statusCode);
        $this->assertEquals($commentsCount + 2, $post->getComments()->count());

        $tag = $this->fm->create(Tag::class);
        $post->link('tags', $tag);
        $response = $this->sendPostRequest('/simple-post/link', ['id' => $post->id], ['tags' => $tag->id]);
        $this->assertEquals(400, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertNotEmpty($response->data['errors']);

        $response = $this->sendPostRequest('/simple-post/link', ['id' => $post->id], ['tag' => $tag->id]);
        $this->assertEquals(400, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertNotEmpty($response->data['errors']);
    }

    /**
     * Tests UnlinkAction without filters
     */
    public function testUnlink()
    {
        $tag = $this->fm->create(Tag::class);
        $post = Post::find()->one();
        $post->link('tags', $tag);
        $tagsCount = $post->getTags()->count();
        $response = $this->sendPostRequest('/simple-post/unlink', ['id' => $post->id], ['tags' => [$tag->id]]);
        $this->assertEquals(204, $response->statusCode);
        $this->assertEquals($tagsCount - 1, $post->getTags()->count());

        $response = $this->sendPostRequest('/simple-post/unlink', ['id' => $post->id], ['tags' => 'test']);
        $this->assertEquals(400, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertNotEmpty($response->data['errors']);
    }

    /**
     * Tests CreateAction with related models
     */
    public function testCreateWithRelation()
    {
        $post = $this->fm->instance(Post::class);
        $tag = $this->fm->instance(Tag::class);
        $postCount = Post::find()->count();
        $tagCount = Tag::find()->count();
        $data = array_merge($post->toArray(), ['tags' => [$tag->toArray()]]);
        $response = $this->sendPostRequest('/simple-post/create', [], $data);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        unset($response->data['data']['id']);
        $this->assertEquals($post->toArray(), $response->data['data']);
        $this->assertEquals($postCount + 1, Post::find()->count());
        $this->assertEquals($tagCount + 1, Tag::find()->count());

        $post = $this->fm->instance(Post::class);
        $tag = Tag::find()->one();
        $postCount = Post::find()->count();
        $tagCount = Tag::find()->count();
        $data = array_merge($post->toArray(), ['tags' => [$tag->toArray()]]);
        $response = $this->sendPostRequest('/simple-post/create', [], $data);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        unset($response->data['data']['id']);
        $this->assertEquals($post->toArray(), $response->data['data']);
        $this->assertEquals($postCount + 1, Post::find()->count());
        $this->assertEquals($tagCount, Tag::find()->count());
    }

    /**
     * Tests UpdateAction with related models
     */
    public function testUpdateWithRelation()
    {
        $post = Post::find()->one();
        $newPost = $this->fm->instance(Post::class);
        $comment = $this->fm->instance(Comment::class);
        $comment2 = Comment::find()->one();
        $postCount = Post::find()->count();
        $commentCount = Comment::find()->count();
        $data = array_merge($newPost->toArray(), ['comments' => [$comment->toArray(), $comment2->toArray()]]);
        $response = $this->sendPutRequest('/simple-post/update', ['id' => $post->id], $data);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals(Response::FORMAT_JSON, $response->format);
        $this->assertCount(3, $response->data);
        $this->assertArrayHasKey('success', $response->data);
        $this->assertArrayHasKey('data', $response->data);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertEquals($postCount, Post::find()->count());
        $this->assertEquals($commentCount + 1, Comment::find()->count());
        $this->assertNotEquals($post->toArray(), $response->data['data']);
        $post->setAttributes($newPost->toArray());
        $this->assertEquals($post->toArray(), $response->data['data']);

        $tag = new Tag();
        $data = array_merge($newPost->toArray(), ['tags' => [$tag->toArray()]]);
        $response = $this->sendPutRequest('/simple-post/update', ['id' => $post->id], $data);
        $this->assertEquals(422, $response->statusCode);
        $this->assertArrayHasKey('errors', $response->data);
        $this->assertNotEmpty($response->data['errors']);
    }
}
