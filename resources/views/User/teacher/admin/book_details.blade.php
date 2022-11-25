@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="row">
            <div class="col-10">
                <div class="font-work">
                    <h4 class="mb-0 page-title">Details for Book {!! $book->title !!}</h4>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">Home</a> > <a href="{{ route('teacher.title.index') }}">Book Catalogue</a> > {!! $book->title !!}
                        </nav>
                    </div>
                    <hr>
                    <div class="resume_programme mb-2">
                        <strong>@isset($book->subject_name){!! $book->subject_name !!}@endisset</strong>
                    </div>
                    <div class="description">
                        {!! $book->description !!}
                    </div>
                    <div class="action_button">
                        <a class="mt-2 btn btn-primary" href="{{ route('teacher.title.reading_book',['title'=>$book->id]) }}">
                            <span>Read</span>
                        </a>
                        <a class="mt-2 ml-1 btn btn-primary" href="{{ route('teacher.title.manage_notes',['title'=>$book->id]) }}">
                            <span>Add Notes For Students</span>
                        </a>
                    </div>

                </div>
            </div>
            <div class="col-2 book_cover">
                <img src="{{ asset('storage/ebooks/book_'.$book->id.'/cover_image/'.$book->cover_image) }}" alt="Book Cover [Book Title]" title="Book Cover [Book Title]" class="img-fluid w-100"/>
            </div>
        </div>
    </div>


    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav" role="tablist">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-classes">
                <span>Classes</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-exercice">
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

        <div class="tab-pane tabs-animation fade show active" id="tab-classes" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">Classes Reading This Book</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="mb-0 table table-hover">
                                <thead>
                                    <tr>
                                        <th>Class Name</th>
                                        <th>Students</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="inactive">
                                        <td><a href="#">Class 1</a></td>
                                        <td>26</td>
                                        <td>Not Started</td>
                                        <td>
                                            <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">
                                            <span class="fa fa-graduation-cap pr-2"></span> Start Reading
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">Class 2</a></td>
                                        <td>21</td>
                                        <td>In Progress</td>
                                        <td>
                                            <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">
                                                <span class="fa fa-users pr-2"></span> Follow Student Progress
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">Class 3</a></td>
                                        <td>14</td>
                                        <td>In Progress</td>
                                        <td>
                                            <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">
                                                <span class="fa fa-users pr-2"></span> Follow Student Progress
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="inactive">
                                        <td><a href="#">Class 4</a></td>
                                        <td>17</td>
                                        <td>Not Started</td>
                                        <td>
                                            <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">
                                            <span class="fa fa-graduation-cap pr-2"></span> Start Reading
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Contributor Tab -->

        <div class="tab-pane tabs-animation fade" id="tab-exercice" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Exercises Created for this Book</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="mb-0 table table-hover" data-url="{{ route('teacher.title.show',['title'=>$book->id]) }}" id="exsLists" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>Chapter</th>
                                    <th>Exercise Title</th>
                                    <th>Type</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                {{-- <tbody>
                                <tr class="inactive">
                                    <td>1.2</td>
                                    <td>Lorem ipsum dolor sit amet</td>
                                    <td>Practice</td>
                                    <td>Moiraï</td>
                                    <td>
                                        <a href="exercice_result.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">
                                            <span class="fa fa-eye"></span>
                                            See Results
                                        </a>
                                        <a href="#nogo" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info disabled">
                                            <span class="fa fa-pen"></span>
                                            Modify
                                        </a>
                                        <a href="#nogo" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info disabled">
                                            <span class="fa fa-trash"></span>
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                </tbody> --}}
                            </table>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('teacher.title.manage_bookexercise',['id'=>$book->id]) }}" class="btn btn-primary float-right"><span class="fa fa-plus"></span> Add My Own Exercise</a>
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
                        <div class="col-md-8">
                            <h5 class="card-title">Table of Contents</h5>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="btn btn-primary float-right">See the Annotated Table of Contents</a>
                        </div>
                    </div>
                    <ul class="list-unstyled">
                        @foreach ($chapters as $k=>$chap)
                        <li><a href="{{ route('teacher.title.reading_book',['title'=>$book->id,'chapter'=>$chap->id]) }}">Chapter {{$k+1}}: {{ $chap->title}}</a></li>
                         @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

@stop
@section('page-script')
<script>
$(function(){
    var table=$("#exsLists").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
         url: $(this).data('url'),
         type: "get",
         data: function(d) {
            d._token = '{{ csrf_token() }}';
            d.show_excercises=1;
          }
        },
        columns: [
            { data: 'chapter', name: 'chapter' },
            { data: 'title', name: 'title' },
            { data: 'type', name: 'type' },
            { data: 'created_by', name: 'type' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        fnDrawCallback: function( oSettings ){},
        "createdRow": function(row, data, dataIndex){},
     });
});

</script>
@stop
