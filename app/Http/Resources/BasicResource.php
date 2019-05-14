<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-4
 * Time: 下午8:19
 */

namespace App\Http\Resources;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicResource extends JsonResource
{
    public function withResponse($request, $response)
    {
        $data = $response->getData();
        if(isset($data->data) && is_array($data->data) && empty($data->data)){
            $data->data = new \stdClass();
            $response->setData($data);
        }
    }
}