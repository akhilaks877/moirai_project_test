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
                    <h4 class="mb-0 page-title">{{ $complete_excerdetails->bookname }} - {{ $complete_excerdetails->chapname }} - {{ $complete_excerdetails->title }} - {{__('messages.add_a_question')}}</h4>
                    <span class="subtitle"> - {{__('messages.add_a_question_info')}}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                        <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="javascript:void(0);">{{ $complete_excerdetails->bookname }}</a> > <a href="{{ route('admin.title.manage_book_exercise',['id'=>$complete_excerdetails->bookid]) }}">{{__('messages.list_of_exercises')}}</a> > <a href="{{ route('admin.title.add_bookexercise',['id'=>$complete_excerdetails->bookid,'excer'=>$complete_excerdetails->id]) }}">{{ $complete_excerdetails->title }}</a> > New question
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{__('messages.add_a_question_info2')}}</h5>
            <div class="form-row">
                @foreach ($question_types as $qtype)
                <div class="col-sm-3 text-center">
                    <a href="{{ route('admin.title.excercise-add_question',['id'=>$complete_excerdetails->id,'type'=>$qtype->slug]) }}">
                     <img src="{{ asset('assets/images/placeholder.png') }}" alt="{{ $qtype->name }}" title="{{ $qtype->name }}" class="img-fluid w-100"/> <br />
                     <h5>{{__('menus.'.$qtype->slug)}}</h5>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="form-row mt-2">
                <div class="col-md-6 offset-6">
                    <a href="javascript:void(0);" class="mt-2 btn btn-primary float-right">{{__('menus.cancel')}}</a>
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
