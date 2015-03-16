<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 11/3/15
 * Time: 9:21 AM
 */

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->truncate();

        $user = new User;
        $user->name = 'Amitav Roy';
        $user->phoneNumber = '+919820098200';
        $user->email = 'amitav.roy@focalworks.in';
        $user->password = Hash::make('password');
        $user->userType = 'normal';
        $user->userId = '0';
        $user->save();
    }
}