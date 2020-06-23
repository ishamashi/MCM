<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo Carousel Widget
 */
class Widget_Eael_Logo_Carousel extends Widget_Base {
    
    /**
	 * Retrieve logo carousel widget name.
	 */
    public function get_name() {
        return 'eael-logo-carousel';
    }

    /**
	 * Retrieve logo carousel widget title.
	 */
    public function get_title() {
        return __( 'EA Logo Carousel', 'essential-addons-elementor' );
    }

    /**
	 * Retrieve the list of categories the logo carousel widget belongs to.
	 */
    public function get_categories() {
        return [ 'essential-addons-elementor' ];
    }

    /**
	 * Retrieve logo carousel widget icon.
	 */
    public function get_icon() {
        return 'eicon-carousel essential-addons-elementor-admin-icon';
    }
    
    /**
	 * Register logo carousel widget controls.
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Logo Carousel
         */
        $this->start_controls_section(
            'section_logo_carousel',
            [
                'label'                 => __( 'Logo Carousel', 'essential-addons-elementor' ),
            ]
        );

		$this->add_control(
			'carousel_slides',
			[
				'label'                 => '',
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'logo_carousel_slide' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
					[
						'logo_carousel_slide' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
					[
						'logo_carousel_slide' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
					[
						'logo_carousel_slide' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
					[
						'logo_carousel_slide' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
				],
				'fields'                => [
					[
						'name'        => 'logo_carousel_slide',
						'label'       => __( 'Upload Logo Image', 'essential-addons-elementor' ),
						'type'        => Controls_Manager::MEDIA,
                        'dynamic'     => [
                            'active'   => true,
                        ],
						'default'     => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
					[
						'name'        => 'logo_title',
						'label'       => __( 'Title', 'essential-addons-elementor' ),
                        'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'   => true,
                        ],
					],
					[
						'name'        => 'link',
						'label'       => __( 'Link', 'essential-addons-elementor' ),
                        'type'        => Controls_Manager::URL,
                        'dynamic'     => [
                            'active'   => true,
                        ],
                        'placeholder' => 'https://www.your-link.com',
                        'default'     => [
                            'url' => '',
                        ],
					],
				],
				'title_field'           => __( 'Logo Image', 'essential-addons-elementor' ),
			]
		);
        
        $this->add_control(
            'title_html_tag',
            [
                'label'                => __( 'Title HTML Tag', 'essential-addons-elementor' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'h4',
                'options'              => [
                    'h1'     => __( 'H1', 'essential-addons-elementor' ),
                    'h2'     => __( 'H2', 'essential-addons-elementor' ),
                    'h3'     => __( 'H3', 'essential-addons-elementor' ),
                    'h4'     => __( 'H4', 'essential-addons-elementor' ),
                    'h5'     => __( 'H5', 'essential-addons-elementor' ),
                    'h6'     => __( 'H6', 'essential-addons-elementor' ),
                    'div'    => __( 'div', 'essential-addons-elementor' ),
                    'span'   => __( 'span', 'essential-addons-elementor' ),
                    'p'      => __( 'p', 'essential-addons-elementor' ),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Carousel Settings
         */
        $this->start_controls_section(
            'section_additional_options',
            [
                'label'                 => __( 'Carousel Settings', 'essential-addons-elementor' ),
            ]
        );
        
        $this->add_control(
            'carousel_effect',
            [
                'label'                 => __( 'Effect', 'essential-addons-elementor' ),
                'description'           => __( 'Sets transition effect', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'slide',
                'options'               => [
                    'slide'     => __( 'Slide', 'essential-addons-elementor' ),
                    'fade'      => __( 'Fade', 'essential-addons-elementor' ),
                    'cube'      => __( 'Cube', 'essential-addons-elementor' ),
                    'coverflow' => __( 'Coverflow', 'essential-addons-elementor' ),
                    'flip'      => __( 'Flip', 'essential-addons-elementor' ),
                ],
            ]
        );
        
        $this->add_responsive_control(
            'items',
            [
                'label'                 => __( 'Visible Items', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 3 ],
                'tablet_default'        => [ 'size' => 2 ],
                'mobile_default'        => [ 'size' => 1 ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 10,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );
        
        $this->add_responsive_control(
            'margin',
            [
                'label'                 => __( 'Items Gap', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 10 ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );
        
        $this->add_control(
            'slider_speed',
            [
                'label'                 => __( 'Slider Speed', 'essential-addons-elementor' ),
                'description'           => __( 'Duration of transition between slides (in ms)', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 400 ],
                'range'                 => [
                    'px' => [
                        'min'   => 100,
                        'max'   => 3000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'                 => __( 'Autoplay', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 2000 ],
                'range'                 => [
                    'px' => [
                        'min'   => 500,
                        'max'   => 5000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'         => [
                    'autoplay'      => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'                 => __( 'Infinite Loop', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'                 => __( 'Grab Cursor', 'essential-addons-elementor' ),
                'description'           => __( 'Shows grab cursor when you hover over the slider', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'          => __( 'Show', 'essential-addons-elementor' ),
                'label_off'         => __( 'Hide', 'essential-addons-elementor' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'navigation_heading',
            [
                'label'                 => __( 'Navigation', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'                 => __( 'Arrows', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'                 => __( 'Dots', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );

        $this->end_controls_section();
        
        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Logos
         */
        $this->start_controls_section(
            'section_logos_style',
            [
                'label'                 => __( 'Logos', 'essential-addons-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'logo_bg',
                'label'                 => __( 'Button Background', 'essential-addons-elementor' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .eael-lc-logo',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'logo_border',
				'label'                 => __( 'Border', 'essential-addons-elementor' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .eael-lc-logo',
			]
		);

		$this->add_control(
			'logo_border_radius',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-lc-logo, {{WRAPPER}} .eael-lc-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-lc-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_logos_style' );

        $this->start_controls_tab(
            'tab_logos_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-elementor' ),
            ]
        );
        
        $this->add_control(
            'grayscale_normal',
            [
                'label'                 => __( 'Grayscale', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'opacity_normal',
            [
                'label'                 => __( 'Opacity', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
                ],
				'selectors'             => [
					'{{WRAPPER}} .eael-logo-carousel img' => 'opacity: {{SIZE}};',
				],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_logos_hover',
            [
                'label'                 => __( 'Hover', 'essential-addons-elementor' ),
            ]
        );
        
        $this->add_control(
            'grayscale_hover',
            [
                'label'                 => __( 'Grayscale', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'opacity_hover',
            [
                'label'                 => __( 'Opacity', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
                ],
				'selectors'             => [
					'{{WRAPPER}} .eael-logo-carousel .swiper-slide:hover img' => 'opacity: {{SIZE}};',
				],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Title
         */
        $this->start_controls_section(
            'section_logo_title_style',
            [
                'label'                 => __( 'Title', 'essential-addons-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'                 => __( 'Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-logo-carousel-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label'                 => __( 'Margin Top', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ 'px' ],
                'range'                 => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-logo-carousel-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'essential-addons-elementor' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .eael-logo-carousel-title',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Arrows
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'                 => __( 'Arrows', 'essential-addons-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrow',
            [
                'label'                 => __( 'Choose Arrow', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => true,
                'default'               => 'fa fa-angle-right',
                'options'               => [
                    'fa fa-angle-right'             => __( 'Angle', 'essential-addons-elementor' ),
                    'fa fa-angle-double-right'      => __( 'Double Angle', 'essential-addons-elementor' ),
                    'fa fa-chevron-right'           => __( 'Chevron', 'essential-addons-elementor' ),
                    'fa fa-chevron-circle-right'    => __( 'Chevron Circle', 'essential-addons-elementor' ),
                    'fa fa-arrow-right'             => __( 'Arrow', 'essential-addons-elementor' ),
                    'fa fa-long-arrow-right'        => __( 'Long Arrow', 'essential-addons-elementor' ),
                    'fa fa-caret-right'             => __( 'Caret', 'essential-addons-elementor' ),
                    'fa fa-caret-square-o-right'    => __( 'Caret Square', 'essential-addons-elementor' ),
                    'fa fa-arrow-circle-right'      => __( 'Arrow Circle', 'essential-addons-elementor' ),
                    'fa fa-arrow-circle-o-right'    => __( 'Arrow Circle O', 'essential-addons-elementor' ),
                    'fa fa-toggle-right'            => __( 'Toggle', 'essential-addons-elementor' ),
                    'fa fa-hand-o-right'            => __( 'Hand', 'essential-addons-elementor' ),
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_size',
            [
                'label'                 => __( 'Arrows Size', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '22' ],
                'range'                 => [
                    'px' => [
                        'min'   => 15,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'left_arrow_position',
            [
                'label'                 => __( 'Align Left Arrow', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'                 => __( 'Align Right Arrow', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'                 => __( 'Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'arrows_border_normal',
				'label'                 => __( 'Border', 'essential-addons-elementor' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label'                 => __( 'Hover', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'                 => __( 'Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Dots', 'essential-addons-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'dots'      => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'                 => __( 'Position', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'inside'     => __( 'Inside', 'essential-addons-elementor' ),
                   'outside'    => __( 'Outside', 'essential-addons-elementor' ),
                ],
                'default'               => 'outside',
            ]
        );
        
        $this->add_responsive_control(
            'dots_size',
            [
                'label'                 => __( 'Size', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 2,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_spacing',
            [
                'label'                 => __( 'Spacing', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'                 => __( 'Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_dot_color_normal',
            [
                'label'                 => __( 'Active Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'dots_border_normal',
				'label'                 => __( 'Border', 'essential-addons-elementor' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
			]
		);

		$this->add_control(
			'dots_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
                'allowed_dimensions'    => 'vertical',
				'placeholder'           => [
					'top'      => '',
					'right'    => 'auto',
					'bottom'   => '',
					'left'     => 'auto',
				],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'                 => __( 'Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render logo carousel widget output on the frontend.
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'logo-carousel-wrap', 'class', 'swiper-container-wrap eael-logo-carousel-wrap' );
        
        $this->add_render_attribute( 'logo-carousel', 'class', 'swiper-container eael-logo-carousel' );
        $this->add_render_attribute( 'logo-carousel', 'class', 'swiper-container-'.esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'logo-carousel', 'data-pagination', '.swiper-pagination-'.esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'logo-carousel', 'data-arrow-next', '.swiper-button-next-'.esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'logo-carousel', 'data-arrow-prev', '.swiper-button-prev-'.esc_attr( $this->get_id() ) );

        if ( $settings['dots_position'] ) {
            $this->add_render_attribute( 'logo-carousel-wrap', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
        }

        if ( $settings['grayscale_normal'] == 'yes' ) {
            $this->add_render_attribute( 'logo-carousel', 'class', 'grayscale-normal' );
        }

        if ( $settings['grayscale_hover'] == 'yes' ) {
            $this->add_render_attribute( 'logo-carousel', 'class', 'grayscale-hover' );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'logo-carousel-wrap' ); ?>>
            <div
                 <?php echo $this->get_render_attribute_string( 'logo-carousel' ); ?>
                 <?php
                    if ( ! empty( $settings['items']['size'] ) ) {
                        echo 'data-items="' . $settings['items']['size'] . '"';
                    }
                    if ( ! empty( $settings['items_tablet']['size'] ) ) {
                        echo 'data-items-tablet="' . $settings['items_tablet']['size'] . '"';
                    }
                    if ( ! empty( $settings['items_mobile']['size'] ) ) {
                        echo 'data-items-mobile="' . $settings['items_mobile']['size'] . '"';
                    }
                    if ( ! empty( $settings['margin']['size'] ) ) {
                        echo 'data-margin="' . $settings['margin']['size'] . '"';
                    }
                    if ( ! empty( $settings['margin_tablet']['size'] ) ) {
                        echo 'data-margin-tablet="' . $settings['margin_tablet']['size'] . '"';
                    }
                    if ( ! empty( $settings['margin_mobile']['size'] ) ) {
                        echo 'data-margin-mobile="' . $settings['margin_mobile']['size'] . '"';
                    }
                    if ( $settings['carousel_effect'] ) {
                        echo 'data-effect="' . $settings['carousel_effect'] . '"';
                    }
                    if ( ! empty( $settings['slider_speed']['size'] ) ) {
                        echo 'data-speed="' . $settings['slider_speed']['size'] . '"';
                    }
                    if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed']['size'] ) ) {
                        echo 'data-autoplay="' . $settings['autoplay_speed']['size'] . '"';
                    } else {
                        echo 'data-autoplay="0"';
                    }
                    if ( $settings['infinite_loop'] == 'yes' ) {
                        echo 'data-loop="1"';
                    }
                    if ( $settings['grab_cursor'] == 'yes' ) {
                        echo 'data-grab-cursor="1"';
                    }
                    if ( $settings['arrows'] == 'yes' ) {
                        echo 'data-arrows="1"';
                    }
                    if ( $settings['dots'] == 'yes' ) {
                        echo 'data-dots="1"';
                    }
                 ?>
                 >
                <div class="swiper-wrapper">
                <?php
                    $i = 1;
                    foreach ( $settings['carousel_slides'] as $index => $item ) :
                        if ( $item['logo_carousel_slide'] ) : ?>
                            <div class="swiper-slide">
                                <div class="eael-lc-logo-wrap">
                                    <div class="eael-lc-logo">
                                        <?php
                                            if ( ! empty( $item['logo_carousel_slide']['url'] ) ) {

                                                if ( ! empty( $item['link']['url'] ) ) {

                                                    $this->add_render_attribute( 'logo-link' . $i, 'href', $item['link']['url'] );

                                                    if ( $item['link']['is_external'] ) {
                                                        $this->add_render_attribute( 'logo-link' . $i, 'target', '_blank' );
                                                    }

                                                    if ( $item['link']['nofollow'] ) {
                                                        $this->add_render_attribute( 'logo-link' . $i, 'rel', 'nofollow' );
                                                    }
                                                }

                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
                                                }

                                                echo '<img src="' . esc_url( $item['logo_carousel_slide']['url'] ) . '">';

                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '</a>';
                                                }
                                            }
                                        ?>
                                    </div>
                                    <?php
                                        if ( ! empty( $item['logo_title'] ) ) {
                                            printf( '<%1$s class="eael-logo-carousel-title">', $settings['title_html_tag'] );
                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
                                                }
                                                echo $item['logo_title'];
                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '</a>';
                                                }
                                            printf( '</%1$s>', $settings['title_html_tag'] );
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        endif;
                        $i++;
                    endforeach;
                ?>
                </div>
            </div>
            <?php
                $this->render_dots();

                $this->render_arrows();
            ?>
        </div>
        <?php
    }

    /**
	 * Render logo carousel dots output on the frontend.
	 */
    protected function render_dots() {
        $settings = $this->get_settings_for_display();

        if ( $settings['dots'] == 'yes' ) { ?>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
        <?php }
    }

    /**
	 * Render logo carousel arrows output on the frontend.
	 */
    protected function render_arrows() {
        $settings = $this->get_settings_for_display();

        if ( $settings['arrows'] == 'yes' ) { ?>
            <?php
                if ( $settings['arrow'] ) {
                    $pa_next_arrow = $settings['arrow'];
                    $pa_prev_arrow = str_replace("right","left",$settings['arrow']);
                }
                else {
                    $pa_next_arrow = 'fa fa-angle-right';
                    $pa_prev_arrow = 'fa fa-angle-left';
                }
            ?>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
            </div>
            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
            </div>
        <?php }
    }

    /**
	 * Render logo carousel dots widget output in the editor.
	 */
    protected function _dots_template() {
        ?>
        <# if ( settings.dots == 'yes' ) { #>
            <div class="swiper-pagination"></div>
        <# } #>
        <?php
    }

    /**
	 * Render logo carousel arrows widget output in the editor.
	 */
    protected function _arrows_template() {
        ?>
        <# if ( settings.arrows == 'yes' ) { #>
            <#
                if ( settings.arrow != '' ) {
                    var eael_next_arrow = settings.arrow;
                    var eael_prev_arrow = eael_next_arrow.replace('right', "left");
                }
                else {
                    var eael_next_arrow = 'fa fa-angle-right';
                    var eael_prev_arrow = 'fa fa-angle-left';
                }
            #>
            <div class="swiper-button-next">
                <i class="{{ eael_next_arrow }}"></i>
            </div>
            <div class="swiper-button-prev">
                <i class="{{ eael_prev_arrow }}"></i>
            </div>
        <# } #>
        <?php
    }

    /**
	 * Render logo carousel widget output in the editor.
	 */
    protected function _content_template() {
        ?>
        <#
            var i = 1;

            var grayscale_normal = ( settings.grayscale_normal == 'yes' ) ? 'grayscale-normal' : '';
            var grayscale_hover = ( settings.grayscale_hover == 'yes' ) ? 'grayscale-hover' : '';
           
            var items           = ( settings.items.size != '' ) ? settings.items.size : '3';
            var items_tablet    = ( settings.items_tablet.size != '' ) ? settings.items_tablet.size : '2';
            var items_mobile    = ( settings.items_mobile.size != '' ) ? settings.items_mobile.size : '1';
            var margin          = ( settings.margin.size != '' ) ? settings.margin.size : '';
            var margin_tablet   = ( settings.margin_tablet.size != '' ) ? settings.margin_tablet.size : '';
            var margin_mobile   = ( settings.margin_mobile.size != '' ) ? settings.margin_mobile.size : '';
            var loop            = ( settings.infinite_loop == 'yes' ) ? '1' : '';
            var carousel_effect = ( settings.carousel_effect ) ? settings.carousel_effect : '';
            var grab_cursor     = ( settings.grab_cursor == 'yes' ) ? settings.grab_cursor : '';
            var arrows          = ( settings.arrows == 'yes' ) ? settings.arrows : '';
            var dots            = ( settings.dots == 'yes' ) ? settings.dots : '';
        #>
        <div class="swiper-container-wrap eael-logo-carousel-wrap swiper-container-wrap-dots-{{ settings.dots_position }}">
            <div class="swiper-container eael-logo-carousel {{ grayscale_normal }} {{ grayscale_hover }}" data-items="{{ items }}" data-items-tablet="{{ items_tablet }}" data-items-mobile="{{ items_mobile }}" data-margin="{{ margin }}" data-margin-tablet="{{ margin_tablet }}" data-margin-mobile="{{ margin_mobile }}" data-effect="{{ carousel_effect }}" data-grab-cursor="{{ grab_cursor }}" data-loop="{{ loop }}" data-arrows="{{ arrows }}" data-dots="{{ dots }}">
                <div class="swiper-wrapper">
                    <# _.each( settings.carousel_slides, function( item ) { #>
                        <# if ( item.logo_carousel_slide ) { #>
                            <div class="swiper-slide">
                                <div class="eael-lc-logo-wrap">
                                    <div class="eael-lc-logo">
                                        <# if ( item.logo_carousel_slide.url != '' ) { #>
                                            <# if ( item.link && item.link.url ) { #>
                                                <a href="{{ item.link.url }}">
                                            <# } #>

                                            <img src="{{ item.logo_carousel_slide.url }}">

                                            <# if ( item.link && item.link.url ) { #>
                                                </a>
                                            <# } #>
                                        <# } #>
                                    </div>
                                    <# if ( item.title != '' ) { #>
                                        <{{ settings.title_html_tag }} class="eael-logo-grid-title">
                                            <# if ( item.link && item.link.url ) { #>
                                                <a href="{{ item.link.url }}">
                                            <# } #>
                                                {{ item.title }}
                                            <# if ( item.link && item.link.url ) { #>
                                                </a>
                                            <# } #>
                                        </{{ settings.title_html_tag }}>
                                    <# } #>
                                </div>
                            </div>
                        <# } #>
                    <# i++ } ); #>
                </div>
            </div>
            <?php $this->_dots_template(); ?>
            <?php $this->_arrows_template(); ?>
        </div>
        <?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Logo_Carousel() );