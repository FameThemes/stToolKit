<?php
/**
 * User: truongsa
 * Date: 7/25/13
 * Time: 8:04 AM
 */

function st_page_layout_config(){
    $layouts =  array(
        'default'=>__('Default Layout - Set in Theme Options','smooththemes'),
        'no-sidebar'=>__('No Sidebar','smooththemes'),
        'right-sidebar'=>__('Right Sidebar','smooththemes'),
        'left-sidebar'=>__('Left Sidebar','smooththemes'),
        'left-right-sidebar'=>__('Left and Right Sidebar','smooththemes')
    );
    return  apply_filters('st_page_layout_config',$layouts);
}

add_filter('st_table_row_fields', 'st_config_tb_row_fields', 10, 2);

function st_config_tb_row_fields($feilds, $type) {
    if ($type == 'price') {
        unset($feilds['highlight']);
        $feilds['pricing'] = __('Pricing Row','smooththemes');
        $feilds['button'] = __('Button Row','smooththemes');
    }
    return $feilds;
}

add_filter('st_table_column_fields', 'st_config_tb_col_fields', 10, 2);

function st_config_tb_col_fields($feilds, $type) {
    if ($type == 'price') {
        unset($feilds['highlight']);
        unset($feilds['center']);
        $feilds['txt-left'] = __('Left Column','smooththemes');
        $feilds['highlight'] = __('Highlight Column','smooththemes');
        //$feilds['desc'] = __('Description Column','smooththemes');
    }
    return $feilds;
}

