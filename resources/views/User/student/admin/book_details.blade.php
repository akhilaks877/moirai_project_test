@extends('layouts.Student.app')
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
                            <a href="{{ route('student.dashboard.index') }}">Home</a> > <a href="{{ route('student.title.index') }}">Book Catalogue</a> > {!! $book->title !!}
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
                        <a class="mt-2 btn btn-primary" href="{{ route('student.title.reading_book',['title'=>$book->id]) }}">
                            <span>Read</span>
                        </a>
                        <a class="mt-2 ml-1 btn btn-primary" href="{{ route('student.title.manage_notes',['title'=>$book->id]) }}">
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
                            <table class="mb-0 table table-hover" data-url="{{ route('student.title.show',['title'=>$book->id]) }}" id="exsLists" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>Chapter</th>
                                    <th>Exercise Title</th>
                                    <th>Type</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
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
                        <div class="col-md-8">
                            <h5 class="card-title">Table of Contents</h5>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="btn btn-primary float-right">See the Annotated Table of Contents</a>
                        </div>
                    </div>
                    <ul class="list-unstyled">
                        @foreach ($chapters as $k=>$chap)
                        <li><a href="{{ route('student.title.reading_book',['title'=>$book->id,'chapter'=>$chap->id]) }}">Chapter {{$k+1}}: {{ $chap->title}}</a></li>
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
            { data: 'grade', name: 'grade' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        fnDrawCallback: function( oSettings ){},
        "createdRow": function(row, data, dataIndex){},
     });
});

</script>
@stop
