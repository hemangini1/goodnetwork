<?php
/**
 * Single Post
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Jobify
 * @since Jobify 1.0
 */

get_header(); ?>

	<?php the_post(); ?>
	<header class="page-header">
		<h1 class="page-title"><?php printf( __( 'Jobs at %s', 'jobify' ), esc_attr( urldecode( get_query_var( apply_filters( 'wp_job_manager_companies_company_slug', 'company' ) ) ) ) ); ?></h1>

		<h2 class="page-subtitle"><strong><?php printf( _n( '%d Job Available', '%d Jobs Available', $wp_query->found_posts, 'jobify' ), $wp_query->found_posts ); ?></strong> <?php if ( get_the_company_tagline( get_the_ID() ) ) : ?>&bull; <?php the_company_tagline( '', '', true, get_the_ID() ); ?><?php endif; ?></h2>
	</header>
	<?php rewind_posts(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="container" role="main">
				
			<div class="company-profile row">
				<?php if ( jobify_get_the_company_description() ) : ?>
				<div itemscope itemtype="http://data-vocabulary.org/Organization" class="col-xs-12 job-company-about">
					<h2 class="widget-title widget-title--job_listing-top" itemprop="name"><?php printf( __( 'About %s', 'jobify' ), get_the_company_name() ); ?></h2>

					<?php jobify_the_company_description(); ?>
				</div>
				<?php endif; ?>

				<div class="company-profile-jobs col-md-10 col-sm-8 col-xs-12">
					<?php if ( have_posts() ) : ?>
					<div class="job_listings">
						<ul class="job_listings">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_job_manager_template_part( 'content', 'job_listing' ); ?>
							<?php endwhile; ?>
						</ul>
					</div>
					<?php else : ?>
						<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>
				</div>

				<div class="company-profile-info job-meta col-md-2 col-sm-4 col-xs-4">

					<article class="widget widget--job_listing">
						<?php the_company_logo( 'fullsize' ); ?>
					</article>

					<article class="widget widget--job_listing">

						<h3 class="widget-title widget-title--job_listing"><?php _e( 'Company Details', 'jobify' ); ?></h3>

						<ul class="job_listing-company-social company-social">
							<?php do_action( 'job_listing_company_social_before' ); ?>

							<?php if ( get_the_company_website() ) : ?>
							<li><a href="<?php echo esc_url( get_the_company_website() ); ?>" target="_blank" itemprop="url" class="job_listing-website">
								<?php _e( 'Website', 'jobify' ); ?>
							</a></li>
							<?php endif; ?>

							<?php if ( get_the_company_twitter() ) : ?>
							<li><a href="<?php echo esc_url( jobify_get_the_company_twitter() ); ?>" target="_blank" class="job_listing-twitter">
								<?php _e( 'Twitter', 'jobify' ); ?>
							</a></li>
							<?php endif; ?>

							<?php if ( jobify_get_the_company_facebook() ) : ?>
							<li><a href="<?php echo esc_url( jobify_get_the_company_facebook() ); ?>" target="_blank" class="job_listing-facebook">
								<?php _e( 'Facebook', 'jobify' ); ?>
							</a></li>
							<?php endif; ?>

							<?php if ( jobify_get_the_company_gplus() ) : ?>
							<li><a href="<?php echo esc_url( jobify_get_the_company_gplus() ); ?>" target="_blank" class="job_listing-googleplus">
								<?php _e( 'Google+', 'jobify' ); ?>
							</a></li>
							<?php endif; ?>

							<?php if ( jobify_get_the_company_linkedin() ) : ?>
							<li><a href="<?php echo esc_url( jobify_get_the_company_linkedin() ); ?>" target="_blank" class="job_listing-linkedin">
								<?php _e( 'LinkedIn', 'jobify' ); ?>
							</a></li>
							<?php endif; ?>

							<?php do_action( 'job_listing_company_social_after' ); ?>
						</ul>
					</article>

				</div>

			</div>
		</div><!-- #content -->

		<?php do_action( 'jobify_loop_after' ); ?>
	</div><!-- #primary -->

<?php get_footer(); ?>
