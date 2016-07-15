<?php

/**
 * All functions must return string
 */

if (!function_exists('stpb_generate_widget')) {
    function stpb_generate_widget($data) {
        $data_settings = $data['settings'];
        $short_code = ' [st_widget '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_widget', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_blog')) {
    function stpb_generate_blog($data) {
        $data_settings = $data['settings'];
        $short_code = ' [st_blog '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_blog', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_text')) {
    function stpb_generate_text($data) {
        $data_settings = $data['settings'];
        $data_settings = wp_parse_args($data_settings, array('text'=>'', 'autop'=>0, 'align'=>''));
        $data_settings['text'] = balanceTags($data_settings['text']);

        if (isset($data_settings['autop']) && $data_settings['autop'] == 1) {
            $data_settings['text'] = wpautop($data_settings['text']);
        }
    
        $class='';  $style = '';
        if($data_settings['align']!=''){
            $class .= " text-".esc_attr($data_settings['align']);
        }

        if($data_settings['color']!='' && st_is_color($data_settings['color'])){
            $style = ' style=" color: '.$data_settings['color'].'; "';
        }

        $effect= st_effect_attr($data_settings['effect']);

        $data_settings['text'] = '<div '.$style.' class="item-text-wrapper'.$class.' '.$effect['class'].'" '.$effect['attr'].'>'. balanceTags($data_settings['text']) .'</div>';
        $data_settings['text'] = apply_filters('stpb_generate_text', $data_settings['text'], $data);
        return $data_settings['text'];
    }
}

