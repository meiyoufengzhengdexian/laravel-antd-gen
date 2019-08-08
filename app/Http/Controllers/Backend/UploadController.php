<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 2019/8/7
 * Time: 9:47 PM
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;

class UploadController extends BackendController
{
    public function upload(Request $request)
    {
        $file = $request->file('upload');
        $res = $file->store(date('Ymd'), 'public');
        return $this->success([
            'url' => url('/upload/'.$res)
        ]);
    }
}
