<?php
/** @var League\FactoryMuffin\FactoryMuffin $this */

use League\FactoryMuffin\Faker\Facade as Faker;
use tests\data\models\Tag;

$this->define(Tag::class)->setDefinitions([
    'name' => Faker::word(),
]);
