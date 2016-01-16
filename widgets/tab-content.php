<?php

class STTabContent extends WP_Widget{

    function STTabContent() {
        $widget_ops = array( 'classname' => 'st-tab-content', 'description' => __('A widget that displays tabs content', 'smooththemes') );
        $control_ops = array( 'width' => 400, 'height' => 0, 'id_base' => 'sttabcontent-widget' );
        $this->WP_Widget( 'sttabcontent-widget', __('ST Tab Content', 'smooththemes'), $widget_ops, $control_ops );

        add_action('admin_footer-widgets.php', array($this, 'st_tab_content_js'));
        add_action('admin_print_styles-widgets.php', array($this, 'st_tab_content_css'));
    }


    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        $instance = wp_parse_args( $instance, array(
            'tab_title'         => array(),
            'tab_content'       => array(),
            'number_item'       => array(),
            'taxonomy'          => array(),
            'txt_content'       => array(),
            'txt_autop'         => array()
        ) );

        echo $before_widget;
        echo '<div class="st-tab-content">';
        if (is_array($instance['tab_title']) && count($instance['tab_title']) > 0) {
            $uni_ar = array();
            echo '<ul class="nav nav-tabs nav-justified">';
            for($i=0; $i<count($instance['tab_title']); $i++) {
                $uni_ar[] = $uni = uniqid();
                $active = ($i==0) ? ' active' : '';
                echo '<li class="tab-title'. $active .'" tab-id="tab-'. $uni .'"><a href="#tab-'. $uni .'" data-toggle="tab">'. $instance['tab_title'][$i] .'</a></li>';
            }
            echo '</ul>';
            echo '<div class="tab-content">';
            for($i=0; $i<count($instance['tab_content']); $i++) {
                $active = ($i==0) ? ' active' : '';
                echo '<div id="tab-'. $uni_ar[$i] .'" tab-id="tab-'. $uni_ar[$i] .'" class="tab-pane'. $active .'">';
                $number = ((int)$instance['number_item'][$i] != 0) ? $instance['number_item'][$i] : 5;
                $args = array();
                $item_instance = array(
                    'number' => $number
                );
                switch (trim($instance['tab_content'][$i])){
                    case 'popular':
                        STPopularPosts::widget($args, $item_instance);
                        break;

                    case 'recent':
                        STRecentPosts::widget($args, $item_instance);
                        break;

                    case 'archive':
                        $args = array(
                            'limit' => $item_instance['number']
                        );
                        echo '<ul class="st-archives">';
                        wp_get_archives( $arg);
                        echo '</ul>';
                        break;

                    case 'comment':
                        STRecentComments::widget($args, $item_instance);
                        break;

                    case 'tag':
                        $args = array(
                            'number' => $number,
                            'taxonomy' => $instance['taxonomy'][$i]
                        );
                        echo '<div class="st-tag">';
                        wp_tag_cloud($args);
                        echo '</div>';
                        break;

                    case 'cat':
                        $args = array(
                            'title_li' => ''
                        );
                        echo '<ul class="st-cat">';
                        wp_list_categories($args);
                        echo '</ul>';
                        break;

                    case 'page':
                        $args = array(
                            'title_li'  => ''
                        );
                        echo '<ul class="st-page">';
                        wp_list_pages($args);
                        echo '</ul>';
                        break;

                    case 'text':
                        $args = array(
                        );
                        echo '<div class="st-page">';
                        if (trim($instance['txt_autop'][$i]) == 'y') {
                            echo do_shortcode(balanceTags(wpautop($instance['txt_content'][$i])));
                        }
                        else {
                            echo do_shortcode(balanceTags($instance['txt_content'][$i]));
                        }
                        echo '</div>';
                        break;

                    default :
                        break;
                }
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( $instance, array(
            'tab_title'         => array(),
            'tab_content'       => array(),
            'number_item'       => array(),
            'taxonomy'          => array(),
            'txt_content'       => array(),
            'txt_autop'         => array()
        ) );
        ?>
        <div class="st-tab-content">
            <div class="tabs-content st-tab-sortable">
                <?php
                $count = (count($instance['tab_title']) > 0) ? count($instance['tab_title']) : 1;
                if ($count > 0) {
                    for($i=0; $i<$count; $i++) {
                        ?>
                        <div class="tab-item <?php echo ($instance['tab_content'][$i] != '') ? $instance['tab_content'][$i] : 'popular'; ?>">
                            <a href="#" class="st-remove-tab"><?php _e('X', 'smooththemes') ?></a>
                            <p>
                                <label for=""><?php _e('Tab Title','smooththemes'); ?></label>
                                <br/>
                                <input type="text" name="<?php echo $this->get_field_name('tab_title') ?>[]" id="<?php echo $this->get_field_id('tab_title') ?>" class="widefat" value="<?php echo $instance['tab_title'][$i] ?>"/>
                            </p>
                            <p>
                                <label for=""><?php _e('Tab Content','smooththemes'); ?></label>
                                <br/>
                                <select name="<?php echo $this->get_field_name('tab_content') ?>[]" id="<?php echo $this->get_field_id('tab_content') ?>" class="st-tab-content widefat">
                                    <?php
                                    $tab_content_ar = array(
                                        'popular' => __('Popular', 'smooththemes'),
                                        'recent' => __('Recent', 'smooththemes'),
                                        'archive' => __('Archive', 'smooththemes'),
                                        'comment' => __('Comment', 'smooththemes'),
                                        'cat' => __('Categories', 'smooththemes'),
                                        'tag' => __('Tag', 'smooththemes'),
                                        'page' => __('Page', 'smooththemes'),
                                        'text' => __('Text', 'smooththemes')
                                    );
                                    foreach($tab_content_ar as $k => $v) {
                                        echo '<option value="'. $k .'" '. selected( $instance['tab_content'][$i], $k, false ) .'>'. $v .'</option>';
                                    }
                                    ?>
                                </select>
                            </p>
                            <p class="st-text-content">
                                <label for=""><?php _e('Text Content','smooththemes'); ?></label>
                                <br/>
                                <textarea rows="16" class="widefat" id="<?php echo $this->get_field_id('txt_content'); ?>" name="<?php echo $this->get_field_name('txt_content'); ?>[]"><?php echo $instance['txt_content'][$i]; ?></textarea>
                            </p>
                            <p class="st-text-content">
                                <input type="checkbox" <?php checked('y', $instance['txt_autop'][$i]); ?> value="y" name="" class="st-checkbox-autop" />&nbsp;<label for=""><?php _e('Automatically add paragraphs','smooththemes'); ?></label>
                                <input class="st-value-autop" type="hidden" name="<?php echo $this->get_field_name('txt_autop'); ?>[]" value="<?php echo ($instance['txt_autop'][$i]) ? $instance['txt_autop'][$i] : 'n'; ?>" />
                            </p>
                            <p class="st-taxonomy">
                                <label for=""><?php _e('Taxonomy','smooththemes'); ?></label>
                                <br/>
                                <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>[]">
                                    <?php foreach ( get_taxonomies() as $taxonomy ) :
                                        $tax = get_taxonomy($taxonomy);
                                        if ( !$tax->show_tagcloud || empty($tax->labels->name) )
                                            continue;
                                        ?>
                                        <option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $instance['taxonomy'][$i]) ?>><?php echo $tax->labels->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </p>
                            <p class="st-number">
                                <label for=""><?php _e('Number Item','smooththemes'); ?></label>
                                <br/>
                                <input type="text" name="<?php echo $this->get_field_name('number_item') ?>[]" id="<?php echo $this->get_field_id('number_item') ?>" class="widefat" value="<?php echo $instance['number_item'][$i] ?>"/>
                            </p>
                        </div>
                    <?php
                    }
                }
                ?>

                <div class="tab-item-default">
                    <div class="tab-item popular">
                        <a href="#" class="st-remove-tab"><?php _e('X', 'smooththemes') ?></a>
                        <p>
                            <label for="<?php echo $this->get_field_id('tab_title') ?>"><?php _e('Tab Title','smooththemes'); ?></label>
                            <br/>
                            <input type="text" name="" id="<?php echo $this->get_field_id('tab_title') ?>" class="st-tab-title widefat" value=""/>
                        </p>
                        <p>
                            <label for="<?php echo $this->get_field_id('tab_content') ?>"><?php _e('Tab Content','smooththemes'); ?></label>
                            <br/>
                            <select name="" id="<?php echo $this->get_field_id('tab_content') ?>" class="st-tab-content widefat">
                                <?php
                                $tab_content_ar = array(
                                    'popular' => __('Popular', 'smooththemes'),
                                    'recent' => __('Recent', 'smooththemes'),
                                    'archive' => __('Archive', 'smooththemes'),
                                    'comment' => __('Comment', 'smooththemes'),
                                    'cat' => __('Categories', 'smooththemes'),
                                    'tag' => __('Tag', 'smooththemes'),
                                    'page' => __('Page', 'smooththemes'),
                                    'text' => __('Text', 'smooththemes')
                                );
                                foreach($tab_content_ar as $k => $v) {
                                    echo '<option value="'. $k .'">'. $v .'</option>';
                                }
                                ?>
                            </select>
                        </p>
                        <p class="st-text-content">
                            <label for=""><?php _e('Text Content','smooththemes'); ?></label>
                            <br/>
                            <textarea rows="16" class="st-area-content widefat" id="<?php echo $this->get_field_id('txt_content'); ?>" name=""><?php echo $instance['txt_content'][$i]; ?></textarea>
                        </p>
                        <p class="st-text-content">
                            <input type="checkbox" <?php checked('y', $instance['txt_autop'][$i]); ?> value="y" name="" class="st-checkbox-autop" />&nbsp;<label for=""><?php _e('Automatically add paragraphs','smooththemes'); ?></label>
                            <input class="st-value-autop" type="hidden" name="" value="<?php echo ($instance['txt_autop'][$i]) ? $instance['txt_autop'][$i] : 'n'; ?>" />
                        </p>
                        <p class="st-taxonomy">
                            <label for="<?php echo $this->get_field_id('taxonomy') ?>"><?php _e('Taxonomy','smooththemes'); ?></label>
                            <br/>
                            <select class="st-select-taxonomy widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>[]">
                                <?php foreach ( get_taxonomies() as $taxonomy ) :
                                    $tax = get_taxonomy($taxonomy);
                                    if ( !$tax->show_tagcloud || empty($tax->labels->name) )
                                        continue;
                                    ?>
                                    <option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <p class="st-number">
                            <label for="<?php echo $this->get_field_id('number_item') ?>"><?php _e('Number Item','smooththemes'); ?></label>
                            <br/>
                            <input type="text" name="<?php echo $this->get_field_name('number_item') ?>[]" id="<?php echo $this->get_field_id('number_item') ?>" class="st-number-item widefat" value="<?php echo $instance['number_item'][$i] ?>"/>
                        </p>
                    </div>
                </div>
            </div>

            <p>
                <a href="#" class="st-add-new-tab"><?php _e('+', 'smooththemes'); ?></a>
            </p>
            <i><?php _e('Drag item to sort.', 'smooththemes'); ?></i>

            <input type="hidden" class="name_tab_title" value="<?php echo $this->get_field_name('tab_title') ?>[]" >
            <input type="hidden" class="name_tab_content" value="<?php echo $this->get_field_name('tab_content') ?>[]" >
            <input type="hidden" class="name_number_item" value="<?php echo $this->get_field_name('number_item') ?>[]" >
            <input type="hidden" class="name_taxonomy" value="<?php echo $this->get_field_name('name_taxonomy') ?>[]" >
            <input type="hidden" class="name_txt_content" value="<?php echo $this->get_field_name('txt_content') ?>[]" >
            <input type="hidden" class="name_txt_autop" value="<?php echo $this->get_field_name('txt_autop') ?>[]" >

        </div>

    <?php
    }

