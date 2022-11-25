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
use App\Models\ClassModel;
use App\Models\Student;
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
use App\Traits\StoreImageTrait;
use Storage;
use Image;

class TeachersController extends Controller
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
		$role = Auth::user()->roles->first()->id;
        if($request->ajax())
        {
            $data = Teacher::select('id','email','first_name','last_name')->withCount(['students']);
			$data->where('created_by',Auth::user()->id);
			$data = $data->orderBy('created_at','DESC')->get();
			//echo "<pre>";print_r($data->toArray());die;
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('email', function($data) { $email = $data->email; return $email;})
                    ->editColumn('last_name', function($data) { return $data->last_name; })
                    ->editColumn('first_name', function($data) { return $data->first_name; })
                    ->addColumn('classes', function($data) { return (isset($data->students_count)?$data->students_count:0);})
                    ->addColumn('action', function($data) { $btn = '<a href="'.route('teacher_details.edit',$data->id).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>'; return $btn; })
                    ->removeColumn('id')
					->rawColumns(['action'])
                    ->make(true);
        }
        return view('User.teacher.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lang = PreLang::pluck('lang_name','id');//->prepend('Select','');
        return view('User.teacher.create',compact('lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$role = Role::find(3);//webmaster

        $validator = Validator::make($request->all(),[
            'email'=>'required|email|unique:teachers',
            'password'=>'required|string|min:8',
            'last_name'=>'required',
            'first_name'=>'required',
            'id_magento'=>'required|unique:teachers',
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
            DB::beginTransaction();
            try{
			$fileName = '';
			if($request->hasFile('file_name'))
			{
				$file = $request->file('file_name');
				$fileName = time().'.'.$file->getClientOriginalExtension();
				$request->merge(['file'=>$fileName]);
				//$request->file_name->move(public_path('uploads/teachers'), $fileName);
				//$fileName   = time() . '.' . $file->getClientOriginalExtension();
				$img = Image::make($file->getRealPath());
				$img->resize(400, 400, function ($constraint) { $constraint->aspectRatio(); });
				$img->stream();
				Storage::disk('local')->put('public/teachers'.'/'.$fileName, $img, 'public');
				Storage::disk('local')->put('public/user_profiles'.'/'.$fileName, $img, 'public');
			}

            $request->merge(['created_by' => auth()->user()->id]);
            $data = $request->all();
            $new = new Teacher();
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
					'created_by' => $data['created_by'],
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
            return redirect()->route('teacher_details.index')->with('success',__('messages.new_data_success'));
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
        $teacher = Teacher::with('created_user','updated_user')->findOrFail($id);
        $lang = PreLang::pluck('lang_name','id');//->prepend('Select','');
        return view('User.teacher.edit',compact('lang','teacher'));
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
			'email' => [ 'required','email',Rule::unique('teachers','email')->ignore($id)],
            'password'=>'nullable|string|min:8',
            'last_name'=>'required',
            'first_name'=>'required',
            'id_magento'=>[ 'required',Rule::unique('teachers')->ignore($id)],
            'language'=>'required|exists:preferrable_langs,id',
            'file_name' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=400,max_height=400',
        ],[
            'id_magento.unique'=>__('messages.magento_id_unique'),
			'file_name.max'=>__('messages.file_name_max'),
		  ]
        );
        if ($validator->fails()) { // validation fails
            //dd($validator->availableErrors());
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
			$update = Teacher::findOrFail($id);//->update(['first_name'=>1]);
			$old_file = $update->file;
			$fileName = $old_file;
			//$file_path = 'uploads/teachers/'.$old_file;

			if($request->hasFile('file_name'))
			{
				$file = $request->file('file_name');
				$fileName = time().'.'.$request->file_name->extension();
				$request->merge(['file'=>$fileName]);
				//$request->file_name->move(public_path('uploads/teachers'), $fileName);
				$img = Image::make($file->getRealPath());
				$img->resize(400, 400, function ($constraint) { $constraint->aspectRatio(); });
				$img->stream();
				Storage::disk('local')->put('public/teachers'.'/'.$fileName, $img, 'public');
				Storage::disk('local')->put('public/user_profiles'.'/'.$fileName, $img, 'public');
			}

            $request->merge(['updated_by' => auth()->user()->id,'created_by' => auth()->user()->id]);
			if ($request->get('password') == '') {
				$data = $request->except('password');
			} else {
				$data = $request->all();
			}
			//dd($data);
			$update->fill($data);
            $save = $update->save();
			$update->touch();
			if($save){
				$user = User::where('id',$update->user_id)->update([
					'name' => $data['first_name'],
					'firstname' => $data['first_name'],
					'lastname' => $data['last_name'],
					'user_img' => $fileName,
					'updated_by' => $data['updated_by'],
					'preferred_language'=>$data['language'],
					'email' => $data['email'],
				]);
				if(isset($data['password'])){
					User::where('id',$update->user_id)->update(['password' => Hash::make($data['password'])]);
				}
				if($request->hasFile('file_name') && isset($old_file)){
				  //dd($file_path);
				  $exists = Storage::exists('public/user_profiles'.'/'.$old_file);
				  $exists_1 = Storage::exists('public/teachers'.'/'.$old_file);
				  if($exists)
					Storage::delete('public/user_profiles'.'/'.$old_file);
				  if($exists_1)
					Storage::delete('public/teachers'.'/'.$old_file);
				  //unlink($file_path);
			    }
				return redirect()->route('teacher_details.index')->with('success',__('messages.new_data_update'));
			}
			//echo "<pre>";print_r($update);die;
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
            $data = ClassModel::with(['teacher'])->where('teacher_id',$request->teacher_id)->get();
            //echo "<pre>";print_r($data->toArray());die;
			//$data = [];
			return DataTables::of($data)
					->addColumn('class_name', function($data) {
                        $class_name = $data->class_name;
                        return $class_name;
                    })
					->addColumn('teacher', function($data) {
                        $teacher = '<a href="'.route('teacher_details.edit',$data->teacher->id).'">'.$data->teacher->last_name.' '.$data->teacher->first_name.'</a>';
                        return $teacher;
                    })
					->addColumn('action', function($data) {
						$btn = '';
						if(isset($data))
							$btn = '<a href="'.route('class_management.edit',$data->id).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>';
						return $btn;
					})
                    ->removeColumn('id')
					->rawColumns(['teacher','action'])
					->make(true);
        }
    }

	public function students_lists(Request $request)
    {
        if($request->ajax())
        {
            $data = Student::select('*')->with(['teachers'])
			->whereHas('teachers', function($q) use($request){
				  $q->whereHas('class_name', function($q1) use($request){
					  $q1->whereHas('teacher', function($q2) use($request){
						$q2->where('id', $request->teacher_id);
					  });
                  });
			 })->get();
			return DataTables::of($data)
					->addColumn('email', function($data) {
                        $column = '<a href="'.route('student_details.edit',$data->id).'">'.(isset($data)?$data['email']:'-').'</a>';
                        return $column;
                    })
					->addColumn('lastname', function($data) {
						$column = '<a href="'.route('student_details.edit',$data->id).'">'.(isset($data)?$data['last_name']:'-').'</a>';
                        return $column;
                    })
					->addColumn('firstname', function($data) {
                        $column = '<a href="'.route('student_details.edit',$data->id).'">'.(isset($data)?$data['first_name']:'-').'</a>';
                        return $column;
                    })
					->addColumn('classes', function($data) {
                        $classes = '';
                            if(isset($data->teachers)){
                                foreach ($data->teachers as $key => $value) {
                                    if(isset($value->class_name))
                                    $classes .= '<a href="'.route('class_management.edit',$value->class_name->id).'">'.$value->class_name->class_name.'</a><br/>';
                                }
                            }
                        return $classes;
                    })
                    ->removeColumn('id')
					->rawColumns(['email','lastname','firstname','classes'])
					->make(true);
        }
    }
}
