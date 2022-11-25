<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentRequest extends Model
{
    //
    protected $table = 'student_request';
    protected $fillable = ['class_id','student_id','teacher_id','status'];
}
