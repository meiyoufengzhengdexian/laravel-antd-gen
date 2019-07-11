<?php
namespace App\Http\Controllers\Backend\Test\Request;



use App\Http\Controllers\Backend\BackendRequest;

class TestShowRequest extends BackendRequest
{
    public function authorize()
    {
        return true;
    }
}