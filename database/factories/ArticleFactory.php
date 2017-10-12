<?php

use App\Models\Article;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Article::class, function (Faker $faker) {

    return [
        'guid'         => $faker->uuid,
        'title'        => $faker->title,
        'author'       => $faker->name,
        'language'     => null,
        'publish_date' => $faker->dateTime,
        'updated_date' => $faker->dateTime,
        'content'      => $faker->randomHtml(),
        'description'  => $faker->text,
        'url'          => $faker->url
    ];
});
