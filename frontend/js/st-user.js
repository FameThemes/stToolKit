jQuery(function(){
    "use strict";

	// Ajax Login
	jQuery('.st-login form').submit(function(){
        var $thisform = jQuery( this );
        var action    = $thisform.attr('action');
        var remember = '';
        jQuery('.'+ window.st_login_params.error_class).remove();
        // Check required fields as a minimum
        var user_login = $thisform.find('#st-login-u').val();
        var user_password = $thisform.find('#st-login-p').val();
        if ( user_login == '' ) {
            $thisform.children('.alert-warning').remove();
        	$thisform.prepend('<p class="'+ window.st_login_params.error_class +'">' + window.st_login_params.username_required +'</p>');
        	return false;
        }
        if ( user_password == '' ) {
            $thisform.children('.alert-warning').remove();
        	$thisform.prepend('<p class="'+ window.st_login_params.error_class +'">' + window.st_login_params.password_required +'</p>');
        	return false;
        }
        // Check for SSL/FORCE SSL LOGIN
        if ( window.st_login_params.force_ssl_login == 1 && window.st_login_params.is_ssl == 0 )
        	return true;
        $thisform.block({ message: null, overlayCSS: {
            backgroundColor: '#fff',
            opacity:         0.6
        }});
        if ( $thisform.find('input[name="rememberme"]:checked' ).size() > 0 ) {
        	remember = $thisform.find('input[name="rememberme"]:checked').val();
        } else {
        	remember = '';
        }
        var data = {
            action: 		'st_login_process',
            user_login: 	user_login,
            user_password: 	user_password,
            remember: 		remember,
            redirect_to:	$thisform.find('#st-login-redirect').val()
        };
        // Ajax action
        jQuery.ajax({
            url: window.st_login_params.ajax_url,
            data: data,
            type: 'POST',
            success: function( response ) {
            	// Get the valid JSON only from the returned string
                if ( response.indexOf("<!--SBL-->") >= 0 )
                    response = response.split("<!--SBL-->")[1]; // Strip off before SBL
                if ( response.indexOf("<!--SBL_END-->") >= 0 )
                    response = response.split("<!--SBL_END-->")[0]; // Strip off anything after SBL_END
            	// Parse
                var result = jQuery.parseJSON( response );
            
                if ( result.success == 1 ) {
                    window.location = result.redirect;
            	} else {
                    $thisform.children('.alert-warning').remove();
                    $thisform.prepend('<p class="'+ window.st_login_params.error_class + '">'+ result.error +'</p>');
                    $thisform.unblock();
            	}
            }
        });
        return false;
	});

    // Ajax lost password
    jQuery('.loginModal .lostpasswordform').submit(function(){

        var $thisform = jQuery( this );
        var remember = '';
        $thisform.block({ message: null, overlayCSS: {
            backgroundColor: '#fff',
            opacity:         0.6
        }});

        /*
        var data = {
            action: 		'st_lostpassword',
            user_login: 	user_login,
            redirect_to:	''
        };
        */

        var data = $thisform.serialize();
        data = 'action=st_lostpassword&'+data;

        // Ajax action
        jQuery.ajax({
            url: window.st_login_params.ajax_url,
            data: data,
            type: 'POST',
            success: function( response ) {
                // Get the valid JSON only from the returned string
                $thisform.unblock();
                var result = jQuery.parseJSON( response );
                // Parse
                if(jQuery('.ajax-note',$thisform).length<=0){
                    $thisform.prepend('<div class="ajax-note"></div>');
                }

                jQuery('.ajax-note',$thisform).html(result.msg);

            }
        });


        return false;
    });



    // Ajax Register
	jQuery('.st-register form').submit(function(){
        var $thisform = jQuery( this );
        var action    = $thisform.attr('action');
        jQuery('.'+ window.st_register_params.error_class).remove();
        // Check required fields as a minimum
        var user_login = $thisform.find('#st-register-u').val();
        var user_email = $thisform.find('#st-register-e').val();
        if ( user_login == '' ) {
            $thisform.children('.alert-warning').remove();
        	$thisform.prepend('<p class="'+ window.st_register_params.error_class +'">'+ window.st_register_params.username_required +'</p>');
        	return false;
        }
        if ( user_email == '' ) {
            $thisform.children('.alert-warning').remove();
        	$thisform.prepend('<p class="'+ window.st_register_params.error_class +'">'+ window.st_register_params.email_required +'</p>');
        	return false;
        }
        $thisform.block({ message: null, overlayCSS: {
            backgroundColor: '#fff',
            opacity:         0.6
        }});
        var data = {
            action: 		'st_register_process',
            user_login: 	user_login,
            user_email: 	user_email,
            redirect_to:	$thisform.find('#st-register-redirect').val()
        };

        // Ajax action
        jQuery.ajax({
            url: window.st_register_params.ajax_url,
            data: data,
            type: 'POST',
            success: function( response ) {
                // Parse
                var result = jQuery.parseJSON( response );
                var error = '';
                if (typeof(result) == 'object') {
                    for(var key in result) {
                        error += result[key] +'<br/>';
                    }
                }
                if ( error == '' ) {
                	window.location = data.redirect_to;
                } else {
                    $thisform.children('.alert-warning').remove();
                	$thisform.prepend('<p class="' + window.st_register_params.error_class + '">' + error + '</p>');
                	$thisform.unblock();
                }
            }
        
        });
        return false;
	});
    
    
    // Login Modal
    jQuery('.st-login-modal .open-login-modal, .modal-form-change').click(function() {
       var tab = jQuery(this).attr('alt');
       jQuery('#loginModal .tab').hide().removeClass('active');
       jQuery('#loginModal #'+ tab ).show().addClass('active');
       //return false;
       // Dont need return false
    });

});