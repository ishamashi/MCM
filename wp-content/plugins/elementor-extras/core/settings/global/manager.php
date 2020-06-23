<?php
namespace ElementorExtras\Core\Settings\General;

use Elementor\Controls_Manager;
use Elementor\Core\Settings\General\Manager as GeneralManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Manager extends GeneralManager {

	const PANEL_TAB_SETTINGS = 'settings';

	const META_KEY = '_elementor_extras_general_settings';

	/**
	 * @since 1.8.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		$this->add_panel_tabs();
	}

	/**
	 * @since 1.8.0
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'extras';
	}

	/**
	 * @since 1.8.0
	 * @access private
	 */
	private function add_panel_tabs() {
		Controls_Manager::add_tab( self::PANEL_TAB_SETTINGS, __( 'Settings', 'elementor-extras' ) );
	}
}
