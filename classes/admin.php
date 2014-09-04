<?php
/**
 * This class handles all things in the admin panel. Custom post type,
 * settings submenu pages, filters, actions, etc.
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    Clean Events
 * @author     Jeff Hays (jphase) <jeff@robido.com>
 */

namespace CleanEvents;

class Admin {

	// Add all actions in our admin class
	function __construct() {
		// Add settings pages
		\add_action( 'admin_menu', array( $this, 'settings_pages' ) );
		// Call event settings save function on save
		\add_action( 'save_post', array( $this, 'event_details_save' ) );
		// Add event settings after title area
		\add_action( 'edit_form_after_title', array( $this, 'event_details' ) );
		// Register post type
		\add_action( 'init', array( $this, 'events_post_type' ), 0 );
	}

	// Add submenu pages
	function settings_pages() {
		\add_submenu_page( 'edit.php?post_type=clean_event', 'settings', 'Settings', 'manage_options', 'event_settings', array( $this, 'display_settings' ) );
	}

	// Display settings on custom submenu page
	function display_settings() {

		// Enqueue styles
		\wp_enqueue_style( 'clean-events-admin', \CleanEvents\URL . 'css/admin.css', false, \CleanEvents\VERSION );

		// Save our settings as needed
		if ( $_POST ) $this->save_settings();

?>
		<form method="post">
			<input type="hidden" name="ce_nonce" value="<?php echo \wp_create_nonce( 'ce_event_settings' ); ?>">
			<div class="metabox-holder wrap">
				<div class="meta-box-sortables">
					<div class="post-box-container">
						<div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3><div class="dashicons dashicons-calendar"></div> <?php echo __( 'Event Settings', 'clean_events' ); ?></h3>
							<div class="inside">
								<h4><?php echo __( 'Date and Time Picker Options', 'clean_events' ); ?></h4>
								<table>
									<tbody>
										<tr>
											<td><label for="ce_12_hour"><?php echo __( 'Hour type:', 'clean_events' ); ?></label></td>
											<td>
												<select id="ce_12_hour" name="ce_12_hour">
													<option value="1"<?php \selected( \get_option( 'clean_events_12_hour' ) ); ?>><?php echo __( '12 hour', 'clean_events' ); ?></option>
													<option value="0"<?php \selected( \get_option( 'clean_events_12_hour' ), 0 ); ?>><?php echo __( '24 hour', 'clean_events' ); ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<td><label for="ce_time_format"><?php echo __( 'Time format:', 'clean_events' ); ?></label></td>
											<td><input type="text" id="ce_time_format" name="ce_time_format" value="<?php echo strlen( \get_option( 'clean_events_time_format' ) ) ? \get_option( 'clean_events_time_format' ) : 'g:ia'; ?>"></td>
										</tr>
										<tr>
											<td><label for="ce_time_step"><?php echo __( 'Time step:', 'clean_events' ); ?></label></td>
											<td><input type="text" id="ce_time_step" name="ce_time_step" value="<?php echo strlen( \get_option( 'clean_events_time_step' ) ) ? \get_option( 'clean_events_time_step' ) : '30'; ?>"></td>
										</tr>
										<tr>
											<td><label for="ce_date_format"><?php echo __( 'Date format:', 'clean_events' ); ?></label></td>
											<td><input type="text" id="ce_date_format" name="ce_date_format" value="<?php echo strlen( \get_option( 'clean_events_date_format' ) ) ? \get_option( 'clean_events_date_format' ) : 'n/j/Y'; ?>"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="post-box-container">
						<div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3><div class="dashicons dashicons-clipboard"></div> <?php echo __( 'General Settings', 'clean_events' ); ?></h3>
						</div>
					</div>

					<input type="submit" class="button button-primary button-large" value="<?php echo __( 'Save Settings', 'clean_events' ); ?>">
				</div>
			</div>
		</form>

<?php
	}

