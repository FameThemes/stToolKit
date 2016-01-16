<?php

if (!function_exists('stpb_blog')) {
    function stpb_blog($pre_name ='', $data_values=  array(), $post= false, $no_value = false,  $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_categories($pre_name.'[cats]',$data_values['cats']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Which categories should be used for the blog?','smooththemes') ?></strong>
                <span><?php _e('Which categories should be used for the blog? You can select multiple categories here. The Page will then show posts from only those categories.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[number]',$data_values['number']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Number','smooththemes') ?></strong>
                <span><?php _e('How many post you want to display ?','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php

                stpb_input_select_one($pre_name.'[display_style]',$data_values['display_style'], array(
                        'list'=>__('List','smooththemes'),
                        'gird'=>__('Grid','smooththemes'),
                    )
                    ,'.blog_display_style'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Display style','smooththemes') ?></strong>
                <span><?php _e('Select list or gird for list post','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item show-on-select-change blog_display_style" show-on="gird">
            <div class="left width-50">
                <?php
                $layouts =  array();
                foreach(array(1,2,3,4) as $k){
                    $layouts[$k] = sprintf(_n('%d Column','%d Columns', $k,'smooththemes'), $k);
                }
                stpb_input_select_one($pre_name.'[columns]',$data_values['columns'], $layouts); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Layout','smooththemes') ?></strong>
                <span><?php _e('Select layout for list post','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item show-on-select-change blog_display_style" show-on="list">
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[thumbnail_type]',$data_values['thumbnail_type'], array(
                        'full-width'=>__('Full with','smooththemes'),
                        'medium-left'=>__('Medium left','smooththemes'),
                        'medium-right'=>__('Medium right','smooththemes'),
                    )
                    ); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Thumbnail Type','smooththemes') ?></strong>
                <span><?php _e('Select thumbnail type for list post','smooththemes'); ?></span>
            </div>
        </div>



        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[exclude]',$data_values['exclude']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Exclude','smooththemes') ?></strong>
                <span><?php _e('Define a comma-separated list of post IDs to be Exclude from the list, (example: 3,7,31 )','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[include]',$data_values['include']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Include','smooththemes') ?></strong>
                <span><?php _e('Define a comma-separated list of post IDs to be Include from the list, (example: 3,7,31 )','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[offset]',$data_values['offset']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Offset','smooththemes') ?></strong>
                <span><?php _e('The number of Posts to pass over (or displace) before collecting the set of Posts.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                if($data_values['excerpt_length']==='' ||  empty($data_values['excerpt_length']) || !is_numeric($data_values['excerpt_length']) ){
                    $data_values['excerpt_length'] = apply_filters('stpb_bog_excerpt_length',17 );
                }else{
                    $data_values['excerpt_length'] = intval($data_values['excerpt_length']);
                }

                stpb_input_text($pre_name.'[excerpt_length]',$data_values['excerpt_length']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Excerpt Length','smooththemes') ?></strong>
                <span><?php _e('The number of words you wish to display in the excerpt','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[pagination]',$data_values['pagination'], array(
                    'no'=>__('No','smooththemes'),
                    'yes'=>__('Yes','smooththemes'),
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Pagination','smooththemes') ?></strong>
                <span><?php _e('Should a pagination be displayed?.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[order_by]',$data_values['order_by'], array(
                    'post_date'=>__('Sort by creation time','smooththemes'),
                    'post_title'=>__('Sort Posts alphabetically (by title) ','smooththemes'),
                    'rand'=>__('Random','smooththemes'),
                    'ID'=>__('Sort by numeric Page ID','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Sort By','smooththemes') ?></strong>
                <span><?php _e('Sorts the list of Post in a number of different ways.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[order]',$data_values['order'], array(
                    'desc'=>__('Descending','smooththemes'),
                    'asc'=>__('Ascending','smooththemes')

                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Sort Order ','smooththemes') ?></strong>
                <span><?php _e('Change the sort order of the list of Post.','smooththemes'); ?></span>
            </div>
        </div>
    <?php
    }
}

if (!function_exists('stpb_widget')) {
    function stpb_widget($pre_name ='', $data_values=  array(), $post= false, $no_value = false,  $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[sidebar]',$data_values['sidebar'], $interface->get_sidebar_widgets()); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Sidebar Name','smooththemes') ?></strong>
                <span><?php _e('Select Sidebar you want display.','smooththemes'); ?></span>
            </div>
        </div>


    <?php
    }
}

if (!function_exists('stpb_text')) {
    function stpb_text($pre_name ='', $data_values=  array(), $post= false, $no_value = false,  $interface= false){
        ?>
        <div class="item">
            <strong><?php _e('Content','smooththemes') ?></strong>
            <span><?php _e('Enter some content for this textblock','smooththemes'); ?></span>
            <?php stpb_input_textarea($pre_name.'[text]',apply_filters('the_content', $data_values['text'])); ?>
            <?php /*
            <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
            <p><label><?php stpb_input_checkbox($pre_name.'[autop]',$data_values['autop'],1); ?>&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>
            */ ?>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php stpb_input_color($pre_name.'[color]',$data_values['color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Text Color','smooththemes') ?></strong>
                <span><?php _e('Special text color for this text block','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[align]',$data_values['align'], array(
                    'default'=>__('Default','smooththemes'),
                    'center'=>__('Center','smooththemes'),
                    'left'=>__('Left','smooththemes'),
                    'right'=>__('Right','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Text Align','smooththemes') ?></strong>
                <span><?php _e('Choose the alignment of your Text here.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php stpb_input_effect($pre_name.'[effect]',$data_values['effect']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Effect','smooththemes') ?></strong>
                <span><?php _e('Special Effect for this text block','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}

if (!function_exists('stpb_map')) {
    function stpb_map($pre_name ='', $data_values=  array(), $post= false, $no_value = false,  $interface= false){
        ?>

        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[address]', $data_values['address'], array(
                    'title'=>true,
                    'content'=>true,
                    'image'=>false,
                    'icon'=>false
                ), array(
                    'title'=>__('Adress:','smooththemes'),
                   // 'image'=>__('Image:','smooththemes')
                ),array(
                    array(
                        'title'=>__('Latitude','smooththemes'),
                        'type'=>'text',
                        'name'=>'lat'
                    ),
                    array(
                        'title'=>__('Longitude','smooththemes'),
                        'type'=>'text',
                        'name'=>'lng'
                    )
                ));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Addresses','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Addresses you want to display. Each Item requered Latitude and Longitude for your address. You can find the Latitude and Longitude <a target="_blank" href="http://itouchmap.com/latlong.html">Here</a> or <a target="_blank" href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm">Here</a> ','smooththemes') ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                if($data_values['zoom']==''){
                    $data_values['zoom'] = 9;
                }
                stpb_input_text($pre_name.'[zoom]',$data_values['zoom']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Zoom level','smooththemes') ?></strong>
                <span><?php _e('The range depends on where you are looking at. Some places only have zoom levels of 15 or so, where as other places have 23 (or possibly more). O is smallest.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                if($data_values['height']==''){
                    $data_values['height'] = 300;
                }
                stpb_input_text($pre_name.'[height]',$data_values['height']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Height','smooththemes') ?></strong>
                <span><?php _e('Map height in pixel.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php stpb_input_color($pre_name.'[color]',$data_values['color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Color','smooththemes') ?></strong>
                <span><?php _e('Special color for this Map','smooththemes'); ?></span>
            </div>
        </div>
        <?php /*
        <div class="item">
            <strong><?php _e('Map Desciptions','smooththemes') ?></strong>
            <span><?php _e('Enter some content for this Map','smooththemes'); ?></span>
            <?php stpb_input_textarea($pre_name.'[desc]',$data_values['desc']); ?>
            <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
            <p><label><?php stpb_input_checkbox($pre_name.'[desc_autop]',$data_values['desc_autop'],1); ?>&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>
        </div>
        */ ?>
    <?php
    }
}


if (!function_exists('stpb_heading')) {
    function stpb_heading($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>

        <div class="item">
            <strong><?php _e('Heading text','smooththemes') ?></strong>
            <?php  stpb_input_text($pre_name.'[heading]',$data_values['heading']); ?>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[type]',$data_values['type'], array(
                    'h1'=>'H1',
                    'h2'=>'H2',
                    'h3'=>'H3',
                    'h4'=>'H4',
                    'h5'=>'H5',
                    'h6'=>'H6',
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Heading type','smooththemes') ?></strong>
                <span><?php _e('Select a heading style.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[align]',$data_values['align'], array(
                    'default'=>__('Default','smooththemes'),
                    'center'=>__('Center','smooththemes'),
                    'left'=>__('Left','smooththemes'),
                    'right'=>__('Right','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Text Align','smooththemes') ?></strong>
                <span><?php _e('Choose the alignment of your Heading here.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[color]',$data_values['color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Text color','smooththemes') ?></strong>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[padding_top]',$data_values['padding_top']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Padding Top','smooththemes') ?></strong>
                <span><?php _e('Top Padding in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[padding_bottom]',$data_values['padding_bottom']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Padding Bottom','smooththemes') ?></strong>
                <span><?php _e('Bottom Padding in pixel.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[margin_top]',$data_values['margin_top']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Margin Top','smooththemes') ?></strong>
                <span><?php _e('Top margin in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[margin_bottom]',$data_values['margin_bottom']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Margin Bottom','smooththemes') ?></strong>
                <span><?php _e('Margin Padding in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[custom_class]',$data_values['custom_class']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Custom class','smooththemes') ?></strong>
                <span><?php _e('Class name for your own style.','smooththemes'); ?></span>
            </div>
        </div>
    <?php
    }
}

if (!function_exists('stpb_tabs')) {
    function stpb_tabs($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="tabs-builder-act">
            <div class="item">
                <div class="left width-50">
                    <?php  stpb_input_select_one($pre_name.'[tab_position]',$data_values['tab_position'], array(
                        'top'=>__('Display tabs at the top','smooththemes'),
                        'left'=>__('Display Tabs on the left','smooththemes'),
                        'right'=>__('Display Tabs on the right','smooththemes')
                    ),'','st-change-tabs-pos'); ?>
                </div>
                <div class="right  width-50">
                    <strong><?php _e('Tab Position','smooththemes') ?></strong>
                    <span><?php _e('Where should the tabs be displayed.','smooththemes'); ?></span>
                </div>
            </div>

            <div class="item">
                <strong><?php _e('Add/Edit Tabs','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Tabs you want to display.','smooththemes') ?></span>
                <?php
                stpb_input_tabs($pre_name."[tabs]", $data_values['tabs']);
                ?>
            </div>
        </div>



        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[initial_open]',$data_values['initial_open']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Initial Open','smooththemes') ?></strong>
                <span><?php _e('Enter the Number of the Tab that should be open initially.','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}

if (!function_exists('stpb_toggle')) {
    function stpb_toggle($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[toggle]', $data_values['toggle'], array('title'=>true,'content'=> true,'icon'=> true,'image'=> false));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Toggle','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Toggles you want to display.','smooththemes') ?></span>
            </div>

        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[initial_open]',$data_values['initial_open']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Initial Open','smooththemes') ?></strong>
                <span><?php _e('Enter the Number of the Tab that should be open initially.','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}

if (!function_exists('stpb_accordion')) {
    function stpb_accordion($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[accordion]', $data_values['accordion'], array('title'=>true,'content'=> true,'icon'=> true,'image'=> false));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Accordion','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Accordions you want to display.','smooththemes') ?></span>
            </div>

        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[initial_open]',$data_values['initial_open']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Initial Open','smooththemes') ?></strong>
                <span><?php _e('Enter the Number of the Tab that should be open initially.','smooththemes'); ?></span>
            </div>
        </div>
    <?php
    }
}

if (!function_exists('stpb_testimonials')) {
    function stpb_testimonials($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[testimonials]', $data_values['testimonials'], array(
                    'title'=>true,
                    'content'=>true,
                    'image'=>true,
                    'icon'=>false
                ), array(
                    'title'=>__('Name:','smooththemes'),
                    'image'=>__('Image:','smooththemes')
                ),array(
                    array(
                        'title'=>__('Subtitle/Job description','smooththemes'),
                        'type'=>'text',
                        'name'=>'subtitle'
                    )
                ));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Testimonials','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Testimonials you want to display.','smooththemes') ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[style]',$data_values['style'], array(
                    'list'=>__('Testimonial list','smooththemes'),
                    'slider'=>__('Testimonial Slider','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Testimonial Style','smooththemes') ?></strong>
                <span><?php _e('Here you can select how to display the testimonials.','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}

if (!function_exists('stpb_notification')) {
    function  stpb_notification($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php stpb_input_textarea($pre_name.'[message]',$data_values['message']); ?>
                <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
                <p><label><?php stpb_input_checkbox($pre_name.'[autop]',$data_values['autop'],1); ?>&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>
            </div>
            <div class="right width-50">
                <strong><?php _e('Message','smooththemes') ?></strong>
                <span><?php _e('This is the text that appears in your Notification.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[type]',$data_values['type'], array(
                    'warning'=>__('Notification','smooththemes'),
                    'info'=>__('Info','smooththemes'),
                    'success'=>__('Success','smooththemes'),
                    'danger'=>__('Error','smooththemes'),
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Message Type','smooththemes') ?></strong>
                <span><?php _e('Choose the Message Type for your Box here.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <strong><?php _e('Icon','smooththemes') ?></strong>
            <span><?php _e('Select your icon.','smooththemes'); ?></span>
            <?php
            stpb_input_icon_popup($pre_name.'[icon]',$data_values['icon']);
            ?>
        </div>

    <?php
    }
}


if (!function_exists('stpb_divider')) {
    function stpb_divider($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[divider_type]',$data_values['divider_type'], array(
                    'space'=>__('While Space','smooththemes'),
                    'border'=>__('Line','smooththemes')
                ),'.st-divider-custom'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Divider Styling','smooththemes') ?></strong>
                <span><?php _e('Here you can set the styling and size of the Divider element.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item show-on-select-change st-divider-custom" show-on="space" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[height]',$data_values['height']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Divider Height','smooththemes') ?></strong>
                <span><?php _e('Divider height in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item show-on-select-change st-divider-custom" show-on="border" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[margin_top]',$data_values['margin_top']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Divider Margin Top','smooththemes') ?></strong>
                <span><?php _e('Divider margin top in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item show-on-select-change st-divider-custom" show-on="border" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[margin_bottom]',$data_values['margin_bottom']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Divider Margin Bottom','smooththemes') ?></strong>
                <span><?php _e('Divider margin bottom in pixel.','smooththemes'); ?></span>
            </div>
        </div>



    <?php
    }
}

if (!function_exists('stpb_icon_list')) {
    function  stpb_icon_list($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[icon_list]', $data_values['icon_list'], array(
                    'title'=>false,
                    'content'=>true,
                    'image'=>false,
                    'icon'=>true
                ), array(
                    'title'=>__('List title:','smooththemes'),
                    'icon'=>__('Icon:','smooththemes')
                ));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit List Items','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the items of your item list.','smooththemes') ?></span>
            </div>

        </div>


        <div class="item" ">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[color_type]',$data_values['color_type'], array(
                    'default'=>__('Default- Inherit form theme settings','smooththemes'),
                    'custom'=>__('Custom','smooththemes'),
                ),'.icon_color_type'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Icon Color','smooththemes') ?></strong>
            </div>
        </div>

         <div class="item show-on-select-change icon_color_type" show-on="custom">
             <div class="left width-50">
                 <?php  stpb_input_color($pre_name.'[color]',$data_values['color']); ?>
             </div>
             <div class="right  width-50">
                 <strong><?php _e('Icon Custom Color','smooththemes') ?></strong>
             </div>
         </div>



    <?php
    }
}

if (!function_exists('stpb_clients')) {
    function stpb_clients($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[clients]', $data_values['clients'], array(
                    'title'=>true,
                    'content'=>false,
                    'image'=>true,
                    'icon'=>false
                ), array(
                    'title'=>__('Client:','smooththemes'),
                    'image'=>__('Image:','smooththemes')
                ),array(
                    array(
                        'title'=>__('Website URL','smooththemes'),
                        'type'=>'text',
                        'name'=>'url'
                    )
                ));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Clients','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Clients you want to display.','smooththemes') ?></span>
            </div>

        </div>


        <div class="item">
            <div class="left width-50">
                <?php
                if(!isset($data_values['visible_items']) || $data_values['visible_items'] ==''){
                    $data_values['visible_items'] = 3;
                }
                stpb_input_select_one($pre_name.'[visible_items]',$data_values['visible_items'],  array('1'=>'1','2'=>'2',3=>3,4=>4, 6=>6)); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Visible items.','smooththemes') ?></strong>
                <span><?php _e('The number of visible items.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[link_target]',$data_values['link_target'], array(
                    '_self'=>__('No, open in same window','smooththemes'),
                    '_blank'=>__('Yes, open in new window','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Open Link in new Window?','smooththemes') ?></strong>
                <span><?php _e('Select here if you want to open the linked page in a new window.','smooththemes'); ?></span>
            </div>
        </div>
    <?php
    }
}

if (!function_exists('stpb_gallery')) {
    function stpb_gallery($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php stpb_input_media($pre_name.'[gallery]',$data_values['gallery'],'gallery', __('Add/Edit Gallery','smooththemes') ); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Gallery','smooththemes') ?></strong>
                <span><?php _e('Create a new Gallery by selecting existing or uploading new images','smooththemes'); ?></span>

            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[size]',$data_values['size'], $interface->list_thumbnail_sizes()); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Preview Image Size','smooththemes') ?></strong>
                <span><?php _e('Choose image size for the preview thumbnails.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                if(!isset($data_values['columns']) || $data_values['columns'] ==''){
                    $data_values['columns'] = 3;
                }
                stpb_input_select_one($pre_name.'[columns]',$data_values['columns'],  array('1'=>'1','2'=>'2',3=>3,4=>4, 6=>6)); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Columns','smooththemes') ?></strong>
                <span><?php _e('Choose the column count of your Gallery.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[lightbox]',$data_values['lightbox'],  array('yes'=>__('Yes','smooththemes'), 'no'=>__('No','smooththemes'))); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Use Lighbox','smooththemes') ?></strong>
                <span><?php _e('Do you want to activate the lightbox.','smooththemes'); ?></span>
            </div>
        </div>


    <?php
    }
}

if (!function_exists('stpb_image')) {
    function stpb_image($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php stpb_input_media($pre_name.'[image]',$data_values['image'],'image', __('Insert Image','smooththemes') ); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Choose Image','smooththemes') ?></strong>
                <span><?php _e('Either upload a new, or choose an existing image from your media library','smooththemes'); ?></span>

            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[size]',$data_values['size'], $interface->list_thumbnail_sizes()); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Preview Image Size','smooththemes') ?></strong>
                <span><?php _e('Choose image size for the preview thumbnails.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php stpb_input_effect($pre_name.'[effect]',$data_values['effect']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Effect','smooththemes') ?></strong>
                <span><?php _e('Special Effect for this Image','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[position]',$data_values['position'], array(
                    'center'=>__('Center','smooththemes'),
                    'left'=>__('Left','smooththemes'),
                    'right'=>__('Right','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Image Position','smooththemes') ?></strong>
                <span><?php _e('Choose the alignment of your Image here.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[link_type]',$data_values['link_type'], array(
                    'none'=>__('None','smooththemes'),
                    'lightbox'=>__('Lightbox','smooththemes'),
                    'link'=>__('Link','smooththemes')
                ),'.image_link_type'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Use Lightbox or Link?','smooththemes') ?></strong>
                <span><?php _e('Choose Lightbox or static link?','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item show-on-select-change image_link_type" show-on="link">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[link]',$data_values['link']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Image Link?','smooththemes') ?></strong>
                <span><?php _e('Where should your image link to?','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item show-on-select-change image_link_type" show-on="link">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[link_target]',$data_values['link_target'], array(
                    '_self'=>__('No, open in same window','smooththemes'),
                    '_blank'=>__('Yes, open in new window','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Open Link in new Window?','smooththemes') ?></strong>
                <span><?php _e('Select here if you want to open the linked page in a new window.','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}

if (!function_exists('stpb_button')) {
    function stpb_button($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[button_label]',$data_values['button_label']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Button Label','smooththemes') ?></strong>
                <span><?php _e('This is the text that appears on your button.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_link($pre_name.'[link]',$data_values['link']);
                  /* stpb_input_text($pre_name.'[link]',$data_values['link']); */ ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Button Link?','smooththemes') ?></strong>
                <span><?php _e('Where should your Button link to?','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[style]',$data_values['style'], array(
                    'default'=>__('Default','smooththemes'),
                    'primary'=>__('Primary','smooththemes'),
                    'success'=>__('Success','smooththemes'),
                    'info'=>__('Info','smooththemes'),
                    'warning'=>__('Warning','smooththemes'),
                    'danger'=>__('Danger','smooththemes'),
                    'color'=>__('Color - Inherit from theme settings','smooththemes'),
                    //'custom'=>__('Custom color','smooththemes')
                ),'.sl-button-color'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Button Style','smooththemes') ?></strong>
                <span><?php _e('Choose the style of your Button here.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
        <p><label><?php stpb_input_checkbox($pre_name.'[is_block]',$data_values['is_block'],1); ?>&nbsp;<?php _e('Is block button ?','smooththemes') ?></label></p>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[margin_top]',$data_values['margin_top']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Margin Top','smooththemes') ?></strong>
                <span><?php _e('Top margin in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[margin_bottom]',$data_values['margin_bottom']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Margin Bottom','smooththemes') ?></strong>
                <span><?php _e('Margin Padding in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <?php /*
        <div  class="item show-on-select-change sl-button-color" show-on="custom">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[custom_bg_color]',$data_values['custom_bg_color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Button Background Color','smooththemes') ?></strong>
            </div>
        </div>

        <div  class="item show-on-select-change sl-button-color" show-on="custom">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[custom_label_color]',$data_values['custom_label_color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Button Label Color','smooththemes') ?></strong>
            </div>
        </div>

        */?>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[size]',$data_values['size'], array(
                    'btn-normal'=>__('Normal','smooththemes'),
                    'btn-sm'=>__('Small','smooththemes'),
                    'btn-lg'=>__('Large','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Button Size','smooththemes') ?></strong>
            </div>
        </div>



        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[position]',$data_values['position'], array(
                    'default'=>__('Default','smooththemes'),
                    'center'=>__('Center','smooththemes'),
                    'left'=>__('Left','smooththemes'),
                    'right'=>__('Right','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Button Position','smooththemes') ?></strong>
                <span><?php _e('Choose the alignment of your Button here.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[link_target]',$data_values['link_target'], array(
                    '_self'=>__('No, open in same window','smooththemes'),
                    '_blank'=>__('Yes, open in new window','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Open Link in new Window?','smooththemes') ?></strong>
                <span><?php _e('Select here if you want to open the linked page in a new window.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <strong><?php _e('Icon','smooththemes') ?></strong>
            <span><?php _e('Select icon for your Button.','smooththemes'); ?></span>
            <?php
            stpb_input_icon_popup($pre_name.'[icon]',$data_values['icon']);
            ?>
        </div>

    <?php
    }
}

if (!function_exists('stpb_video')) {
    function stpb_video($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[video]',$data_values['video']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Video','smooththemes'); ?></strong>
                <span><?php _e('Link to a video by URL.<p>Working examples:<br/> http://vimeo.com/18439821<br/>http://www.youtube.com/watch?v=l9Fi3E-8PmA</p>','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[ratio]',$data_values['ratio'], array(
                    '16:9'=>__('16:9','smooththemes'),
                    '4:3'=>__('4:3','smooththemes'),
                    'custom'=> __('Custom Ratio','smooththemes')
                ),'.video-ratio'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Video Format','smooththemes') ?></strong>
                <span><?php _e('Choose if you want to display a modern 16:9 or classic 4:3 Video, or use a custom ratio.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item show-on-select-change video-ratio" show-on="custom" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[width]',$data_values['width']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Video width','smooththemes') ?></strong>
                <span><?php _e('Enter a value for the width','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item show-on-select-change video-ratio" show-on="custom">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[height]',$data_values['height']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Video height','smooththemes') ?></strong>
                <span><?php _e('Enter a value for the height','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}

if (!function_exists('stpb_iconbox')) {
    function stpb_iconbox($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[title]',$data_values['title']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Title','smooththemes') ?></strong>
                <span><?php _e('Enter something.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_textarea($pre_name.'[content]',$data_values['content']); ?>
                <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
                <p><label><?php stpb_input_checkbox($pre_name.'[autop]',$data_values['autop'],1); ?>&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Content','smooththemes') ?></strong>
                <span><?php _e('Enter something.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item show-on-select-change sl-sr-icontypes" show-on="icon">
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[text_align]',$data_values['text_align'], array(
                    'left'=>__('Left','smooththemes'),
                    'center'=>__('Center','smooththemes'),
                    'right'=>__('right','smooththemes')
                ));
                ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Text algin','smooththemes') ?></strong>
            </div>
        </div>


        <div  class="item" show-on="">
            <strong><?php _e('Icon','smooththemes') ?></strong>
            <?php  stpb_input_icon_popup($pre_name.'[icon]',$data_values['icon']); ?>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[icon_size]',$data_values['icon_size'], array(
                    'small'=>__('Small','smooththemes'),
                    'medium'=>__('Medium','smooththemes'),
                    'large'=>__('Large','smooththemes')
                ));
                ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Icon Size','smooththemes') ?></strong>
                <span><?php _e('Select Icon size to display ?','smooththemes'); ?></span>
            </div>
        </div>


         <div class="item">
             <div class="left width-50">
                 <?php stpb_input_effect($pre_name.'[effect]',$data_values['effect']); ?>
             </div>
             <div class="right  width-50">
                 <strong><?php _e('Effect','smooththemes') ?></strong>
                 <span><?php _e('Special Effect for this Icon','smooththemes'); ?></span>
             </div>
         </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[icon_position]',$data_values['icon_position'], array(
                    'top'=>__('Top','smooththemes'),
                    'left'=>__('Left','smooththemes'),
                    'right'=>__('Right','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Icon Position','smooththemes') ?></strong>
                <span><?php _e('Where the Icon display ?','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[color_type]',$data_values['color_type'], array(
                    'default'=>__('Default- Inherit form theme settings','smooththemes'),
                    'custom'=>__('Custom','smooththemes'),
                ),'.icon_color_type'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Icon Color','smooththemes') ?></strong>
            </div>
        </div>

         <div class="item show-on-select-change icon_color_type" show-on="custom">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[color]',$data_values['color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Icon Custom Color','smooththemes') ?></strong>
            </div>
        </div>

        <?php
    }
}

if (!function_exists('stpb_table')) {
    function stpb_table($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <?php stpb_input_table($pre_name, $data_values); ?>
        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[table_style]',$data_values['table_style'], array(
                    'table-default'=>__('Default','smooththemes'),
                    'table-striped'=>__('Striped rows','smooththemes'),
                    'table-bordered'=>__('Bordered table','smooththemes'),
                    'table-striped table-bordered'=>__('Striped rows & Bordered table','smooththemes'),
                    'table-hover'=>__('Hover rows','smooththemes'),
                    'table-hover table-bordered'=>__('Hover rows & Bordered table','smooththemes'),
                ),''); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Table Purpose','smooththemes') ?></strong>
                <span><?php _e('Choose if the table should be used to display tabular data or to display pricing options. (Difference: Pricing tables are flashier and try to stand out).','smooththemes'); ?></span>
            </div>
        </div>
        
        <div class="item table-tabular">
            <div class="left width-50">
                <?php  stpb_input_textarea($pre_name.'[caption]',$data_values['caption']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Table Caption','smooththemes') ?></strong>
                <span><?php _e('Add a short caption to the table so visitors know what the data is about','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}

if (!function_exists('stpb_table_price')) {
    function stpb_table_price($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <?php stpb_input_table($pre_name, $data_values, 'price'); ?>
        </div>

    <?php
    }
}

if (!function_exists('stpb_slider')) {
    function stpb_slider($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[slider]', $data_values['slider'], array(
                    'title'=>true,
                    'content'=>true,
                    'image'=>true,
                    'icon'=>false
                ), array(
                    'image'=>__('Image:','smooththemes')
                ),array(
                    array(
                        'title'=>__('Link URL','smooththemes'),
                        'type'=>'text',
                        'name'=>'link'
                    )
                ));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Sliders','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Sliders you want to display.','smooththemes') ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[size]',$data_values['size'], $interface->list_thumbnail_sizes()); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Image Size','smooththemes') ?></strong>
                <span><?php _e('Choose image size for the Slider.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[link_target]',$data_values['link_target'], array(
                    '_self'=>__('No, open in same window','smooththemes'),
                    '_blank'=>__('Yes, open in new window','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Open Link in new Window?','smooththemes') ?></strong>
                <span><?php _e('Select here if you want to open the linked page in a new window.','smooththemes'); ?></span>
            </div>
        </div>
    <?php
    }
}

if (!function_exists('stpb_login')) {
    function stpb_login($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[login_redirect]',$data_values['login_redirect']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Login redirect URL','smooththemes') ?></strong>
                <span><?php _e('The url will be redirect when user logged in success.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[logout_redirect]',$data_values['logout_redirect']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Logout redirect URL','smooththemes') ?></strong>
                <span><?php _e('The url will be redirect when user logout','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[register_link]',$data_values['register_link']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Register URL','smooththemes') ?></strong>
                <span><?php _e('Link to register page.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[profile_link]',$data_values['profile_link']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Profile URL','smooththemes') ?></strong>
                <span><?php _e('Link to profile page, Defaul WordPress profile page.','smooththemes'); ?></span>
            </div>
         </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[lost_pass_link]',$data_values['lost_pass_link']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Lost password URL','smooththemes') ?></strong>
                <span><?php _e('Link to lost password page','smooththemes'); ?></span>
            </div>
         </div>
    <?php
    }
}

if (!function_exists('stpb_register')) {
    function stpb_register($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[login_link]',$data_values['login_link']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Login URL','smooththemes') ?></strong>
                <span><?php _e('The url to switch to login page.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[success_redirect]',$data_values['success_redirect']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Success redirect URL','smooththemes') ?></strong>
                <span><?php _e('The url will be redirect when registration Success.','smooththemes'); ?></span>
            </div>
        </div>
    <?php
    }
}

if (!function_exists('stpb_lost_password')) {
    function stpb_lost_password($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item" >
            <?php _e('Nothing to change here :)','smooththemes') ; ?>
        </div>
        <?php
    }
}

if (!function_exists('stpb_profile')) {
    function stpb_profile($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item" >
        <?php _e('Nothing to change here :)','smooththemes') ; ?>
        </div>
        <?php
    }
}

if (!function_exists('stpb_contact_form')) {
    function stpb_contact_form($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_contact($pre_name.'[contact_form]', $data_values['contact_form']);
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Form Elements','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, drag, remove and edit form elements you want to display.','smooththemes') ?></span>
            </div>

        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[form_email_to]',$data_values['form_email_to'] ? $data_values['form_email_to'] : get_option('admin_email', '')); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('E-mail(s) To','smooththemes') ?></strong>
            </div>
        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[form_email_from_name]',$data_values['form_email_from_name'] ? $data_values['form_email_from_name'] : get_option('blogname', '')); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Your Name or Company','smooththemes') ?></strong>
                <span><?php _e('User\'s Name (optional), No required text fields detected','smooththemes'); ?></span>
            </div>
        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[form_email_from]',$data_values['form_email_from']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('E-mail From','smooththemes') ?></strong>
                <span><?php _e('User\'s Name (optional), No required text fields detected','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[form_email_subject]',$data_values['form_email_subject'] ? $data_values['form_email_subject'] : __('You have new message from '. get_option('blogname', ''), 'smooththemes')); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('E-mail Subject','smooththemes') ?></strong>
            </div>
        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[contact_form_mss_success]',$data_values['contact_form_mss_success'] ? $data_values['contact_form_mss_success'] : __('Your message has been sent' ,'smooththemes')); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Success Message','smooththemes') ?></strong>
            </div>
        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[contact_form_mss_notification]',$data_values['contact_form_mss_notification'] ? $data_values['contact_form_mss_notification'] : __('Validation errors occurred. Please confirm the fields and submit it again.' ,'smooththemes')); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Notification Message','smooththemes') ?></strong>
            </div>
        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[contact_form_mss_noti_captcha]',$data_values['contact_form_mss_noti_captcha'] ? $data_values['contact_form_mss_noti_captcha'] : __('Captcha incorect !' ,'smooththemes')); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Notification Captcha','smooththemes') ?></strong>
            </div>
        </div>
        
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_textarea($pre_name.'[form_email_body]',$data_values['form_email_body'], 'contact-from-mss-body'); ?>
                <p>
                    <a href="#" class="button btn-small btn-contact-form-generate"><?php _e('Generate', 'smooththemes'); ?></a>
                </p>
            </div>
            <div class="right  width-50">
                <strong><?php _e('E-mail Message Body','smooththemes') ?></strong>
            </div>
        </div>
    <?php
    }
}

if (!function_exists('stpb_carousel')) {
    function stpb_carousel($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">

            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[carousel]', $data_values['carousel'], array(
                    'title'=>true,
                    'content'=>true,
                    'image'=>true,
                    'icon'=>false
                ), array(
                    'image'=>__('Image:','smooththemes')
                ),array(
                    array(
                        'title'=>__('Link URL','smooththemes'),
                        'type'=>'text',
                        'name'=>'link'
                    )
                ));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Carousels','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Carousels you want to display.','smooththemes') ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php
                if(!isset($data_values['visible_items']) || $data_values['visible_items'] ==''){
                    $data_values['visible_items'] = 3;
                }
                stpb_input_select_one($pre_name.'[visible_items]',$data_values['visible_items'],  array('1'=>'1','2'=>'2',3=>3,4=>4, 6=>6)); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Visible items.','smooththemes') ?></strong>
                <span><?php _e('The number of visible items.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[link_target]',$data_values['link_target'], array(
                    '_self'=>__('No, open in same window','smooththemes'),
                    '_blank'=>__('Yes, open in new window','smooththemes')
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Open Link in new Window?','smooththemes') ?></strong>
                <span><?php _e('Select here if you want to open the linked page in a new window.','smooththemes'); ?></span>
            </div>
        </div>

    <?php
    }
}


if(!function_exists('stpb_team_member')){
    function stpb_team_member($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){

        ?>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[name]',$data_values['name']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Name','smooththemes') ?></strong>
            </div>
        </div>

        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[job]',$data_values['job']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Subtitle/Job description','smooththemes') ?></strong>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php stpb_input_media($pre_name.'[image]',$data_values['image'],'image', __('Insert Image','smooththemes') ); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Choose Image','smooththemes') ?></strong>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_select_one($pre_name.'[size]',$data_values['size'], $interface->list_thumbnail_sizes()); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Image Size','smooththemes') ?></strong>
                <span><?php _e('Choose image size.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item" show-on="">
            <div class="left width-50">
                <?php  stpb_input_textarea($pre_name.'[desc]',$data_values['desc']); ?>
                <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
                <p><label><?php stpb_input_checkbox($pre_name.'[autop]',$data_values['autop'],1); ?>&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Description','smooththemes') ?></strong>
            </div>
        </div>

        <?php

        $socials = array(
            'facebook'=>__('Facebook URL','smooththemes'),
            'twitter'=>__('Twitter URL','smooththemes'),
            'gplus'=>__('Google plus URL','smooththemes'),
            'linkedin'=>__('Linkedin URL','smooththemes'),
            'skype'=>__('Skype ID','smooththemes'),
            'stumbleupon'=>__('StumbleUpon URL','smooththemes'),
            'dribbble'=>__('Dribbble URL','smooththemes'),
            'picasa'=>__('Picasa URL','smooththemes'),
            'pinterest'=>__('Pinterest URL','smooththemes'),
            'flickr'=>__('Flickr URL','smooththemes'),
        );

        $socials = apply_filters('st_memmber_socials', $socials);

        foreach($socials as $id => $title){ ?>
        <div class="item" >
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'['.$id.']',$data_values[$id]); ?>
            </div>
            <div class="right  width-50">
                <strong><i class="iconentypo-<?php echo $id; ?>"> </i> <?php echo $title; ?></strong>
            </div>
        </div>
        <?php } ?>

    <?php
    }

}





// -------------- wooCommerce functions -----------------
if (!function_exists('stpb_wc_products')) {
    function stpb_wc_products($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){

        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_categories($pre_name.'[cats]',$data_values['cats'],'product_cat'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Which categories should be used for the Products?','smooththemes') ?></strong>
                <span><?php _e('Which categories should be used for the Products? You can select multiple categories here. The Page will then show Products from only those categories.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[number]',$data_values['number']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Number','smooththemes') ?></strong>
                <span><?php _e('How many Product you want to display ?','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php
                if(!isset($data_values['columns']) || $data_values['columns'] ==''){
                    $data_values['columns'] = 3;
                }
                stpb_input_select_one($pre_name.'[columns]',$data_values['columns'],  array('1'=>'1','2'=>'2',3=>3,4=>4, 6=>6)); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Columns','smooththemes') ?></strong>
                <span><?php _e('Choose the column count of your Products.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[exclude]',$data_values['exclude']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Exclude','smooththemes') ?></strong>
                <span><?php _e('Define a comma-separated list of product IDs to be Exclude from the list, (example: 3,7,31)','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[include]',$data_values['include']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Include','smooththemes') ?></strong>
                <span><?php _e('Define a comma-separated product of post IDs to be Include from the list, (example: 3,7,31)','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[offset]',$data_values['offset']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Offset','smooththemes') ?></strong>
                <span><?php _e('The number of Products to pass over (or displace) before collecting the set of Posts.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[pagination]',$data_values['pagination'], array(
                    'no'=>__('No','smooththemes'),
                    'yes'=>__('Yes','smooththemes'),
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Pagination','smooththemes') ?></strong>
                <span><?php _e('Should a pagination be displayed?.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[order_by]',$data_values['order_by'], array(
                    ''=>__('Default','smooththemes'),
                    'post_title'=>__('Sort Pages alphabetically (by title) ','smooththemes'),
                    'post_date'=>__('Sort by creation time','smooththemes'),
                    'ID'=>__('Sort by numeric Products ID','smooththemes'),
                    'rand'=>__('Random','smooththemes'),
                    '__price'=>__('Price','smooththemes'),
                    '_total_sales'=>__('Total Sales','smooththemes'),
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Sort By','smooththemes') ?></strong>
                <span><?php _e('Sorts the list of Product in a number of different ways.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[order]',$data_values['order'], array(
                    'asc'=>__('Ascending','smooththemes'),
                    'desc'=>__('Descending','smooththemes'),
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Sort Order ','smooththemes') ?></strong>
                <span><?php _e('Change the sort order of the list of Product.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[hide_free]',$data_values['hide_free'], array(
                    'no'=>__('No','smooththemes'),
                    'yes'=>__('Yes','smooththemes'),
                )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Hide Free Products','smooththemes') ?></strong>
            </div>
        </div>

    <?php
    }
}

/*
 * Contact Form 7 items
 *
 */

if (!function_exists('stpb_contact_from_7')) {
    function stpb_contact_from_7($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){

        $args = array(
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_type'=>'wpcf7_contact_form',
            'offset' =>'',
            'post_status' => 'any'
        );

        $q = new WP_Query();
        $forms = $q->query( $args );


        $objs = array();

        foreach ( $forms as $p ){
            $objs[$p->ID] = $p->post_title." (ID: {$p->ID})";
        }

        wp_reset_query();

        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[form_id]',$data_values['form_id'], $objs); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Contact from','smooththemes') ?></strong>
                <span><?php printf(__('Select your contact form. <a href="%s" target="_blank">Add more form</a>','smooththemes'),admin_url('admin.php?page=wpcf7')); ?></span>
            </div>
        </div>

    <?php
    }
}


if (!function_exists('stpb_LayerSlider')) {
    function stpb_LayerSlider($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
    /// for Layer Slider
        $sliders=  array();
        if(function_exists('layerslider_router')){ // if layerSlider installeds
            // Get WPDB Object
            global $wpdb;
            // Get sliders
            $layersliders = $wpdb->get_results( "SELECT * FROM  {$wpdb->prefix}layerslider WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC " );

            foreach($layersliders as $s){
                $k = $s->id;
                $sliders[$s->id]= stripslashes($s->name);
            }
        }

        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[id]',$data_values['id'], $sliders); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Slider','smooththemes') ?></strong>
                <span><?php printf(__('Select LayerSlider. <a href="%s" target="_blank">Manage LayerSlider</a>','smooththemes'),admin_url('admin.php?page=layerslider')); ?></span>
            </div>
        </div>
        <?php
    }
}



if (!function_exists('stpb_revslider')) {
    function stpb_revslider($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
    /// for Layer Slider
         $sliders=  array();
            // Get WPDB Object
            global $wpdb;
            // Get sliders
            $layersliders = $wpdb->get_results( "SELECT * FROM  {$wpdb->prefix}revslider_sliders WHERE 1  ORDER BY `title` ASC " );

            foreach($layersliders as $s){
                $k = $s->id;
                $sliders[$s->alias]= stripslashes($s->title);
            }

        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[id]',$data_values['id'], $sliders); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Slider','smooththemes') ?></strong>
                <span><?php printf(__('Select Revolution Slider. <a href="%s" target="_blank">Manage Revolution Sliders</a>','smooththemes'),admin_url('admin.php?page=revslider')); ?></span>
            </div>
        </div>
        <?php


    }
}


if (!function_exists('stpb_chart')) {
    function stpb_chart($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[title]',$data_values['title']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Title','smooththemes') ?></strong>
                <span><?php _e('Title of chart', 'smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[percent]',$data_values['percent']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Percent','smooththemes') ?></strong>
                <span><?php _e('Percent number the pie chart should have', 'smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[size]',$data_values['size']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Chart size','smooththemes') ?></strong>
                <span><?php  _e('Chart size in pixel.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[type]',$data_values['type'], array(
                        'number'=>__('Number percent inside','smooththemes'),
                        'icon'=>__('Icon inside','smooththemes'),
                    ),'.chart_icon'); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Display type','smooththemes') ?></strong>
            </div>
        </div>


        <div class="item show-on-select-change chart_icon" show-on="icon">
            <div class="left width-50">
                <?php  stpb_input_icon_popup($pre_name.'[icon]',$data_values['icon']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Icon','smooththemes') ?></strong>
                <span><?php  _e('Select your icon.','smooththemes'); ?></span>
            </div>
        </div>


        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[lineWidth]',$data_values['lineWidth']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Line Width','smooththemes') ?></strong>
                <span><?php  _e('Width of the bar line in pixel.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item barColor">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[barColor]',$data_values['barColor']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Bar Color','smooththemes') ?></strong>
                <span><?php  _e('The color of the curcular bar.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item trackColor">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[trackColor]',$data_values['trackColor']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Track Color','smooththemes') ?></strong>
                <span><?php  _e('The color of the track for the bar.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <strong><?php _e('Description','smooththemes') ?></strong>
            <span><?php _e('Enter some description for this chart','smooththemes'); ?></span>
            <?php stpb_input_textarea($pre_name.'[desc]',$data_values['desc']); ?>
            <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
            <p><label><?php stpb_input_checkbox($pre_name.'[autop]',$data_values['autop'],1); ?>&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>
        </div>

    <?php
    }
}


if(!function_exists('stpb_progress_bars')){
    function stpb_progress_bars($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){
        ?>
        <div class="item">
            <div class="left width-50">
                <?php
                stpb_input_ui($pre_name.'[progress]', $data_values['progress'], array(
                        'title'=>true,
                        'content'=>false,
                        'image'=>false,
                        'icon'=>false
                    ), array(
                        'title'=>__('Title:','smooththemes'),
                    ),array(
                        array(
                            'title'=>__('Percent:','smooththemes'),
                            'type'=>'text',
                            'name'=>'percent'
                        ),
                        array(
                            'title'=>__('Color:','smooththemes'),
                            'type'=>'color',
                            'name'=>'color'
                        )
                    ));
                ?>
            </div>

            <div class="right width-50">
                <strong><?php _e('Add/Edit Progress bars','smooththemes'); ?></strong>
                <span><?php _e('Here you can add, remove and edit the Progress bars you want to display.','smooththemes') ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[style]',$data_values['style'], array(
                        ''=>__('Default','smooththemes'),
                        'striped'=>__('Striped','smooththemes'),
                        'animated'=>__('Animated','smooththemes')
                    )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Style','smooththemes') ?></strong>
                <span><?php _e('Choose your style .','smooththemes'); ?></span>
            </div>
        </div>
        <?php
    }

}


if(!function_exists('stpb_count_to')){
    function stpb_count_to($pre_name ='', $data_values=  array(), $post= false, $no_value = false, $interface= false){

        ?>
        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[title]',$data_values['title']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Title','smooththemes') ?></strong>
                <span><?php _e('Title of CountTo box', 'smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[form]',$data_values['from']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('From','smooththemes') ?></strong>
                <span><?php _e('The number to start counting from.', 'smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[to]',$data_values['to']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('To','smooththemes') ?></strong>
                <span><?php _e('The number to stop counting at.', 'smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_select_one($pre_name.'[size]',$data_values['size'], array(
                        'medium'=>__('medium','smooththemes'),
                        'small'=>__('Small','smooththemes'),
                        'large'=>__('Large','smooththemes')
                    )); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Size','smooththemes') ?></strong>
                <span><?php _e('Choose your Size.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item">
            <div class="left width-50">
                <?php  stpb_input_text($pre_name.'[speed]',$data_values['speed']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Speed','smooththemes') ?></strong>
                <span><?php _e('The number of milliseconds it should take to finish counting.', 'smooththemes'); ?></span>
            </div>
        </div>

        <div class="item trackColor">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[number_color]',$data_values['number_color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Number Color','smooththemes') ?></strong>
                <span><?php  _e('The color of Number.','smooththemes'); ?></span>
            </div>
        </div>

        <div class="item trackColor">
            <div class="left width-50">
                <?php  stpb_input_color($pre_name.'[text_color]',$data_values['text_color']); ?>
            </div>
            <div class="right  width-50">
                <strong><?php _e('Text Color','smooththemes') ?></strong>
                <span><?php  _e('The color of text.','smooththemes'); ?></span>
            </div>
        </div>

        <?php

    }
}