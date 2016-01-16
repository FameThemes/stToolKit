<?php
if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

/**
 * Images column for posr type
 */


function st_manage_post_type_columns($column_name, $id) {
    global $wpdb;
    $size ='thumbnail';
    switch ($column_name) {

        case 'images':
            $html ='';
            // echo $id;
            $options = ST_Page_Builder::get_page_options($id);
            //  echo get_the_post_thumbnail($id,array(40,40));

            switch(strtolower($options['thumb_type'])){
                case 'video':

                    $html ='<span class="video-thumb" style="  video="'.$data['type'].'" size='.$size.' video-id="'.$data['video_id'].'"><i class="iconentypo-video"></i></span>';

                 break;
                case 'gallery':  case 'slider':

                    $image_ids =  $options['gallery'];
                    $image_ids = explode(',',$image_ids);
                    if(count($image_ids)){
                        foreach($image_ids as $img_id){
                            $thumb_image_url = wp_get_attachment_image_src( $img_id , $size);
                            $html .='  <img  alt="" src="'.$thumb_image_url[0].'" >';
                        }

                    }

                    break;
                default;

                    if ( has_post_thumbnail($id) ) {
                        $thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($id), $size);
                        $html ='  <img alt="" src="'.$thumb_image_url[0].'" >';

                    }else{

                    }
            }

            echo apply_filters('st_post_type_thumb_col',$html, $id , $options);

            break;
        default:

        break;
    } // end switch
}



function add_new_st_post_type_columns($columns) {

    $new_cols = array();
    $i=1;
    $insert_index = 3;
    foreach($columns as $k => $col){
        if($i==$insert_index){
            $new_cols['images'] = __('Thumbnail','smooththemes');
        }
        $new_cols[$k] = $col;
        $i++;
    }

    return $new_cols;
}
// Add to admin_init function



function st_show_post_type_thumb_support(){
    foreach( apply_filters('st_show_post_type_thumb_col', array('post', 'portfolio') ) as $k=> $v ){
        add_action('manage_'.$v.'_posts_custom_column', 'st_manage_post_type_columns', 10, 2);
        add_filter('manage_edit-'.$v.'_columns', 'add_new_st_post_type_columns',10);
    }

}

add_action('init', 'st_show_post_type_thumb_support');


function st_add_list_posts_style(){
    wp_enqueue_style('st-list-posts',ST_PAGEBUILDER_URL."assets/css/list-posts.css");
}

add_action('admin_print_styles-post.php','st_add_list_posts_style');
add_action('admin_print_styles-edit.php','st_add_list_posts_style');

