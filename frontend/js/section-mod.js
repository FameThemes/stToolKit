jQuery(document).ready(function(){
    function sectionMode(){

        var calc_width = function(box){
            // console.debug(box);
            var container = box.parent();
            var bodyW =  jQuery('body').width();
            var boxW = container.outerWidth();

            var m = bodyW - boxW;
            //console.debug(bodyW,boxW,m);
            if(m>0){
                m = m/2;
                box.css({'marginLeft': '-'+m+'px','marginRight' : '-'+m+'px' });
            }else{
                box.css({'marginLeft': '','marginRight' : '' });
            }

        };


        jQuery('.section.full-width-mod').each(function(){
            calc_width(jQuery(this));
            jQuery(this).trigger('st_sectionMode_applied');
        });

        jQuery('.section.boxed-mod').each(function(){
            jQuery(this).trigger('st_sectionMode_applied');
        });


        jQuery(window).resize(function(){
            jQuery('.section.full-width-mod').each(function(){
                calc_width(jQuery(this));
                jQuery(this).trigger('st_sectionMode_resize');
            });
        });

    }

    sectionMode();

});