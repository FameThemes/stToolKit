jQuery(document).ready(function(){

    // create form
    var f = jQuery('#st-tmpl-add-widget').html();
    jQuery('.widget-liquid-right').append(f);

    var st_nonce =  '';
    // add remove buttons
    jQuery('.sidebar-st-custom').each(function(){
        jQuery('h3',jQuery(this)).append('<span class="st-remove-widget"></span>');
    });

    // add widget function
    jQuery('.widgets-holder-wrap .st-remove-widget').on('click',function(){
        var widget = jQuery(this).parents('.widgets-holder-wrap');
        widget_name = jQuery.trim(jQuery('.sidebar-name > h3',widget).text()),


        jQuery.ajax({
            type: "POST",
            url: window.ajaxurl,
            data: {
                action: 'st_ajax_delete_custom_sidebar',
                name: widget_name,
                _wpnonce: window.st_sidebar_nonce
            },

            beforeSend: function()
            {
                widget.find('.spinner').addClass('activate_spinner');
            },
            success: function(response)
            {
                if(response == 'sidebar-deleted')
                {

                    widget.remove();
                    window.location = window.location;
                    return false;

                }
            }
        });

    });





});