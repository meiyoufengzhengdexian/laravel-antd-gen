<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-12
 * Time: 上午1:26
 */

namespace App\Http\Backend;


use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;

trait BackendResource
{
    public function success($data=[])
    {
        return new SuccessResource($data);
    }

    public function failed($errorMessage, $errorCode = 0)
    {
        $errorResource = new ErrorResource([]);
        $errorResource->setErrorMessage($errorMessage);
        $errorResource->setErrorCode($errorCode);
        return $errorResource;
    }
}