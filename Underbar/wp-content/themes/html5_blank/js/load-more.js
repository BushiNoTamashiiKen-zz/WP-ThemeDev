/**
 * Load More JS File 
 *
 */

 jQuery(function(jQuery){

	jQuery('.post-listing').append( '<span class="load-more"></span>' );
	var button = jQuery('.post-listing .load-more');
	var page = 2;
	var loading = false;
	var scrollHandling = {
	    allow: true,
	    reallow: function() {
	        scrollHandling.allow = true;
	    },
	    delay: 400 //(milliseconds) adjust to the highest acceptable value
	};

	jQuery(window).scroll(function(){

		if( ! loading && scrollHandling.allow ) {

			scrollHandling.allow = false;
			setTimeout(scrollHandling.reallow, scrollHandling.delay);
			var post_end = jQuery(window);

			if(post_end.top) {

				var offset = jQuery(button).offset().top - jQuery(window).scrollTop(); // Set the offset variable

				if( 2000 > offset ) {

					loading = true;

					var data = {
						action: 'be_ajax_load_more',
						nonce: beloadmore.nonce,
						page: page,
						query: beloadmore.query,
					};
					jQuery.post(beloadmore.url, data, function(res) {

						if( res.success) {
							jQuery('.post-listing').append( res.data );
							jQuery('.post-listing').append( button );
							page = page + 1;
							loading = false;
						} else {

							 console.log(res);
						}
					}).fail(function(xhr, textStatus, e) { // Log fail response in dev console

						 console.log(xhr.responseText);
					});

				}
			}
		}
	});
});