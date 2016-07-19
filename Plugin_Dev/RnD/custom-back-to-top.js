/**
 * What is this?: Theme top button custom JS
 *
 * Description: A custom script to create a shoot back to top button
 * Author: Thabo Mbuyisa
 * Last modified: 11-03-16
 */
jQuery(document).ready(function($) {

 	// Set some physics vars
 	var offset = 100;
 	var speed = 250;
 	var duration = 500;

 	 	$('window').scroll function() {
 	 		if($(this).scrollTop() < offset) { // if the scrollTop object is greater than the offset var

 	 			$('.top-button') .fadeOut(duration);

 	 		} else {

 	 			$('.top-button') .fadeIn(duration);
 	 		}
 	 	}
 		//
 		$('top-button').on('click', function() {
 			$('html, body').animate({scrollTop:0}, speed);
 			return false;
 		});
});
//