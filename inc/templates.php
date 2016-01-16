<?php
if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

if (!function_exists('st_get_template')) {
    /**
     * @param $template_file
     * @return PATH to file in folder template
     */
    function st_get_template($template_file='') {
        $theme_dir = get_template_directory().'/';
        if(is_file($theme_dir.$template_file)  && file_exists($theme_dir.$template_file)){
            return $theme_dir.$template_file;
        }else{
            return ST_PAGEBUILDER_PATH.'/templates/'.$template_file;
        }

    }

}

/**
 * Check maybe this post use template page builder
 * @param $post_id
 * @return bool
 *
 */
function stpb_check_template($post_id){
    $check = false;
    $dont_check = false;
    $type=get_post_type( $post_id );

    if($type =='page'){
        $editor = get_post_meta($post_id, '_st_current_editor', true);
        if(empty($editor) || $editor==''){
            $editor = 'editor';
        }
        if($editor!='editor'){
            $check = true;
        }
        //$dont_check = true;
    }

    if($type=="post"){
        $check = true ;
    }elseif(!$check && !$dont_check){
        $page_options = ST_Page_Builder::get_page_options($post_id);
        if(isset($page_options['layout']) && $page_options['layout'] !='' &&  $page_options['layout'] !='default' ){
            $check = true ;
        }
    }

    $check = apply_filters('stpb_check_template',$check, $post_id, get_post_type( $post_id ) );
    return $check;
}


//Template fallback
add_action("template_include", 'stpb_template_include');
function stpb_template_include($template='') {

    if(!is_page_template()){
        if(  is_page() || is_singular() ||  is_single() ){

            global $post;
            if(stpb_check_template($post->ID)){
                $template = st_get_template('template-builder.php');
            }
        }
    }

    return $template;
}

/**
 * Display builder content
 * @see ST_Page_Builder::the_content($post_id);
 * @param $post_id
 * @return bool
 */
function st_the_builder_content($post_id){
    return (ST_Page_Builder::the_content($post_id));
}


if (!function_exists('st_post_thumbnail')) {
    /**
     * @param $post_id, $size = thumbnail | medium | large ..., $args = array('columns'=>, 'thumb_type'='gallery | slider', 'size')
     * @return show image featured | gallery | slider | video
     */
    function st_post_thumbnail($post_id=0, $args=array(), $echo=true) {

        if(intval($post_id)<=0){
         global $post;
            $post_id =  $post->ID;
        }

        if(!isset($args['force_video_size'])){
            $args['force_video_size'] = true;
        }

        $args = wp_parse_args($args , array('size'=>'','force_video_size'=>'') );

        $size =$args['size'];
        if($size=='' ||  empty($size)){
            $size  = apply_filters('st_post_thumbnail_size','thumbnail');
        }

        $page_options = st_get_post_options($post_id);
        if(!isset($page_options['thumb_type'])){
            $page_options['thumb_type'] ='';
        }

        $args['columns'] = (isset($args['columns']) && (int)$args['columns'] > 0) ? $args['columns'] : 4;
        $args['thumb_type'] = isset($args['thumb_type']) ? $args['thumb_type'] : $page_options['thumb_type'];
        $out = '';
        switch ($args['thumb_type']){
            case 'slider':

                if (isset($page_options['gallery']) && $page_options['gallery'] != '') {
                    $out .= do_shortcode(
                        apply_filters(
                                'st_post_thumbnail_slider',
                                '[st_simple_slider images="'. $page_options['gallery'] .'" size="'. $size .'"]',
                                $page_options['gallery'],
                                $size  ,
                                $args['columns']
                            )
                    );
                }
                break;

            case 'gallery':
                if (isset($page_options['gallery']) && $page_options['gallery'] != '') {
                    $out .= do_shortcode(
                                        apply_filters(
                                                    'st_post_thumbnail_gallery',
                                                   '[st_gallery gallery="'. $page_options['gallery'] .'" size="'. $size .'" columns="'. $args['columns'] .'" lightbox="no"]'

                                                )

                                 );
                }
                break;

            case 'video':

                if (isset($page_options['video']) && $page_options['video'] != '') {
                    global $_wp_additional_image_sizes;
                    if(isset($_wp_additional_image_sizes[$size])  && ($args['force_video_size']=== true ||  $args['force_video_size'] == 'true') ){
                        $out .= do_shortcode('[st_video video="'. $page_options['video'] .'"  ratio="'.$_wp_additional_image_sizes[$size]['width'].':'.$_wp_additional_image_sizes[$size]['height'].'"   ]');
                    }else{
                        $out .= do_shortcode('[st_video video="'. $page_options['video'] .'" ratio="16:9" ]');
                    }

                }

                break;

            default :
                if (has_post_thumbnail($post_id)) {
                    $out .= do_shortcode('[st_image image="'. get_post_thumbnail_id($post_id) .'" size="'. $size .'" position="center" link="" link_target="_self"]');
                }

        }

        if ($echo == true) echo $out;
        else return $out;
    }
}


if(!function_exists('st_search_posts') ){

    // custom search content
    function st_search_posts(&$post){
        if(!is_search()){
            return $post;
        }

        $editor = get_post_meta($post->ID, '_st_current_editor', true);
        if($editor=='builder'){
            if($post->post_excerpt==''){
                $excerpt_length = apply_filters('excerpt_length',30);

                if(class_exists('ST_Page_Builder')){
                    $content=  ST_Page_Builder::get_content($post->ID);
                }else{
                    $content =  get_post_meta($post->ID,'_st_pagebuilder_content', true);
                }

                $post->post_content = $content;
                $post->post_excerpt =  wp_trim_words(strip_shortcodes($content), $excerpt_length,'') ;
            }
        }

        return $post;
    }

    add_action('the_post','st_search_posts',30,1);
}

if(!function_exists('st_post_class')){
    function st_post_class($classes,$class, $post_id =0){
        if(is_singular() || is_page() || is_single()){
            if($post_id<=0){
                global $post;
                $post_id = $post->ID;
            }

            $editor = get_post_meta($post_id, '_st_current_editor', true);
            if($editor==''){
                $editor ='editor';
            }

        }else{
            $editor ='editor no-single';
        }

        $editor ='use-'.$editor;

        array_unshift($classes,$editor);
        return  $classes;
    }

    add_filter('post_class','st_post_class',3, 3);
    add_filter('body_class','st_post_class',3, 2);
}





