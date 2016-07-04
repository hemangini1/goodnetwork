<?php
/**
 * Description Control
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customize_Description_Control extends WP_Customize_Control {
	public $type = 'description';

	public function render_content() {
?>
    <p><?php echo wp_kses_post( $this->label ); ?></p>
<?php
	}
}
