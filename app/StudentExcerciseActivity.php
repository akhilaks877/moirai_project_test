<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExcerciseActivity extends Model
{
    //
    protected $table = 'student_exercise_activity_sessions';
    protected $fillable = ['student_id','exercise_id'];
    public $timestamps = false;

}
