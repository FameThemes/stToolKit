<?php
if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

function st_theme_support($is_support, $args, $_wp_theme_features){
    $type = $args[0];
    return in_array( $type, $_wp_theme_features[0]);
}
add_filter('current_theme_supports-st-widgets', 'st_theme_support' , 60, 3);




if(!function_exists('st_pagination_get_paged')){
    /**
     * Pagination in home page
     */
    function st_pagination_get_paged(){
        if(get_option('permalink_structure')!=''){
            $uri = strtolower($_SERVER['REQUEST_URI']);
            $url_options = parse_url(home_url() );
            if(strpos($uri, $url_options['path'].'/page/') !==false){
                $uri = explode('/page/',$uri);
                if(isset($uri[1])){
                    $uri = explode('/',$uri[1]);
                    $_REQUEST['paged']=$uri[0];
                    $GLOBALS['st_paged']=$uri[0];
                }

            }
        }
    }
    add_action('init', 'st_pagination_get_paged', 99);
}



if (!function_exists('st_post_pagination')) {
    function st_post_pagination($pages = '', $range = 2, $echo = true) {
        $showitems = ($range * 2)+1;
        global $paged;
        if(empty($paged)) $paged = 1;

        if($pages == '')
        {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if(!$pages)
            {
                $pages = 1;
            }
        }

        $html ='';

        if(1 != $pages)
        {
            $html .= "<ul class='st-pagination pagination'>";
            if($paged > 2 && $paged > $range+1 && $showitems < $pages)
                $html .= "<li><a href='".get_pagenum_link(1)."'>&laquo;</a></li>";
            if($paged > 1 && $showitems < $pages)
                $html .= "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a></li>";

            for ($i=1; $i <= $pages; $i++)
            {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    $html .= ($paged == $i)? "<li  class=\"active\" ><a href=\"#\" class='page-current'>".$i."</a></li>" : "<li><a href='".get_pagenum_link($i)."'  >".$i."</a></li>";
                }
            }

            if ($paged < $pages && $showitems < $pages)
                $html .= "<li><a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a></li>";
            if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
                $html .= "<li><a href='".get_pagenum_link($pages)."'>&raquo;</a></li>";
            $html .= "</ul>\n";
        }

        if($echo){
            echo $html;
        }
        return $html;

    }
}


if(!function_exists('stpb_number_to_words')){
    function stpb_number_to_words($n, $level=0){
        switch($n){
            case 1:
                $class ='one';
                break;
            case 2:
                $class ='two';
                break;
            case 3:
                $class ='three';
                break;
            case 4:
                $class ='four';
                break;
            case 5:
                $class ='five';
                break;
            case 6:
                $class ='six';
                break;
            case 7:
                $class ='seven';
                break;
            case 8:
                $class ='eight';
                break;
            case 9:
                $class ='nine';
                break;
            case 10:
                $class ='ten';
                break;
            case 10:
                $class ='eleven';
                break;
            default :
                $class ='twelve';

        }

        $level = intval($level);

        return "col-lg-{$n} col-md-{$n} col-sm-{$n} ".$class;
    }
}


if(!function_exists('stpb_layout_column_class')){
    function  stpb_layout_column_class($width_string, $level=''){
        $class ="";
        $max_cols = 12;
        $w = explode('-',$width_string);
        $w[0] = intval($w[0]);
        $w[1] = intval($w[1]);
        if( $w[0] ==0 or  $w[1] == 0){
            $n = $max_cols;
        }else{
            $n=$max_cols*($w[0]/$w[1]); // 12 columns
        }

        $class =stpb_number_to_words($n,$level);

        $class = apply_filters('stpb_layout_column_class',$class,$n,$level);

        return $class;
    }
}


if(!function_exists('stpb_create_shortcode_attrs')){

    function stpb_create_shortcode_attrs($array){
        if(!is_array($array)){
            return (string) $array;
        }
        $attr = array();
        foreach($array as $k=> $v){
            if(is_array($v)){
                $attr[] = $k.'="'.esc_attr(join(',',$v)).'"';
            }else{
                $attr[] = $k.'="'.esc_attr($v).'"';
            }
        }

        return join(' ',$attr);
    }

}

