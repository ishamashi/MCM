<?php

namespace ElementorExtras\Extensions;

use ElementorExtras\Base\Extension_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Sticky Extension
 *
 * Adds sticky on scroll capability to widgets and sections
 *
 * @since 0.1.0
 */
class Extension_Sticky_Elements extends Extension_Base {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 1.8.0
	 * @access private
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 1.8.0
	 **/
	public function get_script_depends() {
		return [
			'sticky-element',
		];
	}

	/**
	 * The description of the current extension
	 *
	 * @since 1.8.0
	 **/
	public static function get_description() {

		$message = '';

		if ( is_elementor_pro_active() ) {
			$message = '<div class="notice notice-warning inline"><p>';
			$message .= __( '<strong>IMPORTANT:</strong> Enabling this extension disables the default Elementor Pro sticky options.', 'elementor-extras' );
			$message .= '</p></div>';
		}

		$message .= __( 'Adds an option to make any widget or section sticky when scrolling to it\'s position. Can be found under Advanced &rarr; Extras &rarr; Sticky.', 'elementor-extras' );

		return $message;
	}

	/**
	 * Add common sections
	 *
	 * @since 1.8.0
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {

		// Activate sections for widgets
		add_action( 'elementor/element/common/section_custom_css/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for sections
		add_action( 'elementor/element/section/section_custom_css/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

	}

	/**
	 * Add Controls
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$sticky_control_args = [ 'name' => 'sticky', 'render_type' ];

		if ( $element->get_name() === 'section' ) {
			
			$element->add_control(
				'sticky_warning',
				[
					'type' 					=> Controls_Manager::RAW_HTML,
					'raw' 					=> __( 'You cannot make this section sticky if the "Stretch Section" is enabled. To make it work, use a section within a section, make the outer section stretched and the inner section sticky.', 'elementor-extras' ),
					'content_classes' 		=> 'ee-raw-html ee-raw-html__danger',
					'separator'				=> 'before',
					'condition'				=> [
						'stretch_section' 	=> 'section-stretched',
					]
				]
			);
		}

		$element->add_control( 'sticky_enable', [
			'label'			=> _x( 'Sticky', 'Sticky Control', 'elementor-extras' ),
			'description'	=> __( 'Make sure "Content Position" is set to "Default" for any parent sections of this element.', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SWITCHER,
			'default' 		=> '',
			'label_on' 		=> __( 'Yes', 'elementor-extras' ),
			'label_off' 	=> __( 'No', 'elementor-extras' ),
			'return_value' 	=> 'yes',
			'frontend_available'	=> true,
		]);

		$element->add_control( 'sticky_parent', [
			'label'			=> _x( 'Stick in', 'Sticky Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'options'		=> [
				'' 	=> __( 'Parent', 'elementor-extras' ),
				'body' 		=> __( 'Body', 'elementor-extras' ),
				'custom' 	=> __( 'Custom', 'elementor-extras' ),
			],
			'condition' => [
				'sticky_enable!' => '',
			],
			'default' 		=> '',
			'frontend_available'	=> true,
		]);

		$element->add_control( 'sticky_bottoming', [
			'label'			=> _x( 'Bottoming', 'Sticky Control', 'elementor-extras' ),
			'description'	=> __( 'Disable this if you don\'t want the element to stop sticking to the viewport when it hits the bottom of the parent.', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SWITCHER,
			'default' 		=> 'yes',
			'label_on' 		=> __( 'Yes', 'elementor-extras' ),
			'label_off' 	=> __( 'No', 'elementor-extras' ),
			'return_value' 	=> 'yes',
			'condition' => [
				'sticky_enable!' => '',
			],
			'frontend_available'	=> true,
		]);

		$element->add_control( 'sticky_unstick_on', [
			'label' 	=> _x( 'Unstick on', 'Sticky Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SELECT,
			'default' 	=> 'mobile',
			'options' 			=> [
				'none' 		=> __( 'None', 'elementor-extras' ),
				'tablet' 	=> __( 'Mobile and tablet', 'elementor-extras' ),
				'mobile' 	=> __( 'Mobile only', 'elementor-extras' ),
			],
			'condition' => [
				'sticky_enable!' => '',
			],
			'frontend_available' => true,
		]);

		$element->add_control( 'sticky_parent_selector', [
			'label'			=> _x( 'Parent Selector', 'Sticky Control', 'elementor-extras' ),
			'description'	=> __( 'CSS selector for the parent', 'elementor-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> '',
			'frontend_available'	=> true,
			'condition' 	=> [
				'sticky_parent' 	=> 'custom'
			]
		]);

		$element->add_control( 'sticky_offset', [
			'label' 	=> _x( 'Offset Top', 'Sticky Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SLIDER,
			'range' 	=> [
				'px' 	=> [
					'max' => 100,
				],
			],
			'default' 	=> [
				'size' 	=> 0,
			],
			'condition'		=> [
	        	'sticky_enable!' => '',
	        ],
	        'frontend_available' => true,
		]);

	}

	/**
	 * Remove elementor sticky controls
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	protected function remove_elementor_sticky( $element ) {

		$this->remove_controls( $element, [
			'sticky',
			'sticky_effects_offset',
			'sticky_on',
			'sticky_offset',
			'sticky_parent',
			// 'section_scrolling_effect',
		] );
	}

	protected function add_elementor_sticky_warning( $element ) {

		$element->add_control(
			'sticky_elementor_warning',
			[
				'type' 					=> Controls_Manager::RAW_HTML,
				'raw' 					=> __( 'Elementor Extras: To use the default Sticky options in Elementor, disable the Extras "Sticky Elements" extension under Elementor > Extras > Extensions.', 'elementor-extras' ),
				'content_classes' 		=> 'ee-raw-html ee-raw-html__info',
				'separator'				=> 'before',
			]
		);

	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	protected function add_actions() {

		// Activate controls for widgets
		add_action( 'elementor/element/common/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->remove_elementor_sticky( $element );
			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate controls for sections
		add_action( 'elementor/element/section/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->remove_elementor_sticky( $element );
			$this->add_controls( $element, $args );

		}, 10, 2 );

		add_action( 'elementor/element/common/section_scrolling_effect/after_section_start', function( $element, $args ) {

			$this->add_elementor_sticky_warning( $element );

		}, 10, 2 );

		add_action( 'elementor/element/section/section_scrolling_effect/after_section_start', function( $element, $args ) {

			$this->add_elementor_sticky_warning( $element );

		}, 10, 2 );
	}

}