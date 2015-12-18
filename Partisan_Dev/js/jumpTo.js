/*-- js file --*/
/*-- Jump to area on page with animation --*/

$(document).ready(function(){

	$('#contact').click(function(){
    	$('html, body').animate({scrollTop: $( $.attr(this, 'href') ).offset().top},800);
    	return false;
	});
});

