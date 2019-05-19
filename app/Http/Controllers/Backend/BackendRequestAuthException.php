<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午10:08
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\JsonResponse;

class BackendRequestAuthException extends BackendException
{
    protected $code;

    public function toResponse($request)
    {
        return new JsonResponse([
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => []
        ], 401, [], 256);
    }
}