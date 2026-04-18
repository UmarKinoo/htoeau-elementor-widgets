<?php
/**
 * Plugin Name: HtoEAU Elementor Widgets
 * Description: Custom Elementor widgets for HtoEAU — hydrogen-infused water brand.
 * Version:     1.6.97
 * Author:      HtoEAU
 * Text Domain: htoeau-widgets
 * Requires PHP: 7.4
 * Elementor tested up to: 3.25
 * Elementor Pro tested up to: 3.25
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HTOEAU_WIDGETS_VERSION', '1.6.97' );
define( 'HTOEAU_WIDGETS_FILE', __FILE__ );
define( 'HTOEAU_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'HTOEAU_WIDGETS_URL', plugin_dir_url( __FILE__ ) );

require_once HTOEAU_WIDGETS_PATH . 'includes/helpers.php';
require_once HTOEAU_WIDGETS_PATH . 'includes/class-plugin.php';

\HtoEAU_Widgets\Plugin::instance();
