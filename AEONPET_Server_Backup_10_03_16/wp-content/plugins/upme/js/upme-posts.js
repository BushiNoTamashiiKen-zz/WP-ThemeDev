jQuery(document).ready(function($) {
    /* Nice file upload */
    // Calling hidden and native element's action
//    $('.upme-fileupload').click(function(){
//        if($('#file_'+$(this).attr('id')).length > 0)
//            $('#file_'+$(this).attr('id')).click();
//    });
    
//    $( ".upme-post-features-active" ).hover(
//      function() {
//        var type = $(this).attr('upme-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(UPMEPosts.Messages.markAsUnRead);
//                break;
//            case 'favorite':
//                $( this ).html(UPMEPosts.Messages.markAsNotFavorite);
//                break;
//            case 'recommend':
//                $( this ).html(UPMEPosts.Messages.markAsNotRecommended);
//                break;
//        }
//        
//      }, function() {
//        var type = $(this).attr('upme-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(UPMEPosts.Messages.read);
//                break;
//            case 'favorite':
//                $( this ).html(UPMEPosts.Messages.favorite);
//                break;
//            case 'recommend':
//                $( this ).html(UPMEPosts.Messages.recommended);
//                break;
//        }
//      }
//    );
//    
//    $( ".upme-post-features-inactive" ).hover(
//      function() {
//        var type = $(this).attr('upme-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(UPMEPosts.Messages.markAsRead);
//                break;
//            case 'favorite':
//                $( this ).html(UPMEPosts.Messages.markAsFavorite);
//                break;
//            case 'recommend':
//                $( this ).html(UPMEPosts.Messages.markAsRecommended);
//                break;
//        }
//        
//      }, function() {
//        var type = $(this).attr('upme-data-type');
//        switch(type){
//            case 'read':
//                $( this ).html(UPMEPosts.Messages.unread);
//                break;
//            case 'favorite':
//                $( this ).html(UPMEPosts.Messages.notfavorite);
//                break;
//            case 'recommend':
//                $( this ).html(UPMEPosts.Messages.notrecommended);
//                break;
//        }
//      }
//    );
    
    
//    $( ".upme-post-features-reading-active" ).hover(
//      function() {
//        $( this ).html(UPMEPosts.Messages.markAsUnRead);
//      }, function() {
//        $( this ).html(UPMEPosts.Messages.read);
//      }
//    );
//    
//    $( ".upme-post-features-reading-inactive" ).hover(
//      function() {
//        $( this ).html(UPMEPosts.Messages.markAsRead);
//      }, function() {
//        $( this ).html(UPMEPosts.Messages.unread);
//      }
//    );
//    
//    $( ".upme-post-features-recommend-active" ).hover(
//      function() {
//        $( this ).html(UPMEPosts.Messages.markAsNotRecommended);
//      }, function() {
//        $( this ).html(UPMEPosts.Messages.recommended);
//      }
//    );
//    
//    $( ".upme-post-features-recommend-inactive" ).hover(
//      function() {
//        $( this ).html(UPMEPosts.Messages.markAsRecommended);
//      }, function() {
//        $( this ).html(UPMEPosts.Messages.notrecommended);
//      }
//    );
//    
//    $( ".upme-post-features-favorite-active" ).hover(
//      function() {
//        $( this ).html(UPMEPosts.Messages.markAsNotFavorite);
//      }, function() {
//        $( this ).html(UPMEPosts.Messages.favorite);
//      }
//    );
//    
//    $( ".upme-post-features-favorite-inactive" ).hover(
//      function() {
//        $( this ).html(UPMEPosts.Messages.markAsFavorite);
//      }, function() {
//        $( this ).html(UPMEPosts.Messages.notfavorite);
//      }
//    );
    
    
    $('.upme-post-features-panel').on('click','.upme-post-features-btn',function(){
        var type = $(this).attr('upme-data-type');
        var status = $(this).attr('upme-data-status');
        var btn = $(this);
        var btn_html = $(this).html();
        
        $(this).html(UPMEPosts.Messages.processing);
        
        jQuery.post(
                UPMEPosts.AdminAjax,
                {
                    'action': 'upme_change_post_feature_status',
                    'type'  :   type,
                    'status' : status,
                    'post_id' : UPMEPosts.Post_ID,
                },
                function(response){
 
                    if(response.status == 'success'){
       
                        var active_status = '';
                        var inactive_status = '';
                        if(response.type == 'read'){
                           active_status = UPMEPosts.Messages.read;
                           inactive_status = UPMEPosts.Messages.unread;
                        }else if(response.type == 'favorite'){
                           inactive_status = UPMEPosts.Messages.notfavorite;
                           active_status = UPMEPosts.Messages.favorite;
                        }else if(response.type == 'recommend'){
                           inactive_status = UPMEPosts.Messages.notrecommended;
                           active_status = UPMEPosts.Messages.recommended;
                        }
                        
                        if(response.post_status == '1'){
                            btn.attr('upme-data-status','active');
                            btn.removeClass('upme-post-features-inactive').addClass('upme-post-features-active');
                            btn.html(active_status);
                        }else{
                            btn.attr('upme-data-status','inactive');
                            btn.removeClass('upme-post-features-active').addClass('upme-post-features-inactive');
                            btn.html(inactive_status);
                        }
                        
                    }

                

        },"json");
    });
    
    
    
    
});



    