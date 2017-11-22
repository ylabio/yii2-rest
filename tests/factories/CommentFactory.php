<?php
/** @var League\FactoryMuffin\FactoryMuffin $this */

use League\FactoryMuffin\Faker\Facade as Faker;
use tests\data\models\Author;
use tests\data\models\Comment;
use tests\data\models\Post;

$this->define(Comment::class)->setDefinitions([
    'text' => Faker::text(),
    'post_id' => function () {
        return $this->create(Post::class)->id;
    },
    'author_id' => function () {
        return $this->create(Author::class)->id;
    },
]);
