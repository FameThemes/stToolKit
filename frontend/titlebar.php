<?php


function st_titlebar_style(){
    if(!current_theme_supports('st-titlebar')){
        return false;
    }

    $options = array(
        'img'=>'',
        'color'=>'',
        'position'=>'',
        'repeat'=>'',
        'attachment'=>'',
        'more_style' => ''
    );

    $theme_settings = $page_settings = array();

    // default options from theme settings
    if(function_exists('st_get_setting')){

        if(st_get_setting('titlebar_type')=='defined'){
            $list_titlebar_bg = apply_filters('st_titlebar_list_bg',array());
            $defined =  st_get_setting('titlebar_defined');
            $theme_settings = isset( $list_titlebar_bg[$defined]) ?  $list_titlebar_bg[$defined] : array();
        }else{
            foreach($options as $k => $v){
                $theme_settings[$k] =  st_get_setting('titlebar_bg_'.$k);
            }
        }
    }

    // if is post
    if(is_singular() || is_page()){
        global $post;
        $post_options =  ST_Page_Builder::get_page_options($post->ID);

        if($post_options['titlebar']=='custom'){
            foreach($options as $k => $v){
                $page_settings[$k] =  $post_options['titlebar_bg_'.$k];
            }
            // load bg image
            if($page_settings['img']!=''){
                $image_attributes = wp_get_attachment_image_src($page_settings['img'], 'full');
                $page_settings['img'] = $image_attributes[0];
            }


        }else if($post_options['titlebar']=='defined'){
            $list_titlebar_bg = apply_filters('st_titlebar_list_bg',array());
            $defined =  $post_options['titlebar_defined'];
            $page_settings = isset( $list_titlebar_bg[$defined]) ?  $list_titlebar_bg[$defined] : array();
        }

    }

    $style = false;
    if(!empty($page_settings)){
        $style = st_bg($page_settings);
        $more_style = apply_filters('st_titlebar_general_more',$style, $post->ID,  $page_settings );
    }elseif(!empty($theme_settings)){
        $style = st_bg($theme_settings);
        $more_style = apply_filters('st_titlebar_page_more',$style,$post->ID,  $post_options );
    }





    if($style){
    ?>
    <style type="text/css">
        .layout-title{ <?php echo $style; ?>}<?php echo $more_style!='' ?  $more_style : '';  ?>
    </style>
    <?php
    }
}

add_action('wp_head','st_titlebar_style',48);
