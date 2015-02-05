/*-- js file --*/
/*-- Link area on page with animation --*/

jQuery(document).ready(function(){
	if (Modernizr.touch) {
        // show the close overlay button
        jQuery(".close-overlay").removeClass("hidden");
        // handle the adding of hover class when clicked
        jQuery(".postColumn").click(function(e){
            if (!jQuery(this).hasClass("hover")) {
                jQuery(this).addClass("hover");
            }
        });
        // handle the closing of the overlay
        jQuery(".close-overlay").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            if (jQuery(this).closest(".postColumn").hasClass("hover")) {
                jQuery(this).closest(".postColumn").removeClass("hover");
            }
        });
    } else {
        // handle the mouseenter functionality
        jQuery(".postColumn, .postColumn-1-2, .postColumn-2-2").mouseenter(function(){
            jQuery(this).addClass("hover");
        })
        // handle the mouseleave functionality
        .mouseleave(function(){
            jQuery(".postColumn, .postColumn-1-2, .postColumn-2-2").removeClass("hover");
        });
    }
});
