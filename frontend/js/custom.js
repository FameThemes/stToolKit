(function(jQuery) {
    "use strict";

    jQuery.fn.stElements = function(options) {
        var that = this;
        return this.each(function() {

            init(jQuery(this) );

        } );

        function init($contex) {
            // your code here
            video($contex);

            lightbox_gallery($contex);
            carousel($contex);
            alertHide($contex);
            sectionMode($contex);
            gmap($contex);
            middleColumns($contex);
            chart($contex);
        }

        // for full width mod
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

        function alertHide($contex){
            jQuery('.builder-item .alert, .stpb-notification .alert', $contex).on('closed.bs.alert', function () {
                var p = jQuery(this).parents('.builder-item') ||  jQuery(this).parents('.stpb-notification');

                p.remove();
            });
        }

        function video($contex) {
            // load video thumbnail
            jQuery('.video-thumb', $contex).each(function(){
                var obj = jQuery(this);
                var v = obj.attr('video');
                var vi = obj.attr('video-id');
                if(typeof(v)!='undefined' && v !=''&& typeof(vi)!='undefined' && vi !=''){
                    if(v=='youtube'){
                        obj.html('<img src="http://img.youtube.com/vi/'+vi+'/3.jpg" alt="" />');
                    }else{
                        jQuery.getJSON('http://vimeo.com/api/v2/video/'+vi+'.json?callback=?',{format:"json"},function(data,status){
                            var small_thumb=  data[0].thumbnail_small;
                            obj.html('<img src="'+small_thumb+'" alt="" />');
                        });
                    }
                }else{
                    obj.html('<span class="no-thumb"></span>');
                }
            });

            jQuery('.st-video', $contex).fitVids();
        }


        function lightbox_gallery($contex) {
            jQuery('.st-gallery .st-gallery-item a.image-lightbox, .gallery-lightbox', $contex).magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true // set to true to enable gallery
                },
                zoom: {
                    enabled: true, // By default it's false, so don't forget to enable it
                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out' // CSS transition easing function
                }
            });

            jQuery('.single-lightbox', $contex).magnificPopup({
                type: 'image',
                zoom: {
                    enabled: true, // By default it's false, so don't forget to enable it
                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out' // CSS transition easing function
                }
            });

        }
        // st-carousel

        function carousel($contex) {

            jQuery('#container').imagesLoaded(function(){
                //---------------------
                jQuery('.st-carousel-w .st-carousel', $contex).each(function() {
                    var  sl =  jQuery(this);
                    var  p = sl.parents('.st-carousel-w');
                    var number  = sl.attr('data-items');
                    number = parseInt(number);
                    if(isNaN(number) || number <=0 ){
                        number = 3;
                    }

                    var carouFredSelInt = function(sl, number){
                        jQuery(sl).carouFredSel({
                            circular: true,
                            infinite: false,
                            // items: number,
                            width: 'auto',
                            // height: 'variable',
                            // auto: false,
                            responsive: true,
                            scroll: 1,
                            align: 'center',
                            // padding : [0,0,0,0] ,
                            next : jQuery('.next',p),
                            prev : jQuery('.prev',p),
                            pagination  : jQuery('.caro-pagination',p),
                            swipe: true,
                            items: {
                                visible: {
                                    min: 1,
                                    max: number
                                },
                                height: 'variable'
                            },
                            onCreate : function (){
                                sl.css( {
                                    'height': 'variable',
                                    'visibility' : 'visible'
                                });
                            }
                        });

                        sl.touchwipe({
                            wipeLeft: function() {
                                sl.trigger('next', 1);
                            },
                            wipeRight: function() {
                                sl.trigger('prev', 1);
                            }
                        });


                    };

                    if(typeof(sl) !== 'undefined'){
                        carouFredSelInt(sl, number);
                    }

                    jQuery(window).resize(function(){
                        sl.trigger("destroy");
                        carouFredSelInt(sl, number);
                    });


                });
                //---------------------
            });
            /*
            .always( function( instance ) {
                //console.log('all images loaded');
            })
            .done( function( instance ) {
               // console.log('all images successfully loaded');
            })
            .fail( function() {
                //console.log('all images loaded, at least one is broken');
            })
            .progress( function( instance, image ) {
                //var result = image.isLoaded ? 'loaded' : 'broken';
               // console.log( 'image is ' + result + ' for ' + image.img.src );
            });
            */


        }


        function gmap($contex){

            jQuery('.st-map', $contex).each(function(){
                var  m = jQuery(this),  id = m.attr('id'), mapZoom = m.attr('map-zoom') || 9, color = m.attr('map-color') || '';
                m.html('');
                var map_data = window[id];
                if(typeof(map_data)==='undefined'){
                    return false;
                }

                if(m.hasClass('map-added')){
                    return false;
                }

                m.addClass('map-added');

                var is_color =  /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(color);

                mapZoom = parseInt(mapZoom);
                var mapSettings =  {
                    div: '#'+id,
                    lat: map_data[0].lat,
                    lng: map_data[0].lng,
                    mapTypeId: id,
                    zoom: mapZoom,
                    draggable: true,
                    zoomControl: true,
                    panControl: true,
                    mapTypeControl: false,
                    scaleControl: true,
                    streetViewControl: false,
                    overviewMapControl: true,
                    scrollwheel: true,
                    resize : true
                };

                if(!is_color){
                    delete mapSettings.mapTypeId;
                }
                var map = new GMaps(mapSettings);
                for(var i=0; i < map_data.length; i++){
                    var address = {},  content ='';

                    if(map_data[i].lat!=='' && map_data[i].lng!==''){
                        address = map_data[i];

                        if(address.title!=''){
                            content = '<h4 class="m-address">'+address.title+'</h4>';
                        }

                        if(map_data[i].content!=''){
                            content+='<div class="m-content">'+(map_data[i].content)+'</div>';
                        }

                        if(content!=''){
                            address.infoWindow ={};
                            address.infoWindow.content = content;
                        }

                        delete address.content ;
                        // open marker by default
                        var e = map.addMarker(address);
                        e.infoWindow.open(map, e);

                    }
                }

                // if color is set
                if(is_color){
                    var styles = {
                        'styledMapName': id,
                        'mapTypeId': id,
                         styles:
                            [{
                                "featureType": "administrative",
                                "stylers": [
                                    { "visibility": "on" }
                                ]
                            },
                                {
                                    "featureType": "road",
                                    "stylers": [
                                        { "visibility": "on" },
                                        { "hue": color }
                                    ]
                                },
                                {
                                    "stylers": [
                                        { "visibility": "on" },
                                        { "hue": color },
                                        { "saturation": -30 }
                                    ]
                                }
                            ]
                    };

                    map.addStyle(styles);

                }// end check color


            });
        }
    }

    function middleColumns($contex){
        var settHeight =  function(c, p){
            var ww =jQuery(window).width();
            var ph= p.innerHeight();
            if(ww>768){
                c.removeClass('no-table').height(ph);
            }else{
                c.addClass('no-table').css({'height' : 'auto'});
            }
        }
        jQuery('.bd-row .col-va').each(function(){
            var c=  jQuery(this),  p = c.parents('.bd-row');
            settHeight(c,p);
            jQuery(window).resize(function(){
                c.css({'height' : 'auto'});
                settHeight(c,p);
            });
        });
    }

    function chart($contex){
        function scroll_init($el, cb){
            scrollHandle($el, cb);
            jQuery('body').scroll(function(){
                scrollHandle($el, cb);
            });
            jQuery(document).scroll(function(){
                scrollHandle($el, cb);
            });
        }

        function scrollHandle($el, cb){

            if($el.hasClass('chart-added')){
                return false;
            }

            var doc = jQuery(window);
            var etop =  $el.offset().top;
            var docH=  doc.innerHeight();
            var scrolled = doc.scrollTop();
            var eH = $el.height();

            eH =  eH*0.12;

            var elBottom = etop + eH;
            var  viewed = scrolled + docH;
            var h = 0;

            if( elBottom <= viewed  ){ // etop + eH * h) <= viewed &&  (elBottom) >= scrolled
                $el.addClass('chart-added');
                setTimeout( function(){
                   // do ing
                    cb();
                }, 300  );
            }
        }

        //percentage chart
        jQuery('.st-chart').each(function(){
            var chart =  jQuery(this);
            var size=  chart.attr('size') ||  150,
                percent=  chart.attr('ani-percent') ||  0,
                barColor =  chart.attr('barColor') || '#ff0800',
                lineWidth =  chart.attr('lineWidth') || 20,
                trackColor =  chart.attr('trackColor') || '#e2e2e2',
                type = chart.attr('data-type') || '';
                chart.easyPieChart({
                    //easing: 'easeOutElastic',
                   // delay: 4000,
                    animate: 2000,
                    barColor: barColor,
                    lineWidth: lineWidth,
                    trackColor: trackColor,
                    scaleColor: false,
                    lineCap: 'butt',
                    size: size,
                    onStep: function(from, to, percent) {
                        if(type!=='icon'){
                           // this.el.children[0].innerHTML = ;
                            chart.find('.percent').html(Math.round(percent)+'<span class="sep">%</spa>');
                        }

                    }
                });

                scroll_init(chart, function(){
                    chart.data('easyPieChart').update(percent);
                });
        });

        // progress
        jQuery('.progress .progress-bar .inner-tooltip',$contex).each(function(){
            var pr =  jQuery(this);
            var  p = pr.parents('.progress-bar');
            var percent = p.attr('percent') || 0;
            pr.tooltip({
                trigger: 'manual'
            });
            pr.tooltip('show');

            scroll_init(p, function(){
                p.css('width',percent+'%');
            });
        });

        // Count to
        jQuery('.counter-number').each(function(){
            var ct=  jQuery(this);
            scroll_init(ct, function(){
                ct.countTo();
            });
        });


    }


})(jQuery);

jQuery(document).ready(function() {
    jQuery('body').stElements();

    jQuery('#submit, input[type="submit"]').addClass('btn btn-default');
    jQuery('table').each(function(){
        var c= jQuery(this).attr('class') || '';

        if(typeof (c) ==='undefined' ||  c===''){
            jQuery(this).addClass('table js-add').wrap('<div class="table-responsive js-add"></div>');
        }

    });

});
