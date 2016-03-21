jQuery(function($){

	$('.blu-customizer-open').bind('click', function(e){

		e.preventDefault();

		/* only load these scripts and binds once */
		if(!$('.blu-customizer').hasClass('opened')){

			/* show adspots */
			$('#show-adspots').on('change', function(e){

				e.preventDefault();

				if(!$(this).is(':checked')){

					$('.adspot-demo').each(function(){
						$(this).remove();
					});

				}else{

					/* #1 */
					$('#site').prepend('<div class="adspot-demo spot_above_menu spot_above_menu-html"><img src="http://www.bluthemes.com/themes-special/katla/wp-content/themes/blt-katla/_demo/assets/img/adspot_1.jpg"></div>');
					/* #2 */
					$('#site-body').prepend('<div class="adspot-demo spot_below_menu spot_below_menu-html"><img src="http://www.bluthemes.com/themes-special/katla/wp-content/themes/blt-katla/_demo/assets/img/adspot_2.jpg"></div>');
					/* #3 */
					$('.single #site-content-column').prepend('<div class="adspot-demo spot_above_content spot_above_content-html"><img src="http://www.bluthemes.com/themes-special/katla/wp-content/themes/blt-katla/_demo/assets/img/adspot_3.jpg"></div>');
					/* #4 */
					var i = 0;
					$('.home #site-content-column article').each(function(){
						console.log(i);
						i++;
						if(i % 3 == 0){

							$(this).after('<div class="adspot-demo spot_between_posts spot_between_posts-html"><img src="http://www.bluthemes.com/themes-special/katla/wp-content/themes/blt-katla/_demo/assets/img/adspot_4.jpg"></div>');
							i = 0;
						}

					});
					/* #5 */
					$('.single .post-thumbnail-area').after('<div class="adspot-demo spot_below_content spot_below_content-html"><img src="http://www.bluthemes.com/themes-special/katla/wp-content/themes/blt-katla/_demo/assets/img/adspot_5.jpg"></div>');
				
				}

			});

			/* activate the container-style select box */
			$('#blog-style').bind('change', function(){

				if($(this).val() !== '' && $('.blog_normal .article-section').length && !$('.blog_normal .article-section').hasClass('list_1')){
					
					$('.blog_normal .article-section').removeClass('col-md-12');
					$('.blog_normal .article-section').removeClass('col-lg-12');
					$('.blog_normal .article-section').removeClass('col-md-6');
					$('.blog_normal .article-section').removeClass('col-lg-6');
					$('.blog_normal .article-section').removeClass('col-md-4');
					$('.blog_normal .article-section').removeClass('col-lg-4');
					$('.blog_normal .article-section').removeClass('absolute');
					$('.blog_normal .article-section').removeClass('one_column');
					$('.blog_normal .article-section').removeClass('two_column');
					$('.blog_normal .article-section').removeClass('three_column');
					$('.blog_normal .article-section').removeClass('list_1');

					$('.blog_normal .article-section').attr('style', '');
					$('.blog_normal .article-section .post-body').attr('style', '');
					$('.blog_normal .article-section .post-header').attr('style', '');

					if($(this).attr('value').indexOf('two_column') > -1 || $(this).attr('value').indexOf('three_column') > -1){

						$('.blog_normal .article-section .post-body').css('padding-left', '30px').css('padding-right', '30px');
						$('.blog_normal .article-section .post-header').css('padding-left', '30px').css('padding-right', '30px');
						
					}

					$('.blog_normal .article-section').addClass($(this).val());

					$(window).trigger('resizeEnd');

				}

			});
			
			/* disable the blog styling if there's no blog to style */
			if(!$('.blog_normal .article-section').length || $('.blog_normal .article-section').hasClass('list_1')){

				$('#blog-style').addClass('disabled').attr('disabled', 'disabled');

			}

			/* activate the container-style select box */
			$('#container-style').bind('change', function(){

				if($(this).val() !== ''){

					$('body').removeClass('boxed');
					$('body').removeClass('full_width');
					$('body').addClass($(this).val());
					

				}
				
				$(window).trigger('resize');

			});

			var secondary_sidebar_displayed = false;

			/* activate the sidebar-style select box */
			$('#sidebar-style').bind('change', function(){

				if($(this).val() !== ''){

					$('body').removeClass('sidebar-none');
					$('body').removeClass('sidebar-left');
					$('body').removeClass('sidebar-right');

					// hide the sidebar if it's turned off
					if($(this).val() == 'sidebar-none'){
						$('#site-content-sidebar').css('display', 'none');
					}else{
						$('#site-content-sidebar').css('display', '');
					}

					$('body').addClass($(this).val());


				}
				
				$(window).trigger('resize');

			});

			/* load the color picker */
			$("#background-color").spectrum({ 

				color: $('body').css('background-color'),
				change: function(color) {
					$('body').attr('style', '');
					$('body').css('background-color', color.toHexString()); // #ff0000
				}

			});

			/* change background-image on click */
			$('.background-image a').bind('click', function(){

				$('body').addClass('boxed');
				$('body')[0].style.setProperty( 'background-image', 'url('+$(this).children('img').data('lg')+')', 'important' );
				$('body')[0].style.setProperty( 'background-attachment', 'fixed', 'important' );
				$('body')[0].style.setProperty( 'background-repeat', 'no-repeat', 'important' );
				$('body')[0].style.setProperty( 'background-size', 'cover', 'important' );
				$(window).trigger('resize');
				

			});
		
		}

		/* open the container */
		if(!$('.blu-customizer').hasClass('active')){

			$('.blu-customizer').addClass('active');
			$('.blu-customizer').addClass('opened');
		
		}else{
			
			$('.blu-customizer').removeClass('active');

		}
	});


	$('#blt_login_form').prepend('<strong>Demo User:</strong><br>Please use the Username "<strong>demo</strong>" and password "<strong>demo</strong>" to log in.<hr>');
});