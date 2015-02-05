/*-- js file --*/
/*-- Parallax Scroll Effect --*/

$(document).ready(function(){

    // Create HTML5 elements for IE
    document.createElement("article");
    document.createElement("section");

	var $window = $(window);

	$('section[data-type="background"]').each(function(){// Looping through each section element

		var $bgobj = $(this); // create a variable and assign it to the object 
		$(window).scroll(function(){

			var yPos = -($window.scrollTop() / $bgobj.data('speed'))// Determine how much scroll and divide by speed
			var coords = '50%' + yPos + 'px'; //Final background position based on above math

			$bgobj.css({backgroundPosition: coords});// Move the background based on the above math
		});
	});
});

