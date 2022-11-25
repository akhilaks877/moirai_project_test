@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="metismenu-icon pe-7s-display1 icon-gradient bg-green"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">: {{ (isset($model)?$model->class_name:'') }}</h4>
                    <span class="subtitle"> - This page shows the information for the class {{ (isset($model)?$model->class_name:'') }}.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">Home</a> > <a href="{{ route('teacher.my-classes.index') }}">List of Classes</a> > CLASSNAME
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <div class="error_lists"></div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">General Information</h5>
                    <form method="POST" data-url="{{ route('teacher.my-classes.update',['my_class'=>$model->id]) }}"  class="add_teacher_class" data-parsley-validate novalidate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="slug" id="slug" value="{{ (isset($model)?$model['slug']:'') }}">
                        <input type="hidden" name="class_entity" id="class_entity" value="{{ (isset($model)?$model['id']:'') }}">
                        <div class="form-row">
                            <div class="col-md-6">
                                <p>
                                    Class created on: {{ (isset($model)?date('d/m/Y',strtotime($model['created_at'])):'') }} at {{ (isset($model)?date('H:i',strtotime($model['created_at'])):'') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    Last modified on: {{ (isset($model['updated_at'])?date('d/m/Y',strtotime($model['updated_at'])):'') }} at {{ (isset($model['updated_at'])?date('H:i',strtotime($model['updated_at'])):'') }}
                                </p>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="class_name" class="">Class Name</label>
                                    {!! Form::text('class_name',(isset($model)?$model['class_name']:''),['class'=>'form-control','placeholder'=>__('messages.class_name'),'id'=>'class_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="select_school" class="">School</label>
                                    <div class="select_school_parent">
                                        {!! Form::select('select_school', (isset($school)?$school:[]),(isset($model)?$model['school_id']:''),["style"=>"display:none",'id'=>'select_school','class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#class_error"]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-check">
                                    <input type="checkbox" class="form-check-input" name="display_answer" id="display_answer" {{  ($model->display_answer == 1 ? ' checked' : '') }}>
                                    <label class="form-check-label" for="display_answer">Enable answer display: Each student in this class will automatically see the exercise answers after submission.</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="mt-2 btn btn-primary float-right">Save</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <hr>
                            <h5 class="mt-4">List of Students in the Class ({{ (isset($model)?$model->class_name:'') }})</h5>
                            <table class="mb-0 table table-hover" data-url="{{ route('teacher.my-classes.show',['my_class'=>$model->id]) }}" id="myStudlist" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                 <tbody>
                                  @foreach($data as $d)
                                <tr>
                                    <td><a href="detail_eleve.html">{{$d->email}}</a></td>
                                    <td><a href="detail_eleve.html">{{$d->first_name.$d->last_name}}</a></td>
                                    <td>21/04/2020</td>
                                    <td>{{$d->student_status == 0 ? 'Invitation Sent' : 'Active'}}</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-eye pr-2"></span> See Student Activity</a>
                                        <a href="javascript:void(0);" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info remove_studentclass" data-stdent="'.$student_name.'" data-trid="'.$d->id.'"><span class="fa fa-times-circle pr-2"></span> Remove Student from Class</a>
                                    </td>
                                </tr>
                               @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <form data-parsley-validate novalidate id="status_form" method="POST" action="{{ route('teacher.my-classes.addstudents') }}">
                    <div class="row d-flex">
                     @csrf
                     {{ Form::hidden('class_id',$model['id']) }}
                        <div class="col-md-8">
                            <h5>Choose students to add to the class</h5>
                            <label for="select_student" class="">Choose student(s)</label>
                            <div class="select_student_parent">
                              <select style="display:none"  name="select_student[]" data-parsley-required multiple id="select_student"
                              data-parsley-required-message="{{ __('messages.val_required') }}" data-parsley-errors-container="#class_error">
                              @if(isset($students))
                              @foreach($students as $key => $val)
                              @php $disabled = ''; @endphp
                              @if(in_array($key, $stud_class))
                                 @php $disabled = 'disabled'; @endphp
                              @endif
                                 <option value="{{ $key }}" {{ $disabled }}>{{ $val }}</option>
                              @endforeach
                             @endif
                              </select>
                            </div>
                        </div>

                        <div class="col-md-4 align-self-end mt-2">
                            <a href="javascript:void(0);" onclick="$(this).closest('form').submit()" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span>
                                Add new students to the class
                            </a>
                        </div>
                        <div class="col-12">
                            If you do not see the student you are looking for on the platform, you can send them an invitation by <a href="{{ route('teacher.my-classes.invite_students',['class'=>$model->id]) }}">clicking here</a>.
                        </div>
                    </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){

     $('a.remove_studentclass').click(function(){
      //alert("efw");
           var student_name= $(this).data('stdent');
           var student_entity=$(this).data('trid');
           var class_entity=$('input[type="hidden"][name="class_entity"]').val();
           var action_url="{{ route('teacher.my-classes.remove_studentclass') }}";
           var data_bind={"student_entity":student_entity,"class_entity":class_entity}
           if (confirm('Do you want to remove student '+student_name+' from this class?')){
            $.ajax({
                url:action_url,
                headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
               type: "POST",
               data: data_bind,
               dataType:'JSON',
               success: function(response){
                  if(response.status == 'success'){
                   $('#myStudlist').DataTable().ajax.reload();
                   window.location.reload(true);
                    }
                },
                fail:function(){
                }
              });
            }
           });
      

    $('.select_school_parent').dropdown({
		input:'<input type="text" maxLength="20" placeholder="Search">',
		searchable:true,
		searchNoData: '<li style="color:#ddd">No results found.</li>',
	});

    $('.select_student_parent').dropdown({
		input:'<input type="text" maxLength="20" placeholder="Search">',
		searchable:true,
		searchNoData: '<li style="color:#ddd">No results found.</li>',
	});

    $("#class_name").on("input", function(){
      var str = $(this).val();
      var slug = '';var trimmed = $.trim(str);
      slug = trimmed.replace(/[^a-z0-9-]/gi, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
      slug = slug.toLowerCase();$('#slug').val(slug);
      var matches = str.match(/\b(\w)/g);var acronym = matches.join('');$('#code').val(acronym.toUpperCase());
   });

   $('form.add_teacher_class').on("submit",function(e){
     e.preventDefault();
       var url=$(this).data('url');
       var form_data=$(this).serialize();
       $('div.alert-danger .error_lists').html(''); $('div.alert-danger').hide();
       $.ajax({
              url:url,
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "PATCH",
             dataType:'JSON',
             data: form_data,
            success: function(response){
            if(response.status == 'success'){
              window.location.reload(true);
            }
            if(response.status == 'invalid'){
                $lists='';
              $.each(response.messages, function (key,val){
                $lists+='<i class="fa fa-info-circle"></i>&nbsp;'+val+'<br>';
              });
              $('div.alert-danger .error_lists').append($lists);
              $('div.alert-danger').show();
            }
          },
           fail: function(){}
           });
     });

});
</script>
@stop
