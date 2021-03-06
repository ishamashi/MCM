<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Mailchimp extends Widget_Base {

	public function get_name() {
		return 'eael-mailchimp';
	}

	public function get_title() {
		return esc_html__( 'EA Mailchimp', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-envelope-open';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

  		/**
  		 * Mailchimp API Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_mailchimp_api_settings',
  			[
  				'label' => esc_html__( 'Mailchimp Account Settings', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
		  	'eael_mailchimp_lists',
		  	[
		   		'label'       	=> esc_html__( 'Mailchimp List', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'label_block' 	=> false,
		     	'description' 	=> 'Set your API Key from <strong>Elementor &gt; Essential Addons &gt; Mailchimp Settings</strong>',
		     	'options' 		=> eael_mailchimp_lists(),
		  	]
		);
  		$this->end_controls_section();
  		/**
  		 * Mailchimp Fields Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_mailchimp_field_settings',
  			[
  				'label' => esc_html__( 'Field Settings', 'essential-addons-elementor' )
  			]
  		);
  		$this->add_control(
			'eael_mailchimp_email_label_text',
			[
				'label' => esc_html__( 'Email Label', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 'Email',
			]
		);
  		$this->add_control(
			'eael_mailchimp_fname_show',
			[
				'label' => esc_html__( 'Enable First Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_mailchimp_fname_label_text',
			[
				'label' => esc_html__( 'First Name Label', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 'First Name',
				'condition' => [
					'eael_mailchimp_fname_show' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_mailchimp_lname_show',
			[
				'label' => esc_html__( 'Enable Last Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_mailchimp_lname_label_text',
			[
				'label' => esc_html__( 'Last Name Label', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 'Last Name',
				'condition' => [
					'eael_mailchimp_lname_show' => 'yes'
				]
			]
		);
  		$this->end_controls_section();

  		/**
  		 * Mailchimp Button Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_mailchimp_button_settings',
  			[
  				'label' => esc_html__( 'Button Settings', 'essential-addons-elementor' )
  			]
  		);
  		$this->add_control(
			'eael_section_mailchimp_button_text',
			[
				'label' => esc_html__( 'Button Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'Subscribe', 'essential-addons-elementor' ),
			]
		);
		$this->add_control(
			'eael_section_mailchimp_loading_text',
			[
				'label' => esc_html__( 'Loading Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'Submitting...', 'essential-addons-elementor' ),
			]
		);
  		$this->end_controls_section();

  		/**
  		 * Mailchimp Message Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_mailchimp_message_settings',
  			[
  				'label' => esc_html__( 'Message Settings', 'essential-addons-elementor' )
  			]
  		);
  		$this->add_control(
			'eael_section_mailchimp_success_text',
			[
				'label' => esc_html__( 'Success Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'You have subscribed successfully!', 'essential-addons-elementor' ),
			]
		);
  		$this->end_controls_section();
  		/**
		 * -------------------------------------------
		 * Tab Style Mailchimp Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_mailchimp_style_settings',
			[
				'label' => esc_html__( 'General Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
            'eael_mailchimp_layout',
            [
                'label' => __( 'Layout', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inline' => 'Inline',
                    'stacked' => 'Stacked'
                ],
                'default' => 'stacked',

            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'eael_mailchimp_box_bg',
                'label'                 => __( 'Background', 'essential-addons-elementor' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .eael-mailchimp-wrap',
            ]
        );
		$this->add_responsive_control(
			'eael_mailchimp_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_mailchimp_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_mailchimp_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-mailchimp-wrap',
			]
		);
		$this->add_responsive_control(
			'eael_mailchimp_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_mailchimp_box_shadow',
				'selector' => '{{WRAPPER}} .eael-mailchimp-wrap',
			]
		);
  		$this->end_controls_section();

  		/**
  		 * Tab Style: Form Fields Style
  		 */
  		$this->start_controls_section(
			'eael_section_contact_form_field_styles',
			[
				'label' => esc_html__( 'Form Fields Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		$this->add_control(
			'eael_mailchimp_input_background',
			[
				'label' => esc_html__( 'Input Field Background', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-input' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
  			'eael_mailchimp_input_width',
  			[
  				'label' => esc_html__( 'Input Width', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-field-group' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
  		$this->add_responsive_control(
  			'eael_mailchimp_input_height',
  			[
  				'label' => esc_html__( 'Input Height', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-input' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
  		$this->add_responsive_control(
			'eael_mailchimp_input_padding',
			[
				'label' => esc_html__( 'Fields Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
  		$this->add_responsive_control(
			'eael_mailchimp_input_margin',
			[
				'label' => esc_html__( 'Fields Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-field-group' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'eael_mailchimp_input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_mailchimp_input_border',
				'selector' => '{{WRAPPER}} .eael-mailchimp-input',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_mailchimp_input_box_shadow',
				'selector' => '{{WRAPPER}} .eael-mailchimp-input',
			]
		);
  		$this->end_controls_section();

  		/**
  		 * Tab Style: Form Field Color & Typography
  		 */
  		$this->start_controls_section(
			'eael_section_mailchimp_typography',
			[
				'label' => esc_html__( 'Color & Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		$this->add_control(
			'eael_mailchimp_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-wrap label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_mailchimp_field_color',
			[
				'label' => esc_html__( 'Field Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-input' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_mailchimp_field_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-wrap ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-mailchimp-wrap ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-mailchimp-wrap ::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_mailchimp_label_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Label Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_mailchimp_label_typography',
				'selector' => '{{WRAPPER}} .eael-mailchimp-wrap label',
			]
		);
		$this->add_control(
			'eael_mailchimp_heading_input_field',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Input Fields Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_mailchimp_input_field_typography',
				'selector' => '{{WRAPPER}} .eael-mailchimp-input',
			]
		);
  		$this->end_controls_section();

  		/**
  		 * Subscribe Button Style
  		 */
  		$this->start_controls_section(
            'eael_section_subscribe_btn',
            [
                'label' => __( 'Subscribe Button Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
            'eael_mailchimp_subscribe_btn_display',
            [
                'label' => __( 'Button Display', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inline' => 'Inline',
                    'block' => 'Block'
                ],
                'default' => 'inline',

            ]
        );
		$this->add_responsive_control(
  			'eael_mailchimp_subscribe_btn_width',
  			[
  				'label' => esc_html__( 'Button Max Width', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
					'em' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-submit-btn' => 'max-width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		$this->add_responsive_control(
			'eael_mailchimp_subscribe_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-subscribe' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_mailchimp_subscribe_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-subscribe' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
	         'name' => 'eael_mailchimp_subscribe_btn_typography',
				'selector' => '{{WRAPPER}} .eael-mailchimp-subscribe',
			]
		);

		$this->start_controls_tabs( 'eael_mailchimp_subscribe_btn_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_mailchimp_subscribe_btn_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_mailchimp_subscribe_btn_normal_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors' => [
						'{{WRAPPER}} .eael-mailchimp-subscribe' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_mailchimp_subscribe_btn_normal_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#29d8d8',
					'selectors' => [
						'{{WRAPPER}} .eael-mailchimp-subscribe' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'eael_mailchimp_subscribe_btn_normal_border',
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-mailchimp-subscribe',
				]
			);

			$this->add_control(
				'eael_mailchimp_subscribe_btn_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .eael-mailchimp-subscribe' => 'border-radius: {{SIZE}}px;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'eael_mailchimp_subscribe_btn_shadow',
					'selector' => '{{WRAPPER}} .eael-mailchimp-subscribe',
					'separator' => 'before'
				]
			);
			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_mailchimp_subscribe_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_mailchimp_subscribe_btn_hover_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors' => [
						'{{WRAPPER}} .eael-mailchimp-subscribe:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_mailchimp_subscribe_btn_hover_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#27bdbd',
					'selectors' => [
						'{{WRAPPER}} .eael-mailchimp-subscribe:hover' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_mailchimp_subscribe_btn_hover_border_color',
				[
					'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .eael-mailchimp-subscribe:hover' => 'border-color: {{VALUE}};',
					],
				]

			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'eael_mailchimp_subscribe_btn_hover_shadow',
					'selector' => '{{WRAPPER}} .eael-mailchimp-subscribe:hover',
					'separator' => 'before'
				]
			);
			$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

  		/**
  		 * Subscribe Button Style
  		 */
  		$this->start_controls_section(
            'eael_section_success_message',
            [
                'label' => __( 'Message Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'eael_mailchimp_message_background',
			[
				'label' => esc_html__( 'Background', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-message' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_mailchimp_message_color',
			[
				'label' => esc_html__( 'Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mailchimp-message' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'eael_mailchimp_message_alignment',
			[
				'label' => esc_html__( 'Text Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'default' => [
						'title' => __( 'Default', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					],
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'default',
				'prefix_class' => 'eael-mailchimp-message-text-',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_mailchimp_message_typography',
				'selector' => '{{WRAPPER}} .eael-mailchimp-message',
			]
		);
		$this->add_responsive_control(
			'eael_mailchimp_message_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_mailchimp_message_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_mailchimp_message_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-mailchimp-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_mailchimp_message_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-mailchimp-message',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_mailchimp_message_box_shadow',
				'selector' => '{{WRAPPER}} .eael-mailchimp-message',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

   		$settings = $this->get_settings();
   		$api_key = get_option('eael_save_mailchimp_api');
   		$list_id = $settings['eael_mailchimp_lists'];
   		$button_text = $settings['eael_section_mailchimp_button_text'];
   		$success_text = $settings['eael_section_mailchimp_success_text'];
   		$loading_text = $settings['eael_section_mailchimp_loading_text'];
   		// Layout Class
   		if( 'stacked' === $settings['eael_mailchimp_layout'] ) {
   			$layout = 'eael-mailchimp-stacked';
   		} elseif( 'inline' === $settings['eael_mailchimp_layout'] ) {
   			$layout = 'eael-mailchimp-inline';
   		}		
   		// Button Display Class
   		if( 'block' === $settings['eael_mailchimp_subscribe_btn_display'] ) {
   			$subscribe_btn_display = 'eael-mailchimp-btn-block';
   		} elseif( 'inline' === $settings['eael_mailchimp_subscribe_btn_display'] ) {
   			$subscribe_btn_display = 'eael-mailchimp-btn-inline';
   		}
	?>
	<?php if( !empty( $api_key ) ) : ?>
	<div class="eael-mailchimp-wrap <?php echo esc_attr($layout); ?>">
		<form action="#" id="eael-mailchimp-form-<?php echo esc_attr( $this->get_id() ); ?>">
			<div class="eael-form-fields-wrapper eael-mailchimp-fields-wrapper <?php echo esc_attr($subscribe_btn_display); ?>">
				<div class="eael-field-group eael-mailchimp-email">
					<label for="<?php echo esc_attr($settings['eael_mailchimp_email_label_text'], 'essential-addons-elementor'); ?>"><?php echo esc_html__($settings['eael_mailchimp_email_label_text'], 'essential-addons-elementor'); ?></label>
					<input type="email" name="eael_mailchimp_email" class="eael-mailchimp-input" placeholder="Email" required="required">
				</div>
				<?php if( 'yes' == $settings['eael_mailchimp_fname_show'] ) : ?>
					<div class="eael-field-group eael-mailchimp-fname">
						<label for="<?php echo esc_attr($settings['eael_mailchimp_fname_label_text'], 'essential-addons-elementor'); ?>"><?php echo esc_html__($settings['eael_mailchimp_fname_label_text'], 'essential-addons-elementor'); ?></label>
						<input type="text" name="eael_mailchimp_firstname" class="eael-mailchimp-input" placeholder="First Name">
					</div>
				<?php endif; ?>
				<?php if( 'yes' == $settings['eael_mailchimp_lname_show'] ) : ?>
					<div class="eael-field-group eael-mailchimp-lname">
						<label for="<?php echo esc_attr($settings['eael_mailchimp_lname_label_text'], 'essential-addons-elementor'); ?>"><?php echo esc_html__($settings['eael_mailchimp_lname_label_text'], 'essential-addons-elementor'); ?></label>
						<input type="text" name="eael_mailchimp_lastname" class="eael-mailchimp-input" placeholder="Last Name">
					</div>
				<?php endif; ?>
				<div class="eael-field-group eael-mailchimp-submit-btn">
					<button id="eael-subscribe-<?php echo esc_attr( $this->get_id() ); ?>" class="eael-load-more-button eael-mailchimp-subscribe">
						<div class="eael-btn-loader button__loader"></div>
				  		<span><?php echo esc_html__( $settings['eael_section_mailchimp_button_text'], 'essential-addons-elementor' ); ?></span>
					</button>
				</div>
			</div>
		</form>
	</div>
	<?php else: ?>
		<p class="eael-mailchimp-error">Please insert your api key</p>
	<?php endif; ?>

	<script>
		jQuery(document).ready(function($) {
			eael_mailchimp_subscribe( 'eael-mailchimp-form-<?php echo esc_attr( $this->get_id() ); ?>', '<?php echo $api_key; ?>', '<?php echo $list_id; ?>', '<?php echo $button_text; ?>', '<?php echo $success_text; ?>', '<?php echo $loading_text; ?>' );
		});
	</script>
	<?php
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Mailchimp() );