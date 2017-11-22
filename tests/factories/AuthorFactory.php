<?php
/** @var League\FactoryMuffin\FactoryMuffin $this */

use League\FactoryMuffin\Faker\Facade as Faker;
use tests\data\models\Author;

$this->define(Author::class)->setDefinitions([
    'name' => Faker::name(),
    'age' => Faker::numberBetween(18, 70),
    'status' => Faker::boolean(),
]);
