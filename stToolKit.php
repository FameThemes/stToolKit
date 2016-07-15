<?php
/*
Plugin Name: stToolKit
Plugin URI: http://www.smooththemes.com/
Description: A drag and drop, responsive page builder that simplifies building your website.
Version: 1.7
Author: smooththemes
Author URI: http://www.smooththemes.com/
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
*/

if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );


// Add Featured Image to post type
add_theme_support( 'post-thumbnails', array('post') );

// Remove auto p for shortcode
remove_filter('the_content','do_shortcode');
add_filter('the_content','do_shortcode',1);

// Do shortcodes for widget
add_filter('widget_text', 'do_shortcode');

// Defined if stToolKit actived
define('ST_PAGEBUILDER', '1');


define('ST_PAGEBUILDER_URL',plugins_url('/', __FILE__));
define('ST_PAGEBUILDER_PATH',plugin_dir_path( __FILE__));

require_once  ST_PAGEBUILDER_PATH.'inc/templates.php';
require_once  ST_PAGEBUILDER_PATH.'inc/functions.php';
require_once  ST_PAGEBUILDER_PATH.'inc/class-st-pagebuilder.php';

// Import Settings from Current Themes
if(file_exists(  get_template_directory().'/config-plugins/stToolKit/stToolKit.php' ) ){
    require_once   get_template_directory().'/config-plugins/stToolKit/stToolKit.php';
}elseif(file_exists( get_template_directory().'/stToolKit.php' ) ){
    require_once  get_template_directory().'/stToolKit.php';
}

require_once  ST_PAGEBUILDER_PATH.'inc/shortcodes.php';

require_once  ST_PAGEBUILDER_PATH.'inc/user/login.php';
require_once  ST_PAGEBUILDER_PATH.'inc/user/register.php';

/**
 * load widgets
 * Call widgets in theme by function add_theme_support
 * Example: add_theme_support ( 'st-widgets', array('popular-posts', 'recent-comments', 'recent-posts','twitter', 'tab-content', 'ads125', 'flickr') );
 */
require_once  ST_PAGEBUILDER_PATH.'widgets/popular-posts.php';
require_once  ST_PAGEBUILDER_PATH.'widgets/recent-comments.php';
require_once  ST_PAGEBUILDER_PATH.'widgets/recent-posts.php';
require_once  ST_PAGEBUILDER_PATH.'widgets/twitter.php';
require_once  ST_PAGEBUILDER_PATH.'widgets/tab-content.php';
require_once  ST_PAGEBUILDER_PATH.'widgets/login.php';
require_once  ST_PAGEBUILDER_PATH.'widgets/flickr.php';
require_once  ST_PAGEBUILDER_PATH.'widgets/ads-125.php';

function stpb_builder_init() {
    ob_start();
    $args = array( 'public' => false, 'label' => 'Builder Template' );
    // template for page builder
    register_post_type( 'st_template', $args );
}

add_action( 'init', 'stpb_builder_init' );

