<?php

/**
 * Get Post Data
 * @param  array $args
 * @return array
 */
function eael_get_post_data( $args ) {
    $defaults = array(
        'posts_per_page'   => 5,
        'offset'           => 0,
        'category'         => '',
        'category_name'    => '',
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'author'	       => '',
        'author_name'	   => '',
        'post_status'      => 'publish',
        'suppress_filters' => true,
        'tag__in'          => '',
        'post__not_in'     => '',
    );

    $atts = wp_parse_args( $args, $defaults );

    $posts = get_posts( $atts );

    return $posts;
}

/**
 * Get All POst Types
 * @return array
 */
function eael_get_post_types(){

    $eael_cpts = get_post_types( array( 'public'   => true, 'show_in_nav_menus' => true ) );
    $eael_exclude_cpts = array( 'elementor_library', 'attachment', 'product' );

    foreach ( $eael_exclude_cpts as $exclude_cpt ) {
        unset($eael_cpts[$exclude_cpt]);
    }

    $post_types = array_merge($eael_cpts);
    return $post_types;
}

/**
 * Add REST API support to an already registered post type.
 */
add_action( 'init', 'eael_custom_post_type_rest_support', 25 );
function eael_custom_post_type_rest_support() {
    global $wp_post_types;

    $post_types = eael_get_post_types();
    foreach( $post_types as $post_type ) {
        if( $post_type === 'post' ) : $post_type = 'posts'; endif;
        if( $post_type === 'page' ) : $post_type = 'pages'; endif;
        $post_type_name = $post_type;
        if( isset( $wp_post_types[ $post_type_name ] ) ) {
            $wp_post_types[$post_type_name]->show_in_rest = true;
            $wp_post_types[$post_type_name]->rest_base = $post_type_name;
            $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
        }
    }

}

/**
 * Post Settings Parameter
 * @param  array $settings
 * @return array
 */
function eael_get_post_settings($settings){
    $post_args['post_type'] = $settings['eael_post_type'];

    if($settings['eael_post_type'] == 'post'){
        $post_args['category'] = $settings['category'];
    }

    $eael_tiled_post_author = '';
    $eael_tiled_post_authors = $settings['eael_post_authors'];
    if ( !empty( $eael_tiled_post_authors) ) {
        $eael_tiled_post_author = implode( ",", $eael_tiled_post_authors );
    }

    $post_args['posts_per_page'] = $settings['eael_posts_count'];
    $post_args['offset'] = $settings['eael_post_offset'];
    $post_args['orderby'] = $settings['eael_post_orderby'];
    $post_args['order'] = $settings['eael_post_order'];
    $post_args['tag__in'] = $settings['eael_post_tags'];
    $post_args['post__not_in'] = $settings['eael_post_exclude_posts'];
    $post_args['author'] = $eael_tiled_post_author;

    

    return $post_args;
}


/**
 * Getting Excerpts By Post Id
 * @param  int $post_id
 * @param  int $excerpt_length
 * @return string
 */
function eael_get_excerpt_by_id($post_id,$excerpt_length){
    $the_post = get_post($post_id); //Gets post ID

    $the_excerpt = null;
    if ($the_post)
    {
        $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
    }

    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

     if(count($words) > $excerpt_length) :
         array_pop($words);
         array_push($words, '…');
         $the_excerpt = implode(' ', $words);
     endif;

     return $the_excerpt;
}

/**
 * Get Post Thumbnail Size
 * @return array
 */
function eael_get_thumbnail_sizes(){
    $sizes = get_intermediate_image_sizes();
    foreach($sizes as $s){
        $ret[$s] = $s;
    }

    return $ret;
}

/**
 * POst Orderby Options
 * @return array
 */
function eael_get_post_orderby_options(){
    $orderby = array(
        'ID' => 'Post ID',
        'author' => 'Post Author',
        'title' => 'Title',
        'date' => 'Date',
        'modified' => 'Last Modified Date',
        'parent' => 'Parent Id',
        'rand' => 'Random',
        'comment_count' => 'Comment Count',
        'menu_order' => 'Menu Order',
    );

    return $orderby;
}

/**
 * Get Post Categories
 * @return array
 */
function eael_post_type_categories(){
    $terms = get_terms( array(
        'taxonomy' => 'category',
        'hide_empty' => true,
    ));

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->term_id ] = $term->name;
        }
    }
    if( !empty($options) )
    return $options;
}

