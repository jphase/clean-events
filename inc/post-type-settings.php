<?php
/**
 * This file handles all post type settings in the clean_event post type
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    Clean Events
 * @author     Jeff Hays (jphase) <jeff@robido.com>
 */

// Define namespace
namespace CleanEvents;

// Render event details section on custom post type
function ce_event_details() {

	// Only add our event details box on the clean_event post type
	global $post;
	if($post->post_type != 'clean_event') return;

	// Enqueue styles
	wp_enqueue_style( 'clean-events-admin', constant( NS . 'URL' ) . 'css/clean.events.admin.css', false, '1.0');
	wp_enqueue_style( 'datetime-picker', constant( NS . 'URL' ) . 'css/jquery.datetimepicker.css', false, '2.2.9');

	// Enqueue scripts
	wp_enqueue_script( 'datetime-picker', constant( NS . 'URL' ) . 'js/jquery.datetimepicker.js', array( 'jquery' ), '2.2.9', true );
	wp_enqueue_script( 'masked-input', constant( NS . 'URL' ) . 'js/jquery.maskedinput.min.js', array( 'jquery' ), '1.3.1', true );
	wp_enqueue_script( 'clean-events-admin', constant( NS . 'URL' ) . 'js/clean.events.admin.js', array( 'jquery', 'datetime-picker', 'masked-input' ), '1.0', true );

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'ce_event_details', 'ce_event_details_nonce' );
?>

	<div class="meta-box-sortables">
		<div id="event-details" class="postbox">
			<div class="handlediv" title="Click to toggle"><br></div>
			<h3 class="hndle"><span><?php echo __( 'Event Details' ); ?></span></h3>
			<div class="inside">
				<h4 class="pointer open"><div class="dashicons dashicons-clock"></div> Date and Time <div class="dashicons dashicons-arrow-down right"></div></h4>
				<div class="section">
					<table>
						<tbody>
							<tr>
								<td><label for="ce_all_day"><?php echo __( 'All day event:', 'clean_events' ); ?></label></td>
								<td><input type="checkbox" id="ce_all_day" name="ce_all_day" <?php checked( 'on', get_post_meta( $post->ID, '_ce_all_day' )[0] ); ?>></td>
							</tr>
							<tr>
								<td><label for="ce_start_date"><?php echo __( 'Start date / time:', 'clean_events' ); ?></label></td>
								<td>
									<input type="text" id="ce_start_date" name="ce_start_date" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_start_date', true ) ); ?>" class="datepicker">
									<input type="text" id="ce_start_time" name="ce_start_time" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_start_time', true ) ); ?>" class="timepicker">
								</td>
							</tr>
							<tr>
								<td><label for="ce_end_date"><?php echo __( 'End date / time:', 'clean_events' ); ?></label></td>
								<td>
									<input type="text" id="ce_end_date" name="ce_end_date" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_end_date', true ) ); ?>" class="datepicker">
									<input type="text" id="ce_end_time" name="ce_end_time" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_end_time', true ) ); ?>" class="timepicker">
								</td>
							</tr>
							<tr>
								<td><label for="ce_cost"><?php echo __( 'Event Cost:', 'clean_events' ); ?></label></td>
								<td><input type="text" id="ce_cost" name="ce_cost" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ce_cost', true ) ); ?>"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<h4 class="pointer"><div class="dashicons dashicons-location"></div> Location <div class="dashicons dashicons-arrow-up right"></div></h4>
				<div class="section hide">
					OMG WTF BBQ
				</div>
				<h4 class="pointer"><div class="dashicons dashicons-tickets"></div> Cost and Tickets <div class="dashicons dashicons-arrow-up right"></div></h4>
				<div class="section hide">
					OMG WTF BBQ
				</div>
				<h4 class="pointer"><div class="dashicons dashicons-megaphone"></div> Contact Information <div class="dashicons dashicons-arrow-up right"></div></h4>
				<div class="section hide">
					OMG WTF BBQ
				</div>
			</div>
		</div>
	</div>

<?php
}

// Add event settings after title area
add_action( 'edit_form_after_title', NS . 'ce_event_details' );

// Save event details section on custom post type
function ce_event_details_save( $post_id ) {

	// Return if our nonce isn't set, isn't valid, or this is an autosave
	if ( !isset( $_POST['ce_event_details_nonce'] ) || !wp_verify_nonce( $_POST['ce_event_details_nonce'], 'ce_event_details' ) || (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) ) return;

	// Return if the user can't edit posts
	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	// Update the meta for this post
	update_post_meta( $post_id, '_ce_all_day', sanitize_text_field( $_POST['ce_all_day'] ) );
	update_post_meta( $post_id, '_ce_start_date', sanitize_text_field( $_POST['ce_start_date'] ) );
	update_post_meta( $post_id, '_ce_start_time', sanitize_text_field( $_POST['ce_start_time'] ) );
	update_post_meta( $post_id, '_ce_end_date', sanitize_text_field( $_POST['ce_end_date'] ) );
	update_post_meta( $post_id, '_ce_end_time', sanitize_text_field( $_POST['ce_end_time'] ) );
	update_post_meta( $post_id, '_ce_cost', sanitize_text_field( $_POST['ce_cost'] ) );

}

// Call event settings save function on save
add_action( 'save_post', NS . 'ce_event_details_save' );