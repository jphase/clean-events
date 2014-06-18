<?php
/**
 * This file handles all admin settings in the clean_event post type
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    Clean Events
 * @author     Jeff Hays (jphase) <jeff@robido.com>
 */

// Render event details section on custom post type
function ce_event_details() {

	// Only add our event details box on the clean_event post type
	global $post;
	if($post->post_type != 'clean_event') return;

	// Enqueue styles
	wp_enqueue_style( 'clean-events-admin', CE_URL . 'css/clean.events.admin.css', false, '1.0');
	wp_enqueue_style( 'datetime-picker', CE_URL . 'css/jquery.datetimepicker.css', false, '2.2.9');

	// Enqueue scripts
	wp_enqueue_script( 'datetime-picker', CE_URL . 'js/jquery.datetimepicker.js', array( 'jquery' ), '2.2.9', true );
	wp_enqueue_script( 'masked-input', CE_URL . 'js/jquery.maskedinput.min.js', array( 'jquery' ), '1.3.1', true );
	wp_enqueue_script( 'clean-events-admin', CE_URL . 'js/clean.events.admin.js', array( 'jquery', 'datetime-picker', 'masked-input' ), '1.0', true );

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'ce_event_details', 'ce_event_details_nonce' );
?>

	<div class="meta-box-sortables">
		<div id="event-details" class="postbox">
			<div class="handlediv" title="Click to toggle"><br></div>
			<h3 class="hndle"><span><?php echo __( 'Event Details' ); ?></span></h3>
			<div class="inside">
				<h4 class="pointer open">Event Date and Time <div class="dashicons dashicons-arrow-down right"></div></h4>
				<div class="section">
					<label for="ce_start"><?php echo __( 'Event Start:', 'clean_events' ); ?></label>
					<input type="text" id="ce_start" name="ce_start" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_start', true ) ); ?>" class="datetime" size="35">
					<label for="ce_end"><?php echo __( 'Event End:', 'clean_events' ); ?></label>
					<input type="text" id="ce_end" name="ce_end" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_end', true ) ); ?>" class="datetime" size="35">
					<label for="ce_cost"><?php echo __( 'Event Cost:', 'clean_events' ); ?></label>
					<input type="text" id="ce_cost" name="ce_cost" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_cost', true ) ); ?>" size="35">
				</div>
				<h4 class="pointer">Something Else  <div class="dashicons dashicons-arrow-up right"></div></h4>
				<div class="section hide">
					OMG WTF BBQ
				</div>
			</div>
		</div>
	</div>

<?php
}

// Add event settings after title area
add_action( 'edit_form_after_title', 'ce_event_details' );

// Save event details section on custom post type
function ce_event_details_save( $post_id ) {

	// Return if our nonce isn't set, isn't valid, or this is an autosave
	if ( !isset( $_POST['ce_event_details_nonce'] ) || !wp_verify_nonce( $_POST['ce_event_details_nonce'], 'ce_event_details' ) || (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) ) return;

	// Return if the user can't edit posts
	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	// Update the meta for this post
	update_post_meta( $post_id, '_ce_cost', sanitize_text_field( $_POST['ce_cost'] ) );

}

// Call event settings save function on save
add_action( 'save_post', 'ce_event_details_save' );