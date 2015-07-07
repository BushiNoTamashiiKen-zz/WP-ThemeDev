/*-- js file --*/
/*-- Jump back to top of page with animation --*/

$(document).ready(function(){

	//Check to see if the window is on top if not display button
	$(window).scroll(function(){
		if($(this).scrollTop() > 100){
			$('.scrolltoTop').fadeIn();
		}
		else
		{
			$('.scrolltoTop').fadeOut();
		}
	});

	//Click event for scroll
	$('.scrolltoTop').click(function(){
		$('html, body').animate({scrollTop: 0},800);
		return false;	
	});
});

