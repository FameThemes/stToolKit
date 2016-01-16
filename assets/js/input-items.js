/*
 *	JavaScript Wordpress editor
 *	Author: 		Ante Primorac
 *	Author URI: 	http://anteprimorac.from.hr
 *	Version: 		1.0
 *	License:
 *		Copyright (c) 2013 Ante Primorac
 *		Permission is hereby granted, free of charge, to any person obtaining a copy
 *		of this software and associated documentation files (the "Software"), to deal
 *		in the Software without restriction, including without limitation the rights
 *		to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *		copies of the Software, and to permit persons to whom the Software is
 *		furnished to do so, subject to the following conditions:
 *
 *		The above copyright notice and this permission notice shall be included in
 *		all copies or substantial portions of the Software.
 *
 *		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *		AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *		OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *		THE SOFTWARE.
 *	Usage:
 *		server side(WP):
 *			js_wp_editor( $settings );
 *		client side(jQuery):
 *			$('textarea').wp_editor( options );
 */


;(function( $, window ) {
    $.fn.wp_editor = function( options ) {
        if( !$(this).is('textarea') || typeof window.tinyMCEPreInit == 'undefined' || typeof QTags == 'undefined' ) return this;

        var default_options = {
            'wp_root': location.origin,
            'mode': 'html',
            'mceInit': {
                "mode":"textareas",
                "width":"100%",
                "theme": window.tinyMCEPreInit.mceInit.content.theme,
                "skin": window.tinyMCEPreInit.mceInit.content.skin,
                "language": window.tinyMCEPreInit.mceInit.content.lang,

                "formats": window.tinyMCEPreInit.mceInit.formats,
                "relative_urls":false,
                "remove_script_host":false,
                "convert_urls":false,
                "remove_linebreaks":false,
                browser_spellcheck: true,
                "fix_list_elements":true,
                entity_encoding: "raw",
                "keep_styles":true,
                "entities":window.tinyMCEPreInit.mceInit.content.entities,
                "accessibility_focus":true,
                "media_strict":false,
                "paste_remove_styles":false,
                "paste_remove_spans":false,
                "paste_text_use_dialog":true,
                "webkit_fake_resize":false,
                "preview_styles": window.tinyMCEPreInit.mceInit.content.preview_styles,
                "schema":"html5",
                 wpeditimage_disable_captions:  window.tinyMCEPreInit.mceInit.content.wpeditimage_disable_captions,
                 wpeditimage_html5_captions:  window.tinyMCEPreInit.mceInit.content.wpeditimage_html5_captions,
                "wp_fullscreen_content_css": tinyMCEPreInit.base + "/plugins/wpfullscreen/css/wp-fullscreen.css",
                external_plugins: window.tinyMCEPreInit.mceInit.content.external_plugins,
                "plugins": window.tinyMCEPreInit.mceInit.content.plugins,
                "content_css": window.tinyMCEPreInit.mceInit.content.content_css,
                "elements":"ap[id]",
                 menubar: false,
                "wpautop":true,
                indent: false,
                toolbar1: window.tinyMCEPreInit.mceInit.content.toolbar1 ,
                toolbar2: window.tinyMCEPreInit.mceInit.content.toolbar2 ,
                toolbar3: window.tinyMCEPreInit.mceInit.content.toolbar3,
                toolbar4: window.tinyMCEPreInit.mceInit.content.toolbar4,
                "tabfocus_elements": window.tinyMCEPreInit.mceInit.tabfocus_elements,
                "body_class":"ap[id]",
                "theme_advanced_resizing_use_cookie": false
            }
        }, id_regexp = new RegExp('ap\\[id\\]', 'g'), wp_root_regexp = new RegExp('ap\\[wp\\_root\\]', 'g');



      //  var default_options  = window.tinyMCEPreInit,  id_regexp = new RegExp('ap\\[id\\]', 'g'), wp_root_regexp = new RegExp('ap\\[wp\\_root\\]', 'g');;

        if(window.tinyMCEPreInit.mceInit['ap[id]'])
            default_options.mceInit = window.tinyMCEPreInit.mceInit['ap[id]'];

        var options = $.extend(true, default_options, options);
        $.each(options.mceInit, function( key, value ) {
            if( $.type( value ) == 'string' )
                options.mceInit[key] = value.replace(wp_root_regexp, options.wp_root);
        });
        return this.each(function() {
            if($(this).is('textarea')) {
               // console.dir(options);
                var current_id = $(this).attr('id');
                options.mceInit.elements = options.mceInit.elements.replace(id_regexp, current_id);
                options.mceInit.body_class = options.mceInit.body_class.replace(id_regexp, current_id);
             //   options.mode = options.mode == 'tmce' ? 'tmce' : 'html';

                window.tinyMCEPreInit.mceInit[current_id] = options.mceInit;

                $(this).addClass('wp-editor-area').show();
                var self = this;
                if($(this).closest('.wp-editor-wrap').length) {
                    var parent_el = $(this).closest('.wp-editor-wrap').parent();
                    $(this).closest('.wp-editor-wrap').before($(this).clone());
                    $(this).closest('.wp-editor-wrap').remove();
                    self = parent_el.find('textarea[id="' + current_id + '"]');
                }
                var wrap = $('<div id="wp-' + current_id + '-wrap" class="wp-core-ui wp-editor-wrap ' + options.mode + '-active" />'),
                    editor_tools = $('<div id="wp-' + current_id + '-editor-tools" class="wp-editor-tools hide-if-no-js" />'),
                    switch_editor_html = $('<a id="' + current_id + '-html" class="wp-switch-editor switch-html" onclick="switchEditors.switchto(this);">Text</a>'),
                    switch_editor_tmce = $('<a id="' + current_id + '-tmce" class="wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);">Visual</a>'),
                    media_buttons = $('<div id="wp-' + current_id + '-media-buttons" class="wp-media-buttons" />'),
                    insert_media_button = $('<a href="#" id="insert-media-button" class="button insert-media add_media" data-editor="' + current_id + '" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>'),
                    editor_container = $('<div id="wp-' + current_id + '-editor-container" class="wp-editor-container" />'),
                    content_css = false;
                    //content_css = Object.prototype.hasOwnProperty.call(window.tinyMCEPreInit.mceInit[current_id], 'content_css') ? window.tinyMCEPreInit.mceInit[current_id]['content_css'].split(',') : false;

                insert_media_button.appendTo(media_buttons);
                switch_editor_html.appendTo(editor_tools);
                switch_editor_tmce.appendTo(editor_tools);
                media_buttons.appendTo(editor_tools);

                editor_tools.appendTo(wrap);
                editor_container.appendTo(wrap);

                editor_container.append($(self).clone());

                if( content_css != false )
                    $.each( content_css, function() {
                        $(self).before('<link rel="stylesheet" type="text/css" href="' + this + '">');
                    } );
                $(self).before(wrap);
                $(self).remove();

                new QTags(current_id);
                QTags._buttonsInit();
                switchEditors.go(current_id, options.mode);

                $(wrap).on("click", ".insert-media", function(e) {
                    var f = $(this), d = f.data("editor"), c = {frame: "post",state: "insert",title: wp.media.view.l10n.addMedia,multiple: true};
                    e.preventDefault();
                    f.blur();
                    if (f.hasClass("gallery")) {
                        c.state = "gallery";
                        c.title = wp.media.view.l10n.createGalleryTitle
                    }
                    wp.media.editor.open(d, c);
                });
            }
        });
    }
})( jQuery, window );





