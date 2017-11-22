<?php
/** @var League\FactoryMuffin\FactoryMuffin $this */

use League\FactoryMuffin\Faker\Facade as Faker;
use tests\data\models\Author;
use tests\data\models\Post;

$this->define(Post::class)->setDefinitions([
    'text' => Faker::text(),
    'preview' => Faker::sentence(18, 70),
    'author_id' => function () {
        return $this->create(Author::class)->id;
    }
]);
