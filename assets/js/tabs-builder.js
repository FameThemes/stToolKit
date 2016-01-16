//-----Table builder---------
function stTabsBuilder(content, inputName){

    var tabs = function($tabs){
        var self= this;

        this.sort = function(){
            jQuery('.st-tab-titles',$tabs).sortable({
                handle : '.handle',
                stop: function(target, ui){
                    self.reName();
                }
            })
        };

        this.reName = function(){
            var tab_name  = jQuery('.st-tabs',$tabs).attr('data-name');

            jQuery('.st-tab-titles li',$tabs).each(function(tab_index){
                var li= jQuery(this);
                jQuery('select,input,textarea',li).each(function(){
                    var input_obj =  jQuery(this);
                    var data_name = input_obj.attr('tab-item-name') || '';
                    if(typeof(data_name)!==undefined &&  data_name!==''){
                        input_obj.attr('name',inputName+tab_name+'['+tab_index+']'+data_name);
                        input_obj.attr('data-name',tab_name+'['+tab_index+']'+data_name);
                    }
                });

            });
        };

        this.reset = function(tab){
            tab.removeClass('active');
            jQuery('textarea',tab).val('');
            jQuery('select option',tab).removeAttr('selected');
            jQuery('.input-tab-title',tab).attr('value','Tab title');
            //console.debug(jQuery('.input-tab-title',tab));
            jQuery('.live-title',tab).html('Tab title');
            jQuery('.tab-info .icon-mid',tab).html('').hide();
        };

        this.add = function(){
            jQuery('.add-tab',$tabs).click(function(){
                var tab = jQuery('.st-tab-titles li').eq(0).clone();
                self.reset(tab);
                jQuery('.st-tab-titles').append(tab);
                self.reName();
                return false;
            });
        };



        this.activeTab = function(tab){
            jQuery('.st-tab-titles li').removeClass('active');
            tab.addClass('active');
            var content =  jQuery('textarea.input-tab-content',tab).val();
            var title =  jQuery('input.input-tab-title', tab).val();
            jQuery('.live-title',tab).text(title);
            // do something with content
            var icon_type = jQuery('.icon-type',tab).val();
            var icon_html = '';
            if(icon_type==='icon'){
                icon_html =  jQuery('.selected-icon',tab).html();
            }else if(icon_type==='image'){
                icon_html = jQuery('.media-preview .mid',tab).html();
            }else{

            }
            if(icon_html===''){
                jQuery('.tab-info .icon',tab).hide();
            }else{
                jQuery('.icon-mid',tab).html(icon_html);
                jQuery('.tab-info .icon, .tab-info .icon-mid',tab).show();
            }

            if(content!=''){
                if(jQuery('.ui-autop',tab).attr('checked')=='checked'){
                    content = content.replace(/\n/g, '<br />')
                }
                content= jQuery( '<div>'+content+'</div>');
            }
            jQuery('.st-tab-content',$tabs).html(content);
        };

        this.remove = function(){
            jQuery('.st-tab-titles li .remove-tab', $tabs).live('click',function(){
                var li = jQuery(this).parents('li');
                var index = li.index();
                var n = jQuery('.st-tab-titles li', $tabs).length;
                if(n <2 ){
                    return false;
                }
                // reset active
                if(li.hasClass('active')){
                    if(index===n-1){ // last tab
                        self.activeTab(jQuery('.st-tab-titles li', $tabs).eq(index-1));
                    }else if(index===0){// first tab
                        self.activeTab(jQuery('.st-tab-titles li', $tabs).eq(1));
                    }
                }

                li.remove();
                self.reName();
                return false;
            });
        };

        this.hoverTab = function(){
            jQuery('.st-tab-titles li', $tabs).live('hover',function(){
                self.activeTab(jQuery(this));
            });
        }

        this.edit = function(){
            jQuery('.st-tab-titles li', $tabs).live('click',function(){
                var tab = jQuery(this);
                var title = jQuery(this).attr('title-edit') ||  'Edit Tab';

               // open lightbox
               stLightBox(
                   title,
                   jQuery('.tab-settings',tab).html(),
                   function(lb2,content2){ // function open
                       if(typeof(lb2.obj==='undefined')){
                           lb2.obj = jQuery('#'+lb2.lbId);
                       }
                       jQuery('#'+lb2.lbId).addClass('st-lb-lv2');
                       jQuery('#overlay-'+lb2.lbId).addClass('st-lb-lv2');
                       jQuery('.stpb-lb-outer', jQuery('#'+lb2.lbId)).height(lb2.lbHeight-80);
                       jQuery('.stpb-lb-content', jQuery('#'+lb2.lbId)).height(jQuery('.stpb-lb-content', jQuery('#'+lb2.lbId)).height()-80);
                       stCopyLightBoxSettings(jQuery('.tab-settings',tab), lb2, content2);
                       tab.on('stpb_lightbox_changed',function(){
                           self.activeTab(jQuery(this));
                       });

                   },
                   function(lb2, content2){ // function close

                   }

               );
                // end open lightbox

                return false;
            });
        };

        // load each tab
        this.loadTabs = function(){
            jQuery('.st-tab-titles li', $tabs).each(function(){
                self.activeTab(jQuery(this));
            } );

            self.activeTab(jQuery('.st-tab-titles li', $tabs).eq(0));
        };


        this.changeTabsPos = function(){
            jQuery('.tabs-builder-act .st-change-tabs-pos').live('change',function(){
                var  p = jQuery(this).parents('.tabs-builder-act');
                var pos = jQuery(this).val() || '';
                var t = jQuery('.st-tabs',p);
                t.removeClass('st-tabs-left st-tabs-right');
                if(pos==='left'){
                    t.addClass('st-tabs-left');
                }else if(pos==='right'){
                    t.addClass('st-tabs-right');
                }
            });


            jQuery('.tabs-builder-act .st-change-tabs-pos').each(function(){
                var  p = jQuery(this).parents('.tabs-builder-act');
                var pos = jQuery(this).val() || '';
                var t = jQuery('.st-tabs',p);
                t.removeClass('st-tabs-left st-tabs-right');
                if(pos==='left'){
                    t.addClass('st-tabs-left');
                }else if(pos==='right'){
                    t.addClass('st-tabs-right');
                }
            });


        };



        this.init = function(){
            this.sort();
            this.add();
            this.remove();
            this.hoverTab();
            this.edit();
            this.loadTabs();
            this.changeTabsPos();
        };

        this.init();
    }


    var inputname = jQuery('.st-current-index',content).val();
    jQuery('.st-tabs-builder',content).each(function(){
        tabs(jQuery(this), inputname);
    });

}
//------ End Tbas Builder -------