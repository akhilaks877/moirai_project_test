@extends('layouts.Student.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop


<div class="app-main__inner">
	  <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    <h4 class="page-title"> Please select your examination test!!</h4>

                	<ul>
                            <li><a href="{{ route('student.title.chapter_exercise_show',['chapter'=>$id,'excer_type' => 'practise'])}}">Practise test</a></li>
                            <li><a href="{{ route('student.title.chapter_exercise_show',['chapter'=>$id,'excer_type' => 'examination'])}}">Examination test</a></li>
                           
                    </ul>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@stop