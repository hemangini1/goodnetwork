<?php
/**
 * Price Table for WooCommerce Paid Listings
 *
 * Automatically populated with subscriptions.
 *
 * @since Jobify 1.0
 */
class Jobify_Widget_Price_Table_WC extends Jobify_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'jobify_widget_price_table_wc';
        $this->widget_description = __( 'Outputs Job Packages from WooCommerce', 'jobify' );
        $this->widget_id          = 'jobify_widget_price_table_wc';
        $this->widget_name        = __( 'Jobify - Home: WC Paid Listings', 'jobify' );
        $this->settings           = array(
			'home' => array(
				'std' => __( 'Homepage', 'jobify' ),
				'type' => 'widget-area'
			),
            'title' => array(
                'type'  => 'text',
                'std'   => __( 'Plans and Pricing', 'jobify' ),
                'label' => __( 'Title:', 'jobify' )
            ),
            'packages' => array(
                'label' => __( 'Package Type:', 'jobify' ),
                'type' => 'select',
                'std' => 'job_package',
                'options' => array(
                    'job_package' => __( 'Job Packages', 'jobify' ),
                    'resume_package' => __( 'Resume Packages', 'jobify' )
                )
            ),
            'description' => array(
                'type'  => 'textarea',
                'rows'  => 4,
                'std'   => '',
                'label' => __( 'Description:', 'jobify' ),
            )
        );

        parent::__construct();
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title       = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance[ 'title' ] : '', $instance, $this->id_base );
        $description = isset( $instance[ 'description' ] ) ? $instance[ 'description' ] : '';
        $packages    = isset( $instance[ 'packages' ] ) ? esc_attr( $instance[ 'packages' ] ) : 'job_package';
        $type        = 'job_package' == $packages ? 'job_listing' : 'resume';
        $obj         = get_post_type_object( $type );

        if ( 'job_package' == $packages ) {
            $packages = WP_Job_Manager_WCPL_Submit_Job_Form::get_packages();
        } else {
            $packages = WP_Job_Manager_WCPL_Submit_Resume_Form::get_packages();
        }

        if ( ! $packages ) {
            return;
        }

        echo $before_widget;
        ?>

        <div class="container">

            <?php if ( $title ) echo $before_title . $title . $after_title; ?>

            <?php if ( $description ) : ?>
                <p class="widget-description widget-description--home"><?php echo $description; ?></p>
            <?php endif; ?>

            <div class="price-table row" data-columns>

                <?php foreach ( $packages as $key => $package ) : $product = get_product( $package ); ?>
                    <div class="price-option">
                        <div class="price-option__title">
                            <?php echo $product->get_title(); ?>
                        </div>

                        <div class="price-option__description">
                            <h2 class="price-option__price"><?php echo $product->get_price_html(); ?></h2>

                            <p class="price-option__duration"><?php 
                                if ( 'job_listing' == $type ) {
                                    printf( _n( '%s for %s job', '%s for %s jobs', $product->get_limit(), 'jobify' ) . ' ', $product->get_price_html(), $product->get_limit() ? $product->get_limit() : __( 'unlimited', 'jobify' ) );
                                    if ( $product->get_duration() ) {
                                        printf( _n( 'for %s day', 'for %s days', $product->get_duration(), 'jobify' ), $product->get_duration() );
                                    }
                                } else {
                                    printf( _n( '%s to post %d resume', '%s to post %s resumes', $product->get_limit(), 'jobify' ) . ' ', $product->get_price_html(), $product->get_limit() ? $product->get_limit() : __( 'unlimited', 'jobify' ) );
                                    if ( $product->get_duration() ) {
                                        printf( ' ' . _n( 'for %s day', 'for %s days', $product->get_duration(), 'jobify' ), $product->get_duration() );
                                    }
                                }
                            ?></p>

                            <?php echo apply_filters( 'the_content', get_post_field( 'post_content', $product->id ) ); ?>

                            <p>
                                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button-secondary"><?php _e( 'Get Started', 'jobify' ); ?></a>
                            </p>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

        </div>

        <?php
        echo $after_widget;
    }
}
