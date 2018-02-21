<?php

namespace LaraSupport;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Config;

class LaraPassword
{
    /**
     * @param $password
     * @return bool|string
     */
    public function hashPassword($password)
    {
        // hash to remove initial bcrypt restriction
        // @link https://security.stackexchange.com/a/6627/38200
        $password = hash('sha256', $password);
        $cost = Config::get('lara_support.security.password.cost', 12);
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
        return encrypt($hash);
    }

    /**
     * @param $password
     * @param $passwordHash
     * @return bool
     * @throws \Exception
     */
    public function verifyPassword($password, $passwordHash)
    {
        try {
            $hash = decrypt($passwordHash);
        } catch (DecryptException $e) {
            return false;
        }

        $password = hash('sha256', $password);
        return password_verify($password, $hash);
    }
}
