@extends('layouts.Student.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop


				<div class="app-main__inner">
					<div class="app-page-title">
						<div class="page-title-wrapper">
							<div class="page-title-heading">
								<div class="page-title-icon">
									<span class="pe-7s-look icon-gradient bg-green">
									</span>
								</div>
								<div>
									<h4 class="mb-0 page-title">My Badges</h4>
									<span class="subtitle"> - This page shows accomplishment badges earned on the platform.</span>
									<div class="page-title-subheading">
										<nav id="fil-ariane">
											<a href="index.html">Home</a> > My Badges
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
								
									<h5 class="badge-title">Account Activation</h5>
									<div class="row">
										<div class="col-3 col-sm-3 col-md-2 text-center">
											<div data-toggle="tooltip" data-placement="top" title="Badge earned for connecting to the platform" data-original-title="Badge earned for connecting to the platform">
												<img src="{{asset('assets/images/badges/activation.png')}}" alt="Badge earned for connecting to the platform" title="Badge earned for connecting to the platform" class="img-fluid w-100"/> <br />
												<h5>Connected for the first time</h5>
											</div>
										</div>
										<div class="col-3 col-sm-3 col-md-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="Badge earned completing 100% of your profile" data-original-title="Badge earned completing 100% of your profile">
												<img src="{{asset('assets/images/badges/profilecreated.png')}}" alt="Badge earned completing 100% of your profile" title="Badge earned completing 100% of your profile" class="img-fluid w-100"/> <br />
												<h5>Profile completed</h5>
											</div>
										</div>
										<div class="col-3 col-sm-3 col-md-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="Badge earned for enrolling in a class" data-original-title="Badge earned for enrolling in a class">
												<img src="{{asset('assets/images/badges/enrolled.png')}}" alt="Badge earned for enrolling in a class" title="Badge earned for enrolling in a class" class="img-fluid w-100"/> <br />
												<h5>Enrolled in a class</h5>
											</div>
										</div>
										<div class="col-3 col-sm-3 col-md-2 text-center">
											<div data-toggle="tooltip" data-placement="top" title="Badge earned for spending 50 hours using the Moirai platform" data-original-title="Badge earned for spending 50 hours using the Moirai platform">
												<img src="{{asset('assets/images/badges/50hours.png')}}" alt="Badge earned for spending 50 hours using the Moirai platform" title="Badge earned for spending 50 hours using the Moirai platform" class="img-fluid w-100"/> <br />
												<h5>50 hours online</h5>
											</div>
										</div>
										<div class="col-3 col-sm-3 col-md-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="Badge earned for spending 100 hours using the Moirai platform" data-original-title="Badge earned for spending 100 hours using the Moirai platform">
												<img src="{{asset('assets/images/badges/100hours.png')}}" alt="Badge earned for spending 100 hours using the Moirai platform" title="Badge earned for spending 100 hours using the Moirai platform" class="img-fluid w-100"/> <br />
												<h5>100 hours online</h5>
											</div>
										</div>
										<div class="col-3 col-sm-3 col-md-2 text-center">
											<div data-toggle="tooltip" data-placement="top" title="Badge earned for connecting 5 times in a week" data-original-title="Badge earned for connecting 5 times in a week">
												<img src="{{asset('assets/images/badges/5connections.png')}}" alt="Badge earned for connecting 5 times in a week" title="Badge earned for connecting 5 times in a week" class="img-fluid w-100"/> <br />
												<h5>Connected 5 times in a week</h5>
											</div>
										</div>
										<div class="col-3 col-sm-3 col-md-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="Badge earned for connecting 10 times in a week" data-original-title="Badge earned for connecting 10 times in a week">
												<img src="{{asset('assets/images/badges/10connections.png')}}" alt="Badge earned for connecting 10 times in a week" title="Badge earned for connecting 10 times in a week" class="img-fluid w-100"/> <br />
												<h5>Connected 10 times in a week</h5>
											</div>
										</div>
									</div>

									<hr>

									<h5 class="badge-title">Badges for "Book title 1"</h5>
									
									<div class="row align-items-center">
										<div class="col-4 col-sm-3 col-md-2 text-center">
											<h6 class="badge-subtitle m-0">Readings</h6>
										</div>
										
										<div class="col-2 text-center">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 50 hours reading">
												<img src="{{asset('assets/images/badges/reading1.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 200 hours reading">
												<img src="{{asset('assets/images/badges/reading2.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 500 hours reading">
												<img src="asset('assets/images/badges/reading3.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 50 hours reading">
												<img src="{{asset('assets/images/badges/reading4.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
									</div>
									
									<div class="row align-items-center">
										<div class="col-4 col-sm-3 col-md-2 text-center">
											<h6 class="badge-subtitle m-0">Exercices</h6>
										</div>
										
										<div class="col-2 text-center">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 50 hours reading">
												<img src="{{asset('assets/images/badges/exercice1.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 200 hours reading">
												<img src="{{asset('assets/images/badges/exercice2.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 500 hours reading">
												<img src="{{asset('assets/images/badges/exercice3.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 50 hours reading">
												<img src="{{asset('assets/images/badges/exercice4.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
									</div>
									
									<div class="row align-items-center">
										<div class="col-4 col-sm-3 col-md-2 text-center">
											<h6 class="badge-subtitle m-0">Videos</h6>
										</div>
										
										<div class="col-2 text-center">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 50 hours reading">
												<img src="{{asset('assets/images/badges/video1.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 200 hours reading">
												<img src="{{asset('assets/images/badges/video2.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 500 hours reading">
												<img src="{{asset('assets/images/badges/video3.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
										</div>
										<div class="col-2 text-center inactive inactive-badge">
											<div data-toggle="tooltip" data-placement="top" title="" data-original-title="Badge earned for spending 50 hours reading">
												<img src="{{asset('assets/images/badges/video4.png')}}" alt="Multiple Choice" title="Multiple Choice" class="img-fluid w-100"/> <br />
											</div>
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