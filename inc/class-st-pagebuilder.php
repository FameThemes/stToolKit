<?php
if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

if(!function_exists('wp_create_nonce')){
    require_once (ABSPATH.'/wp-includes/pluggable.php');
}

class ST_Page_Builder{
    const VERSION = '1.0';

    /**
     * Settings meta key for pagebuilder
     * @see table wp_postmeta
     */
    // post meta name variables
    const BUILDER_SETTINGS_NAME = '_st_pagebuilder_settings';
    // page builder content meta name
    const CONTENT_NAME ='_st_pagebuilder_content';
    // for other settings out of items settings
    const  META_NAME ='_st_pagebuilder_meta';
    const  PAGE_OPTIONS_NAME = '_st_page_options';

    /**
     * Settings for item sizes
     * @return array
     */
    function get_builder_item_sizes(){
        return array('1-1','3-4','2-3','1-2','1-3','1-4');
    }

    function class_to_items_size(){
        $class_to_items_size = array();
        foreach(self::get_builder_item_sizes() as $k => $v){
            $class_to_items_size[$v] =  $k;
        }

        return  $class_to_items_size;
    }

    function __construct($settings= array()){
        if(!empty($settings)){
            $this->settings = $settings;
        }else{
            $this->settings['url'] = ST_PAGEBUILDER_URL;
            $this->settings['path'] = ST_PAGEBUILDER_PATH;
        }
    }

    /**
     * Save builder settings
     * @param $post_id
     * @param $data
     * @return bool
     */
    function save_builder_settings($post_id, $data){

        $gc = new ST_Page_Builder_Generate_content($data, $post_id);
        $content = $gc->get_content();
        self::save_builder_content($post_id, $content);

        $data = maybe_serialize($data);
        $data = base64_encode($data);

        update_post_meta($post_id,self::BUILDER_SETTINGS_NAME,$data );

        $cache_key = $post_id.'_'.self::BUILDER_SETTINGS_NAME;
        do_action('st_save_builder_settings',$post_id, $data, self::BUILDER_SETTINGS_NAME);
        wp_cache_delete($cache_key);
    }

    /**
     *
     * @param $post_id
     * @param $data
     */
    function  save_page_options($post_id, $data){
        $data = maybe_serialize($data);
        $data = base64_encode($data);
        update_post_meta($post_id,self::PAGE_OPTIONS_NAME,$data );
        $cache_key = $post_id.'_'.self::PAGE_OPTIONS_NAME;
        do_action('st_save_page_options',$post_id, $data, self::PAGE_OPTIONS_NAME);
        wp_cache_delete($cache_key);
    }

    /**
     * @param $post_id
     * @param $data
     * @return bool
     */
    function save_builder_content($post_id,$content){
        return update_post_meta($post_id,self::CONTENT_NAME, $content );
    }

    /**
     * Get page builder items settings
     * @return mixed
     */
    public static function get_builder_settings($post_id, $default = array(), $cache = true){
        $cache_key = $post_id.'_'.self::BUILDER_SETTINGS_NAME;
        $data = wp_cache_get($cache_key);

        if( !$cache || (!isset($data) || empty($data) )){
            $data = get_post_meta($post_id,self::BUILDER_SETTINGS_NAME, true);
            $data = base64_decode($data);
            $data = maybe_unserialize($data);
            $data= stripslashes_deep($data);
            $data = wp_parse_args($data,$default);
            wp_cache_set($cache_key, $data);
        }

        return $data;
    }

    public  static  function get_page_options($post_id, $default = array(), $cache = true){

        $cache_key = $post_id.'_'.self::PAGE_OPTIONS_NAME;

        $data = wp_cache_get($cache_key);

        if( !$cache || (!isset($data) || empty($data) )){

            $data = get_post_meta($post_id,self::PAGE_OPTIONS_NAME, true);
            $data = base64_decode($data);
            $data = maybe_unserialize($data);
            $data= stripslashes_deep($data);
            $data = wp_parse_args($data,$default);

            $data['st_default'] = false;

            if(!is_admin()){

                $layout = false;
                // if is st-frame-work
                if(function_exists('st_get_setting')){
                    $layout =  st_get_setting('layout');
                    if($layout==''){
                        $layout = 'right-sidebar';
                    }
                }

                if($layout && strtolower($data['layout'])=='default'){
                    $data['layout']= $layout;
                    $data['left_sidebar']= st_get_setting('left_sidebar','sidebar_default');
                    $data['right_sidebar']= st_get_setting('right_sidebar','sidebar_default');
                    $data['st_default'] = true;
                }

            }

            wp_cache_set($cache_key, $data);
        }


        return $data;

    }

