<?php

namespace Aepro;


use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Plugin;
use ElementorPro\Modules\Woocommerce\Widgets\Elements;
use WP_Query;


class Aepro_Post_Blocks extends Widget_Base{
    public function get_name() {
        return 'ae-post-blocks';
    }

    public function get_title() {
        return __( 'AE - Post Blocks', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_script_depends() {

        return [ 'jquery-masonry', 'ae-infinite-scroll', 'ae-swiper' ];

    }

    protected function _register_controls() {
		$helper = new Helper();

		$ae_post_types = $helper->get_rule_post_types();
		$ae_post_types_options = $ae_post_types;
        $ae_post_types_options['current_loop'] = __( 'Current Archive','ae-pro' );
		$ae_post_types_options['ae_by_id'] = __( 'Manual Selection','ae-pro' );
		$ae_post_types_options['related'] = __('Related Posts', 'ae-pro');

		if(class_exists('acf') || is_plugin_active('pods/init.php')){
			$ae_post_types_options['relation'] = __('Relationship', 'ae-pro');
        }

        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'ae-pro' ),
            ]
        );
		$this->add_control(
            'ae_post_type',
            [
                'label'         => __('Source','ae-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => $ae_post_types_options,
                'default' => key( $ae_post_types ),
            ]
        );

        $block_layouts[''] = 'Select Template';
        $block_layouts = $block_layouts + $helper->ae_block_layouts();
        
        $this->add_control(
            'template',
            [
                'label'     =>  __('Template','ae-pro'),
                'type'      =>  Controls_Manager::SELECT,
                'options'   =>  $block_layouts,
                'description' => __('Know more about templates <a href="http://aedocs.webtechstreet.com/article/9-creating-block-layout-in-anywhere-elementor-pro" target="_blank">Click Here</a>','ae-pro')
            ]
        );
        if(class_exists('acf') &&  is_plugin_active('pods/init.php')) {
            $this->add_control(
                'relationship_type',
                [
                    'label' => __('Relationship Type', 'ae-pro'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'acf',
                    'options' => [
                        'acf' => __('ACF', 'ae-pro'),
                        'pods' => __('Pods', 'ae-pro')
                    ],
                    'condition' => [
                        'ae_post_type'  => 'relation'
                    ]
                ]
            );
        }

		$this->add_control(
            'ae_post_ids',
            [
                'label'         => __('Posts','ae-pro'),
                'type'          => Controls_Manager::SELECT2,
                'multiple'    => true,
                'label_block' => true,
                'placeholder' => __( 'Selects Posts', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
				'condition' => [
					'ae_post_type' => 'ae_by_id',
				],
            ]
        );

		$this->add_control(
		        'related_by',
                [
                    'label' => __('Related By', 'ae-pro'),
                    'type'  => Controls_Manager::SELECT2,
                    'multiple'  => true,
                    'label_block'   => true,
                    'placeholder' => __('Select Taxonomies', 'ae-pro'),
                    'default'   => '',
                    'options'   => $helper->get_rules_taxonomies(),
                    'condition' => [
                            'ae_post_type'  => 'related'
                    ]
                ]
        );
        $this->add_control(
            'related_match_with',
            [
                'label'   => __( 'Match With', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'OR',
                'options' => [
                    'OR' => __( 'Anyone Term', 'ae-pro' ),
                    'AND'  => __( 'All Terms', 'ae-pro' )
                ],
                'condition' => [
                    'ae_post_type' => 'related'
                ]
            ]
        );

	    if(class_exists('acf') || is_plugin_active('pods/init.php')){
	        $this->add_control(
	            'acf_relation_field',
                [
                    'label' => __('Relationship Field', 'ae-pro'),
                    'tyoe'  => Controls_Manager::TEXT,
                    'description'   => __('Key of ACF / Pods Relationship Field', 'ae-pro'),
                    'condition' => [
                            'ae_post_type'  => 'relation'
                    ]
                ]
            );
	    }


        $this->add_control(
            'author_ae_ids',
            [
                'label'       => 'Authors',
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'label_block' => true,
                'placeholder' => __( 'Enter Author ID Separated by Comma', 'ae-pro' ),
                'options'     => Post_Helper::instance()->get_authors(),
                'conditions'   => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ae_post_type',
                            'operator' => '!==',
                            'value' => 'ae_by_id',
                        ], [
                            'name' => 'ae_post_type',
                            'operator' => '!==',
                            'value' => 'current_loop',
                        ],
                        [
                            'name' => 'ae_post_type',
                            'operator' => '!==',
                            'value' => 'related',
                        ], [
                            'name' => 'ae_post_type',
                            'operator' => '!==',
                            'value' => 'relation',
                        ]
                    ],
                ],
            ]
        );


        $ae_taxonomy_filter_args = [
            'show_in_nav_menus' => true,
        ];

        $ae_taxonomies = get_taxonomies( $ae_taxonomy_filter_args, 'objects' );

        foreach ( $ae_taxonomies as $ae_taxonomy => $object ) {
            $this->add_control(
                $ae_taxonomy . '_ae_ids',
                [
                    'label'       => $object->label,
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'label_block' => true,
                    'placeholder' => __( 'Enter ' .$object->label. ' ID Separated by Comma', 'ae-pro' ),
                    'object_type' => $ae_taxonomy,
                    'options'     => Post_Helper::instance()->get_taxonomy_terms($ae_taxonomy),
                    'condition' => [
                        'ae_post_type' => $object->object_type,
                    ],
                ]
            );
        }

        $this->add_control(
            'current_post',
            [
                'label' => __( 'Exclude Current Post', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'advanced',
            [
                'label'   => __( 'Advanced', 'ae-pro' ),
                'type'    => Controls_Manager::HEADING,
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => __( 'Order By', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post_date',
                'options' => [
                    'post_date'  => __( 'Date', 'ae-pro' ),
                    'post_title' => __( 'Title', 'ae-pro' ),
                    'menu_order' => __( 'Menu Order', 'ae-pro' ),
                    'rand'       => __( 'Random', 'ae-pro' ),
                    'meta_value'  => __( 'Custom Field', 'ae-pro' )
                ],
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'orderby_metakey_name',
            [
                'label' => __('Meta Key Name', 'ae-pro'),
                'tyoe'  => Controls_Manager::TEXT,
                'description'   => __('Custom Field Key', 'ae-pro'),
                'condition' => [
                    'orderby'  => 'meta_value'
                ],
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => __( 'Order', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => __( 'ASC', 'ae-pro' ),
                    'desc' => __( 'DESC', 'ae-pro' ),
                ],
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => __( 'Posts Count', 'ae-pro' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'   => __( 'Offset', 'ae-pro' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'ae_post_type!' => ['current_loop', 'ae_by_id'],
                ],
                'description' => __( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'ae-pro' ),
            ]
        );

        $this->add_control(
           'query_filter',
            [
                'label'         => __('Query Filter', 'ae-pro'),
                'type'          => Controls_Manager::TEXT,
                'condition' => [
                    'ae_post_type!' => ['current_loop', 'ae_by_id'],
                ],
                'description'   => __('<span style="color:red">Danger Ahead!!</span> It is a developer oriented feature. Only use if you know how exaclty WordPress queries and filters works.  <a href="https://aedocs.webtechstreet.com/article/32-add-your-own-filters-to-post-blocks-query" target="_blank">Read Instructions</a>', 'ae-pro')
            ]
        );



        $this->end_controls_section();

        $this->start_controls_section(
          'section_layout',
          [
              'label' => __( 'Layout', 'ae-pro' ),
          ]
        );

        $this->add_control(
            'layout_mode',
            [
                'label' => __('Layout Mode','ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'list'  => __('List','ae-pro'),
                    'grid'  => __('Grid', 'ae-pro'),
                    'carousel' => __('Carousel', 'ae-pro')
                ],
                'default' => 'grid',
                'prefix_class' => 'ae-post-layout-'
            ]
        );


            $this->add_control(
            'masonry_grid',
            [
                'label' => __( 'Masonry', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'masonry_on' => __( 'On', 'ae-pro' ),
                'masonry_off' => __( 'Off', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'layout_mode' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'show_infinite_scroll',
            [
                'label' => __('Infinite Scroll', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'ae-pro' ),
                'label_off' => __( 'No', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'layout_mode' => ['grid', 'list'],
                    'ae_post_type' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => __('Pagination','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'yes' =>   __( 'Yes', 'ae-pro' ),
                    'no'  =>   __( 'No', 'ae-pro' )
                ],
                'default' => 'yes',
                'condition' => [
                    'show_infinite_scroll' => '',
                    'layout_mode'          => ['grid', 'list']
                ]

            ]
        );



        $this->add_responsive_control(
          'columns',
          [
              'label' => __('Columns', 'ae-pro'),
              'type'  => Controls_Manager::NUMBER,
              'desktop_default' => '3',
              'tablet_default' => '2',
              'mobile_default' => '1',
              'min' => 1,
              'max' => 6,
              'condition' => [
                    'layout_mode' => 'grid'
              ],
              'selectors' => [
                  '{{WRAPPER}} .ae-post-list-item' => 'width: calc(100%/{{ value }})',
               ]
          ]
        );

        $this->add_responsive_control(
            'item_col_gap',
            [
                'label' => __('Column Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'layout_mode' => 'grid'
                ],
                'selectors' => [
                    '{{WRAPPER}}.ae-post-layout-grid article.ae-post-list-item' => 'padding-left:{{SIZE}}{{UNIT}}; padding-right:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ae-post-layout-grid .ae-pagination-wrapper' => 'padding-right:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ae-pagination-wrapper' => 'padding-left:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .aep-filter-bar' => 'padding-left:{{SIZE}}{{UNIT}}; padding-right:{{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'item_row_gap',
            [
                'label' => __('Row Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} article.ae-post-list-item' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'layout_mode!' => 'carousel'
                ]
            ]
        );

        $this->add_responsive_control(
            'carousel_item_row_gap',
            [
                'label' => __('Row Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-post-list-wrapper' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );
        if ( class_exists( 'WooCommerce' ) ) {
            $this->add_control(
                'sale_badge_switcher',
                [
                    'label' => __('Enable Sales Badge', 'ae-pro'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Yes', 'ae-pro'),
                    'label_off' => __('No', 'ae-pro'),
                    'return_value' => 'yes',
                ]
            );
        }

        $this->end_controls_section();

        $this->pagination_controls();

        $this->start_controls_section(
            'layout_style',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg',
                'label' => __( 'Item Background', 'ae-pro' ),
                'types' => [ 'none','classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-article-inner',
                'default' => '#fff'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-article-inner',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-article-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __( 'Item Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-article-inner',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'carousel_style',
            [
                'label' => __( 'Carousel', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_mode' => 'carousel',
                ]
            ]
        );

        $this->add_control(
            'heading_style_arrow',
            [
                'label' => __('Arrow', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );
        $this->start_controls_tabs( 'tabs_arrow_styles' );

        $this->start_controls_tab(
            'tab_arrow_normal',
            [
                'label' => __( 'Normal', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev i' => 'color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next i' => 'color:{{VAlUE}};'
                ],
                'default' => '#444',
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __(' Background Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next' => 'background-color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-prev, {{WRAPPER}} .ae-swiper-container .ae-swiper-button-next',
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrow_hover',
            [
                'label' => __( 'Hover', 'ae-pro' ),
            ]
        );
        $this->add_control(
            'arrow_color_hover',
            [
                'label' => __('Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev:hover i' => 'color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next:hover i' => 'color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_bg_color_hover',
            [
                'label' => __(' Background Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev:hover' => 'background-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next:hover' => 'background-color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_border_color_hover',
            [
                'label' => __(' Border Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev:hover' => 'border-color:{{VAlUE}};',
                    '{{WRAPPER}} .ae-swiper-button-next:hover' => 'border-color:{{VAlUE}};'
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_border_radius_hover',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                    '{{WRAPPER}} .ae-swiper-container .ae-swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 50
                    ],
                'range' =>
                    [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev i' => 'font-size:{{SIZE}}px;',
                    '{{WRAPPER}} .ae-swiper-button-next i' => 'font-size:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'navigation_button' => 'yes'
                    ]
            ]
        );


        $this->add_responsive_control(
            'arrow_horizontal_position',
            [
                'label' => __( 'Horizontal Position', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                /*'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-wrapper' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'position: absolute; height: 100%; top: 0; width: 100px; left: 0;',
                    'center' => '',
                    'right' => '',
                ],*/
                'default' => 'center',
                'condition' => [
                        'navigation_button' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'arrow_vertical_position',
            [
                'label' => __( 'Vertical Position', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'ae-pro' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'ae-pro' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'ae-pro' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                /*'selectors_dictionary' => [
                    'top' => 'top: 0; bottom: auto; transform: unset;',
                    'middle' => 'translate(-50%);',
                    'bottom' => 'top: auto; bottom: 0; transform: unset;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => '{{VALUE}}',
                    '{{WRAPPER}} .ae-swiper-button-next' => '{{VALUE}}',

                ],*/
                'default' => 'center',
                'condition' => [
                        'navigation_button' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'horizontal_arrow_offset',
            [
                'label' => __('Horizontal Offset', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-hpos-left .ae-swiper-button-wrapper' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-hpos-right .ae-swiper-button-wrapper' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-hpos-center .ae-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-hpos-center .ae-swiper-button-next' => 'right: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
                    'navigation_button' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'vertical_arrow_offset',
            [
                'label' => __('Vertical Offset', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .ae-vpos-top .ae-swiper-button-wrapper' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-vpos-bottom .ae-swiper-button-wrapper' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-vpos-middle .ae-swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .ae-vpos-middle .ae-swiper-button-next' => 'top: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
                    'navigation_button' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ae-swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'heading_style_dots',
            [
                'label' => __('Dots', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_size',
            [
                'label' => __('Dots Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width:{{SIZE}}px; height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Active Dot Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color:{{VAlUE}} !important;',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'inactive_dots_color',
            [
                'label' => __('Inactive Dot Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'heading_style_scroll',
            [
                'label' => __('Scrollbar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'scroll_size',
            [
                'label' => __('Scrollbar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-vertical .ae-swiper-scrollbar' => 'width:{{SIZE}}px;',
                    '{{WRAPPER}} .swiper-container-horizontal .ae-swiper-scrollbar' => 'height:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scrollbar_color',
            [
                'label' => __('Scrollbar Drag Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scroll_color',
            [
                'label' => __('Scrollbar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-scrollbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'scrollbar' => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'heading_style_progress',
            [
                'label' => __('Progress Bar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );
        $this->add_control(
            'progressbar_color',
            [
                'label' => __('Prgress Bar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );

        $this->add_control(
            'progress_color',
            [
                'label' => __('Prgress Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progress' => 'background-color:{{VAlUE}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );



        $this->add_control(
            'progressbar_size',
            [
                'label' => __('Prgress Bar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progress' => 'height:{{SIZE}}px;',
                    '{{WRAPPER}} .swiper-container-vertical .swiper-pagination-progress' => 'width:{{SIZE}}px;',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' =>
                    [
                        'ptype' => 'progress'
                    ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pagination_style',
            [
                'label' => __( 'Pagination', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_mode' => ['list', 'grid'],
                    'show_pagination' => 'yes'

                ]
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => __('Item Gap','ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};',
                ]

            ]
        );

        $this->add_responsive_control(
            'pi_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pi_color',
            [
                'label' => __('Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'color:{{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pi_bg',
            [
                'label' => __('Backround','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'background-color:{{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pi_hover_color',
            [
                'label' => __('Hover/Current Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper .current' => 'color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper span:hover' => 'color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper a:hover' => 'color:{{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pi_hover_bg',
            [
                'label' => __('Hover/Current Background','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper .current' => 'background-color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper span:hover' => 'background-color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper a:hover' => 'background-color:{{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pi_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-pagination-wrapper *',
            ]
        );

        $this->add_control(
            'pi_border_hover_color',
            [
                'label' => __('Border Hover Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *:hover' => 'border-color: {{VALUE}}'
                ],
                'condition' => [
                    'pi_border_border!' => ''
                ]
            ]
        );

        $this->add_control(
            'pi_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_style',
            [
                'label' => __( 'Loading Overlay', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => __( 'Color', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-post-overlay',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'infinite_scroll_style',
            [
                'label' => __( 'Infinite Scroll', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_infinite_scroll' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'loadmore_align',
            [
                'label' => __( 'Align', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .load-more-wrapper' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .infinite-scroll-last' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .infinite-scroll-request' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'ias_loader_color',
            [
                'label' => __('Loader Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .loader-ellips__dot' => 'background-color:{{VALUE}}'
                ],
                'condition' => [
                    'show_infinite_scroll' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'last_message_heading',
            [
                'label' => __('Last Message', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'last_message_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .infinite-scroll-last',
            ]
        );

        $this->add_control(
            'last_message_bg',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .infinite-scroll-last' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_heading',
            [
                'label' => __('Load More', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more_typography',
                'label' => __( 'Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .view-more-button',
            ]
        );


        $this->start_controls_tabs( 'tabs_read_more_styles' );

        $this->start_controls_tab(
            'tab_load_more_normal',
            [
                'label' => __( 'Normal', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'load_more_color',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'load_more_bg',
            [
                'label' => __( 'Background Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'label' => __( 'Border', 'ae-pro' ),
                'default' => '1px',
                'selector' => '{{WRAPPER}} .view-more-button',
            ]
        );

        $this->add_control(
            'load_more_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_text_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_text_margin',
            [
                'label' => __( 'Margin', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_load_more_hover',
            [
                'label' => __( 'Hover', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'load_more_color_hover',
            [
                'label' => __( 'Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button:hover' => 'color: {{VALUE}};',
                ]
            ]
        );


        $this->add_control(
            'load_more_bg_hover',
            [
                'label' => __( 'Background Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_border_color_hover',
            [
                'label' => __( 'Border Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button:hover' => 'border-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'load_more_border_radius_hover',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .view-more-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->sale_badge_styles();

        $this->infinite_scroll_controls();

        $this->carousel_controls();

        $this->sale_badge_controls();

    }

    protected function render() {

        $settings = $this->get_settings();
        if(!isset($settings['template']) || empty($settings['template'])){
            echo __('Please select a template first','ae-pro');
        }else{
            $this->generate_output($settings);
        }
    }

    function generate_output($settings,$with_wrapper = true){
        $helper = new Helper();
		$post_type = $settings['ae_post_type'];
		
        $settings['template'] = apply_filters( 'wpml_object_id', $settings['template'], 'ae_global_templates' );

        $settings['direction'] = 'horizontal';

        list($post_items, $query_args) = $this->get_posts($settings);

		if(!isset($query_args) && $post_type != 'current_loop' ){
			return;
		}
		//echo '<pre>'; print_r($settings); echo '</pre>'; die();
		if($settings['layout_mode'] == 'carousel') {

            /*-- Carousel */
            $slide_per_view['desktop'] = $settings['slide_per_view'];
            $slide_per_view['tablet'] = $settings['slide_per_view_tablet'];
            $slide_per_view['mobile'] = $settings['slide_per_view_mobile'];

            $slides_per_group['desktop'] = $settings['slides_per_group'];
            $slides_per_group['tablet'] = $settings['slides_per_group_tablet'];
            $slides_per_group['mobile'] = $settings['slides_per_group_mobile'];
            //echo '<pre>';print_r($slide_per_view);'</pre>';

            $direction = $settings['direction'];
            $speed = $settings['speed'];
            $autoplay = $settings['autoplay'];
            $duration = $settings['duration'];
            $effect = $settings['effect'];
            $space['desktop'] = $settings['space']['size'];
            $space['tablet'] = $settings['space_tablet']['size'];
            $space['mobile'] = $settings['space_mobile']['size'];
            //print_r(json_encode($space));
            $loop = $settings['loop'];
            $zoom = 'no'; //$settings['zoom'];
            $pagination_type = $settings['ptype'];
            $navigation_button = $settings['navigation_button'];
            $clickable = $settings['clickable'];
            $keyboard = $settings['keyboard'];
            $scrollbar = $settings['scrollbar'];
            $ptype = $settings['ptype'];

            $this->add_render_attribute('outer-wrapper', 'class', 'ae-swiper-outer-wrapper');

            $this->add_render_attribute('outer-wrapper', 'data-speed', $speed['size']);
            $this->add_render_attribute('outer-wrapper', 'data-direction', $direction);
            if ($autoplay == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-autoplay', $autoplay);
            }
            if ($autoplay == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-duration', $duration['size']);
            }
            $this->add_render_attribute('outer-wrapper', 'data-effect', $effect);
            $this->add_render_attribute('outer-wrapper', 'data-space', json_encode($space, JSON_NUMERIC_CHECK));
            if ($loop == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-loop', $loop);
            } else {
                autoplayStopOnLast:
                true;
            }
            if ($zoom == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-zoom', $zoom);
            }

            if (!empty($slide_per_view)) {
                $this->add_render_attribute('outer-wrapper', 'data-slides-per-view', json_encode($slide_per_view, JSON_NUMERIC_CHECK));
            }

            if (!empty($slides_per_group)) {
                $this->add_render_attribute('outer-wrapper', 'data-slides-per-group', json_encode($slides_per_group, JSON_NUMERIC_CHECK));
            }


            if ($ptype != '') {
                $this->add_render_attribute('outer-wrapper', 'data-ptype', $ptype);
            }
            if ($pagination_type == 'bullets' && $clickable == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-clickable', $clickable);
            }
            if ($navigation_button == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-navigation', $navigation_button);
            }
            if ($keyboard == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-keyboard', $keyboard);
            }
            if ($scrollbar == 'yes') {
                $this->add_render_attribute('outer-wrapper', 'data-scrollbar', $scrollbar);
            }

            /*-- Carousel */
        }

		$masonry = $settings['masonry_grid'];
		$ias = $settings['show_infinite_scroll'];
        $ias_load_with_button = $settings['ias_load_with_button'];
        $ias_load_offest_page = $settings['ias_load_offest_page'];

		$this->add_render_attribute( 'post-list-wrapper', 'class', 'ae-post-list-wrapper' );
        $this->add_render_attribute( 'article-inner', 'class', 'ae-article-inner' );

		$this->add_render_attribute( 'post-widget-wrapper', 'data-pid', get_the_ID() );
		$this->add_render_attribute( 'post-widget-wrapper', 'data-wid', $this->get_id() );
		$this->add_render_attribute( 'post-widget-wrapper', 'data-source', $settings['ae_post_type'] );
		$this->add_render_attribute( 'post-widget-wrapper', 'class', 'ae-post-widget-wrapper' );


		if($settings['ae_post_type'] == 'current_loop' && $ias == 'yes'){
            $this->add_render_attribute( 'post-widget-wrapper', 'class', 'ae-ias-load-with-button-'.$ias_load_with_button);
            $this->add_render_attribute( 'post-widget-wrapper', 'data-load-offset-page', $ias_load_offest_page);
            $this->add_render_attribute( 'post-widget-wrapper', 'class', 'ae-ias-'.$ias);
        }


		if($settings['layout_mode'] == 'carousel'){
            $this->add_render_attribute( 'post-widget-wrapper', 'class', 'ae-carousel-yes');
        }else{
            $this->add_render_attribute( 'post-widget-wrapper', 'class', 'ae-masonry-'.$masonry);

        }

        $this->add_render_attribute('post-widget-wrapper', 'class', 'ae-hpos-' . $settings['arrow_horizontal_position']);
        $this->add_render_attribute('post-widget-wrapper', 'class', 'ae-vpos-' . $settings['arrow_vertical_position']);



		if($post_type == 'current_loop'){
			$this->add_render_attribute( 'post-widget-wrapper', 'class', 'no-ajax' );
		}

		$this->add_render_attribute( 'post-list-item', 'class', 'ae-post-list-item' );

		if(isset($settings['sale_badge_switcher'])  && $settings['sale_badge_switcher'] == 'yes'){
            $this->add_render_attribute( 'post-widget-wrapper', 'class', 'sale-badge-'.$settings['sale_badge_switcher'] );
            $this->add_render_attribute( 'article-inner', 'class', 'badge-type-'.$settings['sale_badge_type'] );
        }

		$with_css = false;
		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			$with_css = true;
		}

		?>
        <div class="ae-post-overlay"></div>
            <?php if($with_wrapper){ ?>
                <div <?php echo $this->get_render_attribute_string('post-widget-wrapper'); ?>>
            <?php } ?>
            <?php if($settings['layout_mode'] == 'carousel'){ ?>
                <div <?php echo $this->get_render_attribute_string('outer-wrapper'); ?> >
                    <?php $this->add_render_attribute('swiper-container', 'class', 'ae-swiper-container'); ?>
                    <div <?php echo $this->get_render_attribute_string('swiper-container'); ?> >
                        <?php $this->add_render_attribute('post-list-wrapper', 'class', 'ae-swiper-wrapper'); ?>
                        <?php $this->add_render_attribute('post-list-item', 'class', 'ae-swiper-slide'); ?>
                        <?php $this->add_render_attribute('article-inner', 'class', 'ae-swiper-slide-wrapper'); ?>

        <?php  } ?>
                    <div <?php echo $this->get_render_attribute_string('post-list-wrapper'); ?>>
                        <?php while($post_items->have_posts()){
                            $post_items->the_post();
                            ?>
                            <article <?php echo $this->get_render_attribute_string('post-list-item'); ?>>
                                <div <?php echo $this->get_render_attribute_string('article-inner'); ?>>
                                    <div class="ae_data elementor elementor-<?php echo $settings['template']; ?>">
                                        <?php if ( class_exists( 'WooCommerce' ) ) { ?>
                                            <?php if ($settings['sale_badge_switcher'] == 'yes') { ?>
                                                <?php if (get_post_type() == 'product') {
                                                    $p = new \WC_Product(get_the_ID());
                                                    if ($p->is_on_sale()) { ?>
                                                        <div class="ae-sale-badge-wrapper"><span
                                                                    class="onsale"><?php echo $settings['sale_badge_title']; ?></span>
                                                        </div>
                                                    <?php }
                                                }
                                            }
                                        }
                                        ?>
                                        <?php echo Plugin::instance()->frontend->get_builder_content( $settings['template'],$with_css ); ?>
                                    </div>
                                </div>
                            </article>
                        <?php }
                        wp_reset_postdata(); ?>
                    </div>
        <?php if($settings['layout_mode'] == 'carousel'){ ?>
                <?php if($pagination_type != ''){ ?>
                    <div class = "ae-swiper-pagination"></div>
                <?php } ?>

                <?php if($navigation_button == 'yes'){ ?>
                        <?php if($settings['arrow_horizontal_position'] != 'center'){;?>
                            <div class="ae-swiper-button-wrapper">
                        <?php } ?>
                                <div class = "ae-swiper-button-prev">
                                    <?php if($settings['direction'] == 'vertical') { ?>
                                        <i class="fa fa-angle-up"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-angle-left"></i>
                                    <?php } ?>
                                </div>
                                <div class = "ae-swiper-button-next">
                                    <?php if($settings['direction'] == 'vertical') { ?>
                                        <i class="fa fa-angle-down"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-angle-right"></i>
                                    <?php } ?>
                                </div>
                        <?php if($settings['arrow_horizontal_position'] != 'center'){;?>
                            </div>
                        <?php } ?>
                <?php } ?>

                <?php if($scrollbar == 'yes'){ ?>
                    <div class = "ae-swiper-scrollbar"></div>

                <?php } ?>
                </div>
            </div>
        <?php  } ?>
        <?php if($ias == 'yes' && $settings['ae_post_type'] == 'current_loop'){
            $last_page_text = $settings['ias_last_page_text'];
            ?>
            <div class="scroller-status">
                <div class="infinite-scroll-request loader-ellips">
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                </div>
                <p class="infinite-scroll-last"><?php echo $last_page_text; ?></p>
                <p class="infinite-scroll-error">No more pages to load</p>
            </div>
            <?php if($ias_load_with_button == 'yes' && $settings['layout_mode'] != 'carousel') { ?>
                <div class="load-more-wrapper"><button class="view-more-button" style="display:none;"> <?php echo $settings['ias_load_more_button_text']; ?> </button ></div>
            <?php } ?>
        <?php } ?>



 		<?php

            $this->pagination_markup($settings, $post_items);

        ?>

		<?php if($with_wrapper){ ?>
            </div>
		<?php } ?>

		<?php
	}

    function infinite_scroll_controls()
    {

        $this->start_controls_section(
            'infinite_scroll_controls',
            [
                'label' => __('Infinite Scroll', 'ae-pro'),
                'condition' => [
                    'layout_mode!' => 'carousel',
                    'show_infinite_scroll' => 'yes',
                    'ae_post_type' => 'current_loop'
                ]
            ]
        );



        $this->add_control(
            'ias_last_page_text',
            [
                'label' => __('Last Page Text','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => __('Last Page Text','ae-pro'),
                'default' => __('End of content','ae-pro'),
                'condition' => [
                    'show_infinite_scroll' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'ias_load_with_button',
            [
                'label' => __('Load With Button', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'ae-pro' ),
                'label_off' => __( 'No', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'show_infinite_scroll' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'ias_load_more_button_text',
            [
                'label' => __('Button Text','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => __('Button Text','ae-pro'),
                'default' => __('View More','ae-pro'),
                'condition' => [
                    'ias_load_with_button' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'ias_load_offest_page',
            [
                'label' => __('Offset Page','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => __('Offset Page','ae-pro'),
                'default' => __('2','ae-pro'),
                'condition' => [
                    'ias_load_with_button' => 'yes',
                    'show_infinite_scroll' => 'yes'
                ]
            ]
        );
        $this->end_controls_section();
    }

    function pagination_controls(){

        $this->start_controls_section(
            'pagination_contols',
            [
                'label' => __( 'Pagination', 'ae-pro' ),
                'condition' => [
                    'layout_mode!' => 'carousel',
                    'show_pagination' => 'yes',
                    'show_infinite_scroll!' => 'yes'
                ]
            ]
        );


        $this->add_control(
            'show_prev_next',
            [
                'label' => __('Show Prev/Next','ae-pro'),
                'type'  => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'show_pagination' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'prev_text',
            [
                'label' => __('Previous Text','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'default' => __('&laquo; Previous','ae-pro'),
                'condition' => [
                    'show_pagination' => 'yes',
                    'show_prev_next' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'next_text',
            [
                'label' => __('Previous Text','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'default' => __('Next &raquo;','ae-pro'),
                'condition' => [
                    'show_pagination' => 'yes',
                    'show_prev_next' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => __( 'Alignment', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                'condition' => [
                    'show_pagination' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    function carousel_controls()
    {

        $this->start_controls_section(
            'carousel_control',
            [
                'label' => __( 'Carousel', 'ae-pro' ),
                'condition' => [
                        'layout_mode' => 'carousel'
                ]
            ]
        );

        $this->add_control(
            'image_carousel',
            [
                'label' => __('Carousel', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        // Todo:: different effects management
        $this->add_control(
            'effect',
            [
                'label' => __('Effects', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade' => __('Fade', 'ae-pro'),
                    'slide' => __('Slide', 'ae-pro'),
                    //'cube' => __('Cube', 'ae-pro'),
                    'coverflow' => __('Coverflow', 'ae-pro'),
                    'flip' => __('Flip', 'ae-pro'),
                ],
                'default'=>'slide',
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );

        /*$this->add_control(
            'direction',
            [
                'label' => __('Direction', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __('Horizontal', 'ae-pro'),
                    'vertical' => __('Vertical', 'ae-pro')
                ],
                'default'=>'horizontal',
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );*/

        $this->add_responsive_control(
            'slide_per_view',
            [
                'label' => __( 'Slides Per View', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'effect',
                            'operator' => '==',
                            'value' => 'slide',
                        ], [
                            'name' => 'effect',
                            'operator' => '==',
                            'value' => 'coverflow',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_per_group',
            [
                'label' => __( 'Slides Per Group', 'ae-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 1,
                'tablet_default' => 1,
                'mobile_default' => 1,
            ]
        );

        $this->add_control(
            'carousel_settings_heading',
            [
                'label' => __('Setting', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Speed', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 5000,
                ],
                'description' => __('Duration of transition between slides (in ms)', 'ae-pro'),
                'range' => [
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 1000
                    ]
                ],
                'condition' => [
                    'layout_mode' => 'carousel'
                ]

            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]

        );

        $this->add_control(
            'duration',
            [
                'label' => __('Duration', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 900,
                ],
                'description' => __('Delay between transitions (in ms)', 'ae-pro'),
                'range' => [
                    'px' =>[
                        'min' => 300,
                        'max' => 3000,
                        'step' => 300,
                    ]
                ],
                'condition' => [
                    'autoplay' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'space',
            [
                'label' => __('Space Between Slides', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>[
                    'size' => 15,
                ],
                'tablet_default' =>[
                    'size' => 10,
                ],
                'mobile_default' =>[
                    'size' => 5,
                ],
                'range' => [
                    'px'=>[
                        'min'=> 0,
                        'max'=> 50,
                        'step'=> 5,
                    ]
                ],
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );

        /*$this->add_control(
            'zoom',
            [
                'label' => __('Zoom', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
                'condition' => [
                    'layout_mode' => 'carousel'
                ]
            ]
        );*/

        $this->add_control(
            'pagination_heading',
            [
                'label' => __('Pagination', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );



        $this -> add_control(
            'ptype',
            [
                'label' => __(' Pagination Type' , 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        ''        => __('None', 'ae-pro'),
                        'bullets' => __( 'Bullets' , 'ae-pro'),
                        'fraction' =>__( 'Fraction' , 'ae-pro'),
                        'progress' =>__('Progress' , 'ae-pro'),
                    ],
                'default'=>'bullets'
            ]
        );

        $this->add_control(
            'clickable',
            [
                'label' =>__('Clickable' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'condition'=> [
                    'ptype' => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'navigation_button',
            [
                'label' => __('Previous/Next Button' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes' , 'ae-pro'),
                'label_off' => __('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'keyboard',
            [
                'label' => __('Keyboard Control' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=> 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'return_value'=>'yes',
            ]
        );

        $this->add_control(
            'scrollbar',
            [
                'label' =>__('Scroll bar', 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=>'yes',
                'label_on' =>__('Yes' , 'ae-pro'),
                'label_off'=>__('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

    }

    function sale_badge_controls(){
        if ( !class_exists( 'WooCommerce' ) ) {
            return;
        }
        $this->start_controls_section(
            'sale_badge_layout',
            [
                'label' => __( 'Sale Badge', 'ae-pro' ),
                'condition' => [
                        'sale_badge_switcher' => 'yes'
                ]
            ]
        );

        $this -> add_control(
            'sale_badge_type',
            [
                'label' => __(' Type ' , 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        'ribbon' => __( 'Ribbon' , 'ae-pro'),
                        'badge' =>__( 'Badge' , 'ae-pro'),
                    ],
                'default'=>'ribbon'
            ]
        );

        $this->add_control(
            'sale_badge_title',
            [
                'label' => __('Title','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'placeholder' => __('Sale Badge Title','ae-pro'),
                'default' => __('Sale!','ae-pro'),
            ]
        );

        $this->add_control(
            'sale_badge_horizontal_position',
            [
                'label' => __( 'Horizontal Position', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ]

                ],
                'prefix_class' => 'badge-h-',
                'default' => 'left',
            ]
        );

        $this->add_control(
            'sale_badge_vertical_position',
            [
                'label' => __( 'Vertical Position', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'ae-pro' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'ae-pro' ),
                        'icon' => 'eicon-v-align-bottom',
                    ]

                ],
                'prefix_class' => 'badge-v-',
                'default' => 'top',
                'condition' => [
                    'sale_badge_type' => 'badge'
                ]
            ]
        );

        $this->end_controls_section();
    }

    function sale_badge_styles(){
        if ( !class_exists( 'WooCommerce' ) ) {
            return;
        }
        $helper = new Helper();
        $this->start_controls_section(
            'sale_badge_style',
            [
                'label' => __( 'Sale Badge', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'sale_badge_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'sale_badge_bg_color',
            [
                'label' => __( 'Background Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );


        $ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

        $this->add_responsive_control(
            'sale_badge_distance',
            [
                'label' => __( 'Distance', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .badge-type-ribbon .ae-sale-badge-wrapper span.onsale' => 'transform: ' . $ribbon_distance_transform,
                ],
                'condition' => [
                    'sale_badge_type' => 'ribbon'
                ]
            ]
        );

        $this->add_responsive_control(
            'sale_badge_size',
            [
                'label' => __( 'Size', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'em', 'px' ],
                'default' => [
                    'unit' => 'em',
                ],
                'tablet_default' => [
                    'unit' => 'em',
                ],
                'mobile_default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 25,
                        'step' => 0.1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'min-height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}};',
                ],
                'condition' => [
                    'sale_badge_type' => 'badge'
                ]
            ]
        );

        $this->add_control(
            'sale_badge_text_color',
            [
                'label' => __( 'Text Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ae-sale-badge-wrapper span.onsale' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sale_badge_typography',
                'selector' => '{{WRAPPER}} span.onsale',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );


        $helper->box_model_controls($this,[
            'name' => 'badge_style',
            'label' => __('Badge','ae-pro'),
            'border' => true,
            'border-radius' => true,
            'margin' => true,
            'padding' => true,
            'box-shadow' => true,
            'selector' => '{{WRAPPER}} .ae-sale-badge-wrapper  span.onsale'
        ]);

        $this->end_controls_section();
    }

    function get_posts($settings){
        $post_items = null;
        $helper = new Helper();
        $query_args = array();
	    $post_type = $settings['ae_post_type'];

	    $ae_post_ids = $settings['ae_post_ids'];

	    if(isset($_POST['pid'])){
		    $current_post_id = $_POST['pid'];
	    }else{
		    $current_post_id = get_the_ID();
	    }

	    switch($post_type)
	    {
		    case 'current_loop' :   if(!Plugin::instance()->editor->is_edit_mode()){
			    global $wp_query;
			    $post_items = new WP_Query( $wp_query->query_vars );
			    //echo '<pre>'; print_r($post_items); echo '</pre>'; die();
		    }else{
			    $render_mode = get_post_meta($current_post_id, 'ae_render_mode', true);
			    //echo $render_mode;
			    switch($render_mode){
				    case 'author_template': $author_data = $helper->get_preview_author_data();
					    $query_args['author'] = $author_data['prev_author_id'];
					    $query_args['post_type'] = 'any';
					    break;

				    case 'archive_template': $term_data = $helper->get_preview_term_data();
					    $query_args['tax_query'] = [
						    [
							    'taxonomy' => $term_data['taxonomy'],
							    'field'    => 'term_id',
							    'terms'    => $term_data['prev_term_id']
						    ]
					    ];
					    $query_args['post_type'] = 'any';
					    break;

				    case 'date_template' : $query_args['post_type'] = 'post';
					    break;

				    default              : $query_args['post_type'] = 'post';
			    }
		    }
			    break;

		    case 'ae_by_id'     :   $query_args['post_type'] = 'any';
                                    $query_args['post__in']  = $ae_post_ids;

                                    if ( empty( $query_args['post__in'] ) ) {
                                        // If no selection - return an empty query
                                        $query_args['post__in'] = [ -1 ];
                                    }
                                    break;

		    case 'related'      :   if(isset($_POST['fetch_mode'])){
                                        $cpost_id = $_POST['cpid'];
                                        $cpost = get_post($cpost_id);
                                    }else{
                                        $cpost = $helper->get_demo_post_data();
                                        $cpost_id = $cpost->ID;
                                    }

                                    $query_args = [
                                        'orderby' => $settings['orderby'],
                                        'order' => $settings['order'],
                                        'ignore_sticky_posts' => 1,
                                        'post_status' => 'publish', // Hide drafts/private posts for admins
                                        'offset'    => $settings['offset'],
                                        'posts_per_page' => $settings['posts_per_page'],
                                        'post__not_in'  => [ $cpost_id],
                                        'post_type'     => 'any'
                                    ];

                                    if($settings['orderby'] == 'meta_value'){
                                        $query_args['meta_key'] = $settings['orderby_metakey_name'];
                                    }

                                    if(isset($_POST['page_num'])){
                                        $query_args['offset'] = ($query_args['posts_per_page'] * ($_POST['page_num']-1)) + $query_args['offset'];
                                    }

                                    $taxonomies = $settings['related_by'];

                                    if($taxonomies) {
                                        foreach ($taxonomies as $taxonomy) {

                                            $terms = get_the_terms($cpost_id, $taxonomy);
                                            if ($terms) {
                                                foreach ($terms as $term) {

                                                    $query_args['tax_query'][] = [
                                                        'taxonomy' => $taxonomy,
                                                        'field' => 'term_id',
                                                        'terms' => $term->term_id,
                                                    ];
                                                }
                                            }

                                        }

                                        $query_args['tax_query']['relation'] = $settings['related_match_with'];
                                    }
                                    break;

		    case 'relation'     :   $field = $settings['acf_relation_field'];


                                    if(isset($_POST['fetch_mode'])){
                                        $cpost_id = $_POST['cpid'];
                                        $cpost = get_post($cpost_id);
                                    }else{
                                        $cpost = $helper->get_demo_post_data();
                                        $cpost_id = $cpost->ID;
                                    }

                                    if(is_plugin_active('advanced-custom-fields/acf.php') && is_plugin_active('pods/init.php')){
                                        if($settings['relationship_type'] == 'pods'){
                                            $pods =  get_post_meta($cpost_id,$field);
                                            foreach($pods as $pod){
                                                $post_items[] = $pod['ID'];
                                            }
                                        } else{
                                            $post_items = get_field($field, $cpost_id);
                                        }

                                    }else if(is_plugin_active('pods/init.php')){
                                        $pods =  get_post_meta($cpost_id,$field);
                                        foreach($pods as $pod){
                                            $post_items[] = $pod['ID'];
                                        }
                                    }else{
                                        $post_items = get_field($field, $cpost_id);
                                    }


                                    if($post_items){
                                        $query_args = [
                                            'orderby'           => $settings['orderby'],
                                            'order'             => $settings['order'],
                                            'ignore_sticky_posts' => 1,
                                            'post_status'       => 'publish', // Hide drafts/private posts for admins
                                            'offset'            => $settings['offset'],
                                            'posts_per_page'    => $settings['posts_per_page'],
                                            'post_type'         => 'any',
                                            'post__in'          => $post_items,
                                            'post__not_in'      => [ $cpost_id]
                                        ];

                                        if($settings['orderby'] == 'meta_value'){
                                            $query_args['meta_key'] = $settings['orderby_metakey_name'];
                                        }

                                        if(isset($_POST['page_num'])){
                                            $query_args['offset'] = ($query_args['posts_per_page'] * ($_POST['page_num']-1)) + $query_args['offset'];
                                        }
                                    }

                                    break;

		    default             :   $query_args = [
                                        'orderby' => $settings['orderby'],
                                        'order' => $settings['order'],
                                        'ignore_sticky_posts' => 1,
                                        'post_status' => 'publish', // Hide drafts/private posts for admins
                                    ];

                                    if($settings['orderby'] == 'meta_value'){
                                        $query_args['meta_key'] = $settings['orderby_metakey_name'];
                                    }

                                    $query_args['post_type'] = $post_type;
                                    $query_args['offset'] = $settings['offset'];
                                    $query_args['posts_per_page'] = $settings['posts_per_page'];
                                    $query_args['tax_query'] = [];

                                    if(is_singular() && ($settings['current_post']=='yes')){
                                        $query_args['post__not_in'] = array($current_post_id);
                                    }
                                    $taxonomies = get_object_taxonomies( $post_type, 'objects' );
                                    foreach ( $taxonomies as $object ) {

	                                    if(isset($settings['filter_taxonomy']) && $object->name == $settings['filter_taxonomy'] && isset($_POST['term_id']) && $_POST['term_id'] > 0){
		                                    $query_args['tax_query'][] = [
			                                    'taxonomy' => $settings['filter_taxonomy'],
			                                    'field'    => 'term_id',
			                                    'terms'    => $_POST['term_id'],
		                                    ];
	                                    }else{
		                                    $setting_key = $object->name . '_ae_ids';

		                                    if ( ! empty( $settings[ $setting_key ] ) ) {
			                                    $query_args['tax_query'][] = [
				                                    'taxonomy' => $object->name,
				                                    'field'    => 'term_id',
				                                    'terms'    => $settings[ $setting_key ],
			                                    ];
		                                    }
                                        }

                                    }

                                    if(isset($_POST['page_num'])){
                                        $query_args['offset'] = ($query_args['posts_per_page'] * ($_POST['page_num']-1)) + $query_args['offset'];
                                    }

                                    if(is_array($settings['author_ae_ids']) && count($settings['author_ae_ids'])){
                                        $query_args['author'] = implode(',', $settings['author_ae_ids']);
                                    }
	    }


	    if($post_type == 'current_loop' && !Plugin::instance()->editor->is_edit_mode()){
            $query_args = null;
	    }else{
		    if(isset($query_args)){
		        if(!empty($settings['query_filter'])){
		            $query_args = apply_filters($settings['query_filter'], $query_args);
                }
			    $post_items = new WP_Query($query_args);
		    }else{

            }

	    }

	    return [ $post_items, $query_args ];
    }

    function carousel_navigation($settings){

    }

    function pagination_markup($settings, $post_items){

        if($settings['layout_mode'] == 'carousel'){
            return;
        }

        if($settings['show_pagination'] != 'yes' && $settings['show_infinite_scroll'] == ''){
            return;
        }

        $helper = new Helper();

        $this->add_render_attribute('pagination-wrapper','class','ae-pagination-wrapper');

        ?>
        <div <?php echo $this->get_render_attribute_string('pagination-wrapper'); ?>>
            <?php
            $current = 1;
            if(isset($_POST['page_num'])){
                $current = $_POST['page_num'];
            }

            if(isset($_POST['pid'])){
                $current_post_id = $_POST['pid'];
            }else{
                $current_post_id = get_the_ID();
            }

            $base_url = get_permalink($current_post_id);

            $paginate_args = [
                'base'  => $base_url.'%_%',
                'total' => $post_items->max_num_pages,
                'current' => $current
            ];

            if($settings['ae_post_type'] == 'current_loop'){
                unset($paginate_args['base']);
                $current = get_query_var('paged');
                if($current == 0){
                    $paginate_args['current'] = 1;
                }else{
                    $paginate_args['current'] = $current;
                }
            }



            if($settings['show_prev_next'] == 'yes'){
                $paginate_args['prev_next'] = true;
                $paginate_args['prev_text'] = $settings['prev_text'];
                $paginate_args['next_text'] = $settings['next_text'];
            }else{
                $paginate_args['prev_next'] = false;
            }

            echo $helper->paginate_links($paginate_args);
            ?>
        </div>
        <?php

    }

    function ias_markup($settings){

    }


}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Blocks() );