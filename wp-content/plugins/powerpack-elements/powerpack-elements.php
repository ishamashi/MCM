<?php
/**
 * Plugin Name: PowerPack Elements
 * Plugin URI: https://powerpackelements.com
 * Description: Custom addons for elementor page builder.
 * Version: 1.3.3
 * Author: Team IdeaBox - PowerPack Elements
 * Author URI: http://powerpackelements.com
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: power-pack
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'POWERPACK_ELEMENTS_VER', '1.3.3' );
define( 'POWERPACK_ELEMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'POWERPACK_ELEMENTS_BASE', plugin_basename( __FILE__ ) );
define( 'POWERPACK_ELEMENTS_URL', plugins_url( '/', __FILE__ ) );
define( 'POWERPACK_ELEMENTS_ELEMENTOR_VERSION_REQUIRED', '1.7' );
define( 'POWERPACK_ELEMENTS_PHP_VERSION_REQUIRED', '5.4' );

require_once POWERPACK_ELEMENTS_PATH . 'includes/helper-functions.php';
require_once POWERPACK_ELEMENTS_PATH . 'classes/class-pp-admin-settings.php';
require_once POWERPACK_ELEMENTS_PATH . 'classes/class-pp-wpml.php';
require_once POWERPACK_ELEMENTS_PATH . 'includes/updater/update-config.php';

/**
 * Check if Elementor is installed
 *
 * @since 1.0
 *
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {
	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $file_path ] );
	}
}

/**
 * Shows notice to user if Elementor plugin
 * is not installed or activated or both
 *
 * @since 1.0
 *
 */
function pa_fail_load() {
    $plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
        $message = __( 'PowerPack requires Elementor plugin to be active. Please activate Elementor to continue.', 'power-pack' );
		$button_text = __( 'Activate Elementor', 'power-pack' );

	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
        $message = sprintf( __( 'PowerPack requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', 'power-pack' ), '<strong>', '</strong>' );
		$button_text = __( 'Install Elementor', 'power-pack' );
	}

	$button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
    
    printf( '<div class="error"><p>%1$s</p>%2$s</div>', esc_html( $message ), $button );
}

/**
 * Shows notice to user if
 * Elementor version if outdated
 *
 * @since 1.0
 *
 */
function pa_fail_load_out_of_date() {
    if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}
    
	$message = __( 'PowerPack requires Elementor version at least ' . POWERPACK_ELEMENTS_ELEMENTOR_VERSION_REQUIRED . '. Please update Elementor to continue.', 'power-pack' );

	printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );
}

/**
 * Shows notice to user if minimum PHP
 * version requirement is not met
 *
 * @since 1.0
 *
 */
function pa_fail_php() {
	$message = __( 'PowerPack requires PHP version ' . POWERPACK_ELEMENTS_PHP_VERSION_REQUIRED .'+ to work properly. The plugins is deactivated for now.', 'power-pack' );

	printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );

	if ( isset( $_GET['activate'] ) ) 
		unset( $_GET['activate'] );
}

/**
 * Deactivates the plugin
 *
 * @since 1.0
 */
function pa_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Load theme textdomain
 *
 * @since 1.0
 *
 */
