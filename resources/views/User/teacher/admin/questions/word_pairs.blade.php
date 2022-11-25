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
               <input type="hidden" name="question_type" value="2" readonly><!-- 2 stands for Word Pairs question type-->
               @isset($question_data->id)<input type="hidden" name="question_ent" value="{{ $question_data->id }}" readonly>
               <input type="hidden" name="chapter_id" value="{{$complete_excerdetails->chapid}}">
               @endisset <!-- for update question-->
                <div class="form-row d-flex">
                    <div class="col-md-12">

                        <div class="position-relative form-group">
                            <label for="question_title" class="">{{ __('messages.what_is_question')}}</label>
                            <input name="question_title" id="question_title" placeholder="{{ __('messages.write_question')}}" type="text" value="{{ isset($question_data->question_data) ? $question_data->question_data:''  }}" class="form-control" data-parsley-required data-parsley-error-message="Question is required">
                        </div>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <label id="liste_mots" class="">{{ __('messages.create_wordpairs')}}</label>
                    </div>
                </div>


                <div class="associated_word_list">
                    @if(isset($answer_arrformat['correct_answers']))
                    @foreach ($answer_arrformat['correct_answers'] as $left_pair=>$right_pair)
                    <div class="form-row d-flex mb-2">
                        <div class="col-sm-5">
                            <div class="position-relative form-group">
                                <label for="mot1" class="just_for_screereaders">Word 1</label>
                                <input name="left_pair[]" id="mot1" placeholder="Word 1" type="text" aria-labelledby="liste_mots" value="{{ $left_pair }}" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field">
                            </div>
                        </div>
                        <div class="col-sm-1 text-center mt-2">
                            <h6>{{ __('messages.goes_with')}}</h6>
                        </div>
                        <div class="col-sm-5">
                            <div class="position-relative form-group">
                                <label for="mot2" class="just_for_screereaders">Word 2</label>
                                <input name="right_pair[]" id="mot2" placeholder="Word 2" type="text" aria-labelledby="liste_mots" value="{{ $right_pair }}" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="position-relative form-group">
                                <a href="javascript:void(0);" class="btn btn-secondary mt-1 float-right trash_associated_wordpair"><span class="fa fa-trash"></span></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="form-row d-flex mb-2">
                        <div class="col-sm-5">
                            <div class="position-relative form-group">
                                <label for="mot1" class="just_for_screereaders">Word 1</label>
                                <input name="left_pair[]" id="mot1" placeholder="Word 1" type="text" aria-labelledby="liste_mots" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field">
                            </div>
                        </div>
                        <div class="col-sm-1 text-center mt-2">
                            <h6>{{ __('messages.goes_with')}}</h6>
                        </div>
                        <div class="col-sm-5">
                            <div class="position-relative form-group">
                                <label for="mot2" class="just_for_screereaders">Word 2</label>
                                <input name="right_pair[]" id="mot2" placeholder="Word 2" type="text" aria-labelledby="liste_mots" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="position-relative form-group">
                                <a href="javascript:void(0);" class="btn btn-secondary mt-1 float-right trash_associated_wordpair"><span class="fa fa-trash"></span></a>
                            </div>
                        </div>
                    </div>

                    <div class="form-row d-flex mb-2">
                        <div class="col-sm-5">
                            <div class="position-relative form-group">
                                <label for="mot3" class="just_for_screereaders">Word 3</label>
                                <input name="left_pair[]" id="mot3" placeholder="Word 3" type="text" aria-labelledby="liste_mots" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field">
                            </div>
                        </div>
                        <div class="col-sm-1 text-center mt-2">
                            <h6>{{ __('messages.goes_with')}}</h6>
                        </div>
                        <div class="col-sm-5">
                            <div class="position-relative form-group">
                                <label for="mot4" class="just_for_screereaders">Word 4</label>
                                <input name="right_pair[]" id="mot4" placeholder="Word 4" type="text" aria-labelledby="liste_mots" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="position-relative form-group">
                                <a href="javascript:void(0);" class="btn btn-secondary mt-1 float-right trash_associated_wordpair"><span class="fa fa-trash"></span></a>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="form-row d-flex">
                    <div class="col-md-1 mx-auto text-center">
                        <a href="javascript:void(0);" class="btn btn-primary add_associated_wordpair"><span class="fa fa-plus"></span></a>
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
    $(".add_associated_wordpair").on('click', function() {
	$('.associated_word_list').append('<div class="form-row d-flex mb-2"><div class="col-sm-5"><div class="position-relative form-group"><label for="mot1" class="just_for_screereaders">Word 1</label><input name="left_pair[]" id="mot1" placeholder="Word 1" type="text" aria-labelledby="liste_mots" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field"></div></div><div class="col-sm-1 text-center mt-2"><h6>{{ __('messages.goes_with')}}</h6></div><div class="col-sm-5"><div class="position-relative form-group"><label for="mot2" class="just_for_screereaders">Word 2</label><input name="right_pair[]" id="mot2" placeholder="Word 2" type="text" aria-labelledby="liste_mots" class="form-control mb-2" data-parsley-required data-parsley-error-message="Please Fill The Field"></div></div><div class="col-sm-1"><div class="position-relative form-group"><a href="javascript:void(0);" class="btn btn-secondary mt-1 float-right trash_associated_wordpair"><span class="fa fa-trash"></span></a></div></div></div>');
});

$(".associated_word_list").on('click','.trash_associated_wordpair', function() {
    var con=($('.trash_associated_wordpair').length > 2) ? $(this).parent().parent().parent().remove() : '';
	//$(this).parent().parent().parent().remove();
});
});
</script>
@stop
