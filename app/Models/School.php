<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\School;

class School extends Model
{
    // protected $table='schools';
	use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'school_name',
		'school_street',
		'school_city',
		'school_zipcode',
		'school_country',
		'school_province',
		'contact_last_name',
		'contact_first_name',
		'contact_email',
		'contact_phone',
		'created_by',
		'updated_by',
		'deleted_at',
    ];

	public function created_user(){
        return $this->belongsTo(User::class,'created_by','id')->with('roles');
    }

	public function updated_user(){
        return $this->belongsTo(User::class,'updated_by','id')->with('roles');
    }

	// public function teachers()
	// {
	// 	return $this->hasManyThrough(
    //         Teacher::class,
    //         ClassModel::class,
    //         'school_id', // Foreign key on users table...
    //         'teacher_id', // Foreign key on posts table...
    //         'id', // Local key on countries table...
    //         'id' // Local key on users table...
    //     );
    // }
	public function classes(){
	   return $this->hasMany(ClassModel::class,'school_id','id');
    }

    // public function teachers(){
    //     return $this->hasMany(ClassModel::class,'school_id','id')->select('school_id','teacher_id',DB::raw('count(*) as total'))
    //     ->groupBy('teacher_id');
    //  }
}
