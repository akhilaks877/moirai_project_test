@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon" style="padding: 0px; width: inherit;">
                @isset($book_data->cover_image)<img src="{{ asset('storage/ebooks/book_'.$book_data->id.'/cover_image/'.$book_data->cover_image) }}" alt="Book cover [Book Title]" title="Book cover [Book Title]" style="height:60px" />@endisset
                </div>
                <div>
                    <h4 class="mb-0 page-title">{{ $book_data->title }} - {{__('messages.create_new_exercise')}}</h4>
                    <span class="subtitle"> - {{__('messages.create_exercise_notice')}}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="javascript:void(0);">{{ $book_data->title }}</a> > <a href="javascript:void(0);">List of Exercises</a> >{{ isset($excer_editdat) ? ucfirst($excer_editdat->title) : 'New Exercise'  }}
                        </nav>
                    </div>
                </div>
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
            <h5 class="card-title">{{__('messages.general_information')}}</h5>
            <form class="" action="{{ route('admin.title.create_upbookexercise',['id'=>$book_data->id]) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                @csrf
            <input type="hidden" name="book_entity" value="{{ $book_data->id }}" readonly />
            @isset($excer_editdat->id) <input type="hidden" name="excercise_ent" value="{{ $excer_editdat->id }}" readonly /> @endisset
                <div class="form-row">
                    <div class="col-md-6">
                        <label for="type_exercice" class="req">{{__('messages.type_of_exercise')}}</label>
                        <select name="type_exercice" id="type_exercice" class="form-control" data-parsley-required data-parsley-error-message="Excercise Type is required">
                            <option value="">Select</option>
                            <option value="1" {{  isset($excer_editdat->exercise_type) && ($excer_editdat->exercise_type == '1') ? 'selected':'' }}>Chapter Test</option> <!-- 1 for exam -->
                            <option value="0" {{  isset($excer_editdat->exercise_type) && ($excer_editdat->exercise_type == '0') ? 'selected':'' }}>Learning Exercise</option>  <!-- 0 for training -->
                        </select>
                    </div>
                    <div class="col-md-6" id="type_exercice_description">
                        <h6>{{__('messages.create_chapter_test')}}</h6>
                        <p>{{__('messages.chapter_test_info')}}</p>
                        <ul>
                            <li>{{__('messages.chapter_test_info2')}}</li>
                            <li>{{__('messages.chapter_test_info3')}}</li>
                            <li>{{__('messages.chapter_test_info4')}}</li>
                        </ul>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-12">
                    <hr>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="exercice_title" class="req">{{__('messages.exercises_title')}}</label>
                            <input name="exercice_title" id="exercice_title" placeholder="Exercise 1" type="text" value="{{ isset($excer_editdat->title) ? $excer_editdat->title:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Tiltle is required">
                        </div>
                        <div class="position-relative form-group">
                            <label for="quizz_block" id="label_quizz_block" class="req">{{__('messages.place_exercise')}}</label>
                            <select name="chapter" id="quizz_block" class="form-control" data-parsley-required data-parsley-error-message="Chapter is required">
                                <option value="">Select</option>
                                @foreach ($data['chapters'] as $k=>$chapter)
                                <option value="{{ $chapter->id }}" {{  isset($excer_editdat->chapter_id) && ($excer_editdat->chapter_id == $chapter->id) ? 'selected':'' }}>Chapter {{ $k+1 }}: {{ $chapter->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="position-relative form-group">
                            <label for="exercice_time" class="">{{__('messages.exercise_time')}}</label>
                            <input name="exercice_time" id="exercice_time" placeholder="30" type="text" value="{{ isset($excer_editdat->completion_time) ? $excer_editdat->completion_time:''  }}" class="form-control">
                            <small class="form-text text-muted">{{__('messages.exercise_time_info')}}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="imgPreview"></div>
                        @if(isset($excer_editdat->illu_img))
                        <img src="{{ asset('storage/ebooks/book_'.$book_data->id.'/chapter_'.$excer_editdat->chapter_id.'/illustration_image/'.$excer_editdat->illu_img) }}" alt="Exercise Illustration" title="Exercise Illustration" style="width: 100%">
                        @else
                        <img src="{{ asset('assets/images/avatars/placeholder_profile.jpg') }}" alt="Exercise Illustration" title="Exercise Illustration" style="width: 100%">
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label for="exampleFile">{{__('messages.exercises_illustration')}}</label>
                        <input name="illufile" id="illuFile" type="file" accept="image/*" class="form-control-file">
                        <small class="form-text text-muted">{{__('messages.exercises_illustration_info')}}</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 offset-6">
                        <button type="submit" class="mt-2 ml-1 btn btn-primary float-right" id="label_save">{{__('menus.publish')}}</button>
                        <a href="{{ isset($excer_editdat->id) ? route('admin.title.excercise-question_manage',['id'=>$excer_editdat->id]) : 'javascript:void(0);'    }}" class="mt-2 ml-1 btn btn-primary float-right {{ isset($excer_editdat->id) ? '' : 'disabled'}}"><span class="fa fa-plus"></span>{{__('menus.add_questions')}}</a>
                        <button type="reset" class="mt-2 btn btn-primary float-right">{{__('menus.cancel')}}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- list of questions associated with the excercise -->
    @isset($excer_editdat->id)
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{__('messages.list_of_questions')}}</h5>
            <div class="form-row">
                <div class="col-md-12">
                    <table class="mb-0 table table-hover dataTable table-custom data-table js-exportable" data-url="{{ route('admin.title.add_bookexercise',['id'=>$excer_editdat->book_id,'excer'=>$excer_editdat->id]) }}" id="qslistTable" style="width:100%;">
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
                                <a href="#" class="mb-2 mr- 2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
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


            $('.delExcercseData').on('click',function()
            {
                var excer_ent=$(this).data('eid');
                //alert(excer_ent);
                   if (confirm('Do you want to remove this excercise question  from this book?')){
                    $.ajax({
                       url:'{{ route('admin.title.remov_theexceciseData') }}',
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
