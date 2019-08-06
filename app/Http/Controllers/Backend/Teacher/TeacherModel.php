<?php
namespace App\Http\Controllers\Backend\Teacher;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;


class TeacherModel extends BackendModel
{
    protected $table = 'teacher';
    use SoftDeletes;

        
        
}
