<?php
/**
 * Customize
 *
 * @package Jobify
 * @since Jobify 3.0.0
 */
class Jobify_Customizer {

	/**
	 * @var object The single instance of the class
	 */
    private static $instance;

	/**
	 * @var object $panels
	 */
    public $panels;

	/**
	 * @var object $output
	 */
    public $output;

	/**
	 * @var object $output
	 */
    public $css;

    public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Jobify_Customizer ) ) {
			self::$instance = new self;
		}

		return self::$instance;
    }

    public function __construct() {
		$this->includes();
		$this->setup();
	}

	public function includes() {
        $files = array(
            'helper-functions.php',

            'class-customizer-priority.php',
            'class-customizer-mods.php',
            'class-customizer-css.php',
            'class-customizer-control.php',
            'class-customizer-section.php',
            'class-customizer-panel.php',
            'class-customizer-panels.php',
            'class-customizer-output-css.php'
        );

        foreach ( $files as $file ) {
            include_once( trailingslashit( dirname( __FILE__) ) . $file );
        }
	}

	public function setup() {
		// set the defaults early -- to filter you must register the filter on init priority 0
		add_action( 'init', array( 'Jobify_Customizer_Mods', 'get_defaults' ) );

        $this->panels = new Jobify_Customizer_Panels();
        $this->output = new Jobify_Customizer_Output();
        $this->css = new Jobify_Customizer_CSS();

        add_action( 'customize_register', array( $this, 'custom_controls' ), 8 );
    }

    public function custom_controls() {
        $controls = array(
            'class-customizer-control-description.php',
            'class-customizer-control-multicheck.php'
        );

        foreach ( $controls as $file ) {
            include_once( trailingslashit( dirname( __FILE__) ) . 'control/' . $file );
        }
    }

}

function jobify_customizer() {
    return Jobify_Customizer::instance();
}

jobify_customizer();
