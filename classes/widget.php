<?php
/**
 * This is currently a placeholder class... which will eventually handle the CLNDR.js widget
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    Clean Events
 * @author     Jeff Hays (jphase) <jeff@robido.com>
 */

namespace CleanEvents;

class Widget extends \WP_Widget {

    function __construct() {
        parent::__construct( 'ce_event_calendar', __( 'Clean Events Calendar', 'clean_events' ), __( 'A Clean Events Calendar Widget', 'clean_events' ) );
        \add_action( 'widgets_init', function() { return \register_widget( 'CleanEvents\Widget' ); } );
    }

    function widget( $args, $instance ) {

        // Enqueue styles
        \wp_enqueue_style( 'clndr', \CleanEvents\URL . 'css/clndr.css' );

        // Enqueue scripts
        \wp_enqueue_script( 'moment', \CleanEvents\URL . 'js/moment.min.js', false, '2.7.0', true );
        \wp_enqueue_script( 'underscore', \CleanEvents\URL . 'js/underscore.min.js', false, '1.6.0', true );
        \wp_enqueue_script( 'clndr', \CleanEvents\URL . 'js/clndr.min.js', array( 'jquery', 'moment', 'underscore' ), '1.2.0', true );
        \wp_enqueue_script( 'clean-events-widget', \CleanEvents\URL . 'js/clean-events-widget.js', array( 'jquery', 'moment', 'underscore', 'clndr' ), \CleanEvents\VERSION, true );

        // Localize scripts
        \wp_localize_script( 'clean-events-widget', 'settings', $this->get_js_settings() );

        // Apply filters to calendar widget container for developers
        echo \apply_filters( 'ce_calendar_widget_container', '<div class="mini-clndr"></div>' );

    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
        return $new_instance;
    }

    function form( $instance ) {
        $instance = \wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
    ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title <small><em>(optional)</em></small>: <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo attribute_escape($title); ?>" />
            </label>
        </p>

    <?php
    }

    function get_js_settings() {

        // JS object
        $obj = new \stdClass;

        // CLNDR object
        $obj->clndr = new \stdClass;
        $obj->clndr->template = file_get_contents( \CleanEvents\PATH . 'templates/widget.html' );
        $obj->clndr->daysOfTheWeek = array( 'S', 'M', 'T', 'W', 'T', 'F', 'S' );
        $obj->clndr->numberOfRows = 5;
        $obj->clndr->days = $this->get_events();

        // Apply a ct_time_picker filter to our time settings for developers
        $obj->clndr = \apply_filters( 'ce_clndr', $obj->clndr );

        return $obj;

    }

    function get_events() {

        // Prepare arguments for WP_Query
        $args = array(
            'post_type' => 'clean_event',
            // 'meta_query' => array(
            //     array(
            //         'key' => '_ce_start_date',
            //         'value' => date('Y-m-d'),
            //         'type' => 'DATE',
            //         'compare' => '>=',
            //     ),
            // ),
        );

        // Get events
        $event_query = new \WP_Query( $args );
        $events = $event_query->get_posts();

        // // The Loop
        // if ( $the_query->have_posts() ) {
        //     echo '<ul>';
        //     while ( $the_query->have_posts() ) {
        //         $the_query->the_post();
        //         echo '<li>' . get_the_title() . '</li>';
        //     }
        //     echo '</ul>';
        // } else {
        //     // no posts found
        // }

        wp_reset_postdata();

        return $events;

    }

}
