$(".app-container").toggleClass("closed-sidebar");

 function adjustWidth() {
   var parentwidth = $(".size_temoin").width();
   $(".header_reading").width(parentwidth);
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
			$(this).html('<span class="fa fa-plus fa-w-16"></span> Ajouter une note');
			$(this).addClass("disabled");
			var a = $(this);
	}
	else
	{
			$(this).html('<span class="fa fa-minus fa-w-16"></span> Replier');
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




