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
        // talleres
        DB::table('work_shops')->insert([
            'code' => '001',
            'name' => 'Refriauto',
            'address' => '',
        ]);

        DB::table('work_shops')->insert([
            'code' => '002',
            'name' => 'Taller Gallegos',
            'address' => '',
        ]);

        // users
        DB::table('users')->insert([
            'name' => 'Admin',
            'workshop_id' => 1,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('1234'),
        ]);
    }
}
