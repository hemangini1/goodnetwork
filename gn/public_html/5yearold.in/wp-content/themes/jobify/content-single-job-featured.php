<?php
/**
 * Featured Job
 *
 * @package Jobify
 * @since Jobify 1.0
 */
?>

<div class="job-spotlight">
	<div class="job-spotlight__featured-image">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php echo jobify_get_the_featured_image(); ?>
		</a>
	</div>

	<div class="job-spotlight__content">
		<p><a href="<?php the_permalink(); ?>" rel="bookmark" class="job-spotlight__title"><?php the_title(); ?></a></p>

		<div class="job-spotlight__actions">
			<span class="job_listing-location"><?php the_job_location(false); ?></span>
			<span class="job-type <?php echo get_the_job_type() ? sanitize_title( get_the_job_type()->slug ) : ''; ?>"><?php the_job_type(); ?></span>
		</div>

		<?php the_excerpt(); ?>
	</div>
</div>