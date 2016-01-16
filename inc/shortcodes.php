<?php




/**
 *
 * Items Layout
 *
 */

if (!class_exists('ST_Layout_Shortcode')) {
    /**
     * PB Item Layout
     */
    class ST_Layout_Shortcode {
        static $shortcode_count;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'col', array(__CLASS__, 'st_col' ) );
            add_shortcode( 'row', array(__CLASS__, 'st_row' ) );
            self::$shortcode_count = 0;
        }


        /**
         * st_col function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_col( $atts, $content ) {
            $atts = shortcode_atts(array(
                'width'  => '1/1'
            ), $atts);
            extract($atts);
            self::$shortcode_count++;
            $out = '';
            $class = stpb_layout_column_class(str_replace('/', '-', $width));
            $out .= '<div class="'. esc_attr(trim($class)) .'">';
            $out .= do_shortcode($content);
            $out .= '</div>';
            $out = apply_filters('st_col', $out, $atts);
            return $out;
        }


        /**
         * st_row function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_row( $atts, $content ) {
            $atts = shortcode_atts(array(
            ), $atts);
            extract($atts);
            self::$shortcode_count = 0;
            $out = '';
            $class = 'row-wrapper row ';
            $out .= '<div class="'. esc_attr(trim($class)) .'">';
            $out .= do_shortcode($content);
            $out .= '</div>';
            $out = apply_filters('st_row', $out, $atts);
            return $out;
        }
    }
    ST_Layout_Shortcode::init();
}



/**
 *
 * Items Content Elements
 *
 */
if (!function_exists('st_widget_func')) {
    /**
     * PB Item Widget
     */
    function st_widget_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'sidebar' => ''
        ), $atts );
        extract($atts);
        $html = '<div class="sidebar builder-sidebar">';
        $html .= st_get_content_from_func('dynamic_sidebar', $sidebar);
        $html .= '</div>';

        $html = apply_filters('st_widget_func', $html, $atts);
        return $html;
    }
    add_shortcode('st_widget', 'st_widget_func');
}


if (!function_exists('st_text_func')) {
    /**
     * PB Item Widget
     */
    function st_text_func($atts, $content='') {
        $html = apply_filters('the_content', $content);
        return $html;
    }
    
    add_shortcode('st_text', 'st_text_func');
}




