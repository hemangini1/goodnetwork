<?php
/**
 * Job Content
 *
 * @package Jobify
 * @since Jobify 1.0
 */

global $post;

$info         = jobify_theme_mod( 'job-display-sidebar' );
$col_overview = 'top' == $info ? '12' : ( ! jobify_get_the_company_description() ? '10' : '6' );
$col_company  = 'top' == $info ? '12' : '4';
?>

<div class="single_job_listing" itemscope itemtype="http://schema.org/JobPosting">

    <div class="page-header">
        <h2 class="page-title">
            <?php the_title(); ?>
            <meta itemprop="title" content="<?php echo esc_attr( $post->post_title ); ?>" />
        </h2>
        <h3 class="page-subtitle">
            <?php do_action( 'single_job_listing_start' ); ?>
        </h3>
    </div>

    <div id="content" class="container content-area" role="main">

        <?php if ( get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
            <div class="job-manager-info"><?php _e( 'This job listing has expired', 'jobify' ); ?></div>
        <?php else : ?>

            <?php locate_template( array( 'sidebar-single-job_listing-top.php' ), true, false ); ?>

            <div class="job-overview-content row">
                <div itemprop="description" class="job_listing-description job-overview col-md-<?php echo $col_overview; ?> col-sm-12">
                    <h2 class="widget-title widget-title--job_listing-top job-overview-title"><?php _e( 'Overview', 'jobify' ); ?></h2>
                    <?php echo apply_filters( 'the_job_description', get_the_content() ); ?>
                </div>

                <?php if ( jobify_get_the_company_description() ) : ?>
                <div itemscope itemtype="http://data-vocabulary.org/Organization" class="job_listing-company-description job-company-about col-md-<?php echo $col_company; ?> <?php echo 'top' == $info ? 'col-md-12' : 'col-sm-6 col-xs-12'; ?>">
                    <h2 class="widget-title widget-title--job_listing-top job-overview-title" itemprop="name"><?php printf( __( 'About %s', 'jobify' ), get_the_company_name() ); ?></h2>
                    <?php jobify_the_company_description(); ?>
                </div>
                <?php endif; ?>

                <?php locate_template( array( 'sidebar-single-job_listing.php' ), true, false ); ?>
            </div>

            <?php do_action( 'single_job_listing_end' ); ?>

        <?php endif; ?>
    </div>

    <?php get_template_part( 'content-single-job', 'related' ); ?>

</div>