stCopyLightBoxSettings = function($toBox, lb, content){
    if(typeof(lb.obj==='undefined')){
        lb.obj = jQuery('#'+lb.lbId);
    }

    jQuery('.stpb-lb-actions .pbdone', lb.obj).click(function(){
        var f =  jQuery('.stpb-lb-content-settings', lb.obj);

      // jQuery('body').trigger("st_lb_before_copy",false,f);
      jQuery('body').trigger({
          type:"st_lb_before_copy",
          item:f
      });

        // clone value
        jQuery('input[type=text], input[type=hidden]',f).each(function(){
            jQuery(this).attr('value',jQuery(this).val());
        });

        jQuery('textarea',f).each(function(){
            jQuery(this).text(jQuery(this).val());
        });

        jQuery('select option:selected',f).each(function(){
            jQuery(this).attr('selected', 'selected');
        });

        jQuery('select option:not(:selected)',f).each(function(){
            jQuery(this).removeAttr('selected');
        });

        jQuery('input[type=checkbox]:checked, input[type=radio]:checked',f).each(function(){
            jQuery(this).attr('checked','checked');
        });

        jQuery('input[type=checkbox]:not(:checked), input[type=radio]:not(:checked)',f).each(function(){
            jQuery(this).removeAttr('checked');
        });

        var change_html = f.html();
        $toBox.html(change_html);
        jQuery('body').trigger({
            type:"stpb_copy_item_done",
            item: $toBox
        });

        $toBox.trigger('stpb_lightbox_changed');
        lb.close();
    });
}

