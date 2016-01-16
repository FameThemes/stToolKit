<?php
if (  ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

/**
 * Created by JetBrains PhpStorm.
 * User: truongsa
 * Date: 7/26/13
 * Time: 11:21 PM
 * To change this template use File | Settings | File Templates.
 */

function st_input_class($classes=''){
    $pre = 'stpt-input ';
     if(is_array($classes)){
         $classes = array_filter($classes);
         array_unshift($classes, $pre);
         echo  join(' ',$classes);
     }elseif(is_string($classes)){
         echo trim($pre.' '.$classes);
     }
}

function stpb_get_img_src_by_id($post_id,$size = 'thumbnail'){
    $img = wp_get_attachment_image_src($post_id,$size);
    return $img[0];
}

/* =============================== NORMAL ELEMENTS ==================================== */

function stpb_input_text($name='', $save_value='', $class="",  $real_name =  false, $attr=''){
    ?>
    <input type="text" <?php echo $attr; ?> data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="<?php echo st_input_class(array('text', $class)); ?>" value="<?php echo esc_attr($save_value); ?>">
    <?php
}


function stpb_input_hidden($name='', $save_value='', $class="",  $real_name =  false, $attr=''){
    ?>
    <input type="hidden" <?php echo $attr; ?> data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="<?php echo st_input_class(array('input-hidden',$class)); ?>" value="<?php echo esc_attr($save_value); ?>">
    <?php
}

function stpb_input_password($name='', $save_value='', $class="",  $real_name =  false){
    ?>
    <input type="password" data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="<?php echo st_input_class(array('password',$class)); ?>" value="<?php echo esc_attr($save_value); ?>">
<?php
}

function stpb_input_radio($name='',$save_value = '', $value='', $real_name){
    ?>
    <input type="radio" data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> <?php echo ($save_value ==  $value) ? ' checked="checked" ' : ''; ?> class="<?php echo st_input_class(array('radio',$class)); ?>" value="<?php echo esc_attr($value); ?>">
    <?php
}

function stpb_input_checkbox($name='',$save_value = '', $value='', $class="",  $real_name =  false){
    ?>
    <input type="checkbox" data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> <?php echo ($save_value ==  $value) ? ' checked="checked" ' : ''; ?> class="<?php echo st_input_class(array('checkbox',$class)); ?>" value="<?php echo esc_attr($value); ?>">
    <?php
}

function stpb_input_textarea($name='', $save_value='', $class="", $quicktags = true,  $real_name =  false, $attr = ''){
    if($quicktags!==false){
        ?>
       <div class="st-editor">
           <textarea <?php echo $attr; ?>  data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="<?php echo st_input_class(array('textarea',$class)); ?>"><?php echo esc_attr($save_value); ?></textarea>
       </div>
       <?php
    }else{
        ?>
        <textarea  <?php echo $attr; ?> data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="<?php echo st_input_class(array('textarea',$class)); ?>"><?php echo esc_attr($save_value); ?></textarea>
        <?php
    }

}


/**
 * @param string $name
 * @param string $save_value
 * @param array $options
 * @param string $show_on_change
 * @param string $class
 * @param bool $real_name
 * @param string $attr
 */
function stpb_input_select_one($name='', $save_value='', $options = array(), $show_on_change ='', $class="",  $real_name =  false, $attr=''){
    ?>
    <select <?php echo $attr; ?> data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?>  show-on-change="<?php echo esc_attr($show_on_change); ?>" class="select <?php echo st_input_class(array('select-one',$class)); ?>">
        <?php foreach($options as $k => $op){
            $selected="";
            if($save_value==$k){
                $selected =' selected="selected" ';
            }
            echo '<option '.$selected.'value="'.esc_attr($k).'" >'.esc_html($op).'</option>';
        } ?>
    </select>
    <?php
}



function stpb_input_select_multiple($name='', $save_value='', $options = array(), $class="",  $real_name =  false){
    if(!is_array($save_value)){
        $save_value =  (array) $save_value;
    }
    ?>
    <select multiple="multiple" data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="select <?php echo st_input_class(array('select-multiple',$class)); ?>">
        <?php foreach($options as $k => $op){
            $selected= "";
            if(in_array( $k, $save_value) ){
                $selected = ' selected="selected" ';
            }
            echo '<option'.$selected.'value="'.esc_attr($k).'">'.esc_html($op).'</option>';
        } ?>
    </select>
<?php
}

/* =============================== ADVANCE ELEMENTS ==================================== */

function stpb_input_link($name='', $save_value='', $attr='' ,  $real_name =  false){

    $options = array(
        'custom'=>__('Custom Link','smooththemes'),
        'post_type'=>__('Content Elements','smooththemes'),
        'taxonomy'=>__('Taxonomies','smooththemes')
    );
     // echo var_dump( get_taxonomies( false, 'objects' ) );

    $link_data = array();
    if($save_value!=''){
        $link_data = (array) json_decode($save_value);
    }

    ?>
    <div class="input-link">
        <input type="hidden"  data-name="<?php echo $name; ?>" <?php echo $attr; ?> <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="stpt-input text link-data" value="<?php echo esc_attr($save_value); ?>">

        <div class="preview-link">
            <a class="change button-secondary"><?php _e('Change','smooththemes'); ?></a>
            <strong class="url"><?php echo st_create_link($link_data,'a'); ?></strong>
        </div>

        <div class="box-link">
            <select class="link-type">
                <?php foreach($options as $k => $op){
                    $selected="";
                    if($save_value==$k){
                        $selected =' selected="selected" ';
                    }
                    echo '<option '.$selected.'value="'.esc_attr($k).'" >'.esc_html($op).'</option>';
                } ?>
            </select>

            <div class="ajax-items"></div>
            <div class="ajax-select-link"></div>

           <p class="custom-link">
               <b><?php _e('Custom link','smooththemes'); ?></b>
               <input type="text" class="stpt-input text custom-link" value="<?php  echo esc_attr($link_data['url']); ?>">
           </p>

            <a class="link-close button-primary"><?php _e('Ok','smooththemes'); ?></a>
            <a class="link-cancel button-secondary"><?php _e('Cancel','smooththemes'); ?></a>
        </div>

    </div>

<?php
}



/**
 * @param string $name
 * @param string $save_value
 * @param array $values List item of layout
 * @param bool $real_name
 */

function stpb_input_layout($name='',$save_value = '', $values= array(), $real_name= false){
    if(!is_array($values)){
        $values = (array) $values;
    }
    ?>
    <div class="st-input-layout">
    <?php
    foreach($values as  $k  =>  $item){
    ?>
    <label>
        <img src="<?php echo $item['img'] ?>" alt="">
        <?php stpb_input_radio($name,$save_value,$k,  $real_name); ?>
    </label>
    <?php
    }
    ?>
    </div>
    <?php
}




function stpb_input_media($name='', $save_value='',$type = 'image', $title='', $class="", $real_name = false){

    $default_type = array(
        'image',
        'video',
        'audio'
        // 'gallery',
        // 'all'
    );

    $type=  strtolower($type);

    if($title==''){
        $title = __('Select Media','smooththemes');
    }

    if(strpos($save_value,']')!==false){
        $save_value ='';
    }

    ?>
    <span class="st-upload-media media-type-<?php echo esc_attr($type); ?>" data-type="<?php echo $type; ?>">
        <input type="<?php echo $type=='audio' ? 'text' :'hidden' ?>" data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="st-media-input <?php echo st_input_class(array('media',$class)); ?>" value="<?php echo esc_attr($save_value); ?>">
        <input type="button" class="st-upload-button button-primary"  value="<?php echo $title; ?>"/>
         <a href="#"  <?php  echo ($save_value!='')? '' : ' style="display: none;" ';  ?> class="remove-media" title="<?php _e('Remove','smooththemes') ?>"><i class="iconentypo-cancel"></i></a>
        <div class="media-preview-w">
            <div class="media-preview">

                <?php
                if($save_value!=''){

                    if($type=='gallery'){
                        $ids =explode(',',$save_value);
                        foreach($ids as $id){
                            $src = stpb_get_img_src_by_id($id);
                            ?>
                            <div class="mi"><div class="mid"><img src="<?php echo $src; ?>" alt=""></div></div>
                        <?php
                        }
                    }elseif($type=='image'){
                        $src = stpb_get_img_src_by_id($save_value);
                        ?>
                        <div class="mi"><div class="mid"><img src="<?php echo $src; ?>" alt=""></div></div>
                    <?php
                    }

                }
                ?>
            </div>
        </div>

    </span>

<?php
}

function stpb_input_categories($name='', $save_value='', $taxonomy = 'category'){
    if(!is_array($save_value)){
        $save_value = (array) $save_value;
    }
    if($taxonomy ==''){
        $taxonomy = 'category';
    }
    $select = wp_dropdown_categories('id=&show_count=1&taxonomy='.$taxonomy.'&orderby=name&echo=0&class=js-multiple+lb-chzn-select&hierarchical=1');
    $select = preg_replace("#<select([^>]*)>#", "<select$1   multiple=\"multiple\" selected-ids=\"".join(',',$save_value)."\"  data-name=\"{$name}[]\">", $select);
    echo $select;
}


function stpb_input_category($name='', $save_value='', $taxonomy = 'category'){
    if($taxonomy ==''){
        $taxonomy = 'category';
    }
    $select = wp_dropdown_categories('id=&show_count=1&taxonomy='.$taxonomy.'&orderby=name&echo=0&class=group-name++lb-chzn-select&hierarchical=1&seleced='.urlencode($save_value));
   // $select = preg_replace("#<select([^>]*)>#", "<select$1   multiple=\"multiple\" selected-ids=\"".join(',',$values['cat'])."\"  group-name=\"{$name}[[cat]\">", $select);
    echo $select;
}


function stpb_input_color($name='', $save_value='', $class="", $real_name= false, $attr=''){
    ?>
    <span class="st-color-wrap">
        <input type="text" <?php echo $attr; ?> data-name="<?php echo $name; ?>" <?php echo ($real_name===true) ? 'name="'.$name.'" ' : ''; ?> class="st-color-picker <?php echo st_input_class(array('text',$class)); ?>" value="<?php echo esc_attr($save_value); ?>">
    </span>
<?php
}

function stpb_input_upload($name='', $save_value='', $class=""){
    ?>
    <span class="st-upload-wrap">
        <input type="text" data-name="<?php echo $name; ?>" class="st-upload-input <?php echo st_input_class(array('text',$class)); ?>" value="<?php echo esc_attr($save_value); ?>">
        <input type="button" class="st-upload-button button-primary" value="<?php _e('Select image','smooththemes') ?>"/>
        <a href="#"  <?php  echo ($save_value!='')? '' : ' style="display: none;" ';  ?> class="remove_image" title="<?php _e('Remove','smooththemes') ?>"><i class="iconentypo-cancel"></i></a>
        <div class="upload-preview-w">
            <div class="upload-preview">
                <?php  echo ($save_value!='') ? ' <a  href="'.esc_attr($save_value).'" target="_blank"> <img src="'.esc_attr($save_value).'" alt=""/> </a>' : ''; ?>
            </div>
        </div>
    </span>
<?php
}


function stpb_input_icon($name='', $save_value='', $class=""){
    stpb_input_icon_popup($name, $save_value, $class);
    /*
    ?>
    <span class="st-icon-wrap">
        <input type="hidden" value="<?php echo esc_attr($save_value); ?>" class="st-icon-value" data-name="<?php echo $name; ?>">
    </span>
    <?php
    */
}


function stpb_input_icon_popup($name='', $save_value='', $class=""){
    ?>
    <div class="st-icon-popup-wrap">
        <div class="icon-action">
            <div class="selected-icon" title="<?php _e('Change icon','smooththemes'); ?>"><i class="<?php echo esc_attr($save_value); ?>"></i> </div>
            <label><?php _e('Icon name','smooththemes'); ?></label><br/>
            <input type="text" value="<?php echo esc_attr($save_value); ?>" class="st-icon-value" data-name="<?php echo $name; ?>">
        </div>
        <div class="clear"></div>
    </div>
<?php
}


function stpb_input_ui_tpl($name='', $item_value=array(), $supports= array(), $index='-1', $titles = array(), $more_fields = array()){
    $item_value = wp_parse_args($item_value, array(
             'title'=>'',
             'content'=>'',
             'image'=>'',
             'autop'=>'',
             'switch_icon_image'=>''
    ));

    if($item_value['switch_icon_image']=='' && $supports['icon'] == true){
        $item_value['switch_icon_image'] = 'icon';
    }

    ?>
    <div class="stpb-widget widget closed">
        <div class="ui-handlediv" title="<?php _e('Click to toggle','smooththemes') ?>"><i class="iconentypo-down-open"></i></div>
        <span class="remove stwrmt " href="#"><i class="iconentypo-cancel"></i></span>
        <div class="stpb-hndle">
            <i class="iconentypo-move algt"></i>

            <?php if( $supports['icon'] == true ||  $supports['image'] == true) { ?>
            <span class="thumb-previw">
                <div class="mi">
                </div>
            </span>
            <?php } ?>

            <?php if( $supports['title'] == true){ ?>
                <p class="algt">
                <?php  echo $titles['title']; ?>
                <span class="live-title"><?php echo esc_html($item_value['title']); ?></span>
                </p>
            <?php } ?>
        </div>
        <div class="inside">
            <div class="widget-content">

                <div class="widget-content">

                    <?php if( $supports['title'] == true){ ?>
                    <p><label><?php  echo $titles['title']; ?></label>
                    <input type="text" class="ui-title ui-input" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][title]'.'" ' : '';  ?> data-ui-name="<?php echo '[title]'; ?>" value="<?php echo esc_attr($item_value['title']); ?>" ></p>
                    <?php } ?>

                    <?php if( $supports['switch_icon_image'] == true  && $supports['icon'] == true && $supports['image'] == true) { ?>
                    <label><?php _e('Image Type:','smooththemes') ?></label>
                    <select <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][switch_icon_image]'.'" ' : '';  ?>  data-ui-name="<?php echo '[switch_icon_image]'; ?>" class="switch_icon_image select <?php echo st_input_class(array('select-one')); ?>">
                        <?php foreach( array('icon'=>__('icon','smooththemes'),'image'=>__('Image','smooththemes') ) as $k => $op){
                            $selected="";
                            if($item_value['switch_icon_image']==$k){
                                $selected =' selected="selected" ';
                            }
                            echo '<option '.$selected.'value="'.esc_attr($k).'" >'.esc_html($op).'</option>';
                        } ?>
                    </select>
                    <?php } ?>

                    <?php
                    if( $supports['image'] == true){
                    /*
                    ?>
                    <div class="ui-item-icon-image ui-item-image" <?php  echo $item_value['switch_icon_image']=='icon'? ' style="display: none;" ' :''; ?> >
                        <label><?php  echo $titles['image']; ?></label>
                        <span class="st-upload-wrap">
                            <input type="text" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][image]'.'" ' : '';  ?>  data-ui-name="<?php echo '[image]'; ?>" class="st-upload-input input-upload" value="<?php echo esc_attr($item_value['image']); ?>">
                            <input type="button" class="st-upload-button button-primary" value="<?php _e('Select image','smooththemes') ?>"/>
                             <a href="#" <?php  echo ($item_value['image']!='')? '' : ' style="display: none;" ';  ?> title="<?php _e('Remove','smooththemes') ?>" class="remove_image"><i class="iconentypo-cancel"></i></a>
                        </span>
                    </div>
                     */ ?>

                    <div class="ui-item-icon-image ui-item-image" <?php  echo $item_value['switch_icon_image']=='icon'? ' style="display: none;" ' :''; ?> >
                        <span class="st-upload-media media-type-image" data-type="image">
                            <input type="hidden" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][image_id]'.'" ' : '';  ?>  data-ui-name="<?php echo '[image_id]'; ?>" class="st-media-input media" value="<?php echo esc_attr($item_value['image_id']); ?>">
                            <input type="button" class="st-upload-button button-primary"  value="<?php _e('Slect image','smooththemes') ?>"/>
                             <a href="#"  <?php  echo ($item_value['image_id']!='')? '' : ' style="display: none;" ';  ?> class="remove-media" title="<?php _e('Remove','smooththemes') ?>"><i class="iconentypo-cancel"></i></a>
                            <div class="media-preview-w">
                                <div class="media-preview">

                                    <?php
                                    if($item_value['image_id']!=''){
                                        $src = stpb_get_img_src_by_id($item_value['image_id']);
                                        ?>
                                        <div class="mi"><div class="mid"><img src="<?php echo $src; ?>" alt=""></div></div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </span>
                    </div>

                    <?php } ?>

                    <?php if( $supports['icon'] == true){ ?>
                    <div class="ui-item-icon-image ui-item-icon" <?php echo $item_value['switch_icon_image']=='image'? ' style="display: none;" ' :''; ?>>

                        <label><?php  echo $titles['icon']; ?></label>
                        <div class="st-icon-popup-wrap">
                            <div class="icon-action">
                                <div class="selected-icon" title="<?php _e('Change icon','smooththemes'); ?>"><i class="<?php echo esc_attr($item_value['icon']); ?>"></i> </div>
                                <label><?php _e('Icon name','smooththemes'); ?></label><br/>
                                <input type="text" value="<?php echo esc_attr($item_value['icon']); ?>" class="st-icon-value"  <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][icon]'.'" ' : '';  ?> data-ui-name="<?php echo '[icon]'; ?>">
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php
                    if(is_array($more_fields)){
                        foreach($more_fields as $field){
                            if($field['type']=='heading'){
                                ?>
                                <div class="ui-heading">
                                   <?php echo $field['title']; ?>
                                </div>
                            <?php
                            }elseif($field['type']=='text'){
                                ?>
                                <p><label><?php  echo $field['title']; ?></label>
                                <input type="text" class="ui-input" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.']['.$field['name'].']'.'" ' : '';  ?> data-ui-name="<?php echo '['.$field['name'].']'; ?>" value="<?php echo esc_attr($item_value[$field['name']]); ?>" >
                                </p>
                                <?php
                            }else if($field['type']=='upload'){
                                ?>
                                <div class="ui-item-image" >
                                    <label><?php  echo $field['title']; ?></label>
                                    <span class="st-upload-wrap">
                                        <input type="text" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.']['.$field['name'].']'.'" ' : '';  ?>  data-ui-name="<?php echo '['.$field['name'].']'; ?>" class="st-upload-input input-upload" value="<?php echo esc_attr($item_value[$field['name']]); ?>">
                                        <input type="button" class="st-upload-button button-primary" value="<?php _e('Select image','smooththemes') ?>"/>

                                        <a href="#" <?php  echo ($item_value[$field['name']]!='')? '' : ' style="display: none;" ';  ?> class="remove_image" title="<?php _e('Remove','smooththemes') ?>"><i class="iconentypo-cancel"></i></a>
                                    </span>
                                </div>
                                <?php
                            }else if($field['type']=='link'){

                                ?>
                                <div class="ui-link">
                                <label><?php  echo $field['title']; ?></label>
                                <?php
                                stpb_input_link($name.'['.$index.']['.$field['name'].']', $item_value[$field['name']], ' data-ui-name="['.$field['name'].']" ' );
                                ?>
                                </div>
                                <?php

                            }else if($field['type']=='color'){

                                ?>
                                <div class="ui-color">
                                <label><?php  echo $field['title']; ?></label>

                                <?php
                                stpb_input_color($name.'['.$index.']['.$field['name'].']', $item_value[$field['name']],'',false, ' data-ui-name="['.$field['name'].']" ' );
                                ?>
                                </div>
                                <?php

                            }
                        }
                    }
                    ?>

                    <?php if( $supports['content'] == true){ ?>
                    <div class="st-editor">
                    <textarea class="ui-cont ui-input" rows="10" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][content]'.'" ' : '';  ?>  data-ui-name="<?php echo '[content]'; ?>"><?php echo esc_attr($item_value['content']); ?></textarea>
                    </div>
                    <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
                    <p><label><input type="checkbox" <?php echo $item_value['autop']==1 ? ' checked="checked" ' :''; ?>  <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][autop]'.'" ' : '';  ?> data-ui-name="<?php echo '[autop]'; ?>" value="1" class="ui-autop" >&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>

                    <?php } ?>

                </div>

                <div class="widget-control-actions">
                    <div class="alignleft">
                        <a class="remove" href="#remove"><?php _e('Remove','smooththemes') ?></a> |
                        <a class="close" href="#close"><?php _e('Close','smooththemes') ?></a>
                    </div>
                    <br class="clear">
                </div>
            </div>

        </div>
    </div>
    <?php
}

