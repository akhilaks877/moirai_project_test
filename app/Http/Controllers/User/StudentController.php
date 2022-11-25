<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Auth\Events\Registered;
use DB;
use App\Models\School;
use App\Models\Teacher;
use App\Models\PreLang;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\StudentClass;
use App\User;
use App\Role;
use App\Preference_setting;
use Carbon\Carbon;
use Response;
use Hash;
use Session;
use Illuminate\Validation\Rule;
use Validator;
use Lang;
use Image;
use Storage;

class StudentController extends Controller
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
        $locale = \App::getLocale();
        if($request->ajax())
        {
            $data = Student::select('id','email','first_name','last_name')->with(['teachers'])->orderBy('created_at','DESC')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('email', function($data) { $email = $data->email; return $email;})
                    ->editColumn('last_name', function($data) { return $data->last_name; })
                    ->editColumn('first_name', function($data) { return $data->first_name; })
                    ->addColumn('teachers', function($data) {
                        $teachers = '';//$data->teachers->first()->class_name->teacher
                            if(isset($data->teachers)){
                                foreach ($data->teachers as $key => $value) {
                                    //$teachers .=  $value->class_name->teacher->first_name;
                                    if(isset($value->class_name->teacher))
                                    $teachers .= '<a href="'.route('teacher_details.edit',$value->class_name->teacher->id).'">'.$value->class_name->teacher->last_name.' '.$value->class_name->teacher->first_name.'</a><br/>';
                                }
                            }
                        return $teachers;
                    })
                    ->addColumn('action', function($data) { $btn = '<a href="'.route('student_details.edit',$data->id).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>'; return $btn; })
                    ->removeColumn('id')
                    ->rawColumns(['teachers','action'])
                    ->make(true);
        }
        return view('User.student.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lang = PreLang::pluck('lang_name','id');//->prepend('Select','');
        return view('User.student.create',compact('lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo "<pre>";print_r($request->all());die;
		$role = Role::find(3);//webmaster
		$validator = Validator::make($request->all(),[
            'email'=>'required|email|unique:students,email|unique:users,email',
            'password'=>'required|string|min:8',
            'last_name'=>'required',
            'first_name'=>'required',
            'id_magento'=>'required|unique:students',
            'language'=>'required|exists:preferrable_langs,id',
            'file_name' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=400,max_height=400',
        ],[
            'id_magento.unique'=>__('messages.magento_id_unique'),
			'file_name.max'=>__('messages.file_name_max'),
		  ]
        );
        if ($validator->fails()) { // validation fails
            //dd("validation error");
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            DB::beginTransaction();
            try{
			$fileName = '';
			if($request->hasFile('file_name'))
			 {
				$file = $request->file('file_name');
				$fileName = time().'.'.$request->file_name->extension();
				$request->merge(['file'=>$fileName]);
				$img = Image::make($file->getRealPath());
				$img->resize(400, 400, function ($constraint) { $constraint->aspectRatio(); });
				$img->stream();
				Storage::disk('local')->put('public/students'.'/'.$fileName, $img, 'public');
			 }
            $request->merge(['created_by' => auth()->user()->id]);
            $data = $request->all();
            $new = new Student();
            $new->fill($data);
            $newSave = $new->save();

            if($newSave){
				//echo "<pre>";print_r($new);die;
				$user = User::create([
					'name' => $data['first_name'],
					'firstname' => $data['first_name'],
					'lastname' => $data['last_name'],
					'user_img' => $fileName,
					'preferred_language'=>$data['language'],
					'email' => $data['email'],
					'password' => Hash::make($data['password']),
				]);
				$user->roles()->attach($role);
				$new->update(['user_id'=>$user->id]);
             }
             $check_array=array('user_id'=>$user->id);
             $insrt_array=array('user_id'=>$user->id,
             'show_notification'=> 1,
             'default_font_size'=>10,
             'selected_font_size'=>null,
             'menu_back_color'=>null,
             'menu_text_color'=>null,
             'readng_back_color'=>null,
             'readng_text_color'=>null,
             'image_preference'=>null,
             'notes_font_size'=>null,
             'view_editor_note'=>null,
             'view_teacher_note'=>null,
             'view_student_note'=>null,
             'enable_note_edit'=>null,
             );
             $add_preffsett=Preference_setting::updateOrCreate($check_array,$insrt_array);
             event(new Registered($ur = $user));
             DB::commit();
             return redirect()->route('student_details.index')->with('success',__('messages.new_data_success'));
           }catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'error','message' => [ $ex->getMessage() ]], 500);
          }
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
        $student = Student::with('created_user','updated_user')->findOrFail($id);
        $stud_ = StudentClass::select('class_id')->where('student_id',$id)->get()->toArray();
        $stud_class = [];
        foreach ($stud_ as $key => $value) {
            $stud_class[] = $value['class_id'];
        }
        $lang = PreLang::pluck('lang_name','id');
		$classes = ClassModel::pluck('class_name','id');
        return view('User.student.edit',compact('lang','student','classes','stud_class'));
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
		$user_id = Student::findOrFail($id)->user_id;//dd($user_id);
		$validator = Validator::make($request->all(),[
			'email' => [ 'required','email',Rule::unique('students','email')->ignore($id),Rule::unique('users','email')->ignore($user_id)],
            'password'=>'nullable|string|min:8',
            'last_name'=>'required',
            'first_name'=>'required',
            'id_magento'=>[ 'required',Rule::unique('students')->ignore($id)],
            'language'=>'required|exists:preferrable_langs,id',
            'file_name' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=400,max_height=400',
        ],[
            'id_magento.unique'=>__('messages.magento_id_unique'),
			'file_name.max'=>__('messages.file_name_max'),
		  ]
        );
        if ($validator->fails()) { // validation fails
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
			$update = Student::findOrFail($id);//->update(['first_name'=>1]);
			$old_file = $update->file;
			$fileName = $old_file;

			if($request->hasFile('file_name'))
			{
				$file = $request->file('file_name');
				$fileName = time().'.'.$request->file_name->extension();
				$request->merge(['file'=>$fileName]);
				$img = Image::make($file->getRealPath());
				$img->resize(400, 400, function ($constraint) { $constraint->aspectRatio(); });
				$img->stream();
				Storage::disk('local')->put('public/students'.'/'.$fileName, $img, 'public');
			}

            $request->merge(['updated_by' => auth()->user()->id]);
			if ($request->get('password') == '') {
				$data = $request->except('password');
			} else {
				$data = $request->all();
			}
			$update->fill($data);
            $save = $update->save();
			$update->touch();
			if($save){
				$user = User::where('id',$update->user_id)->update([
					'name' => $data['first_name'],
					'firstname' => $data['first_name'],
					'lastname' => $data['last_name'],
					'user_img' => $fileName,
					'preferred_language'=>$data['language'],
					'email' => $data['email'],
				]);
				if(isset($data['password'])){
					User::where('id',$update->user_id)->update(['password' => Hash::make($data['password'])]);
				}
				if($request->hasFile('file_name') && isset($old_file)){
				  $exists = Storage::exists('public/students'.'/'.$old_file);
				  if($exists)
					Storage::delete('public/students'.'/'.$old_file);
			    }
				return redirect()->route('student_details.index')->with('success',__('messages.new_data_update'));
			}
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

	public function class_lists(Request $request)
    {
        if($request->ajax())
        {
			$data = StudentClass::where('student_id',$request->student_id)->with(['class_name'])->get();
			//echo "<pre>";print_r($data->toArray());die;
			//$data = [];
			return DataTables::of($data)
					->editColumn('class_name', function($data) {
                        $class = '';
                        if(isset($data->class_name))
                        $class = $data->class_name->class_name;
                        return $class;
                    })
					->editColumn('teacher', function($data) {
                        $teacher = '';
                        if(isset($data->class_name->teacher)){
                            $teacher .= '<a href="'.route('teacher_details.edit',$data->class_name->teacher->id).'" class="">'.$data->class_name->teacher->first_name ." ".$data->class_name->teacher->last_name.'</a>';
                        }
                        return $teacher;
                    })
					->addColumn('action', function($data) {
                        $btn = '';
                        if(isset($data->class_name)) {
                            $btn .= '<a href="'.route('class_management.edit',$data->class_name->id).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>';
                        }
                        $btn .= ' <a href="'.route('student_details.removeclass',$data->id).'" class="disabled mb-2 mr-2 border-0 btn-transition btn btn-outline-dark editModal"><span class="fa fa-trash"></span></a>';
                        return $btn;
                    })
                    ->removeColumn('id')
                    ->rawColumns(['teacher','action'])
					->make(true);
        }
    }

    public function class_excercise_lists(Request $request)
    {
        if($request->ajax())
        {
			$data = [];
            return DataTables::of($data)
                    ->addColumn('excercise_name', function($data) {
                        $class = '';
                        return $class;
                    })
					->addColumn('class_name', function($data) {
                        $class = '';
                        return $class;
                    })
					->addColumn('book_title', function($data) {
                        $book_title = '';
                        return $book_title;
                    })
                    ->addColumn('chapter', function($data) {
                        $chapter = '';
                        return $chapter;
                    })
                    ->addColumn('grade', function($data) {
                        $grade = '';
                        return $grade;
                    })
					->addColumn('action', function($data) {
                        $btn = '';
                        return $btn;
                    })
                    ->removeColumn('id')
                    ->rawColumns([])
					->make(true);
        }
    }

    public function addtoclass(Request $request)
    {

        if($request->has('select_class') && $request->has('student_id'))
        {
            $validator = Validator::make($request->all(),[
                'student_id'=>'exists:students,id',
                'select_class'=>'required|array',
                'select_class.*' => 'exists:classes,id',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else
            {
                //$find = StudentClass::where('student_id',$request->input('student_id'))->get();
                //$collection = StudentClass::where('student_id',$request->input('student_id'))->get();
                //$c =  StudentClass::destroy($collection->toArray());
                //echo "<pre>";print_r($c);die;
                if(isset($request->select_class))
                {
                    foreach ($request->select_class as $key => $value) {
                        $new = StudentClass::where('class_id',$value)->where('student_id',$request->input('student_id'))->first();
                        if(empty($new)){
                            $new = new StudentClass();
                        }
                        $new->class_id = $value;
                        $new->student_id = $request->input('student_id');
                        $new->created_by =  auth()->user()->id;
                        $new->save();
                    }
                }
                return redirect()->route('student_details.edit',$request->student_id)->with('success',__('messages.new_data_success'));
            }
        }
    }
    public function removeFromClass(Request $request,$id)
    {
        dd($id);
        $school = StudentClass::findOrFail($id);
        if($school){
            $status = 'success';
            $school->delete();
        }
    }
}
