<?php

class Listify_Customize_Style_Kit_Control extends Listify_Customize_Group_Control {
    public $type = 'radio';

    public function render_content() {
        $name = '_customize-radio-' . $this->id;
        $kits = listify_get_control_group( 'style-kit' );
    ?>
    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

    <?php foreach ( $kits as $kit => $kit_data ) : ?>

        <p>
            <label>
                <input <?php $this->link(); ?> name="<?php echo esc_attr( $name ); ?>" value="<?php echo $kit; ?>" type="radio" <?php echo $this->generate_group_data( $kit_data[ 'controls' ] ); ?> <?php checked($kit, sanitize_title( $this->value() ) ); ?> />
                <span class="label"><?php echo esc_attr( $kit_data[ 'title' ] ); ?></span>
                <img src="<?php echo get_template_directory_uri() ; ?>/inc/customizer/assets/images/style-kits/<?php echo esc_attr( $kit ); ?>.jpg" alt="" class="style-kit-preview" />
            </label>
        </p>

    <?php endforeach; ?>

    <?php if ( $this->description ) : ?>
        <p><?php echo $this->description; ?></p>
    <?php endif; ?>

<style>
.style-kit-preview {
    box-shadow: 0 0 0 3px transparent, 0px 2px 5px -1px rgba(0, 0, 0, 0.08);
    border-radius: 3px;
}

#customize-control-style-kit input {
    visibility: hidden;
}

#customize-control-style-kit label {
    margin-left: 0;
}

#customize-control-style-kit .label {
    display: none;
}

#customize-control-style-kit input:checked ~ img {
    box-shadow: 0 0 0 3px #00a0d4;
}
</style>

    <?php
    }
}