function stpb_input_ui_contact_tpl($name='', $item_value=array(), $supports= array(), $index='-1', $titles = array(), $more_fields = array()){
    $item_value = wp_parse_args($item_value, array(
            'label'=>'',
            'name'=>'',
            'field_type'=>'',
            'field_width'=>'',
            'placeholder'=>'',
            'required'=>'',
            'options'=>'',
            'css_class'=>'',
            'format'=>''
    ));
    
    $field_type = apply_filters('st_contact_field_type', array(
        'text' => __('Text', 'smooththemes'),
        'textarea' => __('Textarea', 'smooththemes'),
        'select' => __('Select', 'smooththemes'),
        'checkbox' => __('Checkbox', 'smooththemes'),
        'radio' => __('Radio', 'smooththemes'),
        'date' => __('Date', 'smooththemes'),
        'captcha' => __('Captcha', 'smooththemes'),
        'submit' => __('Submit', 'smooththemes'),
    ));
    
    $field_width = apply_filters('st_contact_field_width', array(
        'field-col1' => __('100%', 'smooththemes'),
        'field-col2' => __('50%', 'smooththemes'),
        'field-col3' => __('33%', 'smooththemes'),
    ));
    
    $validation = apply_filters('st_contact_validation', array(
        'none' => __('None', 'smooththemes'),
        'email' => __('Email', 'smooththemes'),
        'number' => __('Number', 'smooththemes'),
        'phone' => __('Phone', 'smooththemes'),
    ));
    
    $required = apply_filters('st_contact_required', array(
        'no' => __('No', 'smooththemes'),
        'yes' => __('Yes', 'smooththemes'),
    ));
    
    $show_on_name = apply_filters('st_contact_form_show_on_name', array(
        'text', 'textarea', 'select', 'radio', 'checkbox', 'date'
    ));
    
    $show_on_option = apply_filters('st_contact_form_show_on_option', array(
        'select', 'radio', 'checkbox'
    ));
    
    $show_on_placeholder = apply_filters('st_contact_form_show_on_placeholder', array(
        'text', 'textarea'
    ));
    
    $show_on_validation = apply_filters('st_contact_form_show_on_validation', array(
        'text'
    ));
    
    $show_on_required = apply_filters('st_contact_form_show_on_required', array(
        'text', 'select', 'radio', 'checkbox', 'textarea', 'date'
    ));
    ?>
    <div class="stpb-widget widget closed">
        <div class="ui-handlediv" title="<?php _e('Click to toggle','smooththemes') ?>"><i class="iconentypo-down-open"></i></div>
        <span class="remove stwrmt " href="#"><i class="iconentypo-cancel"></i></span>
        <div class="stpb-hndle">
            <i class="iconentypo-move algt"></i>

            <?php if( $supports['label'] == true){ ?>
                <p class="algt">
                <span class="live-type"><?php  echo ($item_value['field_type']) ? esc_html($item_value['field_type']) : __('Text', 'smooththemes'); ?> : </span>
                <span class="live-title"><?php echo esc_html($item_value['label']); ?></span>
                </p>
            <?php } ?>
        </div>
        <div class="inside">
            <div class="widget-content">

                <div class="widget-content">

                    <?php if( $supports['label'] == true){ ?>
                    <p>
                        <label><?php  echo $titles['label']; ?></label>
                        <?php 
                            $name_item = ($index>=0) ? $name.'['.$index.'][label]' : '';
                            stpb_input_text($name_item, esc_attr($item_value['label']), 'contact-form-item-label', false, 'data-ui-name="[label]"'); 
                        ?>
                    </p>
                    <?php } ?>
                    
                    <?php if( $supports['name'] == true){ ?>
                    <div class="st-builder-item-contact-form-item" show-on="<?php echo implode(' ', $show_on_name); ?>">
                    <p>
                        <label><?php  echo $titles['name']; ?>&nbsp;<span class="contact-form-item-name-label">[<?php echo esc_attr($item_value['name']); ?>]</span></label>
                        <?php 
                            $name_item = ($index>=0) ? $name.'['.$index.'][name]' : '';
                            stpb_input_hidden($name_item, esc_attr($item_value['name']), 'contact-form-item-name', false, 'data-ui-name="[name]" readonly="true"'); 
                        ?>
                    </p>
                    </div>
                    <?php } ?>

                    <?php if( $supports['field_type'] == true ) { ?>
                    <p>
                        <label><?php  echo $titles['field_type']; ?></label>
                        <select <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][field_type]'.'" ' : '';  ?>  data-ui-name="<?php echo '[field_type]'; ?>" class="contact_field_type select select-one-items" show-on-change=".st-builder-item-contact-form-item">
                        <?php foreach( $field_type as $k => $op){
                                $selected="";
                                if($item_value['field_type']==$k){
                                    $selected =' selected="selected" ';
                                }
                                echo '<option '.$selected.'value="'.esc_attr($k).'" >'.esc_html($op).'</option>';
                            } ?>
                        </select>
                    </p>
                    <?php } ?>
                    
                    <?php if( $supports['options'] == true ) { ?>
                    <div class="st-builder-item-contact-form-item" show-on="<?php echo implode(' ', $show_on_option); ?>">
                    <p>
                        <label><?php  echo $titles['options']; ?></label>
                        <ul class="contact-form-list-items-option">
                            <?php
                            $op = $item_value['options'];
                            $ops = explode('-|-', $item_value['options']);
                            if (count($ops) > 0) {
                                foreach($ops as $item) {
                            ?>
                            <li class="st-builder-item-contact-form-item-option">
                                <input type="text" value="<?php echo $item; ?>" class="contact-form-value-option" />
                                <a href="#" class="st-builder-item-contact-form-sort-option"><i class="iconentypo-arrow-combo"></i></a>
                                <a href="#" class="st-builder-item-contact-form-remove-option"><i class="iconentypo-minus-circled"></i></a>                                
                            </li>
                            <?php
                                } 
                            } 
                            ?>
                        </ul>
                        <a href="#" class="button st-builder-item-contact-form-add-option"><?php _e('Add new'); ?> <i class="iconentypo-plus-circled"></i></a>
                        <?php
                            $name_item = ($index>=0) ? $name.'['.$index.'][options]' : ''; 
                            stpb_input_text($name_item, $item_value['options'], 'hide contact-form-tmp-value-option', false, 'data-ui-name="[options]"'); 
                        ?>
                    </p>
                        <div class="st-builder-item-contact-form-item-option-tpl">
                            <input type="text" value="" class="contact-form-value-option" />
                            <a href="#" class="st-builder-item-contact-form-sort-option"><i class="iconentypo-arrow-combo"></i></a>
                            <a href="#" class="st-builder-item-contact-form-remove-option"><i class="iconentypo-minus-circled"></i></a>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if( $supports['field_width'] == true ) { ?>
                    <p>
                        <label><?php  echo $titles['field_width']; ?></label>
                        <?php 
                            $name_item = ($index>=0) ? $name.'['.$index.'][field_width]' : '';
                            stpb_input_select_one($name_item, $item_value['field_width'], $field_width, '', '', false, 'data-ui-name="[field_width]"');
                        ?>
                    </p>
                    <?php } ?>
                    
                    <?php if( $supports['placeholder'] == true){ ?>
                    <div class="st-builder-item-contact-form-item" show-on="<?php echo implode(' ', $show_on_placeholder); ?>">
                    <p>
                        <label><?php  echo $titles['placeholder']; ?></label>
                        <?php 
                            $name_item = ($index>=0) ? $name.'['.$index.'][placeholder]' : '';
                            stpb_input_text($name_item, esc_attr($item_value['placeholder']), '', false, 'data-ui-name="[placeholder]"'); 
                        ?>
                    </p>
                    </div>
                    <?php } ?>
                    
                    <?php if( $supports['validation'] == true ) { ?>
                    <div class="st-builder-item-contact-form-item" show-on="<?php echo implode(' ', $show_on_validation); ?>">
                        <p>
                            <label><?php  echo $titles['validation']; ?></label>
                            <?php 
                                $name_item = ($index>=0) ? $name.'['.$index.'][validation]' : '';
                                stpb_input_select_one($name_item, $item_value['validation'], $validation, '', '', false, 'data-ui-name="[validation]"');
                            ?>
                        </p>
                    </div>
                    <?php } ?>
                    
                    <?php if( $supports['required'] == true ) { ?>
                    <div class="st-builder-item-contact-form-item" show-on="<?php echo implode(' ', $show_on_required); ?>">
                    <p>
                        <label><?php  echo $titles['required']; ?></label>
                        <?php 
                            $name_item = ($index>=0) ? $name.'['.$index.'][required]' : '';
                            stpb_input_select_one($name_item, $item_value['required'], $required, '', '', false, 'data-ui-name="[required]"');
                        ?>
                    </p>
                    </div>
                    <?php } ?>
                    
                    <?php if( $supports['css_class'] == true){ ?>
                    <p>
                        <label><?php  echo $titles['css_class']; ?></label>
                        <?php 
                            $name_item = ($index>=0) ? $name.'['.$index.'][css_class]' : '';
                            stpb_input_text($name_item, esc_attr($item_value['css_class']), '', false,  'data-ui-name="[css_class]"'); 
                        ?>
                    </p>
                    <?php } ?>

                    <?php
                    if(is_array($more_fields)){
                        foreach($more_fields as $field){
                            if($field['type']=='heading'){
                                ?>
                                <div class="ui-heading">
                                   <?php echo $field['title']; ?>
                                </div>
                            <?php
                            }elseif($field['type']=='text'){
                                ?>
                                <p><label><?php  echo $field['title']; ?></label>
                                <input type="text" class="ui-input" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.']['.$field['name'].']'.'" ' : '';  ?> data-ui-name="<?php echo '['.$field['name'].']'; ?>" value="<?php echo esc_attr($item_value[$field['name']]); ?>" >
                                </p>
                                <?php
                            }else if($field['type']=='upload'){
                                ?>
                                <div class="ui-item-image" >
                                    <label><?php  echo $field['title']; ?></label>
                                    <span class="st-upload-wrap">
                                        <input type="text" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.']['.$field['name'].']'.'" ' : '';  ?>  data-ui-name="<?php echo '['.$field['name'].']'; ?>" class="st-upload-input input-upload" value="<?php echo esc_attr($item_value[$field['name']]); ?>">
                                        <input type="button" class="st-upload-button button-primary" value="<?php _e('Select image','smooththemes') ?>"/>

                                        <a href="#" <?php  echo ($item_value[$field['name']]!='')? '' : ' style="display: none;" ';  ?> class="remove_image" title="<?php _e('Remove','smooththemes') ?>"><i class="iconentypo-cancel"></i></a>
                                    </span>
                                </div>
                                <?php
                            }else if($field['type']=='link'){

                                ?>
                                <div class="ui-link">
                                <label><?php  echo $field['title']; ?></label>
                                <?php
                                stpb_input_link($name.'['.$index.']['.$field['name'].']', $item_value[$field['name']], ' data-ui-name="['.$field['name'].']" ' );
                                ?>
                                </div>
                                <?php

                            }
                        }
                    }
                    ?>

                    <?php if( $supports['content'] == true){ ?>
                    <div class="st-editor">
                    <textarea class="ui-cont ui-input" rows="10" <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][content]'.'" ' : '';  ?>  data-ui-name="<?php echo '[content]'; ?>"><?php echo esc_attr($item_value['content']); ?></textarea>
                    </div>
                    <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
                    <p><label><input type="checkbox" <?php echo $item_value['autop']==1 ? ' checked="checked" ' :''; ?>  <?php echo  ($index>=0) ? ' data-name="'.$name.'['.$index.'][autop]'.'" ' : '';  ?> data-ui-name="<?php echo '[autop]'; ?>" value="1" class="ui-autop" >&nbsp;<?php _e('Automatically add paragraphs','smooththemes') ?></label></p>

                    <?php } ?>

                </div>

                <div class="widget-control-actions">
                    <div class="alignleft">
                        <a class="remove" href="#remove"><?php _e('Remove','smooththemes') ?></a> |
                        <a class="close" href="#close"><?php _e('Close','smooththemes') ?></a>
                    </div>
                    <br class="clear">
                </div>
            </div>

        </div>
    </div>
    <?php
}

