<?php

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'load_widgets' );

/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function load_widgets() {
	register_widget( 'Surname_Widget' );
}

/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Surname_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Surname_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'rpSurnameWidget', 'description' => __('An widget that displays the top X surnames in rootspersona.', 'rootspersona') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'rp_surname_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'rp_surname_widget', __('Surname Widget', 'rootspersona'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
        global $wpdb;
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$cnt = $instance['cnt'];


		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display name from widget settings if one was input. */
		if ( $cnt > 0 ) {
            $creds = new RP_Credentials();
            $creds->set_prefix( $wpdb->prefix );
           $transaction = new RP_Transaction( $creds, false );
           $rows = RP_Dao_Factory::get_rp_persona_dao( $wpdb->prefix )
                            ->get_top_x_surnames( $cnt );
           $rCnt = count($rows);
			if($rCnt > 0 ) {

                //$options = get_option( 'persona_plugin' );
                //$index_page = $options[''];

                for($idx = 0; $idx < $rCnt; $idx++ ) {
                    echo '<div style="margin-left:10px;">' . $rows[$idx]['surname'] . ' (' . $rows[$idx]['cnt'] . ')</div>';
                }
            }
            $transaction->close();
        }

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cnt'] = strip_tags( $new_instance['cnt'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Top Surnames', 'rootspersona'), 'cnt' => '10' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<!-- Your Name: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'cnt' ); ?>"><?php _e('How Many:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cnt' ); ?>" name="<?php echo $this->get_field_name( 'cnt' ); ?>" value="<?php echo $instance['cnt']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}

?>
