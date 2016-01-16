(function(jQuery) {
    "use strict";

    jQuery.fn.stElements = function(options) {
        var that = this;
        return this.each(function() {
            init(jQuery(this) );
        } );

        function init($contex) {
            // your code here
            tabs($contex);
            toggles($contex);
            accodions($contex);
            testimonials_slider($contex);
            notification($contex);
            video($contex);
            lightbox_gallery($contex);
            flexslider($contex);
        }

        function tabs($contex) {
            var st_tab = jQuery('.st-tabs', $contex);
            var m_height = 0, that, p, offset;
            jQuery('.tab-title', st_tab).each(function() {
                p = jQuery(this).parents('.st-tabs');
                m_height = 0;
                that = jQuery(this);
                jQuery('li', that).each(function() {
                    m_height += jQuery(this).outerHeight();
                });
                offset = jQuery('.tab-content', p).outerWidth() - jQuery('.tab-content', p).width();
                jQuery('.tab-content', p).css({'min-height': (m_height-offset) +'px'});
            });

            st_tab.find('.tab-title [tab-id="tab1"]').addClass('current');
            st_tab.find('.tab-content-wrapper [tab-id="tab1"]').addClass('active');
            st_tab.find('ul li').click(function(){
                if(jQuery(this).hasClass('current')) { return; }
                var tab_id = jQuery(this).attr('tab-id');
                var tab_title = jQuery(this).parents('ul.tab-title');
                var tab_content = tab_title.siblings('.tab-content-wrapper');
                tab_title .find('li.current').removeClass('current');
                jQuery(this).addClass('current');
                tab_content.find('div.active').removeClass('active').css('display','none');
                tab_content.find('[tab-id="' + tab_id + '"]').fadeIn().addClass('active');
            });
        }

        function toggles($contex) {
            jQuery('.toggle-content-current', $contex).show();
            jQuery('.toggle-title', $contex).live('click', function(){
                var toggle_tab = jQuery(this).parent();
                toggle_tab.find('> :last-child').stop(true, true).slideToggle();
                if (jQuery(this).hasClass('toggle-current'))
                {
                    jQuery(this).removeClass('toggle-current');
                }
                else
                {
                    jQuery(this).addClass('toggle-current');
                }
            });
        }

        function accodions($contex) {
            jQuery('.open-content', $contex).show();
            jQuery('.acc-title', $contex).live('click', function(){
                var p = jQuery(this).parents('.st-accordion');

                if(jQuery(this).is('.acc-title-inactive')){
                    jQuery('.acc-title-active', p).toggleClass('acc-title-active', p).toggleClass('acc-title-inactive', p).next().slideToggle().toggleClass('open-content', p);
                    jQuery(this).toggleClass('acc-title-active').toggleClass('acc-title-inactive');
                    jQuery(this).next().slideToggle().toggleClass('open-content');
                } else {
                    jQuery(this).toggleClass('acc-title-active').toggleClass('acc-title-inactive');
                    jQuery(this).next().slideToggle().toggleClass('open-content');
                }
            });
        }

        function testimonials_slider($contex) {
            jQuery('.st-testimonial-slider', $contex).each(function() {
                var  p = jQuery(this);
                var sl = jQuery('.st-testimonial-wi', p);
                if(typeof(sl) !== 'undefined'){
                    jQuery(sl).carouFredSel({
                        width: 'auto',
                        height: 'auto',
                        auto: false,
                        responsive: true,
                        scroll: 1,
                        align: 'left',
                        items: {
                            visible : 1,
                            height: 'auto'
                        },
                        pagination  : jQuery('.st-testimonial-pagination', p),
                        swipe: true
                    });

                    sl.touchwipe({
                        wipeLeft: function() {
                            sl.trigger('next', 1);
                        },
                        wipeRight: function() {
                            sl.trigger('prev', 1);
                        }
                    });
                }
            });
        }

        function notification($contex) {
            jQuery('.st-noti-close').live('click', function(){
                jQuery(this).parent().fadeOut("slow");
            });
        }

        function video($contex) {
            jQuery('.st-video', $contex).fitVids();
        }

        function lightbox_gallery($contex) {
            jQuery('.st-gallery .st-gallery-item a.image-lightbox', $contex).magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true, // set to true to enable gallery
                },
                zoom: {
                    enabled: true, // By default it's false, so don't forget to enable it
                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out', // CSS transition easing function
                }
            });
        }
        
        function flexslider($contex) {
            var FSD = {"animation":"slide","pauseOnHover":"true","controlNav":"false","directionNav":"true","animationDuration":"500","slideshowSpeed":"5000","pauseOnAction":"false","nextText" : '<i class="iconentypo-right-open-big"></i>',"prevText" : '<i class="iconentypo-left-open-big"></i>'};
            //var FSD = {"animation":"slide","animationLoop":"true","animationDuration":"500","slideshow":"true","slideshowSpeed":"7000","animationSpeed":"600","pauseOnAction":"false","pauseOnHover":"false","controlNav":"true","randomize":"false","directionNav":"true","nextText":"","prevText":""};
            jQuery('.flexslider', $contex).each(function(){
                if (typeof(window.FS) != 'undefined' && window.FS.size > 0) {
                    jQuery(this).flexslider( FS );
                }
                else {
                    jQuery(this).flexslider( FSD );
                }
            });
        }
    }

})(jQuery);

jQuery(document).ready(function() {
    jQuery('body').stElements();
});
