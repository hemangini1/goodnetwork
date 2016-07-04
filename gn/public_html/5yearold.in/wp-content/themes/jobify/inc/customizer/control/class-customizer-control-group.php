<?php
/**
 * Control Group
 *
 * A single control that updates multiple
 *
 * @since Listify 1.3.0
 */

class Listify_Customize_Group_Control extends WP_Customize_Control {

    public function generate_group_data( $group_items ) {
        $output = array();

        foreach ( $group_items as $key => $value ) {
            $output[ $key ] = $value;
        }

        return "data-controls='" . json_encode( $output )  . "'";
    }

}
