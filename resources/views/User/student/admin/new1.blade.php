@extends('layouts.Student.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
<style>#sticky{
	position: fixed;
	top: 70px;
	right: 30px;
	z-index: 99;
	font-size: 18px;
	border: none;
	outline: none;
	background-color: #d7e8bb;
	color: #000;
	cursor: pointer;
	padding: 15px;
	border-radius: 4px;
  }}</style>
@stop
<div class="app-main__inner">
					<div class="tab-content size_temoin">
						<div class="tab-pane tabs-animation fade show active">
							<div class="main-card mb-3 card">
								<div class="card-body">
									<h5>{!! $exer_data->title !!}</h5>
								   @isset($exer_data->completion_time)
								   <div id='sticky'>Timer
								   <h6>Total Minutes Allowed :{{ $exer_data->completion_time }}</h6>
								   <div id="runner-compo"></div>
								   <button class="btn btn-info run-tests" style="display: none;">Start</button>
								  </div>	
									@endisset
									{{-- <p>This is the description of the exercise. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam bibendum gravida augue in convallis. Aliquam non iaculis eros. Maecenas vitae sem scelerisque, ullamcorper turpis eu, sollicitudin nisl. </p> --}}
									<form>
										<input type="hidden" name="exer_ent" value="{{ $exer_data->id }}" readonly>
										@isset($exer_data->completion_time)<input type="hidden" name="allowable_time" id="allowable_time" value="" >@endisset
                                        <div>
                                        @foreach($qdata as $qd)
                                        {!! $qd !!}
                                        @endforeach
                                        </div>
										<!-- <div class="row">
											<div class="col-12">
												<hr>
												<h6>Question 1: This is a Multiple Choice Question</h6>
											</div>
											<div class="col-12 mt-1">
												<div class="position-relative form-check">
													<input type="checkbox" class="form-check-input" id="reponse1_question1">
													<label class="form-check-label" for="reponse1_question1">This is an answer to the question.</label>
												</div>
												<div class="position-relative form-check">
													<input type="checkbox" class="form-check-input" id="reponse2_question1">
													<label class="form-check-label" for="reponse2_question1">This is an answer to the question.</label>
												</div>
												<div class="position-relative form-check">
													<input type="checkbox" class="form-check-input" id="reponse3_question1">
													<label class="form-check-label" for="reponse3_question1">This is an answer to the question.</label>
												</div>
												<div class="position-relative form-check">
													<input type="checkbox" class="form-check-input" id="reponse4_question1">
													<label class="form-check-label" for="reponse4_question1">This is an answer to the question.</label>
												</div>
											</div>
										</div> -->
									
										<!-- <div class="row">
											<div class="col-12">
												<hr>
												<h6>Question 2: This is an Essay Question</h6>
											</div>
											<div class="col-12 mt-1">
												<div class="position-relative">
													<label class="form-check-label" for="textarea1"><span id="textarea1_max">300</span> words maximum. Number of words remaining: <span id="textarea1_count"></span></label>
													<textarea name="textarea1" id="textarea1" class="form-control word_count" style="min-height: 150px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam bibendum gravida augue in convallis. Aliquam non iaculis eros. Maecenas vitae sem scelerisque, ullamcorper turpis eu, sollicitudin nisl. Proin non nunc commodo, pharetra nisi nec, tristique turpis. Curabitur pharetra justo ut tellus pellentesque efficitur. Quisque orci leo, suscipit cursus ligula id, egestas accumsan risus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum rutrum turpis ultricies, scelerisque nisi id, dictum ex. Vivamus sit amet maximus velit. Nullam quis tincidunt tellus. Pellentesque volutpat sollicitudin venenatis. Sed ut nibh porta, ullamcorper nisi sit amet, sagittis velit. Cras maximus viverra ex vitae interdum. Donec in aliquet ipsum. Sed non ornare augue. Vestibulum ultrices non turpis sed efficitur.</textarea>
												</div>
											</div>
										</div> -->
									
										<!-- <div class="row">
											<div class="col-12">
												<hr>
												<h6>Question 3: This is a True or False Question</h6>
											</div>
											<div class="col-12 mt-1">
											
											
												<fieldset class="position-relative form-group">
													<div class="position-relative form-check">
														<label class="form-check-label" for="reponse1_question5">
															<input name="reponse1_question5" id="reponse1_question5" type="radio" class="form-check-input"> True
														</label>
													</div>
													<div class="position-relative form-check">
														<label class="form-check-label" for="reponse2_question5">
															<input name="reponse2_question5" id="reponse2_question5" type="radio" class="form-check-input"> False
														</label>
													</div>
												</fieldset>
											</div>
										</div> -->
									

										<!-- When creating multiple sorting questions, you just need to change the ID here) -->
										<!-- <div class="create_sortable_exercise" id="sortable1">
											<div class="row">
												<div class="col-12">
													<hr>
													<h6>Question 4: This is a Label the Image Question</h6>
												</div>
												<div class="col-12 mt-1">
													
													<ul class="class_container_draggable init_draggable_list">
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Toad</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Lizard</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Owl</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Bird</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Deer</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Monkey</h6>
														</li>
													</ul>
													
												</div>
											</div>

											<div class="position-relative">
												<img src="assets/images/animaux_legende.png" alt="Image to label" title="Image to label" style="width: 100%;">
												
												<ul class="class_container_draggable class_container_limitation position-absolute" style="top: 30%; left: 15%"></ul>
												<ul class="class_container_draggable class_container_limitation position-absolute" style="top: 30%; left: 45%"></ul>
												<ul class="class_container_draggable class_container_limitation position-absolute" style="top: 30%; left: 75%"></ul>
												<ul class="class_container_draggable class_container_limitation position-absolute" style="top: 80%; left: 15%"></ul>
												<ul class="class_container_draggable class_container_limitation position-absolute" style="top: 80%; left: 45%"></ul>
												<ul class="class_container_draggable class_container_limitation position-absolute" style="top: 80%; left: 75%"></ul>

											</div>
										</div> -->
									
									
	
									
										<!-- When creating multiple sorting questions, you just need to change the ID here) -->
										<!-- <div class="create_sortable_exercise" id="associate1">
											<div class="row">
												<div class="col-12">
													<hr>
													<h6>Question 5: This is a Matching Question</h6>
												</div>
												<div class="col-12 mt-1">
													
													<ul class="class_container_draggable init_draggable_list">
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Word 1</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Word 2</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Word 3</h6>
														</li>
														<li class="class_draggable btn mr-2 btn-primary sortable_tag">
															<h6 class="Heading Heading--size4 text-no-select">Word 4</h6>
														</li>
													</ul>
													
												</div>
											</div>

											<div class="position-relative">
											
												<div class="row">
													<div class="col-lg-3 col-5">
														<div class="word_for_association p-2">Word 5</div>
													</div>
													<div class="col-lg-2 col-2 text-center">
														goes with
													</div>
													<div class="col-lg-3 col-5">
														<ul class="class_container_draggable class_container_limitation"></ul>
													</div>
												</div>
												
												<div class="row">
													<div class="col-lg-3 col-5">
														<div class="word_for_association p-2">Word 6</div>
													</div>
													<div class="col-lg-2 col-2 text-center">
														goes with
													</div>
													<div class="col-lg-3 col-5">
														<ul class="class_container_draggable class_container_limitation"></ul>
													</div>
												</div>
												
												<div class="row">
													<div class="col-lg-3 col-5">
														<div class="word_for_association p-2">Word 7</div>
													</div>
													<div class="col-lg-2 col-2 text-center">
														goes with
													</div>
													<div class="col-lg-3 col-5">
														<ul class="class_container_draggable class_container_limitation"></ul>
													</div>
												</div>
												
												<div class="row">
													<div class="col-lg-3 col-5">
														<div class="word_for_association p-2">Word 8</div>
													</div>
													<div class="col-lg-2 col-2 text-center">
														goes with
													</div>
													<div class="col-lg-3 col-5">
														<ul class="class_container_draggable class_container_limitation"></ul>
													</div>
												</div>
											</div>
										</div> -->


										<!-- <div class="row create_sortable_exercise" id="sort_element1">
											<div class="col-12">
												<hr>
												<h6>Question 6: This is a Sequencing Question</h6>
											</div>

											<div class="col-lg-8 mt-1 offset-lg-2">
												<ul class="list-group mt-3 class_container_draggable init_draggable_list" tabindex="0">
													<li class="list-group-item p-0 class_draggable" tabindex="0">
														<img src="assets/images/test_tri_1.png" alt="Image to sort" title="Image to sort" class="img-fluid">
													</li>
													<li class="list-group-item p-0 class_draggable" tabindex="0">
														<img src="assets/images/test_tri_2.png" alt="Image to sort" title="Image to sort" class="img-fluid">
													</li>
													<li class="list-group-item p-0 class_draggable" tabindex="0">
														<img src="assets/images/test_tri_3.png" alt="Image to sort" title="Image to sort" class="img-fluid">
													</li>
													<li class="list-group-item p-0 class_draggable" tabindex="0">
														<img src="assets/images/test_tri_4.png" alt="Image to sort" title="Image to sort" class="img-fluid">
													</li>
												</ul>
											</div>
										</div> -->

										<div class="row">
											<div class="col-lg-12 text-right">
												<hr>
												<button class="mt-1 btn btn-primary excerz_save" name="button">Save the exercise</button>
												{{-- <button class="mt-1 btn btn-primary" name="commit">Save and publish</button> --}}
												<input type="hidden" id="exam_status" value="0">
											</div>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>

					
				</div>
				<!--- alert modal -->
				<div class="modal fade" id="exsessionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						  ...
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-primary">Save changes</button>
						  
						</div>
					  </div>
					</div>
				  </div>
                @stop