if(!function_exists('st_hex2rgb')){
    /**
     * Conver Hex color to RGB
     * @param unknown $hex
     * @return multitype:number
     */
    function st_hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        $r=  $g=  $b= 255;
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

}

if(!function_exists('st_hex2rgba')){
    /**
     * Hex color to rgba
     * @param color $hex
     * @param number $alpha 0-1;
     * @return string;
     */
    function st_hex2rgba($hex, $alpha=1){
        $rgb =  hex2rgb($hex);
        $rgb[] = $alpha;
        return  join(', ',$rgb);
    }

}


if(!function_exists('st_hex2argb')){

    /**
     * Hex color to rgba
     * @param unknown $hex
     * @param number $alpha 0-1;
     * @return string;
     */
    function st_hex2argb($hex, $alpha=1){
        $hex = str_replace("#", "", $hex);
        $alpha = dechex($alpha*255);
        return $alpha.$hex;
    }

}


if(!function_exists('st_is_color')){

    function st_is_color($hex_color){
        return  (preg_match('/^#[a-f0-9]{6}$/i', $hex_color) || preg_match('/^[a-f0-9]{6}$/i', $hex_color));
    }

}


if(!function_exists('st_bg')){
/**
 * Create background style
 * @param array $args
 *      img
 *      color
 *      position : tl|tc|tr|cc|bl|bc|br
 *      repeat
 *      attachment
 * @return string style
 */
function st_bg($args){
    $args = wp_parse_args($args, array(
        'img'=>'',
        'color'=>'',
        'position'=>'',
        'repeat'=>'',
        'attachment'=>''
    ));

    $style  ='';

    extract($args);

    if(st_is_color($color)){
        if(strpos($color,'#')===false){
            $color ='#'.$color;
        }
        $style .= $color;
    }
    $options ='';

    if($img!=''){
        $style  .= ' url('.esc_url($img).') ';
        switch(strtolower($position)){
            case 'tl':
                $style.=' top left ';
                break;
            case 'tr':
                $style.=' top right ';
                break;

            case 'tc':
                $style.=' top center ';
                break;
            case 'cc':
                $style.=' center center';
                break;
            case 'bl':
                $style.=' bottom left ';
                break;
            case 'br':
                $style.=' bottom right ';
                break;
            case 'bc':
                $style.=' bottom center ';
                break;
            default:
                $style.=' top left ';
                break;
        }

        if($repeat!=''){
            $style .=' '.$repeat;
        }

        if($attachment!=''){
            if($attachment=='stretch'){
                $options ='
                        background-image: url('.esc_url($img).');
                        -webkit-background-size: cover;
                        -moz-background-size: cover;
                        -o-background-size: cover;
                        background-size: cover;
                        filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'.'.esc_url($img).'\', sizingMethod=\'scale\');
                        -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.esc_url($img).'\', sizingMethod=\'scale\')";
                      ';
                }else{
                $style .=' '.$attachment;
            }

        }

    }

    return ($style!='') ? "background: $style; ". str_replace("\n", '', $options)." " : '';

}

}


/*----------- -Utilities functions -------------*/


if (!function_exists('st_is_wpml')) {
    /**
     *  @true  if WPML installed.
     */
    function  st_is_wpml(){
        return function_exists('icl_get_languages');
    }
}


if (!function_exists('st_is_woocommerce')) {
    /**
     * @return true if Woocommerce installed and atvive
     *
     */
    function st_is_woocommerce(){
        return class_exists('Woocommerce');
    }

}




if (!function_exists('st_get_content_from_func')) {
    /**
     * Get content from  function
     * @param function name, param array
     * @return return string
     */
    function st_get_content_from_func() {
        $numargs = func_num_args();
        if ($numargs >= 1) {
            if(!function_exists(func_get_arg(0)) || !is_string(func_get_arg(0))){
                return false;
            }
            $param_arr = array();
            if ($numargs > 1) {
                $arg_list = func_get_args();
                for ($i = 1; $i < $numargs; $i++) {
                    $param_arr[] = $arg_list[$i];
                }
            }
            ob_start();
            $old_cont =  ob_get_contents();
            ob_end_clean();
            ob_start();
            call_user_func_array(func_get_arg(0), $param_arr);
            $content = ob_get_contents();
            ob_end_clean();
            echo $old_cont;
            return $content;
        }
        else {
            return false;
        }
    }
}


