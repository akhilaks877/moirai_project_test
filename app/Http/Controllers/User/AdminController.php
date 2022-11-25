<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Auth\Events\Registered;
use DB;
use Carbon\Carbon;
use App\Models\School;
use App\User;
use App\Models\PreLang;
use App\Role;
use App\Preference_setting;
use Response;
use Hash;
use Session;
use Illuminate\Validation\Rule;
use Validator;
use Lang;
use Auth;

class AdminController extends Controller
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
		$role = Auth::user()->roles->first()->id;
		$data = User::with(['roles'])->select('*');
		$data->where('created_by',Auth::user()->id)->where('is_administrator',1);
		$data = $data->get();
		//echo "<pre>";print_r($data->toArray());die;
        if($request->ajax())
        {

            return DataTables::of($data)
					->addIndexColumn()
                    ->editColumn('email', function($data) { $email = $data->email; return $email;})
                    ->editColumn('last_name', function($data) { return $data->last_name; })
                    ->editColumn('first_name', function($data) { return $data->first_name; })
					->addColumn('role', function($data) { return $data->roles->first()->name;})
                    ->addColumn('action', function($data) { $btn = '<a href="'.route('admins.edit',$data->id).'" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>'; return $btn; })
                    ->removeColumn('id')
					->rawColumns(['action'])
                    ->make(true);
        }
        return view('User.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	   $roles = Role::pluck('name','id');
	   $lang = PreLang::pluck('lang_name','id');
       return view('User.admin.create',compact('lang','roles'));
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'=>'required|string|min:8',
            'last_name'=>'required',
            'first_name'=>'required',
            'language'=>'required|exists:preferrable_langs,id',
        ],[]);
        if ($validator->fails()) { // validation fails
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
            DB::beginTransaction();
            try{
			$request->merge(['created_by' => auth()->user()->id]);
            $data = $request->all();
			$user = User::create([
				'name' => $data['first_name'],
				'firstname' => $data['first_name'],
				'lastname' => $data['last_name'],
                'preferred_language'=>$data['language'],
                'is_administrator'=>1,
				'email' => $data['email'],
				'created_by' => $data['created_by'],
				'password' => Hash::make($data['password']),
			]);
			$role = Role::find($request->admin_role);
			if($role)
                $user->roles()->attach($role);

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

            return redirect()->route('admins.index')->with('success',__('messages.new_data_success'));
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
	   $model = User::with(['roles'])->select('*')->findOrFail($id);
       $roles = Role::pluck('name','id');
	   $lang = PreLang::pluck('lang_name','id');
       return view('User.admin.edit',compact('lang','roles','model'));
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
			'email' => [ 'required','email',Rule::unique('users','email')->ignore($id)],
            'password'=>'nullable|string|min:8',
            'last_name'=>'required',
            'first_name'=>'required',
            'language'=>'required|exists:preferrable_langs,id',
			],[]
        );
        if ($validator->fails()) { // validation fails
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {
			$request->merge(['updated_by' => auth()->user()->id]);
			if ($request->get('password') == '') {
				$data = $request->except('password');
			} else {
				$data = $request->all();
			}
			$user = User::with(['roles'])->findOrFail($id);

			$user->update([
				'name' => $data['first_name'],
				'firstname' => $data['first_name'],
				'lastname' => $data['last_name'],
				'preferred_language'=>$data['language'],
				'updated_by' => $data['updated_by'],
				'email' => $data['email'],
			]);
			if(isset($data['password']))
			{
				$user->update(['password' => Hash::make($data['password'])]);
			}
			$role = Role::find($request->admin_role);//echo "<pre>";print_r($role);die;
			if($role)
				$user->roles()->sync($request->admin_role);

			return redirect()->route('admins.index')->with('success',__('messages.new_data_success'));
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
}
