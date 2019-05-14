<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-4
 * Time: 下午8:19
 */

namespace App\Http\Resources;


use Illuminate\Http\Request;

class ErrorResource extends BasicResource
{
    public $errorMessage = "Unknown Error";
    public $errorCode = "0";

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }


    /**
     * @param string $errorMessage
     * @return ErrorResource
     */
    public function setErrorMessage(string $errorMessage): ErrorResource
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     */
    public function setErrorCode(string $errorCode): void
    {
        $this->errorCode = $errorCode;
    }


    /**
     * @return array
     */
    public function with($request)
    {
        return [
            'result'=>[
                'code'=> $this->getErrorCode(),
                'message'=>$this->getErrorMessage()
            ]
        ];
    }
}