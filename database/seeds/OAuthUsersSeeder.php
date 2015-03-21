<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 11/3/15
 * Time: 9:21 AM
 */

class OAuthUsersSeeder extends Seeder {
    public function run()
    {
        DB::table('oauth_users')->insert(array(
            'username' => "focalworks",
            'password' => sha1('password'),
            'first_name' => "Focalworks",
            'last_name' => "Dev",
        ));
    }
}