// light box
function stLightBox(title,content,  openCallBack, closeCallback){
    var lb = function(){
        var self= this;
        var obj ;
        var overlay ;
        var lbHeight;
        var lbId;

        if(typeof(title)==='undefined'){
            title ='';
        }

        content = jQuery(content);
        lb.lbId = 'lbid-'+(new Date().getTime());

        lb.open = function(){
            self.overlay = jQuery('<div class="stpb-lb-overlay" id="overlay-'+lb.lbId+'"></div>');
            jQuery('body').append(self.overlay);

            var lbTemplate ='<div class="stpb-lb-box" id="'+lb.lbId+'"> \
                                <div class="stpb-lb-wrap">\
                                        <div class="stpb-lb-outer">\
                                            <div class="stpb-lb-inner">\
                                                 <div class="stpb-lb-title">'+title+'</div>\
                                                 <span class="pbcancel top-cancel"><i class="iconentypo-cancel"></i></span>\
                                                 <div class="stpb-lb-content">\
                                                     <div class="stpb-lb-content-settings">\
                                                     </div>\
                                                 </div>\
                                                 <div class="stpb-lb-actions">\
                                                    <div class="stpb-lb-actions-inner">\
                                                        <input value="Cancel" class="pbcancel pbbtn button-secondary" type="button">\
                                                        <input value="Save" class="pbdone pbbtn button-primary" type="button">\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>';

            self.obj= jQuery(lbTemplate);
            jQuery('.stpb-lb-content-settings',self.obj).html(content);
            jQuery('body').append(self.obj);

            lb.fixedHeight();

            // close ligbox
            jQuery('.pbcancel, .close',self.obj).on('click',function(){
                lb.close();
            });

            if(typeof(openCallBack) =='function'){
                jQuery('body').trigger('stLightBox', lb,content);
                openCallBack(lb,content);
                jQuery('body').trigger("st_lb_open", lb,content);
            }

            // -- custom code ----------------------------------------------------
            self.obj.stInputItems();
            // -- end custom code ------------------------------------------------

        };

        lb.close = function(){

           // jQuery('body').trigger("st_lb_before_close", lb,content);

            if(typeof(closeCallback) =='function'){
                closeCallback(lb);
            }

            jQuery('body').trigger("st_lb_close", lb,content);

            self.overlay.remove();
            self.obj.remove();

        };

        lb.fixedHeight = function(){

            var wh =jQuery(window).height();
            lb.lbHeight =wh-80;
            jQuery('.stpb-lb-outer',self.obj).height(lb.lbHeight);

            var ch = jQuery('.stpb-lb-inner',self.obj).innerHeight() - jQuery('.stpb-lb-title',self.obj).outerHeight() - jQuery('.stpb-lb-actions',self.obj).outerHeight();

            if(ch<=0){
                ch = 300;
            }

            if(self.obj.hasClass('st-lb-lv2')){
                jQuery('.stpb-lb-outer',self.obj).height(wh-160);
                ch -= 80;
            }

            jQuery('.stpb-lb-content',self.obj).height(ch).css({ 'margin-top': jQuery('.stpb-lb-title').outerHeight() +'px'});
        };


    };

    var l = new lb();
    lb.open();
    //lb.close();
    jQuery(window).resize(function(){
        lb.fixedHeight();
    });


}; // end function  ;



/**
 * Date: 7/30/13
 * Time: 9:12 AM
 * To change this template use File | Settings | File Templates.
 */

