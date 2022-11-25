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
                    @isset($complete_excerdetails->book_coverimg)<img src="{{ asset('storage/ebooks/book_'.$complete_excerdetails->bookid.'/cover_image/'.$complete_excerdetails->book_coverimg) }}" alt="couverture du livre [titre du livre]" title="couverture du livre [titre du livre]" style="height:60px" />@endisset
                </div>
                <div>
                   <h4 class="mb-0 page-title">{{ $complete_excerdetails->title }} - Create a "{{ __('menus.multiple_choice')}}" Question</h4>
                    <span class="subtitle"> - {{ __('messages.multiple_choice_heading')}} "{{ $complete_excerdetails->title }}".</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="javascript:void(0);">{{ $complete_excerdetails->bookname }}</a> > <a href="{{ route('admin.title.manage_book_exercise',['id'=>$complete_excerdetails->bookid]) }}">{{__('messages.list_of_exercises')}}</a> > <a href="{{ route('admin.title.add_bookexercise',['id'=>$complete_excerdetails->bookid,'excer'=>$complete_excerdetails->id]) }}">{{ $complete_excerdetails->title }}</a> > Question 1
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{ __('messages.question_details')}}</h5>
            <form method="POST" action="{{ route('admin.title.up_questiontype',['id'=>$complete_excerdetails->id]) }}" data-parsley-validate>
                @csrf
               <input type="hidden" name="excercise_ent" value="{{$complete_excerdetails->id}}" readonly>
               <input type="hidden" name="question_type" value="1" readonly><!-- 1 stands for Multiple Choice question type-->
               @isset($question_data->id)<input type="hidden" name="question_ent" value="{{ $question_data->id }}" readonly>@endisset <!-- for update question-->
                <div class="form-row d-flex">
                    <div class="col-md-12">

                        <div class="position-relative form-group">
                            <label for="question_title" class="">{{ __('messages.what_is_question')}}</label>
                            <input name="question_title" id="question_title" placeholder="{{ __('messages.write_question')}}" type="text" value="{{ isset($question_data->question_data) ? $question_data->question_data:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Question is required">
                        </div>

                        <div class="position-relative form-group">
                            <label for="correct_answer" class="">{{ __('messages.correct_answer')}}</label>
                            <input name="correct_answer" id="correct_answer" placeholder="{{ __('messages.this_correct_answer')}}" type="text" value="{{ isset($answer_arrformat['correct_answers']) ? $answer_arrformat['correct_answers'][0]:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Please Fill The Answer Field">
                        </div>
                        <hr>
                        <div class="position-relative form-group wrong_answer_list_choice">
                            <label id="wrong_answers">{{ __('messages.incorrect_answers')}}</label>

                            @if(isset($answer_arrformat['incorrect_answers']))
                             @foreach ($answer_arrformat['incorrect_answers'] as $incor_ans)
                             <div class="input-group mb-2">
                                <label for="wrong_answer1" class="just_for_screereaders">Create a false answer</label>
                             <input name="wrong_answer[]" id="wrong_answer1" aria-labelledby="wrong_answers" placeholder="{{ __('messages.this_incorrect_answer')}}" type="text" value="{{ $incor_ans }}" class="form-control" data-parsley-required data-parsley-error-message="Please Fill This Field">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary trash_wrong_answer"><span class="fa fa-trash"></span></button>
                                </div>
                             </div>
                             @endforeach
                            @else
                             <div class="input-group mb-2">
                                <label for="wrong_answer1" class="just_for_screereaders">Create a false answer</label>
                                <input name="wrong_answer[]" id="wrong_answer1" aria-labelledby="wrong_answers" placeholder="{{ __('messages.this_incorrect_answer')}}" type="text" class="form-control" data-parsley-required data-parsley-error-message="Please Fill This Field">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary trash_wrong_answer"><span class="fa fa-trash"></span></button>
                                </div>
                             </div>

                             <div class="input-group mb-2">
                                <label for="wrong_answer2" class="just_for_screereaders">Create a false answer</label>
                                <input name="wrong_answer[]" id="wrong_answer2" aria-labelledby="wrong_answers" placeholder="{{ __('messages.this_incorrect_answer')}}" type="text" class="form-control" data-parsley-required data-parsley-error-message="Please Fill This Field">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary trash_wrong_answer"><span class="fa fa-trash"></span></button>
                                </div>
                             </div>

                             <div class="input-group mb-2">
                                <label for="wrong_answer3" class="just_for_screereaders">Create a false answer</label>
                                <input name="wrong_answer[]" id="wrong_answer3" aria-labelledby="wrong_answers" placeholder="{{ __('messages.this_incorrect_answer')}}" type="text" class="form-control" data-parsley-required data-parsley-error-message="Please Fill This Field">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary trash_wrong_answer"><span class="fa fa-trash"></span></button>
                                </div>
                             </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-md-1 mx-auto text-center">
                        <a href="javascript:void(0);" class="btn btn-primary add_wrong_answer_choice"><span class="fa fa-plus"></span></a>
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
                        {{-- <a href="new-exercice.html" class="mt-2 ml-1 btn btn-primary float-right">{{ __('menus.create_question')}}</a>
                        <a href="new-questions.html" class="mt-2 btn btn-primary float-right">{{ __('menus.cancel')}}</a> --}}
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
    $(".add_wrong_answer_choice").on('click',function(){
	$('.wrong_answer_list_choice').append('<div class="input-group mb-2"><label for="wrong_answer2" class="just_for_screereaders">Add an incorrect answer</label><input name="wrong_answer[]" id="wrong_answer2" aria-labelledby="wrong_answers" placeholder="{{ __('messages.this_incorrect_answer')}}" type="text" class="form-control" data-parsley-required data-parsley-error-message="Please Fill This Field"><div class="input-group-append"><button type="button" class="btn btn-secondary trash_wrong_answer"><span class="fa fa-trash"></span></button></div></div>');
   });

   $(".wrong_answer_list_choice").on('click','.trash_wrong_answer',function(){
    var con=($('.trash_wrong_answer').length > 1) ? $(this).parent().parent().remove() : '';
 });
});
</script>
@stop