function toolkit_text_domain_init() {

    load_plugin_textdomain( 'smooththemes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'toolkit_text_domain_init');

require_once  ST_PAGEBUILDER_PATH.'inc/class-sidebars.php';

if(is_admin()){ // working in back-end

    require_once  ST_PAGEBUILDER_PATH.'inc/builder-input.php';
    require_once  ST_PAGEBUILDER_PATH.'inc/class-st-pagebuilder-admin.php';
    require_once  ST_PAGEBUILDER_PATH.'inc/class-st-pagebuilder-interface.php';
    require_once  ST_PAGEBUILDER_PATH.'inc/post-type-columns.php';

    require_once  ST_PAGEBUILDER_PATH.'config/builder-items-functions.php';
    require_once  ST_PAGEBUILDER_PATH.'config/builder-generate-items-functions.php';
    require_once  ST_PAGEBUILDER_PATH.'config/builder-items.php';
    require_once  ST_PAGEBUILDER_PATH.'config/layout-items.php';
    require_once  ST_PAGEBUILDER_PATH.'config/font-icons.php';


    $STPageBuilderAdmin  = new ST_Page_Builder_Admin();
    $STPageBuilderAdmin->run();


   // Normal update
    if(!class_exists('PluginUpdateChecker')){
        require_once ST_PAGEBUILDER_PATH.'/update.php';
    }

    add_action('init', 'stpb_activate_auto_update');

    function stpb_activate_auto_update()
    {
        $theme_info =  wp_get_theme();
        //--------------------------- change when plugin live --------------------------------------------------------------
         $plugin_data = get_file_data( __FILE__, array(
                'Name' => 'Plugin Name',
                'PluginURI' => 'Plugin URI',
                'Version' => 'Version',
                'Description' => 'Description',
                'Author' => 'Author',
                'AuthorURI' => 'Author URI',
                'TextDomain' => 'Text Domain')
        );

        $send_data = array(
            'check-update' =>'stToolKit',
            'current_theme' =>$theme_info->get( 'Name' ) ,
            'current_version'=>$plugin_data['Version']
        );

        $stUpdateChecker = new PluginUpdateChecker(
             'http://smooththemes.com/api/github-updater.php?'.http_build_query($send_data),
            __FILE__,
            'stToolKit'
        );

    }
    
    
    // admin notice if curent theme donot support page builder

    function stpb_admin_notice() {
        if(!current_theme_supports('st-pagebuilder')){
            ?>
            <style type="text/css">
                .updated.st-admin-notice{
                    margin: 5px 0 15px;
                    padding: 15px !important;
                    background: #d9ff8b;
                    border:1px solid #a6ca8a;
                }
                .updated.st-admin-notice .note{
                    font-size: 16px;
                    color: #333;;
                }

            </style>
            <div class="updated st-admin-notice">
                <p class="note"><?php _e( 'Your theme does not declare <b>stToolKit</b> support – if you encounter layout issues please read our integration guide or choose a theme  from smooththemes.com :)', 'smooththemes' ); ?></p>
                <a class="button-secondary" target="_blank" href="http://smooththemes.com/"><b><?php _e('Get a Theme','smooththemes'); ?></b></a>
            </div>
        <?php
        }
    }
    add_action( 'admin_notices', 'stpb_admin_notice' );


}else{ // working in front-end

    require_once  ST_PAGEBUILDER_PATH.'frontend/titlebar.php';

    function st_toolkit_css(){
        wp_enqueue_style('fontello', ST_PAGEBUILDER_URL. 'assets/css/fontello.css');
        wp_enqueue_style('fontello-animation',ST_PAGEBUILDER_URL . 'assets/css/animation.css');
        wp_enqueue_style('fontello-ie7',ST_PAGEBUILDER_URL . 'assets/css/fontello-ie7.css');
        // if use default css
        if(!defined('ST_PAGEBUILDER_USE_CSS') || ST_PAGEBUILDER_USE_CSS!=false){
            wp_enqueue_style('bootstrap', ST_PAGEBUILDER_URL. 'frontend/css/bootstrap.css');
            //wp_enqueue_style('bootstrap-theme', ST_PAGEBUILDER_URL. 'frontend/css/bootstrap-theme.css');
            wp_enqueue_style('magnific-popup', ST_PAGEBUILDER_URL. 'frontend/css/magnific-popup.css');
            wp_enqueue_style('st-pagebuilder', ST_PAGEBUILDER_URL. 'frontend/css/custom.css');
            //wp_enqueue_style('bt-custom', ST_PAGEBUILDER_URL. 'frontend/css/bt-custom.css');
            wp_enqueue_style('jquery-ui', ST_PAGEBUILDER_URL. 'frontend/css/smoothness/jquery-ui-1.7.3.custom.css');

        }
    }

    function st_toolkit_scripts(){
        // if use default js
        if(!defined('ST_PAGEBUILDER_USE_JS') || ST_PAGEBUILDER_USE_JS!=false){
            wp_enqueue_script('jquery');
            // wp_enqueue_script('section-mod', ST_PAGEBUILDER_URL. 'frontend/js/section-mod.js', array('jquery'),'1.0');
            wp_enqueue_script('bootstrap', ST_PAGEBUILDER_URL. 'frontend/js/bootstrap.js', array('jquery'),'3.0', true);
            //wp_enqueue_script('google-map',  'http://maps.google.com/maps/api/js?sensor=true', false, false, true);
            wp_localize_script('jquery','ajaxurl',admin_url('admin-ajax.php'));
            wp_enqueue_script('jquery-ui-datepicker');


            /* ==== toolkit.min.js === */
            /*
            wp_enqueue_script('fitvids', ST_PAGEBUILDER_URL. 'frontend/js/jquery.fitvids.js', array('jquery'), '', true);
            wp_enqueue_script('imageLoaded', ST_PAGEBUILDER_URL. 'frontend/js/imageLoaded.js', array('jquery'),'3.0.4', true);
            wp_enqueue_script( 'jquery.touchwipe.min', ST_PAGEBUILDER_URL . 'frontend/js/jquery.touchwipe.min.js', array('jquery'), false, true);
            wp_enqueue_script( 'jquery.carouFredSel', ST_PAGEBUILDER_URL . 'frontend/js/jquery.carouFredSel.js', array('jquery'), false, true);

            wp_enqueue_script('magnific-popup', ST_PAGEBUILDER_URL. 'frontend/js/jquery.magnific-popup.min.js', array('jquery'), false, true);
            wp_enqueue_script('gmap', ST_PAGEBUILDER_URL. 'frontend/js/gmap.js', array('jquery'), false, true);
            wp_enqueue_script('st-contact', ST_PAGEBUILDER_URL. 'frontend/js/st-contact.js', array('jquery'), false, true);
            // For st user
            wp_enqueue_script('blockui.min', ST_PAGEBUILDER_URL. 'frontend/js/jquery.blockui.min.js', array('jquery'), false, true);
            wp_enqueue_script('st-user', ST_PAGEBUILDER_URL. 'frontend/js/st-user.js', array('jquery'), false, true);
            wp_enqueue_script('st-parallax', ST_PAGEBUILDER_URL. 'frontend/js/jquery.parallax.js', array('jquery'), false, true);
            wp_enqueue_script('st-animations', ST_PAGEBUILDER_URL. 'frontend/js/animations.js', array('jquery'), false, true);

            wp_enqueue_script('countto', ST_PAGEBUILDER_URL. 'frontend/js/jquery.countTo.js', array('jquery'), false, true);
            wp_enqueue_script('easypiechart', ST_PAGEBUILDER_URL. 'frontend/js/jquery.easypiechart.js', array('jquery'), false, true);
            wp_enqueue_script('st-pagebuilder', ST_PAGEBUILDER_URL. 'frontend/js/custom.js', array('jquery'), false, true);
            */
            /* ==== END toolkit.min.js === */

            wp_enqueue_script('toolkit', ST_PAGEBUILDER_URL. 'frontend/js/toolkit.min.js', array('jquery'), false, true);

        }
    }

    add_action('wp_enqueue_scripts','st_toolkit_css');
    add_action('wp_enqueue_scripts','st_toolkit_scripts');


}

