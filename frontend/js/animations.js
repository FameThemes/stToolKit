(function($) {
    "use strict";

    jQuery.fn.Animations = function(options) {
        var settings = $.extend({
            effect         : null,
            speed        : 400,
            defaultEffect :  { init : 'animate-fade animate-fadein' , 'active' : 'animate-fade-active animate-fadein' }
        }, options);

        settings.currentEffect = {};

        var animateEffects = {
            topToBottom  : { init : 'animate-side animate-side-top' ,  'active' : 'animate-side-active animate-side-top' },
            bottomToTop  : { init : 'animate-side animate-side-bottom' , 'active' : 'animate-side-active animate-side-bottom' },
            leftToRight : { init : 'animate-side animate-side-left' ,  'active' : 'animate-side-active animate-side-left' },
            rightToLeft  : { init : 'animate-side animate-side-right'  , 'active' : 'animate-side-active animate-side-right'  },
            fadeIn      : { init : 'animate-fade animate-fadein' , 'active' : 'animate-fade-active animate-fadein' }
        };

        if(typeof(settings.effect) === "string"  && settings.effect!==''){
            if(typeof(animateEffects[settings.effect])!=='undefined'){
                settings.currentEffect = animateEffects[settings.effect];
            }
        }else if(typeof (settings.effect) ==='object'){
            settings.currentEffect = settings.effect;
        }else{
            settings.currentEffect = settings.defaultEffect;
        }

        settings.speed =  parseInt(settings.speed);
        if(isNaN(settings.speed) || settings.speed<0){
            settings.speed = 400;
        }

        var that = this;
        return this.each(function() {
            init($(this) , settings);
        });
    }

    function init($el, settings){
        $el.addClass(settings.currentEffect.init);
        scrollHandle($el, settings);
        $('body').scroll(function(){
            scrollHandle($el, settings);
        });
        $(document).scroll(function(){
            scrollHandle($el, settings);
        });
    }

    function scrollHandle($el, settings){
        var doc = $(window);
        var etop =  $el.offset().top;
        var docH=  doc.innerHeight();
        var scrolled = doc.scrollTop();
        var eH = $el.height();

        eH =  eH*0.12;

        var elBottom = etop + eH;
        var  viewed = scrolled + docH;
        var h = 0;

        if( elBottom <= viewed  ){ // etop + eH * h) <= viewed &&  (elBottom) >= scrolled
            setTimeout( function(){
                $el.addClass(settings.currentEffect.active);
            }, settings.speed  );
        }
    }


})(jQuery);


jQuery(document).ready(function(){

    jQuery('.animation-effect').each(function(){
        var el = jQuery(this);
        var effect =  el.attr('effect') ||  'fadeIn';
        el.Animations({
            effect : effect,
            speed : 400
        });
    });

});