if (!function_exists('st_heading_func')) {
    /**
     * PB Item Heading
     */
    function st_heading_func($atts, $content='') {

        $atts = shortcode_atts( array(
            'heading'         => '',
            'type'            => 'h1',
            'padding_top'     => '',
            'padding_bottom'  => '',
            'align'           =>'default',
            'custom_class'    => 'heading',
            'color'           => '',
            'margin_top'=>'',
            'margin_bottom'=>''
        ), $atts );

        extract($atts);
        $html = '';
        $html .= "<$type class=\"{$custom_class} align-{$align}\" ";

        $html .= 'style="';

        $html .= ($padding_top !== '' && is_numeric($padding_top)) ? " padding-top:{$padding_top}px; " : '';
        $html .= ($padding_bottom !== '' && is_numeric($padding_bottom)) ? " padding-bottom:{$padding_bottom}px; " : '';
        $html .= ($margin_top !== '' && is_numeric($margin_top)) ? " margin-top:{$margin_top}px;" : '';

        $html .= ($margin_bottom !== '' && is_numeric($margin_bottom)) ? " margin-bottom:{$margin_bottom}px; " : '';
        $html .= ($color !== ''&& st_is_color($color)) ? " color: {$color}; " : '';

        $html .= '"';
        $html .= '>';

        $html .= $heading;
        $html .= "</$type>";
        $html = apply_filters('st_heading_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_heading', 'st_heading_func');
}




if (!class_exists('ST_Tab_Shortcode')) {
    /**
     * PB Item Toggle
     */
    class ST_Tab_Shortcode {
        static $shortcode_data;
        static $shortcode_count;
        static $initial_open;
        static $shortcode_tab_id;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_tab', array(__CLASS__, 'st_tab' ) );
            add_shortcode( 'st_tabs', array(__CLASS__, 'st_tabs' ) );
            self::$shortcode_count = 0;
            self::$initial_open = 0;
        }

        /**
         * st_tab function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_tab( $atts, $content ) {
            $atts = shortcode_atts(array(
                'title'             => '',
                'icon_type'         => '',
                'icon'              => '',
                'image_id'          => ''
            ), $atts);
            self::$shortcode_count++;
            self::$shortcode_data[self::$shortcode_count] = $atts;
            self::$shortcode_tab_id[self::$shortcode_count] = $tab_id = uniqid();
            extract($atts);
            $out = '';
            if (self::$shortcode_count == self::$initial_open)
                $out.= '<div id="tab-'. $tab_id .'" tab-id="tab-'. self::$shortcode_count .'" class="tab-pane active"><div class="txt-content">'. do_shortcode(balanceTags($content), TRUE) .'</div></div>';
            else
                $out.= '<div id="tab-'. $tab_id .'" tab-id="tab-'. self::$shortcode_count .'" class="tab-pane"><div class="txt-content">'. do_shortcode(balanceTags($content), TRUE) .'</div></div>';
            $out = apply_filters('st_tab', $out, $atts);
            return $out;
        }


        /**
         * st_tabs function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_tabs( $atts, $content ) {
            $atts = shortcode_atts(array(
                'initial_open'  => 1,
                'tab_position'  => 'top'
            ), $atts);
            extract($atts);
            self::$shortcode_count = 0;
            self::$initial_open = $initial_open;
            self::$shortcode_tab_id = array();
            self::$shortcode_data = array();
            $out = '';
            $out .= '<div class="st-tabs position-'. $tab_position .'">';
            $out_content = '<div class="tab-content">';
            $out_content .= do_shortcode( $content );
            $out_content .= '</div>';
            $out_title = '<ul class="nav nav-tabs">';
            foreach(self::$shortcode_data as $k => $item) {
                $icon_tab = '';
                $icon_tab = ($item['icon_type'] == 'icon' && $item['icon'] != '') ? '<span class="icon-tab '. esc_attr($item['icon']) .'"></span>' : $icon_tab;
                $icon_tab = ($item['icon_type'] == 'image' && $item['image_id'] != '') ? '<span class="icon-tab icon-img"><img src="'. esc_url(wp_get_attachment_thumb_url($item['image_id'], 'thumbnail')) .'" alt="" /></span>' : $icon_tab;
                if ($k == $initial_open)
                    $out_title .= '<li class="tab-title active" tab-id="tab-'. $k .'"><a href="#tab-'. self::$shortcode_tab_id[$k] .'" data-toggle="tab">'. $icon_tab .'<span>'. $item['title'] .'</span></a></li>';
                else
                    $out_title .= '<li class="tab-title" tab-id="tab-'. $k .'"><a href="#tab-'. self::$shortcode_tab_id[$k] .'" data-toggle="tab">'. $icon_tab .'<span>'. $item['title'] .'</a></span></li>';
            }
            $out_title .= '</ul>';
            if ($tab_position == 'right') {
                $out .= $out_content . $out_title;
            }
            else {
                $out .= $out_title . $out_content;
            }
            $out .= '</div>';
            $out = apply_filters('st_tabs', $out, $atts);
            return $out;
        }
    }
    ST_Tab_Shortcode::init();
}



if (!class_exists('ST_Toggle_Shortcode')) {
    /**
     * PB Item Toggle
     */
    class ST_Toggle_Shortcode {
        static $shortcode_count;
        static $initial_open;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_toggle', array(__CLASS__, 'st_toggle' ) );
            add_shortcode( 'st_toggles', array(__CLASS__, 'st_toggles' ) );
            self::$shortcode_count = 0;
            self::$initial_open = 0;
        }

        /**
         * st_toggle function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_toggle( $atts, $content ) {
            $atts = shortcode_atts(array(
                'title'             => '',
                'icon' =>''
            ), $atts);
            extract($atts);
            self::$shortcode_count++;
            $out = '';
             $class_content = '';
            $is_open = false;
            if (self::$shortcode_count == self::$initial_open) {
                $class_title = '';
                $class_content = 'in';
                $is_open = true;
            }
            else
            {
                $class_title = '';
                $class_content = '';
            }

            $id_item = uniqid();

            $html_title .= '<div class="panel-heading">';

            $html_title .= '<div class="acc-title '. esc_attr($class_title) .'">';

            if($icon!=''){
                $icon ='<i class="color-icon '.esc_attr($icon).'"></i>';
            }

            $html_title .= '<a class="accordion-toggle '.($is_open ? '' : 'collapsed').'" data-toggle="collapse" data-parent="false" href="#collapse-'. $id_item .'">'.$icon. esc_html($title) .' <span class="toggle-icon"><span class="vert-icon"></span><span class="hor-icon"></span></span></a>';

            $html_title .= '</div>';
            $html_title .= '</div>';
            $html_content = '<div id="collapse-'. $id_item .'" class="panel-collapse collapse '. esc_attr($class_content) .'">';

            $html_content .= '<div class="panel-body toggle-content"><div class="txt-content">'. do_shortcode(balanceTags($content), TRUE) .'</div></div>';

            $html_content .= '</div>';

            $out .= '<div class="panel panel-default">'. $html_title . $html_content .'</div>';
            $out = apply_filters('st_toggle', $out, $atts);
            return $out;
        }


        /**
         * st_toggles function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_toggles( $atts, $content ) {
            $atts = shortcode_atts(array(
                'initial_open'  => 1
            ), $atts);
            extract($atts);
            self::$shortcode_count = 0;
            self::$initial_open = $initial_open;
            $out = '';
            $out .= '<div class="panel-group st-toggle" id="">';
            $out .= do_shortcode($content);
            $out .= '</div>';
            $out = apply_filters('st_toggles', $out, $atts);
            return $out;
        }
    }
   ST_Toggle_Shortcode::init();
}



if (!class_exists('ST_Accordion_Shortcode')) {
    /**
     * PB Item Accordion
     */
    class ST_Accordion_Shortcode {
        static $shortcode_count;
        static $initial_open;
        static $id_accordion;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_accordion', array(__CLASS__, 'st_accordion' ) );
            add_shortcode( 'st_accordions', array(__CLASS__, 'st_accordions' ) );
            self::$shortcode_count = 0;
            self::$initial_open = 0;
        }

        /**
         * st_accordion function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_accordion( $atts, $content ) {
            $atts = shortcode_atts(array(
                'title'             => '',
                'icon'=>''
            ), $atts);
            extract($atts);
            self::$shortcode_count++;
            $out = '';
            $is_open = false;
            $class_content = '';
            if (self::$shortcode_count == self::$initial_open) {
                $class_title = '';
                $class_content = 'in';
                $is_open = true;
            }
            else
            {
                $class_title = '';
                $class_content = '';
            }

            $id_item = uniqid();

            $html_title .= '<div class="panel-heading">';

            $html_title .= '<div class="acc-title '. esc_attr($class_title) .'">';

            if($icon!=''){
                $icon ='<i class="color-icon '.esc_attr($icon).'"></i>';
            }

            $html_title .= '<a class="accordion-toggle '.( $is_open ? '' : 'collapsed'  ).'" data-toggle="collapse" data-parent="#accordion-'. self::$id_accordion .'" href="#collapse-'. $id_item .'">'.$icon. esc_html($title) .' <span class="toggle-icon"><span class="vert-icon"></span><span class="hor-icon"></span></span> </a>';

            $html_title .= '</div>';

            $html_title .= '</div>';

            $html_content = '<div id="collapse-'. $id_item .'" class="panel-collapse collapse '. esc_attr($class_content) .'">';

            $html_content .= '<div class="panel-body acc-content"><div class="txt-content">'. do_shortcode(balanceTags($content), TRUE) .'</div></div>';

            $html_content .= '</div>';

            $out .= '<div class="panel panel-default">'. $html_title . $html_content .'</div>';
            $out = apply_filters('st_accordion', $out, $atts);
            return $out;
        }


        /**
         * st_accordions function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_accordions( $atts, $content ) {
            $atts = shortcode_atts(array(
                'initial_open'  => 1
            ), $atts);
            extract($atts);
            self::$shortcode_count = 0;
            self::$initial_open = $initial_open;
            self::$id_accordion = uniqid();
            $out = '';
            $out .= '<div class="panel-group accordion st-accordion" id="accordion-'. self::$id_accordion .'">';
            $out .= do_shortcode($content);
            $out .= '</div>';
            $out = apply_filters('st_accordions', $out, $atts);
            return $out;
        }
    }
    ST_Accordion_Shortcode::init();
}



if (!class_exists('ST_Testimonial_Shortcode')) {
    /**
     * PB Item Testimonial
     */
    class ST_Testimonial_Shortcode {
        static $shortcode_count;
        static $initial_open;
        static $id_testimonial;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_testimonial', array(__CLASS__, 'st_testimonial' ) );
            add_shortcode( 'st_testimonials', array(__CLASS__, 'st_testimonials' ) );
            self::$shortcode_count = 0;
            self::$initial_open = 0;
        }

        /**
         * st_testimonial function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_testimonial( $atts, $content ) {
            $atts = shortcode_atts(array(
                'title'             => '',
                'subtitle'          => '',
                'image_id'          => '',
                'index'=>'',
            ), $atts);
            extract($atts);

            $out = '';
            $item = $i_title = $i_image = $i_subtitle = $i_content = $class = '';
            $i_title = ($title != '') ? '<span class="st-testimonial-name">'. esc_attr($title) .'</span>' : '';
            $i_subtitle = ($subtitle != '') ? '<span class="sp">-</span> <span class="st-testimonial-subtitle">'. $subtitle .'</span>' : '';
            $i_content = do_shortcode(balanceTags($content), TRUE);
            $i_image = ($image_id != '') ? '<img src="'. esc_url(wp_get_attachment_thumb_url($image_id, 'thumbnail')) .'" class="t-avt" alt="'. esc_attr($title) .'"  title ="'. esc_attr($title) .'"/>' : $i_image;
            $class = (self::$shortcode_count == 1) ? 'testi-item-first active ' : $class;

            if ($index==0){
                $class .= ' first active ';
            }

            if($index+1==self::$shortcode_count){
                $class .=' last ';
            }


            $class .= ($image_id != '') ? 'has-avt' : 'no-avt';

            $item .= '<div class="item testi-item testi-item-'. ($index+1) .' '. esc_attr(trim($class)) .'">';
            if ($i_image) {
                $item .= '<div class="st-testimonial-image">';
                $item .= $i_image;
                $item .= '</div>';
            }
            if ($i_content) {
                $item .= '<div class="st-testimonial-content">';
                $item .= $i_content;
                $item .= '</div>';
            }
            if ($i_subtitle || $i_title) {
                $item .= '<div class="st-testimonial-meta">';
                $item .= $i_title . $i_subtitle;
                $item .= '</div>';
            }
            $item .= '</div><!-- end .testi-item -->';
            $i_content = null;
            $out .= $item;


            $out = apply_filters('st_testimonial', $out, $atts);
            return $out;
        }


        /**
         * st_toggles function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_testimonials( $atts, $content ) {
            $atts = shortcode_atts(array(
                'style'  => 'list',
                'number_items'=>'',
            ), $atts);
            extract($atts);
            self::$shortcode_count = intval($number_items);
            self::$initial_open = $initial_open;
            self::$id_testimonial = uniqid();
            $out = '';

            if ($style == 'slider') {
                $out .= '<div id="carousel-'. self::$id_testimonial .'" class="st-testimonial-slider carousel slide">';

                $content = do_shortcode($content);
                // <!-- Indicators -->
                $out .= '<ol class="carousel-indicators">';

                for($i=0; $i<self::$shortcode_count; $i++) {
                    $active = ($i == 0) ? 'active' : '';
                    $out .= '<li data-target="#carousel-'. self::$id_testimonial .'" data-slide-to="'. $i .'" class="'. $active .'"></li>';
                }


                $out .= '</ol>';

                $out .= '<div class="carousel-inner">'; //    $out .= '<div class="carousel-inner">';

                $out .= $content;

                $out .= '</div>';

                // <!-- Controls -->
                $out .= '<a class="left carousel-control" href="#carousel-'. self::$id_testimonial .'" data-slide="prev"><span class="icon-prev"></span></a>';
                $out .= '<a class="right carousel-control" href="#carousel-'. self::$id_testimonial .'" data-slide="next"><span class="icon-next"></span></a>';
                $out .= '</div>';
            }
            else {
                $out .= '<div class="st-testimonials st-testimonial-'. esc_attr($style) .'">';
                $out .= '<div class="st-testimonial-w" id="testi-'. esc_attr(self::$id_testimonial) .'">';
                $out .= do_shortcode($content);
                $out .= '</div><!-- end .st-testimonial-wrapper-inner -->';
                $out .= '</div><!-- end .st-testimonial-wrapper -->';
            }

            $out = apply_filters('st_testimonials', $out, $atts);
            return $out;
        }
    }
    ST_Testimonial_Shortcode::init();
}




if (!class_exists('ST_Table_Shortcode')) {
    /**
     * PB Item Table
     */
    class ST_Table_Shortcode {
        static $shortcode_data;
        static $shortcode_data_col;
        static $shortcode_data_row;
        static $shortcode_count_col;
        static $shortcode_count_row;
        static $row_style;
        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_col', array(__CLASS__, 'st_col' ) );
            add_shortcode( 'st_row', array(__CLASS__, 'st_row' ) );
            add_shortcode( 'st_table', array(__CLASS__, 'st_table' ) );
            self::$shortcode_count_col = 0;
            self::$shortcode_count_row = 0;
        }
        /**
         * st_col function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_col( $atts, $content ) {
            $atts = shortcode_atts(array(
                'col_style'             => 'default',
                'content'               => $content
            ), $atts);
            self::$shortcode_count_col++;
            self::$shortcode_data[self::$shortcode_count_row][self::$shortcode_count_col] = $atts;
            extract($atts);
            $out = '';
            $class = ($content != '') ? '' : ' blank';
            if (self::$row_style == 'heading') {
                $out.= '<th class="'. $col_style . $class .'">'. do_shortcode(balanceTags($content), TRUE) .'</th>';
            }
            else {
                $out.= '<td class="'. $col_style . $class .'">'. do_shortcode(balanceTags($content), TRUE) .'</td>';
            }
            $out = apply_filters('st_col', $out, $atts);
            return $out;
        }
        /**
         * st_row function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_row( $atts, $content ) {
            $atts = shortcode_atts(array(
                'row_style'  => 'default'
            ), $atts);
            self::$shortcode_count_row++;
            extract($atts);
            self::$shortcode_count_col = 0;
            self::$row_style = $row_style;
            $out = '';
            $out_content .= do_shortcode( $content );
            self::$shortcode_data_row[self::$shortcode_count_row] = $atts;
            if ($row_style == 'heading') {
                $out .= '<thead>';
                $out .= '<tr class="'. $row_style .'">';
                $out .= $out_content;
                $out .= '</tr>';
                $out .= '</thead>';
            } else {
                $out .= '<tr class="'. $row_style .'">';
                $out .= $out_content;
                $out .= '</tr>';   
            }
            $out = apply_filters('st_row', $out, $atts);
            return $out;
        }
        /**
         * st_table function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_table( $atts, $content ) {
            $atts = shortcode_atts(array(
                'table_style'    => '',
                'caption_table'  => ''
            ), $atts);
            extract($atts);
            self::$shortcode_count_col = 0;
            self::$shortcode_count_row = 0;
            self::$shortcode_data_row = array();
            self::$shortcode_data = array();
            $out = '';
            $out_content .= do_shortcode( $content );
            if ($caption_table != '') {
                $out .= '<div class="table-caption">'. balanceTags($caption_table) .'</div>';
            }
            $out .= '<table class="table '. $display_type .' '. $table_style .'">';
            $out .= $out_content;
            $out .= '</table>';
            $out = apply_filters('st_table', $out, $atts);
            return $out;
        }
    }
    ST_Table_Shortcode::init();
}



if (!class_exists('ST_Pricing_Box_Shortcode')) {
    /**
     * PB Pricing Box
     */
    class ST_Pricing_Box_Shortcode {
        static $shortcode_data;
        static $shortcode_data_col;
        static $shortcode_data_row;
        static $shortcode_count_col;
        static $shortcode_count_row;
        static $col_style;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_pricing_row', array(__CLASS__, 'st_pricing_row' ) );
            add_shortcode( 'st_pricing_col', array(__CLASS__, 'st_pricing_col' ) );
            add_shortcode( 'st_pricing_box', array(__CLASS__, 'st_pricing_box' ) );
            self::$shortcode_count_col = 0;
            self::$shortcode_count_row = 0;
        }

        /**
         * st_pricing_row function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_pricing_row( $atts, $content ) {
            $atts = shortcode_atts(array(
                'row_style'             => 'default',
                'content'               => $content,
                'class_name'=>''
            ), $atts);
            self::$shortcode_count_row++;
            extract($atts);
            self::$shortcode_data[self::$shortcode_count_col][self::$shortcode_count_row] = $atts;
            $out = '';
            $class = ($content != '') ? '' : ' blank';
            $out.= '<div class="'. $row_style . $class .'">'. do_shortcode(balanceTags($content), TRUE) .'</div>';
            $out = apply_filters('st_pricing_row', $out, $atts);
            return $out;
        }


        /**
         * st_pricing_col function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_pricing_col( $atts, $content ) {
            $atts = shortcode_atts(array(
                'col_style'  => 'default',
                'class_name'=>''
            ), $atts);

            self::$shortcode_count_col++;
            extract($atts);
            self::$shortcode_count_row = 0;
            self::$col_style = $col_style;
            $out = '';
            $out_content .= do_shortcode( $content );
            self::$shortcode_data_col[self::$shortcode_count_col] = $atts;

            $out .= '<div class="'. $col_style .'">';
            $out .= $out_content;
            $out .= '</div>';
            $out = apply_filters('st_pricing_col', $out, $atts);
            return $out;
        }


        /**
         * st_pricing_box function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_pricing_box( $atts, $content ) {
            $atts = shortcode_atts(array(
                'display_type'   => '',
                'caption_table'  => ''
            ), $atts);
            extract($atts);
            self::$shortcode_count_col = 0;
            self::$shortcode_count_row = 0;
            self::$shortcode_data_col = array();
            self::$shortcode_data = array();
            $out = '';
            do_shortcode( $content );
            foreach(self::$shortcode_data_col as $i => $item) {
                $item['col_style'] = (isset($item['col_style']) && $item['col_style'] != '') ? $item['col_style'] : 'default';

                $class_name = isset($item['class_name']) ? ' '.$item['class_name'] : '';

                $out_content .= '<div class="pricing-box-item '. $item['col_style'] .$class_name.' col-md-6">';
                $out_content .= '<div class="pricing-box-inner">';
                foreach(self::$shortcode_data[$i] as $item_row) {
                    $item_row['row_style'] = (isset($item_row['row_style']) && $item_row['row_style'] != '') ? $item_row['row_style'] : 'default';
                    $item_row['content'] = (isset($item_row['content']) && $item_row['content'] != '') ? $item_row['content'] : '';

                    $class_name = isset($item_row['class_name']) ? ' '.$item_row['class_name'] : '';

                    $out_content .= '<div class="'. $item_row['row_style'] .$class_name.'">';
                    $out_content .= do_shortcode(balanceTags($item_row['content']), TRUE);
                    $out_content .= '</div>';
                }
                $out_content .= '</div>';
                $out_content .= '</div>';
            }
            
            $out .= '<div class="table pricing-box pricing-box-'. (int)self::$shortcode_count_col .' clearfix">';
            $out .= $out_content;
            $out .= '</div>';
            $out = apply_filters('st_pricing_box', $out, $atts);
            return $out;
        }
    }
    ST_Pricing_Box_Shortcode::init();
}



if (!function_exists('st_notification_func')) {
    /**
     * PB Item Notification
     */
    function st_notification_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'type'            => 'notification',
            'icon'            => ''
        ), $atts );
        extract($atts);
        $html = '';
        $html .= "<div class=\"alert alert-{$type} st-noti st-noti-{$type}\">";
        $html .= '<button type="button" class="close st-noti-close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $html .= ($icon != '') ? '<i class="noti-icon '. esc_attr($icon) .'"></i>' : '';
        $html .= '<div class="alert-content">'.$content.'</div>';
        $html .= '<div class="clear"></div>';
        $html .= "</div>";
        $html = apply_filters('st_notification_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_notification', 'st_notification_func');
}



