


var STPageBuilder = function(selector) {
    "use strict";
    var self = this;
    this.selector = jQuery(selector);
    this.settingsL10n=  STBP.config;
    //  width-id       [  0  ,  1  ,  2  ,  3  ,  4  ,  5  ]
    //                 '1-1','3-4','2-3','1-2','1-3','1-4'
    this.stItemSizes = STBP.item_sizes;

    // for builder items tabs

    this.createItems = function() {
        this.tooltip();
        this.tabBuilderItems();
        // can sort items in canvans
        // jQuery('.sortable-list',this.selector).sortable();

        // load builder items by default
        var current_post_id = jQuery('#post_ID').val();
        if(current_post_id!=''){
            self.loadBuilderItemsByID(current_post_id);
        }


        this.rowItemActions();
        this.colItemActions();
        this.builderItemActions();
        this.changeBuilderItemSize();

        this.dragDropItems();
        this.dragElemtsToCanvans();

        this.saveTemplate();
        this.loadTemplate();


        jQuery('form#post').submit(function(){
            self.createItemsSettingsName();
        });

        self.changeEditor();

        jQuery('body').on('st_lb_close',function(){
            self.createItemsSettingsName();
        });

        this.checkEmptyCanvas();
        this.previewBuilderItems();
        this.shortcodes();

        // Multiple select
        jQuery('select.js-multiple',self.selector).each(function(){
            var s = jQuery(this);

            var ids = s.attr('selected-ids');
            if (typeof (ids) == 'undefined' || ids == '') {

            } else {
                ids = ids.split(',');
                jQuery('option', s).each(function () {
                    var v = jQuery(this).val();
                    if (jQuery.inArray(v, ids) >= 0) {
                        jQuery(this).attr('selected', 'selected');
                    }
                });
            }

        });
    };

    /**
     * Call example: self.nameHTMLInput(content2, '_st_shortcode');
     * @param $obj
     * @param pre_name
     */
    this.nameHTMLInput = function($obj, pre_name){
        // :not(input[type="button"], input[type="submit"], input[type="reset"])
        jQuery('.st-current-index', $obj).val(pre_name);
        jQuery('input, select, textarea',$obj).each(function(){
            var data_name = jQuery(this).attr('data-name') || '';
            if(typeof(data_name)!==undefined &&  data_name!==''){
                jQuery(this).attr('name',pre_name+data_name);
            }
        });
    };

    /**
     * Preview Items
     */
    this.previewBuilderItems = function(){
        /**
         * @param $element {jQuery}
         * @returns {string}
         */
        var fakeForm = function($element){
            var serialize_str =  '';
            var $clone = $element.clone();
            var form  = jQuery('<form class="stpb-faceform" />');
            form.append($clone);
            jQuery('body').append(form);

            self.nameHTMLInput(form, '_st_shortcode');
            serialize_str =  form.serialize();
            form.remove();

            return serialize_str;
        };

        var create_preview = function($builder_item, data){
            // send ajax to get config template
            //console.debug($builder_item);

            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    shortcode_data: data,
                    action: 'stpb_preview_builder_item'
                },
                dataType: 'json',
                success:function(data){
                    try{
                        if(data.preview!==''){
                            if($builder_item.find('.item-preview').length>0){
                                $builder_item.find('.item-preview').html(data.preview);
                            }else{
                                // $builder_item.children('.item-preview');
                                jQuery('<div class="item-preview">'+data.preview+'</div>').insertAfter(jQuery('.pbd-item-settings',$builder_item));
                            }
                        }else{
                            $builder_item.find('.item-preview').remove();
                        }

                    }catch(e){

                    }
                }

            });
        };

        // when item change
        jQuery('body').on('stpb_copy_item_done',function(item){
            var box =  item.item;
            if(box.attr('preview')!=='true'){
                return;
            }
            box = box.parent();
            create_preview(box,fakeForm(box));
        });

        // when canvas load
        jQuery('.stpb-canvas .pbd-item', self.selector).each(function(){
            var box = jQuery(this);
            if(box.attr('preview')!=='true'){
                return;
            }
            create_preview(box,fakeForm(box));
        });

        jQuery('.stpb-canvas',self.selector).on('stpb_canvas_load_done',function(){
            jQuery('.stpb-canvas .pbd-item', self.selector).each(function(){
                var box = jQuery(this);
                if(box.attr('preview')!=='true'){
                    return;
                }
                create_preview(box,fakeForm(box));
            });
        });


    };


    this.shortcodes  = function(){
        var btn = jQuery('#st-editor-shortcodes');

        var nameHTMLInput = self.nameHTMLInput;

        var insertStshorcode = function (tagtext) {

            //var tagtext ='[abc]';
            if(typeof(tagtext)=='undefined'){
                return;
            }

            var qt = typeof(QTags) != 'undefined';

            if(typeof(window.tinyMCE)!=='undefined'  && window.tinyMCE.activeEditor) {
                //window.tinyMCE.execInstanceCommand(window.tinyMCE.activeEditor.id, 'mceInsertContent', false, tagtext);
                window.tinyMCE.activeEditor.insertContent(tagtext);
                //Peforms a clean up of the current editor HTML.
                //tinyMCEPopup.editor.execCommand('mceCleanup');
                //Repaints the editor. Sometimes the browser has graphic glitches.
                // tinyMCEPopup.editor.execCommand('mceRepaint');
                //tinyMCEPopup.close();
            }else if ( qt ) {
                if(!QTags.insertContent(tagtext)){
                    document.getElementById('content').value += tagtext;
                }

            } else {
                document.getElementById('content').value += tagtext;
            }
            return;
        }


        btn.click(function(){
            stLightBox(
                btn.attr('title'),
                jQuery('#st-editor-list-sc-tpl').html(),
                function(lb,content){
                    jQuery('.pbdone',lb.obj).remove();
                    jQuery('.item-cell',content).click(function(){

                        var shortcode_str =  jQuery(this).attr('data-shortcode');
                        if(typeof(shortcode_str)!=='undefined'  && shortcode_str!=''){
                            insertStshorcode(shortcode_str);
                            lb.close();
                            return false;
                        }

                        lb.close();
                        var id = jQuery(this).attr('data-id');
                        var title =jQuery(this).attr('edit-title');

                        if( jQuery('#'+id).length===0){
                            return false;
                        }

                        stLightBox(
                            title,
                            '<form class="shorcode-form">'+ jQuery('#'+id).html()+'</form>',
                            function(lb2,content2){

                                nameHTMLInput(content2, '_st_shortcode');

                                jQuery('.pbdone',lb2.obj).click(function(){
                                    nameHTMLInput(content2, '_st_shortcode');
                                    // send data to server to process shortcode
                                    var shortcode_data =  content2.serialize();
                                    //console.debug(jQuery.parseJSON(shortcode_data));
                                    jQuery.ajax({
                                        url: ajaxurl,
                                        type: 'post',
                                        data: {
                                            shortcode_data: shortcode_data,
                                            action: 'stpb_create_shortcode'
                                        },
                                        dataType: 'html',
                                        success:function(data){
                                            // p.remove();
                                            insertStshorcode(data);
                                            lb2.close();
                                            // console.debug(data);
                                        }
                                    });

                                    return false;
                                });


                            },function(lb,content){

                            }
                        );


                    });


                },function(lb,content){

                }
            );


            return false;
        });

    };


    this.changeEditor = function(){
        var btn = jQuery('#st-change-editor');

        var bt=   jQuery('#st-change-editor').attr('builder-title');
        var et=   jQuery('#st-change-editor').attr('editor-title');

        btn.click(function(){
            var current_editor =  jQuery('#st_current_editor_value').val();

            // from editor to builder
            if(current_editor==='' || current_editor==='editor' ||  typeof(current_editor)==='undefined'){
                jQuery('#postdivrich').addClass('hide').removeClass('st-show').hide();
                jQuery('#st_page_builder').removeClass('hide').addClass('st-show').show();
                jQuery('#st_current_editor_value').val('builder');
                btn.text(et);

            }else{  // from builder to editor
                jQuery('#postdivrich').removeClass('hide').addClass('st-show').show();
                jQuery('#st_page_builder').addClass('hide').removeClass('st-show').hide();

                jQuery('#st_current_editor_value').val('editor');
                btn.text(bt);
            }
            return false;
        });


        jQuery('#st_current_editor_value').each(function(){

            var current_editor =  jQuery('#st_current_editor_value').val();

            if(current_editor=='' || current_editor=='editor'){
                jQuery('#postdivrich').removeClass('hide').addClass('st-show').show();
                jQuery('#st_page_builder').addClass('hide').removeClass('st-show').hide();
                // bt.text(et);
            }else{
                jQuery('#postdivrich').addClass('hide').removeClass('st-show').hide();
                jQuery('#st_page_builder').removeClass('hide').addClass('st-show').show();
                //bt.text(bt);
            }
            return false;
        });

    };


    /**
     * Display tooltip Selector Class .st-tooltip
     */
    this.tooltip = function() {
        jQuery('.st-tooltip', this.selector).poshytip({
            className : 'tip-twitter',
            showTimeout : 1,
            alignTo : 'target',
            alignX : 'center',
            offsetY : 5,
            allowTipHover : false,
            fade : false,
            slide : false,
            content : function(updateCallback){
                return jQuery(this).attr('data-tooltip') || null;
            }
        });
    };

    this.copyLightBoxSettings = function($toBox, lb, content){
        stCopyLightBoxSettings($toBox, lb, content);
    };

    this.rowItemActions = function(){
        // for add
        jQuery('.add-item-row',self.selector).live('click',function(){
            var html = jQuery('.item-row-tpl',self.selector).html();
            jQuery('.stpb-canvas',self.selector).append(html);
            self.dragDropItems();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });

        // for remove
        jQuery('.item-row .item-action .item-remove',self.selector).live('click',function(){
            var  r = jQuery(this).parents('.item-row');
            if(jQuery('.pbd-item',r).length>0){
                var c =  confirm(self.settingsL10n.confirm_remove_row);
                if(c=== true){
                    r.remove();
                }
            }else{
                r.remove();
            }
            self.fixColsInRows();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });

        // clone clone
        jQuery('.item-row .item-action .clone',self.selector).live('click',function(){
            var  r = jQuery(this).parents('.item-row');
            var c = r.clone(false, false);
            c.removeClass('droppable ui-droppable ui-sortable');
            c.find('.droppable, .ui-droppable, .ui-sortable').removeClass('droppable ui-droppable ui-sortable');
            c.insertAfter(r);
            self.dragDropItems();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });

        // for settings row
        jQuery('.item-row .item-action .settings').live('click',function(){
            var  p = jQuery(this).parents('.item-action');
            var box = jQuery('.stpb-item-settings',p);

            // open lightbox settings
            self.lightBox(
                self.settingsL10n.row_settings_title,
                box.html(),
                function(lb,content){ // function open
                    self.copyLightBoxSettings(box,lb,content);
                },
                function(){ // function close

                });
            return false;
        });


    };


    this.colItemActions = function(){
        // for add in row
        jQuery('.item-row .item-action .add',self.selector).live('click',function(){
            var  r = jQuery(this).parents('.item-row');
            var html = jQuery('.item-col-tpl',self.selector).html();
            html=  jQuery(html);

            var class_name = '1-1';
            var widthId =0;

            html.addClass('width-'+class_name).attr('width-id',widthId);
            html.find('input.width-id').val(widthId);
            html.find('input.width-class').val('width-' + class_name);

            r.find('.row-cols').append(html);
            self.dragDropItems();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });

        // add column to canvas from button in the top
        jQuery('.add-item-col').click(function(){
            var widthId=  jQuery(this).attr('data-width-id');
            if(typeof(widthId)=='undefined'){
                widthId =0;
            }else{

            }
            var class_name = self.stItemSizes[widthId];
            if(typeof(class_name)=='undefined'){
                class_name = '1-1';
                widthId =0;
            }

            var html = jQuery('.item-col-tpl',self.selector).html();
            html=  jQuery(html);
            html.addClass('width-'+class_name).attr('width-id',widthId);
            html.find('input.width-id').val(widthId);
            html.find('input.width-class').val('width-' + class_name);
            html.find('.info').text(class_name.replace('-','/'));

            jQuery('.stpb-canvas',self.selector).append(html);
            self.dragDropItems();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });


        // for remove
        jQuery('.col-item .item-col-action .item-remove', self.selector).live('click',function(){
            var  r = jQuery(this).parents('.col-item');
            if(jQuery('.pbd-item',r).length>0){
                var c =  confirm(self.settingsL10n.confirm_remove_col);
                if(c=== true){
                    r.remove();
                }
            }else{
                r.remove();
            }

            self.fixColsInRows();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });

        // clone col
        jQuery('.col-item .item-col-action .clone',self.selector).live('click',function(){
            var  r = jQuery(this).parents('.col-item');
            var c = r.clone(false, false);
            c.removeClass('droppable ui-droppable ui-sortable');
            c.find('.droppable, .ui-droppable, .ui-sortable').removeClass('droppable ui-droppable ui-sortable');
            c.insertAfter(r);
            self.dragDropItems();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });

        // for settings column
        jQuery('.col-item .item-col-action .settings').live('click',function(){
            var  p = jQuery(this).parents('.item-col-action');
            var box = jQuery('.stpb-item-settings',p);

            // open lightbox settings
            self.lightBox(
                self.settingsL10n.col_settings_title,
                box.html(),
                function(lb,content){ // function open
                    self.copyLightBoxSettings(box,lb,content);
                },
                function(){ // function close

                });
            return false;
        });


    };


    this.setBuilderItemSize=  function(widthId, c){
        var c_name = self.stItemSizes[widthId];
        if(typeof(c_name)=='undefined'){
            return false;
        }

        var c_txt = c_name.replace('-', '/');
        for (var i = 0; i < self.stItemSizes.length; i++) {
            c.removeClass('width-' + self.stItemSizes[i]);
        }
        c.attr('width-id', widthId);
        c.find('input.width-id',widthId);

        c.find('input.width-id').val(widthId);
        c.find('input.width-class').val('width-' + c_name);

        c.addClass('width-' + c_name);
        c.find('.item-col-action .info').text(c_txt);

        self.fixColsInRows();

    };

    this.changeBuilderItemSize =  function(){
        //
        jQuery('.col-item .item-col-action .up, .col-item .item-col-action .down',self.selector).live('click',function(){
            var  c = jQuery(this).parents('.col-item');
            var widthId= c.attr('width-id');
            if(typeof(widthId)==='undefined'){
                widthId =  0;
            }
            if(jQuery(this).hasClass('up')){
                if (widthId > 0) {
                    widthId --;
                }
            }else{
                if (widthId < self.stItemSizes.length - 1) {
                    widthId++;
                } else {
                    widthId = self.stItemSizes.length - 1;
                }
            }
            self.setBuilderItemSize(widthId,c);
            return false;
        });


    };

    this.builderItemActions = function(){
        // for add
        jQuery('.add-content-element',self.selector).click(function(){
            var  p = jQuery(this);
            if(jQuery('.builder-tpl-item', p).length>0){
                var html = jQuery('.builder-tpl-item', p).html();
                jQuery('.stpb-canvas',self.selector).append(html);
                self.dragDropItems();
                jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            }
            return false;
        });

        // for remove
        jQuery('.pbd-item .item-builder-action .item-remove',self.selector).live('click',function(){
            var  r = jQuery(this).parents('.pbd-item');
            r.remove();
            self.fixColsInRows();
            jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            return false;
        });
        // clone clone
        jQuery('.pbd-item .item-builder-action .clone',self.selector).live('click',function(){
            var  r = jQuery(this).parents('.pbd-item');
            var c = r.clone(false, false);
            c.removeClass('droppable ui-droppable ui-sortable');
            c.find('.droppable, .ui-droppable, .ui-sortable').removeClass('droppable ui-droppable ui-sortable');
            c.insertAfter(r);
            self.dragDropItems();
            return false;
        });

        /// for items settings
        jQuery('.pbd-item .item-preview').live('click',function(){
            var  p = jQuery(this).parents('.pbd-item');
            jQuery('.item-builder-action .settings',p).click();
            return false;
        });

        jQuery('.pbd-item .settings').live('click',function(){
            var  p = jQuery(this).parents('.pbd-item');
            var box = jQuery('.pbd-item-settings',p);
            var title = jQuery('.pdb-item-title',p).text();
            // open lightbox settings
            self.lightBox(
                title,
                box.html(),
                function(lb,content){ // function open
                    self.copyLightBoxSettings(box,lb,content);
                },
                function(){ // function close

                });
            return false;
        });


    };

    this.fixColsInRows = function(){

        var fix=  function($items){
            var total = 0;
            for(var i =0; i < $items.length; i++){

                var $this = jQuery($items[i]);
                var $next = jQuery($items[i+1]);
                var tryNext = 0;

                if(i==0){
                    $this.addClass('first_row_col');
                }

                var withId = $this.attr('width-id');
                var nextWithId ='';
                if(typeof(withId)==='undefined'){
                    withId = 0;
                }

                if(typeof($next)==='undefined'){
                    nextWithId = '0-1';
                }else{
                    nextWithId = $next.attr('width-id');

                    nextWithId =  self.stItemSizes[nextWithId];
                    if(typeof(nextWithId)==='undefined'){
                        nextWithId ='1-1';
                    }
                }

                nextWithId = nextWithId.replace('-','/');
                tryNext =  eval(nextWithId);

                var w =  self.stItemSizes[withId];
                if(typeof(w)==='undefined'){
                    w ='1-1';
                }
                w = w.replace('-','/');

                try{
                    total+= eval(w);
                }catch(e){

                }

                if(total>=1 || tryNext+ total>1){
                    // $this.addClass('first_row_col');
                    $next.addClass('first_row_col');
                    total = 0;
                }

            }

        }


        var $items = jQuery('.stpb-canvas > div');
        $items.removeClass('first_row_col');
        fix($items);

        jQuery('.stpb-canvas > .item-row').each(function(){
            var $items_col = jQuery('.col-item',jQuery(this));
            $items_col.removeClass('first_row_col');
            fix($items_col);
        });

    };


    this.createItemsSettingsName = function(){
        var option_name = STBP.input_name;

        var nameHTMLInput = function($obj, pre_name){
            // :not(input[type="button"], input[type="submit"], input[type="reset"])
            jQuery('.st-current-index', $obj).val(pre_name);

            jQuery('input, select, textarea',$obj).each(function(){
                var data_name = jQuery(this).attr('data-name') || '';
                if(typeof(data_name)!==undefined &&  data_name!==''){
                    jQuery(this).attr('name',pre_name+data_name);
                }
            });
        }

        var nameElement = function($obj, pre_name){
            // level 1
            nameHTMLInput($obj, pre_name);
            // for lv 2 has row
            if(jQuery('.row-cols > .stpbe',$obj).length){ // if has items inside this row

                // if has columns inside
                jQuery('.row-cols > .stpbe',$obj).each(function(c_index){
                    var $col = jQuery(this);

                    // name for columns an builder items outside columns
                    nameHTMLInput($col, pre_name+'[items]['+c_index+']');

                    // for lv 3  name for items inside columns
                    if($col.has('.col-item')){
                        if(jQuery('.p-builder-items',$col).length>0){
                            jQuery('.p-builder-items > .pbd-item',$col).each(function(index){
                                nameHTMLInput(jQuery(this), pre_name+'[items]['+c_index+'][items]['+index+']');
                            });
                        }
                    }else{

                        // name for items out side column but inside row
                        if(jQuery('.row-cols > .pbd-item',$obj).length>0){ // no row and has columns
                            jQuery('.row-cols > .pbd-item',$obj).each(function(index){
                                nameHTMLInput(jQuery(this), pre_name+'[items]['+c_index+']');
                            });
                        }
                    }

                });


            }else if(jQuery('.pbd-item',$obj).length>0){ // no row and has columns
                jQuery('.pbd-item',$obj).each(function(index){
                    nameHTMLInput(jQuery(this), pre_name+'[items]['+index+']');

                });
            }

        }

        jQuery('.stpb-canvas > .stpbe',self.selector).each(function(index){
            var  pre_name = option_name+'['+index+']';
            nameElement(jQuery(this),pre_name);
        });

    };

    this.dragElemtsToCanvans = function() {
        // for layout columns
        jQuery('.stpb-pagebuilder .add-item-col').draggable({
            cursor: "move",
            helper: "clone",
            zIndex: 9999,
            cursorAt: { left: 10 , top:  7 },
            revert: 'invalid',
            connectToSortable: '.stpb-canvas, .stpb-canvas .row-cols'
        });

        // for builder items
        jQuery('.stpb-pagebuilder .add-content-element').draggable({
            cursor: "move",
            helper: "clone",
            zIndex: 9999,
            cursorAt: { left: 10 , top:  7 },
            revert: 'invalid',
            connectToSortable: '.stpb-canvas, .stpb-canvas .row-cols, .stpb-canvas .p-builder-items'
        });
        // for row
        jQuery('.stpb-pagebuilder .add-item-row').draggable({
            cursor: "move",
            helper: "clone",
            zIndex: 9999,
            cursorAt: { left: 10 , top:  7 },
            revert: 'invalid',
            connectToSortable: '.stpb-canvas'
        });

    };

    this.droppingItem =  function(itemTarget, event, ui){

        // console.debug(itemTarget);
        // console.debug(ui.draggable.parent());
        if(ui.draggable.parent().hasClass('builder-item-icon')||  ui.draggable.parent().hasClass('tab-content')){
            return;
        }

        // if is column
        if(ui.draggable.hasClass('add-item-col')){

            var widthId=  ui.draggable.attr('data-width-id');
            if(typeof(widthId)=='undefined'){
                widthId =0;
            }else{

            }
            var class_name = self.stItemSizes[widthId];
            if(typeof(class_name)=='undefined'){
                class_name = '1-1';
                widthId =0;
            }

            var html = jQuery('.item-col-tpl',self.selector).html();
            html=  jQuery(html);
            html.addClass('width-'+class_name).attr('width-id',widthId);
            html.find('input.width-id').val(widthId);
            html.find('input.width-class').val('width-' + class_name);

            // jQuery('.stpb-canvas',self.selector).append(html);
            var c = html.attr('class');
            ui.draggable.html('').attr('class',c).removeAttr('data-tooltip').attr('width-id',widthId);
            ui.draggable.append(html);
            ui.draggable.find('.col-item-inner').unwrap();
            class_name =  class_name.replace('-','/');
            ui.draggable.find('.item-col-action .info').text(class_name);
            ui.draggable.addClass('come-from-tab');


        }else if(ui.draggable.hasClass('add-content-element')){  // is builder item
            var html = ui.draggable.find('.builder-tpl-item .pbd-item').html();
            var c = ui.draggable.find('.builder-tpl-item .pbd-item').attr('class');
            ui.draggable.html(html).attr('class',c);
            ui.draggable.addClass('come-from-tab');

        }else if(ui.draggable.hasClass('add-item-row')){  // is builder item

            if(itemTarget.hasClass('p-builder-items') || itemTarget.hasClass('row-cols')){
                ui.draggable.remove();
                return;
            }

            var html = jQuery('.item-row-tpl',self.selector).html();
            html = jQuery(html);
            var c = html.attr('class');
            ui.draggable.html(html.html()).attr('class',c);
            ui.draggable.addClass('come-from-tab');
        }else{
            ui.draggable.remove();
        }

        // console.debug(itemTarget);
    };


    this.checkEmptyCanvas = function(){
        if( jQuery(".stpb-canvas", self.selector).children().length>0){
            jQuery(".stpb-canvas-no-item" , self.selector).hide();
        }else{
            jQuery(".stpb-canvas-no-item" , self.selector).show();
        }

        jQuery(".stpb-canvas", self.selector).on('stpb_canvas_change',function(){
            if( jQuery(".stpb-canvas", self.selector).children().length>0){
                jQuery(".stpb-canvas-no-item" , self.selector).hide();
            }else{
                jQuery(".stpb-canvas-no-item" , self.selector).show();
            }
        });
    };

    // Drag item to canvas
    this.dragDropItems = function() {

        self.createItemsSettingsName();
        self.fixColsInRows();

        // for dropable
        jQuery(".stpb-canvas:not(.ui-droppable)", self.selector).droppable({
            accept: ".add-item-col, .add-item-row, .add-content-element",
            tolerance:'touch',
            activeClass: 'st-active',
            drop: function( event, ui ) {
                //console.debug('dropped');
                self.droppingItem(jQuery(this),event, ui);
                self.createItemsSettingsName();
                jQuery(this).removeClass('st-pb-hover-lv0');
                self.dragDropItems();
                jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');

            },
            over: function( event, ui ) {
                jQuery(this).addClass('st-pb-hover-lv0');
            },
            out: function( event, ui ) {
                jQuery(this).removeClass('st-pb-hover-lv0');
            }
        });

        jQuery(".stpb-canvas .row-cols:not(.ui-droppable)", self.selector).droppable({
            accept: ".add-item-col .add-content-element",
            tolerance:'pointer',
            drop: function( event, ui ) {
                //console.debug('dropped');
                jQuery(this).removeClass('st-pb-hover-lv1');
                self.droppingItem(jQuery(this),event, ui);
                self.createItemsSettingsName();
                self.dragDropItems();
                jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
            },
            over: function( event, ui ) {
                jQuery(this).addClass('st-pb-hover-lv1');
            },
            out: function( event, ui ) {
                jQuery(this).removeClass('st-pb-hover-lv1');
            }
        });

        jQuery(".stpb-canvas .p-builder-items:not(.ui-droppable)", self.selector).droppable({
            accept: ".add-content-element",
            tolerance:'touch',
            drop: function( event, ui ) {
                //console.debug('dropped');
                jQuery(this).removeClass('st-pb-hover-lv2');
                self.droppingItem(jQuery(this),event, ui);
                self.createItemsSettingsName();
                self.dragDropItems();
                jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');

            },
            over: function( event, ui ) {
                jQuery(this).addClass('st-pb-hover-lv2');
            },
            out: function( event, ui ) {
                jQuery(this).removeClass('st-pb-hover-lv2');
            }
        });


        // first level
        jQuery(".stpb-canvas:not(.ui-sortable)", self.selector).sortable({
            placeholder : "sortable-placeholder",
            handle: '.item-col-action, .item-action, .pdb-item-inner',
            zIndex: 9999,
            tolerance: 'pointer',
            //  appendTo: 'body',
            // revert: 200,
            connectWith: '.stpb-canvas, .stpb-canvas .p-builder-items, .stpb-canvas .row-cols',
            // forceHelperSize: true,
            cursorAt: { left: 10 , top:  7 },
            start: function( event, ui ){
                self.fixColsInRows();
                jQuery(this).addClass('st-pb-hover-lv0');
            },
            sort: function( event, ui ) {
                if(ui.helper.hasClass('item-row')){
                    ui.placeholder.addClass('st-row-placeholder');
                }else if(ui.helper.hasClass('pbd-item')){
                    ui.placeholder.addClass('st-item-placeholder');
                }

            },
            over: function( event, ui ) {
                jQuery(this).addClass('st-pb-hover-lv0');
            },
            out: function( event, ui ) {
                jQuery(this).removeClass('st-pb-hover-lv0');
            },
            receive: function (event, ui) { // add this handler

                jQuery(this).removeClass('st-pb-hover-lv0');
                jQuery(this).find('>.item-row').removeClass('come-from-tab');
                self.createItemsSettingsName();
            },
            stop: function(event, ui){
                self.fixColsInRows();
                jQuery(this).removeClass('st-pb-hover-lv0');
                self.createItemsSettingsName();

            }
        });

        // for columns
        jQuery(".stpb-canvas .row-cols:not(.ui-sortable)", self.selector).sortable({
            placeholder : "sortable-placeholder",
            handle: '.item-col-action, .item-action, .pdb-item-inner',
            zIndex: 9999,
            // appendTo: 'body',
            tolerance: 'pointer',
            // revert: 200,
            cursorAt: { left: 10 , top:  7 },
            connectWith: '.stpb-canvas, .stpb-canvas .row-cols, .stpb-canvas .p-builder-items ',
            // connectWith: '.stpb-canvas, .stpb-canvas .row-cols, .stpb-canvas .p-builder-items ',
            // forceHelperSize: true,
            // cursorAt: { left: 5 },
            start: function( event, ui ){
                jQuery(this).addClass('st-pb-hover-lv1');
                self.fixColsInRows();
            },
            over: function( event, ui ) {
                jQuery(this).addClass('st-pb-hover-lv1');
            },
            out: function( event, ui ) {
                jQuery(this).removeClass('st-pb-hover-lv1');
            },
            sort: function( event, ui ) {

                if(ui.helper.hasClass('pbd-item')){
                    ui.placeholder.addClass('st-item-placeholder');
                }
                self.fixColsInRows();


            },
            receive: function (event, ui) { // add this handler
                // do not accept items
                jQuery(this).removeClass('st-pb-hover-lv1');


                if(ui.item.hasClass('come-from-tab')  && ( ui.item.hasClass('item-row'))){
                    console.debug('-item removed--');
                    ui.item.remove();
                }else if(ui.sender.hasClass('stpb-canvas') && ui.item.hasClass('item-row')){
                    ui.sender.sortable( "cancel" );
                    console.debug('---rows---');
                }else{
                    //ui.sender.sortable( "cancel" );
                    //  console.debug(ui);
                    // console.debug('---in-column --- ');
                    // console.debug(event);
                    // self.dragDropItems();
                    var cp = ui.item.parent();

                    /*
                     if(ui.item.hasClass('col-item') && jQuery(event.target).hasClass('row-cols')){
                     // console.debug('is-column drag in row');
                     //  jQuery(event.target).append();

                     }
                     */

                    /*
                     if(jQuery(event.target).hasClass('row-cols')){
                     console.debug('in-row');
                     }
                     */
                    if(cp.hasClass('p-builder-items') && ui.item.hasClass('col-item')){

                        cp = cp.parent().parent();
                        // console.debug(cp);
                        var clone_item =   ui.item.clone(false, false);
                        clone_item.insertAfter(cp);
                        ui.item.remove();
                        // ui.sender.sortable( "cancel" );
                    }
                }

                self.createItemsSettingsName();
                jQuery(this).removeClass('st-pb-hover-lv1');
            },
            stop: function(event, ui){
                self.fixColsInRows();
                jQuery(this).removeClass('st-pb-hover-lv1');
                self.createItemsSettingsName();
            }
        });


        // sort for builder item
        jQuery('.stpb-canvas .p-builder-items:not(.ui-sortable)',self.selector).sortable({
            placeholder : "sortable-placeholder",
            handle: '.pdb-item-title',
            //  appendTo: 'body',
            zIndex: 9999,
            // helper: "clone",
            // revert: 10,
            tolerance: 'pointer',
            // revert: 200,
            connectWith: '.stpb-canvas .p-builder-items, .stpb-canvas, .stpb-canvas .row-cols',
            cursorAt: { left: 10 , top:  7 },
            //forceHelperSize: true,
            start: function( event, ui ){
                self.fixColsInRows();
                // jQuery(this).addClass('st-pb-hover-lv2');
            },
            stop:  function(){
                self.createItemsSettingsName();
                self.fixColsInRows();
            },
            over: function( event, ui ) {
                jQuery(this).addClass('st-pb-hover-lv2');
            },
            out: function( event, ui ) {
                jQuery(this).removeClass('st-pb-hover-lv2');
            },
            receive: function (event, ui) { // add this handler

                // if(( ui.sender.hasClass('col-item') || ui.sender.hasClass('stpb-canvas') ) && !item.has  ){
                // console.debug(ui.item);
                jQuery(this).removeClass('st-pb-hover-lv2');

                if(!ui.item.hasClass('st-available-item') || ui.item.hasClass('item-row') ){
                    if(ui.item.hasClass('come-from-tab')  && ( ui.item.hasClass('item-row'))){
                        ui.item.remove();
                    }else if( !ui.item.hasClass('pbd-item')  ){
                        ui.sender.sortable( "cancel" );

                    }else{
                        ui.item.removeClass('come-from-tab');
                    }


                }else{
                    ui.item.removeClass('come-from-tab');
                }

                self.createItemsSettingsName();
                // jQuery(this).removeClass('st-pb-hover-lv2');
            },
            sort: function( event, ui ) {
                // self.fixColsInRows();

                if(ui.helper.hasClass('pbd-item')){
                    ui.placeholder.addClass('st-item-placeholder');
                }

            }
        });;


    }; // end function dragDropItems


    this.lightBox = function(title,content,  openCallBack, closeCallback){
        stLightBox(title,content,  openCallBack, closeCallback);
    }; // end function  ;


    this.addItemToBox = function($item) {
        $item.appendTo(jQuery('.stpb-canvas', this.selector));
    };

    this.tabBuilderItems = function() {
        jQuery('.stpb-tabs-wrap .tab-title .tab', this.selector).bind(
            'click',
            function() {
                var p = jQuery(this).parents('.stpb-tabs-wrap');
                var fortab = jQuery(this).attr('for-tab') || false;
                // alert(fortab)
                if (fortab) {
                    jQuery('.tab-title a', p).removeClass('active');
                    jQuery(this).addClass('active');

                    jQuery('.tab-content', p).removeClass('active').addClass('hide');
                    jQuery(fortab).removeClass('hide').addClass('active');
                    //--------
                    if(jQuery(this).hasClass('st-tab-actions')){
                        self.loadTemplate();
                    }
                    //--------
                    if(typeof(setCookie)==='function'){
                        setCookie('stpb_current_tab',fortab, 30);
                    }
                }
                return false;

            });
        // when page bload
        if(typeof(getCookie)==='function'){
            var c_tab =  getCookie('stpb_current_tab');
            if(typeof(c_tab)!=='undefined' && c_tab !==''){
                jQuery('.stpb-tabs-wrap .tab-title .tab[for-tab="'+c_tab+'"]', this.selector).click();
            }
        }
    };

    this.saveTemplate = function(){
        jQuery('.stpb-save-template .save',self.selector).click(function(){
            var content = jQuery(this).parents('.stpb-save-template');
            var btn = jQuery(this);
            var  p = jQuery('.canvas-actions',self.selector);


            if(btn.attr('disabled')==='disabled'){
                return false;
            }else{
                btn.attr('disabled','disabled');
            }

            //alert('ok');
            var name= jQuery('.template-name', content).val();
            if(name==''){
                jQuery('input.template-name', content).addClass('st_error');
                btn.removeAttr('disabled');
                setTimeout(function(){
                    jQuery('input.template-name', content).removeClass('st_error');
                },4000);
            }else{ // send to server
                var post_data = jQuery('form#post').serialize();

                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        action: 'stpb_save_builder_template',
                        template_name: name,
                        post_data: post_data
                    },
                    dataType: 'html',
                    success:function(data){
                        jQuery('.success',content).show(300);
                        jQuery('input.template-name', content).val('');
                        self.loadTemplate();
                        btn.removeAttr('disabled');
                        setTimeout(function(){
                            jQuery('.success',content).hide(500);
                        },4000);

                    }

                });

            }

            return false;
        });
    };


    this.loadBuilderItemsByID = function(id){
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                id: id,
                action: 'stpb_load_template'
            },
            dataType: 'html',
            success:function(data){
                jQuery('.stpb-canvas',self.selector).append(data);
                //jQuery('.').stInputItems();
                self.dragDropItems();
                jQuery('.stpb-canvas').stInputItems();
                jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_load_done');
                jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
                jQuery('#st_page_builder_loaded').val(1);
            }
        });
    }


    this.loadTemplate = function(){
        var content = jQuery('#stpb-list-template');
        content.html('<p>Loading...</p>');
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'stpb_load_builder_templates'
            },
            dataType: 'html',
            success:function(data){
                content.html(data);
                //---------------
                // load template by id
                jQuery('.load-this-tpl',content).click(function(){
                    var id = jQuery(this).attr('post-id');
                    self.loadBuilderItemsByID(id);
                    /*
                     jQuery.ajax({
                     url: ajaxurl,
                     type: 'post',
                     data: {
                     id: id,
                     action: 'stpb_load_template'
                     },
                     dataType: 'html',
                     success:function(data){
                     jQuery('.stpb-canvas',self.selector).append(data);
                     self.dragDropItems();
                     jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_load_done');
                     jQuery('.stpb-canvas',self.selector).trigger('stpb_canvas_change');
                     }
                     });
                     */
                    return false;
                });

                //remove template
                jQuery('.list-template .remove-this-tpl',content).click(function(){
                    var c =  confirm('Are you sure ?');
                    if(c){
                        var  o = jQuery(this).parents('.list-template');
                        var id = jQuery(this).attr('post-id');
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: {
                                id: id,
                                action: 'stpb_remove_template'
                            },
                            dataType: 'html',
                            success:function(data){
                                o.remove();
                            }
                        });
                    }

                    return false;
                });

                //--------------
            }// end success load templates

        });
    }; // end load template


}// end class STPageBuilder


jQuery(document).ready(function() {

    var pagebuilder = new STPageBuilder('#stpb-pagebuilder');
    pagebuilder.createItems();

    jQuery('.st-page-options').stInputItems();

});