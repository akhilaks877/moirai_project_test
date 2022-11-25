<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExerciseMark extends Model
{
    //

    protected $table = "student_exercise_mark";
    protected $fillable = ["student_id","exercise_id","status","mark","question_count","question_attended_count"];
}