if (!function_exists('st_divider_func')) {
    /**
     * PB Item Divider
     */
    function st_divider_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'divider_type'  => 'space',
            'height'        => '',
            'margin_top' =>'',
            'margin_bottom' =>''
        ), $atts );
        extract($atts);
        $html = '';
        $class = 'st-divider st-divider-'. $divider_type;
        $html .= '<div class="'. esc_attr($class) .'" ';
        $html .= 'style="';

        $html .= (isset($height) && is_numeric($height) && $divider_type == 'space') ? ' height:'. $height .'px; ' : '';

        if($divider_type=='border'){
            $html .= (is_numeric($margin_top) ) ? ' margin-top:'. $margin_top .'px; ' : '';
            $html .= (is_numeric($margin_bottom) ) ? ' margin-bottom:'. $margin_bottom .'px; ' : '';
        }

        $html .= '"';
        $html .= '>';
        $html .= "</div>";
        $html = apply_filters('st_divider_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_divider', 'st_divider_func');
}



if (!class_exists('ST_Client_Shortcode')) {

    if (!class_exists('ST_Client_Shortcode')) {
        /**
         * PB Item ST_Carousel
         */
        class ST_Client_Shortcode {
            static $shortcode_count;
            static $initial_open;
            static $id;
            static $items;
            static $link_target;
            static $num_cols;



            /**
             * init function.
             *
             * @access public
             * @static
             * @return void
             */
            static function init() {
                add_shortcode( 'st_client', array(__CLASS__, 'client' ) );
                add_shortcode( 'st_clients', array(__CLASS__, 'clients' ) );
                self::$shortcode_count = 0;
                self::$link_target = '';
                self::$num_cols = 0;
            }

            /**
             * @access public
             * @static
             * @param mixed $atts
             * @param mixed $content
             * @return void
             */
            public static  function client( $atts, $content ) {
                $atts = shortcode_atts(array(
                    'title'             => '',
                    'url'               => '',
                    'image'             => '',
                    'image_id'          => '',
                    'index'             =>''
                ), $atts);
                extract($atts);
                self::$shortcode_count++;
                $link_target = self::$link_target;
                $img_info=  wp_get_attachment_image_src( $image_id, apply_filters('st_carousel_size','medium'));
                $out = '';

                $class = round(12/self::$items);

                $out .='<div class="item col-lg-'.$class.' col-md-'.$class.'">';
                if($link!=''){
                    $out .='<a href="'.$link.'" title="'.esc_attr($title).'" target="'.$link_target.'"><img src="'.$img_info[0].'"  alt=""/></a>';
                }else{
                    $out .='<img src="'.$img_info[0].'" title="'.esc_attr($title).'"  alt=""/>';
                }


                $out .='</div>';

                $out = apply_filters('st_client_item', $out, $atts);
                return $out;
            }


            /**
             *
             * @access public
             * @static
             * @param mixed $atts
             * @param mixed $content
             * @return void
             */
            public static function clients( $atts, $content ) {
                $atts = shortcode_atts(array(
                    'visible_items'  => '3', // number item display per page
                    'number_items' =>'', // total items
                    'link_target'=>'_blank'
                ), $atts);
                extract($atts);
                self::$shortcode_count = 0;
                self::$initial_open = $initial_open;
                self::$id = uniqid();
                self::$link_target = $link_target;



                $visible_items = intval($visible_items);
                if(intval($visible_items)<=0){
                    $visible_items = 3;
                }

                self::$items = $visible_items;

                $out = '';

                $out .= '<div id="carousel-'. self::$id .'"  class="st-carousel-w st-clients">';
                $out .= '<div class="st-carousel" data-items="'.$visible_items.'" >';
                $content = do_shortcode($content);
                $out .= $content;
                $out .= '</div>';

                $out .='<a class="prev" href="#"><i class="iconentypo-left-open-big"></i></a>';
                $out .='<a class="next" href="#"><i class="iconentypo-right-open-big"></i></a>';

                $out .='<span class="caro-pagination" href="#"></span>';
                $out .= '</div>';

                $out ='<div class="row">'.$out.'</div>';

                $out = apply_filters('st_clients', $out, $atts);
                return $out;
            }
        }
        ST_Client_Shortcode::init();
    }


}



