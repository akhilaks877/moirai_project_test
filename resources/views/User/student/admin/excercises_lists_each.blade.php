@extends('layouts.Student.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">

    @if(session()->has('message'))
    <div class="alert alert-danger">
        {{ session()->get('message') }}
    </div>
    @endif
    <div class="app-page-title">
        <div class="row">
            <div class="col-10">
                <div class="font-work">
                    <h4 class="mb-0 page-title">{!! $data['chapter_data']->book_name !!} -Chapter  {!! $data['chapter_data']->title !!} - {!! $data['type'] !!}</h4>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                        <a href="{{ route('student.dashboard.index') }}">Home</a> > <a href="{{ route('student.title.index') }}">Book Catalogue</a> > <a href="{{ route('student.title.show',['title'=>$data['chapter_data']->book_ent]) }}">{!! $data['chapter_data']->book_name !!}</a> > <a href="{{ route('student.title.reading_book',['title'=>$data['chapter_data']->book_ent,'chapter'=>$data['chapter_data']->id]) }}">{{ $data['chapter_data']->title }}</a> > {!! $data['type'] !!}
                        </nav>
                    </div>
                    <hr>
                    <div class="resume_programme mb-2">
                        <strong>{!! $data['chapter_data']->title !!} - {!! $data['type'] !!}</strong>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <img src="{{ asset('storage/ebooks/book_'.$data['chapter_data']->book_ent.'/cover_image/'.$data['chapter_data']->book_coverimg) }}" alt="Book Cover [Book Title]" title="Book Cover [Book Title]" class="img-fluid w-100"/>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">

            <h5 class="card-title">List Of '{{ $data['type'] }}' Exercises</h5>

            <div class="row choice_exercise">

                @foreach ($data['exercise_datas'] as $exer)
                <div class="col-6 col-md-4 col-lg-3 text-center px-5 py-3">
                    <a href="{{route('student.title.show_exercise_form',['excer'=>$exer->id,'chap' => $id,'type' => $data['type']])}}" class="">
                        <div class="button_square">
                            <div class="content">
                             <div class="bg" style="background-image:">
                                </div>
                            </div>
                        </div>
                        <h5 class="pt-3">{{$exer->title}}</h5>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="form-row mt-2">
                <div class="col-md-6 offset-6">
                    <a href="javascript:void(0);" class="mt-2 btn btn-primary float-right">Cancel</a>
                </div>
                <input type="hidden" name="chapter_id" value="{{$id}}">
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){

});
</script>
@stop