/**
 * This use for tabs,  toggle, accorditon
 * @param string $name
 * @param array $save_value
 * @param array bool $supports {title|content|switch_icon_image|image|icon}
 * $param array $titles {title|icon|image}
 * $param $more_fields array
 * $more_fields = array(
 *    array(
 *          'name'=>'input_name',
 *          'title'=>'input title',
 *         'type'=>'text|upload'
 *      )
 * )
 *
 */
function stpb_input_ui($name='', $save_value = array(), $supports= array(),$titles = array(), $more_fields = array()){
    if(empty($supports)){
        $supports = false;
    }
    $titles = array_filter($titles);

    $titles = wp_parse_args($titles, array(
        'title'=>__('Title:','smooththemes'),
        'image'=>__('Image:','smooththemes'),
        'icon'=>__('Icon:','smooththemes'),
    ));

    $supports = wp_parse_args($supports, array(
        'title'=>true,
        'content'=>true,
        'switch_icon_image'=>true,
        'image'=>true,
        'icon'=>true
    ));

    if(!is_array($save_value)){
        $save_value =  array();
    }

    ?>
     <div class="st-editor-ui" >

         <script type="text/html" class="tpl hide display-none">
             <?php stpb_input_ui_tpl(false, false,$supports,'-1',$titles, $more_fields); ?>
         </script>

         <ul  class="list-items" data-name="<?php echo $name; ?>">
              <?php foreach($save_value as $k => $item){ ?>
               <li>
                    <?php stpb_input_ui_tpl($name, $item, $supports, $k, $titles, $more_fields); ?>
               </li>
               <?php } ?>
         </ul>
         <input type="button" class="st-add-ui button-primary" value="<?php _e('Add item','smooththemes'); ?>"/>

     </div>
    <?php

}

