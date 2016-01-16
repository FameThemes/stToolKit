<?php
if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

add_action('stpb_row_settings','stpb_layout_row_settings',10,2);
add_action('stpb_col_settings','stpb_layout_column_settings',10,2);
add_action('edit_form_after_title',array('ST_Page_Builder_Interface','switch_editor'),10,1);


class ST_Page_Builder_Interface
{
    public $builder_items;
    public $post;
    public $id; // current post id
    public $no_value = false;
    public $saved_data;
    public $class_to_items_size;
    public $items_sizes;
    public $image_sizes=  array();
    public $sidebar_widgets = array();

    function __construct($id=false){

        $this->builder_items = ST_Page_Builder_Items_Config();

        if($id>0){
            $p = get_post($id);
            $this->post = $p;
            $this->id = $p->ID;
        }else{
            global $post, $pagenow;
            $this->post = $post;
            $this->id = $post->ID;
        }

        $this->saved_data = ST_Page_Builder::get_builder_settings($this->id, array());

        if(empty($this->saved_data) or !is_array( $this->saved_data) or $this->id <=0){
            $this->no_value = false;
            $this->saved_data=  array();
        }

       // parent::__construct();
        $this->items_sizes = ST_Page_Builder::get_builder_item_sizes();
        $this->class_to_items_size = ST_Page_Builder::class_to_items_size();


    }

    function  switch_editor(){
        global $post;

        $screens = apply_filters('st_page_builder_support',array('page'));
        if(!in_array($post->post_type,$screens)){
            return;
        }

        // if is shop page
        if($post->ID == st_get_shop_page()){
            return ;
        }

        $editor = get_post_meta($post->ID, '_st_current_editor', true);
        if($editor==''){
            $editor ='editor';
        }
        ?>
        <p>
            <a href="#" class="button-primary" id = "st-change-editor"
               editor-title="<?php _e('Switch to default Editor','smooththemes'); ?>"
               builder-title="<?php _e('Switch to Builder','smooththemes') ?>"><?php echo  ($editor=='editor') ?  __('Switch to Builder','smooththemes') : __('Switch to default Editor','smooththemes');  ?>
            </a>
            <input type="hidden" id="st_current_editor_value" name="_st_current_editor" value="<?php echo $editor; ?>" >
            <input type="hidden" id="st_page_builder_loaded" name="_st_page_builder_loaded" value="0" >

        </p>
        <?php
    }



    function get_sidebar_widgets(){
        if(empty($this->sidebar_widgets)){
            global $wp_registered_sidebars;

            foreach($wp_registered_sidebars as $sb){
                $this->sidebar_widgets[$sb['id']] = $sb['name'];
            }
        }
        return $this->sidebar_widgets;

    }

