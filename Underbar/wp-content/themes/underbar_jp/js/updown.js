/*-- js file --*/
/*-- Jump back to top of page with animation --*/

jQuery(document).ready(function(){

	jQuery(window).scroll(function(){
		/*if(jQuery(window).scrollTop() + jQuery(window).height() > jQuery(document).height() - 5){*/
			
		jQuery('.scrolltoTop').fadeIn();
		jQuery('.scrolltoBottom').fadeIn();
		/*}else{

			jQuery('.scrolltoTop').fadeOut();
			jQuery('.scrolltoBottom').fadeOut();
		}*/
	});

	//Click event for window offset
	jQuery('.scrolltoTop').click(function(){
		
		jQuery('html, body').animate({scrollTop: 0},800);
		return false;	
	});
	jQuery('.scrolltoBottom').click(function(){
		
		jQuery('html, body').animate({scrollTop: jQuery(document).height()},800);
		return false;	
	});
});