if (!function_exists('st_get_content_from_file')) {
    /**
     * Get content from  function
     * @param file
     * @return  string
     */
    function st_get_content_from_file($file='', $parameters=array()) {

        @extract($parameters, EXTR_SKIP);

        if(!is_file($file)){
            return false;
        }
        ob_start();
        $old_cont =  ob_get_contents();
        ob_end_clean();
        ob_start();
        include($file);
        $content = ob_get_contents();
        ob_end_clean();
        echo $old_cont;
        return $content;
    }
}




if (!function_exists('st_excerpt_length')) {
    /**
     * @param count word
     * @return function name
     */
    function st_excerpt_length( $count = 70 ) {
        $func = create_function('$length', 'return '.$count.';');
        add_filter('excerpt_length', $func,99999 );
        return $func;
    }

}


if(!function_exists('st_get_video')){
    function st_get_video($url,$ratio="16:9",&$return=array(),$attrs=''){
        $url_lower = strtolower($url);

        if(strpos($url_lower,'youtube')){
            preg_match('/[\\?\\&]v=([^\\?\\&]+)/',$url,$id);
            $return['type']='youtube';
            $return['video_id']=$id[1];
            if($id[1]==''){
                return '';
            }
            return '<iframe '.$attrs.' ratio="'.esc_attr($ratio).'" src="http://www.youtube.com/embed/'.$id[1].'?wmode=transparent"  frameborder="0"></iframe>';
        }else if(strpos($url_lower,'youtu.be') ){
            preg_match('/youtu.be\/([^\\?\\&]+)/', $url, $id);
            $return['type']='youtube';
            $return['video_id']=$id[1];
            if($id[1]==''){
                return '';
            }
            return '<iframe '.$attrs.' ratio="'.esc_attr($ratio).'"  src="http://www.youtube.com/embed/'.$id[1].'?wmode=transparent"   frameborder="0"></iframe>';

        }else if(strpos($url_lower,'vimeo.com') ){
            preg_match('/http:\/\/vimeo.com\/(\d+)$/', $url, $id);
            $return['type']='vimeo';
            $return['video_id']=$id[1];
            if($id[1]==''){
                return '';
            }
            return '<iframe '.$attrs.' ratio="'.esc_attr($ratio).'"  src="http://player.vimeo.com/video/'.$id[1].'?title=0&amp;byline=0&amp;portrait=0"  frameborder="0"></iframe>';
        }
        return '';
    }

}



if(!function_exists('st_create_link')){
    /**
     * Create Link
     * @param array $args
     /*
     * possible $args item
     *  type: custom | taxonomy | post_type
     *  item_type:  depend to type
     *  id, slug :  depend to type taxonomy, post_type
     *  url:  depend to custom
     *  @param string $return :  url | a | array (url , label)
     *
     *
     */
    function st_create_link($args= array(),  $return_type =  'url' ){


        if(empty($args)){
            return false;
        }

        $args = wp_parse_args($args, array(
            'type' =>'',
            'item_type' =>'',
            'id' =>'',
            'slug'=>'',
            'url'=>''
        ));



        extract($args);
        $return = false;

        switch(strtolower($type)){
            case 'custom':
                $return['url'] = $url;
                $return['label'] =$url;
            break;
            case 'post_type':
                $return['url'] = get_permalink($id);
                if($return_type!='url'){
                    $return['label'] = get_the_title($id);
                }else{
                    $return['label'] = $return['url'];
                }
            break;
            case 'taxonomy':
                $link  =  get_term_link($slug, $item_type);

                if(  is_wp_error( $link ) ){
                    $link ='';
                }

                $return['url'] = $link;

                if($return_type!='url'){
                    $term =  get_term( $id, $item_type );
                    $return['label'] = $term->name;
                }else{
                    $return['label'] = $return['url'];
                }
            break;
        }


       switch(strtolower($return_type)){
           case 'url':
               return $return['url'];
           break;
           case 'a':
               return '<a href="'.esc_url( $return['url']).'">'.esc_html($return['label']).'</a>';
           break;
       }

        return $return;

    }
}