@section('page-script')
<script>
	var completion_time="{{ ($exer_data->completion_time) ? $exer_data->completion_time : null  }}"; var started_at='';
$(function(){
	
 if(confirm('You are going to attend the exercise')){
 	startsTest();
 }else{
	$('button.run-tests').show(); $('.excerz').attr('disabled',true);
	
 }


function startsTest(){
	$excerid=$('input[type="hidden"][name="exer_ent"]').val(); var now_dat= new Date(); $start_time=now_dat.getFullYear() +"-"+ (now_dat.getMonth() + 1) +"-"+ now_dat.getDate() + " " + now_dat.getHours() + ":" + now_dat.getMinutes() + ":" + now_dat.getSeconds();

	$('.excerz_save').on('click',function(e){

		

		$.ajax({
  url:'{{ route('student.title.check_exercise_flow') }}',
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
 type: "POST",
 dataType: 'json',
 data: { "exercise":$excerid, "start_time":$start_time},
 success: function(response){
    if(response.messages == 'success'){
		
		if(completion_time){
		 $("#allowable_time").val(response.data.started_at);
		  starsTimer();
			// var input_hid = document.createElement("input"); input_hid.setAttribute("type", "hidden"); input_hid.setAttribute("name", "allowable_time"); input_hid.setAttribute("value", response.data.started_at);
		}
    }
 },
fail: function() {
 }
 });
	});

}

function starsTimer(){
	if($('input[name="allowable_time"]').length){
		$timesgap=completion_time;
	dateDB=$("#allowable_time").val();
	$.start_tim=new Date(Date.parse(dateDB));
	// var start_times=$.start_tim.getTime();
	$.start_tim.setMinutes($.start_tim.getMinutes() + Number($timesgap));
	var end_times=$.start_tim.getTime();

	var x = setInterval(function() {
var now = new Date().getTime();
var distance = end_times - now;var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)); var seconds = Math.floor((distance % (1000 * 60)) / 1000);document.getElementById("runner-compo").innerHTML =minutes + ": " + seconds + "";
if (distance < 0) {
  clearInterval(x);
  document.getElementById("runner-compo").innerHTML = "EXPIRED";
}
}, 1000);
}	
}

