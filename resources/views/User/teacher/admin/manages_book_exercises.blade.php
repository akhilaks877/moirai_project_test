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
                    <h4 class="mb-0 page-title">{!! $book_data->title !!} - Create a New Exercise</h4>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">Home</a> > <a href="{{ route('teacher.title.index') }}">Book Catalogue</a> > <a href="{{ route('teacher.title.show',['title'=>$book_data->id]) }}">{!! $book_data->title !!}</a> >{{ isset($excer_editdat) ? ucfirst($excer_editdat->title) : 'New Exercise'  }}
                        </nav>
                    </div>
                    <hr>
                    <div class="resume_programme mb-2">
                        <strong>@isset($book_data->subject_name){!! $book_data->subject_name !!}@endisset</strong>
                    </div>
                    <div class="description">
                        {!! $book_data->description !!}
                    </div>
                </div>
            </div>
            <div class="col-2">
                <img src="{{ asset('storage/ebooks/book_'.$book_data->id.'/cover_image/'.$book_data->cover_image) }}" alt="Book Cover [Book Title]" title="Book Cover [Book Title]" class="img-fluid w-100"/>
            </div>
        </div>
    </div>
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">General Information</h5>
            <form method="POST" action="{{ route('teacher.title.create_upbookexercise',['id'=>$book_data->id]) }}" enctype="multipart/form-data" data-parsley-validate>
                @csrf
                <input type="hidden" name="book_entity" value="{{ $book_data->id }}" readonly />
                @isset($excer_editdat->id) <input type="hidden" name="excercise_ent" value="{{ $excer_editdat->id }}" readonly /> @endisset
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="exercice_title" class="">Exercise Title</label>
                            <input name="exercice_title" id="exercice_title" placeholder="Exercise 1" type="text" value="{{ isset($excer_editdat->title) ? $excer_editdat->title:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Tiltle is required">
                        </div>
                        <div class="position-relative form-group">
                            <label for="quizz_block" id="label_quizz_block">Insert this exercise at the end of:</label>
                            <select name="chapter" id="quizz_block" class="form-control" data-parsley-required data-parsley-error-message="Chapter is required">
                                <option value="">Select</option>
                                @foreach ($data['chapters'] as $k=>$chapter)
                                <option value="{{ $chapter->id }}" {{  isset($excer_editdat->chapter_id) && ($excer_editdat->chapter_id == $chapter->id) ? 'selected':'' }}>Chapter {{ $k+1 }}: {{ $chapter->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="position-relative form-group">
                            <label for="exercice_time" class="">This exercise must be completed in less than (in minutes)</label>
                            <input name="exercice_time" id="exercice_time" placeholder="30" type="text" value="{{ isset($excer_editdat->completion_time) ? $excer_editdat->completion_time:''  }}" class="form-control">
                            <small class="form-text text-muted">Leave blank if there is no time limit</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="imgPreview"></div>
                        @if(isset($excer_editdat->illu_img))
                        <img src="{{ asset('storage/ebooks/book_'.$book_data->id.'/chapter_'.$excer_editdat->chapter_id.'/illustration_image/'.$excer_editdat->illu_img) }}" alt="Exercise Illustration" title="Exercise Illustration" style="width: 100%">
                        @else
                        <img src="{{ asset('assets/images/avatars/placeholder_profile.jpg') }}" alt="Exercise Illustration" title="Exercise Illustration" style="width: 100%">
                        @endif

                        <label for="exampleFile">Exercise Illustration</label>
                        <input name="illufile" id="illuFile" type="file" accept="image/*" class="form-control-file">
                        <small class="form-text text-muted">Recommended size: 400x400 pixels. Max file size: 1 MB</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 offset-6">
                        <button type="submit" class="mt-2 ml-1 btn btn-primary float-right" id="label_save">{{__('menus.publish')}}</button>
                        <a href="{{ isset($excer_editdat->id) ? route('teacher.title.excercise-question_manage',['id'=>$excer_editdat->id]) : 'javascript:void(0);'    }}" class="mt-2 ml-1 btn btn-primary float-right {{ isset($excer_editdat->id) ? '' : 'disabled'}}"><span class="fa fa-plus"></span>{{__('menus.add_questions')}}</a>
                        <button type="reset" class="mt-2 btn btn-primary float-right">{{__('menus.cancel')}}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @isset($excer_editdat->id)
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{__('messages.list_of_questions')}}</h5>
            <div class="form-row">
                <div class="col-md-12">
                    <table class="mb-0 table table-hover dataTable table-custom data-table js-exportable" data-url="{{ route('teacher.title.manage_bookexercise',['id'=>$excer_editdat->book_id,'excer'=>$excer_editdat->id]) }}" id="qslistTable" style="width:100%;">
                        <thead>
                        <tr>
                            <th>{{__('messages.question')}}</th>
                            <th>{{__('messages.title')}}</th>
                            <th>{{__('messages.type')}}</th>
                            <th>{{__('messages.action')}}</th>
                        </tr>
                        </thead>
                        {{-- <tbody>
                        <tr>
                            <td>1</td>
                            <td>Lorem ipsum dolor sit amet ?</td>
                            <td>Multiple Choice</td>
                            <td>
                                <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                                <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Lorem ipsum dolor sit amet?</td>
                            <td>Word Pairs</td>
                            <td>
                                <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                                <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Lorem ipsum dolor sit amet?</td>
                            <td>Label the Image</td>
                            <td>
                                <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                                <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                            </td>
                        </tr>
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endisset
</div>
@stop
@section('page-script')
<script>
$(function(){
    var table=$("#qslistTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
         url: $(this).data('url'),
         type: "get",
         data: function(d) {
            d._token = '{{ csrf_token() }}';
          }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'title', name: 'title' },
            { data: 'type', name: 'type' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        fnDrawCallback: function( oSettings ){

            $('.delExcercseData').on('click',function(){
               
                var excer_ent=$(this).data('eid');
                if (confirm('Do you want to remove this excercise question from this book?')){
                    $.ajax({
                       url:'{{ route('teacher.title.excercise-delete_question') }}',
                      headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                      type: "POST",
                      data: { "excer_ent":excer_ent},
                      dataType: 'json',
                      success: function(response){
                        if(response.status == 'success'){
                        $('#qslistTable').DataTable().ajax.reload();
                        }
                       },
                      fail: function() {
                      }
                      });
                     }
            //   alert($(this).data('bid'));
            });
        },
        "createdRow": function(row, data, dataIndex){},
     });

    $("#illuFile").change(function(){
    filePreview(this);
   });
});

function filePreview(input){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imgPreview + img').remove();
            $('#imgPreview').after('<img src="'+e.target.result+'" alt="Book cover [Book title]" title="Book cover [Book title]" style="width:100%"/>');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@stop
