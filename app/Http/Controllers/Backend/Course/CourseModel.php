<?php
namespace App\Http\Controllers\Backend\Course;
use App\Http\Controllers\Backend\Teacher\TeacherModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;


class CourseModel extends BackendModel
{
    protected $table = 'course';
    use SoftDeletes;

    public function getTeacher()
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id')->withDefault(['id'=> '', 'name'=> '']);
    }
}
