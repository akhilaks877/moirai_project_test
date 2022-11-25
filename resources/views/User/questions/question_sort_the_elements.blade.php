@extends('layouts.Teacher.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop


				<div class="app-main__inner">
					<div class="app-page-title">
						<div class="page-title-wrapper">
							<div class="page-title-heading">
								<div class="page-title-icon" style="padding: 0px; width: inherit;">
									   <img src="{{ asset('storage/ebooks/book_'.$complete_excerdetails->bookid.'/cover_image/'.$complete_excerdetails->book_coverimg) }}" alt="couverture du livre [titre du livre]" title="couverture du livre [titre du livre]" style="height:60px" />								</div>
								</div>
								<div>
									<h4 class="mb-0 page-title">{{ $complete_excerdetails->title }} - Create a "{{ __('menus.sort_the_elements')}}" Question</h4>
									<span class="subtitle"> - You are creating a "Sort the Elements" question for the exercise {{ $complete_excerdetails->title }}</span>
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
							<form method="POST" action="{{ route('admin.title.up_questiontype',['id'=>$complete_excerdetails->id]) }}" enctype='multipart/form-data' data-parsley-validate>
								@csrf
								<input type="hidden" name="excercise_ent" value="{{$complete_excerdetails->id}}" readonly>
               					<input type="hidden" name="question_type" value="6" readonly>
               					@isset($question_data->id)<input type="hidden" name="question_ent" value="{{ $question_data->id }}" readonly>@endisset <!-- for update question-->
								<input type="hidden" name="sort_form" value="1">
								<div class="form-row d-flex">
									<div class="col-md-12">
										<div class="position-relative form-group">
											<label for="question_title" class="">{{ __('messages.what_is_question')}}</label>
											<input name="question_title" id="question_title" placeholder="Write the question here" type="text" class="form-control">
										</div>
										
										<div class="position-relative form-group" id="element_sort">
											<p>{{ __('messages.add_the_elements')}}</p>
											

											<ul class="list-group mt-3">
												<li class="list-group-item p-0">
													<img src="{{asset('assets/images/test_tri_1.png')}}" alt="Image to sort" title="Image to sort" class="img-fluid" />
													<div class="card-shadow-primary border mb-3 card card-body border-primary p-1">
														<label for="file1" class="mb-0">Choose the element image</label>
														<input name="file1" id="file1" type="file" class="form-control-file">
                                                    </div>
													<a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_element float-right"><span class="fa fa-trash"></span></a>
												</li>
												
												
												
											</ul>
											
											
										</div>
									</div>
									<div class="col-md-1 mx-auto text-center">
										<a href="#nogo" class="btn btn-primary" id="add_sortable_element"><span class="fa fa-plus"></span></a>
									</div>
								</div>
								
								
							








								<div class="form-row">
									<div class="col-md-12">
										<hr>
										<div class="position-relative form-group">
											<label for="exercice_programme" class="">Elements of the Ministry program associated with this question</label>
											<select name="exercice_programme" id="exercice_programme" class="form-control">
												<option>Lorem Ipsum Dolor sit amet</option>
												<option>Lorem Ipsum Dolor sit amet</option>
												<option>Lorem Ipsum Dolor sit amet</option>
												<option>Lorem Ipsum Dolor sit amet</option>
											</select>
										</div>
									</div>
								</div>
							<div class="form-row">
									<div class="col-md-6 offset-6">
										<button type="submit" class="mt-2 ml-1 btn btn-primary float-right" onclick="return ValidateImageBox()">{{ __('menus.create_question')}}</button>
										<button type="reset" class="mt-2 btn btn-primary float-right">{{ __('menus.cancel')}}</button>
									</div>
								</div>
							</form>
						</div>
					</div>
						
				

				@stop

@section('page-script')
<script>
	$(function(){
	});
</script>
@stop

				
