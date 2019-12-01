<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'image' => $faker->name,
        'caption' => $faker->sentence,
        'owner_id' => factory(\App\User::class)
    ];
});
