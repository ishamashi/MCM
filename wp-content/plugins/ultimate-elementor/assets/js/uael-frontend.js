( function( $ ) {

	var isElEditMode = false;

	/**
	 * Function for Before After Slider animation.
	 *
	 */
	var UAELBASlider = function( $element ) {

		$element.css( 'width', '' );
		$element.css( 'height', '' );

		max = -1;

		$element.find( "img" ).each(function() {
			if( max < $(this).width() ) {
				max = $(this).width();
			}
		});

		$element.css( 'width', max + 'px' );
	}

	/**
	 * Function for GF Styler select field.
	 *
	 */
	var WidgetUAELGFStylerHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope )
			return;

		var	gfSelectFields = $scope.find('select:not([multiple])');

		gfSelectFields.wrap( "<span class='uael-gf-select-custom'></span>" );
	}

	/**
	 * Function for CF7 Styler select field.
	 *
	 */
	var WidgetUAELCF7StylerHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope )
			return;

		var	cf7SelectFields = $scope.find('select:not([multiple])'),
			cf7Loader = $scope.find('span.ajax-loader');


		cf7SelectFields.wrap( "<span class='uael-cf7-select-custom'></span>" );

		cf7Loader.wrap( "<div class='uael-cf7-loader-active'></div>" );

		var wpcf7event = document.querySelector( '.wpcf7' );

		wpcf7event.addEventListener( 'wpcf7submit', function( event ) {
			var cf7ErrorFields = $scope.find('.wpcf7-not-valid-tip');
		    cf7ErrorFields.wrap( "<span class='uael-cf7-alert'></span>" );
		}, false );

	}

	/**
	 * Function for Fancy Text animation.
	 *
	 */
	var UAELFancyText = function() {

		var id 					= $( this ).data( 'id' );
		var $this 				= $( this ).find( '.uael-fancy-text-node' );
		var animation			= $this.data( 'animation' );
		var fancystring 		= $this.data( 'strings' );
		var nodeclass           = '.elementor-element-' + id;

		var typespeed 			= $this.data( 'type-speed' );
		var backspeed 			= $this.data( 'back-speed' );
		var startdelay 			= $this.data( 'start-delay' );
		var backdelay 			= $this.data( 'back-delay' );
		var loop 				= $this.data( 'loop' );
		var showcursor 			= $this.data( 'show_cursor' );
		var cursorchar 			= $this.data( 'cursor-char' );

		var speed 				= $this.data('speed');
		var pause				= $this.data('pause');
		var mousepause			= $this.data('mousepause');

		if ( 'type' == animation ) {
			$( nodeclass + ' .uael-typed-main' ).typed({
				strings: fancystring,
				typeSpeed: typespeed,
				startDelay: startdelay,
				backSpeed: backspeed,
				backDelay: backdelay,
				loop: loop,
				showCursor: showcursor,
				cursorChar: cursorchar,
	        });
		} else if ( 'slide' == animation ) {

			$( nodeclass + ' .uael-slide-main' ).vTicker('init', {
					strings: fancystring,
					speed: speed,
					pause: pause,
					mousePause: mousepause,
			});
		}
	}

	/**
	 * Before After Slider handler Function.
	 *
	 */
	var WidgetUAELBASliderHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope )
			return;

		var selector = $scope.find( '.uael-ba-container' );
		var initial_offset = selector.data( 'offset' );
		var move_on_hover = selector.data( 'move-on-hover' );
		var orientation = selector.data( 'orientation' );

		$scope.css( 'width', '' );
		$scope.css( 'height', '' );

		if( 'yes' == move_on_hover ) {
			move_on_hover = true;
		} else {
			move_on_hover = false;
		}

		$scope.imagesLoaded( function() {

			UAELBASlider( $scope );

			$scope.find( '.uael-ba-container' ).twentytwenty(
	            {
	                default_offset_pct: initial_offset,
	                move_on_hover: move_on_hover,
	                orientation: orientation
	            }
	        );

	        $( window ).resize( function( e ) {
	        	UAELBASlider( $scope );
	        } );
		} );
	};

	/**
	 * Fancy text handler Function.
	 *
	 */
	var WidgetUAELFancyTextHandler = function( $scope, $ ) {
		if ( 'undefined' == typeof $scope ) {
			return;
		}
		var node_id = $scope.data( 'id' );
		var viewport_position	= 90;
		var selector = $( '.elementor-element-' + node_id );

		if( typeof elementorFrontend.waypoint !== 'undefined' ) {
			elementorFrontend.waypoint(
				selector,
				UAELFancyText,
				{
					offset: viewport_position + '%'
				}
			);
		}
	};

	/**
	 * Radio Button Switcher JS Function.
	 *
	 */
	var WidgetUAELContentToggleHandler = function( $scope, $ ) {
		if ( 'undefined' == typeof $scope ) {
			return;
		}
		var $this           = $scope.find( '.uael-rbs-wrapper' );
		var node_id 		= $scope.data( 'id' );
		var rbs_section_1   = $scope.find( ".uael-rbs-section-1" );
		var rbs_section_2   = $scope.find( ".uael-rbs-section-2" );
		var main_btn        = $scope.find( ".uael-main-btn" );
		var switch_type     = main_btn.attr( 'data-switch-type' );
		var rbs_label_1   	= $scope.find( ".uael-sec-1" );
		var rbs_label_2   	= $scope.find( ".uael-sec-2" );
		var current_class;

		switch ( switch_type ) {
			case 'round_1':
				current_class = '.uael-switch-round-1';
				break;
			case 'round_2':
				current_class = '.uael-switch-round-2';
				break;
			case 'rectangle':
				current_class = '.uael-switch-rectangle';
				break;
			case 'label_box':
				current_class = '.uael-switch-label-box';
				break;
			default:
				current_class = 'No Class Selected';
				break;
		}

		var rbs_switch      = $scope.find( current_class );

		if( rbs_switch.is( ':checked' ) ) {
			rbs_section_1.hide();
			rbs_section_2.show();
		} else {
			rbs_section_1.show();
			rbs_section_2.hide();
		}

		rbs_switch.on('click', function(e){
	        rbs_section_1.toggle();
	        rbs_section_2.toggle();
	    });

		/* Label 1 Click */
		rbs_label_1.on('click', function(e){
			// Uncheck
			rbs_switch.prop("checked", false);
			rbs_section_1.show();
			rbs_section_2.hide();

	    });

	    /* Label 2 Click */
		rbs_label_2.on('click', function(e){
			// Check
			rbs_switch.prop("checked", true);
			rbs_section_1.hide();
			rbs_section_2.show();
	    });
	};

	/*
	 * Image Gallery handler Function.
	 *
	 */
	var WidgetUAELImageGalleryHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope ) {
			return;
		}

		/* Carousel */
		var slider_selector	= $scope.find('.uael-img-carousel-wrap');

		if ( slider_selector.length > 0 ) {

			var adaptiveImageHeight = function( e, obj ) {

				var node = obj.$slider,
                post_active = node.find('.slick-slide.slick-active'),
                max_height = -1;

	            post_active.each(function( i ) {

	                var $this = $( this ),
	                    this_height = $this.innerHeight();

	                if( max_height < this_height ) {
	                    max_height = this_height;
	                }
	            });

	            node.find('.slick-list.draggable').animate({ height: max_height }, { duration: 200, easing: 'linear' });
	            max_height = -1;
			};

			var slider_options 	= JSON.parse( slider_selector.attr('data-image_carousel') );

			/* Execute when slick initialize */
			slider_selector.on('init', adaptiveImageHeight );

			$scope.imagesLoaded( function(e) {

				slider_selector.slick(slider_options);

				/* After slick slide change */
				slider_selector.on('afterChange', adaptiveImageHeight );

				slider_selector.find('.uael-grid-item').resize( function() {
					// Manually refresh positioning of slick
					setTimeout(function() {
						slider_selector.slick('setPosition');
					}, 300);
				});
			});
		}

		/* Grid */
		if ( ! isElEditMode ) {

			var selector = $scope.find( '.uael-img-grid-masonry-wrap' );

			if ( selector.length < 1 ) {
				return;
			}

			if ( ! ( selector.hasClass('uael-masonry') || selector.hasClass('uael-cat-filters') ) ) {
				return;
			}

			var layoutMode = 'fitRows';

			if ( selector.hasClass('uael-masonry') ) {
				layoutMode = 'masonry';
			}

			var filters = $scope.find('.uael-masonry-filters');
			var def_cat = '*';

			if ( filters.length > 0 ) {

				var def_filter = filters.attr('data-default');

				if ( '' !== def_filter ) {

					def_cat 	= def_filter;
					def_cat_sel = filters.find('[data-filter="'+def_filter+'"]');

					if ( def_cat_sel.length > 0 ) {
						def_cat_sel.siblings().removeClass('uael-current');
						def_cat_sel.addClass('uael-current');
					}
				}
			}

			var masonaryArgs = {
				// set itemSelector so .grid-sizer is not used in layout
				filter 			: def_cat,
				itemSelector	: '.uael-grid-item',
				percentPosition : true,
				layoutMode		: layoutMode,
				hiddenStyle 	: {
					opacity 	: 0,
				},
			};

			var $isotopeObj = {};

			$scope.imagesLoaded( function(e) {
				$isotopeObj = selector.isotope( masonaryArgs );
			});

			// bind filter button click
			$scope.on( 'click', '.uael-masonry-filter', function() {

				var $this 		= $(this);
				var filterValue = $this.attr('data-filter');

				$this.siblings().removeClass('uael-current');
				$this.addClass('uael-current');

				$isotopeObj.isotope({ filter: filterValue });
			});
		}
	};

	UAELVideo = {

		/**
		 * Auto Play Video
		 *
		 */

		_play: function( selector ) {

			var iframe 		= $( "<iframe/>" );
	        var vurl 		= selector.data( 'src' );

	        if ( 0 == selector.find( 'iframe' ).length ) {

				iframe.attr( 'src', vurl );
				iframe.attr( 'frameborder', '0' );
				iframe.attr( 'allowfullscreen', '1' );
				iframe.attr( 'allow', 'autoplay;encrypted-media;' );

				selector.html( iframe );
	        }

	        selector.closest( '.uael-video__outer-wrap' ).find( '.uael-vimeo-wrap' ).hide();
		}
	}

	var WidgetUAELVideoHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope ) {
			return;
		}

		var outer_wrap = $scope.find( '.uael-video__outer-wrap' );

		outer_wrap.off( 'click' ).on( 'click', function( e ) {

			var selector 	= $( this ).find( '.uael-video__play' );

			UAELVideo._play( selector );

		});

		if( '1' == outer_wrap.data( 'autoplay' ) || true == outer_wrap.data( 'device' ) ) {

			UAELVideo._play( $scope.find( '.uael-video__play' ) );
		}
	};

	$( window ).on( 'elementor/frontend/init', function () {

		if ( elementorFrontend.isEditMode() ) {
			isElEditMode = true;
		}

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-fancy-heading.default', WidgetUAELFancyTextHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-ba-slider.default', WidgetUAELBASliderHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-content-toggle.default', WidgetUAELContentToggleHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-gf-styler.default', WidgetUAELGFStylerHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-cf7-styler.default', WidgetUAELCF7StylerHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-image-gallery.default', WidgetUAELImageGalleryHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/uael-video.default', WidgetUAELVideoHandler );

		if( elementorFrontend.isEditMode() ) {

			elementor.channels.data.on( 'element:after:duplicate element:after:remove', function( e, arg ) {
				$( '.elementor-widget-uael-ba-slider' ).each( function() {
					WidgetUAELBASliderHandler( $( this ), $ );
				} );
			} );
		}
	});

} )( jQuery );
