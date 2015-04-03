<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Faker\Provider\en_US\PhoneNumber;
use App\UserProfile;

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
        $user->role = $role[0];
        $user->save();

        $userId = $user->id;
        $userProfile = new UserProfile();
        $userProfile->user_id = $userId;
        $userProfile->user_type = 'normal';
        $userProfile->save();

        $user = new User;
        $user->name = 'Kaustubh Malgaonkar';
        $user->phone_number = '+919830098300';
        $user->email = 'kaustubh.malgaonkar@focalworks.in';
        $user->password = Hash::make('password');
        $user->role = $role[0];
        $user->save();

        $userId = $user->id;
        $userProfile = new UserProfile();
        $userProfile->user_id = $userId;
        $userProfile->user_type = 'normal';
        $userProfile->save();


        $faker = Faker\Factory::create();

        for ($i = 0; $i < 49; $i++)
        {
            $number = $faker->numberBetween('9820098200', '9820099900');
            $user = new User;
            $user->name = $faker->userName;
            $user->phone_number = '+91' . $number;
            $user->email = $faker->email;
            $user->password = Hash::make('password');
            $user->role = $role[array_rand($role, 1)];
            $user->save();

            $userId = $user->id;
            $userProfile = new UserProfile();
            $userProfile->user_id = $userId;
            $userProfile->user_type = $faker->randomElement(array('normal', 'facebook', 'google'));
            $userProfile->save();
        }
    }
}