<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-4
 * Time: 下午7:54
 */

namespace App\Service\Users;


class UserService
{
    public function createUser($userName, $phone, $password, $email="")
    {
        $users = new UsersModel();
        $users->name = $userName;
        $users->phone = $phone;
        $users->password = bcrypt($password);
        $users->email = $email;
        $users->save();
        return $users;
    }
}