@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
					<input type="file">
				<div class="app-main__inner">
					<div class="app-page-title">
						<div class="page-title-wrapper">
							<div class="page-title-heading">
								<div class="page-title-icon" style="padding: 0px; width: inherit;">
                                    <img src="{{ asset('storage/ebooks/book_'.$complete_excerdetails->bookid.'/cover_image/'.$complete_excerdetails->book_coverimg) }}" alt="couverture du livre [titre du livre]" title="couverture du livre [titre du livre]" style="height:60px" />								</div>
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
               					<input type="hidden" name="question_type" value="6" readonly><!-- 1 stands for Multiple Choice question type-->
               					@isset($question_data->id)<input type="hidden" name="question_ent" value="{{ $question_data->id }}" readonly>@endisset <!-- for update question-->
								<input type="hidden" name="sort_form" value="1">
									<div class="form-row d-flex">
									<div class="col-md-12">
										<div class="position-relative form-group">
											<label for="question_title" class="">{{ __('messages.what_is_question')}}</label>
											<input name="question_title" id="question_title" placeholder="Write the question here" type="text" class="form-control" data-parsley-required data-parsley-error-message="Question is required" value="{{ isset($question_data->question_data) ? $question_data->question_data:''}}">
										</div>
										
										<div class="position-relative form-group" id="element_sort">
											<p>{{ __('messages.add_the_elements')}}</p>

												
												




											


											<ul class="list-group mt-3">
											@if(isset($answer_arrformat))
											
											<input type="hidden" name="edit-images" value="1" class="edit-images">
											 @php $j=50;@endphp
											@foreach($answer_arrformat as $k => $value)
											
												<li class="list-group-item p-0">
													<img src="{{$answer_arrformat[$k]}}" height=350 width=350 alt="Image to sort" title="Image to sort" class="img-fluid{{$j}}"/>
													<div class="card-shadow-primary border mb-3 card card-body border-primary p-1">
														<label for="file1" class="mb-0">Choose the element image</label>
														<input name="sortfile[]" id="file[]" type="file" class="form-control-files" onchange="readEditedFile(this,{{$j}});">
														 <input type="hidden" name="edit-image[]" class="edit-image{{$j}}" value="{{$answer_arrformat[$k]}}">
                                                    </div>
													<a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_element float-right"><span class="fa fa-trash"></span></a>
												</li>
 											@php $j++; @endphp
 											@endforeach

											@endif
											
											
											</ul>
											
									    </div>
									</div>
									
									<div class="position-relative form-group">
									<label for="image_to_caption">Add image:</label>

									
										<a href="#nogo" class="btn btn-primary" id="add_element"><span class="fa fa-plus" data-parsley-required data-parsley-error-message="Question is required"></span></a>
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
										<button type="submit" class="mt-2 ml-1 btn btn-primary float-right" onclick="return ValidateImageBox()">{{ __('menus.create_question')}}</button>
										<button type="reset" class="mt-2 btn btn-primary float-right reload">{{ __('menus.cancel')}}</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
</div>
@stop

@section('page-script')
<script>
var i=0;

$(function(){
	



$('#add_element').on('click',function(e)
	{
		//alert("hiii");
		

		$('.list-group').append('<li class="list-group-item p-0"><img src="{{asset('assets/images/test_tri_1.png')}}"  alt="Image to sort"  title="Image to sort" class="img-fluid'+i+'" id="img-fluid'+i+' " multiple/><div class="card-shadow-primary border mb-3 card card-body border-primary p-1"><label for="file" class="mb-0">{{ __('messages.choose_element_image')}}</label><input name="sortfile[]" id="file[]" type="file" onchange="readFile(this);" class="form-control-file"></div><a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_element float-right"><span class="fa fa-trash"></span></a></li>');
});

$('.reload').on('click',function(e)
{
	location.reload();
});
   
});

function readFile(input) {
	//alert("changed");
//	alert("fwe");
	console.log("hiii");
    //$("#status").html('Processing...');
    counter = input.files.length;
   // var edit_images = $('.edit-images').val();
    //console.log(counter);
    for (x = 0; x < counter; x++) {
        if (input.files && input.files[x]) {
            var reader = new FileReader();
            reader.onload = function(e) {

            	
                $('.img-fluid'+i+' ').attr('src',e.target.result).attr('width',500).attr('height',500);
				i++;
            };
            reader.readAsDataURL(input.files[x]);
        }
    }
   //  if (counter == x) {
   //      $("#status").html('');
   //  }
}


	function readEditedFile(input,j)
	{
	 //console.log(i);
	counter = input.files.length;
   
    for (x = 0; x < counter; x++) {
        if (input.files && input.files[x]) {
            var reader = new FileReader();
            reader.onload = function(e) {

            	
            	
                $('.img-fluid'+j+' ').attr('src',e.target.result).attr('width',500).attr('height',500);
                $('.edit-image'+j+' ').remove();
				
            };
            reader.readAsDataURL(input.files[x]);
        }
    }
	}



</script>
@stop


