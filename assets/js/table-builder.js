//-----Table builder---------
function stTableBuilder(content){
    // add row
    var table = function($obj, inputName){


        var current_name = jQuery('.st-table',$obj).attr('data-name') || '';

        var reName = function(){
            jQuery('.st-table-row',$obj).each(function(row_index){
                var $row = jQuery(this);
                jQuery('.st-table-cell',$row).each(function(col_index){
                    var $col =  jQuery(this);

                    jQuery('select,input,textarea',$col).each(function(){
                        var input_obj =  jQuery(this);
                        var data_name = input_obj.attr('table-cell-name') || '';
                        if(typeof(data_name)!==undefined &&  data_name!==''){
                            input_obj.attr('name',inputName+current_name+'[table]['+row_index+']['+col_index+']['+data_name+']');
                            input_obj.attr('data-name',current_name+'[table]['+row_index+']['+col_index+']['+data_name+']');
                        }
                    });

                });
            });
        }

        var resetToEmpty = function($item){
            jQuery('.row_style option, .column_style option',$item).removeAttr('selected');
            jQuery('textarea',$item).val('');
            jQuery('.button-item-preview',$item).html('');
            jQuery('.button-item, .cell-text-data',$item).show();
        }

        var addRow= function(){
            jQuery('.add-table-row',$obj).click(function(){
                var newRow = jQuery('.st-table-content-row',$obj).eq(0).clone();

                resetToEmpty(newRow);

                newRow.insertBefore(jQuery('.st-table-row-footer',$obj));
                restColumnStyle();
                resetRowsStyle();
                reName();
                return false;
            })
        }

        var addCols = function(){
            //st-table-row
            jQuery('.add-table-col',$obj).click(function(){

                jQuery('.st-table-row',$obj).each(function(){
                    var row = jQuery(this);
                    var newCol = jQuery('.st-table-cell',row).eq(1).clone();
                    resetToEmpty(newCol);

                    newCol.insertBefore(jQuery('.st-remove-row',row));

                });

                resetRowsStyle();
                restColumnStyle();
                reName();
                return false;
            })

        };

        var removeRow = function(){
            jQuery('.st-table-row .st-remove-row',$obj).live('click',function(){

                if(jQuery('.st-table-row',$obj).length<=3){ /// include table header and footer
                    resetToEmpty(jQuery(this).parents('.st-table-row'));
                    return false;
                }else{
                    jQuery(this).parents('.st-table-row').remove();
                }

                resetRowsStyle();
                restColumnStyle();
                reName();
                return false;
            });
        };

        var removeCol = function(){
            jQuery('.st-table-row-footer .st-remove-col',$obj).live('click',function(){
                var index = jQuery(this).index();
                jQuery('.st-table-row',$obj).each(function(){
                    if(jQuery(this).find('.st-table-cell').length<=3){
                        resetToEmpty(jQuery(this).find('.st-table-cell').eq(index));
                    }else{
                        jQuery(this).find('.st-table-cell').eq(index).remove();
                    }

                });
                resetRowsStyle();
                restColumnStyle();
                reName();
                return false;
            });
        };

        // add a style for column
        var addColClass = function (index, className ){
            if(typeof(className)!=='string'|| className==='' ){
                return ;
            }
            className ='st-'+className+'-col';
            jQuery('.st-table-row',$obj).each(function(ri){
                if(ri>0 && ri < jQuery('.st-table-row',$obj).length-1){
                    jQuery(this).find('.st-table-cell').eq(index).addClass(className);
                }
            });
        };

        // remove all columns style
        var removeColClass = function (index ){
            jQuery('.st-table-row',$obj).each(function(){
                jQuery(this).find('.st-table-cell').eq(index).removeClass('st-highlight-col st-desc-col st-center-col');
            });
        };

        // reset columns style when table load
        var restColumnStyle = function(){
            jQuery('.st-table-row-header .st-table-cell', $obj).each(function(index){
                removeColClass(index);
                if(jQuery(this).find('select.column_style').length){
                    var s = jQuery(this).find('select.column_style').eq(0);
                    var v= s.val();
                    addColClass(index, v);
                }
            });

        };

        // When change columns style
        var changeColStyle = function(){
            jQuery('.st-table-row-header .st-table-cell select.column_style',  $obj).live('change',function(){
                var  index = jQuery(this).parents('.st-table-cell').index();
                removeColClass(index);
                var v= jQuery(this).val();
                addColClass(index, v);
            });
        };


        // reset Row Style
        var removeRowStyle = function($row){
            $row.removeClass('st-heading-row  st-button-row st-pricing-row');
            jQuery('.button-item',$row).hide();
        };

        var addRowStyle = function($row, className){
            if(typeof(className)!=='string'|| className==='' ){
                return ;
            }
            if(className==='button'){
                jQuery('.button-item',$row).show();
                jQuery('.cell-text-data',$row).hide();
            }else{
                jQuery('.button-item',$row).hide();
                jQuery('.cell-text-data',$row).show();
            }

            className ='st-'+className+'-row';
            $row.addClass(className);
        };

        // when change row tyle
        var changeRowStyle = function(){
            jQuery('.st-table-row select.row_style', $obj).live('change',function(){
                var $r = jQuery(this).parents('.st-table-row');
                var v=  jQuery(this).val();
                removeRowStyle($r);
                addRowStyle($r,v);

            });
        };

        var resetRowsStyle = function(){
            jQuery('.st-table-row', $obj).each(function(){
                var $r = jQuery(this);
                var v=  $r.find('select.row_style').eq(0).val();
                removeRowStyle($r);
                addRowStyle($r,v);

            });
        };

        // buttons layout
        var button = function(){
            var nameHTMLInput = function($obj, pre_name){
                // :not(input[type="button"], input[type="submit"], input[type="reset"])
                jQuery('.st-current-index', $obj).val(pre_name);
                jQuery('input, select, textarea',$obj).each(function(){
                    var data_name = jQuery(this).attr('data-name') || '';
                    if(typeof(data_name)!==undefined &&  data_name!==''){
                        jQuery(this).attr('name',pre_name+data_name);
                    }
                });
            };

            jQuery('.st-table-cell .button-item',$obj).live('click',function(){
                var btn = jQuery(this);
                if(btn.hasClass('clicked')){
                    return false;
                }

                btn.addClass('clicked');

                var  p = btn.parents('.st-table-cell');
                var id = btn.attr('config-tpl-id');
                var title =btn.attr('edit-title');



                var input = jQuery('.cell-button-data',p);
                var shortcode_data = input.val();
                // send ajax to get config template
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        shortcode_data: shortcode_data,
                        action: 'stpb_table_button_template'
                    },
                    dataType: 'html',
                    success:function(config_template){

                        stLightBox(
                            title,
                            '<form class="shorcode-form">'+ config_template+'</form>',
                            function(lb2,content2){

                                jQuery('#'+lb2.lbId).addClass('st-lb-lv2');
                                jQuery('#overlay-'+lb2.lbId).addClass('st-lb-lv2');

                                jQuery('.stpb-lb-outer', jQuery('#'+lb2.lbId)).height(lb2.lbHeight-80);
                                jQuery('.stpb-lb-content', jQuery('#'+lb2.lbId)).height(jQuery('.stpb-lb-content', jQuery('#'+lb2.lbId)).height()-80);
                                //console.debug( lb2.lbId);

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
                                            action: 'stpb_table_button_shortcode'
                                        },
                                        dataType: 'json',
                                        success:function(data){
                                            // p.remove();
                                            //  insertStshorcode(data);
                                            input.val(data.shortcode);
                                            jQuery('.button-item-preview',p).html(data.preview);
                                            lb2.close();
                                            btn.removeClass('clicked');
                                            // console.debug(data);
                                        }
                                    });

                                    return false;
                                });


                            },function(lb,content){
                                btn.removeClass('clicked');
                            }
                        ); // end light box


                    }// end success send data
                });

                return false;
            });
        };


        var init = function(){
            addRow();
            addCols();
            removeRow();
            removeCol();
            restColumnStyle();
            changeColStyle();
            button();

            changeRowStyle();
            resetRowsStyle();
            reName();
        }

        init();

    }



    var inputname = jQuery('.st-current-index',content).val();
    jQuery('.st-table-builder',content).each(function(){
        table(jQuery(this), inputname);
    });

}
//------ End Table Builder -------