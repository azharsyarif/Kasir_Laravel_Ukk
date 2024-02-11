<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('products')->insert([
        //     'nama_product' => 'PERSONA 3 RELOAD',
        //     'image' => 'https://assets-prd.ignimgs.com/2024/01/05/persona3reload-1704483016718.jpg',
        //     'platform' => 'PS5',
        //     'genre' => 'RPG',
        //     'harga' => 500000,
        // ]);
        DB::table('products')->insert([
            'nama_product' => 'GRAN TURISMO 7',
            'image' => 'https://image.api.playstation.com/vulcan/ap/rnd/202109/1321/yZ7dpmjtHr1olhutHT57IFRh.png',
            'platform' => 'PS5',
            'genre' => 'RACING',
            'harga' => 1000000,
        ]);
    }
}
