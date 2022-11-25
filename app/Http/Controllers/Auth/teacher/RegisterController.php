<?php

namespace App\Http\Controllers\Auth\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Preferrable_lang;
use App\Preference_setting;
use App\Models\Teacher;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = 'teacher/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $data=[]; $data['langs']=Preferrable_lang::get();
        return view('auth.teacher.registration',compact('data'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'language'=> ['required','exists:preferrable_langs,id'],
            'user_img'  => ['nullable','mimes:jpeg,png,svg,webp','max:2024'],
        ]);
    }

    protected function create(array $data)
    {
        $user_data= User::create([
            'name' => $data['firstname'],
            'firstname'=> $data['firstname'],
            'lastname'=> $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'preferred_language' => $data['language'],
        ]);

        $teacher_data=Teacher::create([
            'user_id'=>$user_data->id,
            'email'=>$data['email'],
            'first_name'=>$data['firstname'],
            'last_name'=>$data['lastname'],
            'language'=>$data['language'],
            'created_by'=>$user_data->id,
        ]);

        $check_array=array('user_id'=>$user_data->id);
            $insrt_array=array('user_id'=>$user_data->id,
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
        return $user_data;
    }
}
