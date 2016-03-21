function blt_open_uploader(element, class_name){

	$that = jQuery(element);
    wp.media.editor.send.attachment = function(props, attachment){
    	console.log(attachment.sizes[props.size].url);
    	$that.closest('.upload_image_container').find('.uploaded_image_uri').val(attachment.sizes[props.size].url);

    	$that.closest('.upload_image_container').find('.uploaded_image').remove()
    	$that.closest('.upload_image_container').find('.uploaded_image').remove()
		$that.closest('.upload_image_container').append('<img class="uploaded_image" src="'+attachment.sizes[props.size].url+'" style="margin-top: 10px; width:100%">');
    		
    }

    wp.media.editor.open(this);

    return false;
}
function blt_remove_image(element){

	$that = jQuery(element);
    $that.closest('.upload_image_container').find('.uploaded_image').remove();
    $that.closest('.upload_image_container').find('.uploaded_image_uri').val('');
    return false;
}


function blt_add_random_vote_count(e){

    e.preventDefault();

    var max = prompt("What is the maximum vote count you want?", "50");
    
    if(max != null){
        jQuery.post(ajaxurl, {action: 'blt_random_vote', max: max}, function(){
            alert('Random votes have been added to all posts')
        });
    }

}


jQuery(function($){

    /**
     * Hide notifications on the admin side
     */
    $('.blu-hide-notification').click(function(e){

        e.preventDefault();

        var key = $(this).attr('data-key'),
            url = $(this).attr('href');

        $.post(url, {action: 'blu_notifications', key: key}, function(){
            $('.blu-notification[data-key="'+key+'"]').remove();
        });
    });

});