var $content = $('.ajax_posts');
var $loader = $('#more_posts');
var cat = $loader.data('category');
var ppp = 3;
var offset = $('#main').find('.post').length;
 
$loader.on( 'click', load_ajax_posts );
 
function load_ajax_posts() {

	if (!($loader.hasClass('post_loading_loader') || $loader.hasClass('post_no_more_posts'))) {

		$.ajax({
			type: 'POST',
			dataType: 'html',
			url: screenReaderText.ajaxurl,
			data: {
				'cat': cat,
				'ppp': ppp,
				'offset': offset,
				'action': 'underbar_more_post_ajax'
			},
			beforeSend : function () {

				$loader.addClass('post_loading_loader').html('');
			},
			success: function (data) {

				var $data = $(data);
				if ($data.length) {
					
					var $newElements = $data.css({ opacity: 0 });
					$content.append($newElements);
					$loader.removeClass('post_loading_loader');
					$newElements.animate({ opacity: 1 });
					$loader.removeClass('post_loading_loader').html(screenReaderText.loadmore);
				} else {

					$loader.removeClass('post_loading_loader').addClass('post_no_more_posts').html(screenReaderText.noposts);
				}
			},
			error : function (jqXHR, textStatus, errorThrown) {

				$loader.html($.parseJSON(jqXHR.responseText) + ' :: ' + textStatus + ' :: ' + errorThrown);
				console.log(jqXHR);
			},
		});
	}
	offset += ppp;
	return false;
}

var pageNumber = 1;
 
function load_ajax_posts() {

	if (!($loader.hasClass('post_loading_loader') || $loader.hasClass('post_no_more_posts'))) {
                pageNumber++;
	}
}