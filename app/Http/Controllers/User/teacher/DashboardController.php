<?php

namespace App\Http\Controllers\User\teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\StudentController;
use Illuminate\Http\Request;
use Auth;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\StudentClass;
use App\StudentExcerciseActivity;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Auth::user()->id);
        return view('User.teacher.admin.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function statistics()
    {
        //dd(Auth::user()->id);
       $data = [];
       $classes = ClassModel::get();
       $classList = ClassModel::pluck('class_name','id')->toArray();
       $data['class'] = $classList;
       $resA = [];
       $data['invited'] = [];
       $data['subscribed'] = [];
      foreach($classes as $c)
       {
            $invited = StudentClass::where(['class_id' => $c->id,'status' => '0'])->count();
            $subscribed = StudentClass::where(['class_id' => $c->id,'status' => '1'])->count();
            $resA['id'] = $c->id;
            $resB['id'] = $c->id;
            $resA['invited_count'] = $invited;
            $resA['subscribed_count'] = $subscribed;
            $resA['class_name'] = $c->class_name;
            array_push($data['invited'],$resA);
            array_push($data['subscribed'],$resA);

       }
        $user_id = Auth::user()->id;
        $teacher_id = Teacher::where('user_id',$user_id)->pluck('id')->first();
        $class_count = ClassModel::where('teacher_id',$teacher_id)->count();
        $class = ClassModel::where('teacher_id',$teacher_id)->pluck('id');
        //dd($class->toArray());
        $student_count = 0;
        $students_excercise_count = 0;
        $arr = [];
        //dd($student_id);
        for($i=0;$i<count($class);$i++)
        {
            $class_id = $class[$i];
            $students = StudentClass::where(['class_id' => $class_id,'is_deleted'=>'0'])->count();
            $student_count = $student_count + $students;
           
            $student_id = StudentClass::where(['class_id'=>$class_id,'is_deleted'=>'0'])->distinct()->pluck('student_id');
            $stud_array = $student_id->toArray();
           /* dd($stud_array);*/
            array_push($stud_array,$arr);
            for($j=0;$j<count($student_id);$j++)
            {
                if(array_search($student_id[$j],$arr) == 0)
                {
                $student_exercise = StudentExcerciseActivity::where(['student_id' => $student_id[$j],'examination_status' => '1' ])->count();
          
            $students_excercise_count = $students_excercise_count+$student_exercise;
            array_push($arr,$student_id[$j]);
            }
        }
           

        }
       //dd($students_excercise_count);
        $data = json_encode($data,JSON_NUMERIC_CHECK);
        return view('User.teacher.statistics',compact('class_count','student_count','students_excercise_count','data'));
    }
}
