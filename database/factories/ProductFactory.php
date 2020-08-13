<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $filePath = public_path('storage/images');
    return [
        'user_id' => function(){
            return User::all()->random();
        },
        'type' => $faker->randomElement(['product', 'service']),
        'name' => $faker->word,
        'desc' => $faker->paragraph,
        'photo' => $faker->image($filePath, 400, 300),
    ];
});
