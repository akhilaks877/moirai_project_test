<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\user;
use App\Preferrable_lang;

class MyAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
	{
		$this->middleware('verify.admin');

    }

    public function index()
    {
        $data=[]; $data['langs']=Preferrable_lang::get();
        return view('User.my_account_profile',compact('data'));
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
        if($request->ajax() && ((int) Auth::user()->id === (int) $id)){
            $validator=Validator::make($request->all(),[
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$id,
                'password' => 'nullable|string|min:8',
                'user_img'  => 'sometimes|mimes:jpeg,png,svg,webp|max:2024',
            ]);
            if ($validator->fails()) {
                $validation_errors=$validator->getMessageBag()->toArray();
                return response()->json(['resultant'=>'validation','messages'=>$validation_errors]);
            }
            if($request->hasFile('user_img')){
                try{
                $user_img=$request->file('user_img');
                $imgName = time().'_'.$user_img->getClientOriginalName();
                $user_img->storeAs('public/user_profiles',$imgName);
                $filename = $imgName;
               }
               catch(\Exception $ex){
                return response()->json(['resultant'=>'failed','messages'=>$ex->getMessage()]);
               }
           }
            $user = User::findOrFail(Auth::user()->id);
            $user->email = $request->input('email');
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            if((!(Hash::check($request->input('password'), Auth::user()->password))) && !empty($request->input('password'))){
              $user->password =Hash::make($request->input('password'));
            }
            $user->preferred_language = ($request->input('preferred_language')) ? $request->input('preferred_language') : NULL;

            if(isset($filename)){
                $user->user_img =  $filename;
            }
            $user->save();
            return response()->json(['resultant'=>'success']);
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
