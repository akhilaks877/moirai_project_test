$(document).ready(function() {
	
// ____________________________________________________________________________________________________________________
// The following functions are used on the page to create a question (question type "Label the Image")
// ____________________________________________________________________________________________________________________
	
	$("#div_legend").on('click', function(event) {
		var relX = event.pageX - $(this).offset().left;
		// get the X axis of the mouse relative to the div
		var relY = event.pageY - $(this).offset().top;
		// get the Y axis of the mouse relative to the div
		var div_height = $(this).height();
		var div_width = $(this).width();

		var pourcentX = relX * 100 / div_width;
		var pourcentY =  relY * 100 / div_height;

		$(this).append('<div class="input-group add_input draggable" style="position: absolute; top: '+pourcentY+'%; left: '+pourcentX+'%; width: 250px;"><div class="input-group-prepend move_input grab"><a href="#nogo" class="btn btn-secondary"><i class="fa fa-arrows-alt"></i></a></div><input type="text" class="form-control added_input"><div class="input-group-append delete_input"><a href="#nogo" class="btn btn-secondary"><i class="fa fa-trash"></i></a></div></div>');
		$('.added_input').last().focus();
		$( ".draggable" ).draggable();
	}).on('click', '.delete_input', function(event) {
		$(this).parents('.add_input').remove();
	}).on('click', '.move_input', function(event) {
	}).on('click', 'div', function(event) {
		// clicked on descendant div
		event.stopPropagation();
	});

	
// ____________________________________________________________________________________________________________________
// The following functions are used on the programm tree used in the bibliographic section
// ____________________________________________________________________________________________________________________

$("#new_program_part").on('click', function(event) {
	var str = $("#program_part").val();
	$('#program_list .list-group:last-child').append('<li class="list-group-item">'+str+'<a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_program"><span class="fa fa-trash "></span></a><ul class="containable"></ul></li>');
});

$("#program_list").on('click','.trash_program', function(event) {
	$(this).parent().remove();
});



// The adjust_li() function is used to deleted the parasite UL when a top section li is placed on the second level of the tree.
// It's fired every time the drag and drop stop with a 0.1 seconds delay so that the DOM is adjusted with the correct new sort for the li

function adjust_li() {
	$('#program_list li').each(function(i, obj) {
		if($(this).parent('ul').parent().is('li')){
			$(this).children('ul').remove();
		}
		else
		{
			if($(this).children().is('.containable'))
			{
			}
			else
			{
				$(this).append('<ul class="containable"></ul>');
			}
		}
	});
}


const sortable = new Sortable.default(document.querySelectorAll('#program_list ul'), {
	draggable: 'li'
});

sortable.on('sortable:stop', (evt) => {
	window.setTimeout( adjust_li, 100 );
});






// ____________________________________________________________________________________________________________________
// The following functions are used on the "sort the element" type of question page
// ____________________________________________________________________________________________________________________


const sortable2 = new Sortable.default(document.querySelectorAll('#element_sort ul'), {
	draggable: 'li'
});


$("#add_sortable_element").on('click', function() {
	$('#element_sort ul').append('<li class="list-group-item p-0"><div class="card-shadow-primary border mb-3 card card-body border-primary p-1"><label for="file4" class="mb-0">Choose an image for this element</label><input name="file4" id="file4" type="file" class="form-control-file"></div><a href="#nogo" class="border-0 btn-transition btn btn-outline-info trash_element float-right"><span class="fa fa-trash"></span></a></li>');});


$("#element_sort").on('click','.trash_element', function() {
	$(this).parent().remove();
});



// ____________________________________________________________________________________________________________________
// The following functions are used on the "QCM" type of question page
// ____________________________________________________________________________________________________________________


$(".add_wrong_answer").on('click', function() {
	$('.wrong_answer_list').append('<div class="input-group mb-2"><label for="wrong_answer2" class="just_for_screereaders">Add an incorrect answer</label><input name="wrong_answer2" id="wrong_answer2" aria-labelledby="wrong_answers" placeholder="This is an incorrect answer" type="text" class="form-control"><div class="input-group-append"><button class="btn btn-secondary trash_wrong_answer"><span class="fa fa-trash"></span></button></div></div>');
});


$(".wrong_answer_list").on('click','.trash_wrong_answer', function() {
	$(this).parent().parent().remove();
});


// ____________________________________________________________________________________________________________________
// The following functions are used on the "word association" type of question page
// ____________________________________________________________________________________________________________________


$(".add_associated_word").on('click', function() {
	$('.associated_word_list').append('<div class="form-row d-flex mb-2"><div class="col-sm-5"><div class="position-relative form-group"><label for="mot1" class="just_for_screereaders">Word 1</label><input name="mot1" id="mot1" placeholder="Word 1" type="text" aria-labelledby="liste_mots" class="form-control mb-2"></div></div><div class="col-sm-1 text-center mt-2"><h6>goes with</h6></div><div class="col-sm-5"><div class="position-relative form-group"><label for="mot2" class="just_for_screereaders">Word 2</label><input name="mot2" id="mot2" placeholder="Word 2" type="text" aria-labelledby="liste_mots" class="form-control mb-2"></div></div><div class="col-sm-1"><div class="position-relative form-group"><a href="#nogo" class="btn btn-secondary mt-1 float-right trash_associated_word"><span class="fa fa-trash"></span></a></div></div></div>');
});

$(".associated_word_list").on('click','.trash_associated_word', function() {
	$(this).parent().parent().parent().remove();
});


// ____________________________________________________________________________________________________________________
// The following functions are used to automatically change the size unit on the bibliographic data page
// ____________________________________________________________________________________________________________________


$("#size_unit_choice").change(function(){
	var selected_unit = $(this).val();
	$(".size_unit").text(selected_unit);
});


// ____________________________________________________________________________________________________________________
// The following functions are used to automatically change the new exercise descriptions
// ____________________________________________________________________________________________________________________


$("#type_exercice").change(function(){
	var selected_type = $(this).val();
	if(selected_type == "exam"){
		var description = "<h6>Create a Chapter Test</h6><p>These tests are always placed at the end of a chapter and evaluate the student's level of comprehension of said chapter. This type of exercise requires the following specifications:</p><ul><li>The chapter test is always inserted at the end of a chapter</li><li>The student can only submit the test once</li><li>The questions on the test are associated with specific Ministry program elements</li></ul>";
		var label_placement = "Insert this test at the end of:";
		var label_btn = "Publish";
	}
	else if(selected_type == "training"){
		var description = "<h6>Create a Practice Exercise</h6><p>These exercises help students practice the skills acquired in this chapter. This type of exercise has the following characteristics:</p><ul><li>A practice exercise can be placed anywhere in the chapter</li><li>The student may submit the exercise as many times as desired</li><li>The questions in the exercise are not necessarily associated with specific Ministry program elements</li></ul>";
		var label_placement = "Place this exercise in:";
		var label_btn = "Insert exercise and publish";
	}
	$("#type_exercice_description").html(description);
	$("#label_quizz_block").text(label_placement);
	$("#label_save").text(label_btn);
	
});

// ____________________________________________________________________________________________________________________
// The following functions are used to deal with the drag and drop and other features of the "complement_web" page
// ____________________________________________________________________________________________________________________

const sortable3 = new Sortable.default(document.querySelectorAll('#page_complement_preview'), {
	draggable: '.block_draggable'
});

$('.block_draggable').each(function(i, obj) {
	$(this).prepend('<button type="button" class="btn btn-outline-primary float-right trash_block"><i class="fa fa-trash"></i></button>');
});

$("#page_complement_preview").on('click','.trash_block', function(event) {
	$(this).parent().remove();
});

	
});