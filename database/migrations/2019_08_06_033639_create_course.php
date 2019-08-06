<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false)->comment('课程名');
            $table->integer('teacher_id')->default(0)->comment('教师id');
            $table->integer('max_student')->default(0)->comment('最多学生数量');
            $table->integer('now_student')->default(0)->comment('当前学生数量');
            $table->dateTime('start_at')->default(null)->comment('开始上课时间');
            $table->dateTime('end_at')->default(null)->comment('结束上课时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course');
    }
}