if (!class_exists('ST_Icon_List_Shortcode')) {
    /**
     * PB Item Icon List
     */
    class ST_Icon_List_Shortcode {
        static $shortcode_count;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_icon_list', array(__CLASS__, 'st_icon_list' ) );
            add_shortcode( 'st_icon_lists', array(__CLASS__, 'st_icon_lists' ) );
            self::$shortcode_count = 0;
        }

        /**
         * st_icon_list function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_icon_list( $atts, $content ) {
            $atts = shortcode_atts(array(
                'title'             => '',
                'icon'              => '',
                'index'             =>'',
                'color_type'        => '',
                'color'=>''
            ), $atts);
            extract($atts);
            $out = '';
            $class = '';

            if($color_type=='custom' && $color!=''){
                $icon = ($icon != '') ? '<span class="il-icon" style="color: '.esc_attr($color).';"><i class="'. esc_attr($icon) .'"></i> </span>' : '';
            }else{
                $icon = ($icon != '') ? '<span class="il-icon color-icon"><i class="'. esc_attr($icon) .'"></i> </span>' : '';
            }



            $title = esc_html($title);
            $class .= 'icon-item  icl-'.($index+1);
            if ($index == 0){
                $class .= ' first';
            }

            if ($index == self::$shortcode_count-1){
                $class .= ' last';
            }

            if ($content != '') {
                $content = '<div class="st-il-content">'. do_shortcode(balanceTags($content), true) .'</div>';
                $class .= ' st-il-des';
            }

            if($title!=''){
                $title = '<h3  class="il-title">'.$title.'</h3>';
            }

            $out .= '<li class="'. esc_attr($class) .'">'. $icon . '<div class="il-inner">'. $title . $content .' </div></li>';
            $out = apply_filters('st_icon_list', $out, $atts);
            return $out;
        }


        /**
         * st_icon_lists function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_icon_lists( $atts, $content ) {
            $atts = shortcode_atts(array(
                'number_items' => ''
            ), $atts);
            extract($atts);
            self::$shortcode_count = intval($number_items);
            $id = uniqid();
            $out = '';
            $out = '<ul class="st-icon-list">';
            $out .= do_shortcode($content);
            $out.= '</ul>';
            $out = apply_filters('st_icon_lists', $out, $atts);
            return $out;
        }
    }
    ST_Icon_List_Shortcode::init();
}



if (!function_exists('st_button_func')) {
    /**
     * PB Item Button
     */
    function st_button_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'button_label'          => '',
            'link'                  => '',
            'style'                 => 'default',
            'custom_bg_color'       => '',
            'custom_label_color'    => '',
            'size'                  => '',
            'position'              => 'default',
            'link_target'           => '_self',
            'icon'                  => '',
            'margin_top' =>'',
            'margin_bottom' =>'',
            'is_block'=>'',
            // for link settings
            'id' =>'',
            'slug' =>'',
            'item_type' =>'',
            'type' =>'',
            'url' =>''
        ), $atts );


        extract($atts);
        $link = st_create_link($atts);
        $html  = $class = '';

        $class = 'btn '. esc_attr($size) .' ';
        $class .= ($style == 'custom' && $custom_label_color != '') ? 'btn-custom ' : 'btn-'.$style;

        if($is_block==1){
            $class.=' btn-block ';
        }

        $html .= ($position != 'default') ? '<div class="btn-wrap align-'. esc_attr($position) .'">' : '';
        $html .= '<a href="'. $link .'" target="'.$link_target.'" class="'. trim($class) .'" ';
        $html .= 'style="';
        $html .= ($style == 'custom' && $custom_bg_color != '') ? 'background-color:'. $custom_bg_color .'; background-image: none;' : '';
        $html .= ($style == 'custom' && $custom_label_color != '') ? 'color:'. $custom_label_color .';' : '';

        $html .= ($margin_top !== '' && is_numeric($margin_top)) ? " margin-top:{$margin_top}px;" : '';
        $html .= ($margin_bottom !== '' && is_numeric($margin_bottom)) ? " margin-bottom:{$margin_bottom}px; " : '';

        $html .= '"';
        $html .= '>';
        $html .= ($icon != '') ? '<i class="'. esc_attr($icon) .'"></i>' : '';
        $html .= $button_label;
        $html .= "</a>";
        $html .= ($position != 'default') ? '</div>' : '';
        $html = ($button_label == '' && $icon == '') ? '' : $html;
        $html = apply_filters('st_button_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_button', 'st_button_func');
}



if (!function_exists('st_iconbox_func')) {
    /**
     * PB Item iconbox
     */
    function st_iconbox_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'title'                 => '',
            'icon_type'             => 'icon',
            'icon'                  => '',
            'image'                 => '',
            'icon_size'             => 'small',
            'image_size'            => 'thumbnail',
            'icon_position'         => 'top',
            'text_align'            =>'left',
            'color_type'            =>'default',
            'color'                 =>'',
            'effect'=>''

        ), $atts );
        extract($atts);
        $html = '';

        $title = ($title != '') ? '<h3 class="iconbox-title">'. $title .'</h3>' :  '';
        $icon_html =  '';
        $classes = array();

        $classes[] = 'icon-align-'.$icon_position;
        $classes[] = 'text-align-'.$text_align;

        if( $icon != '') {
            $classes[] = 'icon-icon';
            $classes[] = 'icon-'.$icon_size;

            $icef=  st_effect_attr($effect);


            if($color_type=='custom'&& $color!=''){
                $icon_html =  '<div class="icon-iconbox '.$icef['class'].'"  '.$icef['attr'].' style="color: '.esc_attr($color).'; " ><i class="'. esc_attr($icon) .'"></i></div>' ;
            }else{
                $icon_html =  '<div class="icon-iconbox '.$icef['class'].'"  '.$icef['attr'].'><i class="color-icon '. esc_attr($icon) .'"></i></div>' ;
            }

        }

        $content = ($content != '') ? '<div class="iconbox-content">'. $content .'</div>' : '';

        $html .= '<div class="st-iconbox '.join(' ',$classes).' clearfix">';
        $html .= $icon_html. $title. $content;
        $html .= '</div>';
        $html = apply_filters('st_iconbox_func', $html , $atts);
        return $html;
    }

    add_shortcode('st_iconbox', 'st_iconbox_func');
}



/**
 *
 * Items Media Elements
 *
 */
if (!function_exists('st_gallery_func')) {
    /**
     * PB Item Gallery
     */
    function st_gallery_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'gallery'       => '',
            'size'          => '',
            'columns'       => '',
            'lightbox'      => ''
        ), $atts );
        global $post;
        $tmpl_post =  $post;

        extract($atts);
        $html = '';
        $class_item = '';
        $html .= '<div class="st-gallery row">';
        $galleries = ($gallery != '') ? explode(',', $gallery) : '';
        $galleries = (is_array($galleries)) ? wp_parse_args($galleries) : '';
        if (is_array($galleries) && count($galleries) > 0) {

            $args =  array(
                'post_type'         => 'attachment',
                'posts_per_page'    => -1,
                'post_mime_type'    => 'image',
                'post_status'       => 'any',
                'post__in'          => $galleries,
                'orderby'           => 'post__in',
                'order'             => 'ASC'
            ) ;
            $wp_query = new WP_Query($args);
            $myposts =  $wp_query->posts;
            $j = 1;
            $count =  count($myposts);
            $columns = intval($columns);
            if($columns<=0){
                $columns =1;
            }

            // calculation last row item  do not remove
            if($count % $columns==0){
                $last_from = $count- $columns;
            }else{
                $last_from =  $count - ($count % $columns);
            }

            $num_class=  round(12/$columns);

            foreach($myposts as $i => $post) {
                $class_item = '';
                setup_postdata($post);
                $image_attributes = wp_get_attachment_image_src($post->ID, $size);
                $img_full_attributes = wp_get_attachment_image_src($post->ID, 'full');

                if ($image_attributes) {
                    $class_item .= 'st-gallery-item st-gallery-item-'. ($i+1) .' ';
                    $class_item .= ($i == 0) ? 'st-gallery-item-first ' : '';
                    $class_item .= (($i+1) == count($galleries)) ? 'st-gallery-item-last ' : '';
                    $class_item .= 'columns col-lg-'.$num_class.' col-md-'.$num_class.' ';
                    if($i>=$last_from){
                        $class_item.=' gallery-last-row ';
                    }

                    $html .= '<div class="'. esc_attr(trim($class_item)) .'">';
                        $class_lightbox = '';
                        $class_lightbox .= ($lightbox == 'yes') ? 'image-lightbox' : '';
                        $html .= '<a class="'. $class_lightbox .'" title="'. esc_attr($post->post_excerpt) .'" href="'. esc_url($img_full_attributes[0]) .'">';
                        $html .= '<img src="'. esc_url($image_attributes[0]) .'" alt="'. esc_attr($post->post_excerpt) .'" />';
                        $html .= '</a>';
                    $html .= '</div>';

                    if($j==$columns){
                        $html .='<div class="clear"></div>';
                        $j =1;
                    }else{
                        $j++;
                    }


                }
            }
            wp_reset_query();
            wp_reset_postdata();
            $post =  $tmpl_post;
            setup_postdata($post);

        }
        $html .= '<div class="clear"></div>';
        $html .= '</div><!-- end .st-gallery -->';
        $html = apply_filters('st_gallery_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_gallery', 'st_gallery_func');
}