if (!function_exists('stpb_generate_heading')) {
    function stpb_generate_heading($data) {
        $data_settings = $data['settings'];
        $short_code = ' [st_heading '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_heading', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_tabs')) {
    function stpb_generate_tabs($data) {
        $data_settings = $data['settings'];
        $items_settings = array(
            'tab_position'  => $data_settings['tab_position'],
            'initial_open' => $data_settings['initial_open']
        );
        $items_settings = wp_parse_args($items_settings, array('tab_position'=>'top', 'initial_open'=>1));
        $count_item = count($data_settings['tabs']);
        $items_settings['initial_open'] = ((int)$items_settings['initial_open'] < 1) ? 1 : $items_settings['initial_open'];
        $items_settings['initial_open'] = ((int)$items_settings['initial_open'] > $count_item) ? $count_item : $items_settings['initial_open'];
        $short_code = ' [st_tabs '. stpb_create_shortcode_attrs($items_settings) .'] ';
        if ($count_item > 0) {
            foreach($data_settings['tabs'] as $item) {
                $item_settings = array(
                    'title'             => ($item['title']),
                    'icon_type'         => ($item['icon_type']),
                    'icon'              => ($item['icon']),
                    'image_id'          => ($item['image_id'])
                );
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_tab '. stpb_create_shortcode_attrs($item_settings) .'] ';
                $item['content'] = balanceTags($item['content']);
                $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                $short_code .= $item['content'];
                $short_code .= ' [/st_tab] ';
            }
        }
        $short_code .= ' [/st_tabs] ';
        $short_code = apply_filters('stpb_generate_tabs', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_toggle')) {
    function stpb_generate_toggle($data) {
        $data_settings = $data['settings'];
        $items_settings = array(
            'initial_open' => $data_settings['initial_open']
        );
        $items_settings = wp_parse_args($items_settings, array('initial_open'=>1));
        $count_item = count($data_settings['toggle']);
        $items_settings['initial_open'] = (int)$items_settings['initial_open'];
        $items_settings['initial_open'] = ($items_settings['initial_open'] < 1 && $items_settings['initial_open'] != 0) ? 1 : $items_settings['initial_open'];
        $items_settings['initial_open'] = ($items_settings['initial_open'] > $count_item && $items_settings['initial_open'] != 0) ? $count_item : $items_settings['initial_open'];
        $short_code = ' [st_toggles '. stpb_create_shortcode_attrs($items_settings) .'] ';
        if ($count_item > 0) {
            foreach($data_settings['toggle'] as $item) {
                $item_settings = array(
                    'title'             => $item['title'],
                    'icon'              => $item['icon']
                );
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_toggle '. stpb_create_shortcode_attrs($item_settings) .'] ';
                $item['content'] = balanceTags($item['content']);
                $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                $short_code .= do_shortcode($item['content']);
                $short_code .= ' [/st_toggle] ';
            }
        }
        $short_code .= ' [/st_toggles] ';
        $short_code = apply_filters('stpb_generate_toggle', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_accordion')) {
    function stpb_generate_accordion($data) {
        $data_settings = $data['settings'];
        $items_settings = array(
            'initial_open' => $data_settings['initial_open']
        );
        $items_settings = wp_parse_args($items_settings, array('initial_open'=>1));
        $count_item = count($data_settings['accordion']);
        $items_settings['initial_open'] = (int)$items_settings['initial_open'];
        $items_settings['initial_open'] = ($items_settings['initial_open'] < 1 && $items_settings['initial_open'] != 0) ? 1 : $items_settings['initial_open'];
        $items_settings['initial_open'] = ($items_settings['initial_open'] > $count_item && $items_settings['initial_open'] != 0) ? $count_item : $items_settings['initial_open'];
        $short_code = ' [st_accordions '. stpb_create_shortcode_attrs($items_settings) .'] ';
        if ($count_item > 0) {
            foreach($data_settings['accordion'] as $item) {
                $item_settings = array(
                    'title'             => $item['title'],
                    'icon'              => $item['icon']
                );
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_accordion '. stpb_create_shortcode_attrs($item_settings) .'] ';
                $item['content'] = balanceTags($item['content']);
                $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                $short_code .= do_shortcode($item['content']);
                $short_code .= ' [/st_accordion] ';
            }
        }
        $short_code .= ' [/st_accordions] ';
        $short_code = apply_filters('stpb_generate_accordion', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_testimonials')) {
    function stpb_generate_testimonials($data) {
        $data_settings = $data['settings'];
        $items_settings = array(
            'style' => $data_settings['style']
        );
        $items_settings = wp_parse_args($items_settings);
        $count_item = count($data_settings['testimonials']);
        $short_code = ' [st_testimonials number_items="'.$count_item.'" '. stpb_create_shortcode_attrs($items_settings) .'] ';
        if ($count_item > 0) {
            foreach($data_settings['testimonials'] as $index => $item) {
                $item_settings = array(
                    'title'             => $item['title'],
                    'image_id'          => $item['image_id'],
                    'subtitle'          => $item['subtitle']
                );
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_testimonial index="'.$index.'" '. stpb_create_shortcode_attrs($item_settings) .'] ';
                $item['content'] = balanceTags($item['content']);
                $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                $short_code .= do_shortcode($item['content']);
                $short_code .= ' [/st_testimonial] ';
            }
        }
        $short_code .= ' [/st_testimonials] ';
        $short_code = apply_filters('stpb_generate_testimonials', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_notification')) {
    function stpb_generate_notification($data) {
        $data_settings = $data['settings'];
        $item_settings = array(
            'type' => $data_settings['type'],
            'icon' => $data_settings['icon']
        );
        $item_settings = wp_parse_args($item_settings);
        $short_code = ' [st_notification '. stpb_create_shortcode_attrs($item_settings) .'] ';
        $check = $data_settings['message'] = balanceTags($data_settings['message']);
        $data_settings['message'] = (isset($data_settings['autop']) && $data_settings['autop'] == 1) ? wpautop($data_settings['message']) : $data_settings['message'];
        $short_code .= do_shortcode($data_settings['message']);
        $short_code .= ' [/st_notification] ';
        $short_code = apply_filters('stpb_generate_notification', $short_code, $data);
        return (trim($check)) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_divider')) {
    function stpb_generate_divider($data) {
        $data_settings = $data['settings'];
        $data_settings = wp_parse_args($data_settings);
        $short_code = ' [st_divider '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_divider', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_clients')) {
    function stpb_generate_clients($data) {
        $data_settings = $data['settings'];
        $items_settings = array(
            'link_target'   => $data_settings['link_target'],
            'num_cols'      => $data_settings['num_cols'],
            'visible_items'  => $data_settings['visible_items']
        );
        $items_settings = wp_parse_args($items_settings);
        $count_item = count($data_settings['clients']);
        $short_code = ' [st_clients number_items="'.$count_item.'" '. stpb_create_shortcode_attrs($items_settings) .'] ';
        if ($count_item > 0) {
            foreach($data_settings['clients'] as $index => $item) {
                $item_settings = array(
                    'title'             => $item['title'],
                    'image_id'          => $item['image_id'],
                    'url'               => $item['url'],
                    'index'=> $index
                );
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_client '. stpb_create_shortcode_attrs($item_settings) .'] ';
                $item['content'] = balanceTags($item['content']);
                $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                $short_code .= do_shortcode($item['content']);
                $short_code .= ' [/st_client] ';
            }
        }
    
        $short_code .= ' [/st_clients] ';
        $short_code = apply_filters('stpb_generate_clients', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_icon_list')) {
    function stpb_generate_icon_list($data) {
        $data_settings = $data['settings'];

        $count_item = count($data_settings['icon_list']);
        //$short_code = ' [st_icon_lists '. stpb_create_shortcode_attrs($items_settings) .'] ';
        $short_code = ' [st_icon_lists number_items="'.$count_item.'"] ';
        if ($count_item > 0) {
            foreach($data_settings['icon_list'] as $index => $item) {
                $item_settings = array(
                    'title'             => $item['title'],
                    'icon'              => $item['icon'],
                    'index'             => $index,
                    'color_type'        => $data_settings['color_type'],
                    'color'             => $data_settings['color'],
                );
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_icon_list '. stpb_create_shortcode_attrs($item_settings) .']';
                if($item['content']!=''){
                    $item['content'] = balanceTags($item['content']);
                    $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                    $short_code .= do_shortcode($item['content']);
                }
                $short_code .= '[/st_icon_list] ';
            }
        }
        $short_code .= ' [/st_icon_lists] ';
        $short_code = apply_filters('stpb_generate_icon_list', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_button')) {
    function stpb_generate_button($data) {
        $data_settings = $data['settings'];
        $data_settings = wp_parse_args($data_settings);

        $link  = (array) json_decode(stripslashes_deep($data_settings['link']));

        // echo var_dump($link); die();
        unset($data_settings['link']);

        $data_settings = array_merge($link, $data_settings);

        $short_code = ' [st_button '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_button', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_gallery')) {
    function stpb_generate_gallery($data) {
        $data_settings = $data['settings'];
        $data_settings = wp_parse_args($data_settings);
        $short_code = ' [st_gallery '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_gallery', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_image')) {
    function stpb_generate_image($data) {
        $data_settings = $data['settings'];
        $data_settings = wp_parse_args($data_settings);
        $short_code = ' [st_image '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_image', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_video')) {
    function stpb_generate_video($data) {
        $data_settings = $data['settings'];
        $data_settings = wp_parse_args($data_settings);
        $short_code = ' [st_video '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_video', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_table')) {
    function stpb_generate_table($data) {
        $data_table = $data['settings']['table'];
        $count_row = count($data_table);
        $count_col = count($data_table[0]);
        $rows_setting = array();
        $cols_setting = array();
        $caption_table = (isset($data['settings']['caption']) && $data['settings']['caption'] != '') ? $data['settings']['caption'] : '';
        $display_type_table = (isset($data['settings']['display_type']) && $data['settings']['display_type'] != '') ? $data['settings']['display_type'] : '';
        $table_style_table = (isset($data['settings']['table_style']) && $data['settings']['table_style'] != '') ? $data['settings']['table_style'] : '';
        for($i=0; $i<$count_row; $i++) {
            $rows_setting[] = $data_table[$i][0];
        }
        for($i=0; $i<$count_col; $i++) {
            $cols_setting[] = $data_table[0][$i];
        }
        $table_setting = array(
            'table_style'    => $table_style_table,
            'display_type'   => $display_type_table,
            'caption_table'  => $caption_table
        );
        $table_setting = wp_parse_args($table_setting);
        $short_code = '';
        $short_code .= '[st_table '. stpb_create_shortcode_attrs($table_setting) .']';
        for($i=1; $i<$count_row; $i++) {
            $row_setting = array(
                'row_style' => $rows_setting[$i]['row_style']
            );
            $row_setting = wp_parse_args($row_setting);
            $short_code .= '[st_row '. stpb_create_shortcode_attrs($row_setting) .']';
            for($j=1; $j<$count_col; $j++) {
                if (is_array($data_table[$i][$j])) {
                    $col_setting = array(
                        'col_style' => $cols_setting[$j]['column_style']
                    );
                    $col_setting = wp_parse_args($col_setting);
                    $short_code .= '[st_col '. stpb_create_shortcode_attrs($col_setting) .']';
                    if (isset($rows_setting[$i]['row_style']) && $rows_setting[$i]['row_style'] == 'button') {
                        if (isset($data_table[$i][$j]['button']) && trim($data_table[$i][$j]['button']) != '') {
                            $short_code .= stripslashes_deep($data_table[$i][$j]['button']);
                        }
                    } else {
                        $short_code .= balanceTags($data_table[$i][$j]['text']);
                    }
                    $short_code .= '[/st_col]';
                }
            }
            $short_code .= '[/st_row]';
        }
        $short_code .= '[/st_table]';
        $short_code = apply_filters('stpb_generate_table', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_table_price')) {
    function stpb_generate_table_price($data) {
        $data_table = $data['settings']['table'];
        $count_row = count($data_table);
        $count_col = count($data_table[0]);
        $rows_setting = array();
        $cols_setting = array();
        $caption_table = (isset($data['settings']['caption']) && $data['settings']['caption'] != '') ? $data['settings']['caption'] : '';
        $display_type_table = (isset($data['settings']['display_type']) && $data['settings']['display_type'] != '') ? $data['settings']['display_type'] : '';
        for($i=0; $i<$count_row; $i++) {
            $rows_setting[] = $data_table[$i][0];
        }
        for($i=0; $i<$count_col; $i++) {
            $cols_setting[] = $data_table[0][$i];
        }
        //var_dump($rows_setting); exit();
        $table_setting = array(
            'display_type'   => $display_type_table,
            'caption_table'  => $caption_table
        );
        $table_setting = wp_parse_args($table_setting);
        $short_code = '';
        $short_code .= '[st_pricing_box '. stpb_create_shortcode_attrs($table_setting) .']';
        for($j=1; $j<$count_col; $j++) {
            $col_setting = array(
                'col_style' => $cols_setting[$j]['column_style'],
                'class_name' =>' c-'.$j
            );

            if($j==1){
                $col_setting['class_name'] .=' first';
            }

            if($j==$count_col-1){
                $col_setting['class_name'] .=' last';
            }

            $col_setting = wp_parse_args($col_setting);
            $short_code .= '[st_pricing_col '. stpb_create_shortcode_attrs($col_setting) .']';
            for($i=1; $i<$count_row; $i++) {
                if (is_array($data_table[$i][$j])) {
                    $row_setting = array(
                        'row_style' => $rows_setting[$i]['row_style'],
                        'class_name' =>' r-'.$i
                    );

                    if($i==1){
                        $row_setting['class_name'] .=' first';
                    }

                    if($i==$count_row-1){
                        $row_setting['class_name'] .=' last';
                    }

                    $row_setting = wp_parse_args($row_setting);
                    $short_code .= '[st_pricing_row '. stpb_create_shortcode_attrs($row_setting) .']';
                    if (isset($rows_setting[$i]['row_style']) && $rows_setting[$i]['row_style'] == 'button') {
                        if (isset($data_table[$i][$j]['button']) && trim($data_table[$i][$j]['button']) != '') {
                            $short_code .= stripslashes_deep($data_table[$i][$j]['button']);
                        }
                    } else {
                        $short_code .= balanceTags($data_table[$i][$j]['text']);
                    }
                    $short_code .= '[/st_pricing_row]';
                }
            }
            $short_code .= '[/st_pricing_col]';
        }
        $short_code .= '[/st_pricing_box]';
        $short_code = apply_filters('stpb_generate_table_price', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_iconbox')) {
    function stpb_generate_iconbox($data) {
        $data_settings = $data['settings'];
        $content = (isset($data_settings['autop']) && $data_settings['autop'] == 1) ? wpautop($data_settings['content']) : $data_settings['content'];
        unset($data_settings['content']);
        $short_code = ' [st_iconbox '. stpb_create_shortcode_attrs($data_settings) .'] '. $content .' [/st_iconbox] ';
        $short_code = apply_filters('stpb_generate_iconbox', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_wc_products')) {
    function stpb_generate_wc_products($data) {
        if(empty($data)){
            return '';
        }
        $data_settings = $data['settings'];
        $short_code = ' [st_products '. stpb_create_shortcode_attrs($data_settings) .']';
        $short_code = apply_filters('stpb_generate_wc_products',$short_code,$data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_slider')) {
    function stpb_generate_slider($data) {
        $data_settings = $data['settings'];
        $count_item = count($data_settings['slider']);
        //$short_code = ' [st_icon_lists '. stpb_create_shortcode_attrs($items_settings) .'] ';
        $short_code = ' [st_slider number_items ="'.$count_item.'"] ';
        if ($count_item > 0) {
            foreach($data_settings['slider'] as $i => $item) {
    
                $item_settings = array(
                    'title'             => $item['title'],
                    'image_id'          => intval($item['image_id']),
                    'index'             => $i,
                    'link'              =>$item['link'],
                    'size'              =>$data_settings['size'],
                    'link_target'       =>$data_settings['link_target']
                );
    
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_slider_item '. stpb_create_shortcode_attrs($item_settings) .']';
    
                if($item['content']!=''){
                    $item['content'] = balanceTags($item['content']);
                    $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                    $short_code .= ' '.($item['content']).' ';
                }
    
                $short_code .= '[/st_slider_item] ';
            }
        }
        $short_code .= ' [/st_slider] ';
        $short_code = apply_filters('stpb_generate_slider', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_carousel')) {
    function stpb_generate_carousel($data){
        $data_settings = $data['settings'];
        $count_item = count($data_settings['carousel']);
        $short_code = ' [st_carousel number_items ="'.$count_item.'" visible_items ="'.intval($data_settings['visible_items']).'"] ';
        if ($count_item > 0) {
            foreach($data_settings['carousel'] as $i => $item) {
    
                $item_settings = array(
                    'title'             => $item['title'],
                    'image_id'          => intval($item['image_id']),
                    'index'             => $i,
                    'link'              =>$item['link'],
                    'link_target'       =>$data_settings['link_target']
                );
    
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_carousel_item '. stpb_create_shortcode_attrs($item_settings) .']';
    
                if($item['content']!=''){
                    $item['content'] = balanceTags($item['content']);
                    $item['content'] = (isset($item['autop']) && $item['autop'] == 1) ? wpautop($item['content']) : $item['content'];
                    $short_code .= ' '.($item['content']).' ';
                }
    
                $short_code .= '[/st_carousel_item] ';
            }
        }
        $short_code .= ' [/st_carousel] ';
        $short_code = apply_filters('stpb_generate_carousel', $short_code, $data);
        return ($count_item > 0) ? $short_code : '';
    }
}

if (!function_exists('stpb_generate_contact_form')) {
    function stpb_generate_contact_form($data){
        $short_code ='';
        $data_settings = $data['settings'];
        $count_item = count($data_settings['contact_form']);
        $form_settings = array(
            'form_email_subject'    => base64_encode($data_settings['form_email_subject']),
            'form_email_from_name'  => base64_encode($data_settings['form_email_from_name']),
            'form_email_from'       => base64_encode($data_settings['form_email_from']),
            'form_email_to'         => base64_encode($data_settings['form_email_to']),
            'form_email_body'       => base64_encode($data_settings['form_email_body']),
            'contact_form_mss_noti_captcha' => base64_encode($data_settings['contact_form_mss_noti_captcha']),
            'contact_form_mss_success' => base64_encode($data_settings['contact_form_mss_success']),
            'contact_form_mss_notification' => base64_encode($data_settings['contact_form_mss_notification']),
        );
        $short_code = ' [st_contact_form '. stpb_create_shortcode_attrs($form_settings) .' ] ';
        if ($count_item > 0) {
            foreach($data_settings['contact_form'] as $i => $item) {
                $item_settings = array(
                    'label'             => $item['label'],
                    'name'              => $item['name'],
                    'field_type'        => $item['field_type'],
                    'options'           => $item['options'],
                    'field_width'       => $item['field_width'],
                    'placeholder'       => $item['placeholder'],
                    'validation'        => $item['validation'],
                    'required'          => $item['required'],
                    'css_class'         => $item['css_class']
                );
                if ($item['field_type'] == 'captcha') $item_settings['required'] = 'yes';
                $item_settings = wp_parse_args($item_settings);
                $short_code .= ' [st_contact_form_item '. stpb_create_shortcode_attrs($item_settings) .' ] ';
                $short_code .= ' [/st_contact_form_item] ';
            }
        }
        $short_code .= ' [/st_contact_form] ';
        return $short_code;
    }
}

if (!function_exists('stpb_generate_contact_from_7')) {
    function stpb_generate_contact_from_7($data){
        $data_settings = wp_parse_args($data['settings'], array('form_id'=>''));
        $short_code ='';
        if(intval($data_settings['form_id'])>0){
            $short_code ='[contact-form-7 id="'.intval($data_settings['form_id']).'" title=""]';
        }
       return $short_code;
    }
}

if (!function_exists('stpb_generate_LayerSlider')) {
    function stpb_generate_LayerSlider($data){
        $data_settings = wp_parse_args($data['settings'], array('id'=>''));
        $short_code ='';
        if(intval($data_settings['id'])>0){
            $short_code ='[layerslider  id="'.intval($data_settings['id']).'"]';
        }
        return $short_code;
    }
}


if (!function_exists('stpb_generate_revslider')) {
    function stpb_generate_revslider($data){
        $data_settings = wp_parse_args($data['settings'], array('id'=>''));
        $short_code ='';
        if($data_settings['id']!=''){
            $short_code ='[rev_slider '.esc_attr($data_settings['id']).']';
        }
        return $short_code;
    }
}


if (!function_exists('stpb_generate_login')) {
    function stpb_generate_login($data){
        $data_settings = $data['settings'];
        $short_code = ' [st_login '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_login', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_register')) {
    function stpb_generate_register($data){
        $data_settings = $data['settings'];
        $short_code = ' [st_register '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_login', $short_code, $data);
        return $short_code;
    }
}

if (!function_exists('stpb_generate_lost_password')) {
    function stpb_generate_lost_password($data){
        $data_settings = $data['settings'];
        $short_code = ' [st_lost_password '. stpb_create_shortcode_attrs($data_settings) .']';
        $short_code = apply_filters('stpb_generate_lost_password', $short_code, $data);
    
        return $short_code;
    }
}

if (!function_exists('stpb_generate_profile')) {
    function stpb_generate_profile($data){
        $data_settings = $data['settings'];
        $short_code = ' [st_profile '. stpb_create_shortcode_attrs($data_settings) .'] ';
        $short_code = apply_filters('stpb_generate_profile', $short_code, $data);
        return $short_code;
    }
}


if(!function_exists('stpb_generate_team_member')){

    function stpb_generate_team_member($data) {
        $data_settings = $data['settings'];

        $shortcode ='';

        $data_settings =  array_filter($data_settings);
        if(!empty($data_settings)){

            $desc =  $data_settings['desc'];
            unset($data_settings['desc']);
            $shortcode .= ' [st_team_member '.stpb_create_shortcode_attrs($data_settings).'] '.balanceTags($desc) .' [/st_team_member] ';
            $shortcode = apply_filters('stpb_generate_team_member', $shortcode, $data);
        }

        return ($shortcode !='') ? $shortcode : '';

    }

}

if(!function_exists('stpb_generate_chart')){
    function stpb_generate_chart($data){
        $data_settings = $data['settings'];
        $short_code = ' [st_chart '. stpb_create_shortcode_attrs($data_settings) .'] ';

        $desc =  trim($data_settings['desc']);
        unset($data_settings['desc']);
        if($desc!=''){
            $desc = balanceTags($desc);
            if($data_settings['autop']==1){
                $desc = wpautop($desc);
            }else{
            }
            $short_code .=' '.$desc.' [/st_chart]' ;
        }

        $short_code = apply_filters('stpb_generate_chart', $short_code, $data);
        return $short_code;
    }
}

if(!function_exists('stpb_generate_progress_bars')){
    function stpb_generate_progress_bars($data){
        $data_settings = $data['settings'];
        $progress = $data_settings['progress'];
        $style = $data_settings['style'];
        unset($data_settings['progress'], $data_settings['style'] );
        $short_code = '[st_progress '. stpb_create_shortcode_attrs($data_settings) .']';



        if(is_array($progress)){
            $n = count($progress);

            foreach($progress as $k=> $v){
                $v['class'] = 'p-'.$k;
                if($k==0){
                    $v['class'].=' first';
                }

                if($k==$n-1){
                    $v['class'].=' last';
                }
                $v['style'] = $style;

                $short_code.='[st_progress_bar '. stpb_create_shortcode_attrs($v) .']';
            }
        }

        $short_code.='[/st_progress]';

        return $short_code;
    }
}

if(!function_exists('stpb_generate_count_to')){
    function stpb_generate_count_to($data){
        $data_settings = $data['settings'];
        $short_code = '[st_countto '. stpb_create_shortcode_attrs($data_settings) .']';
        return $short_code;
    }
}






