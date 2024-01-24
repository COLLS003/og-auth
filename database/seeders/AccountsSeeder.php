<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accounts;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // using the faker to generate some fake data ..
        $faker = \Faker\Factory::create();

        // generate like 10 fake data
        for ($i = 0; $i < 50; $i++) {
            Accounts::create([
                'name' => $faker->name,
                'type' => $faker->randomElement(['Advertiser', 'Publisher'])
            ]);
        }
    }
}
