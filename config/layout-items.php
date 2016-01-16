<?php

/**
 * hook stpb_col_settings
 * @param array $settings_value
 */
function stpb_layout_row_settings($settings_value=array()){
    $pre_name ='[settings]';
    if(!is_array($settings_value)){
        $settings_value = array();
    }
    ?>

    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[mod]',$settings_value['mod'], array(
                'boxed'=>__('Boxed','smooththemes'),
                'full-width'=>__('Full Width','smooththemes')
            ),'.section-inside-mod',''); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Section Mod','smooththemes'); ?></strong>
        </div>
    </div>

    <div class="item section-inside-mod show-on-select-change"  show-on="full-width">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[inside_mod]',$settings_value['inside_mod'], array(
                'boxed'=>__('Boxed','smooththemes'),
                'full-width'=>__('Full Width','smooththemes')
            )); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Content Inside Mod','smooththemes'); ?></strong>
        </div>
    </div>


    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[padding]',$settings_value['padding'], array(
                'default-padding'=>__('Default','smooththemes'),
                'large-padding'=>__('Large Padding','smooththemes'),
                'no-padding'=>__('No Padding','smooththemes'),
                'custom'=>__('Custom Padding','smooththemes')
            ),'.custom_row_padding'); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Section padding','smooththemes'); ?></strong>
        </div>
    </div>

    <div class="item custom_row_padding show-on-select-change"  show-on="custom">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[padding_top]',$settings_value['padding_top']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section Padding top','smooththemes'); ?></strong>
            <span><?php _e('Padding top in pixel.','smooththemes'); ?></span>
        </div>
    </div>


    <div class="item custom_row_padding show-on-select-change"  show-on="custom">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[padding_bottom]',$settings_value['padding_bottom']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section Padding bottom','smooththemes'); ?></strong>
            <span><?php _e('Padding top in pixel.','smooththemes'); ?></span>
        </div>
    </div>


    <div class="item custom_row_padding show-on-select-change"  show-on="custom">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[padding_left]',$settings_value['padding_left']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section Padding left','smooththemes'); ?></strong>
            <span><?php _e('Padding left in pixel.','smooththemes'); ?></span>
        </div>
    </div>


    <div class="item custom_row_padding show-on-select-change"  show-on="custom">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[padding_right]',$settings_value['padding_right']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section Padding right','smooththemes'); ?></strong>
            <span><?php _e('Padding right in pixel.','smooththemes'); ?></span>
        </div>
    </div>


    <div class="item">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[margin_top]',$settings_value['margin_top']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section Margin top','smooththemes'); ?></strong>
            <span><?php _e('Margin top in pixel.','smooththemes'); ?></span>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[margin_bottom]',$settings_value['margin_bottom']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section Margin Bottom','smooththemes'); ?></strong>
            <span><?php _e('Margin bottom in pixel.','smooththemes'); ?></span>
        </div>
    </div>


    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[is_parallax]',$settings_value['is_parallax'], array(
                'n'=>__('No','smooththemes'),
                'y'=>__('Yes','smooththemes')
            ),'.is_parallax_mod'); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Enable parallax effect ','smooththemes') ?></strong>
            <span><?php _e('This option required Background Image.','smooththemes'); ?></span>
        </div>
    </div>

    <div class="item show-on-select-change is_parallax_mod" show-on="y">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[opacity]',$settings_value['opacity']); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Opacity','smooththemes') ?></strong>
            <span><?php _e('Background opacity (0 to 1).','smooththemes'); ?></span>
        </div>
    </div>


    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[border]',$settings_value['border'], array(
                    ''=>__('No border','smooththemes'),
                    'section-bordered'=>__('Border top and bottom','smooththemes'),
                )); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Section border','smooththemes') ?></strong>
        </div>
    </div>


    <div class="item">
        <div class="left width-50">
            <?php stpb_input_color($pre_name.'[bg_color]',$settings_value['bg_color']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e(' Background Color','smooththemes'); ?></strong>
            <span><?php _e('Select a custom background color for your Section here. Leave empty if you want to use the background color of the color scheme defined above','smooththemes'); ?></span>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php stpb_input_upload($pre_name.'[bg_img]',$settings_value['bg_img']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Background image','smooththemes'); ?></strong>
            <span><?php _e('Enter image url, or choose an existing image from your media library. Leave empty if you want to use the background image of the color scheme defined above','smooththemes'); ?></span>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[bg_position]',$settings_value['bg_position'], array(
                'tl'=>__('Top left','smooththemes'),
                'tc'=>__('Top center','smooththemes'),
                'tr'=>__('Top right','smooththemes'),
                'cc'=>__('Center','smooththemes'),
                'bl'=>__('Bottom left','smooththemes'),
                'bc'=>__('Bottom center','smooththemes'),
                'br'=>__('Bottom right','smooththemes')
            )); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Background Image Position','smooththemes') ?></strong>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[bg_repeat]',$settings_value['bg_repeat'], array(
                'repeat'=>__('Repeat','smooththemes'),
                'no-repeat'=>__('No repeat','smooththemes'),
                'repeat-x'=>__('Horizontally','smooththemes'),
                'repeat-y'=>__('Vertically','smooththemes')

            )); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Background Repeat','smooththemes') ?></strong>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[bg_attachment]',$settings_value['bg_attachment'], array(
                'scroll'=>__('Scroll','smooththemes'),
                'fixed'=>__('Fixed','smooththemes'),
                'stretch'=>__('Stretch to fit','smooththemes')
            )); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Background Attachment','smooththemes') ?></strong>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[custom_class]',$settings_value['custom_class']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section Class','smooththemes'); ?></strong>
            <span><?php _e('Custom Section Class class name for your own style','smooththemes'); ?></span>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[custom_id]',$settings_value['custom_id']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Section ID','smooththemes'); ?></strong>
            <span><?php _e('Custom Section ID id for your own style or script','smooththemes'); ?></span>
        </div>
    </div>
<?php
}


/**
 * hook stpb_col_settings
 * @param array $settings_value
 */
function stpb_layout_column_settings($settings_value=  array()){
    $pre_name ='[settings]';
    if(!is_array($settings_value)){
        $settings_value = array();
    }
    ?>

    <div class="item">
        <div class="left width-50">
            <?php  stpb_input_select_one($pre_name.'[vertical_align]',$settings_value['vertical_align'], array(
                'top'=>__('Top','smooththemes'),
                'middle'=>__('Middle','smooththemes'),
                'bottom'=>__('Bottom','smooththemes')
            )); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Vertical align','smooththemes') ?></strong>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php stpb_input_effect($pre_name.'[effect]',$settings_value['effect']); ?>
        </div>
        <div class="right  width-50">
            <strong><?php _e('Effect','smooththemes') ?></strong>
            <span><?php _e('Special Effect for this Image','smooththemes'); ?></span>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[custom_class]',$settings_value['custom_class']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Layout Class','smooththemes'); ?></strong>
            <span><?php _e('Custom Layout Class class name for your own style','smooththemes'); ?></span>
        </div>
    </div>

    <div class="item">
        <div class="left width-50">
            <?php stpb_input_text($pre_name.'[custom_id]',$settings_value['custom_id']); ?>
        </div>
        <div class="right width-50">
            <strong><?php _e('Layout ID','smooththemes'); ?></strong>
            <span><?php _e('Custom Layout ID id for your own style or script','smooththemes'); ?></span>
        </div>
    </div>

<?php
}