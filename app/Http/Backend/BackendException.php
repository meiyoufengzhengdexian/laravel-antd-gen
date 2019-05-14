<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午10:04
 */

namespace App\Http\Backend;


use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class BackendException extends \Exception implements Responsable
{
    public function toResponse($request)
    {
        return new JsonResponse([
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => []
        ], 200, [], 256);
    }
}