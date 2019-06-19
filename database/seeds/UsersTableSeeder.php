<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'role_id'=>8,
                'port_id'=>1,
                'name'=>'WareHouse1',
                'username'=>'WareHouse1',
                'email'=>'WareHouse1@gmail.com',
                'password'=>bcrypt('WareHouse1'),
                'remember_token'=>str_random(10),
                'active'=>1,

            ]
            );
    }



}
