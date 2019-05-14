<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-4
 * Time: 下午8:19
 */

namespace App\Http\Resources;



class SuccessResource extends BasicResource
{
    /**
     * @param $request
     * @return array
     */
    public function with($request)
    {
        return [
            'result'=>[
                'code'=> "1",
                'message'=> "OK"
            ]
        ];
    }

}