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
        $this->call(UnitsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(DocumentTypesTableSeeder::class);
        $this->call(DocumentTableSeeder::class);
        
    }
}
