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
                    <h4 class="mb-0 page-title">{!! $complete_excerdetails->bookname !!} - Add a Question</h4>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                        <a href="{{ route('teacher.dashboard.index') }}">Home</a> > <a href="{{ route('teacher.title.index') }}">Book Catalogue</a> > <a href="{{ route('teacher.title.show',['title'=>$complete_excerdetails->bookid]) }}">{!! $complete_excerdetails->bookname !!}</a> > <a href="{{ route('teacher.title.manage_bookexercise',['id'=>$complete_excerdetails->bookid,'excer'=>$complete_excerdetails->id]) }}">{{ $complete_excerdetails->title }}</a> > New Question
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

            <h5 class="card-title">What type of question would you like to add?</h5>

            <div class="row choice_exercise">

                @foreach ($question_types as $qtype)
                  @switch($qtype->slug)
                   @case('multiple_choice')
                   @php $qimg=asset('assets/images/buttons_exercice/multiple_choice.png'); @endphp
                   @break
                   @case('word_pairs')
                   @php $qimg=asset('assets/images/buttons_exercice/word_pairs.png'); @endphp
                   @break
                   @case('label_the_image')
                   @php $qimg=asset('assets/images/buttons_exercice/label_image.png'); @endphp
                   @break
                   @case('short_answer_essay')
                   @php $qimg=asset('assets/images/buttons_exercice/essay.png'); @endphp
                   @break
                   @case('true_or_false')
                   @php $qimg=asset('assets/images/buttons_exercice/right_wrong.png'); @endphp
                   @break
                   @case('sort_the_elements')
                   @php $qimg=asset('assets/images/buttons_exercice/sort_element.png'); @endphp
                   @break
                  @endswitch
                <div class="col-6 col-md-4 col-lg-3 text-center px-5 py-3">
                    <a href="{{ route('teacher.title.excercise-add_question',['id'=>$complete_excerdetails->id,'type'=>$qtype->slug]) }}" class="">
                        <div class="button_square">
                            <div class="content">
                             <div class="bg" style="background-image: {{ $qimg }}">
                                </div>
                            </div>
                        </div>
                        <h5 class="pt-3">{{__('menus.'.$qtype->slug)}}</h5>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="form-row mt-2">
                <div class="col-md-6 offset-6">
                    <a href="javascript:void(0);" class="mt-2 btn btn-primary float-right">Cancel</a>
                </div>
            </div>
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
