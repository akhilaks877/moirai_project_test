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
                    <h4 class="mb-0 page-title">{{ $complete_excerdetails->title }} - Create a "True or False" Question</h4>
                    <span class="subtitle"> - You are creating a "True or False" question for the exercise "{{ $complete_excerdetails->title }}".</span>
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
               <input type="hidden" name="question_type" value="5" readonly><!-- 5 stands for true or false question type-->
               @isset($question_data->id)<input type="hidden" name="question_ent" value="{{ $question_data->id }}" readonly>@endisset <!-- for update question-->
                <div class="form-row d-flex">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="question_title" class="">What is the question?</label>
                            <input name="question_title" id="question_title" placeholder="{{ __('messages.write_question')}}" type="text" value="{{ isset($question_data->question_data) ? $question_data->question_data:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Question is required">
                        </div>

                        <div class="position-relative form-group">
                            What is the answer?
                            <fieldset class="position-relative form-group">
                                <div class="position-relative form-check">
                                    <label class="form-check-label" for="vrai">
                                    <input name="real_answer" type="radio" class="form-check-input" value="1" {{ isset($answer_arrformat['correct_answers']) && ($answer_arrformat['correct_answers'] == true) ? 'checked':''  }} id="vrai" data-parsley-required data-parsley-error-message="Please choose the answer"> True
                                    </label>
                                </div>
                                <div class="position-relative form-check">
                                    <label class="form-check-label" for="faux">
                                        <input name="real_answer" type="radio" class="form-check-input" value="0" {{ isset($answer_arrformat['correct_answers']) && ($answer_arrformat['correct_answers'] == false) ? 'checked':''  }} id="faux"> False
                                    </label>
                                </div>
                            </fieldset>
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
                        {{-- <a href="new-exercice.html" class="mt-2 ml-1 btn btn-primary float-right">Create question</a>
                        <a href="new-questions.html" class="mt-2 btn btn-primary float-right">Cancel</a> --}}
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
});
</script>
@stop
