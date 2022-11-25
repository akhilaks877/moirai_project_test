<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\StudentClass;
//use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    protected $table='classes';
    
    //use SoftDeletes;

    //protected $dates = ['deleted_at'];

    protected $fillable = [
        'class_name','slug','created_by','school_id','teacher_id','updated_by','display_answer',
    ];

	public function created_user(){
        return $this->belongsTo(User::class,'created_by','id')->with('roles');
    }

	public function updated_user(){
        return $this->belongsTo(User::class,'updated_by','id')->with('roles');
    }

	public function school(){
        return $this->belongsTo(School::class,'school_id','id');
    }

	public function teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id','id');
    }

	public function students(){
	   return $this->hasMany(StudentClass::class,'class_id','id')->with('student');
    }

    public function student_lists(){
        return $this->belongsToMany(Student::class, 'student_class', 'class_id', 'student_id');
     }

    public function book_suggestions(){
        return $this->belongsToMany('App\Book', 'class_book_suggestions', 'class_id', 'book_id');
    }

}
