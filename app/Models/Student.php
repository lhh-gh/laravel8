<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student';                               # 绑定数据表
    public $timestamps = false;                                 # 不创建时间
    protected $primaryKey = 'id';

}
