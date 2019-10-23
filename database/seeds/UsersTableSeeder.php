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
        \App\Models\User::create([
            'first_name' => 'Polina',
            'last_name'  => 'Kasatkina',
            'email'      => 'polina.kasatkina@arqino.com',
            'password'   => bcrypt('PhKMaPNWp'),
            'role_id'    => 1
        ]);

    }
}
