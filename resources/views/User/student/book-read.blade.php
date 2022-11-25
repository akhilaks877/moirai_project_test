@extends('layouts.Student.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
<div> 
 @if((!$data['chapters']->isEmpty()) && $data['type'] == '')
 @Include('User.student.books.view_chapter')
 @endif
</div>
</div>
@stop