    /**
     * Get page builder content of page
     * @return string
     */
    public static function  get_content($post_id){
        return   get_post_meta($post_id,self::CONTENT_NAME,true);
    }

    /**
     * Display builder content
     * * @return bool if has page builder conmtent
     */
    public static function  the_content($post_id){
        $editor = get_post_meta($post_id, '_st_current_editor', true);
        if ( post_password_required() ) {
            return false;
        }
        if(strtolower($editor)=='builder'){
            $content = self::get_content($post_id);
            if($content!=''){
                echo do_shortcode($content);
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    /**
     * Get page builder metas of page
     * @return mixed
     */
    public static function get_meta($post_id, $default = array()){
        $data = get_post_meta($post_id,self::META_NAME,true);
        $data = wp_parse_args($data,$default);
        return $data;

    }


    function generate_build_content($builder_data){
        $builder_content = '';
        return $builder_content;
    }

}



class ST_Page_Builder_Generate_content{
    public  $content;
    public $builder_data;
    public $builder_items;
    public $item_sizes;
    public $class_to_items_size;
    public $id;
    public $include_items;

    function __construct($builder_data, $post_id=0){
        $this->id=  $post_id;
        $this->include_items =  array();
        $this->builder_data = $builder_data;
        $this->builder_items = ST_Page_Builder_Items_Config();
        $this->items_sizes = ST_Page_Builder::get_builder_item_sizes();
        $this->class_to_items_size = ST_Page_Builder::class_to_items_size();

        // settings for colum
        add_filter('stpb_column_generate_settings_before',array($this,'column_settings_before'),10,2);
        add_filter('stpb_column_generate_settings_after',array($this,'column_settings_after'),10,2);

        // settings for row
        add_filter('stpb_row_generate_settings_before',array($this,'column_settings_before'),10,2);
        add_filter('stpb_row_generate_settings_after',array($this,'column_settings_after'),10,2);

    }

    /**
     * @param $width_string  e.g: 1-2, 1-3, 3-4
     */
    function divider($width_string=''){

        $width_string = explode('-',$width_string);

        if(count($width_string)<2 || empty($width_string)){
            return  1;
        }

        $width_string[0] = intval($width_string[0]);
        $width_string[1] = intval($width_string[1]);

        if($width_string[1]!=0){
            return  $width_string[0]/$width_string[1];
        }

        return  1;
    }

    function get_col_width($col_data){
        $width = '1-1';
        if( isset($col_data['type']) && strtolower($col_data['type'])=='column' ){

            $width = str_replace('width-','',$col_data['width_class']);
            if($width==''){
                $width ='1-1';
            }

            if(!is_numeric($col_data['width_id']) || strpos($col_data['width_id'],'[')===true){
                $col_data['width_id'] = $this->class_to_items_size[$width];
            }

            if(!in_array($width, $this->items_sizes)){
                $width = $this->items_sizes[$col_data['width_id']];
            }
        }

        return $width;
    }

    function get_content(){
        if(is_string($this->builder_data)){
            return  $this->builder_data;
        }else{
            return  $this->group_columns_to_row($this->builder_data);
        }
    }

    /**
     * Settings  before column start
     * @param $col_data
     * @return string
     */
    function column_settings_before($default='',$data= array()){
        $col_data = $data['settings'];

        if(!is_array($col_data) || empty($col_data)){
            return '';
        }

        $col_data =  wp_parse_args($col_data,array(
            '_classes'=>'',
            'mod'=>'boxed',
            'inside_mod'=>'boxed',
            'custom_class'=>'',
            'custom_id'=>'',
            'padding'=>'',
            'bg_img'=>'',
            'bg_color'=>'',
            'bg_attachment'=>'',
            'bg_repeat'=>'',
            'margin_bottom'=>'',
            'margin_top' =>'',
            'padding_bottom'=>'',
            'padding_top'   =>'',
            'is_parallax'=>'n',
            'opacity' =>'',
            'effect' =>'',
            'vertical_align' =>'',
            'border' =>''
        ));

        $attrs = array();
        $classes = array();

        $classes[] = trim($col_data['_classes']);

        $style='';
        $container_open ='';

        if($col_data['is_parallax']=='y' && $col_data['bg_img']!=''){
            $attrs[] =' data-bg="'.esc_attr($col_data['bg_img']).'" ';
            $attrs[] =' data-speed="0.4" ';

            if(is_numeric($col_data['opacity'])){
                $attrs[] =' data-opacity="'.esc_attr($col_data['opacity']).'" ';
            }

            $classes[] = 'parallax';

        }else{

        }

        $bg_style = st_bg( array(
            'img'=>$col_data['bg_img'],
            'color'=>$col_data['bg_color'],
            'attachment'=>$col_data['bg_attachment'],
            'repeat'=>$col_data['bg_repeat'],
            'position'=>$col_data['bg_position']
        ));


        $is_full_w = false;
        $fwpd='';

        if($col_data['padding'] =='custom'){
            if(is_numeric($col_data['padding_left'])){
                $fwpd .= ' padding-left: '.esc_attr(trim($col_data['padding_left'])).'px; ';
            }

            if(is_numeric($col_data['padding_right'])){
                $fwpd.= ' padding-right: '.esc_attr(trim($col_data['padding_right'])).'px; ';
            }
        }


        if(strtolower($data['type'])=='row'){
            $row_wrapper_class =  (isset( $col_data['mod']) &&  $col_data['mod']!='') ?  $col_data['mod'].='' : 'boxed';

            if($row_wrapper_class=='full-width'){
                $is_full_w= true;
                if($fwpd!=''){
                    $fwpd = ' style="'.$fwpd.'" ';
                }
                if($col_data['inside_mod']=='full-width'){
                    $container_open =' <div class="rc-inside rc-full-with"'.$fwpd.'>  ';
                }else{
                    $container_open =' <div class="rc-inside rc-boxed container"'.$fwpd.'>  ';
                }

                $fwpd='';
            }

            $row_wrapper_class .= '-mod';
            $row_wrapper_class .=' settings-'.$data['type'];

        }else{
            $row_wrapper_class .=' settings-col';
            $reffect= st_effect_attr($col_data['effect']);

            $classes[] = $reffect['class'];
            $attrs[] =  $reffect['attr'];

            if($col_data['vertical_align']!=''  && $col_data['vertical_align'] !='top'){
                $classes[] = 'col-va va-'.esc_attr($col_data['vertical_align']);
            }

        }

        if(is_numeric($col_data['margin_bottom'])){
            $style.= ' margin-bottom: '.esc_attr(trim($col_data['margin_bottom'])).'px; ';
        }

        if(is_numeric($col_data['margin_top'])){
            $style.= ' margin-top: '.esc_attr(trim($col_data['margin_top'])).'px; ';
        }

        if($col_data['padding'] =='custom'){

            if(is_numeric($col_data['padding_bottom'])){
                $style.= ' padding-bottom: '.esc_attr(trim($col_data['padding_bottom'])).'px; ';
            }

            if(is_numeric($col_data['padding_top'])){
                $style.= ' padding-top: '.esc_attr(trim($col_data['padding_top'])).'px; ';
            }

            if(!$is_full_w && $fwpd!=''){
                $style.=$fwpd;
            }

        }

        if($style!=''|| $bg_style!='' ){
            $style = ' style="'.$style.$bg_style.'" ';
        }


        if(!empty($attrs) ||  $style!=''){
            $row_wrapper_class.=' has-custom alt-bg';
        }else{
            $row_wrapper_class.=' no-custom';
        }


        if($col_data['custom_id']!=''){
            $attrs[] = ' id="'.esc_attr($col_data['custom_id']).'" ';
        }

        $padding = $col_data['padding'];

        $classes[] =  'custom-settings';
        $classes[] =  $padding;
        $classes[] =  $row_wrapper_class;

        if($col_data['custom_class']!=''){
            $classes[] =  esc_attr($col_data['custom_class']);
        }

        if($col_data['border']!=''){
            $classes[] = esc_attr($col_data['border']);
        }

        $attrs[] = ' class="'.esc_attr(join(' ', $classes)).'" ';

        return  '<div '.$style.join(' ',$attrs).'>'.$container_open;


    }

    /** Settings  after column end
     * @param $col_data
     * @return string
     */
    function column_settings_after($default='',$data= array()){
        $col_data = $data['settings'];
        if(!is_array($col_data) || empty($col_data)){
            return '';
        }

        $container_close ='';
        if(strtolower($data['type'])=='row'){
            $row_wrapper_class =  (isset( $col_data['mod']) &&  $col_data['mod']!='') ?  $col_data['mod'].='' : 'boxed';
            if($row_wrapper_class=='full-width'){
                $container_close =' </div >';
            }
        }

        return '<div class="clear"></div></div>'.$container_close;
    }


    function group_columns_to_row($cols, $level =1, $parent_type ='' ){
        $rows = array();
        $ri=$i=0;
        $n= count($cols);

        while($i<$n){

            $width = $this->get_col_width($cols[$i]);
            $c = $this->divider($width);


            if($rows[$ri]['total']+$c<=1){
                $rows[$ri]['total'] += $c;
                $rows[$ri]['cols'][] = $cols[$i];
            }else{
                $ri++;
                $rows[$ri]['total'] += $c;
                $rows[$ri]['cols'][] = $cols[$i];
            }

            $rows[$ri]['__type']=$cols[$i]['type'];
            $i++;
        }// end while

        // generate code to display: maybe html or shortcode

        $string_shortcode = array();

        $n_rows = count($rows);

        $row_data = false;

        foreach($rows as $j => $row){
            $str_cols =  array();
            $i=1;

            $nr = count($row['cols']);

            $row_wrapper_class='';
            $row_data = false;

            foreach($row['cols'] as  $ci => $data){

                // row - column index
                $rc = 'index-'.$i;
                if($i==$nr){
                    $rc.= ' last ';
                }

                if($i==1){
                    $rc.= ' first ';
                }
                $i++;

                $data = stripslashes_deep($data);

                $width = $this->get_col_width($data);
                $item_class ='';
                //$item_class =' lv-'.$level;


                if(strtolower($data['type'])=='item'){
                    $row_wrapper_class =' items-inside';
                    $item_class = ' '.stpb_layout_column_class('1-1').' '.$item_class;
                    $item = $this->builder_items[$data['item_func']];
                    if(empty($item)){
                        $item = $this->builder_items[$data['builder_item_func']];
                    }

                    $i_class =' index-'.($j+1);
                    if($j==0){
                        $i_class .=' first';
                    }
                    if($j==$n_rows-1){
                        $i_class .=' last';
                    }

                    $row_wrapper_class .= $i_class;


                    if(function_exists($item['generate_func'])){
                        $func_class= str_replace(array('generate_','_'),array('','-'),$item['generate_func']);

                        //$str_cols[]="<div class=\"".apply_filters('stpb_item_generate_class',"builder-item".$item_class." $rc", $data)."\" ><div class=\"item-inner {$func_class}\">".call_user_func($item['generate_func'],$data)."</div> </div>";
                        //$str_cols[]="<div class=\"item-inner ".apply_filters('stpb_item_generate_class',"builder-item".$item_class." $rc", $data)." {$func_class}\">".call_user_func($item['generate_func'],$data)."</div> ";
                        if($level==1){

                            if(in_array($item['generate_func'], array(  'stpb_generate_map', 'stpb_generate_LayerSlider', 'stpb_generate_revslider' ))){

                                // fake is row
                                $row['__type'] = 'row';
                                $row_data =  array('settings'=> array('_classes'=> $func_class));

                                $str_cols[]=call_user_func($item['generate_func'],$data);

                            }else{
                                $str_cols[] = "<div class=\"".apply_filters('stpb_item_generate_class',"builder-item".$item_class." $rc", $data)."\" ><div class=\"item-inner {$func_class}\">".call_user_func($item['generate_func'],$data)."</div> </div>";

                            }

                        }else{
                            $str_cols[]="<div class=\"item-inner {$func_class}\">".call_user_func($item['generate_func'],$data)."</div>";
                        }

                    }else{
                        $str_cols[]="<div class=\"nothing-inside ".apply_filters('stpb_item_generate_class',"builder-item".$item_class." $rc", $data)."\">  </div>";
                    }

                }elseif(strtolower($data['type'])=='column'){  // if item is a column
                    $row_wrapper_class =' columns-inside';
                    $data['settings'] = array_filter($data['settings']);

                    $str_cols[] = '   <div class=" '.apply_filters('stpb_column_generate_class',"builder-column ".stpb_layout_column_class($width, $level).$item_class." $rc",  $data).'"> '
                        .apply_filters('stpb_column_generate_settings_before','',$data)
                        .self::group_columns_to_row($data['items'], $level+1, 'column')
                        .apply_filters('stpb_column_generate_settings_after','',$data)
                        .' </div> ';

                }elseif(strtolower($data['type'])=='row'){
                    // $data['settings'] = array_filter($data['settings']);
                    $row_data = $data;

                    $row_wrapper_class =' rows-inside';
                    // row- secction mod
                    //$row_data['settings']['_classes'] = apply_filters('stpb_row_generate_class','builder-row'.$item_class." $rc", $data);

                    $str_cols[] =
                        /* apply_filters('stpb_row_generate_settings_before','',$data)
                         . */ self::group_columns_to_row($data['items'],$level+1, 'row') ;
                    // .apply_filters('stpb_row_generate_settings_after','',$data);
                }else{
                    $str_cols[]="<div class=\"no-item-inside".stpb_layout_column_class($width, $level).$item_class."\"></div>";
                }
            }

            $str_cols= join("\n",$str_cols);
            $classes = 'items-wrapper '.$row_wrapper_class;


            if($level<2){
                $classes.=' row';
            }

            $index_class='';
            /*
            if($level<2){
                $index_class.=' r-index-'.($j+1);
                if($j==0){
                    $index_class .=' r-first';
                }
                if($j==$n_rows-1){
                    $index_class .=' r-last';
                }
            }
            */
            // echo  var_dump($row['__type'])."\n\n<hr/>\n\n";

            if($row_data){
                $content = $str_cols;
            }else{
                $content =  '<div class="'.apply_filters('stpb_wraper_class', $classes, $level, $row).'">'.$str_cols.' <div class="clear"></div> </div>';
            }

            $string_shortcode[] = array(
                'content'=> $content,
                // 'content'=>  $str_cols,
                'type' => $row['__type'],
                'data' => $row_data
            );
            $row_index++;

        }

        $a_content =  array();
        $isc= 0;
        $is_new_row = false;
        foreach($string_shortcode as $k=> $e){
            if($e['type']!='row'){
                if($is_new_row){
                    $isc++;
                    $is_new_row = false;
                }
                $a_content[$isc]['c'][] = $e['content'] ;
                $a_content[$isc]['data'] = $e['data'] ;
            }else{
                $isc++;
                $a_content[$isc]['c'] = $e['content'] ;
                $a_content[$isc]['data'] = $e['data'] ;
                $is_new_row = true;

            }
        }

        unset($string_shortcode, $isc);

        // $a_content = array_filter($a_content);

        $return_content ='';
        $ca=  count($a_content);
        $aindex=0;
        foreach($a_content as $k => $e){
            $classes = array();
            $classes[] = 'section  section-'.($aindex+1);
            $classes[] = ' lv-'.$level;

            if($aindex == 0){
                $classes[] = ' first';
            }

            if($aindex ==$ca-1){
                $classes[] = 'last';
            }

            if($level==1){

                if(!empty($e['data']) && is_array($e['data'])){
                    $e['data']['settings']['_classes'].='  '.join(' ', $classes);
                    $return_content .= apply_filters('stpb_row_generate_settings_before','',$e['data']);
                }else{
                    $classes[]= 'g';
                    $return_content.='<div class="'.join(' ', $classes).'">';
                }
            }


            if(is_array($e['c']) && count($e['c'])>1){
                //$return_content .=   join(' ', $e['c']);

                $n = count($e['c']);

                foreach($e['c'] as $k=>  $c){
                    $classs ='bd-row row-'.$k.' n-'.$n.' lv-'.$level;
                    if($k==0){
                        $classs.=' first';
                    }

                    if($k==$n-1){
                        $classs.=' last';
                    }

                    if($level<2){
                        $classs.= ' row';
                    }

                    $classs.= ' clearfix';

                    $return_content.='<div class="'.$classs.'">'.$c.'</div>';
                }

            }else{
                $c =  is_string($e['c']) ? $e['c'] : $e['c'][0] ;
                if($level==1){
                    $c='<div class="bd-row one first last clearfix lv-1">'.$c.'</div>';
                }

                $return_content.= $c;
                unset($c);
            }

            if($level==1){

                if(!empty($e['data']) && is_array($e['data'])){
                    $return_content .=  apply_filters('stpb_row_generate_settings_after','',$e['data']);

                }else{
                    $return_content .="</div>";
                }


            }

            $aindex++;

        }
        // $string_shortcode = join("\n",$string_shortcode);
        return $return_content;

    }



}
