<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OAuth2\Storage\Pdo;

class DoLoginPdo extends Pdo
{
    public function __construct($connection, $config = array())
    {
        parent::__construct($connection, $config);
        $this->config['user_table'] = 'users';
    }

    public function getUser($username)
    {
        $stmt = $this->db->prepare($sql = sprintf('SELECT * from %s where email=:username', $this->config['user_table']));
        $stmt->execute(array('username' => $username));

        if (!$userInfo = $stmt->fetch(\PDO::FETCH_BOTH)) {
            return false;
        }

        // the default behavior is to use "username" as the user_id
        return array_merge(array(
            'user_id' => $username
        ), $userInfo);
    }

    /* OAuth2\Storage\UserCredentialsInterface */
    public function checkUserCredentials($email, $password)
    {
        if (Auth::validate(array('email' => $email, 'password' => $password)))
        {
            return true;
        }

        return false;
    }

}