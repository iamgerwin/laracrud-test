<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->unique()->safeEmail,
        'logo' => UploadedFile::fake()->image(
            storage_path('app/public'),
            100,
            100,
            null,
            false
        ),
        'website' => $faker->url,
    ];
});