/**
 * Get Dynamic Post Categories
 * @return array
 */
function eael_all_post_type_categories( ) {
    global $wpdb;

    $results = array();

    foreach ($wpdb->get_results("
        SELECT terms.slug AS 'slug', terms.name AS 'label', termtaxonomy.taxonomy AS 'type'
        FROM $wpdb->terms AS terms
        JOIN $wpdb->term_taxonomy AS termtaxonomy ON terms.term_id = termtaxonomy.term_id
        LIMIT 100
    ") as $result) {
        $results[$result->type . ':' . $result->slug] = $result->type . ':' . $result->label;
    }
    return $results;
}

/**
 * WooCommerce Product Query
 * @return array
 */
function eael_woocommerce_product_categories(){
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
        $options[ $term->slug ] = $term->name;
    }
    return $options;
    }
}

/**
 * WooCommerce Get Product By Id
 * @return array
 */
function eael_woocommerce_product_get_product_by_id(){
    $postlist = get_posts(array(
        'post_type' => 'product',
        'showposts' => 9999,
    ));
    $posts = array();

    if ( ! empty( $postlist ) && ! is_wp_error( $postlist ) ){
    foreach ( $postlist as $post ) {
        $options[ $post->ID ] = $post->post_title;
    }
    return $options;

    }
}

/**
 * WooCommerce Get Product Category By Id
 * @return array
 */
function eael_woocommerce_product_categories_by_id(){
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
        $options[ $term->term_id ] = $term->name;
    }
    return $options;
    }

}

/**
 * Get Contact Form 7 [ if exists ]
 */
if ( function_exists( 'wpcf7' ) ) {
function eael_select_contact_form(){
    $wpcf7_form_list = get_posts(array(
        'post_type' => 'wpcf7_contact_form',
        'showposts' => 999,
    ));
    $posts = array();

    if ( ! empty( $wpcf7_form_list ) && ! is_wp_error( $wpcf7_form_list ) ){
    foreach ( $wpcf7_form_list as $post ) {
        $options[ $post->ID ] = $post->post_title;
    }
    return $options;
    }
}
}

/**
 * Get Gravity Form [ if exists ]
 */
if ( !function_exists('eael_select_gravity_form') ) {
    function eael_select_gravity_form() {
        if ( class_exists( 'GFCommon' ) ) {
            $options = array();

            $gravity_forms = RGFormsModel::get_forms( null, 'title' );

            if ( ! empty( $gravity_forms ) && ! is_wp_error( $gravity_forms ) ) {

                $i = 0;

                foreach ( $gravity_forms as $form ) {   
                    if ( $i == 0 ) {
                        $options[0] = esc_html__( 'Select Gravity Form', 'essential-addons-elementor' );
                    }
                    $options[ $form->id ] = $form->title;
                    $i++;
                }
            }
        } else {
            $options = array();
        }

        return $options;
    }
}

/**
 * Get WeForms Form List
 * @return array
 */

function eael_select_weform() {

    $wpuf_form_list = get_posts( array(
        'post_type' => 'wpuf_contact_form',
        'showposts' => 999,
    ));
    $posts = array();

    if ( ! empty( $wpuf_form_list ) && ! is_wp_error( $wpuf_form_list ) ) {
        foreach ( $wpuf_form_list as $post ) {
            $options[ $post->ID ] = $post->post_title;
        }
        return $options;
    }

}

/**
 * Get Ninja Form List
 * @return array
 */
if ( !function_exists('eael_select_ninja_form') ) {
    function eael_select_ninja_form() {
        if ( class_exists( 'Ninja_Forms' ) ) {
            $options = array();

            $contact_forms = Ninja_Forms()->form()->get_forms();

            if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

                $i = 0;

                foreach ( $contact_forms as $form ) {   
                    if ( $i == 0 ) {
                        $options[0] = esc_html__( 'Select Ninja Form', 'essential-addons-elementor' );
                    }
                    $options[ $form->get_id() ] = $form->get_setting( 'title' );
                    $i++;
                }
            }
        } else {
            $options = array();
        }

        return $options;
    }
}

/**
 * Get Caldera Form List
 * @return array
 */