function pp_load_plugin_textdomain() {
	load_plugin_textdomain( 'power-pack', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * Assigns category to PowerPack
 *
 * @since 1.0
 *
 */
function pp_category() {
	\Elementor\Plugin::instance()->elements_manager->add_category(
        'power-pack',
        array(
            'title' => PP_Admin_Settings::get_admin_label(),
            'icon'  => 'font',
        ),
	    1 );
}

/**
 * Include widgets
 *
 * @since 1.0
 *
 */
function pp_add_elements() {
    
    $enabled_modules = pp_get_enabled_modules();

	foreach ( $enabled_modules as $module ) {
		$module_file = POWERPACK_ELEMENTS_PATH . 'includes/widgets/'.$module.'.php';
		if ( file_exists( $module_file ) ) {
			require_once $module_file;
		}
	}
}

/**
 * Enqueue scripts and styles
 */
function pp_elements_scripts() {

	$settings = PP_Admin_Settings::get_settings();

    wp_enqueue_style( 'powerpack', POWERPACK_ELEMENTS_URL . 'assets/css/frontend.css', '', POWERPACK_ELEMENTS_VER );
    
    wp_enqueue_style( 'tablesaw', POWERPACK_ELEMENTS_URL . 'assets/css/tablesaw.css' );
    
    wp_enqueue_style( 'animate', POWERPACK_ELEMENTS_URL . 'assets/css/animate.css' );
    
    wp_enqueue_style( 'tipso', POWERPACK_ELEMENTS_URL . 'assets/css/tipso.css' );
    
    wp_enqueue_style( 'odometer', POWERPACK_ELEMENTS_URL . 'assets/css/odometer-theme-default.css' );
    
	wp_enqueue_style( 'mmenu', POWERPACK_ELEMENTS_URL . 'assets/css/mmenu.css' );

    if ( class_exists( 'GFCommon' ) ) {
        foreach( pp_get_gravity_forms() as $form_id => $form_name ){
            if ( $form_id != '0' ) {
                gravity_form_enqueue_scripts( $form_id );
            }
        };
    }
    
    if ( function_exists( 'wpforms' ) ) {
        wpforms()->frontend->assets_css();
    }
    
    wp_register_script( 'instafeed', POWERPACK_ELEMENTS_URL . 'assets/js/instafeed.min.js', array( 'jquery' ), '1.4.1', true );

    wp_register_script( 'tipso', POWERPACK_ELEMENTS_URL . 'assets/js/tipso.js', array( 'jquery' ), '1.0.8', true );
    
    wp_register_script( 'jquery-event-move', POWERPACK_ELEMENTS_URL . 'assets/js/jquery.event.move.js', array( 'jquery' ), '2.0.0', true );

    wp_register_script( 'twentytwenty', POWERPACK_ELEMENTS_URL . 'assets/js/jquery.twentytwenty.js', array( 'jquery' ), '1.0.', true );
    
    wp_register_script( 'magnific-popup', POWERPACK_ELEMENTS_URL . 'assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), '2.2.1', true );
    
    wp_register_script( 'jquery-cookie', POWERPACK_ELEMENTS_URL . 'assets/js/jquery.cookie.js', array( 'jquery' ), '1.4.1', true );
    
    wp_register_script( 'waypoints', POWERPACK_ELEMENTS_URL . 'assets/js/waypoints.min.js', array( 'jquery' ), '4.0.1', true );
    
	wp_register_script( 'odometer', POWERPACK_ELEMENTS_URL . 'assets/js/odometer.min.js', array( 'jquery' ), '0.4.8', true );
    
	wp_register_script( 'jquery-powerpack-dot-nav', POWERPACK_ELEMENTS_URL . 'assets/js/one-page-nav.js', array( 'jquery' ), '1.0.0', true );
	
	if ( isset( $settings['google_map_api'] ) && ! empty( $settings['google_map_api'] ) ) {
		wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $settings['google_map_api'], '', rand() );
	} else {
		wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js', '', rand() );
	}

    wp_register_script( 'pp-jquery-plugin', POWERPACK_ELEMENTS_URL . 'assets/js/jquery.plugin.js', array( 'jquery' ), '1.0.0', true );
    
    wp_register_script( 'jquery-countdown', POWERPACK_ELEMENTS_URL . 'assets/js/jquery.countdown.js', array( 'jquery' ), '2.0.2', true );
    
    wp_register_script( 'pp-frontend-countdown', POWERPACK_ELEMENTS_URL . 'assets/js/frontend-countdown.js', array( 'jquery' ), '1.0.0', true );

	wp_register_script( 'smartmenu', POWERPACK_ELEMENTS_URL . 'assets/js/jquery-smartmenu.js', array( 'jquery' ), '1.0.1', true );
	wp_register_script( 'pp-advanced-menu', POWERPACK_ELEMENTS_URL . 'assets/js/frontend-advanced-menu.js', array( 'jquery' ), POWERPACK_ELEMENTS_VER, true );
	wp_register_script( 'scotchpanel', POWERPACK_ELEMENTS_URL . 'assets/js/scotchPanels.js', array( 'jquery' ), POWERPACK_ELEMENTS_VER, true );
	wp_register_script( 'mmenu', POWERPACK_ELEMENTS_URL . 'assets/js/mmenu.js', array( 'jquery' ), POWERPACK_ELEMENTS_VER, true );

    wp_register_script( 'tablesaw', POWERPACK_ELEMENTS_URL . 'assets/lib/tablesaw/tablesaw.jquery.js', array( 'jquery' ), '3.0.3', true );
    
    wp_register_script( 'tablesaw-init', POWERPACK_ELEMENTS_URL . 'assets/lib/tablesaw/tablesaw-init.js', array( 'jquery' ), '3.0.3', true );
    
    wp_register_script( 'pp-scripts', POWERPACK_ELEMENTS_URL . 'assets/js/pp-scripts.js', array( 'jquery' ), POWERPACK_ELEMENTS_VER, true );
}

add_action( 'plugins_loaded', 'pp_init' );

function pp_init() {
    if ( class_exists( 'Caldera_Forms' ) ) {
        add_filter( 'caldera_forms_force_enqueue_styles_early', '__return_true' );
    }

    // Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'pa_fail_load' );
		return;
	}

	// Check for required Elementor version
	if ( ! version_compare( ELEMENTOR_VERSION, POWERPACK_ELEMENTS_ELEMENTOR_VERSION_REQUIRED, '>=' ) ) {
		add_action( 'admin_notices', 'pa_fail_load_out_of_date' );
		add_action( 'admin_init', 'pa_deactivate' );
		return;
	}
    
    // Check for required PHP version
	if ( ! version_compare( PHP_VERSION, POWERPACK_ELEMENTS_PHP_VERSION_REQUIRED, '>=' ) ) {
		add_action( 'admin_notices', 'pa_fail_php' );
		add_action( 'admin_init', 'pa_deactivate' );
		return;
	}
    
    add_action( 'init', 'pp_load_plugin_textdomain' );

	add_action( 'elementor/init', 'pp_category' );
    
    add_action( 'elementor/widgets/widgets_registered', 'pp_add_elements' );
    
    add_action( 'wp_enqueue_scripts', 'pp_elements_scripts' );
}

add_action( 'elementor/editor/before_enqueue_scripts', function() {
    wp_enqueue_style( 'powerpack-elements-admin-styles', POWERPACK_ELEMENTS_URL . 'assets/css/backend.css' );
    wp_enqueue_script( 'pp-editor', POWERPACK_ELEMENTS_URL . 'assets/js/editor.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'magnific-popup', POWERPACK_ELEMENTS_URL . 'assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), '2.2.1', true );
} );

/**
 * Enable white labeling setting form after re-activating the plugin
 *
 * @since 1.0.1
 * @return void
 */
function pp_plugin_activation()
{
	$settings = get_site_option( 'pp_elementor_settings' );
	
	if ( is_array( $settings ) ) {
		$settings['hide_wl_settings'] = 'off';
		$settings['hide_plugin'] = 'off';
	}

	update_site_option( 'pp_elementor_settings', $settings );
}
register_activation_hook( __FILE__, 'pp_plugin_activation' );