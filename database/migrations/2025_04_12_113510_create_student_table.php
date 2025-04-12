<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTable extends Migration
{
    /**
     * 运行迁移程序
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',30)->comment("姓名");
            $table->tinyInteger('sex')->comment('性别');
            // 生日
            $table->date('birthday')->comment('生日');
            // 入学年份
            $table->year('year')->comment('入校年份');
           //   班级
            $table->string('class',30)->comment('班级');
            $table->timestamps();


        });
    }

    /**
     * 回滚迁移
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student');
    }
}
