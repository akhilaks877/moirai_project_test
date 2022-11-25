@extends('layouts.Student.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop


@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="row">
            <div class="col-10">
                <div class="font-work">
                    
                       <h4 class="mb-0 page-title">
                        Details for  {{isset($bk->title) ? $bk->title : ''}}
                        
                    </h4>
                   
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{route('student.dashboard.index')}}">Home</a> > <a href="{{route('student.title.index')}}">Book Catalogue</a> > 
                            {{isset($bk->title) ? $bk->title : ''}}
                           
                        </nav>
                    </div>
                    <hr>
                   
                   
                    <div class="resume_programme mb-2">
                        <strong>{{isset($bk->subtitle) ? $bk->subtitle : ''}}</strong>
                    </div>
                   


                    <div class="description">
                        {{isset($bk->description) ? $bk->description : ''}}                   
                    </div>
                   
                    <div class="action_button">
                        <a class="mt-2 btn btn-primary" href="{{route('student.title.reading_book',['title' => $bk->id])}}">
                            <span>Read</span>
                        </a>
                        <a class="mt-2 ml-1 btn btn-primary" href="{{route('student.title.notes-show',['title' => $bk->id])}}">
                            <span>Add Notes</span>
                        </a>
                    </div>
                    
                </div>
            </div>
            <div class="col-2 book_cover">
                <img src="{{asset('storage/ebooks/book_'.$bk->id.'/cover_image/'.$bk->cover_image) }}" alt="Book Cover [Book Title]" title="Book Cover [Book Title]" class="img-fluid w-100"/>
            </div>
        </div>
    </div>
    
    
    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav" role="tablist">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-exercice">
                <span>Exercises</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-sommaire">
                <span>Table of Contents</span>
            </a>
        </li>
    </ul>
    
    <div class="tab-content">
        
        <!-- Exercise Tab -->
        
        <div class="tab-pane tabs-animation  active" id="tab-exercice" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Exercises to Complete in this Book</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>Chapter</th>
                                    <th>Exercise Title</th>
                                    <th>Type</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if(count($bookData)>0)
                                    @foreach($bookData as $k=>$book)
                                    <tr>
                                    <td>{{ $k+1 }}</td>
                                    <td>{{$bookData[$k]->excercise_title}}</td>
                                    <td>{{(($bookData[$k]->exercise_type==1)?'Chapter Test':'')}}
                                        {{(($bookData[$k]->exercise_type==0)?'Practise Test':'')}}</td>
                                    <td>{{$bookData[$k]->attended_status == 0 ? 'Not Attended' :$bookData[$k]->student_mark}}</td>
                                    <td>
                                         @if($bookData[$k]->student_mark >=0 && $bookData[$k]->attended_status == 1)
                                        <a href="{{route('student.title.exercise_result_per_student',['exer_id'=>$bookData[$k]->excercise_id,'book_id' => $bookData[$k]->id,'type' => $bookData[$k]->exercise_type])}}" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">
                                            <span class="fa fa-eye"></span>
                                            See My Results
                                        </a>
                                        @endif
                                        @if($bookData[$k]->exercise_type==0)
                                        @if($bookData[$k]->student_mark >=0)
                                        <a href="{{route('student.title.show_exercise_form',['excer'=>$bookData[$k]->excercise_id,'chap' =>$bookData[$k]->chapter_id,'type' => 'Practise Exercise'])}}" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">
                                            <span class="fa fa-pen"></span>
                                            Redo Exercise
                                        </a>
                                        @endif
                                        @endif
                                    </td>
                                    </tr>
                                @endforeach
                                @else  
                                <tr >
                                    <td colspan="5" style="text-align:center;">No Data Found!</td>
                                </tr>
                                @endif
                                </tbody>
                            </table>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table of Contents Tab -->
        
        <div class="tab-pane tabs-animation fade" id="tab-sommaire" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="card-title">Table of Contents</h5>
                        </div>
                    </div>
                    <ul class="list-unstyled">
                        <li>
                            <a href="book_reading.html">Chapter 1: Lorem Ipsum dolor sit amet</a>
                        </li>
                        <li>
                            <a href="book_reading.html">Chapter 2: Lorem Ipsum dolor sit amet</a>
                        </li>
                        <li>
                            <a href="book_reading.html">Chapter 3: Lorem Ipsum dolor sit amet</a>
                        </li>
                        <li>
                            <a href="book_reading.html">Chapter 4: Lorem Ipsum dolor sit amet</a>
                        </li>
                        <li>
                            <a href="book_reading.html">Chapter 5: Lorem Ipsum dolor sit amet</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop