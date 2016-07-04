<?php
/**
 * Color Schemes
 *
 * @since Listify 1.0.0
 */
class Listify_Customize_Color_Scheme_Control extends Listify_Customize_Group_Control {
    public $type = 'radio';
    public $schemes = array();
    public $full_schemes = false;

    public function render_content() {
        $name = '_customize-radio-' . $this->id;
        $schemes = $this->schemes;
    ?>
    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

    <?php foreach ( $schemes as $scheme => $scheme_data ) : ?>

        <p><label>
            <input <?php $this->link(); ?> name="<?php echo esc_attr( $name ); ?>" value="<?php echo $scheme; ?>" type="radio" <?php echo $this->generate_group_data( $scheme_data[ 'controls' ] ); ?> <?php checked($scheme, sanitize_title( $this->value() ) ); ?> />
            <?php echo $this->generate_scheme_preview( $scheme_data[ 'controls' ] ); ?>
            <?php echo esc_attr( $scheme_data[ 'title' ] ); ?>
        </label></p>

    <?php endforeach; ?>

    <?php if ( $this->description ) : ?>
        <p><?php echo $this->description; ?></p>
    <?php endif; ?>

    <style>
        .color-scheme {
            display: inline-block;
            height: 24px;
            vertical-align: middle;
            padding: 2px;
            border: 1px solid #ddd;
            margin-right: 4px;
            margin-top: -3px;
        }

        .color-scheme-color {
            display: inline-block;
            width: 10px;
            height: 24px;
        }
    </style>
    <?php
    }

    public function generate_scheme_preview( $colors ) {
        echo '<span class="color-scheme">';

        // They were set manually
        if ( ! $this->full_schemes ) {
            $colors = array_splice($colors, 2, 10);
        }

        foreach ( $colors as $color ) {
            echo '<span class="color-scheme-color" style="background-color: ' . $color . '"></span>';
        }

        echo '</span>';
    }

}
