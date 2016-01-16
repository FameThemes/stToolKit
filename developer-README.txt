jQuery page builder input trigger events
See:  assets/js/input-items.js

+ Color Picker: when color change
    - jQuery('body').trigger('stInputItems_colorPicker_change',content);

+ Upload input:
    - when image change:
        jQuery('body').trigger('stInputItems_upload_change',content,imgurl);
    - when send media content :
        jQuery('body').trigger('stInputItems_upload_send_to_editor',content,html);


/* PHP */
Add more/Remove page builder items for page builder
     return  apply_filters('stpb_list_items',$items);

+ Add More settings for page page options
    <?php do_action('st_page_options_more_settings',$name,$save_values); ?>


--------------
 jQuery('.st-media-input',p).trigger({
                    'type': 'stpb_media_remove'
                });

input.trigger({
                        'type': 'stpb_media_gallery_change',
                        'gallery': image_urls
                    });

input.trigger({
                        'type': 'stpb_media_image_change',
                        'image': media_attachment.sizes.thumbnail.url
                    });

 p.find('.st-icon-value').trigger({
                        'type': 'stpb_icon_change',
                        'icon': icon_val
                    });