if ( !function_exists('eael_select_caldera_form') ) {
    function eael_select_caldera_form() {
        if ( class_exists( 'Caldera_Forms' ) ) {
            $options = array();

            $contact_forms = Caldera_Forms_Forms::get_forms( true, true );

            if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

            $i = 0;

            foreach ( $contact_forms as $form ) {   
                if ( $i == 0 ) {
                    $options[0] = esc_html__( 'Select Caldera Form', 'essential-addons-elementor' );
                }
                $options[ $form['ID'] ] = $form['name'];
                $i++;
            }
            }
        } else {
            $options = array();
        }

        return $options;
    }
}

/**
 * Get WPForms List
 * @return array
 */
if ( !function_exists('eael_select_wpforms_forms') ) {
    function eael_select_wpforms_forms() {
        if ( class_exists( 'WPForms' ) ) {
            $options = array();

            $args = array(
                'post_type'         => 'wpforms',
                'posts_per_page'    => -1
            );

            $contact_forms = get_posts( $args );

            if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

            $i = 0;

            foreach ( $contact_forms as $post ) {   
                if ( $i == 0 ) {
                    $options[0] = esc_html__( 'Select a WPForm', 'essential-addons-elementor' );
                }
                $options[ $post->ID ] = $post->post_title;
                $i++;
            }
            }
        } else {
            $options = array();
        }

        return $options;
    }
}

/**
 *
 */
