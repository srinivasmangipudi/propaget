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
        DB::table('users')->truncate();

        $user = new User;
        $user->name = 'Amitav Roy';
        $user->phoneNumber = '+919820098200';
        $user->email = 'amitav.roy@focalworks.in';
        $user->password = Hash::make('password');
        $user->userType = 'normal';
        $user->userId = '0';
        $user->save();

        $user = new User;
        $user->name = 'Kaustubh Malgaonkar';
        $user->phoneNumber = '+919830098300';
        $user->email = 'kaustubh.malgaonkar@focalworks.in';
        $user->password = Hash::make('password');
        $user->userType = 'normal';
        $user->userId = '0';
        $user->save();

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 49; $i++)
        {
            $number = $faker->numberBetween('9820098200', '9820099900');
            $user = new User;
            $user->name = $faker->userName;
            $user->phoneNumber = '+91' . $number;
            $user->email = $faker->email;
            $user->password = Hash::make('password');
            $user->userType = $faker->randomElement(array('normal', 'facebook', 'google'));
            $user->userId = '0';
            $user->save();
        }
    }
}