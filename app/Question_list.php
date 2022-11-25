<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question_list extends Model
{
    protected $table='question_lists';

    protected $fillable = ['question_data','answer_format','question_type','excercise_id','education_element','order_num','deleted_at'];
}
