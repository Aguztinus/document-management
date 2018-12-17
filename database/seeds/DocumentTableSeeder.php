<?php

use Illuminate\Database\Seeder;

class DocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\DocumentNum::class, 50)->create();
        factory(App\Document::class, 50)->create();

        $doc = App\Document::all();

        // Populate the pivot table
        App\User::all()->each(function ($user) use ($doc) { 
            $user->document()->attach(
                $doc->random(rand(1, 15))->pluck('id')->toArray()
            ); 
        });

        App\Document::all()->each(function ($docku) use ($doc) { 
            $docku->reference()->attach(
                $doc->random(rand(1, 5))->pluck('id')->toArray()
            ); 
        });

        App\Document::all()->each(function ($dochis) use ($doc) { 
            $dochis->history()->attach(
                $doc->random(rand(1, 2))->pluck('id')->toArray()
            ); 
        });
    }
}