(function(jQuery) {
    jQuery.fn.stInputItems = function(options) {
        var that = this; //.find('.stpb-lb-box');
        return this.each(function() {
            //return ;
            init(jQuery(this) );
        } );

         function init(content, in_ui){
            upload( content);
            link( content);
            colorPicker( content );
            iconBox(content);

            textarea(content );
            media(content);
            // editor(jQuery(this));
            jsSelectMultiple(content );
            slectChange(content);
            stTableBuilder(content);
            stTabsBuilder(content);
            layout(content);

            if(typeof(in_ui)!=='undefined' && in_ui===true){

            }else{

                ui( content );
            }

        }

        function link(content){
            //=------------------------------------------------------------

            var ajax_send = function(data_send,success_callback){
                jQuery.ajax({
                    type : 'post',
                    data :  data_send,
                    url : window.ajaxurl,
                    success: function(data){
                        if(typeof (success_callback)==='function'){
                            success_callback(data);
                        }
                    }
                });
            };

            var input_data = function (obj){

                jQuery('.link-item-data',obj).live('click',function(){
                    var data = jQuery(this).attr('data-link') || '';
                    var url=  jQuery(this).attr('data-url') || '';
                    var label=  jQuery(this).attr('data-label') || '';

                    jQuery('.link-data', obj).val(data);
                    jQuery('input.custom-link', obj).val(url);
                    jQuery('.preview-link .url', obj).show();

                    if(label!='' && url!=''){
                        var a = jQuery('<a/>');
                        a.attr('href',url);
                        a.attr('target','_blank');
                        a.text(label);
                        jQuery('.preview-link .url', obj).html(a);
                    }else if(label===''){
                        jQuery('.preview-link .url', obj).text(url);
                    }else{
                        jQuery('.preview-link .url', obj).hide();
                    }

                    close_box(obj);
                    return false;
                });

            };

            var search = function(obj){
                jQuery('.search-submit', obj).live('click', function(){

                    var data_send =  jQuery(this).attr('data') || '';
                    var  s = jQuery('input.link-search',obj).val() || '';

                    if(s!==''){
                        data_send =  jQuery.parseJSON(data_send);
                        data_send.s = s;

                        // console.debug(data_send);

                        var h = jQuery('.ajax-select-link',obj).height();
                        jQuery('.ajax-select-link',obj).height(h);

                        if(typeof (STBP)!=='undefined'){
                            jQuery('.ajax-select-link',obj).html('<div class="loading">'+STBP.config.loading+'</div>');
                        }

                        ajax_send(data_send,function(data){
                            jQuery('.ajax-select-link',obj).height('auto');
                            jQuery('.ajax-select-link',obj).html(data);
                        });

                    }

                    return false;
                });
            };


            var paging = function(obj){

                jQuery('.paging a',obj).live('click',function(){

                    var h = jQuery('.ajax-select-link',obj).height();
                    jQuery('.ajax-select-link',obj).height(h);

                    if(typeof (STBP)!=='undefined'){
                        jQuery('.ajax-select-link',obj).html('<div class="loading">'+STBP.config.loading+'</div>');
                    }

                    var request = jQuery(this).attr('href');
                    jQuery.ajax({
                        type : 'post',
                        url : request,
                        success: function(data){
                            jQuery('.ajax-select-link',obj).height('auto');
                            jQuery('.ajax-select-link',obj).html(data);
                        }
                    });
                    return false;
                });
            };

            var change_link_items_type = function(obj){
                 jQuery('.link-item-type',obj).change(function(){
                     var type = jQuery('.link-type',obj).val();

                    // jQuery('.ajax-items',obj).html('');
                    //// jQuery('.ajax-select-link',obj).html('');
                     jQuery('.custom-link', obj).hide();

                    if(type!=='custom'){

                        var item_type  = jQuery(this).val();
                        var data_send ={action: 'stpb_link_actions', _do : 'get_items', 'type' : type, item_type : item_type };
                        ajax_send(data_send, function(data){
                            jQuery('.ajax-select-link',obj).html(data);
                            paging(obj);
                        });

                    }


                 });
            };


            var change_link_type = function(obj){
                jQuery('.link-type',obj).change(function(){
                    var v = jQuery(this).val();

                    jQuery('.ajax-items',obj).html('');
                    jQuery('.ajax-select-link',obj).html('');
                    jQuery('.custom-link, .link-close', obj).hide();

                    if(v!=='custom'){

                        var data_send ={action: 'stpb_link_actions', _do : 'get_type', 'type' : v };
                        ajax_send(data_send, function(data){
                            jQuery('.ajax-items',obj).html(data);
                            change_link_items_type(obj);
                            jQuery('.link-item-type',obj).change();

                        });
                    }else{
                        jQuery('.custom-link, .link-close', obj).show();
                    }


                });
            };


            var close_box = function(obj){
                jQuery('.box-link',obj).hide();
                jQuery('.preview-link').show();
            };

            jQuery('.input-link',content).each(function(){
                var obj = jQuery(this);

                jQuery('.box-link',obj).hide();
                jQuery('.preview-link').show();

                jQuery('.change',obj).click(function(){
                    jQuery('.box-link',obj).show();
                    jQuery('.preview-link').hide();
                });

                jQuery('.link-close',obj).click(function(){
                    close_box(obj);
                    var type = jQuery('.link-type',obj).val();
                    if(type==='custom'){
                        var curl =  jQuery('input.custom-link',obj).val();
                        jQuery('.preview-link .url', obj).text(curl);
                        var data = {type: type, url:  curl };
                        jQuery('.link-data', obj).val(JSON.stringify(data));

                    }
                });

                jQuery('.link-cancel',obj).click(function(){
                    close_box(obj);
                });

                change_link_type(obj);
                search(obj);
                input_data(obj);

            });

            //=------------------------------------------------------------
        }


        function layout(content){
            jQuery('.st-input-layout',content).each(function(){
                var layout = jQuery(this);
                jQuery('label input:radio', layout).each(function(){
                    var r = jQuery(this);
                    if(r.attr('checked')){
                        r.parents('label').addClass('active');
                    }
                });
            });

            jQuery('.st-input-layout label input:radio',content).change(function(){
                var r = jQuery(this);
                var g = r.parents('.st-input-layout');
                jQuery('label',g).removeClass('active');
                r.parents('label').addClass('active');
            });

        }

        function slectChange(content){

            var do_change = function(s){
                var selector = s.attr('show-on-change');
                var v = s.val();

                if(typeof(selector)!=='undefined' && selector!==''){

                    if(s.hasClass('parent-hide')){
                        jQuery(selector).hide();
                    }else{
                        jQuery(selector).hide();
                        jQuery(selector).find('select.select-one').addClass('parent-hide');
                        jQuery(selector+'[show-on~="'+v+'"]').show().find('select.select-one').removeClass('parent-hide');

                        var lv2 = jQuery(selector);

                        if(lv2.length>0){

                           setTimeout(function(){

                               lv2.each(function(){
                                   var s = jQuery(this);
                                   jQuery('select.select-one', s).each(function(){
                                       var slv2 = jQuery(this);
                                       do_change(slv2);
                                   });

                                   jQuery('select.select-one',s).live('change',function(){
                                       var slv2 = jQuery(this);
                                       do_change(slv2);
                                   });

                               });

                           }, 300);

                        }
                    }

                }
            };

            jQuery('select.select-one',content).each(function(){
                var s = jQuery(this);
                do_change(s);
            });

            jQuery('select.select-one').live('change',function(){
                var s = jQuery(this);
                do_change(s);
            });


        }

        function  jsSelectMultiple(content){
            jQuery('select.js-multiple',content).each(function(){
                var s = jQuery(this);

                var ids = s.attr('selected-ids');
                if (typeof (ids) == 'undefined' || ids == '') {

                } else {
                    ids = ids.split(',');
                    jQuery('option', s).each(function () {
                        var v = jQuery(this).val();
                        if (jQuery.inArray(v, ids) >= 0) {
                            jQuery(this).attr('selected', 'selected');
                        }
                    });
                }

            });

        }

        function textarea(content){
          
            /*
            jQuery('.st-editor textarea',content).each(function(){

                var  p = jQuery(this).parents('.st-editor');

                jQuery('.quicktags-toolbar', content).remove();
                var id = 'textarea-id-'+new Date().getTime();
                jQuery('.mceEditor, .mce-tinymce',p).remove();
                jQuery(this).removeAttr('style');
                jQuery(this).attr('id',id);

                jQuery(this).wp_editor();

            });
            */


        }


        // for color picker
        function colorPicker(content){

            jQuery('.st-color-wrap',content).each(function(){
                var  p = jQuery(this);

                if(jQuery('.wp-picker-container',p).length>0){
                    var input = jQuery('.wp-picker-input-wrap .st-color-picker', p).eq(0).clone();
                    p.html(input);
                }
                //wpColorPicker
                // jQuery('.st-color-picker',content).iris({
                jQuery('.st-color-picker',p).wpColorPicker({
                    palettes: true,
                    width: 250,
                    change: function(event, ui) {
                        // jQuery(this).parent().find('.color-preview').css( 'backgroundColor', ui.color.toString());
                        //  jQuery('body').trigger('stInputItems_colorPicker_change',content);
                    }
                });

            });

        }

        // for upload
        function upload(content) {
            // remove upload item
            jQuery('.st-upload-wrap .remove_image',content).click(function() {
                var p   = jQuery(this).parent('.st-upload-wrap');
                jQuery('.upload-preview',p).find('img').remove().end().html('');
                jQuery('.st-upload-input',p).val('');
                jQuery(this).fadeOut();
                return false;
            });

            // open upload box
            jQuery('.st-upload-wrap .st-upload-button',content).live('click',function() {
                var p   = jQuery(this).parent('.st-upload-wrap');
                var post_ID = 0;
                if(jQuery(this).attr('data-type')!='id'){
                    // window.original_send_to_editor = window.send_to_editor;
                    window.send_to_editor = function(html) {
                        // alert(html);
                        var  imgurl = jQuery('img',html).attr('src');
                        // var id= jQuery('img',html).attr('id');
                        if(typeof(imgurl)!=='undefined'){
                            jQuery('.st-upload-input',p).val(imgurl);
                            jQuery('.upload-preview',p).html('<a  href="'+imgurl+'" target="_blank"> <img src="'+imgurl+'" alt=""/> </a>');
                            jQuery('.remove_image',p).fadeIn();

                            jQuery('.remove_image', p).click(function(){
                                jQuery('.upload-preview',p).find('img').remove().end().html('');
                                jQuery('.st-upload-input',p).val('');
                                jQuery(this).fadeOut();
                                return false;
                            });

                            jQuery('body').trigger('stInputItems_upload_change',content,imgurl);
                        }

                        jQuery('body').trigger('stInputItems_upload_send_to_editor',content,html);
                        tb_remove();
                    }
                }

                var _custom_media = true;
                var frame = wp.media;
                var _orig_send_attachment = wp.media.editor.send.attachment;
                var send_attachment_bkp = wp.media.editor.send.attachment;

                frame.view.settings.mimeTypes ={'image' : 'Images'};
                frame.view.l10n.allMediaItems ='All Images';
                frame.view.l10n.insertMediaTitle ='Upload and select image';
                frame.view.l10n.insertIntoPost = 'Insert';

                frame.view.settings.tabs = false;
                frame.editor.send.attachment = function(props, attachment) {
                    return _orig_send_attachment.apply( this, [props, attachment] );
                }
                frame.editor.open();

                return false;
            });
        };



        // console.debug(wp.shortcode('[button title="button title"]'))

        function media(content){
            jQuery('.st-upload-media .remove-media').live('click',function(){
                var p   = jQuery(this).parent('.st-upload-media');
                jQuery('.st-media-input',p).val('');
                jQuery(this).hide();
                jQuery('.media-preview',p).html('');
                jQuery('.st-media-input',p).trigger({
                    'type': 'stpb_media_remove'
                });
                return false;
            });

            // open upload box
            jQuery('.st-upload-media .st-upload-button',content).live('click',function() {
                var p   = jQuery(this).parent('.st-upload-media');
                var input = jQuery('.st-media-input',p);
                var mediaType = p.attr('data-type') || '';
                if(typeof (mediaType)==='undefined'){
                    mediaType = '';
                }

                var current_val =  input.val();

                if(mediaType=='gallery'){
                    //---------------------------------------------------
                    // thanks : http://shibashake.com/wordpress-theme/how-to-add-the-wordpress-3-5-media-manager-interface-part-2
                    // see: in wp-includes/js/media-editor.js
                    // console.debug(wp.media.view.l10n);
                    var  media_select = function(current_val) {
                        if(typeof(current_val)==='undefined' ||  current_val==''){
                            return {};
                        }else{
                            current_val = '[st-media ids="'+current_val+'"]';
                        }

                        var shortcode = wp.shortcode.next( 'st-media',current_val),
                            defaultPostId = wp.media.gallery.defaults.id,
                            attachments, selection;

                        // Bail if we didn't match the shortcode or all of the content.
                        if ( ! shortcode )
                            return;

                        // Ignore the rest of the match object.
                        shortcode = shortcode.shortcode;

                        if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
                            shortcode.set( 'id', defaultPostId );

                        attachments = wp.media.gallery.attachments( shortcode );
                        selection = new wp.media.model.Selection( attachments.models, {
                            props:    attachments.props.toJSON(),
                            multiple: true
                        });

                        selection.gallery = attachments.gallery;

                        // Fetch the query's attachments, and then break ties from the
                        // query to allow for sorting.
                        selection.more().done( function() {
                            // Break ties with the query.
                            selection.props.set({ query: false });
                            selection.unmirror();
                            selection.props.unset('orderby');
                        });

                        return selection;
                    };

                    var state = 'gallery-library';
                    if(typeof(current_val)==='undefined' ||  current_val==''){
                        var s =  media_select(current_val);
                    }else{
                        var s =  media_select(current_val);
                        state= 'gallery-edit';
                    }

                    frame = wp.media({
                        id: 'st-media-gallery',
                        //className: 'st-media',
                        frame:      'post',
                        state:      state, // gallery-library | gallery-edit
                        title:      wp.media.view.l10n.editGalleryTitle,
                        library : { type : 'image'},
                        editing:    true,
                        multiple:   true,
                        displayUserSettings: true,
                        selection:  s
                    });

                    frame.on( 'select update insert',function(){
                        var media_attachment = frame.state().get('selection').toJSON();
                        var controller = frame.states.get('gallery-edit');
                        var gallery = controller.get('library');
                        // Need to get all the attachment ids for gallery
                        var ids = gallery.pluck('id');
                        // console.debug(gallery);
                        var attrs ={};
                        attrs.ids = gallery.pluck('id');
                        /* attrs.ids = '1,2,3,4,5';
                         var st = new wp.shortcode({
                         tag:    'gallery',
                         attrs:  attrs,
                         type:   'single'
                         });
                         */
                        input.val(attrs.ids.join(','));

                        var preview =  '';
                        var image_urls = [];
                        gallery.forEach( function (item  ){
                            // console.debug(item);
                            var img_url;
                            if(typeof (item.attributes.sizes.thumbnail)!=='undefined'){
                                img_url = item.attributes.sizes.thumbnail.url;
                            }else{
                                img_url = item.attributes.sizes.full.url;
                            }

                            preview += ' <div class="mi"><div class="mid"><img src="'+img_url+'" alt=""></div></div>';
                            image_urls.push(img_url);
                        } );

                        jQuery('.media-preview',p).html(preview);
                        jQuery('.remove-media',p).show();

                        input.trigger({
                            'type': 'stpb_media_gallery_change',
                            'gallery': image_urls
                        });

                    });

                    frame.open();

                    // ------------------------------------------------------

                }else { // image

            var frame = wp.media({
                title : wp.media.view.l10n.addMedia,
                multiple : false,
                library : { type : mediaType },
                button : { text : 'Insert' }
            });

            //  console.debug(frame.view.settings);

            frame.on('close',function() {
                // get selections and save to hidden input plus other AJAX stuff etc.
                var selection = frame.state().get('selection');
                // console.log(selection);
            });

            frame.on('select', function(){
                // Grab our attachment selection and construct a JSON representation of the model.
                var media_attachment = frame.state().get('selection').first().toJSON();
                //  console.debug(media_attachment);
                // console.debug(media_attachment);
                // media_attachment= JSON.stringify(media_attachment);

                if(mediaType!=='audio'){
                    input.val(media_attachment.id);
                    var  preview, img_url;

                    if(typeof (media_attachment.sizes.thumbnail)!=='undefined'){
                        img_url = media_attachment.sizes.thumbnail.url;
                    }else{
                        img_url = media_attachment.sizes.full.url;
                    }

                    preview = ' <div class="mi"><div class="mid"><img src="'+img_url+'" alt=""></div></div>';

                    jQuery('.media-preview',p).html(preview);
                    jQuery('.remove-media',p).show();
                }else{
                    input.val(media_attachment.url);

                }

                input.trigger({
                    'type': 'stpb_media_image_change',
                    'image': img_url
                });
            });

            frame.on('open',function() {

            });
            frame.open();

        }
        /// end media action
        return false;
    });
}


// for select icon
function iconBox(content){
    // icon popup
    jQuery('.st-icon-popup-wrap',content).each(function(){
        var  p = jQuery(this);

        jQuery('.icon-action .selected-icon',p).on('click',function(){

            var e = jQuery(this).parents('.icon-action');

            if(jQuery('.bst-list-icons-pp').length<=0){
                jQuery('.bst-list-icons-pp-ov, .bst-list-icons-pp').remove();
            }else{

            }


            jQuery('body').append('<div class="bst-list-icons-pp-ov" style="display: none;"> </div><div style="display: none;" class="bst-list-icons-pp"> <div class="bst-list-icons-w"> <a class="cancel" href="#"><i class="iconentypo-cancel"></i></a>  <div class="bst-list-icons-inner"></div> </div> </div>');
            for(var i =0; i < STBP.font_icons.length; i++){
                icon = STBP.font_icons[i];
                jQuery('.bst-list-icons-inner').append('<span><i class="'+icon+'" data-value="'+icon+'" title="'+icon+'"></i></span>');
            }

            jQuery('.bst-list-icons-pp-ov, .bst-list-icons-pp').fadeIn(300);

            // bst-list-icons-pp
            jQuery('.bst-list-icons-pp .cancel').click(function(){
                jQuery('.bst-list-icons-pp-ov, .bst-list-icons-pp').fadeOut(300, function(){
                    jQuery('.bst-list-icons-pp-ov, .bst-list-icons-pp').remove();
                });
                return false;
            });

            jQuery('.bst-list-icons-pp .bst-list-icons-inner i').click(function(){

                icon_val =  jQuery(this).attr('data-value');

                e.find('.st-icon-value').val(icon_val);
                e.find('.selected-icon i').html('<i  class="'+icon_val+'"></i> ');

                e.find('.st-icon-value').trigger({
                    'type': 'stpb_icon_popup_change',
                    'icon': icon_val,
                    'el': e
                });

                jQuery('.bst-list-icons-pp-ov, .bst-list-icons-pp').fadeOut(300);
                return false;

            });

        });
    });
}

/* for ui items */
function ui(content){

    var setting_name = jQuery('.st-current-index',content).val();

    var set_name  = function(obj,name,index){
        jQuery('select,input,textarea',obj).each(function(){
            var input_obj =  jQuery(this);
            var data_name = input_obj.attr('data-ui-name') || '';
            if(typeof(data_name)!==undefined &&  data_name!==''){
                input_obj.attr('name',setting_name+name+'['+index+']'+data_name);
                input_obj.attr('data-name',name+'['+index+']'+data_name);
            }
        });
    }

    var ui_rename = function(uip){
        var current_name = jQuery('.list-items',uip).attr('data-name');
        jQuery('.list-items > li',uip).each(function(index){
            set_name(jQuery(this), current_name ,index);
        });
    }

    jQuery('.st-editor-ui',content).each(function(){
        var uip = jQuery(this);
        // sortable
        jQuery('.list-items',uip).sortable({
            handle: '.stpb-hndle',
            stop: function(event, ui){
                ui_rename(uip);
            }
        });


        jQuery('.st-add-ui',uip).on('click',function(){
            var item = jQuery('.tpl',uip).html();
            item = jQuery('<li>'+item+'</li>');
            jQuery('.list-items',uip).append(item);
            item.find('.stpb-widget').removeClass('closed');

           // console.debug(item);

            ui_rename(uip);
            // call other item again
            init(item, true);
        });

        // toggle open and close
        jQuery('.widget .ui-handlediv,  .widget .close',uip).live('click',function(){
            var  p =  jQuery(this).parents('.widget');
            if(p.hasClass('closed')){
                p.find('.inside').slideDown(400,function(){
                    p.removeClass('closed');
                });

            }else{
                p.find('.inside').slideUp(400,function(){
                    p.addClass('closed');;
                });
            }
            return ;
        });

        // live view thumb image or icon when change
        // if is image - when change
        jQuery('.widget .st-media-input',uip).live('stpb_media_image_change',function(item){
            // console.debug(item);
            var  p =  jQuery(this).parents('.widget');
            jQuery('.thumb-previw .mi',p).html('<img src="'+item.image+'" alt=" " />');
        });
        // when remove image
        jQuery('.widget .st-media-input',uip).live('stpb_media_remove',function(item){
            // console.debug(item);
            var  p =  jQuery(this).parents('.widget');
            jQuery('.thumb-previw .mi',p).html('');
        });
        // if is icon
        // stpb_icon_popup_change
        jQuery('.widget .st-icon-value',uip).live('stpb_icon_popup_change',function(item){
            // console.debug(item);
            var  p =  jQuery(this).parents('.widget');
            var icon =  item.icon || '';
            jQuery('.thumb-previw .mi',p).html('<i class="'+icon+'"></i>');
        });

        // switch icon or image
        if( jQuery('.widget .switch_icon_image',uip).length>0){
            jQuery('.widget .switch_icon_image',uip).each(function(){
                var  p =  jQuery(this).parents('.widget');
                var type = jQuery(this).val();
                jQuery('.ui-item-icon-image',p).hide();
                jQuery('.ui-item-icon-image.ui-item-'+type,p).show();
                // change thumbnail
                switch(type){
                    case 'image':
                        jQuery('.thumb-previw .mi',p).html(jQuery('.media-preview .mid',p).html());
                        break;
                    default : // icon
                        jQuery('.thumb-previw .mi',p).html(jQuery('.selected-icon',p).html());

                }
            });
        }else{
            jQuery('.widget',uip).each(function(){
                var p  = jQuery(this);
                if(jQuery('.media-preview .mid',p).length>0){
                    jQuery('.thumb-previw .mi',p).html(jQuery('.media-preview .mid',p).html());
                }else if(jQuery('.selected-icon',p).length){
                    jQuery('.thumb-previw .mi',p).html(jQuery('.selected-icon',p).html());
                }

            } );

        }


        jQuery('.widget .switch_icon_image',uip).live('change',function(){
            var  p =  jQuery(this).parents('.widget');
            var type = jQuery(this).val();

            jQuery('.st-list-icons-wrap, .toggle-icons-w',p).hide(200,function(){
                jQuery('.icon-action',p).show();
            });

            jQuery('.ui-item-icon-image',p).hide();
            jQuery('.ui-item-icon-image.ui-item-'+type,p).show();
            // change thumbnail
            switch(type){
                case 'image':
                    jQuery('.thumb-previw .mi',p).html(jQuery('.media-preview .mid',p).html());
                    break;
                default : // icon
                    jQuery('.thumb-previw .mi',p).html(jQuery('.selected-icon',p).html());

            }

        });

        // remove
        jQuery('.widget .remove',uip).live('click',function(){
            var  p =  jQuery(this).parents('.widget');
            p.remove();
            ui_rename(uip);
            return ;
        });

        // live update title
        jQuery('.widget .ui-title',uip).live('keyup',function(){
            var  p =  jQuery(this).parents('.widget');
            var  v = jQuery(this).val();
            jQuery('.stpb-hndle .live-title',p).text(v);
        });
        

        
        // builder item contact form
        var show_option = function(uip) {
            jQuery('select.select-one-items', uip).each(function(){
                var ts = jQuery(this); 
                var tv = ts.val();
                var tselector = ts.attr('show-on-change');
                ts.parents('.stpb-widget').find(tselector).hide();
                ts.parents('.stpb-widget').find(tselector+'[show-on~="'+tv+'"]').show();
            });
        }
        show_option(uip);
        
        // add new option
        jQuery('.st-builder-item-contact-form-add-option',uip).live('click',function(){
            var item = jQuery('.st-builder-item-contact-form-item-option-tpl',uip).html();
            item = '<li class="st-builder-item-contact-form-item-option">'+ item +'</li>';
            jQuery(this).parents('.st-builder-item-contact-form-item').find('.contact-form-list-items-option').append(item);
            update_value_option(jQuery(this),uip);
            return false;
        });
        
        // sort option select, radio, checkbox
        var init_sortable_option = function(uip) {
            jQuery('.contact-form-list-items-option',uip).sortable({
                handle: '.st-builder-item-contact-form-sort-option',
                stop: function(event, ui){
                    ui_rename(uip);
                    update_value_option(ui.item,uip);
                }
            });
        }

        init_sortable_option(uip);
        
        // remove option
        jQuery('.st-builder-item-contact-form-remove-option',uip).live('click',function(){
            var  p =  jQuery(this).parents('.st-builder-item-contact-form-item-option');
            var pp = p.parents('.contact-form-list-items-option');
            p.remove();
            ui_rename(uip);
            update_value_option(pp,uip);
            return false;
        });
        
        var update_value_option = function(element,uip) {
            var self = element;
            var tmp_val = '';
            self.parents('.st-builder-item-contact-form-item').find('.contact-form-list-items-option .st-builder-item-contact-form-item-option').each(function(index){
                if (index != 0) {
                    tmp_val += '-|-';
                }
                tmp_val += jQuery(this).find('.contact-form-value-option').val();
            });
            self.parents('.st-builder-item-contact-form-item').find('.contact-form-tmp-value-option').val(tmp_val);
        }
        
        var makeid = function() {
            var text = "";
            //var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            var possible = "0123456789";
            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        }
        
        // update data options
        jQuery('.contact-form-value-option', uip).live('change',function(){
            update_value_option(jQuery(this),uip);
        });
        
        // update live label
        jQuery('.stpb-widget .contact-form-item-label', uip).live('keyup',function(){
            var s = jQuery(this);
            var v = s.val();
            s.parents('.stpb-widget').find('.live-title').html(v);
        });
        
        // option field type change
        jQuery('select.select-one-items', uip).live('change',function(){
            var s = jQuery(this);
            var v = s.val();
            var selector = s.attr('show-on-change');
            s.parents('.stpb-widget').find('.live-type').html(v);
            s.parents('.stpb-widget').find(selector).hide();
            s.parents('.stpb-widget').find(selector+'[show-on~="'+v+'"]').show();
            show_option(uip);
            init_sortable_option(uip);
        });
        
        // when add new item
        jQuery('.st-add-ui',uip).on('click',function(event){
            var s = jQuery(this);
            var idtmp = '';
            var init_makeid = setInterval(function(){
                s.parents('.st-editor-ui').find('.contact-form-item-name').each(function(){
                    var v = jQuery(this).val();
                    idtmp = 'field_'+ makeid();
                    if (v == '') {
                        jQuery(this).val(idtmp);
                        jQuery(this).parents('.st-builder-item-contact-form-item').find('.contact-form-item-name-label').text('['+ idtmp +']');
                    }
                });
                init_makeid = window.clearInterval(init_makeid);
                jQuery('select.select-one-items').trigger('change');
            }, 50);
            init_sortable_option(uip);
        });
        
        // generate message body
        jQuery('.btn-contact-form-generate').live('click', function() {
            var s = jQuery(this);
            var temp = '';
            s.parents('.stpb-lb-content-settings').find('.item .stpb-widget').each(function(){
                var t = jQuery(this);
                var type = t.find('.contact_field_type').val();
                var l = t.find('.contact-form-item-label').val();
                var v = t.find('.contact-form-item-name').val();
                if (type != 'captcha' && type != 'submit') {
                    temp += '<b>'+ l +'</b> : ['+ v +']\n';    
                }
            });
            s. parents('.stpb-lb-content-settings').find('.contact-from-mss-body').val(temp);
            return false; 
        });
    });
}
}
})(jQuery);