if (!function_exists('st_simple_slider_func')) {
    /**
     * PB Item: Not sure
     */
    function st_simple_slider_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'images'            => '',
            'size'              => '',
            'is_top_slider'    => false
        ), $atts );

        global $post;

        $tmpl_post = $post;

        extract($atts);
        $images = ($images != '') ? explode(',', $images) : '';


        if(trim($size)==''){
            $size = apply_filters('st_simple_slider_size','large');
        }

        $html = '';
        $id = 'slider-'.uniqid();
        $indicators =  '';
        $html_items = '';

        if (is_array($images) && count($images) > 0) {
            $args =  array(
                'post_type'         => 'attachment',
                'posts_per_page'    => -1,
                'post_mime_type'    => 'image',
                'post_status'       => 'any',
                'post__in'          => $images,
                'orderby'           => 'post__in',
                'order'             => 'ASC'
            ) ;

            $wp_query = new WP_Query($args);
            $myposts =  $wp_query->posts;
            $j = 0;

            foreach($myposts as $i => $post) {
                setup_postdata($post);
                $image_attributes = wp_get_attachment_image_src($post->ID, $size);
                $img_full_attributes = wp_get_attachment_image_src($post->ID, 'full');
                if ($image_attributes) {
                    $indicators .= '<li class="" data-slide-to="'.$j.'" data-target="#'.$id.'"></li>';
                    $html_items .= '<div class="item'.( ($j==0) ? ' active' : '' ) .'">';
                    $html_items .= '<img src="'. esc_url($image_attributes[0]) .'" alt="'. esc_attr($post->post_title) .'" />';
                    $html_items .= '</div>';
                    $j++;
                }

            }


            wp_reset_query();
            wp_reset_postdata();
            $post=  $tmpl_post;
            setup_postdata($post);
        }

        $html.='<div class="carousel slide" id="'.$id.'">';
            $html.='<ol class="carousel-indicators">'.$indicators.'</ol>';

            $html .=' <div class="carousel-inner">';
                $html .= $html_items;
            $html .='</div>';

            $html .= ' <a class="left carousel-control"  href="#'.$id.'"data-slide="prev">'.apply_filters('st_slider_prev',' <span class="icon-prev"></span> ').'</a> ' ;
            $html .= ' <a class="right carousel-control" href="#'.$id.'"  data-slide="next">'.apply_filters('st_slider_next','<span class="icon-next"></span>').'</a>';

           // $html .=' <a data-slide="prev" href="#'.$id.'" class="left carousel-control"> <span class="icon-prev"></span> </a>';
           // $html .=' <a data-slide="next" href="#'.$id.'" class="right carousel-control">  <span class="icon-next"></span> </a>';

        $html .='</div>';

        $html = apply_filters('st_simple_slider_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_simple_slider', 'st_simple_slider_func');
}


if (!class_exists('ST_Slider')) {
    /**
     * PB Item Simple Slider
     */
    class ST_Slider {
        static $shortcode_count;
        static $initial_open;
        static $id_slider;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_slider', array(__CLASS__, 'slider' ) );
            add_shortcode( 'st_slider_item', array(__CLASS__, 'item' ) );
            self::$shortcode_count = 0;
            self::$initial_open = 0;
        }

        /**
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function item( $atts, $content ) {
            $atts = shortcode_atts(array(
                'title'             => '',
                'image_id'          => '',
                'index'             =>0,
                'link'              =>'',
                'size'              =>'',
                'link_target'       =>'_self'
            ), $atts);
            extract($atts);
            self::$shortcode_count++;
            if($size==''){
                $size = apply_filters('st_slider_size','large') ;
            }

            $img_info=  wp_get_attachment_image_src( $image_id,$size);
            $out = '';

            $class='';
            if($index==0){
                $class =' active';
            }

            $out .='<div class="item'.$class.'">';
            if($link!=''){
                $out .='<a href="'.$link.'" target="'.$link_target.'"><img src="'.$img_info[0].'"  alt=""/></a>';
            }else{
                $out .='<img src="'.$img_info[0].'"  alt=""/>';
            }



            if($title!='' || $content!=''){
                $out .=' <div class="carousel-caption"> ';
                if($title!=''){
                    $out.='<h3>'.esc_html($title).'</h3>';
                }

                if($content!=''){
                    $out.='<div class="caption-cont">'.esc_html($content).'</div>';
                }

                $out .='</div>';
            }
            $out .='</div>';

            $out = apply_filters('st_slider_item', $out, $atts);
            return $out;
        }


        /**
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function slider( $atts, $content ) {
            $atts = shortcode_atts(array(
                'style'  => 'list'
            ), $atts);
            extract($atts);
            self::$shortcode_count = 0;
            self::$initial_open = $initial_open;
            self::$id_slider = uniqid();
            $out = '';

            $out .= '<div id="slider-'. self::$id_slider .'" class="carousel slide">';

            $content = do_shortcode($content);

            $out .= '<ol class="carousel-indicators">'; // <!-- Indicators -->

            for($i=0; $i<self::$shortcode_count; $i++) {
                $active = ($i == 0) ? 'active' : '';
                $out .= '<li data-target="#slider-'. self::$id_slider .'" data-slide-to="'. $i .'" class="'. $active .'"></li>';
            }


            $out .= '</ol>';

            $out .= '<div class="carousel-inner">';
            $out .= $content;
            $out .= '</div>';


            $out .= ' <a class="left carousel-control" href="#slider-'. self::$id_slider .'" data-slide="prev">'.apply_filters('st_slider_prev',' <span class="icon-prev"></span> ').'</a> ' ;
            $out .= ' <a class="right carousel-control" href="#slider-'. self::$id_slider .'" data-slide="next">'.apply_filters('st_slider_next','<span class="icon-next"></span>').'</a>';

            $out .= '</div>';

            $out = apply_filters('st_slider', $out, $atts);
            return $out;
        }
    }
    ST_Slider::init();
}


if (!class_exists('ST_Carousel')) {
    /**
     * PB Item ST_Carousel
     */
    class ST_Carousel {
        static $shortcode_count;
        static $initial_open;
        static $id;
        static $items;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_carousel', array(__CLASS__, 'carousel' ) );
            add_shortcode( 'st_carousel_item', array(__CLASS__, 'item' ) );
            self::$shortcode_count = 0;
            self::$initial_open = 0;
        }

        /**
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function item( $atts, $content ) {
            $atts = shortcode_atts(array(
                'title'             => '',
                'image_id'          => '',
                'index'             =>0,
                'link'              =>'',
                'link_target'       =>'_self'
            ), $atts);
            extract($atts);
            self::$shortcode_count++;

            $img_info=  wp_get_attachment_image_src( $image_id, apply_filters('st_carousel_size','medium'));
            $out = '';

            $class = round(12/self::$items);

            $out .='<div class="item col-lg-'.$class.' col-md-'.$class.'">';
            if($link!=''){
                $out .='<a href="'.$link.'" target="'.$link_target.'"><img src="'.$img_info[0].'"  alt=""/></a>';
            }else{
                $out .='<img src="'.$img_info[0].'"  alt=""/>';
            }

            if($title!='' || $content!=''){
                $out .=' <div class="carousel-caption"> ';
                if($title!=''){
                    $out.='<h3>'.esc_html($title).'</h3>';
                }

                if($content!=''){
                    $out.='<div class="caption-cont">'.esc_html($content).'</div>';
                }

                $out .='</div>';
            }
            $out .='</div>';

            $out = apply_filters('st_carousel_item', $out, $atts);
            return $out;
        }


        /**
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function carousel( $atts, $content ) {
            $atts = shortcode_atts(array(
                'visible_items'  => '3', // number item display per page
                'number_items' =>'' // total items
            ), $atts);
            extract($atts);
            self::$shortcode_count = 0;
            self::$initial_open = $initial_open;
            self::$id = uniqid();



            $visible_items = intval($visible_items);
            if(intval($visible_items)<=0){
                $visible_items = 3;
            }

            self::$items = $visible_items;

            $out = '';

            $out .= '<div id="carousel-'. self::$id .'"  class="st-carousel-w">';
                $out .= '<div class="st-carousel" data-items="'.$visible_items.'" >';
                    $content = do_shortcode($content);
                    $out .= $content;
                $out .= '</div>';

                $out .='<a class="prev" href="#"><i class="iconentypo-left-open-big"></i></a>';
                $out .='<a class="next" href="#"><i class="iconentypo-right-open-big"></i></a>';

                $out .='<span class="caro-pagination" href="#"></span>';
            $out .= '</div>';


            $out ='<div class="row">'.$out.'</div>';

            $out = apply_filters('st_carousel', $out, $atts);
            return $out;
        }
    }
    ST_Carousel::init();
}




if (!function_exists('st_image_func')) {
    /**
     * PB Item Image
     */
    function st_image_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'image'                 => 0,
            'size'                  => 'thumbnail',
            'position'              => '',
            'link_type'             => 'lightbox',
            'link'                  => '#',
            'link_target'           => '_self',
            'effect' =>''
        ), $atts );
        extract($atts);

        if($link_type==''){
            $link_type='lightbox';
        }

        $image = intval($image);

       // $link ='';
        $html = '';
        $html .= '<div class="st-image st-image-'. $position .'">';
        $image_infor = get_post( $image);
       // $thumb = wp_get_attachment_image($image, $size, $attr = array('alt' => esc_attr(@$image_infor->post_title)) );

        $thumb =  wp_get_attachment_image_src( $image, $size  );
        $thumb = $thumb[0];
        if($thumb!=''){
            $eef = st_effect_attr($effect);
            $thumb ='<img class="'.$eef['class'].'" '.$eef['attr'].' src="'.esc_url($thumb).'" alt=""/>';
        }

        if($link!='' && $link_type!='none'){
            $a_class= 'image-normal';
            if($link_type=='lightbox'){
                $full = wp_get_attachment_image_src( $image, 'full' );

                $link =$full[0];
                $a_class = 'single-lightbox';
            }



            $html .= ($link != '') ? '<a class="'.$a_class.'" href="'. esc_url($link) .'" target="'. esc_attr($link_target) .'" title="'. esc_attr($image_infor->post_title) .'">' : '';
        }else{

        }

        $html .= ($thumb != '') ? $thumb : '';
        $html .= ($link != '') ? '</a>' : '';
        $html .= '</div>';
        $html = apply_filters('st_image_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_image', 'st_image_func');
}



