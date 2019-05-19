<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-11
 * Time: 下午3:47
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class BackendPermissionException extends BackendException implements Responsable
{
    public function toResponse($request)
    {
        return new JsonResponse([
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => []
        ], 409, [], 256);
    }
}