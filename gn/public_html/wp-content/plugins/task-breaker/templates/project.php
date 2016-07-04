<?php global $post; ?>

<div id="task_breaker-preloader">

	<div class="la-ball-clip-rotate la-sm">

	    <div></div>

	</div>

</div>

<div class="active task_breaker-project-tab-content-item" data-content="task_breaker-project-dashboard" id="task_breaker-project-dashboard-context">

	<div id="task_breaker-dashboard-about">

		<h3><?php _e('About', 'task_breaker'); ?></h3>

		<?php echo wpautop( do_shortcode( $post->post_content ), true ); ?>

		<div class="clearfix"></div>

	</div><!--#task_breaker-dashboard-about-->

	<div id="task_breaker-dashboard-at-a-glance">
		<?php

		// Total tasks.
		$total     = intval( task_breaker_count_tasks( $post->ID ) );
		// Completed tasks.
		$completed = intval( task_breaker_count_tasks( $post->ID, $type = 'completed' ) );
		// Remaining Tasks.
		$remaining = absint( $total - $completed );

		?>
		<h3>
			<?php _e('At a Glance', 'task_breaker'); ?>
		</h3>
		<ul>
			<li>
				<div class="task_breaker-dashboard-at-a-glance-box">

					<h4>
						<span id="task_breaker-total-tasks-count" class="task_breaker-total-tasks">
							<?php printf('%d', $total); ?>
						</span>
					</h4>

					<p>
						<?php _e('Total Tasks', 'task_breaker'); ?>
					</p>

				</div>
			</li>

			<li>
				<a href="#tasks" class="task_breaker-dashboard-at-a-glance-box">

					<h4>
						<span id="task_breaker-remaining-tasks-count" class="task_breaker-remaining-tasks-count">
							<?php printf('%d', $remaining); ?>
						</span>
					</h4>

					<p><?php _e('Task(s) remaining', 'task_breaker'); ?></p>

				</a>
			</li>

			<li>
				<a href="#tasks/completed" class="task_breaker-dashboard-at-a-glance-box">

					<h4>
						<span id="task-progress-completed-count" class="task-progress-completed">
							<?php printf('%d', $completed); ?>
						</span>
					</h4>

					<p><?php _e('Task(s) Completed', 'task_breaker'); ?></p>

				</a>
			</li>

		</ul>

		<div class="clearfix"></div>

	</div><!--#task_breaker-dashboard-at-a-glance-->
</div>

<div class="task_breaker-project-tab-content-item" data-content="task_breaker-project-tasks" id="task_breaker-project-tasks-context">

	<?php
		$args = array(
				'project_id' => $post->ID,
				'orderby' => 'priority',
				'order' => 'desc'
			);
	?>

	<?php task_breaker_task_filters(); ?>

	<?php echo task_breaker_the_tasks( $args ); ?>

</div><!--#task_breaker-project-tasks-context-->

<div class="task_breaker-project-tab-content-item" data-content="task_breaker-project-settings" id="task_breaker-project-settings-context">
	<?php task_breaker_project_settings(); ?>
</div>

<div class="task_breaker-project-tab-content-item" data-content="task_breaker-project-add-new" id="task_breaker-project-add-new-context">
	<?php task_breaker_add_task_form(); ?>
</div>

<div class="task_breaker-project-tab-content-item" id="task_breaker-project-edit-context">
	<?php task_breaker_edit_task_form(); ?>
</div>

<script>
var task_breakerProjectSettings = {
	project_id: '<?php echo absint( $post->ID ); ?>',
	nonce: '<?php echo wp_create_nonce( "task_breaker-transaction-request" ); ?>'
};
</script>
