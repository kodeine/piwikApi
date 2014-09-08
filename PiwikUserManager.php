<?php namespace Piwik;

use \Config;
use \Session;

/**
 * Class Piwik\UserManager
 * @module UsersManager
 */
class UserManager extends API
{
    public function addUser($userLogin, $password, $format = null)
    {
        return $this->getResponse([
            'method' => 'UsersManager.addUser',
            'userLogin' => $userLogin,
            'password' => $password,
            'email=' => 'null@null.com',
            'alias' => 'admin',
            'format' => $format,
        ]);
    }

    public function updateUser($userLogin, $password, $format = null)
    {
        return $this->getResponse([
            'method' => 'UsersManager.updateUser',
            'userLogin' => $userLogin,
            'password' => $password,
            'email=' => 'null@null.com',
            'alias' => 'admin',
            'format' => $format,
        ]);
    }

    public function deleteUser($userLogin, $format = null)
    {
        return $this->getResponse([
            'method' => 'UsersManager.deleteUser',
            'userLogin' => $userLogin,
            'format' => $format,
        ]);
    }

    public function userExists($userLogin, $format = null)
    {
        return $this->getResponse([
            'method' => 'UsersManager.userExists',
            'userLogin' => $userLogin,
            'format' => $format,
        ]);
    }

    public function getUser($userLogin, $format = null)
    {
        return $this->getResponse([
            'method' => 'UsersManager.getUser',
            'userLogin' => $userLogin,
            'format' => $format,
        ]);
    }

    public function getUsers($userLogins = '', $format = null)
    {
        return $this->getResponse([
            'method' => 'UsersManager.getUsers',
            'userLogins' => $userLogins,
            'format' => $format,
        ]);
    }

    public function getTokenAuth($userLogin, $md5Password, $format = null)
    {
        return $this->getResponse([
            'method' => 'UsersManager.getTokenAuth',
            'userLogins' => $userLogin,
            'md5Password' => $md5Password,
            'format' => $format,
        ]);
    }
}