    function st_tab_content_js() {
        if (is_admin()) {
            ?>
            <script type='text/javascript'>
                /* <![CDATA[ */
                jQuery(document).ready(function() {
                    jQuery('.st-tab-content .st-tab-sortable').sortable();

                    <?php /*
                    var name_tab_title = '<?php echo $this->get_field_name('tab_title') ?>[]';
                    var name_tab_content = '<?php echo $this->get_field_name('tab_content') ?>[]';
                    var name_number_item = '<?php echo $this->get_field_name('number_item') ?>[]';
                    var name_taxonomy = '<?php echo $this->get_field_name('name_taxonomy') ?>[]';
                    var name_txt_content = '<?php echo $this->get_field_name('txt_content') ?>[]';
                    var name_txt_autop = '<?php echo $this->get_field_name('txt_autop') ?>[]';
                    */ ?>


                    jQuery('.st-tab-content .st-remove-tab').live('click', function() {
                        jQuery(this).parents('.tab-item').remove();
                        return false;
                    });

                    jQuery('.st-tab-content .st-add-new-tab').live('click', function() {
                        var  p = jQuery(this).parents('.st-tab-content ');

                        var name_tab_title = jQuery('.name_tab_title', p).val();
                        var name_tab_content = jQuery('.name_tab_content', p).val();
                        var name_number_item = jQuery('.name_number_item', p).val();
                        var name_taxonomy = jQuery('.name_taxonomy', p).val();
                        var name_txt_content = jQuery('.name_txt_content' , p).val();
                        var name_txt_autop = jQuery('.name_txt_autop', p).val();

                        var tab = jQuery(this).parents('.st-tab-content').find('.tab-item-default').clone();

                        tab.find('.st-tab-title').attr('name', name_tab_title);
                        tab.find('.st-tab-content').attr('name', name_tab_content);
                        tab.find('.st-select-taxonomy').attr('name', name_taxonomy);
                        tab.find('.st-area-content').attr('name', name_txt_content);
                        tab.find('.st-checkbox-autop').attr('name', name_txt_autop);
                        tab.find('.st-number-item').attr('name', name_number_item);
                        jQuery(this).parents('.st-tab-content').find('.tabs-content').append(tab.html());

                        return false;
                    });

                    jQuery('.st-tab-content .st-tab-content').live('change', function() {
                        var class_name = jQuery(this).val();
                        jQuery(this).parents('.tab-item').removeClass('popular recent archive comment cat tag page text').addClass(class_name);
                        return false;
                    });

                    jQuery('.st-tab-content .st-checkbox-autop').live('click', function() {
                        if (jQuery(this).is(':checked')) {
                            jQuery(this).parents('.st-tab-content').find('.st-value-autop').val('y');
                        }
                        else {
                            jQuery(this).parents('.st-tab-content').find('.st-value-autop').val('n');
                        }
                    });
                });

                jQuery(document).ajaxSuccess(function(e, xhr, settings) {
                    jQuery('.st-tab-content .st-tab-sortable').sortable();
                });
                /* ]]> */
            </script>
        <?php
        }
    }

