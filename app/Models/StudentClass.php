<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\School;
use App\Models\ClassModel;
use App\Models\Student;
//use Illuminate\Database\Eloquent\SoftDeletes;

class StudentClass extends Model
{

    //use SoftDeletes;

    //protected $dates = ['deleted_at'];

	protected $table='student_class';
	
    protected $fillable = [
        'student_id','class_id','created_by','is_deleted'
    ]; 
	
	public function created_user(){
        return $this->belongsTo(User::class,'created_by','id')->with('roles');
    }
	
	public function class_name(){
        return $this->belongsTo(ClassModel::class,'class_id','id')->with('teacher','school','students','book_suggestions');
    }
	
	public function student(){
        return $this->belongsTo(Student::class,'student_id','id');
    }
    
    

    public $timestamps = false;
}
