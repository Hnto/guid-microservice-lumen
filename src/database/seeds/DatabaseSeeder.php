<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'name' => 'test',
            'email' => 'testuser@test',
            'api_key' => bin2hex(random_bytes(10)),
        ]);
    }
}
