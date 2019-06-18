<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-10
 * Time: 下午10:04
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class BackendException extends \Exception implements Responsable
{
    private $data = [];
    public function setData($data)
    {
        $this->data = $data;
    }
    public function toResponse($request)
    {
        return new JsonResponse([
            'result'=>[
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
            ],
            'data' => $this->data
        ], 200, [], 256);
    }
}