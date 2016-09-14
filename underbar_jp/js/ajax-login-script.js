/**
 *
 * JS File to capture user input from front-end and send it to the server for validation
 * Author: Thabo
 * Source Code Credit: Natko Hasic
 * License: GPL2+
 *
 */
jQuery(document).ready(function($) {

    // Show the login dialog box on click
    $('a#show_login').on('click', function(e){
        $('body').prepend('<div class="login_overlay"></div>');
        $('form#login').fadeIn(500);
        $('div.login_overlay, form#login a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('form#login').hide();
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form#login').on('submit', function(e){
        $('form#login p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl, 
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login #username').val(), 
                'password': $('form#login #password').val(), 
                'security': $('form#login #security').val() },
            success: function(data){
                $('form#login p.status').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });

});

//

jQuery(document).ready(function($) {

    // Show the sign up dialog box on click
    $('a#show_signup').on('click', function(e){
        $('body').prepend('<div class="login_overlay"></div>');
        $('form#signup').fadeIn(500);
        $('div.login_overlay, form#signup a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('form#signup').hide();
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form#signup').on('submit', function(e){
        $('form#signup p.status').show().text(ajax_login_object.loadingmessage);

        var ajax_url = ajax_login_object.redirecturl;
        // Do AJAX request
        $.post( ajax_url, data, function(response) {
     
              // If we have response
              if( response ) {
         
                if( response === '1' ) {
                  // If user is created
                  $('form#login p.status').text(data.message);
                } else {
                  $('.result-message').html( response ); // If there was an error, display it in results div
                  $('.result-message').addClass('alert-danger'); // Add class failed to results div
                  $('.result-message').show(); // Show results div
                }
              }
        });
        e.preventDefault();
    });
});