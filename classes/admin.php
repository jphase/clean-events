<?php
/**
 * This is currently a placeholder class... which will eventually handle admin settings
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    Clean Events
 * @author     Jeff Hays (jphase) <jeff@robido.com>
 */

namespace CleanEvents\Admin;

class Settings {

	function __construct() {
		\add_action( 'admin_init', array( &$this, 'settings_pages' ) );
	}

	function settings_pages() {
		\add_submenu_page( 'edit.php?post_type=clean_event', 'settings', 'Settings', 'manage_options', 'event_settings', array( &$this, 'display_settings' ) );
	}

	function display_settings() {
		echo 'oh herro!';
	}

}