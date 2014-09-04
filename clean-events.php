<?php
/**
 * Plugin Name: Clean Events
 * Plugin URI: http://robido.com/clean-events
 * Description: This is an event calendar plugin for the appreciators of simple and fast code with minimalist design concepts. It uses native WP tables and is designed specifically for those who hate bloatware and bad design.
 * Version: 1.0.0
 * Author: robido
 * Author URI: http://robido.com/blog
 * License: GPLv2 or later
 * Text Domain: clean_events
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    Clean Events
 * @author     Jeff Hays (jphase) <jeff@robido.com>
 */

// Define namespace
namespace CleanEvents;

// Block direct access
if ( ! defined('ABSPATH') ) {
	die( 'Sorry, no script kiddies allowed...' );
}

// Define constants
define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );
define( NS . 'URL', plugin_dir_url(__FILE__) );
define( NS . 'PATH', plugin_dir_path(__FILE__) );
define( NS . 'VERSION', '1.0.0' );

// Check for required version of PHP
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	if ( is_admin() && ( ! defined('DOING_AJAX') || ! DOING_AJAX ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
		wp_die( __( 'Clean events requires PHP 5.3 or higher. You currently have PHP ' . PHP_VERSION . ' installed. <a href="' . admin_url('plugins.php') . '">&laquo; Go back to plugins page</a>', 'clean_events' ) );
	}
}

// Autoload classes and includes
function ce_autoload() {
	$init = glob( dirname ( __FILE__ ) . '/inc/*.php' );
	$classes = glob( dirname( __FILE__ ) . '/classes/*.php' );
	foreach ( $init as $script ) require_once $script;
	foreach ( $classes as $class ) require_once $class;
}

// Initialize clean events plugin
function ce_init() {
	ce_autoload();
	$admin = new Admin;
	$widget = new Widget;
}

// Call bootstrap after plugins are loaded
add_action( 'plugins_loaded', __NAMESPACE__ . '\ce_init', 10, 0 );
