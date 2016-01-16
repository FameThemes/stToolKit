jQuery(function(){
    "use strict";
    
    var st_contact_form_valdation = function(value, type){
        switch (type){
        	case 'email':
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                return regex.test(value);
        	break;
        
        	case 'number':
                return jQuery.isNumeric(value);
        	break;
        
        	case 'phone':
                var regex = /^[0-9-+]+$/;
                return regex.test(value);
        	break;
        
        	default :
                return true;
            break;
        }
    }

	// Ajax Contact Form
	jQuery('.st-contact-form .field-submit').click(function(){
	    var $this = jQuery(this);
        var check = true;
        var data = "";
        var form_email_subject = $this.parents('.st-contact-form').find('.contact-form-email-subject').val();
        var form_email_from_name = $this.parents('.st-contact-form').find('.contact-form-from-name').val();
        var form_email_from = $this.parents('.st-contact-form').find('.contact-form-email-from').val();
        var form_email_to = $this.parents('.st-contact-form').find('.contact-form-email-to').val();
        var form_email_body = $this.parents('.st-contact-form').find('.contact-form-email-body').val();
        var mss_noti_captcha = $this.parents('.st-contact-form').find('.contact-form-mss-noti-captcha').val();
        var mss_noti_dont_send = $this.parents('.st-contact-form').find('.contact-form-mss-dont-send').val();
        var mss_noti_success = $this.parents('.st-contact-form').find('.contact-form-mss-success').val();
        $this.parents('.st-contact-form').find('.contact-field-item').each(function(index) {
           var self = jQuery(this).find('.field-control');
           var required = self.attr('aria-required');
           var type = self.attr('field-type');
           var validation = self.attr('field-validation');
           var val = self.val();
           var split = "";
           var itemdata = "";
           var label = self.parents('.contact-field-item').find('label').text();
           var name = self.parents('.contact-field-item').find('.field-control').attr('name');
           var check_option = false;
           if (index != 0) {
                split = ",";
           }
           switch (type){
            	case 'checkbox':
                case 'radio':
                    var l = self.parents('.contact-field-item').find('.field-control:checked').length;
                    self.parents('.contact-field-item').find('.field-control:checked').each(function(i){
                        var s = jQuery(this);
                        if (s.is(':checked')) {
                            var v = s.val();
                            if (i == l-1) {
                                itemdata += '{"name":"'+ name +'","type":"'+ type +'","label":"'+ label +'","value":"'+ v +'"}';
                            } else {
                                itemdata += '{"name":"'+ name +'","type":"'+ type +'","label":"'+ label +'","value":"'+ v +'"},';
                            }
                            check_option = true;
                        }
                    });
                    if (required == 'yes') {
                        check = check_option;
                    }
            	break;
                
                case 'submit':
                    itemdata = "";
            	break;
            
            	default :
                    // do code
                    itemdata = '{"name":"'+ name +'","type":"'+ type +'","label":"'+ label +'","value":"'+ val +'"}';
                break;
           }
           if (itemdata != '') {
            data += split + itemdata;
           }
           if (required == 'yes') {
                if (val == '') {
                    self.addClass('invalid');
                    check = false;
                }
           }
           if (!st_contact_form_valdation(val, validation)) {
                self.addClass('invalid');
                check = false;
           }
        });
        data = "["+ data +"]";
        $this.parents('.st-contact-form').find('.contact-form-message').html('').removeClass('alert-success alert-danger').hide();
        if (check == true) {
            $this.parents('.st-contact-form').find('.contact-form-loader').show();
            var dt = {
                action: 'st_contact_form',
                data: data,
                form_email_subject: form_email_subject,
                form_email_from_name: form_email_from_name,
                form_email_from: form_email_from,
                form_email_to: form_email_to,
                form_email_body: form_email_body,
                mss_noti_captcha: mss_noti_captcha,
                mss_noti_dont_send: mss_noti_dont_send,
                mss_noti_success: mss_noti_success
            };            
            // Ajax action
            jQuery.ajax({
                url: ajaxurl,
                data: dt,
                type: 'POST',
                success: function( response ) {
                    var mss = jQuery.parseJSON(response);
                    $this.parents('.st-contact-form').find('.contact-form-loader').hide();
                    if (mss.message != '') {
                        $this.parents('.st-contact-form').find('.contact-form-message').html(mss.message).addClass(mss.type).fadeIn();
                    }
                    if (mss.type == 'alert-success') {
                        $this.parents('.st-contact-form-action').trigger('reset');
                    }
                    var img_captcha = $this.parents('.st-contact-form').find('.field-type-captcha .field-img-captcha');
                    var base_url_img_captcha = img_captcha.attr('base-src');
                    img_captcha.attr('src', base_url_img_captcha +'&rand='+ Math.random());
                    var off_form = $this.parents('.st-contact-form').offset().top;
                    jQuery('body,html').animate({scrollTop:off_form},500);
                }
            });
        } else {
            var mss_noti = $this.parents('.st-contact-form').find('.contact-form-mss-notification').val();
            var response = '{"type":"alert-danger","message":"'+ mss_noti +'"}';
            var mss = jQuery.parseJSON(response);
            $this.parents('.st-contact-form').find('.contact-form-loader').hide();
            if (mss.message != '') {
                $this.parents('.st-contact-form').find('.contact-form-message').html(mss.message).addClass(mss.type).fadeIn();    
            }
            var img_captcha = $this.parents('.st-contact-form').find('.field-type-captcha .field-img-captcha');
            var base_url_img_captcha = img_captcha.attr('base-src');
            img_captcha.attr('src', base_url_img_captcha +'&rand='+ Math.random());
            var off_form = $this.parents('.st-contact-form').offset().top;
            jQuery('body,html').animate({scrollTop:off_form},500);
        }
        return false;
	});
    // Check Valid Control
    jQuery('.st-contact-form .contact-field-item .field-control').each(function(){
       var self = jQuery(this);
       jQuery(this).change(function(){
            var required = self.attr('aria-required');
            var type = self.attr('field-type');
            var validation = self.attr('field-validation');
            var val = self.val();
            if (required == 'yes') {
                if (val == '') {
                    self.addClass('invalid');
                } else {
                    self.removeClass('invalid');
                }
            }
            if (!st_contact_form_valdation(val, validation)) {
                self.addClass('invalid');
            } else {
                self.removeClass('invalid');
            }
       }); 
    });
    // Reload img captcha
    jQuery('.reload-img-captcha').click(function() {
        var $this = jQuery(this);
        var src = $this.parents('.field-type-captcha').find('.field-img-captcha').attr('base-src');
        $this.parents('.field-type-captcha').find('.field-img-captcha').attr('src', src +'&rand='+ Math.random());
        return false;
    });
    // add date picker to field date
    jQuery('.st-contact-form .contact-field-item .field-date').datepicker({
        dateFormat : 'yy-mm-dd'
    }).attr('type', 'text');
});