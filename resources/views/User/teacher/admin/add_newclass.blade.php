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
                    <h4 class="mb-0 page-title">Add a New Class</h4>
                    <span class="subtitle"> - This page allows you to create a new class.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">Home</a> > <a href="{{ route('teacher.my-classes.index') }}">List of Classes</a> > Create a New Class
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
                <p>Adding a class allows you to interact with students, leave notes in their texts, and create personalized exercises for them to complete online.</p>
                    <form method="POST" data-url="{{ route('teacher.my-classes.store') }}" class="add_teacher_class" data-parsley-validate novalidate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="slug" id="slug">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="class_name" class="">Class Name</label>
                                    {!! Form::text('class_name','',['class'=>'form-control','placeholder'=>__('messages.class_name'),'id'=>'class_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="select_school" class="">School</label>
                                    <div class="select_school_parent">
                                      {!! Form::select('select_school', (isset($school)?$school:[]),'',["style"=>"display:none",'id'=>'select_school','class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#class_error"]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-check">
                                    <input type="checkbox" class="form-check-input" name="display_answer" id="display_answer">
                                     <label class="form-check-label" for="display_answer">
                                        Enable answer display: Each student in this class will automatically see the exercise answers after submission.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button class="mt-2 btn btn-primary save_invite float-right">Save and invite students</button>
                                <button  class="mt-2 btn btn-primary save float-right mr-3">Save</button>
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
    $('.select_school_parent').dropdown({
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

   $(".save,.save_invite").on("click", function(e){
       var button_stat=$(this);
       var url=$('form.add_teacher_class').data('url');
       var form_data=$('form.add_teacher_class').serialize();
       $('div.alert-danger .error_lists').html(''); $('div.alert-danger').hide();
       if ($('form.add_teacher_class').parsley().isValid()) {
        console.log(url);
             e.preventDefault();
              $.ajax({
              url:url,
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "POST",
             dataType:'JSON',
             data: form_data,
            success: function(response){
            if(response.status == 'success'){
               var new_class=response.data.id;
               if(button_stat.hasClass("save")){
                var redirection_url="{{ route('teacher.my-classes.index') }}"
                window.location.href=redirection_url;
               }
               if(button_stat.hasClass("save_invite")){
                var redirection_url="{{ route('teacher.my-classes.invite_students', [':class']) }}".replace(':class', new_class);
                window.location.href=redirection_url;
               }

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
        }
        else{
             console.log('invalid');
            }
   });

});
</script>
@stop