function eael_load_post_list() {
    global $post;
    $categories = explode(',', $_POST['catId']);

    $settings = array(
        'post_type' => $_POST['settings']['postType'],
        'category' => $categories,
        'posts_per_page' => $_POST['settings']['perPage'],
        'offset' => $_POST['settings']['offset']
    );
    $posts = eael_get_post_data($settings);
    $eael_list_featured_img = $_POST['settings']['listFeatureImage'];

    if(count($posts)) : $counter = 0; foreach( $posts as $post ) : setup_postdata($post); if($counter < 1) : ?>
        <div class="eael-post-list-featured-wrap">
            <div class="eael-post-list-featured-inner" style="background-image: url('<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')); ?>')">
                <div class="featured-content">
                    <?php if( $_POST['settings']['featuredPostMeta'] === 'yes' ) : ?>
                    <div class="meta">
                        <span><i class="fa fa-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author(); ?></a></span>
                        <span><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if( $_POST['settings']['featuredPostTitle'] === 'yes' ) : ?>
                        <h2 class="eael-post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php endif; ?>
                    <?php if( $_POST['settings']['featuredPostExcerpt'] === 'yes' ) : ?>
                        <p><?php echo eael_get_excerpt_by_id( get_the_ID(), $_POST['settings']['featuredExcerptLength'] ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; $counter++; endforeach; endif; ?>
    <div class="eael-post-list-posts-wrap">
        <?php if( count($posts) ) : $i = 0; foreach( $posts as $post ) : setup_postdata($post); if( $i >= 1 ) : ?>
        <div class="eael-post-list-post">
            <?php if( $eael_list_featured_img === 'yes' ) : ?>
            <div class="eael-post-list-thumbnail<?php if( empty( wp_get_attachment_image_url(get_post_thumbnail_id() ) ) ) : ?> eael-empty-thumbnail<?php endif; ?>"><?php if( !empty( wp_get_attachment_image_url(get_post_thumbnail_id() ) ) ) : ?><img src="<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')); ?>" alt="<?php the_title(); ?>"><?php endif; ?></div><?php endif; ?>
            <div class="eael-post-list-content">
                <?php if( $_POST['settings']['postTitle'] === 'yes' ) : ?>
                <h2 class="eael-post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php endif; ?>
                <?php if( $_POST['settings']['postMeta'] === 'yes' ) : ?>
                <div class="meta">
                    <span><?php echo get_the_date(); ?></span>
                </div>
                <?php endif; ?>
                <?php if( $_POST['settings']['postExcerpt'] === 'yes' ) : ?>
                    <p><?php echo eael_get_excerpt_by_id( get_the_ID(), $_POST['settings']['postExcerptLength'] ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; $i++; endforeach;endif; ?>
    </div>
    <?php
    die();
}
add_action( 'wp_ajax_load_post_list', 'eael_load_post_list' );

/**
 *
 */
function eael_load_more_post_list() {
    global $post;
    $categories = explode(',', $_POST['catId']);

    $category = get_category( $categories );
    $post_count = $category->category_count;


    $settings = array(
        'post_type' => $_POST['settings']['postType'],
        'category' => $categories,
        'posts_per_page' => $_POST['settings']['perPage'],
        'offset' => $_POST['newOffset'],
    );
    $posts = eael_get_post_data($settings);

    $eael_list_featured_img = $_POST['settings']['listFeatureImage'];

    if(count($posts)) : $counter = 0; foreach( $posts as $post ) : setup_postdata($post); if($counter < 1) : ?>
        <div class="eael-post-list-featured-wrap">
            <div class="eael-post-list-featured-inner" style="background-image: url('<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')); ?>')">
                <div class="featured-content">
                    <?php if( $_POST['settings']['featuredPostMeta'] === 'yes' ) : ?>
                    <div class="meta">
                        <span><i class="fa fa-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author(); ?></a></span>
                        <span><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if( $_POST['settings']['featuredPostTitle'] === 'yes' ) : ?>
                        <h2 class="eael-post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php endif; ?>
                    <?php if( $_POST['settings']['featuredPostExcerpt'] === 'yes' ) : ?>
                        <p><?php echo eael_get_excerpt_by_id( get_the_ID(), $_POST['settings']['featuredExcerptLength'] ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; $counter++; endforeach; endif; ?>
    <div class="eael-post-list-posts-wrap">
        <?php if( count($posts) ) : $i = 0; foreach( $posts as $post ) : setup_postdata($post); if( $i >= 1 ) : ?>
        <div class="eael-post-list-post">
            <?php if( $eael_list_featured_img === 'yes' ) : ?>
            <div class="eael-post-list-thumbnail<?php if( empty( wp_get_attachment_image_url(get_post_thumbnail_id() ) ) ) : ?> eael-empty-thumbnail<?php endif; ?>"><?php if( !empty( wp_get_attachment_image_url(get_post_thumbnail_id() ) ) ) : ?><img src="<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'full')); ?>" alt="<?php the_title(); ?>"><?php endif; ?></div><?php endif; ?>
            <div class="eael-post-list-content">
                <?php if( $_POST['settings']['postTitle'] === 'yes' ) : ?>
                <h2 class="eael-post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php endif; ?>
                <?php if( $_POST['settings']['postMeta'] === 'yes' ) : ?>
                <div class="meta">
                    <span><?php echo get_the_date(); ?></span>
                </div>
                <?php endif; ?>
                <?php if( $_POST['settings']['postExcerpt'] === 'yes' ) : ?>
                    <p><?php echo eael_get_excerpt_by_id( get_the_ID(), $_POST['settings']['postExcerptLength'] ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; $i++; endforeach;endif; ?>
    </div>
    <?php
    die();
}
add_action( 'wp_ajax_load_more_post_list', 'eael_load_more_post_list' );

function eael_get_category_post_count() {
    global $post;
    $categories = explode(',', $_POST['catId']);
    $post_count = 0;
    foreach( $categories as $cat ) {
        $category = get_category( $cat );
        $post_count = $post_count + $category->category_count;
    }

    $return_array = array(
        'post_count' => $post_count,
        'cat' => $categories
    );

    wp_send_json( $return_array );
    die();
}
add_action( 'wp_ajax_get_category_post_count', 'eael_get_category_post_count' );


// Get all elementor page templates
if ( !function_exists('eael_get_page_templates') ) {
    function eael_get_page_templates(){
        $page_templates = get_posts( array(
            'post_type'         => 'elementor_library',
            'posts_per_page'    => -1
        ));

        $options = array();

        if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ){
            foreach ( $page_templates as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        }
        return $options;
    }
}

// Get all Authors
if ( !function_exists('eael_get_authors') ) {
    function eael_get_authors() {

        $options = array();

        $users = get_users();

        foreach ( $users as $user ) {
            $options[ $user->ID ] = $user->display_name;
        }

        return $options;
    }
}

// Get all Authors
if ( !function_exists('eael_get_tags') ) {
    function eael_get_tags() {

        $options = array();

        $tags = get_tags();

        foreach ( $tags as $tag ) {
            $options[ $tag->term_id ] = $tag->name;
        }

        return $options;
    }
}

// Get all Posts
if ( !function_exists('eael_get_posts') ) {
    function eael_get_posts() {

        $post_list = get_posts( array(
            'post_type'         => 'post',
            'orderby'           => 'date',
            'order'             => 'DESC',
            'posts_per_page'    => -1,
        ) );

        $posts = array();

        if ( ! empty( $post_list ) && ! is_wp_error( $post_list ) ) {
            foreach ( $post_list as $post ) {
               $posts[ $post->ID ] = $post->post_title;
            }
        }

        return $posts;
    }
}