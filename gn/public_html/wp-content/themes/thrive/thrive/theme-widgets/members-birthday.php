<?php
/**
 * Adds thrive_members_birthday_widget widget.
 */
class Thrive_Members_Birthday_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {
		parent::__construct(
			'thrive_members_birthday_widget', // Base ID
			__( 'Thrive: Birthday Widget', 'thrive' ), // Name
			array( 'description' => __( 'Celebrate your members\' birthday with Thrive Birthday Widget.', 'thrive' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		global $wpdb;

		ob_start();

		?>

		<?php $db_field_id = 0; ?>

		<?php if ( function_exists('xprofile_get_field_id_from_name') ) { ?>

			<?php $db_field_id = xprofile_get_field_id_from_name( 'Date of Birth' ); ?>

		<?php } ?>

		<?php if ( empty( $db_field_id ) ) { ?>

			<?php _e('I can\'t find "Date of Birth" profile data. Have you added "Date of Birth" user field already?', 'thrive'); ?>

			<?php echo $args['after_widget']; ?>

		<?php } ?>

		<?php
			$stmt = sprintf( "SELECT * FROM {$wpdb->prefix}bp_xprofile_data
				WHERE field_id = %d ORDER BY value", $db_field_id );
		?>

		<?php $members = $wpdb->get_results( $stmt, OBJECT ); ?>

		<?php $birthday_count = 0 ; ?>

		<?php if ( !empty( $members ) ) { ?>

			<ul>

			<?php foreach( $members as $member ) { ?>

				<?php

					$user_id = $member->user_id;

					$user_link = bp_core_get_userlink( $user_id );

					$user_domain = bp_core_get_user_domain( $user_id );

					$user_domain_activity = sprintf( "%sactivity", $user_domain );

					$date = $member->value;

					// Get user birthday of this year.
					$user_current_bday = strtotime( sprintf( date( "F d %s", strtotime( $date) ), date("Y") ) );

					$now = strtotime( sprintf( date( "F d %s", strtotime( "now") ), date("Y") ) );

					$selected_scope = intval( $instance['filter'] );

					// Yearly (Default)
					$scope = strtotime( sprintf("December 31 %s", date("Y") ) );

					if ( 2 === $selected_scope ) {
						// This Month.
						$scope = strtotime( date( sprintf("%s-m-t", date("Y") ) ) );
					}

					if ( 3 === $selected_scope ) {
						// This Week.
						$dateTime = new DateTime();
						// Get last day of the week.
						// @resouce: http://stackoverflow.com/questions/8541466/getting-first-last-date-of-the-week
						$std_date =  $dateTime->modify('this week +6 days');
						$scope = strtotime( $std_date->format('Y-m-d') );
					}
				?>

				<?php if ( $now <= $user_current_bday ) { ?>

					<?php if ( $user_current_bday <= $scope ) { ?>

					<?php $birthday_count = $birthday_count += 1; ?>

					<li>

						<div class="row">

							<div class="col-xs-3 pd-right-5">

								<a href="<?php echo esc_url( $user_domain_activity ); ?>" title="<?php _e('Visit Profile', 'thrive'); ?>">

									<?php echo get_avatar( $user_id, 64 ); ?>

								</a>
							</div>

							<div class="col-xs-9">
								<h5 class="mg-bottom-5">
									<?php echo $user_link; ?>
								</h5>
								<p>
									<?php $date_formatted = date_i18n('F d', strtotime( $date ) ); ?>

									<?php if ( $now === $user_current_bday ) { ?>

										<?php $bp_page_option = get_option('bp-pages'); ?>

										<?php $user_name = bp_members_get_user_nicename( $user_id ); ?>

										<?php $greetings_link = $user_domain; ?>

										<?php if ( !empty( $bp_page_option['activity'] ) ) { ?>

											<?php $greetings_link = get_permalink( $bp_page_option['activity'] ); ?>

											<?php $message = apply_filters('thrive_activity_birthday_message', __(' Happy%20Birthday!', 'thrive')); ?>

											<?php $greetings_link .= "?r=".$user_name.'%20'.$message; ?>

										<?php } ?>

										<span class="celebrating">

											<i class="material-icons md-18 secondary">cake</i>

											<a href="<?php echo esc_url( $greetings_link ); ?>" title="<?php _e('Happy Birthday!', 'thrive'); ?>">
												<?php _e('Greet', 'thrive'); ?>
											</a>

										</span>

									<?php } else { ?>

										<span class="upcoming">

											<i class="material-icons md-18">cake</i>

											<?php echo esc_html( $date_formatted ); ?>

										</span>

									<?php } ?>
								</p>
							</div>
						</div>
					</li>

					<?php } // end $user_current_bday <= $scope ?>

				<?php } // end $now <= $user_current_bday ?>

			<?php } //end foreach ?>

			</ul>

			<?php if ( $birthday_count == 0 ) { ?>
				<p>
					<?php echo __('There are no upcoming birthday celebrations', 'thrive'); ?>
				</p>
			<?php } ?>
		<?php } ?>
		<?php

		$template = ob_get_clean();

		if ( "on" === $instance['is_hide'] ) {
			if ( $birthday_count <= 0 ) {
				// Do nothing...
			} else {

				echo $args['before_widget'];

					if ( ! empty( $instance['title'] ) ) {
						echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
					}

					echo $template;

				echo $args['after_widget'];
			}
		} else {

			echo $args['before_widget'];

				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
				}

				echo $template;

			echo $args['after_widget'];
		}

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Birthdays', 'thrive' );
		$filter = ! empty( $instance['filter'] ) ? $instance['filter'] : 1;
		$is_hide = ! empty( $instance['is_hide'] ) ? $instance['is_hide'] : 'no';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'thrive' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e( 'Show Birthdays:', 'thrive' ); ?></label>
			<br/>
			<select name="<?php echo $this->get_field_name( 'filter' ); ?>" id="<?php echo $this->get_field_id( 'filter' ); ?>">
				<option <?php echo $filter == 1 ? "selected":"";?> value="1"><?php _e('This Year','thrive'); ?> </option>
				<option <?php echo $filter == 2 ? "selected":"";?> value="2"><?php _e('This Month', 'thrive'); ?></option>
				<option <?php echo $filter == 3 ? "selected":"";?> value="3"><?php _e('This Week', 'thrive'); ?> </option>
			</select>
		</p>

		<p>
			<?php
			$checked = '';
			if ( "on" === $is_hide ) {
					$checked = 'checked';
				};
			?>
			<label for="<?php echo $this->get_field_id( 'is_hide' ); ?>"><?php _e( 'Hide if no birthdays:', 'thrive' ); ?></label>
			<input type="checkbox" <?php echo $checked; ?> name="<?php echo $this->get_field_name( 'is_hide' ); ?>" id="<?php echo $this->get_field_id( 'is_hide' ); ?>" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['filter'] = ( ! empty( $new_instance['filter'] ) ) ? strip_tags( $new_instance['filter'] ) : '';
		$instance['is_hide'] = ( ! empty( $new_instance['is_hide'] ) ) ? strip_tags( $new_instance['is_hide'] ) : '';

		return $instance;
	}

} // class thrive_members_birthday_widget

// register thrive_members_birthday_widget widget
function register_thrive_members_birthday_widget() {
    register_widget( 'thrive_members_birthday_widget' );
}

add_action( 'widgets_init', 'register_thrive_members_birthday_widget' );
