/*
 Plugin: jQuery Parallax
 Version 1.1.3
 Author: Ian Lunn
 Twitter: @IanLunn
 Author URL: http://www.ianlunn.co.uk/
 Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

 Dual licensed under the MIT and GPL licenses:
 http://www.opensource.org/licenses/mit-license.php
 http://www.gnu.org/licenses/gpl.html
 */

(function( $ ){
    var $window = $(window);
    var windowHeight = $window.height();

    $window.resize(function () {
        windowHeight = $window.height();
    });

    $.fn.parallax = function(xpos, speedFactor, outerHeight) {
        var $this = $(this);
        var getHeight;
        var firstTop;
        var paddingTop = 0;

        //get the starting position of each element to have parallax applied to it
        $this.each(function(){
            firstTop = $this.offset().top;
        });

        if (outerHeight) {
            getHeight = function(jqo) {
                return jqo.outerHeight(true);
            };
        } else {
            getHeight = function(jqo) {
                return jqo.height();
            };
        }

        // setup defaults if arguments aren't specified
        if (arguments.length < 1 || xpos === null) xpos = "50%";
        if (arguments.length < 2 || speedFactor === null) speedFactor = 0.1;
        if (arguments.length < 3 || outerHeight === null) outerHeight = true;

        // function to be called whenever the window is scrolled or resized
        function update(){
            var pos = $window.scrollTop();

            $this.each(function(){
                var $element = $(this);
                var top = $element.offset().top;
                var height = getHeight($element);

                // Check if totally above or totally below viewport
                if (top + height < pos || top > pos + windowHeight) {
                    return;
                }

                $this.css('backgroundPosition', xpos + " " + Math.round((firstTop - pos) * speedFactor) + "px");
            });
        }

        $window.bind('scroll', update).resize(update);
        update();
    };
})(jQuery);

/* --------------------------- */

jQuery(document).ready(function(){

    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    var testMobile = isMobile.any();

    jQuery('.parallax').each(function() {
        var $this = jQuery(this);
        var bg = $this.attr('data-bg');
        if($this.hasClass('parallax-added')){
            return ;
        }

        $this.bind('st_sectionMode_applied',function(){

            var opacity = $this.attr('data-opacity') || undefined;
            var boxHieght =  $this.outerHeight();

            if (testMobile == null) {

                $this.addClass('parallax-added');
                $this.addClass('no-mb');
                $this.wrapInner('<div class="parallax-mc-inside js-add"></div>');
                $this.wrapInner('<div class="parallax-wrap js-add"></div>');

                jQuery('<div class="parallax-bg js-add"></div>').insertBefore(jQuery('.parallax-wrap',$this));
                jQuery('<div class="parallax-pattern js-add"></div>').insertBefore(jQuery('.parallax-wrap',$this));

                var pbg=  jQuery('.parallax-bg',$this);
               // var style = $this.attr('style');

                //$this.attr('style', '');
                $this.css( {'position' : 'relative' });

                pbg.css('backgroundImage', 'url(' + bg + ')');
                pbg.css('backgroundPosition', 'center');
                pbg.css('backgroundAttachment', 'fixed');
                pbg.css('backgroundSize', 'cover');
                pbg.css({'position' : 'absolute' , 'width' :  '100%', 'height' : '100%', 'display' : 'block', 'top' : '0px',  'left' : '0px' });

                if(!isNaN(opacity)){
                    opacity= parseFloat(opacity);
                    jQuery('.parallax-pattern',$this).css( {'opacity' : opacity });
                }

                pbg.parallax('50%', 0.4);

                jQuery(window).resize(function(){
                    // do something
                });

            } else {

                $this.wrapInner('<div class="mobile-mc-inside js-add"></div>');
                $this.wrapInner('<div class="mobile-wrap js-add"></div>');

                jQuery('<div class="mobile-bg js-add"></div>').insertBefore(jQuery('.mobile-wrap',$this));
                jQuery('<div class="mobile-pattern js-add"></div>').insertBefore(jQuery('.mobile-wrap',$this));

               // var style = $this.attr('style');
               // $this.attr('style', '');
                var pbg=  jQuery('.mobile-bg',$this);

                $this.css( {'position' : 'relative' });

                pbg.css('backgroundImage', 'url(' + bg + ')');
                pbg.css('backgroundPosition', 'center');
                pbg.css('backgroundAttachment', 'fixed');
                pbg.css('backgroundRepeat', 'no-repeat');
                pbg.css('backgroundAttachment', 'inherit');
                pbg.css('backgroundSize', 'cover');
                pbg.css({'position' : 'absolute' , 'width' :  '100%', 'height' : '100%', 'display' : 'block', 'top' : '0px',  'left' : '0px' });

                if(!isNaN(opacity)){
                    opacity= parseFloat(opacity);
                    jQuery('.mobile-pattern',$this).css( {'opacity' : opacity });
                }

            }


        });

    });
});
