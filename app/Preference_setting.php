<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preference_setting extends Model
{
    protected $table='preference_settings';
    protected $fillable = ['user_id','show_notification','default_font_size','selected_font_size','menu_back_color','menu_text_color','readng_back_color','readng_text_color','image_preference','notes_font_size','view_editor_note','view_teacher_note','view_student_note','enable_note_edit'];
}
