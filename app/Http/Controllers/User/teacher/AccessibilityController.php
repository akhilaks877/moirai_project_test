<?php

namespace App\Http\Controllers\User\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Illuminate\Validation\Rule;
use Validator;
use Lang;

class AccessibilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
	{
		$this->middleware('verify.teacher');

    }

    public function index()
    {
        $user_id=auth()->user()->id;
        $pref_setts=DB::table('preference_settings')->where('user_id',$user_id)->first();
        return view('User.teacher.admin.accessibility_index',compact('pref_setts'));
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
        $user_id=auth()->user()->id;
        if($request->ajax())
        {
            if($request->has('note_userpreff') && $request->input('note_userpreff') == 1){  // for individual user's note font settings
                $narray=[
                    'selected_font_size' => $request->input('selected_font_size'),
                    'view_editor_note' => $request->input('view_editor_note'),
                    'view_teacher_note' => $request->input('view_teacher_note'),
                    'view_student_note' => $request->input('view_student_note'),
                    'enable_note_edit' => $request->input('enable_note_edit'),
                ];
                $pref_setts=DB::table('preference_settings')->whereRaw('user_id = ?',array($user_id))->update($narray);
                 $message="Note settings updated.";
                 $request->session()->flash('prefstatus.status', 'success');
                 $request->session()->flash('prefstatus.message', $message);
                return response()->json(['status'=>'success','message'=>$message]);
            }
            $array=[
                'show_notification' => $request->input('show_notification'),
                'selected_font_size' => $request->input('selected_font_size'),
                'menu_back_color' => $request->input('menu_back_color'),
                'menu_text_color' => $request->input('menu_text_color'),
                'readng_back_color' => $request->input('readng_back_color'),
                'readng_text_color' => $request->input('readng_text_color'),
                'image_preference' => $request->input('image_preference'),
            ];
            $pref_setts=DB::table('preference_settings')->whereRaw('user_id = ?',array($user_id))->update($array);
            $message="Accesibility Settings Upadated.";
            $request->session()->flash('prefstatus.status', 'success');
            $request->session()->flash('prefstatus.message', $message);
            return response()->json(['status'=>'success','message'=>$message]);
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
