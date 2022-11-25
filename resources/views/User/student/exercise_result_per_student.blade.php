@extends('layouts.Student.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
					<div class="app-page-title">
						<div class="row">
							<div class="col-10">
								<div class="font-work">
									<h4 class="mb-0 page-title">{{$book_title ? $book_title : '' }}- Results for {{$exercise_title ? $exercise_title : ''}} </h4>
									<div class="page-title-subheading">
										<nav id="fil-ariane">
											<a href="index.html">Home</a> > <a href="all-titles.html">Book Catalogue</a> > <a href="books_detail_student.html">{{$book_title ? $book_title : '' }}</a> > <a href="exercice_results.html">{{$exercise_title ? $exercise_title : ''}} Results</a> > {{$student_name ? $student_name : ''}} Results
										</nav>
									</div>
									<hr>
									<div class="resume_programme mb-2">
										<strong>{{$book_subtitle ? $book_subtitle : '' }}</strong>
									</div>
									<div class="description">
										{{$book_description ? $book_description : '' }}
									</div>
								</div>
							</div>
							<div class="col-2">
								<img src="{{asset('storage/ebooks/book_'.$book_id.'/cover_image/'.$book_cover_img) }}" alt="Book Cover [Book Title]" title="Book Cover [Book Title]" class="img-fluid w-100"/>
							</div>
						</div>
					</div>
					
					<div class="tab-content">
						<div class="tab-pane tabs-animation fade show active">
							<div class="main-card mb-3 card">
								<div class="card-body">
									<h5 class="card-title"><a href="#">{{$student_name ? $student_name : ''}} </a>'s Answers</h5>
									<form class="see_results">
										<div class="row">
											<div class="col-12">
												<h6>Results</h6>
											</div>
											<div class="col-md-4">
												<div class="card-shadow-warning border mb-3 card card-body border-warning">
													<h6>Final Grade Earned</h6>
													<span class="exercice_mark text-center">{{isset($studentmark) ? $studentmark : ''}}</span>
													<span class="questions_answered text-center">({{$question_attended_count ? $question_attended_count : ''}} out of {{$question_count ? $question_count : ''}} questions)</span>
												</div>
											</div>
											<div class="col-md-4">
												<div class="card-shadow-success border mb-3 card card-body border-success">
													<h6>Demonstrated Skills</h6>
													<ul>
														<li>Associated Ministry Program Element</li>
														<li>Associated Ministry Program Element</li>
														<li>Associated Ministry Program Element</li>
													</ul>
												</div>
											</div>
											<div class="col-md-4">
												<div class="card-shadow-danger border mb-3 card card-body border-danger">
													<h6>Needs Improvement</h6>
													<ul>
														<li>Associated Ministry Program Element</li>
														<li>Associated Ministry Program Element</li>
														<li>Associated Ministry Program Element</li>
													</ul>
												</div>
											</div>
										</div>
										
										

										<div class="row">
											<div class="col-12">
												 @foreach($qdata as $qd)
                                        			{!! $qd !!}
                                        		 @endforeach

												
											</div>
										
										</div>
									
										
										

										

										

										
									
										
										
										
									
										
										
										
										<div class="row">	
											<div class="col-12">
												<a href="{{route('student.show-book',['id'=>$book_id])}}" class="mt-2 btn btn-primary float-right ml-2">Return to Exercise</a>
												@if($type==0)
												<button type="button" class="mt-2 btn btn-danger float-right reset" data-studid="{{$student_id}}" data-exerid="{{$exer_id}}">Reset Exercise</button>
												@endif
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

   

        $('.reset').on('click',function(e)
        {
        	//alert("clicked");
        	if (confirm('Are you sure want to reset this exercise?')){
        	var studid = $(this).attr('data-studid');
        	var exerid = $(this).attr('data-exerid');
        	var url="{{route('student.reset-exercise')}}";
        	//alert(exerid);
        	 $.ajax({
              url:url,
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "GET",
             dataType:'JSON',
             data: {studentId : studid,exerciseId : exerid},
             success: function(response){
            	if(response.status == 'success')
            	{
                   alert('Exercise reset successfully!!');
                   window.location.href="{{route('student.show-book',['id'=>$book_id])}}"
               }
          			
          },
           fail: function(){}
           });
        	}

        });
     });


    $("#background-color_menu").spectrum({
        type: "color"
    });
    $("#font-color_menu").spectrum({
        type: "color"
    });
    $("#font-color_readings").spectrum({
        type: "color"
    });
    $("#background-color_readings").spectrum({
        type: "color"
    });
	
	
	
	
</script>
@stop