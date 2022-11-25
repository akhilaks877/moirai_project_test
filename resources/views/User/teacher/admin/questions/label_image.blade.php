@extends('layouts.Teacher.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="row">
            <div class="col-10">
                <div class="font-work">
                    <h4 class="mb-0 page-title">{{ $complete_excerdetails->title }} - Add a Question</h4>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">Home</a> > <a href="{{ route('teacher.title.index') }}">Book Catalogue</a> > <a href="{{ route('teacher.title.show',['title'=>$complete_excerdetails->bookid]) }}">{!! $complete_excerdetails->bookname !!}</a> > <a href="{{ route('teacher.title.manage_bookexercise',['id'=>$complete_excerdetails->bookid,'excer'=>$complete_excerdetails->id]) }}">{{ $complete_excerdetails->title }}</a> > Multiple Choice Question
                        </nav>
                    </div>
                    <hr>
                    <div class="resume_programme mb-2">
                        <strong>{!! $complete_excerdetails->subject_name !!}</strong>
                    </div>
                    <div class="description">
                        {!! $complete_excerdetails->book_description !!}
                    </div>
                </div>
            </div>
            <div class="col-2">
                <img src="{{ asset('storage/ebooks/book_'.$complete_excerdetails->bookid.'/cover_image/'.$complete_excerdetails->book_coverimg) }}" alt="Book Cover [Book Title]" title="Book Cover [Book Title]" class="img-fluid w-100"/>
            </div>
        </div>
    </div>
    @if($errors->any())<!--error messages-->
    <div class="alert alert-danger alert-dismissible" role="alert">
       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
       @foreach ($errors->all() as $error)
       <i class="fa fa-info-circle"></i>&nbsp;{{ $error }}<br>
       @endforeach
    </div>
    @endif
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{ __('messages.question_details')}}</h5>
            <form method="POST" action="{{ route('teacher.title.up_questiontype',['id'=>$complete_excerdetails->id]) }}" enctype="multipart/form-data" data-parsley-validate>
                @csrf
                <input type="hidden" name="excercise_ent" value="{{$complete_excerdetails->id}}" readonly>
                <input type="hidden" name="question_type" value="3" readonly><!-- 3 stands for Label the image question type-->
                @isset($question_data->id)<input type="hidden" name="question_ent" value="{{ $question_data->id }}" readonly>
                <input type="hidden" name="chapter_id" value="{{$complete_excerdetails->chapid}}">
                @endisset <!-- for update question-->
                <div class="form-row d-flex">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="question_title" class="">{{ __('messages.what_is_question')}}</label>
                            <input name="question_title" id="question_title" placeholder="{{ __('messages.write_question')}}" type="text" value="{{ isset($question_data->question_data) ? $question_data->question_data:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Question is required">
                        </div>

                        <div class="position-relative form-group">
                            <label for="image_to_caption">Image to label</label>
                            <input name="image_to_caption" id="image_to_caption" type="file" class="form-control-file" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 1200 pixel minimum width. Max file size: 2MB</small>
                        </div>
                        <input type="hidden" name="newimg" readonly>
                        <h5>To add labels, simply click the location on the image that you would like to label</h5>
                        <div class="position-relative form-group border" id="div_leg_label" style="position: relative; overflow: hidden;">
                            @if(isset($answer_arrformat['label_imgpath']))
                            <img src="{{ $answer_arrformat['label_imgpath'] }}" alt="Image to label" title="Image to label" style="width: 100%;" />
                               @if(count($answer_arrformat['correct_answers']) > 0)
                               @foreach ($answer_arrformat['correct_answers'] as $corr_val)
                                <div class="input-group add_input draggable" style="position: absolute; top: {!! $corr_val['ycordinate'] !!}%; left: {!! $corr_val['xcordinate'] !!}%; width: 250px;">
                                <div class="input-group-prepend move_input grab"><a href="javascript:void(0);" class="btn btn-secondary"><i class="fa fa-arrows-alt"></i></a> </div>
                                <input type="text" name="label_text[]"class="form-control added_input" value="{!! $corr_val['label_text'] !!}" data-parsley-required><input type="hidden" name="x_cord[]" value="{!! $corr_val['xcordinate'] !!}"><input type="hidden" name="y_cord[]" value="{!! $corr_val['ycordinate'] !!}">
                                <div class="input-group-append delete_input"><a href="javascript:void(0);" class="btn btn-secondary"><i class="fa fa-trash"></i></a></div>
                                </div>
                               @endforeach
                               @endif
                            @else
                            <img src="{{ asset('assets/images/coeur_legende.png') }}" alt="Image to label" title="Image to label" style="width: 100%;" />
                            @endif
                        </div>

                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-12">
                        <hr>
                        <div class="position-relative form-group">
                            <label for="exercice_programme" class="">{{ __('messages.questions_elements_ministry')}}</label>
                            <select name="exercice_programme" id="exercice_programme" class="form-control">
                                <option value="">Select</option>
                                @isset($programm_elements['education_programm_elements'])
                                 @foreach ($programm_elements['education_programm_elements'] as $ele)
                                <option value="{{ $ele->id }}" {{  isset($question_data->education_element) && ($question_data->education_element == $ele->id) ? 'selected':'' }}>{{ $ele->name }}</option>
                                 @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 offset-6">
                        <button type="submit" class="mt-2 ml-1 btn btn-primary float-right">{{ __('menus.create_question')}}</button>
                        <button type="reset" class="mt-2 btn btn-primary float-right">{{ __('menus.cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
    $("#image_to_caption").change(function () {
    filePreview(this);
   });

    $(document).on('click','#div_leg_label', function(event) {
		var relX = event.pageX - $(this).offset().left;
		// get the X axis of the mouse relative to the div
		var relY = event.pageY - $(this).offset().top;
		// get the Y axis of the mouse relative to the div
		var div_height = $(this).height();
		var div_width = $(this).width();
		var pourcentX = relX * 100 / div_width;
		var pourcentY =  relY * 100 / div_height;

         var label_div='<div class="input-group add_input draggable" style="position: absolute; top: '+pourcentY+'%; left: '+pourcentX+'%; width: 250px;"><div class="input-group-prepend move_input grab"><a href="javascript:void(0);" class="btn btn-secondary"><i class="fa fa-arrows-alt"></i></a>';
            label_div+='</div><input type="text" name="label_text[]"class="form-control added_input" data-parsley-required><input type="hidden" name="x_cord[]" value="'+pourcentX+'"><input type="hidden" name="y_cord[]" value="'+pourcentY+'"><div class="input-group-append delete_input"><a href="javascript:void(0);" class="btn btn-secondary"><i class="fa fa-trash"></i></a></div></div>';
		$(this).append(label_div);
		$('.added_input').last().focus();
		$( ".draggable" ).draggable({
            stop:function(e){
                var idiv_height = $('#div_leg_label').height();
		        var idiv_width = $('#div_leg_label').width();
                var xCordval =e.pageX - $('#div_leg_label').offset().left;
		         var yCordval =e.pageY - $('#div_leg_label').offset().top;

                var ipourcentX = xCordval * 100 / idiv_width;
		        var ipourcentY = yCordval * 100 / idiv_height;
                $('input[name="x_cord[]"]',this).val(ipourcentX);
                $('input[name="y_cord[]"]',this).val(ipourcentY);
            }
        });
	}).on('click', '.delete_input', function(event) {
		$(this).parents('.add_input').remove();
	}).on('click', '.move_input', function(event) {
	}).on('click', 'div', function(event) {
		// clicked on descendant div
		event.stopPropagation();
	});


    $( ".draggable" ).draggable({
            stop:function(e){
                var idiv_height = $('#div_leg_label').height();
		        var idiv_width = $('#div_leg_label').width();
                var xCordval =e.pageX - $('#div_leg_label').offset().left;
		         var yCordval =e.pageY - $('#div_leg_label').offset().top;

                var ipourcentX = xCordval * 100 / idiv_width;
		        var ipourcentY = yCordval * 100 / idiv_height;
                $('input[name="x_cord[]"]',this).val(ipourcentX);
                $('input[name="y_cord[]"]',this).val(ipourcentY);
            }
    });
});

function filePreview(input){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#div_leg_label img').remove();
            $('.add_input.draggable').remove();
            $('#div_leg_label').append('<img src="'+e.target.result+'" alt="Image to label" title="Image to label" style="width: 100%;"/>');
            $('input[name="newimg"]').val(1);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@stop
