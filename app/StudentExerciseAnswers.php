<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExerciseAnswers extends Model
{
    //
    protected $table = 'student_excercise_answers';
    protected $fillable = ['student_id','exercise_id','question','answer'];
}
