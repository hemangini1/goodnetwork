<?php
/**
 * Font Packs
 */
class Listify_Customize_Font_Pack_Control extends Listify_Customize_Group_Control {
    public $type = 'radio';

    public function render_content() {
        $name = '_customize-radio-' . $this->id;
        $packs = listify_get_control_group( 'font-pack' );
    ?>
    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

    <?php foreach ( $packs as $pack => $pack_data ) : ?>

        <p><label>
            <input <?php $this->link(); ?> name="<?php echo esc_attr( $name ); ?>" value="<?php echo $pack; ?>" type="radio" <?php echo $this->generate_group_data( $pack_data[ 'controls' ] ); ?> <?php checked($pack, sanitize_title( $this->value() ) ); ?> />
            <?php echo esc_attr( $pack_data[ 'title' ] ); ?>
        </label></p>

    <?php endforeach; ?>

    <?php
    }

}