function stpb_input_contact($name='', $save_value = array(), $supports= array(),$titles = array(), $more_fields = array()){
    if(empty($supports)){
        $supports = false;
    }
    $titles = array_filter($titles);

    $titles = wp_parse_args($titles, array(
        'label'=>__('Label:','smooththemes'),
        'name'=>__('Name:','smooththemes'),
        'field_type'=>__('Field Type:','smooththemes'),
        'field_width'=>__('Field Width:','smooththemes'),
        'placeholder'=>__('Place Holder:','smooththemes'),
        'validation'=>__('Validation:','smooththemes'),
        'required'=>__('Required:','smooththemes'),
        'options'=>__('Options:','smooththemes'),
        'css_class'=>__('CSS Class:','smooththemes'),
        'format'=>__('Format:','smooththemes')
    ));

    $supports = wp_parse_args($supports, array(
        'label'=>true,
        'name'=>true,
        'field_type'=>true,
        'field_width'=>true,
        'placeholder'=>true,
        'validation'=>true,
        'required'=>true,
        'options'=>true,
        'css_class'=>true,
        'format'=>true
    ));

    if(!is_array($save_value)){
        $save_value =  array();
    }

    ?>
     <div class="st-editor-ui" >

         <script type="text/html" class="tpl hide display-none">
             <?php stpb_input_ui_contact_tpl(false, false,$supports,'-1',$titles, $more_fields); ?>
         </script>

         <ul  class="list-items" data-name="<?php echo $name; ?>">
              <?php foreach($save_value as $k => $item){ ?>
               <li>
                    <?php stpb_input_ui_contact_tpl($name, $item, $supports, $k, $titles, $more_fields); ?>
               </li>
               <?php } ?>
         </ul>
         <input type="button" class="st-add-ui button-primary" value="<?php _e('Add item','smooththemes'); ?>"/>

     </div>
    <?php

}

