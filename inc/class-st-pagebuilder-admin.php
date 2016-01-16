<?php
if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

if (!function_exists('wp_create_nonce')) {
    require_once(ABSPATH . '/wp-includes/pluggable.php');
}

function st_create_nonce(){
    return plugin_basename('stToolKit');
}


function stpb_table_button_template( $m ) {
    global $post;
    // allow [[foo]] syntax for escaping a tag
    if ( $m[1] == '[' && $m[6] == ']' ) {

    }

    $attr = shortcode_parse_atts( $m[3] );
    if(!is_array($attr)){
        $attr = (array) $attr;
    }

    $func =  apply_filters('st_table_builder_button_config','stpb_button');
    ?>
    <input type="hidden" value="<?php echo $func; ?>" class="st-item-func" data-name="[item_func]" >
    <?php
    call_user_func($func,'[settings]',$attr, $post, true);

}



class ST_Page_Builder_Admin extends ST_Page_Builder
{

    public $nonceName = 'STnonce';
    public $nonceValue = '';
    public $settings = array('url' => '', 'path' => '');
    public $item_sizes ;
   // public $class_to_items_size;

    function __construct($settings = array())
    {

        if (!empty($settings)) {
            $this->settings = $settings;
        } else {
            $this->settings['url'] = ST_PAGEBUILDER_URL;
            $this->settings['path'] = ST_PAGEBUILDER_PATH;
        }

        add_action('save_post', array($this, 'saveData'));

        $this->item_sizes = ST_Page_Builder::get_builder_item_sizes();

        add_action('wp_ajax_stpb_save_builder_template',  array($this,'save_builder_template'));
        add_action('wp_ajax_stpb_load_builder_templates',  array($this,'load_templates'));
        add_action('wp_ajax_stpb_load_template',  array($this,'load_builder_template'));
        add_action('wp_ajax_stpb_remove_template',  array($this,'remove_template'));

        // for table builder
        add_action('wp_ajax_stpb_table_button_template',  array($this, 'table_button_template'));
        add_action('wp_ajax_stpb_table_button_shortcode',  array($this, 'shortcode_and_preview'));
        add_action('wp_ajax_stpb_preview_builder_item',  array($this, 'shortcode_and_preview')); // preview builder item

        add_action('wp_ajax_stpb_create_shortcode',  array($this,'create_shortcode'));

        add_action('wp_ajax_stpb_link_actions',  array($this, 'link_actions'));

        // add shortcode btn for editor
        add_action('media_buttons_context',  array($this, 'st_add_shorcodes_button'));

    }


