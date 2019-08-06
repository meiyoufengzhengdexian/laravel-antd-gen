<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\BackendLoginRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackendLoginController extends BackendController
{
    public function login(BackendLoginRequest $request)
    {
        $userName = $request->input('username');
        $password = $request->input('password');


        $client = new Client();

        try {
            $res = $client->post(url('/oauth/token'), [
                'json' => [
                    'grant_type' => "password",
                    'client_id' => config('backend.clientId'),
                    "client_secret" => config('backend.clientSecret'),
                    "username" => $userName,
                    'password' => $password,
                    'scope' => 'backend'
                ]
            ]);


            $data = json_decode($res->getBody(), true);

            return $this->success($data);
        } catch (ClientException $exception) {
            $httpCode = $exception->getCode();
            switch ($httpCode) {
                case "401":
                    return $this->failed("用户名或密码错误");
                case "409":
                    return $this->failed("您没有权限");
                case "500":
                    return $this->failed("服务器内部错误");
            }
        }
    }

    public function currentUser(Request $request)
    {
        $user = Auth::user();
        $permissions = $user->abilities()->get();
        if ($permissions) {
            $permissions = $permissions->pluck('name');
        } else {
            $permissions = [];
        }

        return $this->success([
            'user' => $user,
            'permissions' => $permissions
        ]);
    }
}
