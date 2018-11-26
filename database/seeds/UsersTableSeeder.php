<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 150)->create([
            'unit_id' => $this->getRandomUnitId()
        ]);
    }

    private function getRandomUnitId() {
        $unit = \App\Units::inRandomOrder()->first();
        return $unit->id;
    }
}
