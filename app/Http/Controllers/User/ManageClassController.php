<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use App\Models\School;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\PreLang;
use App\Models\StudentClass;
use App\Models\Student;
use Carbon\Carbon;
use Response;
use Hash;
use Session;
use Illuminate\Validation\Rule;
use Validator;
use Lang;
use Auth;

class ManageClassController extends Controller
{
	private $user_role;

	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$user = Auth::user();
			$this->user_role = $user->roles ? $user->roles->first()->name : 'No role';
			view()->share('user_role',$this->user_role);
			return $next($request);
		});
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->ajax())
        {
            $data = ClassModel::select('*')->withCount(['students'])->with(['created_user','school'])->orderBy('id', 'DESC')->get();
            //echo "<pre>";print_r($data->toArray());die;
			return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('class_name', function($data) {
						$class = '';
						$class = (isset($data->class_name)?$data->class_name:'');
						return $class;
					})
					->editColumn('created_by', function($data) {
						$name = '';
						$name .= '<a href="javascript:void(0);">'.(isset($data->created_user)?$data->created_user->firstname:'').' '.(isset($data->created_user)?$data->created_user->lastname:'').' ('.(isset($data->created_user->roles)?$data->created_user->roles->first()->name:'').')</a>';
						return $name;
					})
					->editColumn('school', function($data) {
						$school = '';
						$school = (isset($data->school)?$data->school->school_name:'');
						return $school;
					})
					->editColumn('no_students', function($data) { return (isset($data->students_count)?$data->students_count:0); })
                    ->addColumn('action', function($data) { $btn = '<a href="'.route('class_management.edit',$data->id).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>'; return $btn; })
                    ->removeColumn('id')
					->rawColumns(['created_by','action'])
                    ->make(true);
		}
        return view('User.class.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $school = School::pluck('school_name','id')->prepend('Select','');
		$teacher = Teacher::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name', 'id')->prepend('Select','');
		return view('User.class.create',compact('school','teacher'));
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
            'school_id'=>'required|exists:schools,id',
            'teacher_id'=>'required|exists:teachers,id',
        ],[]);
        if ($validator->fails()) { // validation fails
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            $display_answer = 0;
            if($request->has('display_answer'))
            $display_answer = 1;

            $request->merge(['display_answer' => $display_answer,'created_by' => auth()->user()->id]);
            $data = $request->all();
            $new = new ClassModel();
            $new->fill($data);
            $newSave = $new->save();
            return redirect()->route('class_management.index')->with('success',__('messages.new_data_success'));
        }
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
        $model = ClassModel::withCount(['students'])->findOrFail($id);
        $students = Student::select(DB::raw("CONCAT(last_name,' ',first_name) AS name"),'id')->pluck('name', 'id');
        $school = School::pluck('school_name','id')->prepend('Select','');
        $teacher = Teacher::select(DB::raw("CONCAT(last_name,' ',first_name) AS name"),'id')->pluck('name', 'id')->prepend('Select','');
        $stud_ = StudentClass::select('student_id')->where('class_id',$id)->get()->toArray();
        $stud_class = [];
        foreach ($stud_ as $key => $value) {
            $stud_class[] = $value['student_id'];
        }
        //echo "<pre>";print_r($stud_class);die;
        return view('User.class.edit',compact('model','school','teacher','students','stud_class'));
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
        //echo "<pre>";print_r($request->all());die;
        $validator = Validator::make($request->all(),[
            'class_name'=>['required',Rule::unique('classes','class_name','slug')->ignore($id),],
            'slug'=>'required|string|unique:classes',
            'school_id'=>'required|exists:schools,id',
            'teacher_id'=>'required|exists:teachers,id',
        ],[]);
        if ($validator->fails()) { // validation fails
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            $display_answer = 0;
            if($request->has('display_answer'))
            $display_answer = 1;

            $request->merge(['display_answer' => $display_answer,'updated_by' => auth()->user()->id]);
            $data = $request->all();
            $update = ClassModel::findOrFail($id);
            $update->fill($data);
            $newSave = $update->save();
            $update->touch();
            return redirect()->route('class_management.index')->with('success',__('messages.new_data_update'));
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

    public function student_lists(Request $request)
    {
        if($request->ajax())
        {
            $data = StudentClass::where('class_id',$request->classID)->with(['student'])->get();
			//echo "<pre>";print_r($data->toArray());die;
			return DataTables::of($data)
					->addColumn('email', function($data) {
                        $email = '';
                        if(isset($data->student))
                            $email = $data->student->email;
                        return $email;
                    })
					->addColumn('lastname', function($data) {
                        $lastname = '';
                        if(isset($data->student))
                            $lastname = $data->student->last_name;
                        return $lastname;
                    })
                    ->addColumn('firstname', function($data) {
                        $firstname = '';
                        if(isset($data->student))
                            $firstname = $data->student->first_name;
                        return $firstname;
                    })
					->addColumn('action', function($data) {
                        $btn = '';
                        $btn .= ' <a href="'.route('class_management.removestudent',$data->id).'" class="disabled mb-2 mr-2 border-0 btn-transition btn btn-outline-dark editModal"><span class="fa fa-trash"></span></a>';
                        return $btn;
                    })
                    ->removeColumn('id')
                    ->rawColumns(['action'])
					->make(true);
        }
    }
    public function addtoclass(Request $request)
    {
        //echo "<pre>";print_r($request->all());die;
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
                return redirect()->route('class_management.edit',$request->class_id)->with('success',__('messages.new_data_success'));
            }
        }
    }
}
