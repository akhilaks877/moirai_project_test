<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use App\Models\School;
use App\Models\Teacher;
use Carbon\Carbon;
use Response;
use Hash;
use Session;
use Illuminate\Validation\Rule;
use Validator;
use Lang;
use Auth;
use Illuminate\Support\Collection;

class ManageSchoolController extends Controller
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
        //$data = School::select('id','school_name')
        //->with(['students'])
        //     ->whereHas('classes', function($q) use($request){
        //        // $q->orWhereHas('teacher', function($q1) use($request){
        //             //$q1->whereHas('teacher', function($q2) use($request){
        //                 //$q->groupBy('teacher_id');
        //                 $q->select('slug');
        //            // });
        //        // });
        //    })
        //->orderBy('created_at','DESC')
        //->get();
        //echo "<pre>";print_r($data->toArray());die;
        if($request->ajax())
        {
            $data = School::select('id','school_name')->with('classes.teacher','classes.student_lists')->orderBy('created_at','DESC')->get();
			return DataTables::of($data)
					->addIndexColumn()
                    ->editColumn('school_name', function($data) { $name = ucfirst($data->school_name); return $name;})
                    ->addColumn('teachers_count', function($data) { $teachers=isset($data->classes) ? (new Collection($data->classes->pluck('teacher')))->unique()->count() : 0; return $teachers;  })
                    ->addColumn('students_count', function($data) { $students=isset($data->classes) ? (new Collection($data->classes->pluck('student_lists')->collapse()))->groupBy('user_id')->count() : 0;  return $students;  })
                    ->addColumn('action', function($data) { $btn = '<a href="'.route('school_management.edit',$data->id).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a><a href="javascript:void(0)" data-id="'.$data->id.'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-dark disabled editModal"><span class="fa fa-trash"></span></a>'; return $btn; })
                    ->removeColumn('id')
                    ->make(true);
        }
        return view('User.school.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('User.school.create');
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
			'school_name'=>'required',
			'school_street'=>'required',
			'school_city'=>'required',
			'school_zipcode'=>'required|min:6|max:6',
			'school_province'=>'required',
			'school_country'=>'required',
            'contact_email'=>'required|email|unique:schools',
            'contact_last_name'=>'required',
            'contact_first_name'=>'required',
			'contact_phone'=>'required',
        ],[]
        );
        if ($validator->fails()) { // validation fails
            //dd($validator->availableErrors());
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
			$request->merge(['created_by' => auth()->user()->id]);
            $data = $request->all();
            $new = new School();
            $new->fill($data);
            $newSave = $new->save();
            if($newSave)
                return redirect()->route('school_management.index')->with('success',__('messages.new_data_success'));
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
        $school = School::with('created_user')->findOrFail($id);
        return view('User.school.edit',compact('school'));
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
			'school_name'=>'required',
			'school_street'=>'required',
			'school_city'=>'required',
			'school_zipcode'=>'required|min:6|max:6',
			'school_province'=>'required',
			'school_country'=>'required',
			'contact_email' => [ 'required','email',Rule::unique('schools','contact_email')->ignore($id)],
            'contact_last_name'=>'required',
            'contact_first_name'=>'required',
			'contact_phone'=>'required',
        ],[]
        );
        if ($validator->fails()) { // validation fails
            //dd($validator->availableErrors());
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
			$request->merge(['updated_by' => auth()->user()->id]);
            $data = $request->all();
            $update  = School::findOrFail($id);
            $update->fill($data);
            $Edit = $update->save();
			$update->touch();
            if($Edit)
                return redirect()->route('school_management.index')->with('success',__('messages.new_data_update'));
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
	   $status = 'fail';
	   $school = School::findOrFail($id);
	   if($school){
	       $status = 'success';
		   $school->delete();
	   }
	   return response()->json(['status'=>$status]);
    }

    public function teacher_lists(Request $request)
    {
        if($request->ajax())
        {
            //$data = School::findOrFail($request->school_id);
            $data = Teacher::select('*')->with(['classes'])->withCount(['students'])
			->whereHas('classes', function($q) use($request){
				  $q->whereHas('school', function($q1) use($request){
					  //$q1->whereHas('teacher', function($q2) use($request){
						$q1->where('id', $request->school_id);
					 // });
                  });
             })
             ->get();
            //echo "<pre>";print_r($data->toArray());die;
			//$data = [];
			return DataTables::of($data)
                    ->addColumn('email', function($data) {
                        $column = '<a href="'.route('teacher_details.edit',$data->id).'">'.(isset($data)?$data['email']:'-').'</a>';
                        return $column;
                    })
                    ->addColumn('lastname', function($data) {
                        $column = '<a href="'.route('teacher_details.edit',$data->id).'">'.(isset($data)?$data['last_name']:'-').'</a>';
                        return $column;
                    })
                    ->addColumn('firstname', function($data) {
                        $column = '<a href="'.route('teacher_details.edit',$data->id).'">'.(isset($data)?$data['first_name']:'-').'</a>';
                        return $column;
                    })
                    ->addColumn('no_of_students', function($data) {
                        $column = 0;
                        if(isset($data->students_count))
                            $column = $data->students_count;
                        return $column;
                    })
                    ->removeColumn('id')
                    ->rawColumns(['email','lastname','firstname','no_of_students'])
					->make(true);
        }
    }
}
