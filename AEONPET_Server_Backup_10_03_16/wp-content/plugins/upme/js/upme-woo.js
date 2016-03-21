jQuery(document).ready(function($) {
    
    $(".upme-woo-account-navigation-item").click(function(){
        $('.upme-woo-account-navigation-item').removeClass("upme-woo-active");
        $(this).addClass("upme-woo-active");
        var panel_id = $(this).attr("data-nav-ietm-id");
        $(".upme-woo-account-navigation-content").hide();
        $("#"+panel_id).show();
    });
    
    $(".upme-field-edit .upme-fire-editor").click(function(){
        $(".upme-woo-account-navigation-content").hide();
        $('.upme-woo-account-navigation-item').hide();
    });
    

});