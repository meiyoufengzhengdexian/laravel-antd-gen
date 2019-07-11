<?php
namespace App\Http\Controllers\Backend\Test;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;

                
class TestModel extends BackendModel
{
    protected $table = 'test';
    use SoftDeletes;
}