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
									<span class="pe-7s-graph2 icon-gradient bg-green">
									</span>
								</div>
								<div>
								
									<h4 class="mb-0 page-title">Platform Analytics </h4>
									<span class="subtitle"> - This page shows the statistics for how the platform has been used.</span>
									<div class="page-title-subheading">
										<nav id="fil-ariane">
											<a href="index.html">Home</a> > Platform Analytics
										</nav>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6 col-xl-4">
							<div class="card mb-3 widget-content">
								<div class="widget-content-wrapper">
									<div class="widget-content-left">
										<div class="widget-heading">Classes</div>
										<div class="widget-subheading">Number of classes created</div>
									</div>
									<div class="widget-content-right">
										<div class="widget-numbers text-success"><span>{{isset($class_count) ? $class_count : ''}}</span></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-xl-4">
							<div class="card mb-3 widget-content">
								<div class="widget-content-wrapper">
									<div class="widget-content-left">
										<div class="widget-heading">Students</div>
										<div class="widget-subheading">Number of students in classes</div>
									</div>
									<div class="widget-content-right">
										<div class="widget-numbers text-primary"><span>{{isset($student_count) ? $student_count : ''}}</span></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-xl-4">
							<div class="card mb-3 widget-content">
								<div class="widget-content-wrapper">
									<div class="widget-content-left">
										<div class="widget-heading">Exercises</div>
										<div class="widget-subheading">Number of exercises submitted by students</div>
									</div>
									<div class="widget-content-right">
										<div class="widget-numbers text-warning"><span>{{isset($students_excercise_count) ? $students_excercise_count : ''}}</span></div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="main-card mb-3 card">
						<div class="card-body">
							<div class="row">
								<div class="col-8">
									<h5 class="card-title">Platform Use by Students</h5>
								</div>
							
								<div class="col-12">
									<canvas id="created_students"></canvas>
								</div>
							</div>
							
							
							<div class="row">
								<div class="col-8">
									<h5 class="card-title">Time Spent on the Platform by Students</h5>
								</div>
							
								<div class="col-12">
									<canvas id="students_time"></canvas>
								</div>
							</div>							
						</div>
					</div>
				</div>

@stop
@section('page-script')

<script>
	$(function(){
		
		
		var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

		var config = {
			type: 'line',
			data: {
				labels: ['January', 'February', 'March'],
				datasets: [{
					label: 'Class 1',
					backgroundColor: window.chartColors.red,
					borderColor: window.chartColors.red,
					data: [
						158,
						142,
						167
					],
					fill: false,
				}, {
					label: 'Class 2',
					fill: false,
					backgroundColor: window.chartColors.blue,
					borderColor: window.chartColors.blue,
					data: [
						0,
						14,
						38
					],
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Number of Hours Spent on the Platform Over the Last 3 Months'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Hours'
						}
					}]
				}
			}
		};


	var ctx = document.getElementById('students_time').getContext('2d');
	new Chart(ctx, config);
	var color = Chart.helpers.color;
		//
		var aget='{!! $data !!}'; 
		aget = JSON.parse(aget);
		var dataTypes = aget['invited'];
		//console.log(aget);
		var dataTypes2 = aget['subscribed'];
		//var dataClasses = aget['class'];
		var mInvited=[];
		fClasses=[];
		nSubscribed=[];
	    for (i = 0; i < dataTypes.length; i++)
	    {
			m_c = dataTypes[i]['invited_count'];
			f_c = dataTypes[i]['class_name'];
			s_c = dataTypes[i]['subscribed_count'];
			mInvited.push(m_c);
		    fClasses.push(f_c);
		    nSubscribed.push(s_c);
		    
}
		var barChartData = {
			labels: fClasses,
			datasets: [{
				label: 'Number of Students Invited',
				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: mInvited
			
		}, {
				label: 'Number of Students Subscribed',
				backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: nSubscribed 
					
				
			}
		]
			

		};

		window.onload = function() {
			var ctx = document.getElementById('created_students').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'Number of students'
					}
				}
			});

		};
	});
</script>
@stop
