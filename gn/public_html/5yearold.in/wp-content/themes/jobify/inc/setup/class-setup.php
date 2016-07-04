<?php
/**
 * Setting up Jobify
 *
 * @since 3.0.0
 * @package Jobify
 * @category Admin
 */
class Jobify_Setup {

	/**
	 * @var object
	 */
	public static $theme;

	/**
	 * @var object
	 */
	public static $maybe_child_theme;

	/**
	 * @var array
	 */
	public static $steps;

	/**
	 * Start things up.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! is_admin() ) {
			return;
		}

		// require_once( dirname( __FILE__ ) . '/class-use-a-child-theme.php' );

		// see if a child theme is active. If it is we need the parent theme's dir.
		self::$maybe_child_theme = wp_get_theme();

		if ( false !== self::$maybe_child_theme->parent() ) {
			self::$theme = wp_get_theme( self::$maybe_child_theme->Template );
		} else {
			self::$theme = wp_get_theme();
		}

		self::setup_actions();
		self::register_steps();
	}

	/**
	 * Set hooks and callbacks.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public static function setup_actions() {
		add_action( 'admin_menu', array( __CLASS__, 'add_page' ), 100 );
		add_action( 'admin_menu', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );
    }

	/**
	 * Create an array of steps for setting up the theme. 
	 * Each step has a title and completion boolean, with the step name being used
	 * to load the step's content from `steps/{$step-key}.php`
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
    private static function register_steps() {
		$menus = get_theme_mod( 'nav_menu_locations' );
		$has_listings = new WP_Query( array( 'post_type' => 'job_listing', 'fields' => 'ids', 'posts_per_page' => 1 ) );

		self::$steps = array(
			// 'child-theme' => array(
			// 	'title' => __( 'Create a Child Theme', 'jobify' ),
			// 	'completed' => false !== self::$maybe_child_theme->parent() 
			// ),
			'install-plugins' => array(
				'title' => __( 'Install Required &amp; Recommended Plugins', 'jobify' ),
				'completed' => ( class_exists( 'WP_Job_Manager' ) && class_exists( 'WooCommerce' ) ) ? true : false,
			),
			'import-content' => array(
				'title' => __( 'Import Demo Content', 'jobify' ),
				'completed' => $has_listings->have_posts(),
			),
			'import-widgets' => array(
				'title' => __( 'Import Widgets', 'jobify' ),
				'completed' => is_active_sidebar( 'widget-area-front-page' ),
			),
			'setup-menus' => array(
				'title' => __( 'Setup Menus', 'jobify' ),
				'completed' => isset( $menus[ 'primary' ] ),
			),
			'setup-homepage' => array(
				'title' => __( 'Setup Static Homepage', 'jobify' ),
				'completed' => (bool) get_option( 'page_on_front', false ),
			),
			'setup-widgets' => array(
				'title' => __( 'Setup Widgets', 'jobify' ),
				'completed' => is_active_sidebar( 'widget-area-front-page' ),
			),
			'customize-theme' => array(
				'title' => __( 'Customize', 'jobify' ),
				'completed' => get_option( 'theme_mods_listify' ),
			),
			'support-us' => array(
				'title' => __( 'Get Involved', 'jobify' ),
				'completed' => 'na',
			)
		);
	}

	/**
	 * Load additional styles for the setup steps.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
    public static function enqueue_styles() {
		$screen = get_current_screen();

		if ( 'appearance_page_jobify-setup' != $screen->id ) {
			return;
		}

		wp_enqueue_style( 'setup-guide', get_template_directory_uri() . '/inc/setup/setup.css' );
	}

	/**
	 * Create the admin theme page. Appears under "Appearance"
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
    public static function add_page() {
		add_theme_page( __( 'Jobify Setup', 'jobify' ), __( 'Setup Guide', 'jobify' ), 'manage_options', 'jobify-setup', array( __CLASS__, 'setup_page' ) );
    }

	/**
	 * Create a metabox for each of the steps registered in `register_steps`
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
    public static function add_meta_boxes() {
		foreach ( self::$steps as $step => $info ) {
			$args = array_merge( array( 'step' => $step ), $info );
			add_meta_box( $step , $info[ 'title' ], array( __CLASS__, 'step_box' ), 'jobify_setup_steps', 'normal', 'high', $args );
		}
    }

	/**
	 * Output the step HTML and content.
	 *
	 * @since 3.0.0
	 *
	 * @param object $object
	 * @param array $args
	 * @return void
	 */
    public static function step_box( $object, $metabox ) {
		$args = $metabox[ 'args' ];
    ?>
		<?php if ( $args[ 'completed' ] === true ) { ?>
			<div class="is-completed"><?php _e( 'Completed!', 'jobify' ); ?></div>
		<?php } elseif ( $args[ 'completed' ] === false || $args[ 'completed' ] == '' ) { ?>
			<div class="not-completed"><?php _e( 'Incomplete', 'jobify' ); ?></div>
		<?php } ?>

		<?php require( sprintf( '%s/steps/%s.php', dirname( __FILE__ ), $args[ 'step' ] ) ); ?>
    <?php
    }

	/**
	 * Output the admin page HTML structure.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
    public static function setup_page() {
?>
<div class="wrap about-wrap jobify-setup">
	<h1><?php printf( __( 'Welcome to Jobify %s', 'jobify' ), self::$theme->Version ); ?></h1>
	<p class="about-text"><?php printf( __( 'Creating a job listing website has never been easier with Jobify — the easiest to use job board theme available. Use the steps below to finish setting up your new website. If you have more questions please <a href="%s">review the documentation</a>.', 'jobify' ), 'http://jobify.astoundify.com' ); ?></p>
	<div class="jobify-badge"><img src="<?php echo get_template_directory_uri(); ?>/inc/setup/images/badge.jpg" width="140" alt="" /></div>

	<p class="helpful-links">
		<a href="http://jobify.astoundify.com" class="button button-primary js-trigger-documentation"><?php _e( 'Search Documentation', 'jobify' ); ?></a>&nbsp;
		<a href="https://astoundify.com/go/astoundify-support/" class="button button-secondary"><?php _e( 'Submit a Support Ticket', 'jobify' ); ?></a>&nbsp;
	</p>

	<script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={docs:{enabled:!0,baseUrl:"//astoundify-jobify.helpscoutdocs.com/"},contact:{enabled:!1,formId:"7f6e93b6-cb77-11e5-9e75-0a7d6919297d"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});</script>
	<script>
		HS.beacon.config({
			modal: true
		});

		jQuery(document).ready(function($) {
			$('.js-trigger-documentation').click(function(e) {
				e.preventDefault();
				HS.beacon.open();
			});
		});
	</script>

</div>

<div id="poststuff" class="wrap jobify-steps" style="margin: 25px 40px 0 20px">
	<?php do_accordion_sections( 'jobify_setup_steps', 'normal', null ); ?>
</div>
<?php  
    }

}