if(!function_exists('ST_Page_Builder_Items_Config')){

    function ST_Page_Builder_Items_Config()
    {
        $items = array(
            'stpb_widget' => array(
                'title'         => __('Sidebar','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_widget.png",
                'tooltip'       =>__('Add a widget sidebar','smooththemes'),
                'generate_func' => 'stpb_generate_widget'
            ),

            'stpb_text' => array(
                'title'         => __('Text','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_text.png",
                'tooltip'       =>__('Add a custom text block','smooththemes'),
                'generate_func' => 'stpb_generate_text',
                'shortcode'     => false,
                'preview'       =>true // preview in page builder
            ),
            'stpb_heading' => array(
                'title'         => __('Heading','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_heading.png",
                'generate_func' => 'stpb_generate_heading',
                'tooltip'       =>__('Add a Heading','smooththemes'),
                'preview'       =>true
            ),
            'stpb_tabs' => array(
                'title' => __('Tabs','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_tab.png",
                'tooltip'=>__('Add Tabs Block','smooththemes'),
                'generate_func' => 'stpb_generate_tabs'
            ),
            'stpb_toggle' => array(
                'title' => __('Toggle','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_accordion.png",
                'tooltip'=>__('Add Toggle block','smooththemes'),
                'generate_func' => 'stpb_generate_toggle'
            ),
            'stpb_accordion' => array(
                'title'         => __('Accordion','smooththemes'),
                'tooltip'       =>__('Add Accordion block','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_accordion.png",
                'generate_func' => 'stpb_generate_accordion'
            ),

            'stpb_testimonials' => array(
                'title'         => __('Testimonials','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_testimonial.png",
                'tooltip'       =>__('Add Testimonials block','smooththemes'),
                'generate_func' => 'stpb_generate_testimonials'
            ),

            'stpb_notification' => array(
                'title'         =>__('Notification','smooththemes') ,
                'tooltip'       =>__('Add a Notification block','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_notification.png",
                'generate_func' => 'stpb_generate_notification',
                'preview'       =>true
            ),

            'stpb_divider' => array(
                'title' => __('Divider','smooththemes'),
                'tooltip'=>__('Add Divider','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_divider.png",
                'generate_func' => 'stpb_generate_divider'
            ),


            'stpb_clients' => array(
                'title' => __('Clients','smooththemes') ,
                'tooltip'=>__('Add Clients block','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_client.png",
                'generate_func' => 'stpb_generate_clients'
            ),


           'stpb_team_member' => array(
                'title' => __('Team Member','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_client.png",
                'tooltip'=>__('Team member','smooththemes'),
                'tab'=>'content', // tab display
                'generate_func' => 'stpb_generate_team_member'
            ),

            'stpb_icon_list' => array(
                'title' =>__('Custom List','smooththemes'),
                'tooltip'=>__('Add Custom List','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_list.png",
                'generate_func' => 'stpb_generate_icon_list'
            ),

            'stpb_button' => array(
                'title' => __('Button','smooththemes'),
                'tooltip'=>__('Add a Button','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_button.png",
                'generate_func' => 'stpb_generate_button',
                'preview'=>true
            ),
            
            'stpb_table' => array(
                'title' => __('Table Data','smooththemes'),
                'tooltip'=>__('Add a Table','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_table.png",
                'generate_func' => 'stpb_generate_table',
                'shortcode'=> true
            ),
            
            'stpb_table_price' => array(
                'title' => __('Pricing Box','smooththemes'),
                'tooltip'=>__('Add a Pricing Box','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_table.png",
                'generate_func' => 'stpb_generate_table_price',
                'shortcode'=> true
            ),

            'stpb_iconbox' => array(
                'title' => __('Icon Box','smooththemes'),
                'tooltip'=>__('Add a Icon box','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_iconbox.png",
                'generate_func' => 'stpb_generate_iconbox',
                'preview'=>true
            ),

            'stpb_gallery' => array(
                'title' =>__('Simple Gallery','smooththemes') ,
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_simple_gallery.png",
                'tab'=>'media',
                'tooltip'=>__('Add Simple Gallery block','smooththemes'),
                'generate_func' => 'stpb_generate_gallery',
                'preview'=>true
            ),

            'stpb_image' => array(
                'title' => __('Image','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_image.png",
                'tab'=>'media',
                'tooltip'=>__('Add a Image','smooththemes'),
                'generate_func' => 'stpb_generate_image',
                'preview'=>true
            ),

            'stpb_video' => array(
                'title' =>__('Video','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_video.png",
                'tab'=>'media',
                'tooltip'=>__('Add a Video','smooththemes'),
                'generate_func' => 'stpb_generate_video',
                'preview'=>true
            ),

            'stpb_slider' => array(
                'title' => __('Slider','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_carousel.png",
                'tab'=>'media',
                'tooltip'=>__('Add a Slider','smooththemes'),
                'generate_func' => 'stpb_generate_slider',
                'preview'=>false
            ),

            /*
            'stpb_carousel' => array(
                'title' => __('Carousel','smooththemes'),
                'icon'=>ST_PAGEBUILDER_URL."assets/images/builder_carousel.png",
                'tab'=>'media',
                'tooltip'=>__('Add a Carousel Slider','smooththemes'),
                'generate_func' => 'stpb_generate_carousel',
                'preview'=>false
            ),
            */

            'stpb_blog' => array(
                'title'         =>  __('Blog','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_blog.png",
                'tooltip'       =>__('Add Blog Item','smooththemes'),
                'tab'           =>'post',
                'generate_func' => 'stpb_generate_blog'
            ),

            'stpb_map' => array(
                'title'         =>  __('Map','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_map.png",
                'tooltip'       =>__('Add Blog Item','smooththemes'),
                'generate_func' => 'stpb_generate_map'
            ),


            'stpb_login' => array(
                'title'         =>  __('Login','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_user_login.png",
                'tooltip'       =>__('Add a Login form','smooththemes'),
                'generate_func' => 'stpb_generate_login'
            ),
            'stpb_register' => array(
                'title'         =>  __('Register','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_user_login.png",
                'tooltip'       =>__('Add a Register form','smooththemes'),
                'generate_func' => 'stpb_generate_register'
            ),
            'stpb_profile' => array(
                'title'         =>  __('Profile','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_profile.png",
                'tooltip'       =>__('Add a Profile form','smooththemes'),
                'generate_func' => 'stpb_generate_profile'
            ),
            'stpb_lost_password' => array(
                'title'         =>  __('Lost Password','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_user_login.png",
                'tooltip'       =>__('Add a Lost Password form','smooththemes'),
                'generate_func' => 'stpb_generate_lost_password'
            ),

            'stpb_contact_form' => array(
                'title'         =>  __('Contact From','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_user_login.png",
                'tooltip'       =>__('Add a Contact form','smooththemes'),
                'generate_func' => 'stpb_generate_contact_form'
            ),

            'stpb_chart' => array(
                'title'         =>  __('Chart','smooththemes'),
                'icon'          =>"",
                'tooltip'       =>__('Add a percentage chart','smooththemes'),
                'generate_func' => 'stpb_generate_chart'
            ),

            'stpb_progress_bars' => array(
                'title'         =>  __('Progress bars','smooththemes'),
                'icon'          =>"",
                'tooltip'       =>__('Add progress bars','smooththemes'),
                'generate_func' => 'stpb_generate_progress_bars'
            ),

            'stpb_count_to' => array(
                'title'         =>  __('CountTo','smooththemes'),
                'icon'          =>"",
                'tooltip'       =>__('Add a CountTo box','smooththemes'),
                'generate_func' => 'stpb_generate_count_to'
            )

        );


        if(st_is_woocommerce()){
            $items['stpb_wc_products'] = array(
                'title'         =>  __('WC Products','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_woocommerce.png",
                'tooltip'       =>__('WooCommerce Products','smooththemes'),
                'tab'           =>'post',
                'generate_func' => 'stpb_generate_wc_products'
            );
        }

        // if contact form 7 actived
        if(is_plugin_active('contact-form-7/wp-contact-form-7.php')){
            //$icon = plugins_url().'/contact-form-7/admin/images/screen-icon.png';
            $items['stpb_contact_from_7'] = array(
                'title'         =>  __('Contact Form 7','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_contactform7.png",
                'tab'           =>'post',
                'tooltip'       =>__('Add a Contact From 7','smooththemes'),
                'generate_func' => 'stpb_generate_contact_from_7'
            );
        }

        // if LayerSlider actived
        if(is_plugin_active('LayerSlider/layerslider.php')){
            //$icon = plugins_url().'/LayerSlider/img/icon_16x16.png';
            $items['stpb_LayerSlider'] = array(
                'title' =>  __('LayerSlider','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_layerslider.png",
                'tab'=>'media',
                'tooltip'=>__('Add a LayerSlider','smooththemes'),
                'generate_func' => 'stpb_generate_LayerSlider'
            );
        }

        // if revslider. actived
        if(is_plugin_active('revslider/revslider.php')){
            //$icon = plugins_url().'/LayerSlider/img/icon_16x16.png';
            $items['stpb_revslider'] = array(
                'title' =>  __('Revolution','smooththemes'),
                'icon'          =>ST_PAGEBUILDER_URL."assets/images/builder_carousel.png",
                'tab'=>'media',
                'tooltip'=>__('Add a Revolution Slider','smooththemes'),
                'generate_func' => 'stpb_generate_revslider'
            );
        }


        return  apply_filters('stpb_list_items',$items);

    }


}// end  check class ST_Page_Builder_Items_Config