if(!function_exists('st_get_shop_page')){
    /**
     * Get WC shop page id
     * @return page id
     */
    function st_get_shop_page(){
        $post_id  = get_option('woocommerce_shop_page_id');
        if(st_is_wpml()){
            $post_id=   icl_object_id($post_id, 'page', true);
        }

        $post_id = intval($post_id);
        if($post_id<=0){
            $post_id =-999999;
        }

        return $post_id;
    }
}



function st_effect_attr($effect){
    $return = array('class'=>'','attr' =>'' );

    if(empty($effect) || $effect == 'no-effect'){
        $return['class'] = 'no-effect';
    }else{
        $return['class'] = 'animation-effect';
        $return['attr'] = ' effect="'.esc_attr($effect).'" ';
    }

    return $return;
}



if(!function_exists('st_set_mail_html_content_type')){
    function st_set_mail_html_content_type() {
        return 'text/html';
    }

}


if (!function_exists('st_contact_form')) {
    /**
     * Ajax proccess Contact Form 
     */
    add_action('wp_ajax_st_contact_form', 'st_contact_form');
    add_action('wp_ajax_nopriv_st_contact_form', 'st_contact_form');
    function st_contact_form() {
        if (!session_id()) {
            session_start();    
        }
        $_POST['data'] = str_replace('\"', '"', $_POST['data']);
        $data = json_decode($_POST['data']);
        $form_email_subject = base64_decode($_POST['form_email_subject']);
        $form_email_from_name = base64_decode($_POST['form_email_from_name']);
        $form_email_from = base64_decode($_POST['form_email_from']);
        $form_email_to = base64_decode($_POST['form_email_to']);
        $form_email_body = base64_decode($_POST['form_email_body']);
        $attr_keys = $attr_values = $check_key = $tmpv = array();
        $captcha = $captcha_sys = $body = '';
        if (isset($data) && is_array($data)) {
            foreach($data as $item) {
                if ($item->name == $form_email_from) {
                    $form_email_from = $item->value;
                }
                // check captcha
                if ($item->type == 'captcha') {
                    $captcha = $item->value;
                    $captcha_sys = $_SESSION[$item->name];
                }
                if (!in_array($item->name, $check_key)) {
                    array_push($check_key, $item->name);
                    $tmpv = array();
                    $tmpv[] = $item->value;
                } else {
                    $tmpv[] = $item->value;
                }
                $attr_keys[$item->name] = '['. $item->name .']';
                $attr_values[$item->name] = implode(',', $tmpv);
                // create message body if dont have template Email
                $label = (isset($item->label)) ? '<b>'. $item->label .' : </b>' : '';
                $value = (implode(',', $tmpv)) ? implode(',', $tmpv) .'<br/>' : '';
                $body .= $label . $value;
            }
            if ($captcha == $captcha_sys) {
                if (trim($form_email_body) != '') $body = str_replace($attr_keys, $attr_values, $form_email_body);
                $body = wpautop($body);
                $headers = array();
                if($form_email_from!=''){
                    if($form_email_from_name==''){
                        $form_email_from_name = $form_email_from;
                    }
                    $headers[] = sprintf(__('From: %1$s <%2$s>'), $form_email_from_name, $form_email_from);
                }

                add_filter( 'wp_mail_content_type', 'st_set_mail_html_content_type' );
                $check = wp_mail($form_email_to, $form_email_subject, $body, $headers);
                remove_filter( 'wp_mail_content_type', 'st_set_mail_html_content_type' );
                if ($check) {
                    echo '{"type":"alert-success","message":"'. esc_attr($_POST['mss_noti_success']) .'"}';
                } else {
                    echo '{"type":"alert-danger","message":"'. esc_attr($_POST['mss_noti_dont_send']) .'"}';
                }
            } else {
                echo '{"type":"alert-danger","message":"'. esc_attr($_POST['mss_noti_captcha']) .'"}';
            } 
        }
        die();
    }
}