	// Save settings on custom submenu page
	function save_settings() {

		// Return if our nonce isn't set, isn't valid, or this is an autosave
		if ( ! isset( $_POST['ce_nonce'] ) || ! \wp_verify_nonce( $_POST['ce_nonce'], 'ce_event_settings' ) ) return;

		// Return if the user can't edit posts
		if ( ! \current_user_can( 'edit_post', $post_id ) ) return;

		// Update our settings
		\update_option( 'clean_events_12_hour', \esc_attr( $_POST['ce_12_hour'] ) );
		\update_option( 'clean_events_time_format', \esc_attr( $_POST['ce_time_format'] ) );
		\update_option( 'clean_events_time_step', \esc_attr( $_POST['ce_time_step'] ) );
		\update_option( 'clean_events_date_format', \esc_attr( $_POST['ce_date_format'] ) );
		\update_option( 'clean_events_date_object', \esc_attr( $_POST['ce_date_object'] ) );

	}

	// Render event details section on custom post type
	function event_details() {

		// Only add our event details box on the clean_event post type
		global $post;
		if ( $post->post_type != 'clean_event' ) return;

		// Enqueue styles
		\wp_enqueue_style( 'clean-events-admin', \CleanEvents\URL . 'css/admin.css', false, '1.0');
		\wp_enqueue_style( 'datetime-picker', \CleanEvents\URL . 'css/jquery.datetimepicker.css', false, '2.2.9');

		// Enqueue scripts
		\wp_enqueue_script( 'jquery-ui-core' );
		\wp_enqueue_script( 'jquery-ui-autocomplete' );
		\wp_enqueue_script( 'datetime-picker', \CleanEvents\URL . 'js/jquery.datetimepicker.js', array( 'jquery' ), '2.2.9', true );
		\wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=places', false, false, true );
		\wp_enqueue_script( 'clean-events-admin', \CleanEvents\URL . 'js/admin.js', array( 'jquery', 'datetime-picker', 'google-maps' ), '1.0', true );

		// Localize scripts
		\wp_localize_script( 'clean-events-admin', 'settings', $this->get_js_settings() );

		// Add an nonce field so we can check for it later.
		\wp_nonce_field( 'ce_event_details', 'ce_event_details_nonce' );
	?>

		<div class="meta-box-sortables">
			<div id="event-details" class="postbox">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3><span><?php echo __( 'Event Details' ); ?></span></h3>
				<div class="inside">
					<h4 class="pointer open"><div class="dashicons dashicons-clock"></div> <?php echo __( 'Date and Time', 'clean_events' ); ?> <div class="dashicons dashicons-arrow-down right"></div></h4>
					<div class="section">
						<table>
							<tbody>
								<tr>
									<td><label for="ce_all_day"><?php echo __( 'All day event:', 'clean_events' ); ?></label></td>
									<td><input type="checkbox" id="ce_all_day" name="ce_all_day" <?php \checked( 'on', \get_post_meta( $post->ID, '_ce_all_day' )[0] ); ?>></td>
								</tr>
								<tr>
									<td><label for="ce_start_date"><?php echo __( 'Start date / time:', 'clean_events' ); ?></label></td>
									<td>
										<input type="text" id="ce_start_date" name="ce_start_date" value="<?php echo \esc_attr( \get_post_meta( $post->ID, '_ce_start_date', true ) ); ?>" class="datepicker" placeholder="Start Date">
										<input type="text" id="ce_start_time" name="ce_start_time" value="<?php echo \esc_attr( \get_post_meta( $post->ID, '_ce_start_time', true ) ); ?>" class="timepicker" placeholder="Start Time">
									</td>
								</tr>
								<tr>
									<td><label for="ce_end_date"><?php echo __( 'End date / time:', 'clean_events' ); ?></label></td>
									<td>
										<input type="text" id="ce_end_date" name="ce_end_date" value="<?php echo \esc_attr( \get_post_meta( $post->ID, '_ce_end_date', true ) ); ?>" class="datepicker" placeholder="End Date">
										<input type="text" id="ce_end_time" name="ce_end_time" value="<?php echo \esc_attr( \get_post_meta( $post->ID, '_ce_end_time', true ) ); ?>" class="timepicker" placeholder="End Time">
									</td>
								</tr>
								<tr>
									<td><label for="ce_cost"><?php echo __( 'Event Cost:', 'clean_events' ); ?></label></td>
									<td><input type="text" id="ce_cost" name="ce_cost" value="<?php echo \esc_attr( \get_post_meta( $post->ID, '_ce_cost', true ) ); ?>"></td>
								</tr>
							</tbody>
						</table>
					</div>
					<h4 class="pointer"><div class="dashicons dashicons-location"></div> <?php echo __( 'Location', 'clean_events' ); ?> <div class="dashicons dashicons-arrow-up right"></div></h4>
					<div class="section hide">
						<table>
							<tbody>
								<tr>
									<td><label for="ce_venue_name"><?php echo __( 'Venue Name:', 'clean_events' ); ?></label></td>
									<td><input type="text" id="ce_venue_name" name="ce_venue_name" value="<?php echo \esc_attr( \get_post_meta( $post->ID, '_ce_venue_name', true ) ); ?>"></td>
								</tr>
								<tr>
									<td><label for="ce_venue_location"><?php echo __( 'Venue Location:', 'clean_events' ); ?></label></td>
									<td><input type="text" id="ce_venue_location" name="ce_venue_location" value="<?php echo \esc_attr( \get_post_meta( $post->ID, '_ce_venue_location', true ) ); ?>"></td>
								</tr>
							</tbody>
						</table>
						<div id="map"></div>
					</div>
					<h4 class="pointer"><div class="dashicons dashicons-tickets"></div> <?php echo __( 'Cost and Tickets', 'clean_events' ); ?> <div class="dashicons dashicons-arrow-up right"></div></h4>
					<div class="section hide">
						OMG WTF BBQ
					</div>
					<h4 class="pointer"><div class="dashicons dashicons-megaphone"></div> <?php echo __( 'Contact Information', 'clean_events' ); ?> <div class="dashicons dashicons-arrow-up right"></div></h4>
					<div class="section hide">
						OMG WTF BBQ
					</div>
				</div>
			</div>
		</div>

	<?php
	}