/*-----------------------Tabs Builder---------------------------------*/

function stpb_input_tabs_item($name, $tab_name = array(),  $values =  array()){
    if(!is_array($values)){
        $values = array();
    }

  $icon_options =  array('no-icon'=>__('No Icon','smooththemes'),'icon'=>__('Icon','smooththemes'),'image'=>__('Image','smooththemes') );
?>
    <div class="tab-settings hide">
            <div class="item">
                <div class="left width-50">
                    <input type="text" data-name="<?php echo $name; ?>[title]" tab-item-name="[title]" class="stpt-input text input-tab-title" value="<?php echo esc_attr($values['title']); ?>">
                </div>
                <div class="right  width-50">
                    <strong><?php _e('Tab title','smooththemes') ?></strong>
                    <span><?php _e('Enter the tab title here (Better keep it short).','smooththemes'); ?></span>
                </div>
            </div>

            <div class="item">
                <div class="left width-50">
                    <select data-name="<?php echo $name; ?>[icon_type]"  tab-item-name="[icon_type]"  show-on-change=".st-tab-iton-type" class="icon-type select select-one">
                        <?php foreach($icon_options as $k => $op){
                            $selected="";
                            if($values['icon_type']==$k){
                                $selected =' selected="selected" ';
                            }
                            echo '<option '.$selected.'value="'.esc_attr($k).'" >'.esc_html($op).'</option>';
                        } ?>
                    </select>
                </div>

                <div class="right  width-50">
                    <strong><?php _e('Icon','smooththemes') ?></strong>
                    <span><?php _e('Should an icon/image be displayed at the left side of the tab title?','smooththemes'); ?></span>
                </div>
            </div>

        <div class="item st-tab-iton-type" show-on="icon">
            <strong><?php _e('Select your icon','smooththemes') ?></strong>
            <div class="st-icon-popup-wrap">
                <div class="icon-action">
                    <div class="selected-icon" title="<?php _e('Change icon','smooththemes'); ?>"><i class="<?php echo esc_attr($values['icon']); ?>"></i> </div>
                    <label><?php _e('Icon name','smooththemes'); ?></label><br/>
                    <input type="text" value="<?php echo esc_attr($values['icon']); ?>" class="st-icon-value" data-name="<?php echo $name; ?>[icon]" tab-item-name="[icon]">
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div class="item st-tab-iton-type" show-on="image">

            <div class="left width-50">
                <span class="st-upload-media media-type-image" data-type="image">
                <input type="hidden" data-name="<?php echo $name; ?>[image_id]" tab-item-name="[image_id]" class="st-media-input media" value="<?php echo esc_attr($values['image_id']); ?>">
                <input type="button" class="st-upload-button button-primary"  value="<?php _e('Select image','smooththemes') ?>"/>
                 <a href="#"  <?php  echo ($values['image_id']!='')? '' : ' style="display: none;" ';  ?> class="remove-media" title="<?php _e('Remove','smooththemes') ?>"><i class="iconentypo-cancel"></i></a>
                <div class="media-preview-w">
                    <div class="media-preview">
                        <?php
                        if($values['image_id']!=''){
                            $src = stpb_get_img_src_by_id($values['image_id']);
                            ?>
                            <div class="mi"><div class="mid"><img src="<?php echo $src; ?>" alt=""></div></div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </span>
            </div>

            <div class="right  width-50">
                <strong><?php _e('Image','smooththemes') ?></strong>
            </div>
        </div>

        <div class="item">
            <div class="st-editor">
                <textarea class="input-tab-content ui-input stpt-input textarea" rows="10" data-name="<?php echo $name; ?>[content]"  tab-item-name="[content]"><?php echo esc_attr($values['content']); ?></textarea>
            </div>
            <span class="desc"><?php _e('Arbitrary text or HTML','smooththemes') ?></span>
            <p><label><input type="checkbox" <?php echo $values['autop']==1 ? ' checked="checked" ' :''; ?>  data-name="<?php echo $name; ?>[autop]"  tab-item-name="[autop]" value="1" class="ui-autop" >
                <?php _e('Automatically add paragraphs','smooththemes') ?></label>
            </p>

        </div>

    </div>
<?php
}

/**
 * Visual tabs input
 * @param string $name
 * @param string $save_value
 * @param string $class
 */
function stpb_input_tabs($name='', $save_value = array(), $class=""){

    if(!is_array($save_value)){
        $save_value = array(
            array('title'=>'Tab 1')
        );
    }
    ?>
     <div class="st-tabs-builder">
         <div class="st-tabs <?php echo ($class!='') ? ' '.esc_attr($class) : ''; ?>"  data-name="<?php echo $name; ?>">
             <a class="add-tab" href="#">
                 <i class="iconentypo-plus"></i>
             </a>
            <ul class="st-tab-titles clear-after">
                <?php foreach($save_value as $k => $tab){ ?>
                <li class="active" title-edit="<?php _e('Edit Tab','smooththemes'); ?>">
                    <div class="ins">
                        <i class="handle left iconentypo-arrow-combo"></i>
                        <span class="left tab-info" >
                            <div class="icon" style="display: none;">
                                <div class="icon-mid">
                                </div>
                            </div>
                            <p class="live-title"><?php  echo esc_attr($tab['title']); ?></p>
                        </span>
                        <i class="remove-tab iconentypo-cancel"></i>
                        <?php stpb_input_tabs_item($name."[$k]", $tab_name,  $tab ); ?>
                    </div>
                </li>
                <?php  } ?>

            </ul>
             <div class="st-tab-contents content-preview">
                 <div class="st-tab-content"></div>
             </div>
             <div class="clear"></div>
         </div>
     </div><!-- /.st-tabs-builder -->
    <?php
}


/*---------- Table Builder ------*/
function stpb_table_input_select($data_name, $name , $value, $options, $class="column_style"){
    ?>
    <select data-name="<?php echo $data_name."[$name]"; ?>" class="<?php echo esc_attr($class); ?>" table-cell-name="<?php echo $name; ?>">

        <?php foreach($options as $k => $op){
            $selected="";
            if($value==$k){
                $selected =' selected="selected" ';
            }
            echo '<option '.$selected.'value="'.esc_attr($k).'" >'.esc_html($op).'</option>';
        } ?>
    </select>
<?php
}

function stpb_table_input_textarea($data_name, $name , $value, $class=""){
    ?>
    <textarea data-name="<?php echo $data_name."[$name]"; ?>"  class="<?php echo $class; ?>" table-cell-name="<?php echo $name; ?>"><?php echo esc_attr($value); ?></textarea>
    <?php
}


function stpb_input_table($name='', $save_value='', $type='data', $class=""){

        $column_styles= apply_filters('st_table_column_fields', 
            array(
                 'default'=> __('Default Column','smooththemes'),
                 'center'=> __('Center Column','smooththemes'),
                 'txt-right'=> __('Right Column','smooththemes')
             ), $type
        );
    
        $row_styles = apply_filters('st_table_row_fields',
             array(
                'default'=> __('Default Row','smooththemes'),
                'heading'=> __('Heading Row','smooththemes'),
                'bold'=> __('Bold Row','smooththemes'),
                'highlight'=> __('Highlight Row','smooththemes'),
             ), $type
        );


    // echo var_dump($save_value);

    if(empty($save_value) || !is_array($save_value['table'])){
        $save_value['table'] = array(
            //first row - header column
            array(
                // first col
                array(

                ),
                array(
                    'column_style'=>''
                ),
                array(
                    'column_style'=>''
                ),
                array(
                    'column_style'=>''
                )
            ),

             //  second row - content column
             array(
                 // first col
                 array(
                    'row_style'=>''
                 ),
                 array(
                     'text'=>'',
                     'button'=>''
                 ),
                 array(
                     'text'=>'',
                     'button'=>''
                 ),
                 array(
                     'text'=>'',
                     'button'=>''
                 )
             ),
            array(
                // first col
                array(
                    'row_style'=>''
                ),
                array(
                    'text'=>'',
                    'button'=>''
                ),
                array(
                    'text'=>'',
                    'button'=>''
                ),
                array(
                    'text'=>'',
                    'button'=>''
                )
            )

        );
    }


   $num_cols = count($save_value['table'][1]);
   if(!isset($save_value['table'][0][0])){
       $save_value['table'][0][0] = array();
   }

   ?>
    <div class="st-table-builder">
        <div class="st-table-builder-add-buttons">
            <a class="add-table-row button button-primary"><?php _e('Add Table Row','smooththemes'); ?></a>
            <a class="add-table-col button button-primary"><?php _e('Add Table Column','smooththemes'); ?></a>
        </div>

        <div class="st-table" data-name="<?php echo esc_attr($name); ?>">


            <?php

            foreach($save_value['table'] as $row_index => $row){
                // header row
                if($row_index==0){
                    ?>
                    <div class="st-table-row st-table-row-header">
                        <?php

                        foreach($row as $col_index =>  $col){
                            if(!is_array($col)){
                                $col = array('column_style'=>'');
                            }
                            // cell 0-0
                            if($col_index==0){
                                ?>
                                <div class="st-table-cell st-action-cell">
                                    <?php stpb_table_input_textarea($name."[table][$row_index][$col_index]",'text',$col['text'],'cell-button-data hide display-none'); ?>
                                </div>
                                <?php
                            }else{
                                ?>
                                <div class="st-table-cell st-action-cell">
                                    <?php stpb_table_input_select($name."[table][$row_index][$col_index]",'column_style',$col['column_style'],  $column_styles,'column_style'); ?>
                                </div>
                                <?php
                            }
                        }

                        // remove row col

                        ?>
                        <div class="st-table-cell st-action-cell st-remove-row">
                        </div>

                    </div><!-- /.st-table-row-header -->
                    <?php
                }else{ // content rows
                    ?>
                    <div class="st-table-row st-table-content-row">
                    <?php

                    foreach($row as $col_index =>  $col){
                        if(!is_array($col)){
                            $col = array('row_style'=>'');
                        }
                        // cell n - 0
                        if($col_index==0){
                            ?>
                            <div class="st-table-cell st-action-row">
                                <?php stpb_table_input_select($name."[table][$row_index][$col_index]", 'row_style',$col['row_style'], $row_styles,'row_style'); ?>
                            </div>
                        <?php
                        }else{
                            ?>
                            <div class="st-table-cell">
                                <div class="button-item" config-tpl-id="stpb_button-sc-tpl" edit-title="Button">
                                    <div class="button-item-preview">
                                        <?php echo do_shortcode($col['button']); ?>
                                    </div>
                                    <?php stpb_table_input_textarea($name."[table][$row_index][$col_index]", 'button',$col['button'],'cell-button-data hide'); ?>

                                </div>
                                <?php stpb_table_input_textarea($name."[table][$row_index][$col_index]", 'text',$col['text'],'cell-text-data'); ?>
                            </div>
                        <?php
                        }

                    }
                    ?>

                        <div class="st-table-cell st-action-cell st-remove-row">
                            <div class="remove"><i class="iconentypo-cancel"></i></div>
                        </div>
                    </div><!-- /.st-table-content-row -->
                    <?php

                }

            }
            ?>
            <div class="st-table-row st-table-row-footer st-row-actions">
            <?php
            // footer table
            for($i=0;  $i<=$num_cols; $i++){
                if($i==0){
                    ?>
                    <div class="st-table-cell st-action-cell">
                    </div>
                    <?php
                }elseif($i == $num_cols){
                    ?>
                    <div class="st-table-cell st-action-cell st-remove-row">
                    </div>
                    <?php
                }else{
                    ?>
                    <div class="st-table-cell st-action-cell st-remove-col">
                        <div class="remove"><i class="iconentypo-cancel"></i></div>
                    </div>
                    <?php
                }
            }

            ?>
            </div>

        </div><!-- /.st-table -->

    </div><!-- /.st-table-buidlder -->
   <?php
}


function stpb_input_effect($name, $save_value = '', $real_name =  false){
    $options = array(
        'no-effect'     =>__('No effect','smooththemes'),
        'topToBottom'   =>__('Top To Bottom','smooththemes'),
        'bottomToTop'   =>__('Bottom To Top','smooththemes'),
        'leftToRight'  =>__('Left To Right','smooththemes'),
        'rightToLeft'   =>__('Right To Left','smooththemes'),
        'fadeIn'        =>__('FadeIn','smooththemes')
    );

    $options = apply_filters('stpb_input_effect', $options);
    stpb_input_select_one($name, $save_value, $options, '', '',  $real_name);
}