    function link_actions(){

        $do = $_REQUEST['_do'];
        $type =  $_REQUEST['type'];
        if($do == 'get_type'){

            switch($type){
                case 'post_type':
                    $post_types = get_post_types(false,'objects');
                    echo '<p>';
                    echo '<select class="link-item-type">';
                    foreach($post_types as $k=> $pt){
                        if($pt->show_in_nav_menus){
                            echo '<option  value="'.esc_attr($k).'">'.esc_html($pt->labels->menu_name).'</option>';
                        }
                    }

                    echo '</select>';
                    echo '</p>';

                break;

                case 'taxonomy':
                    $taxs =  get_taxonomies( false, 'objects' );
                     echo '<p>';
                    echo '<select  class="link-item-type" >';
                    foreach($taxs as $k=> $t){
                        if($t->show_ui){
                            echo '<option  value="'.esc_attr($k).'">'.esc_html($t->labels->singular_name).'</option>';
                        }
                    }
                    echo '</select>';
                    echo '</p>';
                break;
            }

        }elseif($do=='get_items'){

            $item_type =  $_REQUEST['item_type'];

            $paged = intval($_REQUEST['paged']);
            $show =  10;

            $http_args = array(
                'action'=>'stpb_link_actions',
                '_do'=>$do,
                'item_type'=>$item_type,
                'type'=>$type
            );

            switch($type){
                case 'post_type':

                    $args = array(
                        'posts_per_page'    => $show,
                        'post_type' =>$item_type,
                        'orderby'           => 'title',
                        'order'             => 'ASC'
                    );

                    // search
                    $s = $_REQUEST['s'];
                    if(trim($s)!=''){
                        $args['s'] = trim($s);
                        $http_args['s'] =  $args['s'];
                    }


                    if($paged > 0){
                        $args['paged'] =  $paged;
                    }

                    if(st_is_wpml()) {
                        $args['sippress_filters'] = true;
                        $args['language'] = get_bloginfo('language');
                    }

                    $query = new WP_Query( );
                    $posts =  $query->query($args);

                    // search input
                     echo '<p class="link-form"><input type="text" class="link-search" value="'.esc_attr($s).'" placeholder="'.esc_attr(__('Keyword','smooththemes')).'" > <a data=\''.esc_attr(json_encode( $http_args  )).'\'  href="#" class="button-secondary search-submit">'.__('Search','smooththemes').'</a> </p>';

                    if($posts){

                        foreach($posts as $post){
                            $item_data = array(
                                'id'=>$post->ID,
                                'item_type'=>$item_type,
                                'type'=>$type
                            );
                            $link = get_permalink($post->ID);
                            echo '<div class="post-type link-item-data" data-url="'.$link.'" data-label="'.esc_attr($post->post_title).'" data-id="'.$post->ID.'" data-link=\''.esc_attr(json_encode( $item_data )).'\' >#'.$post->ID.' -  '.esc_html($post->post_title).'</div>';
                        }

                        $total_pages = ceil($query->found_posts/$show);
                        if($paged>$total_pages ){
                            $paged = $total_pages;
                        }

                        $page_links = paginate_links(array(
                            'base' =>admin_url('admin-ajax.php').'%_%',
                            'format' => '?paged=%#%&'.$lquery,
                            'current' => $paged,
                            'total' => $total_pages,
                            'end_size'     => 3,
                            'mid_size'     => 3,
                            'prev_next'    => true,
                            'prev_text'    => __('&laquo; Previous','smooththemes'),
                            'next_text'    => __('Next &raquo;','smooththemes'),
                            'show_all' => false,
                            'add_args'=> $http_args,
                            'type' => 'array'

                        ));


                        if($page_links){
                            $p = '';

                            foreach($page_links as  $v){
                                $p.='<span class="button-secondary">'.$v.'</span>';
                            }

                            echo '<div class="paging"><p>'. $p .'</p></div>';
                        }


                    }else{
                        echo '<strong>'.__('Not found.','smooththemes').'</strong>';
                    }

                    break;

                case 'taxonomy':

                    $terms = get_terms( $item_type );

                    if(count($terms)){
                        foreach($terms as $term){
                            $item_data = array(
                                'id'=>$term->term_id,
                                'slug'=>$term->slug,
                                'item_type'=>$item_type,
                                'type'=>$type
                            );

                            $link = get_term_link($term->slug, $item_type);
                            if(  is_wp_error( $link ) ){
                                $link ='';

                                // get_category_link();
                            }

                            echo '<div class="tax-type link-item-data"  data-label="'.esc_attr($term->name).'" data-url="'.$link.'" data-id="'.$term->term_id.'" data-link="'.esc_attr(json_encode( $item_data )).'" >#'.$term->term_id.' -  '.esc_html($term->name).'</div>';
                        }
                    }else{
                        echo '<strong>'.__('Not found.','smooththemes').'</strong>';
                    }





                break;
            }
        }

        die();
    }


    function table_button_template(){

        $func =  apply_filters('st_table_builder_button_config','stpb_button');
        $pattern = get_shortcode_regex();
        $shortcode_data = (string)  $_POST['shortcode_data'];

        $shortcode_data =stripslashes($shortcode_data);

        if($shortcode_data=='' || strpos($shortcode_data,'[')===false){
            $shortcode_data ='[st_button]';
        }


        if(function_exists($func)){
            if(!preg_replace_callback( "/$pattern/s", 'stpb_table_button_template', $shortcode_data )){
               // stpb_table_button_template('');
            }
            die();
        }else{
            die('Invalid st_table_builder_button_config');
        }
    }


    function shortcode_and_preview(){
        $json['shortcode'] =  self::create_shortcode_from_post();
        $json['preview'] =  do_shortcode( $json['shortcode']);

        die(json_encode($json));
    }

    function create_shortcode_from_post(){
        $items = ST_Page_Builder_Items_Config();

        $shortcode ='';
        $_POST['shortcode_data'] = wp_parse_args($_POST['shortcode_data'], array());
        $_POST['shortcode_data'] = stripslashes_deep($_POST['shortcode_data']);
        if(isset($_POST['shortcode_data']['_st_shortcode']['item_func'])){
            $item = $items[$_POST['shortcode_data']['_st_shortcode']['item_func']];
            if(isset($item)){
                $func =  $item['generate_func'];
                if(function_exists($func)){
                    $shortcode = call_user_func_array($func,$_POST['shortcode_data']);
                }
            }
        }
        return $shortcode;
    }

