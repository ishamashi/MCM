(function ($) {
    "use strict";
    var ToggleHandler = function ( $scope, $ ) {
        var toggle_elem             = $scope.find('.eael-toggle-container').eq(0);
        $(toggle_elem).each(function () {
            var $toggle_target      = $(this).data('toggle-target');
            var $toggle_switch      = $($toggle_target).find('.eael-toggle-switch');
            $($toggle_target).find('.eael-primary-toggle-label').addClass("active");
            $($toggle_switch).toggle(
                function() {
                    var $parent_container = $(this).closest('.eael-toggle-container');
                    $($parent_container).find('.eael-toggle-content-wrap').removeClass("primary");
                    $($parent_container).children('.eael-toggle-content-wrap').addClass("secondary");
                    $($parent_container).find('.eael-toggle-switch-container').addClass("eael-toggle-switch-on");
                    $(this).parent().parent().find('.eael-primary-toggle-label').removeClass("active");
                    $(this).parent().parent().find('.eael-secondary-toggle-label').addClass("active");
                },
                function() {
                    var $parent_container = $(this).closest('.eael-toggle-container');
                    $($parent_container).children('.eael-toggle-content-wrap').addClass("primary");
                    $($parent_container).children('.eael-toggle-content-wrap').removeClass("secondary");
                    $($parent_container).find('.eael-toggle-switch-container').removeClass("eael-toggle-switch-on");
                    $(this).parent().parent().find('.eael-primary-toggle-label').addClass("active");
                    $(this).parent().parent().find('.eael-secondary-toggle-label').removeClass("active");
                }
            );
        });
    };

    var CounterHandler = function ($scope, $) {

        var counter_elem                = $scope.find('.eael-counter').eq(0),
            $target                     = counter_elem.data('target');
        
        $(counter_elem).waypoint(function () {
            $($target).each(function () {
                var v                   = $(this).data("to"),
                    speed               = $(this).data("speed"),
                    od                  = new Odometer({
                        el:             this,
                        value:          0,
                        duration:       speed
                    });
                od.render();
                setInterval(function () {
                    od.update(v);
                });
            });
        },
            {
                offset:             "80%",
                triggerOnce:        true
            });
    };

    var FlipCarousel = function( $scope, $ ) {
        var flipCarousel_elem = $scope.find('.eael-flip-carousel').eq(0);
        $(flipCarousel_elem).each(function() {
            var style = $(this).data('style'),
                start = $(this).data('start'),
                fadeIn = $(this).data('fadein'),
                loop = $(this).data('loop'),
                autoplay = $(this).data('autoplay'),
                pauseOnHover = $(this).data('pauseonhover'),
                spacing = $(this).data('spacing'),
                click = $(this).data('click'),
                scrollwheel = $(this).data('scrollwheel'),
                touch = $(this).data('touch'),
                buttons = $(this).data('buttons'),
                buttonPrev = $(this).data('buttonprev'),
                buttonNext = $(this).data('buttonnext');

            $(this).flipster({
                style: style,
                start: start,
                fadeIn: fadeIn,
                loop: loop,
                autoplay: autoplay,
                pauseOnHover: pauseOnHover,
                spacing: spacing,
                click: click,
                scrollwheel: scrollwheel,
                tocuh: touch,
                buttons: buttons,
                buttonPrev: '<i class="flip-custom-nav ' + buttonPrev + '"></i>',
                buttonNext: '<i class="flip-custom-nav ' + buttonNext + '"></i>',
            });
        });
    }

    var FilterGallery = function( $scope, $ ) {
        var filtergallery_elem = $scope.find('.eael-filter-gallery-wrapper').eq(0);

        $(filtergallery_elem).each(function() {
            var gridStyle = $(this).data('grid-style'),
                ref = $(this).find('.item').data('ref'),
                duration = $(this).data('duration'),
                effects = $(this).data('effects'),
                popup = $(this).data('popup'),
                galleryEnabled = $(this).data('gallery-enabled');
            var mixer = mixitup( $(this), {
                controls: {
                    scope: 'local'
                },
                selectors: {
                    target: '[data-ref~="'+ref+'"]'
                },
                animation: {
                    enable: true,
                    duration: ''+duration+'',
                    effects: ''+effects+'',
                    easing: 'cubic-bezier(0.245, 0.045, 0.955, 1)',
                }
            } );

            // Set Background Image
            if( gridStyle == 'eael-hoverer' || gridStyle == 'eael-tiles' ) {
                var postColumn = $(this).find( '.eael-filter-gallery-container .item' );
                postColumn.each( function() {
                    let dataBg = $(this).attr( 'data-item-bg' );
                    $(this).css( 'background-image', 'url( '+ dataBg +' )' );
                } );
            }
            // Magnific Popup
            if( true == popup ) {
                $(this).find('.eael-magnific-link').magnificPopup({
                    type: 'image',
                    gallery:{
                        enabled: galleryEnabled
                    },
                    callbacks: {
                        close: function() {
                            $( '#elementor-lightbox' ).hide();
                        }
                    }
                });
            }
        });
    }

    var InstagramGallery = function( $scope, $ ) {

        var instagramGallery = $scope.find('.eael-instagram-feed').eq(0),
            caption          = (instagramGallery.find('.eael-insta-grid').data('caption') === 'show-caption') ? '<p class="insta-caption">{{caption}}</p>' : '',
            likes            = (instagramGallery.find('.eael-insta-grid').data('likes') === 'yes') ? '<p class="eael-insta-post-likes"> <i class="fa fa-heart-o" aria-hidden="true"></i> {{likes}}</p>' : '',
            comments         = (instagramGallery.find('.eael-insta-grid').data('comments') === 'yes') ? '<p class="eael-insta-post-comments"><i class="fa fa-comment-o" aria-hidden="true"></i> {{comments}}</p>' : '',
            link_target      = (instagramGallery.find('.eael-insta-grid').data('link-target') === 'yes') ? 'target="_blank"' : '',
            link             = (instagramGallery.find('.eael-insta-grid').data('link') === 'yes') ? '<a href=\"{{link}}\" ' +link_target+ '></a>' : '';

        $(instagramGallery).each(function() {
            var get = $(this).find('.eael-insta-grid').data('get'),
                tagName = $(this).find('.eael-insta-grid').data('tag-name'),
                userId = $(this).find('.eael-insta-grid').data('user-id'),
                clientId = $(this).find('.eael-insta-grid').data('client-id'),
                accessToken = $(this).find('.eael-insta-grid').data('access-token'),
                limit = $(this).find('.eael-insta-grid').data('limit'),
                resolution = $(this).find('.eael-insta-grid').data('resolution'),
                sortBy = $(this).find('.eael-insta-grid').data('sort-by'),
                target = $(this).find('.eael-insta-grid').data('target');

            var loadButton = $(this).find('.eael-load-more-button');
            var feed = new Instafeed({
                get: ''+get+'',
                tagName: ''+tagName+'',
                userId: userId,
                clientId: ''+clientId+'',
                accessToken: ''+accessToken+'',
                limit: ''+limit+'',
                resolution: ''+resolution+'',
                sortBy: ''+sortBy+'',
                target: ''+target+'',
                template: '<div class="eael-insta-feed eael-insta-box"><div class="eael-insta-feed-inner"><div class="eael-insta-feed-wrap"><div class="eael-insta-img-wrap"><img src="{{image}}" /></div><div class="eael-insta-info-wrap"><div class="eael-insta-info-wrap-inner"><div class="eael-insta-likes-comments">' + likes + comments + '</div>' + caption + '</div></div>' + link + '</div></div></div>',
                after: function() {
                    var el = $(this);
                    if (el.classList)
                        el.classList.add('show');
                    else
                        el.className += ' ' + 'show';
                },
                // every time we load more, run this function
                after: function() {
                    // disable button if no more results to load
                    if (!this.hasNext()) {
                        $(loadButton).parent().addClass( 'no-pagination' );
                        loadButton.attr('disabled', 'disabled');
                    }
                },
                success: function() {
                    $(this).find('.eael-insta-grid').masonry();
                    $(loadButton).removeClass( 'button--loading' );
                    $(loadButton).find( 'span' ).html( 'Load More' );
                }
            });

            // bind the load more button
            loadButton.on('click', function() {
                feed.next();
                $(loadButton).addClass( 'button--loading' );
                $(loadButton).find( 'span' ).html( 'Loading...' );
            });

            feed.run();

            $(window).load(function() {
                $(this).find('.eael-insta-grid').masonry({
                    itemSelector: '.eael-insta-feed',
                    percentPosition: true,
                    columnWidth: '.eael-insta-box'
                });
            });
        });
    }

    /* ------------------------------ */
    /* Advance accordion
    /* ------------------------------ */
    var AdvAccordionHandler = function($scope, $) {

        var $eael_adv_accordion = $scope.find('.eael-adv-accordion');
        var $this =  $eael_adv_accordion,
            $accordionID                = $this.attr('id'),
            $currentAccordion           = $('#'+$accordionID),
            $accordionType              = $this.data('accordion-type'),
            $accordionSpeed             = $this.data('toogle-speed'),
            $accrodionList              = $this.find('.eael-accordion-list'),
            $eaelAccordionListHeader    = $accrodionList.find('.eael-accordion-header');

        $accrodionList.each(function(i) {
            if( $(this).find($eaelAccordionListHeader).hasClass('active-default') ) {
                $(this).find($eaelAccordionListHeader).addClass('active');
                $(this).find('.eael-accordion-content').addClass('active').css('display', 'block').slideDown($accordionSpeed);
            }
        });

        if( 'accordion' == $accordionType ) {
            $eaelAccordionListHeader.on('click', function() {
                //Check if 'active' class is already exists
                if( $(this).hasClass('active') ) {
                    $(this).removeClass('active');
                    $(this).next('.eael-accordion-content').removeClass('active').slideUp($accordionSpeed);
                }else {
                    $eaelAccordionListHeader.removeClass('active');
                    $eaelAccordionListHeader.next('.eael-accordion-content').removeClass('active').slideUp($accordionSpeed);
            
                    $(this).toggleClass('active');
                    $(this).next('.eael-accordion-content').slideToggle($accordionSpeed, function() {
                        $(this).toggleClass('active');
                    });
                }
            });
        }

        if( 'toggle' == $accordionType ) {
            $eaelAccordionListHeader.on('click', function() {
                if( $(this).hasClass('active') ) {
                    $(this).removeClass('active');
                    $(this).next('.eael-accordion-content').removeClass('active').slideUp($accordionSpeed);
                }else {
                    $(this).toggleClass('active');
                    $(this).next('.eael-accordion-content').slideToggle($accordionSpeed, function() {
                        $(this).toggleClass('active');
                    });
                }
            });
        }
    }; // End of advance accordion


    /* ------------------------------ */
    /* Advance google map
    /* ------------------------------ */
    var AdvGoogleMap = function($scope, $) {

        var $map        = $scope.find('.eael-google-map'),
            $thisMap    = $('#' + $map.attr('id')),
            $mapID      = $thisMap.data('id'),
            $mapType    = $thisMap.data('map_type'),
            $mapAddressType    = $thisMap.data('map_address_type'),
            $mapLat     = $thisMap.data('map_lat'),
            $mapLng     = $thisMap.data('map_lng'),
            $mapAddr    = $thisMap.data('map_addr'),
            $mapBasicMarkerTitle            = $thisMap.data('map_basic_marker_title'),
            $mapBasicMarkerContent          = $thisMap.data('map_basic_marker_content'),
            $mapBasicMarkerIconEnable       = $thisMap.data('map_basic_marker_icon_enable'),
            $mapBasicMarkerIcon             = $thisMap.data('map_basic_marker_icon'),
            $mapBasicMarkerIconWidth        = $thisMap.data('map_basic_marker_icon_width'),
            $mapBasicMarkerIconHeight       = $thisMap.data('map_basic_marker_icon_height'),
            $mapZoom                = $thisMap.data('map_zoom'),
            $mapMarkerContent       = $thisMap.data('map_marker_content'),
            $mapMarkers             = $thisMap.data('map_markers'),
            $mapStaticWidth     = $thisMap.data('map_static_width'),
            $mapStaticHeight    = $thisMap.data('map_static_height'),
            $mapStaticLat       = $thisMap.data('map_static_lat'),
            $mapStaticLng       = $thisMap.data('map_static_lng'),
            $mapPolylines       = $thisMap.data('map_polylines'),
            $mapStrokeColor     = $thisMap.data('map_stroke_color'),
            $mapStrokeOpacity       = $thisMap.data('map_stroke_opacity'),
            $mapStrokeWeight        = $thisMap.data('map_stroke_weight'),
            $mapStrokeFillColor     = $thisMap.data('map_stroke_fill_color'),
            $mapStrokeFillOpacity       = $thisMap.data('map_stroke_fill_opacity'),
            $mapOverlayContent          = $thisMap.data('map_overlay_content'),
            $mapRoutesOriginLat         = $thisMap.data('map_routes_origin_lat'),
            $mapRoutesOriginLng    = $thisMap.data('map_routes_origin_lng'),
            $mapRoutesDestLat    = $thisMap.data('map_routes_dest_lat'),
            $mapRoutesDestLng    = $thisMap.data('map_routes_dest_lng'),
            $mapRoutesTravelMode    = $thisMap.data('map_routes_travel_mode'),
            $mapPanoramaLat    = $thisMap.data('map_panorama_lat'),
            $mapPanoramaLng    = $thisMap.data('map_panorama_lng'),
            $mapTheme          = JSON.parse(decodeURIComponent(($thisMap.data('map_theme') + '').replace(/\+/g, '%20'))),
            $map_streeview_control  = $thisMap.data('map_streeview_control'),
            $map_type_control       = $thisMap.data('map_type_control'),
            $map_zoom_control       = $thisMap.data('map_zoom_control'),
            $map_fullscreen_control = $thisMap.data('map_fullscreen_control'),
            $map_scroll_zoom        = $thisMap.data('map_scroll_zoom');

        var eaelMapHeader = new GMaps({
            el: "#eael-google-map-" + $mapID,
            lat: $mapLat,
            lng: $mapLng,
            zoom: $mapZoom,
            streetViewControl: $map_streeview_control,
            mapTypeControl: $map_type_control,
            zoomControl: $map_zoom_control,
            fullscreenControl: $map_fullscreen_control,
            scrollwheel: $map_scroll_zoom
        });

        if($mapTheme != '') {
            eaelMapHeader.addStyle({
                styledMapName:"Styled Map",
                styles: JSON.parse($mapTheme),
                mapTypeId: "map_style"  
            });
            
            eaelMapHeader.setStyle("map_style");
        }

        if( 'basic' == $mapType ) {

            var infoWindowHolder = $mapBasicMarkerContent != '' ? { content: $mapBasicMarkerContent} : '';
                
            if($mapBasicMarkerIconEnable == 'yes') {
                var iconHolder = {
                    url: $mapBasicMarkerIcon,
                    scaledSize: new google.maps.Size($mapBasicMarkerIconWidth, $mapBasicMarkerIconHeight),
                }
            } else { var iconHolder = null; }

            if($mapAddressType == 'address') {
                GMaps.geocode({
                    address: $mapAddr,
                    callback: function(results, status) {
                        if (status == 'OK') {
                            var latlng = results[0].geometry.location;
                            eaelMapHeader.setCenter(latlng.lat(), latlng.lng());
                            eaelMapHeader.addMarker({
                                lat: latlng.lat(),
                                lng: latlng.lng(),
                                title: $mapBasicMarkerTitle,
                                infoWindow: infoWindowHolder,
                                icon: iconHolder
                            });
                        }
                    }
                });
            }else if($mapAddressType == 'coordinates') {
                eaelMapHeader.addMarker({
                    lat: $mapLat,
                    lng: $mapLng,
                    title: $mapBasicMarkerTitle,
                    infoWindow: infoWindowHolder,
                    icon: iconHolder
                });
            }
        
        } // end of basic map script


        if('marker' == $mapType) {

            var $data = JSON.parse(decodeURIComponent(($mapMarkers + '').replace(/\+/g, '%20')));

            if($data.length > 0) {
                $data.forEach(function($marker) {
                    
                    if($marker.eael_google_map_marker_content != '') {
                        var infoWindowHolder = {
                            content: $marker.eael_google_map_marker_content
                        };
                    }else {
                        var infoWindowHolder = '';
                    }

                    if($marker.eael_google_map_marker_icon_enable == 'yes') {
                        var iconHolder =  {
                            url: $marker.eael_google_map_marker_icon.url,
                            scaledSize: new google.maps.Size($marker.eael_google_map_marker_icon_width, $marker.eael_google_map_marker_icon_height), // scaled size
                        };
                    }else {
                        var iconHolder = '';
                    }


                    eaelMapHeader.addMarker({
                        lat: parseFloat($marker.eael_google_map_marker_lat),
                        lng: parseFloat($marker.eael_google_map_marker_lng),
                        title: $marker.eael_google_map_marker_title,
                        infoWindow: infoWindowHolder,
                        icon: iconHolder
                    });

                });
            }
        }// end of multiple markers map


        if('static' == $mapType) {

            var $data = JSON.parse(decodeURIComponent(($mapMarkers + '').replace(/\+/g, '%20'))),
                markersHolder = [];

            if($data.length > 0) {
                $data.forEach(function($marker) {
                    markersHolder.push(
                        {
                            lat: parseFloat($marker.eael_google_map_marker_lat),
                            lng: parseFloat($marker.eael_google_map_marker_lng),
                            color: $marker.eael_google_map_marker_icon_color
                        },
                    );
                });
            }

            var eaelStaticMapUrl = GMaps.staticMapURL({
                size: [$mapStaticWidth, $mapStaticHeight],
                lat: $mapStaticLat,
                lng: $mapStaticLng,
                markers: markersHolder
            });

            $('<img />').attr('src', eaelStaticMapUrl).appendTo('#eael-google-map-' + $mapID);

        } // End of static map


        if('polyline' == $mapType) {

            var $polylines_data = JSON.parse(decodeURIComponent(($mapPolylines + '').replace(/\+/g, '%20'))),
                $data = JSON.parse(decodeURIComponent(($mapMarkers + '').replace(/\+/g, '%20'))),
                $eael_polylines = [];


            var eaelPolylineMap = new GMaps({
                el: '#eael-google-map-' + $mapID,
                lat: $mapLat,
                lng: $mapLng,
                zoom: $mapZoom,
                center: {
                    lat: -12.07635776902266,
                    lng: -77.02792530422971,
                }
            });

            $polylines_data.forEach(function($polyline) {
                $eael_polylines.push(
                    [
                        parseFloat($polyline.eael_google_map_polyline_lat),
                        parseFloat($polyline.eael_google_map_polyline_lng)
                    ]
                )
            });
            
            var path = JSON.parse(JSON.stringify($eael_polylines));

            eaelPolylineMap.drawPolyline({
                path: path,
                strokeColor: $mapStrokeColor.toString(),
                strokeOpacity: $mapStrokeOpacity,
                strokeWeight: $mapStrokeWeight
            });

            $data.forEach(function($marker) {
                if($marker.eael_google_map_marker_content != '') {
                    var infoWindowHolder = {
                        content: $marker.eael_google_map_marker_content
                    };
                }else {
                    var infoWindowHolder = '';
                }

                if($marker.eael_google_map_marker_icon_enable == 'yes') {
                    var iconHolder =  {
                        url:$marker.eael_google_map_marker_icon.url,
                        scaledSize: new google.maps.Size($marker.eael_google_map_marker_icon_width, $marker.eael_google_map_marker_icon_height), // scaled size
                    };
                }else {
                    var iconHolder = '';
                }

                eaelPolylineMap.addMarker({
                    lat: $marker.eael_google_map_marker_lat,
                    lng: $marker.eael_google_map_marker_lng,
                    title: $marker.eael_google_map_marker_title,
                    infoWindow: infoWindowHolder,
                    icon: iconHolder
                });
            });

            if($mapTheme != '') {
                eaelPolylineMap.addStyle({
                    styledMapName:"Styled Map",
                    styles: JSON.parse($mapTheme),
                    mapTypeId: "polyline_map_style"  
                });

                eaelPolylineMap.setStyle("polyline_map_style");
            }
            
        } // End of polyline map


        if('polygon' == $mapType) {

            var $polylines_data = JSON.parse(decodeURIComponent(($mapPolylines + '').replace(/\+/g, '%20'))),
                $eael_polylines = [];

            $polylines_data.forEach(function($polyline) {
                $eael_polylines.push(
                    [
                        parseFloat($polyline.eael_google_map_polyline_lat),
                        parseFloat($polyline.eael_google_map_polyline_lng)
                    ]
                )
            });

            var path = JSON.parse(JSON.stringify($eael_polylines));
            eaelMapHeader.drawPolygon({
                paths: path,
                strokeColor: $mapStrokeColor.toString(),
                strokeOpacity: $mapStrokeOpacity,
                strokeWeight: $mapStrokeWeight,
                fillColor: $mapStrokeFillColor.toString(),
                fillOpacity: $mapStrokeFillOpacity
            });
        } // End of polygon map


        if('overlay' == $mapType) {

            if( $mapOverlayContent != '') {
                var contentHolder = '<div class="eael-gmap-overlay">'+$mapOverlayContent+'</div>';
            }else {
                var contentHolder = '';
            }

            eaelMapHeader.drawOverlay({
                lat: $mapLat,
                lng: $mapLng,
                content: contentHolder
            });
        } // End of overlay map


        if('routes' == $mapType) {
            eaelMapHeader.drawRoute({
                origin: [$mapRoutesOriginLat, $mapRoutesOriginLng],
                destination: [$mapRoutesDestLat, $mapRoutesDestLng],
                travelMode: $mapRoutesTravelMode.toString(),
                strokeColor: $mapStrokeColor.toString(),
                strokeOpacity: $mapStrokeOpacity,
                strokeWeight: $mapStrokeWeight,
            });

            var $data = JSON.parse(decodeURIComponent(($mapMarkers + '').replace(/\+/g, '%20')));

            if($data.length > 0) {
                $data.forEach(function($marker) {
                
                    if($marker.eael_google_map_marker_content != '') {
                        var infoWindowHolder = {
                            content: $marker.eael_google_map_marker_content
                        };
                    }else {
                        var infoWindowHolder = '';
                    }

                    if($marker.eael_google_map_marker_icon_enable == 'yes') {
                        var iconHolder =  {
                            url:$marker.eael_google_map_marker_icon.url,
                            scaledSize: new google.maps.Size($marker.eael_google_map_marker_icon_width, $marker.eael_google_map_marker_icon_height), // scaled size
                        };
                    }else {
                        var iconHolder = '';
                    }

                    eaelMapHeader.addMarker({
                        lat: $marker.eael_google_map_marker_lat,
                        lng: $marker.eael_google_map_marker_lng,
                        title: $marker.eael_google_map_marker_title,
                        infoWindow:infoWindowHolder,
                        icon: iconHolder,
                    });
                });
            }
        } // End of map routers


        if('panorama' == $mapType) {
            var eaelPanorama = GMaps.createPanorama({
                el: '#eael-google-map-'+$mapID,
                lat: $mapPanoramaLat,
                lng: $mapPanoramaLng,
            });
        } // end of map panorama

    } // Advance google map

    /* ------------------------------ */
    /* Advance Tab
    /* ------------------------------ */
    var AdvanceTabHandler = function ($scope, $) {

        jQuery(document).ready(function($) {
            var $currentTab = $scope.find('.eael-advance-tabs'),
                $currentTabId = '#' + $currentTab.attr('id').toString();

                $($currentTabId + ' .eael-tabs-nav ul li').each( function(index) {
                    if( $(this).hasClass('active-default') ) {
                        $($currentTabId + ' .eael-tabs-nav > ul li').removeClass('active').addClass('inactive');
                        $(this).removeClass('inactive');
                    }else {
                        if( index == 0 ) {
                            $(this).removeClass('inactive').addClass('active');
                
                        }
                    }
                } );

                $($currentTabId + ' .eael-tabs-content div').each( function(index) {
                    if( $(this).hasClass('active-default') ) {
                        $($currentTabId + ' .eael-tabs-content > div').removeClass('active');
                    }else {
                        if( index == 0 ) {
                            $(this).removeClass('inactive').addClass('active');
                        }
                    }
                } );

                $($currentTabId + ' .eael-tabs-nav ul li').click(function(){
                    var currentTabIndex = $(this).index();
                    var tabsContainer = $(this).closest('.eael-advance-tabs');
                    var tabsNav = $(tabsContainer).children('.eael-tabs-nav').children('ul').children('li');
                    var tabsContent = $(tabsContainer).children('.eael-tabs-content').children('div');
                
                    $(this).parent('li').addClass('active');
                
                    $(tabsNav).removeClass('active active-default').addClass('inactive');
                    $(this).addClass('active').removeClass('inactive');
                
                    $(tabsContent).removeClass('active').addClass('inactive');
                    $(tabsContent).eq(currentTabIndex).addClass('active').removeClass('inactive');
                
                    $(tabsContent).each( function(index) {
                        $(this).removeClass('active-default');
                });
            });

        });
    }

    /* ------------------------------ */
    /* Post Timeline
    /* ------------------------------ */
    var postTimelineHandler = function ($scope, $) {
        var $_this = $scope.find('.eael-post-timeline'),
            $currentTimelineId = '#' + $_this.attr('id'),
            $site_url       = $_this.data('url'),
            $total_posts    = $_this.data('total_posts'),
            $timeline_id    = $_this.data('timeline_id'),
            $post_type      = $_this.data('post_type'),
            $posts_per_page     = $_this.data('posts_per_page'),
            $post_order         = $_this.data('post_order'),
            $show_images        = $_this.data('show_images'),
            $show_title         = $_this.data('show_title'),
            $show_excerpt       = $_this.data('show_excerpt'),
            $excerpt_length     = $_this.data('excerpt_length'),
            $btn_text       = $_this.data('btn_text'),
            $categories     = $_this.data('categories');

        var options = {
            siteUrl: $site_url,
            totalPosts: $total_posts,
            loadMoreBtn: $( '#eael-load-more-btn-'+$timeline_id ),
            postContainer: $( '.eael-post-appender-'+$timeline_id ),
            postStyle: 'timeline',
        }
    
        var settings = {
            postType: $post_type,
            perPage: parseInt( $posts_per_page, 10 ),
            postOrder: $post_order,
            showImage: $show_images,
            showTitle: $show_title,
            showExcerpt: $show_excerpt,
            excerptLength: parseInt( $excerpt_length, 10 ),
            btnText: $btn_text,
            categories: $categories
        }
        eaelLoadMore( options, settings );
    }


   // Team member carousel
    var TeamMemberCarouselHandler = function ($scope, $) {
       var $carousel                   = $scope.find('.eael-tm-carousel').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };

    // Image otspots
    var ImageHotspotHandler = function ($scope, $) {
        $('.eael-hot-spot-tooptip').each(function () {
            var $position_local         = $(this).data('tooltip-position-local'),
                $position_global        = $(this).data('tooltip-position-global'),
                $width                  = $(this).data('tooltip-width'),
                $size                   = $(this).data('tooltip-size'),
                $animation_in           = $(this).data('tooltip-animation-in'),
                $animation_out          = $(this).data('tooltip-animation-out'),
                $background             = $(this).data('tooltip-background'),
                $text_color             = $(this).data('tooltip-text-color'),
                $arrow                  = ($(this).data('eael-tooltip-arrow') === 'yes') ? true : false,
                $position               = $position_local;

            if (typeof $position_local === 'undefined' || $position_local === 'global') {
                $position = $position_global;
            }
            if (typeof $animation_out === 'undefined' || !$animation_out) {
                $animation_out = $animation_in;
            }
            
            $(this).tipso({
                speed:                  200,
                delay:                  200,
                width:                  $width,
                background:             $background,
                color:                  $text_color,
                size:                   $size,
                position:               $position,
                animationIn:            $animation_in,
                animationOut:           $animation_out,
                showArrow:              $arrow
            });
        });
    };

    var LogoCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.eael-logo-carousel').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            $centeredSlides             = ($effect == 'coverflow')? true : false,

            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                effect:                 $effect,
                centeredSlides:         $centeredSlides,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                autoHeight:             true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };

    var PostCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.eael-post-carousel').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            $centeredSlides             = ($effect == 'coverflow')? true : false,

            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                effect:                 $effect,
                centeredSlides:         $centeredSlides,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                autoHeight:             true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };
    
    var FacebookCarouselHandler = function ($scope, $) {

        var loadingFeed = $scope.find( '.eael-loading-feed' );
        var $fbCarousel            = $scope.find('.eael-facebook-feed-carousel-wrapper').eq(0),
                $name         = ($fbCarousel.data("facebook-feed-carousel-ac") !== undefined) ? $fbCarousel.data("facebook-feed-carousel-ac") : '',
                $limit         = ($fbCarousel.data("facebook-feed-carousel-post-limit") !== undefined) ? $fbCarousel.data("facebook-feed-carousel-post-limit") : '',
                $app_id         = ($fbCarousel.data("facebook-feed-carousel-app-id") !== undefined) ? $fbCarousel.data("facebook-feed-carousel-app-id") : '',
                $app_secret         = ($fbCarousel.data("facebook-feed-carousel-app-secret") !== undefined) ? $fbCarousel.data("facebook-feed-carousel-app-secret") : '',
                $length         = ($fbCarousel.data("facebook-feed-carousel-length") !== undefined) ? $fbCarousel.data("facebook-feed-carousel-length") : 400,
                $media         = ($fbCarousel.data("facebook-feed-carousel-media") !== undefined) ? $fbCarousel.data("facebook-feed-carousel-media") : false,
                $carouselId         = ($fbCarousel.data("facebook-feed-carousel-id") !== undefined) ? $fbCarousel.data("facebook-feed-carousel-id") : ' '; 
        
        /**
    	 * Facebook Feed Init
    	 */
    	function eael_facebook_feeds() {
            var $access_token = ($app_id+'|'+$app_secret).toString();
            var $id_name = $name.toString();
    		$( '#eael-facebook-feed-'+ $carouselId +'.eael-facebook-feed-main-carousel-container' ).socialfeed({

			    facebook:{
			       accounts:[$id_name],
			       limit: $limit,
                   access_token: $access_token
			    },

	            // GENERAL SETTINGS
	            length: $length,
	            show_media: $media,
	            template_html: '<div class="swiper-slide"><div class="carousel-cell eael-social-feed-element {{?\ !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{\{=it.id}}">\
                {{=it.attachment}}\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read \</a></p>\
                        </div>\
                    </div>\
                </div>\
            </div></div>',
	        });
    	}

    	/**
    	 * Facebook Feed Carousel View
    	 */
         function eael_facebook_feed_carosuel() {
            var $carousel               = $scope.find('.eael-facebook-feed-carousel-nav').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $centeredSlides             = ($effect == 'coverflow')? true : false,

            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                effect:                 $effect,
                centeredSlides:         $centeredSlides,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                autoHeight:             true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
        }

		$.ajax({
		   	url: eael_facebook_feeds(),
		   	beforeSend: function() {
		   		loadingFeed.addClass( 'show-loading' );
		   	},
		   	success: function() {
		   		setTimeout(function() {
		   		eael_facebook_feed_carosuel();
					loadingFeed.removeClass( 'show-loading' );
		   		}, 2000);
			},
			error: function() {
				console.log('error loading');
			}
		});  
    };

    var FacebookFeedHandler = function ($scope, $) {
        var loadingFeed = $scope.find( '.eael-loading-feed' );
        var $fbCarousel            = $scope.find('.eael-facebook-feed-layout-wrapper').eq(0),
                $name         = ($fbCarousel.data("facebook-feed-ac-name") !== undefined) ? $fbCarousel.data("facebook-feed-ac-name") : '',
                $limit         = ($fbCarousel.data("facebook-feed-post-limit") !== undefined) ? $fbCarousel.data("facebook-feed-post-limit") : '',
                $app_id         = ($fbCarousel.data("facebook-feed-app-id") !== undefined) ? $fbCarousel.data("facebook-feed-app-id") : '',
                $app_secret         = ($fbCarousel.data("facebook-feed-app-secret") !== undefined) ? $fbCarousel.data("facebook-feed-app-secret") : '',
                $length         = ($fbCarousel.data("facebook-feed-content-length") !== undefined) ? $fbCarousel.data("facebook-feed-content-length") : 400,
                $media         = ($fbCarousel.data("facebook-feed-media") !== undefined) ? $fbCarousel.data("facebook-feed-media") : false,
                $feed_type     = ($fbCarousel.data("facebook-feed-type") !== undefined) ? $fbCarousel.data("facebook-feed-type") : false,
                $carouselId         = ($fbCarousel.data("facebook-feed-id") !== undefined) ? $fbCarousel.data("facebook-feed-id") : ' ';
        // Facebook Feed Init
    	function eael_facebook_feeds() {
            var $access_token = ($app_id+'|'+$app_secret).toString();
            var $id_name = $name.toString();
    		$( '#eael-facebook-feed-'+ $carouselId +'.eael-facebook-feed-layout-container' ).socialfeed({

			    facebook:{
			       accounts:[$id_name],
			       limit: $limit,
                   access_token: $access_token
			    },

	            // GENERAL SETTINGS
	            length: $length,
	            show_media: $media,
	            template_html: '<div class="eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}\" social-feed-id = "{{=it.id}}">\
                {{=it.attachment}}\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More</a></p>\
                        </div>\
                    </div>\
                </div>\
            </div>',
	        });
        }
        
        // Facebook Feed Masonry View
		function eael_facebook_feed_masonry() {
            $('.eael-facebook-feed-layout-container.masonry-view').masonry({
			    itemSelector: '.eael-social-feed-element',
			    percentPosition: true,
			    columnWidth: '.eael-social-feed-element'
			});
        }

        $.ajax({
            url: eael_facebook_feeds(),
            beforeSend: function() {
                loadingFeed.addClass( 'show-loading' );
            },
            success: function() {
                if($feed_type == 'masonry') {
                    setTimeout(function() {
                        eael_facebook_feed_masonry();
                    }, 2000);
                     
                }                
             loadingFeed.removeClass( 'show-loading' );
         },
         error: function() {
             console.log('error loading');
         }
     });
        

    };

    var TwitterFeedHandler = function ($scope, $) {
        var loadingFeed = $scope.find( '.eael-loading-feed' );
        var $twitterFeed            = $scope.find('.eael-twitter-feed-layout-wrapper').eq(0),
                $name               = ($twitterFeed.data("twitter-feed-ac-name") !== undefined) ? $twitterFeed.data("twitter-feed-ac-name") : '',
                $limit              = ($twitterFeed.data("twitter-feed-post-limit") !== undefined) ? $twitterFeed.data("twitter-feed-post-limit") : '',
                $hash_tag           = ($twitterFeed.data("twitter-feed-hashtag-name") !== undefined) ? $twitterFeed.data("twitter-feed-hashtag-name") : '',
                $key                = ($twitterFeed.data("twitter-feed-consumer-key") !== undefined) ? $twitterFeed.data("twitter-feed-consumer-key") : '',
                $app_secret         = ($twitterFeed.data("twitter-feed-consumer-secret") !== undefined) ? $twitterFeed.data("twitter-feed-consumer-secret") : '',
                $length             = ($twitterFeed.data("twitter-feed-content-length") !== undefined) ? $twitterFeed.data("twitter-feed-content-length") : 400,
                $media              = ($twitterFeed.data("twitter-feed-media") !== undefined) ? $twitterFeed.data("twitter-feed-media") : false,
                $feed_type          = ($twitterFeed.data("twitter-feed-type") !== undefined) ? $twitterFeed.data("twitter-feed-type") : false,  
                $carouselId         = ($twitterFeed.data("twitter-feed-id") !== undefined) ? $twitterFeed.data("twitter-feed-id") : ' '; 

        var $id_name = $name.toString(); 
        var $hash_tag_name = $hash_tag.toString();    
        var $key_name = $key.toString();
        var $app_secret = $app_secret.toString();
        
        function eael_twitter_feeds() {
    		$( '#eael-twitter-feed-'+ $carouselId +'.eael-twitter-feed-layout-container' ).socialfeed({
	            // TWITTER
			    twitter:{
			       accounts: [ $id_name , $hash_tag_name ],
			       limit: $limit,
			       consumer_key: $key_name,
			       consumer_secret: $app_secret,
			    },

	            // GENERAL SETTINGS
	            length: $length,
	            show_media: $media,
	            template_html: '<div class="eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{{=it.id}}">\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More <i class="fa fa-angle-double-right"></i></a></p>\
                        </div>\
                    </div>\
                </div>\
                {{=it.attachment}}\
            </div>',
	        });
    	}

		
		//Twitter Feed masonry View
		
		function eael_twitter_feed_masonry() {
			$('.eael-twitter-feed-layout-container.masonry-view').masonry({
			    itemSelector: '.eael-social-feed-element',
			    percentPosition: true,
			    columnWidth: '.eael-social-feed-element'
			});
		}

		$.ajax({
		   	url: eael_twitter_feeds(),
		   	beforeSend: function() {
		   		loadingFeed.addClass( 'show-loading' );
		   	},
		   	success: function() {
                if($feed_type == 'masonry') {
                    setTimeout(function() {
                        eael_twitter_feed_masonry();
                    }, 2000);
                     
                }
                loadingFeed.removeClass( 'show-loading' );
			},
			error: function() {
				console.log('error loading');
			}
        });
                
     }

     var TwitterFeedCarouselHandler = function ($scope, $) {
        var loadingFeed = $scope.find( '.eael-loading-feed' );
        var $twitterFeedCarousel            = $scope.find('.eael-twitter-feed-carousel-wrapper').eq(0),
                $name         = ($twitterFeedCarousel.data("twitter-feed-carousel-ac-name") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-ac-name") : '',
                $limit         = ($twitterFeedCarousel.data("twitter-feed-carousel-post-limit") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-post-limit") : '',
                $hash_tag         = ($twitterFeedCarousel.data("twitter-feed-carousel-hashtag-name") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-hashtag-name") : '',
                $key         = ($twitterFeedCarousel.data("twitter-feed-carousel-consumer-key") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-consumer-key") : '',
                $app_secret         = ($twitterFeedCarousel.data("twitter-feed-carousel-consumer-secret") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-consumer-secret") : '',
                $length         = ($twitterFeedCarousel.data("twitter-feed-carousel-content-length") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-content-length") : 400,
                $media         = ($twitterFeedCarousel.data("twitter-feed-carousel-media") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-media") : false,  
                $carouselId         = ($twitterFeedCarousel.data("twitter-feed-carousel-id") !== undefined) ? $twitterFeedCarousel.data("twitter-feed-carousel-id") : ' '; 
        var $id_name = $name.toString(); 
        var $hash_tag_name = $hash_tag.toString();    
        var $key_name = $key.toString();
        var $app_secret = $app_secret.toString();
        
        function eael_twitter_feeds() {
    		$( '#eael-twitter-feed-'+ $carouselId +'.eael-twitter-feed-main-carousel-container' ).socialfeed({
	            // TWITTER
			    twitter:{
			       accounts: [ $id_name , $hash_tag_name ],
			       limit: $limit,
			       consumer_key: $key_name,
			       consumer_secret: $app_secret,
			    },

	            // GENERAL SETTINGS
	            length: $length,
	            show_media: $media,
	            template_html: '<div class="swiper-slide"><div class="carousel-cell eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{{=it.id}}">\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More <i class="fa fa-angle-double-right"></i></a></p>\
                        </div>\
                    </div>\
                </div>\
                {{=it.attachment}}\
            </div></div>',
	        });
    	}

                
                /**
    	 * Twitter Feed Carousel View
    	 */
         function eael_twitter_feed_carosuel() {
            var $carousel               = $scope.find('.eael-twitter-feed-carousel-nav').eq(0),
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 0,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $centeredSlides             = ($effect == 'coverflow')? true : false,

            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                effect:                 $effect,
                centeredSlides:         $centeredSlides,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                pagination:             $pagination,
                paginationClickable:    true,
                autoHeight:             true,
                loop:                   $loop,
                nextButton:             $arrow_next,
                prevButton:             $arrow_prev,
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
        }

				$.ajax({
				   	url: eael_twitter_feeds(),
				   	beforeSend: function() {
				   		loadingFeed.addClass( 'show-loading' );
				   	},
				   	success: function() {
						setTimeout( function() {
                            eael_twitter_feed_carosuel();
							loadingFeed.removeClass( 'show-loading' );							
						}, 6000 );
					},
					error: function() {
						console.log('error loading');
					}
                });
     }


    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-flip-carousel.default', FlipCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-filterable-gallery.default', FilterGallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-dynamic-filterable-gallery.default', FilterGallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-instafeed.default', InstagramGallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-accordion.default', AdvAccordionHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-google-map.default', AdvGoogleMap);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-counter.default', CounterHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-tabs.default', AdvanceTabHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-post-timeline.default', postTimelineHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-team-member-carousel.default', TeamMemberCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-image-hotspots.default', ImageHotspotHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-logo-carousel.default', LogoCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-post-carousel.default', PostCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-facebook-feed-carousel.default', FacebookCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-facebook-feed.default', FacebookFeedHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-twitter-feed.default', TwitterFeedHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-twitter-feed-carousel.default', TwitterFeedCarouselHandler);
    });
}(jQuery));