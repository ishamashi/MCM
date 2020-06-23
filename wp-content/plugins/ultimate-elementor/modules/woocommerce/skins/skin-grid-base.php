<?php
/**
 * UAEL WooCommerce Skin Grid - Default.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\Woocommerce\Skins;

use Elementor\Controls_Manager;
use Elementor\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Grid_Base
 *
 * @property Products $parent
 */
abstract class Skin_Grid_Base extends Skin_Base {

	/**
	 * Register control actions.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls_actions() {

		add_action( 'elementor/element/uael-woo-products/section_filter_field/after_section_end', [ $this, 'register_quick_view_controls' ], 20 );
		add_action( 'elementor/element/uael-woo-products/section_design_image/after_section_end', [ $this, 'register_quick_view_style_controls' ], 20 );
	}

	/**
	 * Register Quick View Controls.
	 *
	 * @since 0.0.1
	 * @param Widget_Base $widget widget object.
	 * @access public
	 */
	public function register_quick_view_controls( Widget_Base $widget ) {

		$this->parent = $widget;

		$this->start_controls_section(
			'section_content_quick_view',
			[
				'label' => __( 'Quick View', 'uael' ),
			]
		);

			$this->add_control(
				'quick_view_type',
				[
					'label'   => __( 'Quick View', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						''     => 'Hide',
						'show' => 'Show',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Quick View Style Controls.
	 *
	 * @since 0.0.1
	 * @param Widget_Base $widget widget object.
	 * @access public
	 */
	public function register_quick_view_style_controls( Widget_Base $widget ) {

		$this->start_controls_section(
			'section_content_quick_view_style',
			[
				'label'     => __( 'Quick View', 'uael' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'quick_view_type' ) => 'show',
				],
			]
		);

			$this->add_control(
				'quick_view_color',
				[
					'label'     => __( 'Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .uael-woocommerce .uael-quick-view-btn span' => 'color: {{VALUE}};',
					],
					'condition' => [
						$this->get_control_id( 'quick_view_type' ) => 'show',
					],
				]
			);

			$this->add_control(
				'quick_view_bg_color',
				[
					'label'     => __( 'Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .uael-woocommerce .uael-quick-view-btn' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						$this->get_control_id( 'quick_view_type' ) => 'show',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Change pagination arguments based on settings.
	 *
	 * @since 0.0.1
	 * @access protected
	 * @param string $located location.
	 * @param string $template_name template name.
	 * @param array  $args arguments.
	 * @param string $template_path path.
	 * @param string $default_path default path.
	 * @return string template location
	 */
	public function woo_pagination_template( $located, $template_name, $args, $template_path, $default_path ) {

		if ( 'loop/pagination.php' === $template_name ) {
			$located = UAEL_MODULES_DIR . 'woocommerce/templates/loop/pagination.php';
		}

		return $located;
	}

	/**
	 * Change pagination arguments based on settings.
	 *
	 * @since 0.0.1
	 * @access protected
	 * @param array $args pagination args.
	 * @return array
	 */
	public function woo_pagination_options( $args ) {

		$settings = $this->parent->get_settings();

		$pagination_arrow = false;

		if ( 'numbers_arrow' === $settings['pagination_type'] ) {
			$pagination_arrow = true;
		}

		$args['prev_next'] = $pagination_arrow;

		if ( '' !== $settings['pagination_prev_label'] ) {
			$args['prev_text'] = $settings['pagination_prev_label'];
		}

		if ( '' !== $settings['pagination_next_label'] ) {
			$args['next_text'] = $settings['pagination_next_label'];
		}

		return $args;
	}

	/**
	 * Get Wrapper Classes.
	 *
	 * @since 0.0.1
	 * @access public
	 */
	public function set_slider_attr() {

		$settings = $this->parent->get_settings();

		if ( 'slider' !== $settings['products_layout_type'] ) {
			return;
		}

		$is_rtl      = is_rtl();
		$direction   = $is_rtl ? 'rtl' : 'ltr';
		$show_dots   = ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );

		$slick_options = [
			'slidesToShow'   => ( $settings['slides_to_show'] ) ? absint( $settings['slides_to_show'] ) : 4,
			'slidesToScroll' => ( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 1,
			'autoplaySpeed'  => ( $settings['autoplay_speed'] ) ? absint( $settings['autoplay_speed'] ) : 5000,
			'autoplay'       => ( 'yes' === $settings['autoplay'] ),
			'infinite'       => ( 'yes' === $settings['infinite'] ),
			'pauseOnHover'   => ( 'yes' === $settings['pause_on_hover'] ),
			'speed'          => ( $settings['transition_speed'] ) ? absint( $settings['transition_speed'] ) : 500,
			'arrows'         => $show_arrows,
			'dots'           => $show_dots,
			'rtl'            => $is_rtl,
			'prevArrow'      => '<button type="button" data-role="none" class="slick-prev slick-arrow fa fa-angle-left" aria-label="Previous" role="button"></button>',
			'nextArrow'      => '<button type="button" data-role="none" class="slick-next slick-arrow fa fa-angle-right" aria-label="Next" role="button"></button>',
		];

		if ( $settings['slides_to_show_tablet'] || $settings['slides_to_show_mobile'] ) {

			$slick_options['responsive'] = [];

			if ( $settings['slides_to_show_tablet'] ) {

				$tablet_show   = absint( $settings['slides_to_show_tablet'] );
				$tablet_scroll = ( $settings['slides_to_scroll_tablet'] ) ? absint( $settings['slides_to_scroll_tablet'] ) : $tablet_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 1024,
					'settings'   => [
						'slidesToShow'   => $tablet_show,
						'slidesToScroll' => $tablet_scroll,
					],
				];
			}

			if ( $settings['slides_to_show_mobile'] ) {

				$mobile_show   = absint( $settings['slides_to_show_mobile'] );
				$mobile_scroll = ( $settings['slides_to_scroll_mobile'] ) ? absint( $settings['slides_to_scroll_mobile'] ) : $mobile_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 767,
					'settings'   => [
						'slidesToShow'   => $mobile_show,
						'slidesToScroll' => $mobile_scroll,
					],
				];
			}
		}

		$this->parent->add_render_attribute(
			'wrapper', [
				'data-woo_slider' => wp_json_encode( $slick_options ),
			]
		);
	}

	/**
	 * Render Query.
	 *
	 * @since 1.1.0
	 */
	public function render_query() {

		$this->parent->query_posts();
	}

	/**
	 * Render loop required arguments.
	 *
	 * @since 1.1.0
	 */
	public function render_loop_args() {

		$query = $this->parent->get_query();

		global $woocommerce_loop;

		$settings = $this->parent->get_settings();

		if ( 'grid' === $settings['products_layout_type'] ) {
			$woocommerce_loop['columns'] = (int) $settings['products_columns'];

			if ( 0 < $settings['products_per_page'] && '' !== $settings['pagination_type'] ) {
				/* Pagination */
				$paged                            = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$woocommerce_loop['paged']        = $paged;
				$woocommerce_loop['total']        = $query->found_posts;
				$woocommerce_loop['post_count']   = $query->post_count;
				$woocommerce_loop['per_page']     = $settings['products_per_page'];
				$woocommerce_loop['total_pages']  = ceil( $query->found_posts / $settings['products_per_page'] );
				$woocommerce_loop['current_page'] = $paged;
			}

			$this->parent->add_render_attribute(
				'inner', [
					'class' => [
						' columns-' . $woocommerce_loop['columns'],
					],
				]
			);
		} else {
			if ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) ) {

				$this->parent->add_render_attribute(
					'inner', [
						'class' => [
							'uael-slick-dotted',
						],
					]
				);
			}
		}
	}

	/**
	 * Pagination Structure.
	 *
	 * @since 1.1.0
	 */
	public function render_pagination_structure() {

		$settings = $this->parent->get_settings();

		if ( '' !== $settings['pagination_type'] ) {
			add_filter( 'wc_get_template', [ $this, 'woo_pagination_template' ], 10, 5 );
			add_filter( 'uael_woocommerce_pagination_args', [ $this, 'woo_pagination_options' ] );
			woocommerce_pagination();
			remove_filter( 'uael_woocommerce_pagination_args', [ $this, 'woo_pagination_options' ] );
			remove_filter( 'wc_get_template', [ $this, 'woo_pagination_template' ], 10, 5 );
		}
	}

	/**
	 * Render wrapper start.
	 *
	 * @since 1.1.0
	 */
	public function render_wrapper_start() {

		$settings = $this->parent->get_settings();

		$this->set_slider_attr();

		$this->parent->add_render_attribute(
			'wrapper', [
				'class' => [
					'uael-woocommerce',
					'uael-woo-products-' . $settings['products_layout_type'],
					'uael-woo-skin-' . $this->get_id(),
				],
			]
		);

		echo '<div ' . $this->parent->get_render_attribute_string( 'wrapper' ) . '">';
	}

	/**
	 * Render wrapper end.
	 *
	 * @since 1.1.0
	 */
	public function render_wrapper_end() {
		echo '</div>';
	}

	/**
	 * Render inner container start.
	 *
	 * @since 1.1.0
	 */
	public function render_inner_start() {

		$settings = $this->parent->get_settings();

		$this->parent->add_render_attribute(
			'inner', [
				'class' => [
					'uael-woo-products-inner',
					'uael-woo-product__column-' . $settings['products_columns'],
					'uael-woo-product__column-tablet-' . $settings['products_columns_tablet'],
					'uael-woo-product__column-mobile-' . $settings['products_columns_mobile'],
				],
			]
		);

		if ( '' !== $settings['products_hover_style'] ) {
			$this->parent->add_render_attribute(
				'inner', [
					'class' => [
						'uael-woo-product__hover-' . $settings['products_hover_style'],
					],
				]
			);
		}

		echo '<div ' . $this->parent->get_render_attribute_string( 'inner' ) . '>';
	}

	/**
	 * Render inner container end.
	 *
	 * @since 1.1.0
	 */
	public function render_inner_end() {
		echo '</div>';
	}

	/**
	 * Render woo loop start.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop_start() {
		woocommerce_product_loop_start();
	}

	/**
	 * Render woo loop.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop() {

		$query = $this->parent->get_query();

		while ( $query->have_posts() ) :
			$query->the_post();
			$this->render_woo_loop_template();
		endwhile;
	}

	/**
	 * Render woo default template.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop_template() {

		$settings = $this->parent->get_settings();

		include UAEL_MODULES_DIR . 'woocommerce/templates/content-product-default.php';
	}
	/**
	 * Render woo loop end.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop_end() {
		woocommerce_product_loop_end();
	}

	/**
	 * Render reset loop.
	 *
	 * @since 1.1.0
	 */
	public function render_reset_loop() {

		woocommerce_reset_loop();

		wp_reset_postdata();
	}

	/**
	 * Quick View.
	 *
	 * @since 0.0.1
	 * @access public
	 */
	public function quick_view_modal() {

		$quick_view_type = $this->get_instance_value( 'quick_view_type' );

		if ( '' !== $quick_view_type ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_script( 'flexslider' );

			$widget_id = $this->parent->get_id();

			include UAEL_MODULES_DIR . 'woocommerce/templates/quick-view-modal.php';
		}
	}

	/**
	 * Render Content.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	public function render() {

		$this->render_query();

		$query = $this->parent->get_query();

		if ( ! $query->have_posts() ) {
			return;
		}

		$this->render_loop_args();
		$this->render_wrapper_start();
			$this->render_inner_start();
				$this->render_woo_loop_start();
					$this->render_woo_loop();
				$this->render_woo_loop_end();
				$this->render_pagination_structure();
				$this->render_reset_loop();
			$this->render_inner_end();
		$this->render_wrapper_end();

		$this->quick_view_modal();
	}
}
