<?php
/**
 * Single Resume
 *
 * @since Jobify 3.0.0
 */

global $post;

get_header();
?>

    <?php while( have_posts() ) : the_post(); ?>

        <?php get_job_manager_template_part( 'content-single', 'resume' ); ?>

    <?php endwhile; ?>

<?php get_footer(); ?>
