@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
				<div class="app-main__inner">
					<div class="app-page-title">
						<div class="page-title-wrapper">
							<div class="page-title-heading">
								<div class="page-title-icon">
									<span class="metismenu-icon pe-7s-users icon-gradient bg-green"></span>
								</div>
								<div>
								
									<h4 class="mb-0 page-title">Student Account: {{$data[0]->student ? $data[0]->student->first_name.$data[0]->student->last_name: ''}}</h4>
									<span class="subtitle"> - This page shows the information for the student&nbsp{{$data[0]->student ? $data[0]->student->first_name.$data[0]->student->last_name: ''}}.</span>
									<div class="page-title-subheading">
										<nav id="fil-ariane">
											<a href="index.html">Home</a> > <a href="classes.html">List of Classes</a> > <a href="detail_class.html">CLASSNAME</a> > {{$data[0]->student ? $data[0]->student->first_name.$data[0]->student->last_name: ''}}
										</nav>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="tab-content">
						<div class="tab-pane tabs-animation fade show active">
							<div class="main-card mb-3 card">
								<div class="card-body">
									<h5 class="card-title">General Information</h5>
									<div class="row">
									
										<div class="col-md-3">
											<img src="{{ (isset($data[0]->student->file)?asset('storage/user_profiles/'.$data[0]->student->file):asset('assets/images/avatars/placeholder_profile.jpg')) }}" alt="{{$data[0]->student->first_name.$data[0]->student->last_name}} current profile picture" title="John Doe's current profile picture" style="width: 100%" />
										</div>
									
										<div class="col-md-3">
											<h5>{{$data[0]->student ? $data[0]->student->first_name.$data[0]->student->last_name: ''}}</h5>
											<h6>{{$data[0]->student ? $data[0]->student->email: ''}}</h6>
											<hr>
											<h6>Earned Badges</h6>
											<div class="row">
												<div class="col-3">
													<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for activating your account">
														<img src="{{asset('assets/images/badges/activation.png')}}" alt="Badge earned for activating your account" title="Badge earned for activating your account" class="img-fluid w-100"/> <br />
													</div>
												</div>
												<div class="col-3">
													<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for enrolling a class">
														<img src="{{asset('assets/images/badges/enrolled.png')}}" alt="Badge earned for enrolling a class" title="Badge earned for enrolling a class" class="img-fluid w-100"/> <br />
													</div>
												</div>
												
											</div>
										</div>
										
										<div class="col-md-6">
											<h5>List of Classes</h5>
											<table class="mb-0 table table-hover" id="myClasses">
												<thead>
												<tr>
													<th>Class Name</th>
													<th>Teacher</th>
												</tr>
												</thead>
												<tbody>
													@if(isset($data))
													@foreach($data as $k => $value)
												<tr>
													<td><a href="{{route('teacher.my-classes.show',['my_class'=>$value->class_name->id])}}">{{$value->class_name->class_name}}</a></td>
													<td>{{$value->class_name->teacher->first_name.$value->class_name->teacher->last_name}}</td>
												</tr>
												@endforeach
												@endif
												
												</tbody>
											</table>
										</div>

									</div>


									<div class="row">
										<div class="col-md-12">
											<hr>
											<h5 class="mt-4">List of Completed Exercises</h5>
											<table class="mb-0 table table-hover" id="exerciseList" style="width:100%;">
												<thead>
												<tr>
													<th>Exercise Name</th>
													<th>Class Name</th>
													<th>Book Title</th>
													<th>Chapter</th>
													<th>Grade</th>
													<th>Action</th>
												</tr>
												</thead>
												 <tbody>
												 	@if(isset($exercises_datas))
												 	@foreach($exercises_datas as $data)
													<tr>
														<td>{{$data->exercise_title}}</td>
														<td></td>
														<td>{{$data->book_title}}</td>
														<td>{{$data->chapter_title}}</td>
														<td>{{$data->student_mark}}</td>
														<td>
															<a href="{{route('teacher.exercise_result_per_student',['exer_id' =>$data->exercise_id,'book_id' => $data->book_id,'studid' => $data->student_id])}}" title="See the results" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-eye"></span> See Results</a>
															<button type="button" title="Reset the exercise" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info reset" data-stdid="{{$data->student_id}}" data-exerid="{{$data->exercise_id}}"><span class="fa fa-history"></span> Reset</button>
														</td>
													</tr>
													@endforeach
													@endif
												</tbody>
											</table>
										</div>
									</div>
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
        	var studid = $(this).attr('data-stdid');
        	var exerid = $(this).attr('data-exerid');
        	var url="{{route('teacher.reset-exercise')}}";
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
                   window.location.reload(true);
               }
          			
          },
           fail: function(){}
           });
        	}

        });
     });
			
			


$('.select_class_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
	searchNoData: '<li style="color:#ddd">No results found.</li>',
});


</script>
@stop
