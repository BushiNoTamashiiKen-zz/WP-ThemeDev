jQuery(document).ready(function($) {
 
  /**
   * When user clicks on button...
   *
   */
  $('#btn-new-user').click( function(event) {
 
    /**
     * Prevent default action, so when user clicks button he doesn't navigate away from page
     *
     */
    if (event.preventDefault) {
        event.preventDefault();
    } else {
        event.returnValue = false;
    }
 
    // Show 'Please wait' loader to user, so she/he knows something is going on
    $('.indicator').show();
 
    // If for some reason result field is visible hide it
    $('.result-message').hide();
 
    // Collect data from inputs
    var reg_nonce = $('#vb_new_user_nonce').val();
    var reg_user  = $('#vb_username').val();
    var reg_pass  = $('#vb_pass').val();
    var reg_mail  = $('#vb_email').val();
    var reg_name  = $('#vb_name').val();
    var reg_nick  = $('#vb_nick').val();
 
    /**
     * AJAX URL where to send data
     * (from localize_script)
     */
    var ajax_url = vb_reg_vars.vb_ajax_url;
 
    // Data to send
    data = {
      action: 'register_user',
      nonce: reg_nonce,
      user: reg_user,
      pass: reg_pass,
      mail: reg_mail,
      name: reg_name,
      nick: reg_nick,
    };
 
    // Do AJAX request
    $.post( ajax_url, data, function(response) {
 
      // If we have response
      if( response ) {
 
        // Hide 'Please wait' indicator
        $('.indicator').hide();
 
        if( response === '1' ) {
          // If user is created
          $('.result-message').html('Your submission is complete.'); // Add success message to results div
          $('.result-message').addClass('alert-success'); // Add class success to results div
          $('.result-message').show(); // Show results div
        } else {
          $('.result-message').html( response ); // If there was an error, display it in results div
          $('.result-message').addClass('alert-danger'); // Add class failed to results div
          $('.result-message').show(); // Show results div
        }
      }
    });
 
  });
});