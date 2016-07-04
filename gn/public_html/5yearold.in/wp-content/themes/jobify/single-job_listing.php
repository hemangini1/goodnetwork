<?php
/**
 * Single Job
 *
 * @since Jobify 3.0.0
 */

get_header();
?>

    <?php while( have_posts() ) : the_post(); ?>

        <?php get_job_manager_template_part( 'content-single', 'job_listing' ); ?>

    <?php endwhile; ?>

<?php get_footer(); ?>
