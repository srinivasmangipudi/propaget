<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 11/3/15
 * Time: 9:21 AM
 */

class OAuthClientsSeeder extends Seeder {
    public function run()
    {
        DB::table('oauth_clients')->insert(array(
            'client_id' => "testclient",
            'client_secret' => "testpass",
            'redirect_uri' => "http://fake/",
        ));
    }
}