    function create_shortcode(){
        $shortcode = self::create_shortcode_from_post();
        die($shortcode);
    }

    /**
     * Run page builder
     */
    public function run()
    {
        $this->nonceValue = wp_create_nonce('STPageBuilder');

        add_action('add_meta_boxes', array($this, 'pageBuilderSupport'),1);
        add_action('add_meta_boxes', array($this, 'page_options'));
        add_action('add_meta_boxes', array($this, 'post_options'));

        // add css
        add_action('admin_print_styles-post.php', array($this, 'css'));
        add_action('admin_print_styles-post-new.php', array($this, 'css'));
        add_action('admin_print_scripts-post.php', array($this, 'js'));
        add_action('admin_print_scripts-post-new.php', array($this, 'js'));

    }

    function st_add_shorcodes_button(){
        $items = ST_Page_Builder_Items_Config();
        global $post;
        $screen  =  get_current_screen();
        if(!in_array($screen->base, array('post'))){
            return;
        }

        $layouts = array(
            array(
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_11.png",
                'title'=>__('Layout 1/1','smooththemes'),
                'data_shortcode'=>'[row] [col width="1/1"]  Your content [/col] [/row]'
            ),
            array(
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_12.png",
                'title'=>__('Layout 1/2+1/2','smooththemes'),
                'data_shortcode'=>'[row] [col width="1/2"]  Your content [/col] [col width="1/2"]  Your content [/col] [/row]'
            ),
            array(
                 'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_13.png",
                 'title'=>__('Layout 1/3+1/3+1/3','smooththemes'),
                 'data_shortcode'=>'[row] [col width="1/3"]  Your content [/col] [col width="1/3"]  Your content [/col] [col width="1/3"]  Your content [/col] [/row]'
            ),
            array(
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_14.png",
                'title'=>__('Layout 1/4+1/4+1/4+1/4','smooththemes'),
                'data_shortcode'=>'[row] [col width="1/4"]  Your content [/col] [col width="1/4"]  Your content [/col] [col width="1/4"]  Your content [/col] [col width="1/4"]  Your content [/col] [/row]'
            )
        );

        $items =  apply_filters('st_list_shortcodes',array_merge($layouts,$items));

        $builder =  new ST_Page_Builder_Interface();
        ?>
        <a href="#" id="st-editor-shortcodes" title="<?php _e('Select shortcode to insert','smooththemes'); ?>" class="button-secondary"><span><i class="iconentypo-flash"></i></span><?php _e('Shortcodes','smooththemes') ?></a>
        <div id = "st-editor-list-sc-tpl" class="hide">
            <div class="st-list-sc">
                <?php
                foreach($items as $func => $item){

                    if(isset($item['shortcode']) && $item['shortcode']===false){
                        continue;
                    }
                    ?>
                    <div class="item-cell" <?php echo ($item['data_shortcode']) ? ' data-shortcode="'.esc_attr($item['data_shortcode']).'" ' : '' ;?> data-id="<?php echo ($func!='') ? $func.'-sc-tpl' : ''; ?>" edit-title="<?php echo esc_attr($item['title']); ?>">
                    <span class="iconw">
                        <span class="icon">
                    <?php if($item['icon']!=''){ ?>
                        <img src="<?php echo $item['icon']; ?>" alt="icon">
                    <?php }else{ ?>
                        <span class="no-icon"></span>
                    <?php } ?>
                    </span>
                    </span>
                        <strong><?php echo esc_html($item['title']); ?></strong>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <?php
        foreach($items as $func => $item){
            if(function_exists($func)){
            ?>
            <div  class="hide" id="<?php echo $func.'-sc-tpl'; ?>">
                <?php

                    ?>
                    <input type="hidden" value="<?php echo $func; ?>" class="st-item-func" data-name="[item_func]" >
                    <?php
                    call_user_func($func,'[settings]',array(), $post, true, $builder);

                ?>
            </div>
            <?php
            }
        }
        ?>
    <?php
    }


    function save_builder_template(){

        $tpl_post['post_title'] =$_POST['template_name'];
        $tpl_post['post_type'] ='st_template';

        $_POST['post_data'] = wp_parse_args($_POST['post_data'], array());

        $settings =   $_POST['post_data'][ST_Page_Builder::BUILDER_SETTINGS_NAME];
        $id =  wp_insert_post( $tpl_post );

        if(is_numeric($id)){
            ST_Page_Builder::save_builder_settings($id, $settings);
             die(1);
        }

        die(0);
    }


    function load_templates(){
        $args = array(
            'posts_per_page'=>'-1',
            'order' => 'DESC',
            'orderby' => 'ID',
            'post_type' => 'st_template',
            'post_status' => 'any'
        );
        $posts = get_posts($args);
        foreach($posts as $p){
            ?>
            <div class="item list-template">
                <a href="#" class="load-this-tpl" title="<?php _e('Use this template','smooththemes'); ?>"  post-id="<?php echo $p->ID; ?>"><i class="iconentypo-plus-circled"></i></a>
                <a href="#" class="remove-this-tpl" title="<?php _e('Remove this template','smooththemes'); ?>"  post-id="<?php echo $p->ID; ?>"><i class="iconentypo-minus-circled"></i></a>
                <strong><?php echo esc_html($p->post_title); ?></strong>
            </div>
            <?php
        }
        die();
    }

    function load_builder_template(){
        $id=  intval($_POST['id']);
        $builder= new ST_Page_Builder_Interface($id);
        $builder->canvans();

        die();
    }

    function remove_template(){
        $id=  intval($_POST['id']);
        wp_delete_post( $id, true );
        die(1);
    }

    function pageBuilderSupport()
    {
        global $post;
        // if is shop page
        if($post->ID == st_get_shop_page()){
            return ;
        }

        $screens = apply_filters('st_page_builder_support',array('page'));

        foreach ($screens as $screen) {
            add_meta_box(
                'st_page_builder',
                __('ST Page Builder', 'smooththemes'),
                array($this, 'builderInterface'),
                $screen,
                'advanced',
                'high'
            );
        }
    }

    function page_options(){
        $screens = array('page');
        foreach ($screens as $screen) {
            add_meta_box(
                'st_page_options',
                __('Layout', 'smooththemes'),
                array($this, 'page_options_inferface'),
                $screen
            );
        }
    }

    function post_options(){
        $screens = array('post');
        foreach ($screens as $screen) {
            add_meta_box(
                'st_post_options',
                __('Layout', 'smooththemes'),
                array($this, 'post_options_inferface'),
                $screen
            );
        }
    }


    function post_options_inferface(){
        global $post ;
        $name =  ST_Page_Builder::PAGE_OPTIONS_NAME;
        $save_values = ST_Page_Builder::get_page_options($post->ID, array());

        $layouts = st_page_layout_config();

        $inter=  new ST_Page_Builder_Interface();
        $sidebars =  $inter->get_sidebar_widgets();

        wp_nonce_field(plugin_basename(__FILE__), 'stPageBuilder_nonce');

        ?>
        <div class="st-page-options stpb-lb-content-settings">
            <?php do_action('st_post_options_before_settings',$name,$save_values); ?>

            <div class="st-option-item item">
                <div class="width-50 left">
                    <?php stpb_input_select_one($name.'[layout]',$save_values['layout'],$layouts, '.st-select-sidebar','', true); ?>
                </div>
                <div class="width-50 right">
                    <strong><?php _e('Layout','smooththemes');  ?></strong>
                    <span><?php _e('Select the desired Page layout','smooththemes');  ?></span>
                </div>

            </div>

            <div class="item st-option-item show-on-select-change st-select-sidebar" show-on="right-sidebar left-right-sidebar">
                <div class="width-50 left">
                    <?php stpb_input_select_one($name.'[right_sidebar]',$save_values['right_sidebar'],$sidebars,'','', true); ?>
                </div>
                <div class="width-50 right">
                    <strong><?php _e('Right Sidebar','smooththemes');  ?></strong>
                    <span><?php _e('Choose a custom right sidebar for this entry','smooththemes');  ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change st-select-sidebar" show-on="left-sidebar left-right-sidebar">
                <div class="width-50 left">
                    <?php stpb_input_select_one($name.'[left_sidebar]',$save_values['left_sidebar'],$sidebars,'','', true); ?>
                </div>
                <div class="width-50 right">
                    <strong><?php _e('Left Sidebar','smooththemes');  ?></strong>
                    <span><?php _e('Choose a custom left sidebar for this entry','smooththemes');  ?></span>
                </div>

            </div>


            <div class="item st-option-item">
                <div class="left width-50">
                    <?php stpb_input_select_one($name.'[thumb_type]',$save_values['thumb_type'], apply_filters('st_post_thumb_settings', array(
                        'featured'=>__('Featured Image','smooththemes'),
                        'gallery'=>__('Gallery','smooththemes'),
                        'slider'=>__('Slider','smooththemes'),
                        'video'=>__('Video','smooththemes')
                    )),'.st_thumbnail_type','', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Thumbnail Type','smooththemes');  ?></strong>
                </div>

            </div>

            <div class="item st-option-item show-on-select-change st_thumbnail_type" show-on="gallery slider">
                <div class="left width-50">
                    <?php stpb_input_media($name.'[gallery]',$save_values['gallery'],'gallery',__('Select/Change Images','smooththemes'),'', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Gallery','smooththemes');  ?></strong>
                    <span><?php _e('Use gallery for your post thumbnail.','smooththemes');  ?></span>
                </div>
            </div>


            <div class="item st-option-item show-on-select-change st_thumbnail_type" show-on="video">
                <div class="width-50 left">
                    <?php stpb_input_text($name.'[video]',$save_values['video'],'', true); ?>
                </div>
                <div class="width-50 right">
                    <strong><?php _e('Video','smooththemes');  ?></strong>
                    <span><?php _e('Use Video for your post thumbnail, enter video url here:','smooththemes');  ?></span>
                </div>
            </div>

            <?php do_action('st_post_options_more_settings',$name,$save_values); ?>

        </div>
        <?php
    }



    function page_options_inferface(){
        global $post ;
        $name =  ST_Page_Builder::PAGE_OPTIONS_NAME;
        $save_values = ST_Page_Builder::get_page_options($post->ID, array());

        $layouts = st_page_layout_config();

        $inter=  new ST_Page_Builder_Interface();
        $sidebars =  $inter->get_sidebar_widgets();

        wp_nonce_field(plugin_basename(__FILE__), 'stPageBuilder_nonce');

        ?>
        <div class="st-page-options stpb-lb-content-settings">

          <?php do_action('st_page_options_before_settings',$name,$save_values); ?>

            <div class="st-option-item item">
                <div class="width-50 left">
                    <?php stpb_input_select_one($name.'[layout]',$save_values['layout'],$layouts, '.st-select-sidebar','', true); ?>
                </div>
                <div class="width-50 right">
                    <strong><?php _e('Layout','smooththemes');  ?></strong>
                    <span><?php _e('Select the desired Page layout','smooththemes');  ?></span>
                </div>

            </div>

            <div class="item st-option-item show-on-select-change st-select-sidebar" show-on="right-sidebar left-right-sidebar">
                <div class="width-50 left">
                    <?php stpb_input_select_one($name.'[right_sidebar]',$save_values['right_sidebar'],$sidebars,'','', true); ?>
                </div>
                <div class="width-50 right">
                    <strong><?php _e('Right Sidebar','smooththemes');  ?></strong>
                    <span><?php _e('Choose a custom right sidebar for this entry','smooththemes');  ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change st-select-sidebar" show-on="left-sidebar left-right-sidebar">
                <div class="width-50 left">
                    <?php stpb_input_select_one($name.'[left_sidebar]',$save_values['left_sidebar'],$sidebars,'','', true); ?>
                </div>
                <div class="width-50 right">
                    <strong><?php _e('Left Sidebar','smooththemes');  ?></strong>
                    <span><?php _e('Choose a custom left sidebar for this entry','smooththemes');  ?></span>
                </div>

            </div>

            <div class="item st-option-item">
                <div class="width-50  left">
                    <?php stpb_input_select_one($name.'[show_page_el]',$save_values['show_page_el'], array(
                        'yes'=>__('Yes','smooththemes'),
                        'no'=>__('No','smooththemes')
                    ),'.show_page_el','', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Show Top Elements','smooththemes');  ?></strong>
                    <span><?php  _e('Display the Header with Page Title, Breadcrumb Navigation,...' ,'smooththemes'); ?></span>
                </div>
            </div>

            <?php
            do_action('st_after_show_page_el_settings', $name, $save_values );


            // title bar settings
            if(current_theme_supports('st-titlebar')){ ?>
            <div class="item st-option-item show-on-select-change show_page_el" show-on="yes">
                <div class="width-50  left">
                    <?php stpb_input_select_one($name.'[titlebar]',$save_values['titlebar'], array(
                        'default'=>__('Default - Set in Theme Options','smooththemes'),
                        'defined'=>__('Defined Style','smooththemes'),
                        'custom'=>__('Custom','smooththemes')
                    ),'.title_bar_style','', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Title bar Style','smooththemes');  ?></strong>
                    <span><?php  _e('' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change title_bar_style show_page_el" show-on="yes defined">
                <div class="width-50  left">
                    <?php
                    $list_titlebar_bg = apply_filters('st_titlebar_list_bg',array());
                    stpb_input_layout($name.'[titlebar_defined]',$save_values['titlebar_defined'],$list_titlebar_bg, true) ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Title bar style','smooththemes');  ?></strong>
                    <span><?php  _e('Select defined title bar style.' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change title_bar_style show_page_el" show-on="custom">
                <div class="width-50  left">
                    <?php
                    stpb_input_media($name.'[titlebar_bg_img]',$save_values['titlebar_bg_img'],'image',__('Select/Change image','smooththemes'),'', true);
                    ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Titlebar background image','smooththemes');  ?></strong>
                    <span><?php  _e('' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change title_bar_style show_page_el" show-on="custom">
                <div class="left width-50">
                    <?php stpb_input_color($name.'[titlebar_bg_color]',$save_values['titlebar_bg_color'],'', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e(' Titlebar Background Color','smooththemes'); ?></strong>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change title_bar_style show_page_el" show-on="custom">
                <div class="left width-50">
                    <?php  stpb_input_select_one($name.'[titlebar_bg_position]',$save_values['titlebar_bg_position'], array(
                        'tl'=>__('Top left','smooththemes'),
                        'tc'=>__('Top center','smooththemes'),
                        'tr'=>__('Top right','smooththemes'),
                        'cc'=>__('Center','smooththemes'),
                        'bl'=>__('Bottom left','smooththemes'),
                        'bc'=>__('Bottom center','smooththemes'),
                        'br'=>__('Bottom right','smooththemes')
                    ),'','', true); ?>
                </div>
                <div class="right  width-50">
                    <strong><?php _e('Titlebar Background Image Position','smooththemes') ?></strong>
                </div>
            </div>


            <div class="item st-option-item show-on-select-change title_bar_style show_page_el" show-on="custom">
                <div class="left width-50">
                    <?php  stpb_input_select_one($name.'[titlebar_bg_repeat]',$save_values['titlebar_bg_repeat'], array(
                        'repeat'=>__('Repeat','smooththemes'),
                        'no-repeat'=>__('No repeat','smooththemes'),
                        'repeat-x'=>__('Horizontally','smooththemes'),
                        'repeat-y'=>__('Vertically','smooththemes'),

                    ),'','', true); ?>
                </div>
                <div class="right  width-50">
                    <strong><?php _e('Titlebar Background Repeat','smooththemes') ?></strong>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change title_bar_style show_page_el" show-on="custom">
                <div class="left width-50">
                    <?php  stpb_input_select_one($name.'[titlebar_bg_attachment]',$save_values['titlebar_bg_attachment'], array(
                        'scroll'=>__('Scroll','smooththemes'),
                        'fixed'=>__('Fixed','smooththemes'),
                        'stretch'=>__('Stretch to fit','smooththemes')
                    ),'','', true); ?>
                </div>
                <div class="right  width-50">
                    <strong><?php _e('Titlebar Background Attachment','smooththemes') ?></strong>
                </div>
            </div>

            <?php

            do_action('st_more_titlebar_settings', $name,$save_valuess );

            }
            // end title bar settings
            ?>


            <?php
            if($post->ID == st_get_shop_page()){
            ?>

            <div class="item st-option-item">
                <div class="width-50  left">
                    <?php
                    if(!isset($save_values['shop_columns']) ||  $save_values['shop_columns']==''){
                        $save_values['shop_columns']= 3;
                    }
                    stpb_input_select_one($name.'[shop_columns]',$save_values['shop_columns'], array(
                        2=>2, 3=> 3, 4=>4, 6=>6
                    ),'','', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Number columns','smooththemes');  ?></strong>
                    <span><?php  _e('How many columns of products to show ?' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item">
                <div class="width-50  left">
                    <?php
                    if(!isset($save_values['number_product']) ||  $save_values['number_product']==''){
                        $save_values['number_product'] = 9;
                    }
                    stpb_input_text($name.'[number_product]',$save_values['number_product'],'', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Number Products','smooththemes');  ?></strong>
                    <span><?php  _e('How many products per page to show ?' ,'smooththemes'); ?></span>
                </div>
            </div>

            <?php
            // ---------relative product ---------------------------
            ?>
            <div class="item st-option-item">
                <div class="width-50  left">
                    <?php stpb_input_select_one($name.'[show_relative_prod]',$save_values['show_relative_prod'], array(
                        'yes'=>__('Yes','smooththemes'),
                        'no'=>__('No','smooththemes')
                    ),'.number_relative_prod','', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Show relative products','smooththemes');  ?></strong>
                    <span><?php  _e('Display relative product on single product' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change number_relative_prod" show-on="yes">
                <div class="width-50  left">
                    <?php
                    if(!isset($save_values['number_relative_prod']) ||  $save_values['number_relative_prod']==''){
                        $save_values['number_relative_prod'] = 3;
                    }
                    stpb_input_text($name.'[number_relative_prod]',$save_values['number_relative_prod'],'', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Number Relative Products','smooththemes');  ?></strong>
                    <span><?php  _e('How many relative products to show ?' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change number_relative_prod" show-on="yes">
                <div class="width-50  left">
                    <?php
                    if(!isset($save_values['relative_prod_num_col']) ||  $save_values['relative_prod_num_col']==''){
                        $save_values['relative_prod_num_col'] = 3;
                    }
                    stpb_input_select_one($name.'[relative_prod_num_col]',$save_values['relative_prod_num_col'], array(
                        2=>2, 3=> 3, 4=>4, 6=>6
                    ),'','', true);

                    ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Number Relative columns','smooththemes');  ?></strong>
                    <span><?php  _e('How many columns of Relative products to show ?' ,'smooththemes'); ?></span>
                </div>
            </div>

            <?php
            // ---------upsells product ---------------------------
            ?>

            <div class="item st-option-item">
                <div class="width-50  left">
                    <?php stpb_input_select_one($name.'[show_upsells_prod]',$save_values['show_upsells_prod'], array(
                        'yes'=>__('Yes','smooththemes'),
                        'no'=>__('No','smooththemes')
                    ),'.number_upsells_prod','', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Show Up-Sells products','smooththemes');  ?></strong>
                    <span><?php  _e('Display Up-Sells product on single product' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change number_upsells_prod" show-on="yes">
                <div class="width-50  left">
                    <?php
                    if(!isset($save_values['number_upsells_prod']) ||  $save_values['number_upsells_prod']==''){
                        $save_values['number_upsells_prod'] = 3;
                    }
                    stpb_input_text($name.'[number_upsells_prod]',$save_values['number_upsells_prod'],'', true); ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Number Up-Sells Products','smooththemes');  ?></strong>
                    <span><?php  _e('How many Up-Sells products to show ?' ,'smooththemes'); ?></span>
                </div>
            </div>

            <div class="item st-option-item show-on-select-change number_upsells_prod" show-on="yes">
                <div class="width-50  left">
                    <?php
                    if(!isset($save_values['upsells_prod_num_col']) ||  $save_values['upsells_prod_num_col']==''){
                        $save_values['upsells_prod_num_col'] = 3;
                    }
                    stpb_input_select_one($name.'[upsells_prod_num_col]',$save_values['upsells_prod_num_col'], array(
                        2=>2, 3=> 3, 4=>4, 6=>6
                    ),'','', true);

                    ?>
                </div>
                <div class="right width-50">
                    <strong><?php _e('Number Up-Sells columns','smooththemes');  ?></strong>
                    <span><?php  _e('How many columns of Up-Sells products to show ?' ,'smooththemes'); ?></span>
                </div>
            </div>




            <?php
            }
            ?>

            <?php do_action('st_page_options_more_settings',$name,$save_values); ?>

        </div>
    <?php
    }

    /**
     * Pagebuilder interface
     */
    function builderInterface($post)
    {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'stPageBuilder_nonce');
        $interface = new ST_Page_Builder_Interface();
        $interface->display();

    }

    /**
     * Save page builder data
     */
    function saveData($post_id)
    {


        // First we need to check if the current user is authorised to do this action.
        /*
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return;
        } else {
            if (!current_user_can('edit_post', $post_id))
                return;
        }
        */

        // Secondly we need to check if the user intended to change this value.
        if (!isset($_POST['stPageBuilder_nonce']) || ( !wp_verify_nonce($_POST['stPageBuilder_nonce'], st_create_nonce() ) &&  !wp_verify_nonce($_POST['stPageBuilder_nonce'], plugin_basename(__FILE__)  )  ) ){

            return;
        }

        ST_Page_Builder::save_page_options($post_id, $_POST[ST_Page_Builder::PAGE_OPTIONS_NAME]);
        update_post_meta($post_id, '_st_current_editor', $_POST['_st_current_editor']);

        // make sure page builder load completed
        if($_POST['_st_page_builder_loaded']==1){
            ST_Page_Builder::save_builder_settings($post_id, $_POST[ST_Page_Builder::BUILDER_SETTINGS_NAME]);

        }

    }

    /**
     * Add Css to header edit/Add new post
     */
    function css()
    {
        wp_enqueue_style('fontello', $this->settings['url'] . 'assets/css/fontello.css');
        wp_enqueue_style('fontello-animation', $this->settings['url'] . 'assets/css/animation.css');
        wp_enqueue_style('fontello-ie7', $this->settings['url'] . 'assets/css/fontello-ie7.css');
        //wp_enqueue_style('bootstrap', $this->settings['url'] . 'frontend/css/bootstrap.css');
       // wp_enqueue_style('bootstrap-theme', $this->settings['url'] . 'frontend/css/bootstrap-theme.css');


        wp_enqueue_style( 'wp-color-picker' );
       // wp_enqueue_style('st-ui', $this->settings['url'] . 'assets/css/ui/jquery-ui.css');
        wp_enqueue_style('st-pagebuilder', $this->settings['url'] . 'assets/css/pagebuilder.css');
        wp_enqueue_style('st-preview-builder', $this->settings['url'] . 'assets/css/preview-builder.css');
    }

    /**
     * Add JS to header edit/Add new post
     */
    function js()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-core');
        // http://code.jquery.com/ui/1.10.3/jquery-ui.js
        //  wp_enqueue_script('jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', array('jquery'));

        if(function_exists( 'wp_enqueue_media' )){
            wp_enqueue_media();
        }

        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-position');
        wp_enqueue_script('iris');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('quicktag');


        wp_enqueue_script('jquery.poshytip.js', $this->settings['url'] . 'assets/js/jquery.poshytip.js', array('jquery'));
        wp_enqueue_script('st-cookies', $this->settings['url'] . 'assets/js/cookies.js', array('jquery'));
        wp_enqueue_script('st-tabs-builder', $this->settings['url'] . 'assets/js/tabs-builder.js', array('jquery'));
        wp_enqueue_script('st-table-builder', $this->settings['url'] . 'assets/js/table-builder.js', array('jquery'));
        wp_enqueue_script('st-input-items', $this->settings['url'] . 'assets/js/input-items.js', array('jquery'));
        wp_enqueue_script('st-pagebuilder', $this->settings['url'] . 'assets/js/pagebuilder.js', array('jquery'));

        $l10n = array();
        $l10n[$this->nonceName] = $this->nonceValue;
        $l10n['input_name'] = ST_Page_Builder::BUILDER_SETTINGS_NAME;
        $l10n['item_sizes'] = $this->item_sizes;

        $l10n['config']['confirm_remove_row'] = __('Are you sure want to remove this row ?','smooththemes');
        $l10n['config']['confirm_remove_col'] = __('Are you sure want to remove this column ?','smooththemes');
        $l10n['config']['row_settings_title'] = __('Section settings','smooththemes');
        $l10n['config']['col_settings_title'] = __('Layout settings','smooththemes');
        $l10n['config']['loading'] = __('Loading...','smooththemes');
        $l10n['config']['tinymce_base'] = get_bloginfo('home').'/wp-includes/js/tinymce';

        if(function_exists('st_get_font_icons')){
            $l10n['font_icons'] = st_get_font_icons();
        }

        wp_localize_script('jquery-core', 'STBP', $l10n);


        if ( ! class_exists( '_WP_Editors' ) )
            require( ABSPATH . WPINC . '/class-wp-editor.php' );
        $set = _WP_Editors::parse_settings( 'ap[id]', $settings );

        if ( !current_user_can( 'upload_files' ) )
            $set['media_buttons'] = false;

        if ( $set['media_buttons'] ) {
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script('media-upload');

            $post = get_post();
            if ( ! $post && ! empty( $GLOBALS['post_ID'] ) )
                $post = $GLOBALS['post_ID'];

            wp_enqueue_media( array(
                'post' => $post
            ) );
        }

        _WP_Editors::editor_settings( 'ap[id]', $set );




    }


}
