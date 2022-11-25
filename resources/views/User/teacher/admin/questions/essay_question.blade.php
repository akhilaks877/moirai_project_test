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
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{ __('messages.question_details')}}</h5>
            <form method="POST" action="{{ route('teacher.title.up_questiontype',['id'=>$complete_excerdetails->id]) }}" data-parsley-validate>
                @csrf
               <input type="hidden" name="excercise_ent" value="{{$complete_excerdetails->id}}" readonly>
               <input type="hidden" name="question_type" value="4" readonly><!-- 4 stands for Short Answer or Essay question type-->
               @isset($question_data->id)<input type="hidden" name="question_ent" value="{{ $question_data->id }}" readonly>
                <input type="hidden" name="chapter_id" value="{{$complete_excerdetails->chapid}}">
               @endisset <!-- for update question-->
                <div class="form-row d-flex">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="question_title" class="">{{ __('messages.what_is_question')}}</label>
                            <input name="question_title" id="question_title" placeholder="{{ __('messages.write_question')}}" type="text" value="{{ isset($question_data->question_data) ? $question_data->question_data:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Question is required">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="position-relative form-group">
                            <label for="words_limit" class="">{{ __('messages.maximum_word_numbers_essay')}}</label>
                            <input name="words_limit" id="words_limit" placeholder="300" type="text" class="form-control" value="{{ isset($answer_arrformat['words_limit']) ? $answer_arrformat['words_limit']:''  }}" data-parsley-required data-parsley-type="digits" data-parsley-error-message="It should be digits">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="position-relative form-group">
                            <label for="rating_scale" class="">{{ __('messages.rating_scale')}}</label>
                            <input name="rating_scale" id="rating_scale" placeholder="6" type="text" class="form-control" value="{{ isset($answer_arrformat['rating_scale']) ? $answer_arrformat['rating_scale']:''  }}" data-parsley-required data-parsley-type="digits" data-parsley-error-message="It should be digits">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="position-relative form-group">
                            <label for="ideal_answer" class="">{{ __('messages.correct_answer')}}</label>
                            <textarea name="correct_answer" id="ideal_answer" class="form-control" data-parsley-required data-parsley-error-message="PLease fill the correct answer" rows="4">{{ isset($answer_arrformat['correct_answer']) ? $answer_arrformat['correct_answer']:''  }}</textarea>
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

});
</script>
@stop
