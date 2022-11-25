$(".app-container").toggleClass("closed-sidebar");



 function adjustWidth() {
   var parentwidth = $(".size_temoin").width();
   var parentRight = $(".size_temoin").offset().left;
   console.log (parentRight);
   $(".header_reading").width(parentwidth);
   $(".header_reading").css({left: parentRight});

 }


$(window).scroll(function(){
  if ($(this).scrollTop() > 30) {
	  $('.header_reading').addClass('fixed');
       window.setTimeout( adjustWidth, 100 );

  } else {
	  $('.header_reading').removeClass('fixed');
       window.setTimeout( adjustWidth, 100 );
  }
});

 $(window).resize(function() {
 if ($(this).scrollTop() > 30) {
       window.setTimeout( adjustWidth, 100 );
	   }
     });

$(".close-sidebar-btn").click(function(){
window.setTimeout( adjustWidth, 300 );
});

$(".add_note").on('click', function() {
	if($(this).attr('aria-expanded') == 'true'){
			$(this).html('<span class="fa fa-plus fa-w-16"></span> Add a note');
			$(this).addClass("disabled");
			var a = $(this);
	}
	else
	{
			$(this).html('<span class="fa fa-minus fa-w-16"></span> Close');
			$(this).addClass("disabled");
			var a = $(this);
	}
setTimeout( function() {
    a.removeClass("disabled");
  }, 400);

});


$(function(){
  $(window).scroll(function(){
	  if($('#reading_nav').hasClass('show')){
		  
		  $('#reading_nav').slideUp('200',function() {
			$('#reading_nav').removeClass('show');
			$('#reading_nav').removeAttr("style");
			});
	}
  });
});

$('.book_navigation').click(function(){
  $(this).find('.sign').text(function(i,old){
      return old=='+' ?  '-' : '+';
  });
});


$(document).ready(function()
{
	
/* This function is used for the word count on a text area question */
    $('.word_count').each(function() {
        var input = '#' + this.id;
        var count = input + '_count';
        var id_max = input + '_max';
        var max = parseInt($(id_max).text());
        $(count).show();
        word_count(input, count, max);
        $(this).keyup(function() { word_count(input, count, max) });
    });
 
/* This function is used to create every sortable elements on an exercise */
    $('.create_sortable_exercise').each(function(i) {
        var id_source = '#' + this.id;
      //  var id_reception = id_source + '_reception';
       // var counter = i;
        create_sortable(id_source);
    });
 
 }); 

function word_count(input, count, max) {
    var number = 0;
    var matches = $(input).val().match(/\b/g);
    if(matches) {
        number = max - matches.length/2;
    }
    $(count).text(number);
}