	// Save event details section on custom post type
	function event_details_save( $post_id ) {

		// Return if our nonce isn't set, isn't valid, or this is an autosave
		if ( ! isset( $_POST['ce_event_details_nonce'] ) || ! \wp_verify_nonce( $_POST['ce_event_details_nonce'], 'ce_event_details' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) return;

		// Return if the user can't edit posts
		if ( ! \current_user_can( 'edit_post', $post_id ) ) return;

		// Update the meta for this post
		\update_post_meta( $post_id, '_ce_all_day', \sanitize_text_field( $_POST['ce_all_day'] ) );
		\update_post_meta( $post_id, '_ce_start_date', \sanitize_text_field( $_POST['ce_start_date'] ) );
		\update_post_meta( $post_id, '_ce_start_time', \sanitize_text_field( $_POST['ce_start_time'] ) );
		\update_post_meta( $post_id, '_ce_end_date', \sanitize_text_field( $_POST['ce_end_date'] ) );
		\update_post_meta( $post_id, '_ce_end_time', \sanitize_text_field( $_POST['ce_end_time'] ) );
		\update_post_meta( $post_id, '_ce_cost', \sanitize_text_field( $_POST['ce_cost'] ) );
		\update_post_meta( $post_id, '_ce_venue_name', \sanitize_text_field( $_POST['ce_venue_name'] ) );
		\update_post_meta( $post_id, '_ce_venue_location', \sanitize_text_field( $_POST['ce_venue_location'] ) );

	}

	// Build object of settings needed in JS
	function get_js_settings() {

		// JS object
		$obj = new \stdClass;

		// Time object
		$obj->time = new \stdClass;
		$obj->time->datepicker = false;
		$obj->time->hours12 = (bool) \get_option( 'clean_events_12_hour' );
		$obj->time->format = \get_option( 'clean_events_time_format' );
		$obj->time->formatTime = \get_option( 'clean_events_time_format' );
		$obj->time->step = (int) \get_option( 'clean_events_time_step' );

		// Apply a ct_time_picker filter to our time settings for developers
		$obj->time = \apply_filters( 'ce_time_picker', $obj->time );

		// Date object
		$obj->date = new \stdClass;
		$obj->date->timepicker = false;
		$obj->date->format = \get_option( 'clean_events_date_format' );

		// Apply a ct_date_picker filter to our date settings for developers
		$obj->date = \apply_filters( 'ce_date_picker', $obj->date );

		return $obj;

	}

	// Register the clean_event post type
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

		\register_post_type( 'clean_event', $args );

	}

}