if (!function_exists('st_video_func')) {
    /**
     * PB Item Video
     */
    function st_video_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'video'     => '',
            'ratio'     => '',
            'width'     => '',
            'height'    => '',
            'no_fit' =>'',
            'style'=>''
        ), $atts );
        extract($atts);
        if ($ratio == 'custom') {
            $rt = (int)$width .':'. (int)$height;
        }
        else {
            $rt = $ratio;
        }
        $html = $attrs = '';
        $r ='';
        if($no_fit === false || $no_fit=='no-fit'){
            $attrs .= ' class="no-fit" ';
        }

         if($style!=''){
             $attrs .=' style="'.$style.'" ';
         }

        $content = st_get_video($video, $rt,$r, $attrs);
        $html .= ($content != '') ? '<div class="st-video">'. $content .'</div>' : '';
        $html = apply_filters('st_video_func', $html , $atts);
        return $html;
    }
    add_shortcode('st_video', 'st_video_func');
}


/**
 *
 * Items Posts Elements
 *
 */
if (!function_exists('st_blog_func')) {
    /**
     * BP Item Blog
     */
    function st_blog_func($atts, $content='') {
        $atts = shortcode_atts( array(
            'number'          => -1,
            'cats'            => array(),
            'exclude'         => array(),
            'include'         => array(),
            'offset'          => 0,
            'thumbnail_type'  =>'',
            'columns'         =>1,
            'display_style'=> 'list',
            'excerpt_length'  => 70,
            'pagination'      => 'no',
            'order_by'        => 'ID',
            'order'           => 'desc'
        ), $atts );

        extract($atts);
        global $post, $paged;

        $current_post_options =  ST_Page_Builder::get_page_options($post->ID);

        $html = '';
        if(is_string($cats)){
            $cats = explode(',',$cats);
        }

        if (is_array($cats) && count($cats)>0) {
            if (in_array(0, $cats)) $cats = array();
        }
        $exclude = ($exclude != '') ? explode(',', $exclude) : $exclude;
        $include = ($include != '') ? explode(',', $include) : $include;
        $number = ($number != '') ? $number : get_option('posts_per_page', 10);
        $args = array(
            'posts_per_page'    => (int)$number,
            'category__in'      => (array)$cats,
            'post__not_in'      => $exclude,
            'post__in'          => $include,
            'offset'            => $offset,
            'orderby'           => $order_by,
            'order'             => $order
        );
        if($paged > 0){
            $args['paged'] =  $paged;
        } else {
            $paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']) : 1;
            $args['paged'] =  $paged;
        }
        if(st_is_wpml()) {
            $args['sippress_filters'] = true;
            $args['language'] = get_bloginfo('language');
        }

        $wp_query = new WP_Query( $args );
        $myposts =  $wp_query->posts;
        $func = st_excerpt_length( $excerpt_length );
        $columns = intval($columns);

        // support display type list or gird only
        if(!in_array($display_style, array('list','gird') ) ){
            $display_style ='list';
        }

        /*
         blog loop templates:
            Gird:
            1 -loop-post-gird-{$columns}.php // (optional) template for each column
            2 -loop-post-gird.php  // (optional) if number columns larger than 1 if have not any templates above

            List:
            3 -loop-post-list.php          // (optional) for display style as list

            Default:
            4 -loop-post.php                // (requested) if have not any templates above
         */


        $file_template = st_get_template('loop/loop-post.php');
        $thumb_size = 'large';


        if($display_style=='gird'){ // display gird
            if(is_file(st_get_template('loop/loop-post-gird-'.$columns.'.php'))){
                $file_template = st_get_template('loop/loop-post-gird-'.$columns.'.php') ;
            }elseif(is_file(st_get_template('loop/loop-post-gird.php')) ){
                $file_template = st_get_template('loop/loop-post-gird.php') ;
            }

            if(isset($thumbnail_type) && $thumbnail_type!=''){
                if($columns<2){
                    if($current_post_options['layout']=='no-sidebar'){
                        $thumb_size ='blog-full';
                    }else{
                        $thumb_size ='blog-large';
                    }
                }elseif($columns>3){
                    $thumb_size ='blog-medium';
                }else{
                    $thumb_size ='blog-large';
                }
            }

        }else{ // display list
            if(is_file(st_get_template('loop/loop-post-list.php'))){
                $file_template = st_get_template('loop/loop-post-list.php') ;
            }


            if(isset($thumbnail_type) && $thumbnail_type!=''){
                if($thumbnail_type=='full-width'){
                    if($current_post_options['layout']=='no-sidebar'){
                        $thumb_size ='blog-full';
                    }else{
                        $thumb_size ='blog-large';
                    }
                }else{

                    if($current_post_options['layout']=='no-sidebar'){
                        $thumb_size ='blog-large';
                    }else{
                        $thumb_size ='blog-medium';
                    }

                }

            }


        }


        if($display_style!='gird'){ // list display
            foreach( $myposts as $i => $post ) {
                setup_postdata($post);
                $html .= st_get_content_from_file($file_template, array('thumbnail_type'=>$thumbnail_type,'thumb_size'=>$thumb_size ));
            }
        }else{  // gird display
            $j = 0;
            $c=1;
            $groups=  array();
            $col_num = intval(12/$columns);
            foreach( $myposts as $i => $post ) {
                setup_postdata($post);
                $groups[$j][] = '<div class="blog-style '.stpb_number_to_words($col_num).'">'.st_get_content_from_file($file_template, array('item_index'=> $i, 'thumbnail_type'=>$thumbnail_type, 'thumb_size'=>$thumb_size )).'</div>';
                if($c==$columns){
                    $c=1; $j++;
                }else{
                    $c++;
                }
            }

            $n = count($groups);
            foreach($groups as $i => $g){
                $html.= join(' ',$g);
                if($i<$n-1){
                    $html .='<div class="clearfix item-sp item-index-'.$i.'"></div>';
                }else{
                    $html .='<div class="clearfix item-sp item-index-'.$i.' last"></div>';
                }
            }
        }

        $p = '';
       // if(!is_home() && !is_front_page()) { // only true if not is home page or front page
            if($pagination == 'yes'){
                $p =  st_post_pagination($wp_query->max_num_pages,2, false);
                if($p != ''){
                    $p = '<div class="st-pagination-wrap">'. $p .'</div>';
                }
            }
       // }


        wp_reset_query();
        remove_filter('excerpt_length', $func);

        $html = '<div class="list-post '.( ($display_style=='gird') ? 'gird row' : 'list').'">'.$html.'</div>';

        $html = apply_filters('st_blog_func', $html . $p, $atts);
        return $html;
    }
    add_shortcode('st_blog', 'st_blog_func');
}



