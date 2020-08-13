<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UserSeeder::class, 5);
        factory(App\User::class, 2)->create();
        factory(App\Model\Product::class, 2)->create();
    }
}
