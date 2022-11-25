<?php

namespace App\Http\Controllers\User\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Book;
use App\Models\School;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\StudentClass;
use App\StudentExcerciseActivity;
use App\StudentExerciseMark;
use Validator;
use Illuminate\Validation\Rule;
use DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use Mail;
use DateTime;
use DateTimeZone;
class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->ajax()){
            $user_id=auth()->user()->id;
            $teacher_data=DB::table('teachers')->select('teachers.*','users.id as connectingid')->join('users','users.id','=','teachers.user_id')->whereRaw('teachers.user_id = ?',array($user_id))->first();
            $teacher_id=isset($teacher_data) ? $teacher_data->id : 0;
            $data=[];
            $data=ClassModel::whereHas('teacher',function($q)use($teacher_id){
                $q->where('id',$teacher_id);
            })->with(['school'])->withCount('students')->get();
            //dd($data->toArray());
            return DataTables::of($data)
               ->addColumn('class_name', function($data){  $class=$data->class_name; return $class;})
               ->addColumn('school_name', function($data){
                   if(isset($data->school->school_name)){
                    $class=$data->school->school_name;
                   }else{
                    $class='-';
                   }
                    return $class;
                })
               ->addColumn('num_students', function($data){
                  $class=$data->students_count;
                  return $class;
                })
                ->addColumn('action', function($data){
                    $btn='<a href="'.route('teacher.my-classes.show',['my_class'=>$data->id]).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-eye pr-2"></span> Class Details</a>';
                    $btn.='<a href="'.route('teacher.my-classes.propose_book',['my_class'=>$data->id]).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-book pr-2"></span> Suggest a Book</a>';
                    return $btn;
                   })
               ->rawColumns(['action'])
               ->make(true);
        }
     return view('User.teacher.admin.my_classes');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     $school = School::pluck('school_name','id')->prepend('Select','');
      return view('User.teacher.admin.add_newclass',compact('school'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'class_name'=>['required',Rule::unique('classes','class_name','slug'),],
            'slug'=>'required|string|unique:classes',
            'select_school'=>'required|exists:schools,id',
        ],[]);
        if ($validator->fails()) { // validation fails
            $error = $validator->errors();
            return response()->json(['status'=>'invalid','messages'=>$error,'message'=>'Invalid Attempt']);
        }
        else
        {
            $display_answer = 0;
            if($request->has('display_answer'))
            $display_answer = 1;

            $user_id=auth()->user()->id;
            $request->merge(['display_answer' => $display_answer,'created_by' => $user_id ]);
            $teacher_data=DB::table('teachers')->select('teachers.*','users.id as connectingid')->join('users','users.id','=','teachers.user_id')->whereRaw('teachers.user_id = ?',array($user_id))->first();
            $teacher_id=isset($teacher_data) ? $teacher_data->id : 0;
            $new = ClassModel::create([
             'class_name'=>$request->input('class_name'),
             'slug'=>$request->input('slug'),
             'school_id'=>$request->input('select_school'),
             'teacher_id'=>$teacher_id,
             'display_answer'=>$request->input('display_answer'),
             'created_by'=>$request->input('created_by'),
            ]);
            $message='success';
            return response()->json(['status'=>'success','message'=>$message,'data'=>$new]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {

        $model = ClassModel::withCount(['students'])->findOrFail((int)$id);

        
        $students = Student::select(DB::raw("CONCAT(last_name,' ',first_name) AS name"),'id')->pluck('name', 'id');


/*$data=DB::table('students')->select('students.*','student_class.student_id as studid','student_class.status as status','student_class.class_id as classid')->join('student_class','students.id','=','student_class.student_id')
                 ->where('student_class.class_id',$model->id)
                 ->whereExists(function ($query) use ($model){
                   $query->select(DB::raw(1))
                      ->from('student_class')
                      ->whereRaw('student_class.student_id = students.id')
                      ->whereRaw('student_class.class_id = ?',array($model->id));
                    })->get();
dd($data->toArray());*/
          if($request->ajax()){
            $data=[];


            $data=DB::table('students')->select('students.*','student_class.student_id as studid','student_class.status as status','student_class.class_id as classid')->join('student_class','students.id','=','student_class.student_id')
                 ->where('student_class.class_id',$model->id)
                 ->whereExists(function ($query) use ($model){
                   $query->select(DB::raw(1))
                      ->from('student_class')
                      ->whereRaw('student_class.student_id = students.id')
                      ->whereRaw('student_class.class_id = ?',array($model->id));
                    })->get();




           
            return DataTables::of($data)
                  ->addColumn('email', function($data){  $student='<a href="javascript:void(0);">'.$data->email.'</a>'; return $student;})
                  ->addColumn('student_name', function($data){ $student='<a href="javascript:void(0);">'.$student=$data->first_name.' '.$data->last_name.'</a>'; return $student;})
                  ->addColumn('created_on', function($data){ $class='lk'; return $class;})
                  ->addColumn('status', function($data){ $student=($data->status == 1) ? 'Active' : 'Invitation Sent'; return $student;})
                   ->addColumn('action', function($data){
                       $status=$student=($data->status == 1) ? '' : 'disabled';
                       $student_name=$data->first_name.' '.$data->last_name;
                       $btn='<a href="'.route('teacher.my-classes.detail_student',['student_id'=>$data->studid,'class_id' => $data->classid]).'"  class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info '.$status.' "><span class="fa fa-eye pr-2"></span> See Student Activity</a>';
                       $btn.='<a href="javascript:void(0);" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info remove_studentclass" data-stdent="'.$student_name.'" data-trid="'.$data->id.'"><span class="fa fa-times-circle pr-2"></span> Remove Student from Class</a>';
                       return $btn;
                      })
                  ->rawColumns(['email','student_name','action'])
                  ->make(true);
          }

        $school = School::pluck('school_name','id')->prepend('Select','');
        $stud_ = StudentClass::select('student_id')->where('class_id',$model->id)->get()->toArray();
        $stud_class = [];
        foreach ($stud_ as $key => $value) {
            $stud_class[] = $value['student_id'];
        }
        return view('User.teacher.admin.class_details',compact('model','stud_class','school','students'));
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
        $validator = Validator::make($request->all(),[
            'class_entity'=> 'required|integer|in:'.$id,
            'class_name'=>['required',Rule::unique('classes','class_name','slug')->ignore($id),],
            'slug'=>'required|string',
            'select_school'=>'required|exists:schools,id',
        ],[]);
        if ($validator->fails()) { // validation fails
            $error = $validator->errors();
            return response()->json(['status'=>'invalid','messages'=>$error,'message'=>'Invalid Attempt']);
        }
        else
        {
            $display_answer = 0;
            if($request->has('display_answer'))
            $display_answer = 1;

            $user_id=auth()->user()->id;
            $request->merge(['display_answer' => $display_answer,'updated_by' => $user_id ]);
            $update = ClassModel::findOrFail($id);
            $update->class_name=$request->input('class_name');
            $update->slug=$request->input('slug');
            $update->school_id=$request->input('select_school');
            $update->display_answer=$request->input('display_answer');
            $update->updated_by=$request->input('updated_by');
            $newSave = $update->save();
            $message='updated';
            return response()->json(['status'=>'success','message'=>$message,'data'=>$newSave]);
        }
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

    public function show_invitation($id){
        // dd($id);
        $students = Student::all();
        // dd($students);exit;


        return view('User.teacher.admin.student_invitation',compact('id','students'));
    }

    public function add_student_toclass(Request $request){
        if($request->has('select_student') && $request->has('class_id'))
        {
            $validator = Validator::make($request->all(),[
                'class_id'=>'exists:classes,id',
                'select_student'=>'required|array',
                'select_student.*' => 'exists:students,id',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else
            {
                if(isset($request->select_student))
                {
                    foreach ($request->select_student as $key => $value) {

                        $new = StudentClass::where('student_id',$value)->where('class_id',$request->input('class_id'))->first();
                        if(empty($new)){
                            $new = new StudentClass();
                        }
                        $new->student_id = $value;
                        $new->class_id = $request->input('class_id');
                        $new->created_by =  auth()->user()->id;
                        //	status field is set to 1 defaultly, for invitations status field is set to 0
                        $new->save();
                    }
                }
                return redirect()->back()->with('success',__('messages.new_data_success'));

            }
        }

    }

    public function remove_student_fromclass(Request $request){

        if($request->has('class_entity') && $request->has('student_entity'))
        {
            $validator = Validator::make($request->all(),[
                'class_entity'=>'required|exists:classes,id',
                'student_entity' => 'required|exists:students,id',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return response()->json(['status'=>'invalid','messages'=>$error,'message'=>'Invalid Attempt']);
            }
            else
            {
                $class_id=$request->input('class_entity');
                $student_id=$request->input('student_entity');
                $cond = StudentClass::where('student_id',$student_id)->where('class_id',$class_id);
                if($cond->delete()){
                  return response()->json(['status'=>'success','message'=>'success']);
                }
            }
        }
    }

    public function propose_book_toclass($cid,Request $request){
      $data=[]; $class_ent=(int)$cid;
      $data['class_info']=DB::table('classes')->whereraw('classes.id = ?',array($class_ent))->first();

      if($request->ajax()){
        $book_list=DB::table('books')->select('books.id', 'books.title', 'books.subtitle', 'books.cover_image', 'books.subject', 'class_book_suggestions.book_id as bookid','class_book_suggestions.class_id as classid')
                  ->leftJoin('class_book_suggestions',function ($join)use($data){
                      $join->on('class_book_suggestions.book_id', '=' , 'books.id') ;
                      $join->where('class_book_suggestions.class_id','=',$data['class_info']->id);
                    });
        if($request->has('subject_val') && $request->input('subject_val') != ''){
            $book_list=$book_list->where('subject',(int)$request->input('subject_val'));
        }
        $book_list=$book_list->selectRaw("CASE WHEN class_book_suggestions.book_id IS NULL THEN NULL ELSE 1 END AS is_attached")->paginate(8);
        $data['book_lists']=$book_list;
        $view=view('User.teacher.admin.book_proposes',compact('data'))->render();
        return response()->json(['status'=>'success','results'=>$view]);
       }

      $data['book_lists']=DB::table('books')->select('books.id', 'books.title', 'books.subtitle', 'books.cover_image', 'books.subject', 'class_book_suggestions.book_id as sbookid','class_book_suggestions.class_id as classid')
                        ->leftJoin('class_book_suggestions',function ($join)use($data){
                            $join->on('class_book_suggestions.book_id', '=' , 'books.id') ;
                            $join->where('class_book_suggestions.class_id','=',$data['class_info']->id);
                        })
                        ->selectRaw("CASE WHEN class_book_suggestions.book_id IS NULL THEN NULL ELSE 1 END AS is_attached")->paginate(8);
      $data['subjects']=DB::table('subjects')->get();
      return view('User.teacher.admin.proposetitles',compact('data'));
    }

    public function process_propose($cid,Request $request){
        if($request->ajax()){
            $validator = Validator::make($request->all(),[
                'class_entity'=>'required|integer|exists:classes,id',
                'book_entity' => 'required|integer|exists:books,id',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return response()->json(['status'=>'invalid','messages'=>$error,'message'=>'Invalid Attempt']);
            }
            else
            {
              $classid=(int)$request->input('class_entity');
              $bookid=(int)$request->input('book_entity');
              $userid=auth()->user()->id;
               $class=ClassModel::find($classid);
               if (! $class->book_suggestions->contains($bookid)) {
                $class->book_suggestions()->attach($bookid, ['created_by' =>$userid]);
                return response()->json(['status'=>'success','message'=>'Book has been suggested to the class']);
               }
               else{
                return response()->json(['status'=>'exception','message'=>'Already Suggested']);
               }
                // dd($request->all());
            }
        }
    }

    public function send_invitation($id,Request $request)
    {
       //dd("hii");
        
        $datas = $request->all();
        $model = ClassModel::withCount(['students'])->findOrFail((int)$id);

    
        $student_mails =  explode("\n", str_replace("\r", "", $datas["text"]));

       
         foreach($student_mails as $mail)
        {
            $student_id = Student::where(['email'=>$mail])->pluck('id')->first();
            $student_invitation = new StudentClass();
           
            $student_invitation->student_id = $student_id;
            $student_invitation->class_id =$model->id;
            $student_invitation->status = 0;
            $student_invitation->created_by = Auth::user()->id;
            $student_invitation->save();
      
       }
       return redirect()->back()->with('success','Invitation sent successfully');
      

}


    

public function invitation($id)
{
    return view('User.student.student_invitation_accept',compact('id'));

}


public function detail_student($student_id,$class_id,Request $request)
{
   // dd($student_id);
     $data = StudentClass::with(['class_name'])
                    ->where(['student_id'=>$student_id,'is_deleted'=>'0'])
                    ->get();

     $exercises_datas = DB::table('book_exercises')
     ->select('book_exercises.title AS exercise_title','book_exercises.id AS exercise_id','books.title AS book_title','books.id AS book_id','chapters.title AS chapter_title','student_exercise_mark.mark AS student_mark','student_exercise_mark.student_id AS student_id')
     ->join('student_exercise_mark','student_exercise_mark.exercise_id','=','book_exercises.id')
     ->join('chapters','chapters.id','=','book_exercises.chapter_id')
     ->join('books','books.id','=','book_exercises.book_id')
     ->join('student_exercise_activity_sessions','student_exercise_activity_sessions.exercise_id','=','book_exercises.id')
     ->where('student_exercise_activity_sessions.examination_status','=','1')
     ->where('student_exercise_mark.student_id','=',$student_id)->get();
     //dd($exercises_datas);
     





                   
    //dd($data[0]->class_name->class_name);
    //echo "<pre>";print_r($data[0]->class_name->class_name);die;
    return view('User.teacher.admin.detail_student',compact('data','exercises_datas'));
}

public function resetExercise(Request $request)
{
    //dd("hii");
    $student_id = $request->studentId;
    $exercise_id = $request->exerciseId;
    $query1 = StudentExcerciseActivity::where(['student_id' => $student_id,'exercise_id' => $exercise_id,'examination_status' => '1'])->delete();
    //dd($data);
    //$query1->examination_status = '0';
    $query2 = StudentExerciseMark::where(['student_id' => $student_id,'exercise_id' => $exercise_id,'status' => '1'])->delete();
   // $query2->status = '0';

    if($query1 && $query2)
    {
        return response()->json(['status' => 'success','student_id' => $student_id,'exercise_id' => $exercise_id]);
    }else{
        dd("not");
    }
    //dd($student_id);
}
       
}