if (!function_exists('st_wc_products_func')) {
    /**
     * BP WC Products
     */
    function st_wc_products_func($atts, $content='') {
        global $wp_query, $woocommerce;
        global $post, $paged;
        $tmp_post = $post;

        $atts = shortcode_atts( array(
            'cats'              => array(),
            'number'            => 3,
            'columns'           => 3,
            'exclude'           => '',
            'include'           => '',
            'offset'            => 0,
            'pagination'        => 'no',
            'hide_free'         => 'yes',
            'order_by'           => 'ID',
            'order'             => 'DESC'
        ), $atts );
        extract($atts);
        if(!st_is_woocommerce()){
            return '';
        }


        $html =   $cat_link = $cat_title='';
        // just only for one cate
        if(is_string($cats)){
            $cats = explode(',',$cats);
        }

        $cats =  $cats[0];
        if(intval($number) <= 0) {
            $number = (int)get_option('posts_per_page', 10);
        } else {
            $number = intval($number);
        }
        $args = array( 'posts_per_page' => (int)$number, 'offset' => (int)$offset );
        if($cats > 0) {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => array($cats),
                    'operator' => 'IN'
                )
            );
        }
        $exclude = ($exclude != '') ? explode(',', $exclude) : $exclude;
        $include = ($include != '') ? explode(',', $include) : $include;
        $args['post__not_in']   = $exclude;
        $args['post__in']       = $include;
        // custom order by meta key
        if(isset($order_by[0])  && $order_by[0]=='_'){
            $order_by = substr($order_by, 1);
            $args['meta_key'] 		 = $order_by;
            $args['orderby'] 		 = 'meta_value_num';
            $args['meta_query'] = array();

            if ( $hide_free == 'yes' ) {
                $args['meta_query'][] = array(
                    'key'     => '_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'DECIMAL',
                );
            }

            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
        } else {
            $args['orderby'] = $order_by;
        }
        $args['order'] = $order;
        if($paged > 0) {
            $args['paged'] =  $paged;
        } else {
            $paged =  isset($_REQUEST['paged']) ?  intval($_REQUEST['paged']) : 1;
            $args['paged'] =  $paged;
        }
        $args['post_type']='product';
        $args['post_status']='publish';
        if(st_is_wpml()){
            $args['sippress_filters'] = true;
            $args['language'] = get_bloginfo('language');
        }
        $new_query = new WP_Query($args);
        $myposts =  $new_query->posts;
        $i = 0;
        $e = '';
        ob_start();
        $old_content = ob_get_clean();

        if($type==3 && count($myposts)%2!=0){
            $myposts[] =  false;
        }

       // $image_size = 'large'; // ***

        // show columns
        $columns =  intval($columns);
        $columns =  ($columns > 0  && $columns <= 6)  ? $columns :  6;

        // echo $num_col; die();
        global $woocommerce_loop;
        $woocommerce_loop['columns'] =  $columns ; //apply_filters( 'loop_shop_columns', $columns );
        foreach( $myposts as $post ) :
            setup_postdata($post);
            global $product;
            $tpl ='';
            ob_start();
            woocommerce_get_template_part( 'content', 'product' );
            $tpl = ob_get_clean();
            $e .= $tpl;
            $i++;
        endforeach;

        $html .= $e;
        $html = '<div class="products row">'. $html .'</div>';
        $p = '';
       // if(!is_home() && !is_front_page()) { // only true if not is home page or front page
            if($pagination == 'yes'){
                $p =  st_post_pagination($new_query->max_num_pages, 2, false);
                if($p != ''){
                    $p = '<div class="st-pagination-wrap ">'. $p .'</div>';
                }
            }
       // }

        wp_reset_query();
        echo $old_content;

        $post  = $tmp_post;
        setup_postdata($post);


        $html = '<div class="woocommerce-wrap woocommerce  builder-item-wrapper">'. do_shortcode($html) . $p .'</div>';
        $html = apply_filters('st_wc_products_func', $html, $atts);
        return $html;
    }
    add_shortcode('st_products', 'st_wc_products_func');
}


if (!function_exists('ST_Team_member_Shortcode')) {
    /**
     * PB Team Memmber
     */
     function ST_Team_member_Shortcode($atts, $conent){



         $atts = shortcode_atts( array(
             'name'              => '',
             'job'            => 3,
             'image'           => 3,
             'size'           => 'thumbnail',
             'facebook'=>'',
             'twitter'=>'',
             'gplus'=>'',
             'linkedin'=>'',
             'skype'=>'',
             'stumbleupon'=>'',
             'dribbble'=>'',
             'picasa'=>'',
             'pinterest'=>'',
             'flickr'=>'',
         ), $atts );

         extract($atts);


         $html ='';

         $image =  wp_get_attachment_image_src( $image, $size, '' );
         $image = $image[0];

         if($image!=''){
             $html .= '<img src="'.esc_url($image).'" alt="">';
         }

         $html .=' <div class="about-info-inner"> ';

            if($job!=''){
                $job_html= ' / <span class="position">'.esc_html($job).'</span> ';
            }

            if($name!=''){
                $html.= ' <h3 class="name">'.esc_html($name).$job_html.'</h3> ';
            }
            

             if($conent!=''){
                 $html.= '  <div class="desc">'.do_shortcode(balanceTags($conent)).'</div> ';
             }

             $socials_html ='';

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

             foreach($socials as $id => $t){
                 if(isset($atts[$id] ) && $atts[$id]!=''){
                    $socials_html.=' <li><a href="'.esc_url($atts[$id]).'" target="_blank"><i class="iconentypo-'.$id.'"></i></a></li>';
                 }
             }

             if($socials_html!=''){
                 $socials_html ='<div class="social-about-wrap"> <ul class="social about-social nav-justified">'.$socials_html.' </ul> </div>';
             }

            $html.=$socials_html;

         $html .=' </div> ';
         $html= '<div class="team-member">'. $html .'</div>';

         return $html;
     }

     add_shortcode('st_team_member', 'ST_Team_member_Shortcode');
}


if (!class_exists('ST_Contact_Form_Shortcode')) {
    /**
     * PB Item ST_Contact_Form
     */
    class ST_Contact_Form_Shortcode {
        static $shortcode_count;
        static $id_contact;

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        static function init() {
            add_shortcode( 'st_contact_form_item', array(__CLASS__, 'st_contact_form_item' ) );
            add_shortcode( 'st_contact_form', array(__CLASS__, 'st_contact_form' ) );
            self::$shortcode_count = 0;
        }

        /**
         * st_contact_form_item function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static  function st_contact_form_item( $atts, $content ) {
            $atts = shortcode_atts(array(
                'label'         => '',
                'name'          => '',
                'field_type'    => '',
                'options'       => '',
                'field_width'   => '',
                'placeholder'   => '',
                'validation'    => '',
                'required'      => '',
                'css_class'     => ''
            ), $atts);
            extract($atts);
            self::$shortcode_count++;
            $out = '';
            $id_item = '_'. uniqid();
            $ops = explode('-|-', $options);
            $out .= '<div class="'. implode(' ', array('contact-field-item', 'field-type-'. $field_type, esc_attr($field_width), esc_attr($css_class), 'clearfix')) .'">';
            $out .= ($label && $field_type != 'submit') ? '<label for="'. $id_item .'">'. $label .'</label>' : '';
            if (in_array($field_type, array('checkbox', 'radio'))) {
                $attr = ' id="'. esc_attr($id_item) .'" field-type="'. esc_attr($field_type) .'" field-validation="" name="'. esc_attr($name) .'" class="field-control field-'. $field_type .'" aria-required="'. esc_attr($required) .'" placeholder="'. esc_attr($placeholder) .'"';
            } else {
                if (in_array($field_type, array('select'))) {
                    $validation = '';
                }
                $attr = ' id="'. esc_attr($id_item) .'" field-type="'. esc_attr($field_type) .'" field-validation="'. esc_attr($validation) .'" name="'. esc_attr($name) .'" class="form-control field-control field-'. $field_type .'" aria-required="'. esc_attr($required) .'" placeholder="'. esc_attr($placeholder) .'"';
            }
            $item = '';
            switch ($field_type){
            	case 'text':
                    $item .= '<input '. $attr .' type="text" />';
            	break;
            
            	case 'textarea':
                    $item .= '<textarea rows="10" '. $attr .'></textarea>';
            	break;
            
            	case 'select':
                    $item = '<select '. $attr .'>';
                    if (count($ops) > 0) {
                        foreach($ops as $op) {
                            $item .= '<option value="'.esc_attr($op).'" >'.esc_html($op).'</option>';
                        }
                    }
                    $item .= '</select>';
            	break;
                
                case 'checkbox':
                    if (count($ops) > 0) {
                        foreach($ops as $op) {
                            $item .= '<span class="contact-form-item-option"><input '. $attr .' type="checkbox" value="'.esc_attr($op).'" /> '. esc_html($op) .'</span>';
                        }
                    }
            	break;
                
                case 'radio':
                    if (count($ops) > 0) {
                        foreach($ops as $op) {
                            $item .= '<span class="contact-form-item-option"><input '. $attr .' type="radio" value="'.esc_attr($op).'" /> '. esc_html($op) .'</span>';
                        }
                    }
            	break;
                
                case 'date':
                    $item .= '<input '. $attr .' type="text" />';
            	break;
                
                case 'captcha':
                    $item .= '<img class="field-img-captcha" base-src="'. ST_PAGEBUILDER_URL .'inc/captcha/captcha.php?captcha='. $name .'" src="'. ST_PAGEBUILDER_URL .'inc/captcha/captcha.php?captcha='. $name .'" alt="'. __('Captcha', 'smooththemes') .'" />';
                    $item .= '<a title="'. __('Reload img captcha', 'smooththemes') .'" href="#" class="reload-img-captcha"><i class="iconentypo-arrows-ccw"></i></a>';
                    $item .= '<input '. $attr .' type="text" />';
            	break;
                
                case 'submit':
                    $item .= '<input field-type="'. esc_attr($field_type) .'" type="submit" name="" class="field-control field-submit" aria-required="'. esc_attr($required) .'" value="'. esc_attr($label) .'" />';
                    $item .= '<i class="contact-form-loader"><img src="'. ST_PAGEBUILDER_URL .'frontend/images/ajax-loader.gif" /></i>';
            	break;
            
            	default :
                break;
            }
            $item = apply_filters('st_contact_form_item_attr', $item, $attr);
            $out .=  $item .'</div>';
            $out = apply_filters('st_contact_form_item', $out, $atts);
            return $out;
        }


        /**
         * st_contact_form function.
         *
         * @access public
         * @static
         * @param mixed $atts
         * @param mixed $content
         * @return void
         */
        public static function st_contact_form( $atts, $content ) {
            $atts = shortcode_atts(array(
                'form_email_subject'    => '',
                'form_email_from_name'  => '',
                'form_email_from'       => '',
                'form_email_to'         => '',
                'form_email_body'       => '',
                'contact_form_mss_noti_captcha' => '',
                'contact_form_mss_success'       => '',
                'contact_form_mss_notification'       => ''
            ), $atts);
            extract($atts);
            self::$shortcode_count = 0;
            self::$id_contact = uniqid();
            $out = '';
            $out .= '<div class="st-contact-form clearfix" id="form-contact-'. self::$id_contact .'">';
            $out .= '<div class="alert contact-form-message"></div><div class="clear"></div>';
            $out .= '<form action="" method="post" class="st-contact-form-action clearfix">';
            $out .= do_shortcode($content);
            $out .= '<input type="hidden" value="'. esc_attr(($form_email_subject)) .'" class="contact-form-email-subject"/>';
            $out .= '<input type="hidden" value="'. esc_attr(($form_email_from_name)) .'" class="contact-form-from-name"/>';
            $out .= '<input type="hidden" value="'. esc_attr(($form_email_from)) .'" class="contact-form-email-from"/>';
            $out .= '<input type="hidden" value="'. esc_attr(($form_email_to)) .'" class="contact-form-email-to"/>';
            $out .= '<input type="hidden" value="'. esc_attr(($form_email_body)) .'" class="contact-form-email-body"/>';
            $out .= '<input type="hidden" value="'. esc_attr((base64_decode($contact_form_mss_noti_captcha))) .'" class="contact-form-mss-noti-captcha"/>';
            $out .= '<input type="hidden" value="'. esc_attr((base64_decode($contact_form_mss_notification))) .'" class="contact-form-mss-notification"/>';
            $out .= '<input type="hidden" value="'. esc_attr((base64_decode($contact_form_mss_success))) .'" class="contact-form-mss-success"/>';
            //$out .= '<i class="contact-form-loader"><img src="'. ST_PAGEBUILDER_URL .'frontend/images/ajax-loader.gif" /></i>';
            $out .= '</form>';
            $out .= '</div>';
            $out = apply_filters('st_contact_form', $out, $atts);
            return $out;
        }
    }
    ST_Contact_Form_Shortcode::init();
}

