<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Faker\Provider\en_US\PhoneNumber;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 11/3/15
 * Time: 9:21 AM
 */

class UserTableSeeder extends Seeder {

    /**
     *
     */
    public function run()
    {
//        DB::table('users')->truncate();
        $role =array('agent', 'client','anonymous');

        $user = new User;
        $user->name = 'Amitav Roy';
        $user->phone_number = '+919820098200';
        $user->email = 'amitav.roy@focalworks.in';
        $user->password = Hash::make('password');
        $user->user_type = 'normal';
        $user->uid = '0';
        $user->role = $role[0];
        $user->save();

        $user = new User;
        $user->name = 'Kaustubh Malgaonkar';
        $user->phone_number = '+919830098300';
        $user->email = 'kaustubh.malgaonkar@focalworks.in';
        $user->password = Hash::make('password');
        $user->user_type = 'normal';
        $user->uid = '0';
        $user->role = $role[0];
        $user->save();

        $role =array('agent', 'client','anonymous');

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 49; $i++)
        {
            $number = $faker->numberBetween('9820098200', '9820099900');
            $user = new User;
            $user->name = $faker->userName;
            $user->phone_number = '+91' . $number;
            $user->email = $faker->email;
            $user->password = Hash::make('password');
            $user->user_type = $faker->randomElement(array('normal', 'facebook', 'google'));
            $user->uid = '0';
            $user->role = $role[array_rand($role, 1)];
            $user->save();
        }
    }
}