    function list_thumbnail_sizes(){
        if(empty($this->image_sizes)){

            global $_wp_additional_image_sizes;
            $sizes = array();
            foreach( get_intermediate_image_sizes() as $s ){
                $sizes[ $s ] = array( 0, 0 );
                if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
                    $sizes[ $s ][0] = get_option( $s . '_size_w' );
                    $sizes[ $s ][1] = get_option( $s . '_size_h' );
                }else{
                    if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
                        $sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], );
                }
            }

            foreach( $sizes as $size => $atts ){
                $this->image_sizes[$size] = ucwords(str_replace(array('-','_'),' ', $size) . ' (' . implode( 'x', $atts ) . ")");
            }

            $this->image_sizes['full'] ='Full';

        }

        return $this->image_sizes;
    }


    function  get_available_items()
    {
        $items = array(
            'text' => array(
                'name' => 'Text',
                'generate' => 'text_generate',
                'tooltip' => 'Create text',
                'icon' => $this->settings['url'] . 'assets/images/sc-text_block.png'
            )
        );

        return $items;
    }

    function get_layout_items()
    {
        $layouts = array(
            array(
                'class'=>'add-item-col',
                'tooltip'=>__('Add a column 100% witdh','smooththemes'),
                'width_id'=>'0',
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_11.png",
                'name'=>'1/1'
            ),
            array(
                'class'=>'add-item-col',
                'tooltip'=>__('Add a column 75% witdh','smooththemes'),
                'width_id'=>'1',
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_34.png",
                'name'=>'3/4'
            ),
            array(
                'class'=>'add-item-col',
                'tooltip'=>__('Add a column 66% witdh','smooththemes'),
                'width_id'=>'2',
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_23.png",
                'name'=>'2/3'
            ),

            array(
                'class'=>'add-item-col',
                'tooltip'=>__('Add a column 50% witdh','smooththemes'),
                'width_id'=>'3',
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_12.png",
                'name'=>'1/2'
            ),

            array(
                'class'=>'add-item-col',
                'tooltip'=>__('Add a column 33% witdh','smooththemes'),
                'width_id'=>'4',
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_13.png",
                'name'=>'1/3'
            ),

            array(
                'class'=>'add-item-col',
                'tooltip'=>__('Add a column 33% witdh','smooththemes'),
                'width_id'=>'5',
                'icon'=>ST_PAGEBUILDER_URL."assets/images/layout_14.png",
                'name'=>'1/4'
            )

        );

        return apply_filters('stpb_layout_items',$layouts);
    }


    function layout_item($item_func='',$item_data= array()){

        if(!function_exists($item_func)){
            $item_func = $item_data['builder_item_func'];
        }

        $curent_item = $this->builder_items[$item_func];

       //  echo $item_func;

        if(!function_exists($item_func) || empty($curent_item)){
            return false ;
        }

        $preview=  (isset($curent_item['preview']) && $curent_item['preview']===true) ? 'true' : 'false';

        ?>
        <div class="stpbe pbd-item draggable <?php echo $item_func; ?>" preview="<?php echo $preview; ?>" >
            <div class="pdb-item-inner">
                <div class="pdb-item-title"><?php echo esc_html($curent_item['title']); ?></div>
                <div class="item-builder-action p-act">
                    <span class="settings"><i class="iconentypo-pencil"></i></span>
                    <span class="clone"><i class="iconentypo-popup"></i></span>
                    <span class="item-remove"><i class="iconentypo-trash"></i></span>
                    <div class="clear"></div>
                </div><!-- /.item-action -->

                <input type="hidden" value="<?php echo $item_func; ?>"  data-name="[builder_item_func]" >

                <div class="pbd-item-settings" preview="<?php echo $preview; ?>" >
                    <input type="hidden" value="" class="st-current-index"  >
                    <input type="hidden" value="item" class="st-item-type" data-name="[type]" >
                    <input type="hidden" value="<?php echo $item_func; ?>" class="st-item-func" data-name="[item_func]" >
                    <?php
                     call_user_func($item_func,'[settings]',$item_data['settings'],$this->post,$this->no_value,$this);
                    ?>
                </div> <!-- pbd-item-settings -->

                <div class="clear"></div>
            </div><!-- /.pdb-item-inner -->
        </div><!--  /.pbd-item  -->
        <?php
    }

    function layout_column($col_data = array()){
        $ow = $col_data['width_id'];
        $tpl = str_replace('width-','',$col_data['width_class']);
        if($tpl==''){
            $tpl ='1-1';
        }

        if(!is_numeric($col_data['width_id']) || strpos($col_data['width_id'],'[')===true){
               $col_data['width_id'] = $this->class_to_items_size[$tpl];
        }

        if(!in_array($tpl, $this->items_sizes)){
             $tpl = $this->items_sizes[$col_data['width_id']];
             $col_data['width_class'] =  'width-'.$tpl;
        }

        ?>
        <div class="stpbe col-item<?php echo ($col_data['width_class']!='') ?  ' '.$col_data['width_class'] : '' ?>" width-id="<?php echo $col_data['width_id']; ?>">
            <div class="col-item-inner">
                <?php // echo var_dump($ow, $tpl, $col_data['width_id']); ?>
                <div class="item-col-action p-act">
                    <span class="down"><i class=" iconentypo-left-open-big"></i></span>
                    <span class="info"><?php

                        echo str_replace('-','/',$tpl);
                        ?></span>
                    <span class="up"><i class=" iconentypo-right-open-big"></i></span>
                    <span class="clone" title="<?php  _e('Duplicate','smooththemes'); ?>"><i class="iconentypo-popup"></i></span>
                    <span class="settings" title="<?php  _e('Settings','smooththemes'); ?>"><i class="iconentypo-cog"></i></span>
                    <span class="item-remove" title="<?php  _e('Remove','smooththemes'); ?>"><i class="iconentypo-trash"></i></span>

                    <div class="clear"></div>
                    <div class="hide col-settings stpb-item-settings">
                        <input type="hidden" value="" class="st-current-index"  >
                        <input type="hidden" value="column" class="st-item-type" data-name="[type]" >
                        <input type="hidden" value="<?php echo $col_data['width_id']; ?>" class="width-id" data-name="[width_id]" >
                        <input type="hidden" value="<?php echo $col_data['width_class']; ?>" class="width-class" data-name="[width_class]" >
                        <?php  do_action('stpb_col_settings',$col_data['settings'],$this->no_value); ?>
                    </div><!-- /.stpb-item-settings -->
                </div><!-- /.item-action -->

                <div class="p-builder-items">
                    <?php
                    if(!empty($col_data['items'])){
                        foreach($col_data['items'] as $item_data){
                             $this->layout_item($item_data['item_func'],$item_data);
                        }
                    }
                    ?>
                </div><!-- p-builder-items -->
            </div>
        </div> <!-- col-item -->
        <?php
    }

    function layout_row($row_data = array()){
        $row_data = wp_parse_args($row_data, array(
                                    'type'=>'row',
                                    'settings'=> array(),
                                    'columns'=> array()
                                ));


        if(!is_array($row_data['items'])){
            $row_data['items'] = array();
        }
        ?>
        <div class="stpbe item-row st-available-item width-1-1">
            <div class="item-row-innner items-area">

                <div class="item-action p-act">
                    <span class="add" title="<?php  _e('New column','smooththemes'); ?>"><i class=" iconentypo-window"></i></span>

                    <span class="clone" title="<?php  _e('Duplicate','smooththemes'); ?>"><i class="iconentypo-popup"></i></span>
                    <span class="settings" title="<?php  _e('Settings','smooththemes'); ?>"><i class="iconentypo-cog"></i></span>
                    <span class="item-remove" title="<?php  _e('Remove','smooththemes'); ?>"><i class="iconentypo-trash"></i></span>

                    <div class="clear"></div>

                    <div class="hide row-settings stpb-item-settings">
                         <input type="hidden" value="" class="st-current-index"   >
                         <input type="hidden" value="row" class="st-item-type" data-name="[type]" >
                        <?php  do_action('stpb_row_settings',$row_data['settings'], $this->no_value); ?>
                    </div><!-- /.stpb-item-settings -->
                </div><!-- /.item-action -->

                <div class="row-cols-wrapper">
                    <?php // echo var_dump($row_data); ?>
                    <div class="row-cols">
                        <?php
                        if(!empty($row_data['items'])){
                            foreach($row_data['items'] as $items){
                                if($items['type']=='column'){
                                    $this->layout_column($items);
                                }else{
                                    $this->layout_item($items['item_func'],$items);
                                }

                            }
                        }
                        ?>
                    </div><!-- /.row-cols -->
                </div><!-- /.row-cols-wrapper -->

            </div><!--  /.item-row-innner -->
        </div><!-- /.item-row -->
        <?php
    }


    function group_builder_items(){
        $tabs_group = array(
            'content'=> array(),
            'media'=> array(),
            'post'=> array()
            //'other'=>array()
        );

        $groups = array(
            'content',
            'media',
            'post',
            'other'
        );

        foreach($this->builder_items as $func => $item){
            $item['tab'] = strtolower($item['tab']);

            if($item['tab']=='content' || $item['tab']=='' || !isset($item['tab'])){
                $item['tab'] = $groups[0];
            }

            if(in_array($item['tab'],$groups)){
                $tabs_group[$item['tab']][$func] =  $item;
            }else{
                $tabs_group['content'][$func] =  $item;
            }

        }


        // echo var_dump($tabs_group);

       return apply_filters('stpb_group_builder_items',  $tabs_group);
    }


    function display()
    {
        // echo var_dump($this->saved_data);


        $builder_items_groups = $this->group_builder_items();

        ?>
        <div class="stpb-wraper">

        <div class="stpb-pagebuilder" id="stpb-pagebuilder">
        
            <div class="stpb-items-wrap stpb-tabs-wrap">
                <div class="tab-title">
                    <a href="#" class="tab active" for-tab=".layout-elements"><?php _e('Layout','smooththemes') ?> <span class="arr"></span> </a>

                    <?php foreach($builder_items_groups as $tab => $items){

                        $title= __('Content Elements','smooththemes');
                        switch($tab){
                            case 'media':
                                $title= __('Media Elements','smooththemes');
                                break;
                            case 'post':
                                $title= __('Posts Elements','smooththemes');
                                break;
                            case 'other':
                                $title= __('Other Elements','smooththemes');
                                break;
                        }

                        ?>
                      <a href="#" class="tab" for-tab=".tab-builder-<?php echo $tab; ?>-group"><?php echo $title; ?> <span class="arr"></span></a>
                    <?php } ?>


                    <a href="#" class="tab st-tab-actions tab-template" for-tab=".tab-template-act"><?php _e('Templates','smooththemes') ?> <span class="arr"></span></a>

                    <?php do_action('stpb_builder_tabs', $this); ?>
                </div>
            <div class="builder_header">
                <div class="tab-content layout-elements active">

                    <div class="builder-item-icon">

                        <?php
                        foreach($this->get_layout_items() as $layout){
                            ?>
                            <div data-tooltip="<?php  echo $layout['tooltip']; ?>" data-width-id="<?php echo $layout['width_id']; ?>" class="st-available-item st-tooltip add-item-col" title="">
                            <span class="item-icon">
                                <img src="<?php echo $layout['icon']; ?>" alt="">
                                <span class="item-name"><?php echo $layout['name']; ?></span>
                            </span>
                            </div>
                            <?php
                        }
                        ?>
                        <div data-tooltip="<?php _e('Add a section','smooththemes'); ?>" data-config-class="width-1-1" class="st-available-item st-tooltip add-item-row" title="">
                            <span class="item-icon">
                                <img src="<?php echo ST_PAGEBUILDER_URL; ?>/assets/images/layout_full.png" alt="">
                                <span class="item-name"><?php _e('Full Width','smooththemes'); ?></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>

                </div><!-- /. tab-content -->


                <?php foreach($builder_items_groups as $tab => $items){ ?>

                <div class="tab-content tab-builder-<?php echo $tab; ?>-group tab-group">
                    <div class="tab-group">
                    <?php
                    foreach($items as $func => $item){
                    $item_class="";
                    if($item['tooltip']!=''){
                    $item_class =' st-tooltip';
                    }

                    ?>
                    <div data-tooltip="<?php echo $item['tooltip']; ?>"  class="st-available-item add-content-element<?php echo $item_class; ?>" >
                            <span class="item-icon">
                                <!--
                                <?php if($item['icon']!=''){ ?>
                                    <img src="<?php echo $item['icon']; ?>" alt="icon">
                                <?php }else{ ?>
                                    <span class="no-icon"></span>
                                <?php } ?>
                                -->
                                <span class="item-name"><?php echo esc_html($item['title']); ?></span>
                            </span>

                        <?php if(function_exists($func)){ ?>
                            <div class="builder-tpl-item"><?php $this->layout_item($func); ?></div>
                        <?php } ?>
                    </div>
                    <?php

                    } ?>

                    <div class="clear"></div>
                    </div>
                </div>

                <?php } ?>


            <div class="tab-content tab-template-act">

                <div class="tab-group width-50 stpb-save-template">
                    <h4><?php _e('Save as Template','smooththemes'); ?></h4>
                    <div class=" input-save-tpl-name">
                        <input type="text" placeholder="<?php _e('Enter Template Name','smooththemes'); ?>" class="stpt-input  text template-name" >
                        <a  class="button-primary save" href="#"><?php _e('Save','smooththemes'); ?></a>
                        <div class="success"><?php _e('Your template saved.','smooththemes'); ?></div>
                    </div>
                </div><!-- /.save-template -->

                <div class="tab-group width-50 pb-templates">
                    <h4><?php _e('Select a template','smooththemes'); ?></h4>

                    <div class="list_templates" id="stpb-list-template">

                    </div>
                </div>

                <div class="clear"></div>
            </div><!-- /. tab-content -->



            </div><!-- /.stpb-available-items -->
        <!--  hidden templates -->
        <div class="builder-tpl-item item-row-tpl">
            <?php
              $this->layout_row();
            ?>
        </div><!--  builder-tpl-item -->
        <div class="builder-tpl-item item-col-tpl">
            <?php
            $this->layout_column();
            ?>
        </div><!--  builder-tpl-item -->
        <!-- end hidden templates -->

            <div class="canvas-actions">
               <!--
                <a href="#" class="stpb-load-template button-secondary" lightbox-title="<?php _e('Select template','smooththemes'); ?>"><i class="iconentypo-newspaper"></i><?php _e('Load Template','smooththemes'); ?></a>
                <a href="#" class="stpb-save-template button-secondary" lightbox-title="<?php _e('Save as Template','smooththemes'); ?>"><i class="iconentypo-cc-share"></i><?php _e('Save as Template','smooththemes'); ?></a>
               
                -->


            </div>

            </div>

            <div class="stpb-canvas-wrap">
                <div class="stpb-canvas-no-item">
                    <div class="tb">
                        <div class="text">
                            <?php _e('Welcome to your visual preview area...<br/>You don’t have any content at the moment. <br/><span>Click or Drag and Drop Builder items to this canvas.</span>','smooththemes'); ?>
                        </div>
                    </div>
                </div>
                <div class="stpb-canvas item-droppable sortable-list">
                    <?php
                    // $this->canvans();
                    ?>
                </div> <!-- ./stpb-canvas -->
            </div><!-- /.stpb-canvas-wrap -->

        </div><!--  /.stpb-pagebuilder -->
        </div><!-- /.stpb-wraper -->
    <?php

    }

    function canvans(){
        foreach($this->saved_data as $item){
            switch($item['type']){
                case 'row':
                    $this->layout_row($item);
                    break;
                case 'column':
                    $this->layout_column($item);
                    break;
                case 'item':
                    $this->layout_item($item['func'],$item);
                    break;
            }
        }
    }


}