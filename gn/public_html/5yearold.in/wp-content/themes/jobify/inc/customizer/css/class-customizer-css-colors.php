<?php
/**
 * Output the color CSS.
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_CSS_Colors {

    public function __construct() {
        $this->css = jobify_customizer()->css;

        add_action( 'jobify_output_customizer_css', array( $this, 'colors' ), 0 );
    }

    public function colors() {
        /**
         * Page Background Color
         */
        $page_background = '#' . get_background_color();

        $this->css->add( array(
            'selectors' => array(
                'html'
            ),
            'declarations' => array(
                'background-color' => esc_attr( $page_background )
            )
        ) );

		/**
         * Body Text Color
         *
         * A lot of the specific selectors are to override plugin CSS
         * or links that stick out too much (buttons, etc).
         */
        $body_text_color = jobify_theme_mod( 'color-body-text' );

        $this->css->add( array(
            'selectors' => array(
				'body',
				'input',
				'textarea',
				'select',
				'body .chosen-container-single .chosen-single span',
				'body .chosen-container-single .chosen-single div:before'
			),
			'declarations' => array(
				'color' => esc_attr( $body_text_color )
			)
		) );

		/**
         * Body Link Color
         */
        $body_link_color = jobify_theme_mod( 'color-link' );

        $this->css->add( array(
            'selectors' => array(
				'a',
				'.job_listing-clickbox:hover'
            ),
            'declarations' => array(
                'color' => esc_attr( $body_link_color )
            )
        ) );

        // darken on hover
        $this->css->add( array(
            'selectors' => array(
                'a:active',
                'a:hover',
            ),
            'declarations' => array(
                'color' => $this->css->darken( esc_attr( $body_link_color ), -25 )
            )
        ) );

		/**
		 * Header & Navigation
		 */
		$header_background = jobify_theme_mod( 'color-header-background' );
		$navigation_text = jobify_theme_mod( 'color-navigation-text' );

        $this->css->add( array(
            'selectors' => array(
                '.site-header',
                '.nav-menu--primary .sub-menu',
            ),
            'declarations' => array(
                'background' => esc_attr( $header_background )
            )
        ) );

		$this->css->add( array(
            'selectors' => array(
                '.nav-menu--primary ul li a',
                '.nav-menu--primary li a',
                '.nav-menu--primary ul li a:hover',
                '.nav-menu--primary li a:hover',
				'.primary-menu-toggle',
				'.searchform--header__submit',
				'.searchform--header__input'
            ),
            'declarations' => array(
                'color' => esc_attr( $navigation_text )
            )
        ) );

		$this->css->add( array(
            'selectors' => array(
                '.nav-menu--primary ul li.highlight a',
                '.nav-menu--primary ul li.login a'
            ),
            'declarations' => array(
                'border-color' => esc_attr( $navigation_text )
            )
        ) );

		$this->css->add( array(
            'selectors' => array(
                '.nav-menu--primary ul li.highlight a:hover',
                '.nav-menu--primary ul li.login a:hover'
            ),
            'declarations' => array(
				'color' => esc_attr( $header_background ),
				'background-color' => esc_attr( $navigation_text ),
                'border-color' => esc_attr( $navigation_text )
            )
        ) );

		/**
		 * Primary
		 */
		$primary = jobify_theme_mod( 'color-primary' );

		$this->css->add( array(
            'selectors' => array(
                '.search_jobs',
                '.search_resumes',
                '.cluster div',
                '.job-type',
                '.price-option__title',
				'.entry-header__featured-image:hover .overlay',
				'.widget_price_filter .ui-slider-horizontal .ui-slider-range'
            ),
            'declarations' => array(
                'background-color' => esc_attr( $primary )
            )
        ) );


		$this->css->add( array(
            'selectors' => array(
				// Tags
				'.job_filters .search_jobs .filter_by_tag a.active'
            ),
            'declarations' => array(
                'color' => esc_attr( $primary )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.cluster div:after',
                'input:focus',
				'.widget_price_filter .ui-slider .ui-slider-handle',
            ),
            'declarations' => array(
                'border-color' => esc_attr( $primary )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                'ul.job_listings .job_listing:hover',
                '.job_position_featured',
                'li.type-resume:hover',
            ),
            'declarations' => array(
                'box-shadow' => 'inset 5px 0 0 ' . esc_attr( $primary )
            )
        ) );

        // Buttons
        $this->css->add( array(
            'selectors' => array(
                '.button',
                'input[type=button]',
                'button',
                '#submitcomment',
				'#commentform input[type=submit]',
				'.widget--footer input[type=submit]',
                '.mfp-close-btn-in .mfp-close',
                
                // Applications
                'input[name=wp_job_manager_send_application]',
                'input[name=wp_job_manager_edit_application]',

                // Bookmarks
                'input[name=submit_bookmark]',

                // RCP
                '#rcp_submit',

                // Resumes
                'input[name=wp_job_manager_resumes_apply_with_resume]',
                'input[name=wp_job_manager_resumes_apply_with_resume_create]',

                // Contact Form 7
                '.wpcf7-submit',

                // Ninja
                'input[type=submit].ninja-forms-field',

                // Alerts
				'input[name=submit-job-alert]',

				// Hero Search
                '.hero-search .search_jobs>div input[type=submit]',
                '.hero-search .search_resumes>div input[type=submit]'
            ),
            'declarations' => array(
				'background-color' => esc_attr( $primary ),
				'border-color' => 'transparent',
                'color' => '#fff'
            )
        ) );
        
        // Button Hover
        $this->css->add( array(
            'selectors' => array(
                '.button:hover',
                'input[type=button]:hover',
                'button:hover',
                '.job-manager-pagination a:hover',
                '.job-manager-pagination span:hover',
                '.page-numbers:hover',
                '#searchform button:hover',
                '#searchform input[type=submit]:hover',
                '#submitcomment:hover',
				'#commentform input[type=submit]:hover',
				'.page-numbers.current',
				'.widget--footer input[type=submit]:hover',
                '.mfp-close-btn-in .mfp-close:hover',

                // Applications
                'input[name=wp_job_manager_send_application]:hover',
                'input[name=wp_job_manager_edit_application]:hover',

                // Bookmarks
                'input[name=submit_bookmark]:hover',

                // RCP
                '#rcp_submit:hover',

                // Resumes
                'input[name=wp_job_manager_resumes_apply_with_resume]:hover',
                'input[name=wp_job_manager_resumes_apply_with_resume_create]:hover',

                // Contact Form 7
                '.wpcf7-submit:hover',

                // Ninja
                'input[type=submit].ninja-forms-field:hover',

                // Alerts
				'input[name=submit-job-alert]:hover',
				
				// Soliloquy
				'.tp-caption .button:hover'
            ),
            'declarations' => array(
                'background-color' => 'transparent',
                'color' => esc_attr( $primary ),
                'border-color' => esc_attr( $primary )
            )
        ) );
        
        // Inverted Buttons
        $this->css->add( array(
            'selectors' => array(
				'.button--type-inverted',

				// Widgets
				'.widget--home-video .button',

				// WP Job Manager
                '.load_more_jobs strong',
                '.load_more_resumes strong',

                // Bookmarks
                '.job-manager-form.wp-job-manager-bookmarks-form a.bookmark-notice'
            ),
            'declarations' => array(
                'color' => esc_attr( $primary ),
                'border-color' => esc_attr( $primary )
            )
        ) );

        // Inverted Button Hover
        $this->css->add( array(
            'selectors' => array(
				'.button--type-inverted:hover',

				// Widgets
				'.widget--home-video .button:hover',

				// WP Job Manager
                '.load_more_jobs strong:hover',
                '.load_more_resumes strong:hover',

                // Bookmarks
                '.job-manager-form.wp-job-manager-bookmarks-form a.bookmark-notice:hover'
            ),
            'declarations' => array(
                'background-color' => esc_attr( $primary ),
                'color' => '#fff'
            )
        ) );
		
		/**
		 * Accent
		 */
		$accent = jobify_theme_mod( 'color-accent' );

        // Special Button
        $this->css->add( array(
            'selectors' => array(
				// Button
				'.button--type-action',
				'.button--type-secondary:hover',

				// WooCommerce
                '.single-product #content .single_add_to_cart_button',
                '.checkout-button',
                '#place_order',

				// Apply
                'input[type=button].application_button',
                'input[type=button].resume_contact_button',
            ),
			'declarations' => array(
                'color' => esc_attr( $accent ),
				'background-color' => 'transparent',
                'border-color' => esc_attr( $accent )
            )
        ) );
        
        // Special Button Hover
        $this->css->add( array(
            'selectors' => array(
				'.button--type-action:hover',
				'.button--type-secondary',

				// WooCommerce
                '.single-product #content .single_add_to_cart_button:hover',
                '.checkout-button:hover',
                '#place_order:hover',

				// Apply
                'input[type=button].application_button:hover',
                'input[type=button].resume_contact_button:hover',
            ),
            'declarations' => array(
                'background-color' => esc_attr( $accent ),
                'color' => '#ffffff',
                'border-color' => esc_attr( $accent )
            )
        ) );

		/**
		 * White Buttons
		 */
        $this->css->add( array(
            'selectors' => array(
				'.button--color-white',
				'.button--color-white.button--type-inverted:hover',
				'.button--type-hover-white:hover'
            ),
			'declarations' => array(
                'color' => esc_attr( $body_text_color ),
				'background-color' => '#ffffff',
                'border-color' => '#ffffff'
            )
        ) );
        
        $this->css->add( array(
            'selectors' => array(
				'.button--color-white:hover',
				'.button--color-white.button--type-inverted',
				'.button--type-hover-inverted-white:hover',
            ),
            'declarations' => array(
                'background-color' => 'transparent',
                'color' => '#ffffff',
                'border-color' => '#ffffff'
            )
        ) );
    }

}
