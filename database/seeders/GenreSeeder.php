<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            'nama_genre' => 'ACTION-RPG',
        ]);
        DB::table('genres')->insert([
            'nama_genre' => 'RPG',
        ]);
        DB::table('genres')->insert([
            'nama_genre' => 'RACING',
        ]);
        DB::table('genres')->insert([
            'nama_genre' => 'HORROR',
        ]);
        DB::table('genres')->insert([
            'nama_genre' => 'SIMULATOR',
        ]);
        DB::table('genres')->insert([
            'nama_genre' => 'FIGHTING',
        ]);
    }
}
