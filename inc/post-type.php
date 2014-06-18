<?php
/**
 * This file handles everything with the clean_event post type
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    Clean Events
 * @author     Jeff Hays (jphase) <jeff@robido.com>
 */

function events_post_type() {

	$labels = array(
		'name'                => _x( 'Events', 'Clean Events', 'clean_events' ),
		'singular_name'       => _x( 'Event', 'Clean Event', 'clean_events' ),
		'menu_name'           => __( 'Events', 'clean_events' ),
		'parent_item_colon'   => __( 'Parent Event:', 'clean_events' ),
		'all_items'           => __( 'All Events', 'clean_events' ),
		'view_item'           => __( 'View Event', 'clean_events' ),
		'add_new_item'        => __( 'Add New Event', 'clean_events' ),
		'add_new'             => __( 'Add New', 'clean_events' ),
		'edit_item'           => __( 'Edit Event', 'clean_events' ),
		'update_item'         => __( 'Update Event', 'clean_events' ),
		'search_items'        => __( 'Search Events', 'clean_events' ),
		'not_found'           => __( 'Not found', 'clean_events' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'clean_events' ),
	);
	$args = array(
		'label'               => __( 'clean_event', 'clean_events' ),
		'description'         => __( 'Clean Events', 'clean_events' ),
		'labels'              => $labels,
		'supports'            => array( ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'clean_event', $args );

}

// Hook into the init action
add_action( 'init', 'events_post_type', 0 );