<?php

namespace App\Http\Controllers\Backend\Course;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;


class CourseModel extends BackendModel
{
    protected $table = 'course';
    use SoftDeletes;

    public function getTeacher()
    {
        return $this->belongsTo(\App\Http\Controllers\Backend\Teacher\TeacherModel::class, 'teacher_id', 'id');
    }


}