if (!function_exists('st_map_func')) {
    /**
     * PB Team Memmber
     */
    function st_map_func($atts, $content){
        $atts = shortcode_atts( array(
            'data'              => '',
            'zoom'              => 9,
            'color'             =>'',
            'height'            =>300,
            'desc_autop'        =>''
        ), $atts );
        extract($atts);
        $height = intval($height);

        if($height<=0){
            $height = 300;
        }

        $html ='';
        $data = json_decode(base64_decode($data));
        $id = 'map_'.uniqid();

        $zoom = intval($zoom);
        if($zoom<0){
            $zoom = 9;
        }

        if(!empty($data) &&  is_array($data)){
            $html .='<script type="text/javascript">/* <![CDATA[ */  var '.$id.' = '.json_encode($data).';   /* ]]> */ </script>';
            $html = '<div class="st-map-wrap" style="height: '.$height.'px;"> <div class="st-map" id="'.$id.'" map-color="'.esc_attr($color).'" map-zoom="'.$zoom.'" data-height="'.$height.'"  style="height: '.$height.'px;"></div> </div>'.$html;
        }

        return $html;
    }

    add_shortcode('st_map', 'st_map_func');
}



if (!function_exists('st_chart_func')) {
    /**
     * PB Chart
     *
     * [st_chart percent="60" size="150" lineWidth="20" barColor="#2f1d66" trackColor="#81d742"]
     */
    function st_chart_func($atts, $content=''){

        $atts = shortcode_atts( array(
            'title'=>'',
            'percent'              => '',
            'size'              => 150,
            'linewidth'             =>20,
            'barcolor'            =>'',
            'trackcolor'        =>'',
            'type'=>'number',
            'icon'=>''
        ), $atts );
        extract($atts);

        //var_dump($atts, $trackColor);

        if(!is_numeric($percent)){
            return '';
        }

        if(!is_numeric($size)){
            $size =  apply_filters('st_chart_size',150);
        }

        if(!is_numeric($linewidth)){
            $linewidth =  apply_filters('st_chart_linewidth',20);
        }

        if(!st_is_color($barcolor)){
            $barcolor =  apply_filters('st_chart_barcolor','#ff0800');
        }

        if(!st_is_color($trackcolor)){
            $trackcolor =  apply_filters('st_chart_trackcolor','#e2e2e2');
        }

        $html='';

        $html.='<div class="st-chart-wrap">';
            $html .= '<span style="height: '.$size.'px; width: '.$size.'px;" class="st-chart" ani-percent="'.$percent.'" data-type="'.esc_attr($type).'" size="'.$size.'" lineWidth="'.$linewidth.'" barColor="'.$barcolor.'" trackColor="'.$trackcolor.'"   data-percent="0">';

                if($type=='icon' && $icon!=''){
                    $html.='<span class="percent" style="height: '.$size.'px; width: '.$size.'px; line-height: '.$size.'px;"> <i class="'.esc_attr($icon).'"></i></span>';
                }else{
                    $html.='<span class="percent" style="height: '.$size.'px; width: '.$size.'px; line-height: '.$size.'px;"></span>';
                }

            $html.=' </span>';

        if($title!=''){
            $html.='<h2 class="chart-title">'.esc_html($title).'</h2>';
        }

        if(trim($content)!=''){
            $html.='<div class="chart-desc">'.do_shortcode($content).'</div>';
        }

        $html.='</div>';
        return $html;
    }

   add_shortcode('st_chart', 'st_chart_func');
}



if (!function_exists('st_progress_func')) {
    /**
     * PB progress
     *
     * [st_progress title=""] [/st_progress]
     */
    function st_progress_func($atts, $content=''){

        $atts = shortcode_atts( array(
            'title'=>'',
        ), $atts );
        extract($atts);

        $html ='<div class="st-progress">'.do_shortcode($content).'</div>';
        return $html;
    }

    add_shortcode('st_progress', 'st_progress_func');
}


if (!function_exists('st_progress_bar_func')) {
    /**
     * PB progress
     *
     * [st_progress_bar title="your title" percent="66"]
     */
    function st_progress_bar_func($atts, $content=''){

        $atts = shortcode_atts( array(
            'title'=>'',
            'percent'=>0,
            'color'=>'',
            'class'=>'',
            'style'=>''
        ), $atts );
        extract($atts);
        if(!is_numeric($percent)){
            $percent = 0;
        }

        if($color!='' && st_is_color($color)){
            $color =' background-color: '.$color.' ;';
        }

        $class='';

        if($style=='striped'){
            $class.=' progress-striped';
        }elseif($style=='animated'){
            $class.=' progress-striped active';
        }

        $html ='<div class="progress-bar-wrap '.esc_attr($class).'">';
            $html.='<div class="progress-title">'.esc_html($title).'</div>';
                $html.='<div class="progress '.$class.'" >';
                    $html.='<div class="progress-bar progress-bar-success"  role="progressbar" percent="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width: 0%; '.$color.'">';
                    $html.='<div class="inner-tooltip" data-placement="top" title="'.esc_attr($percent.'%').'"></div>';
                $html.='</div>';
            $html.='</div>';
        $html.='</div>';
        return $html;
    }

    add_shortcode('st_progress_bar', 'st_progress_bar_func');
}





if (!function_exists('st_counto_func')) {
    /**
     * PB CountTo
     *
     * [st_countto title="CountTo" form="0" to="100" speed="3000" number_color="#898989" text_color="#1e73be"]
     */
    function st_counto_func($atts, $content=''){

        $atts = shortcode_atts( array(
            'title'=>'',
            'to'=>100,
            'from'=>0,
            'speed'=>'',
            'number_color'=>'',
            'text_color'=>'',
            'size'=>'medium'
        ), $atts );
        extract($atts);
        if(!is_numeric($to)){
            $to = 0;
        }

        if(!is_numeric($from)){
            $from = 0;
        }
        if(!is_numeric($speed)){
            $speed = 100;
        }

        if($number_color!='' && st_is_color($number_color)){
            $number_color =' style="color: '.$number_color.' ; "';
        }

        if($text_color!='' && st_is_color($text_color)){
            $text_color =' style="color: '.$text_color.' ; "';
        }


        $html ='';

        $html .='<div class="st-counter '.$size.'">';

        $html .='<span class="counter-number" '.$number_color.' data-from="'.$from.'" data-to="'.$to.'" data-speed="'.$speed.'" data-refresh-interval="10">';
            $html.= $from;
        $html.='</span>';

        if($title!=''){
            $html .='<span class="counter-title" '.$text_color.'>';
                $html.= esc_html($title);
            $html.='</span>';
        }

        $html.='</div>';

        return $html;
    }

    add_shortcode('st_countto', 'st_counto_func');
}


