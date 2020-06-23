<?php
/**
 * UAEL WooCommerce Add To Cart Button.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Woo_Categories.
 */
class Woo_Categories extends Common_Widget {

	/**
	 * Retrieve Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'Woo_Categories' );
	}

	/**
	 * Retrieve Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Woo_Categories' );
	}

	/**
	 * Retrieve Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Woo_Categories' );
	}

	/**
	 * Register controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		/* Product Control */
		$this->register_content_general_controls();
		$this->register_content_filter_controls();

		/* Style */
		$this->register_style_layout_controls();
		$this->register_style_category_controls();
		$this->register_style_category_desc_controls();
		$this->register_helpful_information();
	}

	/**
	 * Register Woo Products General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_content_general_controls() {

		$this->start_controls_section(
			'section_general_field',
			[
				'label' => __( 'General', 'uael' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
			$this->add_responsive_control(
				'cat_columns',
				[
					'label'          => __( 'Columns', 'uael' ),
					'type'           => Controls_Manager::SELECT,
					'default'        => '4',
					'tablet_default' => '3',
					'mobile_default' => '2',
					'options'        => [
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					],
				]
			);

			$this->add_control(
				'cats_count',
				[
					'label'   => __( 'Categories Count', 'uael' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => '4',
				]
			);
		$this->end_controls_section();
	}

	/**
	 * Register Woo Products Filter Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_content_filter_controls() {

		$this->start_controls_section(
			'section_filter_field',
			[
				'label' => __( 'Filters', 'uael' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'category_filter_rule',
				[
					'label'   => __( 'Category Filter Rule', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'all',
					'options' => [
						'all'     => __( 'Show All', 'uael' ),
						'top'     => __( 'Only Top Level', 'uael' ),
						'include' => __( 'Match These Categories', 'uael' ),
						'exclude' => __( 'Exclude These Categories', 'uael' ),
					],
				]
			);
			$this->add_control(
				'category_filter',
				[
					'label'     => __( 'Category Filter', 'uael' ),
					'type'      => Controls_Manager::SELECT2,
					'multiple'  => true,
					'default'   => '',
					'options'   => $this->get_product_categories(),
					'condition' => [
						'category_filter_rule' => [ 'include', 'exclude' ],
					],
				]
			);
			$this->add_control(
				'display_cat_desc',
				[
					'label'        => __( 'Display Category Description', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => 'Yes',
					'label_off'    => 'No',
					'return_value' => 'yes',
				]
			);
			$this->add_control(
				'display_empty_cat',
				[
					'label'        => __( 'Display Empty Categories', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => 'Yes',
					'label_off'    => 'No',
					'return_value' => 'yes',
				]
			);
			$this->add_control(
				'orderby',
				[
					'label'   => __( 'Order by', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'name',
					'options' => [
						'name'  => __( 'Name', 'uael' ),
						'slug'  => __( 'Slug', 'uael' ),
						'desc'  => __( 'Description', 'uael' ),
						'count' => __( 'Count', 'uael' ),
					],
				]
			);

			$this->add_control(
				'order',
				[
					'label'   => __( 'Order', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'desc',
					'options' => [
						'desc' => __( 'Descending', 'uael' ),
						'asc'  => __( 'Ascending', 'uael' ),
					],
				]
			);
		$this->end_controls_section();
	}

	/**
	 * Style Tab
	 */
	/**
	 * Register Layout Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_style_layout_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => __( 'Layout', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'     => __( 'Columns Gap', 'uael' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .uael-woo-categories li.product' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .uael-woo-categories ul.products' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => __( 'Rows Gap', 'uael' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 35,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .uael-woo-categories li.product' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Category Content Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_style_category_controls() {
		$this->start_controls_section(
			'section_design_cat_content',
			[
				'label' => __( 'Category Content', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_control(
				'cat_content_alignment',
				[
					'label'        => __( 'Alignment', 'uael' ),
					'type'         => Controls_Manager::CHOOSE,
					'label_block'  => false,
					'options'      => [
						'left'   => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'default'      => 'center',
					'prefix_class' => 'uael-woo-cat--align-',
					'separator'    => 'after',
				]
			);

			$this->start_controls_tabs( 'cat_content_tabs_style' );

				$this->start_controls_tab(
					'cat_content_normal',
					[
						'label' => __( 'Normal', 'uael' ),
					]
				);

					$this->add_control(
						'cat_content_color',
						[
							'label'     => __( 'Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories li.product .woocommerce-loop-category__title, {{WRAPPER}} .uael-woo-categories li.product .uael-category__title-wrap .uael-count' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'cat_content_background_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories li.product .uael-category__title-wrap' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'cat_content_hover',
					[
						'label' => __( 'Hover', 'uael' ),
					]
				);

					$this->add_control(
						'cat_content_hover_color',
						[
							'label'     => __( 'Text Hover Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories li.product-category > a:hover .woocommerce-loop-category__title, {{WRAPPER}} .uael-woo-categories li.product-category > a:hover .uael-category__title-wrap .uael-count' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'cat_content_background_hover_color',
						[
							'label'     => __( 'Background Hover Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories li.product-category > a:hover .uael-category__title-wrap' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'cat_content_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'default'    => [
						'top'      => '10',
						'right'    => '',
						'bottom'   => '10',
						'left'     => '',
						'isLinked' => false,
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-woo-categories li.product .uael-category__title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'  => 'before',
				]
			);

			$this->add_control(
				'cat_content_typography',
				[
					'label'     => __( 'Typography', 'uael' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'cat_content_title_typography',
					'label'    => __( 'Title', 'uael' ),
					'selector' => '{{WRAPPER}} .uael-woo-categories li.product .woocommerce-loop-category__title',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'cat_content_count_typography',
					'label'     => __( 'Count', 'uael' ),
					'selector'  => '{{WRAPPER}} .uael-woo-categories li.product .uael-category__title-wrap .uael-count',
					'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
					'separator' => 'after',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Category Description Content Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_style_category_desc_controls() {

		$this->start_controls_section(
			'section_design_cat_desc',
			[
				'label'     => __( 'Category Description', 'uael' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_cat_desc' => 'yes',
				],
			]
		);
			$this->add_control(
				'cat_desc_alignment',
				[
					'label'       => __( 'Alignment', 'uael' ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => false,
					'options'     => [
						'left'   => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'default'     => 'left',
					'selectors'   => [
						'{{WRAPPER}} .uael-woo-categories .uael-term-description' => 'text-align: {{VALUE}};',
					],
					'condition'   => [
						'display_cat_desc' => 'yes',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'cat_desc_typography',
					'label'     => '',
					'selector'  => '{{WRAPPER}} .uael-woo-categories .uael-term-description',
					'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
					'condition' => [
						'display_cat_desc' => 'yes',
					],
				]
			);

			$this->start_controls_tabs( 'desc_tabs_style' );

				$this->start_controls_tab(
					'desc_normal',
					[
						'label'     => __( 'Normal', 'uael' ),
						'condition' => [
							'display_cat_desc' => 'yes',
						],
					]
				);
					$this->add_control(
						'cat_desc_color',
						[
							'label'     => __( 'Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_3,
							],
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories .uael-term-description' => 'color: {{VALUE}};',
							],
							'condition' => [
								'display_cat_desc' => 'yes',
							],
						]
					);
					$this->add_control(
						'cat_desc_background_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories .uael-product-cat-desc' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'display_cat_desc' => 'yes',
							],
						]
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'desc_hover',
					[
						'label'     => __( 'Hover', 'uael' ),
						'condition' => [
							'display_cat_desc' => 'yes',
						],
					]
				);
					$this->add_control(
						'cat_desc_hover_color',
						[
							'label'     => __( 'Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_3,
							],
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories li.product-category > a:hover .uael-term-description' => 'color: {{VALUE}};',
							],
							'condition' => [
								'display_cat_desc' => 'yes',
							],
						]
					);
					$this->add_control(
						'cat_desc_background__hover_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-woo-categories li.product-category > a:hover .uael-product-cat-desc' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'display_cat_desc' => 'yes',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'cat_desc_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'default'    => [
						'top'      => '15',
						'right'    => '15',
						'bottom'   => '15',
						'left'     => '15',
						'isLinked' => true,
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-woo-categories .uael-product-cat-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'display_cat_desc' => 'yes',
					],
					'separator'  => 'before',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Helpful Information.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_helpful_information() {

		if ( parent::is_internal_links() ) {
			$this->start_controls_section(
				'section_helpful_info',
				[
					'label' => __( 'Helpful Information', 'uael' ),
				]
			);

			$this->add_control(
				'help_doc_1',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( __( '%1$s Getting Started Article » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/woo-categories-widget/?utm_source=uael-pro-dashboard&utm_medium=uael-editor-screen&utm_campaign=uael-pro-plugin" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->add_control(
				'help_doc_2',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( __( '%1$s How to set description for category? » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/how-to-set-description-for-category-in-woocommerce/?utm_source=uael-pro-dashboard&utm_medium=uael-editor-screen&utm_campaign=uael-pro-plugin" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->end_controls_section();
		}
	}

	/**
	 * Get WooCommerce Product Categories.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_product_categories() {

		$product_cat = array();

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$product_categories = get_terms( 'product_cat', $cat_args );

		if ( ! empty( $product_categories ) ) {

			foreach ( $product_categories as $key => $category ) {

				$product_cat[ $category->term_id ] = $category->name;
			}
		}

		return $product_cat;
	}

	/**
	 * List all product categories.
	 *
	 * @return string
	 */
	public function query_product_categories() {

		$settings    = $this->get_settings();
		$include_ids = array();
		$exclude_ids = array();

		$atts = array(
			'limit'   => ( $settings['cats_count'] ) ? $settings['cats_count'] : '-1',
			'columns' => ( $settings['cat_columns'] ) ? $settings['cat_columns'] : '4',
			'parent'  => '',
		);

		if ( 'top' === $settings['category_filter_rule'] ) {
			$atts['parent'] = 0;
		} elseif ( 'include' === $settings['category_filter_rule'] && is_array( $settings['category_filter'] ) ) {
			$include_ids = array_filter( array_map( 'trim', $settings['category_filter'] ) );
		} elseif ( 'exclude' === $settings['category_filter_rule'] && is_array( $settings['category_filter'] ) ) {
			$exclude_ids = array_filter( array_map( 'trim', $settings['category_filter'] ) );
		}

		$hide_empty = ( 'yes' === $settings['display_empty_cat'] ) ? 0 : 1;

		// Get terms and workaround WP bug with parents/pad counts.
		$args = array(
			'orderby'    => ( $settings['orderby'] ) ? $settings['orderby'] : 'name',
			'order'      => ( $settings['order'] ) ? $settings['order'] : 'ASC',
			'hide_empty' => $hide_empty,
			'pad_counts' => true,
			'child_of'   => $atts['parent'],
			'include'    => $include_ids,
			'exclude'    => $exclude_ids,
		);

		$product_categories = get_terms( 'product_cat', $args );

		if ( '' !== $atts['parent'] ) {
			$product_categories = wp_list_filter(
				$product_categories, array(
					'parent' => $atts['parent'],
				)
			);
		}

		if ( $hide_empty ) {
			foreach ( $product_categories as $key => $category ) {
				if ( 0 === $category->count ) {
					unset( $product_categories[ $key ] );
				}
			}
		}

		$atts['limit'] = intval( $atts['limit'] );

		if ( $atts['limit'] > 0 ) {
			$product_categories = array_slice( $product_categories, 0, $atts['limit'] );
		}

		$columns = absint( $atts['columns'] );

		wc_set_loop_prop( 'columns', $columns );

		/* Category Link */
		remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
		add_action( 'woocommerce_before_subcategory', array( $this, 'template_loop_category_link_open' ), 10 );

		/* Category Wrapper */
		add_action( 'woocommerce_before_subcategory', array( $this, 'category_wrap_start' ), 15 );
		add_action( 'woocommerce_after_subcategory', array( $this, 'category_wrap_end' ), 8 );

		if ( 'yes' === $settings['display_cat_desc'] ) {
			add_action( 'woocommerce_after_subcategory', array( $this, 'category_description' ), 8 );
		}

		/* Category Title */
		remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
		add_action( 'woocommerce_shop_loop_subcategory_title', array( $this, 'template_loop_category_title' ), 10 );

		ob_start();

		if ( $product_categories ) {
			woocommerce_product_loop_start();

			foreach ( $product_categories as $category ) {

				include UAEL_MODULES_DIR . 'woocommerce/templates/content-product-cat.php';
			}

			woocommerce_product_loop_end();
		}

		woocommerce_reset_loop();

		$inner_classes  = ' uael-woo-cat__column-' . $settings['cat_columns'];
		$inner_classes .= ' uael-woo-cat__column-tablet-' . $settings['cat_columns_tablet'];
		$inner_classes .= ' uael-woo-cat__column-mobile-' . $settings['cat_columns_mobile'];

		$inner_content = ob_get_clean();

		/* Category Link */
		add_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
		remove_action( 'woocommerce_before_subcategory', array( $this, 'template_loop_category_link_open' ), 10 );

		/* Category Wrapper */
		remove_action( 'woocommerce_before_subcategory', array( $this, 'category_wrap_start' ), 15 );
		remove_action( 'woocommerce_after_subcategory', array( $this, 'category_wrap_end' ), 8 );

		if ( 'yes' === $settings['display_cat_desc'] ) {
			remove_action( 'woocommerce_after_subcategory', array( $this, 'category_description' ), 8 );
		}

		/* Category Title */
		remove_action( 'woocommerce_shop_loop_subcategory_title', array( $this, 'template_loop_category_title' ), 10 );
		add_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );

		return '<div class="uael-woo-categories-inner ' . $inner_classes . '">' . $inner_content . '</div>';
	}

	/**
	 * Wrapper Start.
	 *
	 * @param object $category Category object.
	 */
	function template_loop_category_link_open( $category ) {
		$link = apply_filters( 'uael_woo_category_link', esc_url( get_term_link( $category, 'product_cat' ) ) );

		echo '<a href="' . $link . '">';
	}

	/**
	 * Wrapper Start.
	 *
	 * @param object $category Category object.
	 */
	public function category_wrap_start( $category ) {
		echo '<div class="uael-product-cat-inner">';
	}


	/**
	 * Wrapper End.
	 *
	 * @param object $category Category object.
	 */
	public function category_wrap_end( $category ) {
		echo '</div>';
	}

	/**
	 * Category Description.
	 *
	 * @param object $category Category object.
	 */
	public function category_description( $category ) {

		if ( $category && ! empty( $category->description ) ) {

			echo '<div class="uael-product-cat-desc">';
				echo '<div class="uael-term-description">' . wc_format_content( $category->description ) . '</div>'; // WPCS: XSS ok.
			echo '</div>';
		}
	}


	/**
	 * Show the subcategory title in the product loop.
	 *
	 * @param object $category Category object.
	 */
	public function template_loop_category_title( $category ) {
		$output          = '<div class="uael-category__title-wrap">';
			$output     .= '<h2 class="woocommerce-loop-category__title">';
				$output .= esc_html( $category->name );
			$output     .= '</h2>';

		if ( $category->count > 0 ) {
				$output .= sprintf( // WPCS: XSS OK.
					/* translators: 1: number of products */
					_nx( '<mark class="uael-count">%1$s Product</mark>', '<mark class="uael-count">%1$s Products</mark>', $category->count, 'product categories', 'uael' ),
					number_format_i18n( $category->count )
				);
		}
		$output .= '</div>';

		echo $output;
	}

	/**
	 * Render output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();
		$node_id  = $this->get_id();

		$out_html      = '<div class="uael-woo-categories uael-woocommerce">';
			$out_html .= $this->query_product_categories();
		$out_html     .= '</div>';

		echo $out_html;
	}
}
