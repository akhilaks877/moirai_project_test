<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book_exercise extends Model
{
    protected $table='book_exercises';

    protected $fillable = ['title','exercise_type','book_id','chapter_id','illu_img','completion_time','user_id'];
}
