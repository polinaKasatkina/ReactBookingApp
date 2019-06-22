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

        \App\Models\User::create([
            'first_name' => 'Pundarik',
            'last_name'  => 'Ranchhod',
            'email'      => 'pundarik.ranchhod@arqino.com',
            'password'   => bcrypt('viNQ7NuVHRnVTYBT'),
            'role_id'    => 1
        ]);

        \App\Models\User::create([
            'first_name' => 'John',
            'last_name'  => 'Durno',
            'email'      => 'john.durno@arqino.com',
            'password'   => bcrypt('CRToAaL8ZCUFaCnn'),
            'role_id'    => 1
        ]);

        \App\Models\User::create([
            'first_name' => 'Peter',
            'last_name'  => 'van\'t Hoogerhuys',
            'email'      => 'peter.vanthoogerhuys@arqino.com',
            'password'   => bcrypt('bzrKqoB7fJwriJaQ'),
            'role_id'    => 1
        ]);
    }
}
