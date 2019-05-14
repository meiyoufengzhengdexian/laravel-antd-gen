<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\SuccessResource;
use App\Http\Resources\userResource;
use App\Service\Test;
use App\Service\Users\UserService;
use App\Service\Users\UsersModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;

class UsersController extends Controller
{

    public function show($id)
    {
        $user = UsersModel::find($id);
        return new userResource($user);
    }

    public function store(Request $request, UserService $userService)
    {
        $name =$request->input('name');
        $email = $request->input('email', "");
        $password =  $request->input('passwd');
        $phone = $request->input('phone');
        $userService->createUser($name, $phone, $password, $email);
        return new SuccessResource();
    }
}