$(".word_count").on('keyup', function() { // textarea count
    var words = this.value.match(/\S+/g).length;
	$limit=$(this).data('maxims');
    if (words > Number($limit)) {
      // Split the string on first 200 words and rejoin on spaces
      var trimmed = $(this).val().split(/\s+/, Number($limit)).join(" ");
      // Add a space at the end to make sure more typing creates new words
      $(this).val(trimmed + " ");
    }
    else {
    $(this).prev().find('.textarea1_count').text(Number($limit)-words);
    }
  });

  const sortable = new Sortable.default(document.querySelectorAll('.class_container_draggable'), {
  draggable: '.class_draggable'
});

sortable.on('drag:start', (evt) => {
	$('.class_container_limitation').each(function(i, obj) {
		// loop dans tous les container draggable
		
		capacityReached = sortable.getDraggableElementsForContainer(this).length >= 1;
		// genere un boolean si le container est utilisé
		console.log(capacityReached);
		
		this.classList.toggle('draggable_full', capacityReached);
		// Ajoute ou retire la classe draggable_full si le container est full ou non
	});
	
});

sortable.on('drag:stop', (evt) => {
	$('.class_draggable').each(function(i){
		var parnt=$(this).parent();
      if(parnt.hasClass('labeling') && $(this).hasClass('draggable-source--is-dragging draggable--over')){ 
		
		// parnt.find('.store_label_text').val($(this).children('h6').text());
		}
	});
});

sortable.on('sortable:sort', (evt) => {
	if (evt.dragEvent.overContainer.outerHTML.includes('draggable_full')) {
		evt.cancel();
		// si la cible du drop ne contient pas la classe draggable_full alors on accepte sinon on annule l'evenement
	}
});

$(".class_draggable").on("drop dragdrop", function(event) {
    event.preventDefault();  
    event.stopPropagation();
    alert("Dropped!");
});




});

function checkChoiceclick(ele){
	$box=$(ele.target);
  if ($box.is(":checked")) {
    var group = "input:checkbox[class='" + $box.attr("class") + "']";
    $(group).prop("checked", false);
    $box.prop("checked", true);
  } else {
    $box.prop("checked", false);
  }

}
</script>
@stop