<?php

use Illuminate\Database\Seeder;

class WorkShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('work_shops')->insert([
            'code' => '001',
            'name' => 'Refri Auto',
            'address' => '',
        ]);

        DB::table('work_shops')->insert([
            'code' => '002',
            'name' => 'Taller Gallegos',
            'address' => '',
        ]);
    }
}