    function st_tab_content_css() {
        ?>
        <style type="text/css">
            .st-tab-content {}
            .st-tab-content .st-taxonomy {display: none;}
            .st-tab-content .tag .st-taxonomy {display: block;}
            .st-tab-content .st-text-content {display: none;}
            .st-tab-content .text .st-text-content {display: block;}
            .st-tab-content .st-number {display: none;}
            .st-tab-content .popular .st-number,
            .st-tab-content .recent .st-number,
            .st-tab-content .comment .st-number{display: block;}

            .st-tab-content .archive .st-number {display: none;}

            .st-tab-content .tab-item {
                border: 1px solid #ccc; padding: 10px; margin: 10px 0px;position: relative; background: #F5F5F5;
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
            }
            .st-tab-content .ui-sortable-helper {background: #f2ffdf;}
            .st-tab-content .tab-item .st-remove-tab {position: absolute; top: 0px; right: 5px; text-decoration: none; font-weight: bold; font-size: 15px; color: red;}
            .st-tab-content .tab-item-default {display: none;}
            .st-tab-content .st-add-new-tab {text-decoration: none; font-weight: bold; font-size: 20px; border: 1px solid #ccc; display: inline-block; padding: 7px;}

            .st-tab-content label{font-weight: bold;  margin-bottom: 5px;}
        </style>

    <?php
    }
}



// register Foo_Widget widget
function register_STTabContent() {
    if(current_theme_supports('st-widgets','tab-content')){
        register_widget( 'STTabContent' );
    }
}
add_action( 'widgets_init', 'register_STTabContent' );