<?php

class CFPerkInfoinOrder {
    /* Construct the Function without Creating the Object */

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box_for_order_table'));
    }

    public static function add_meta_box_for_order_table() {
        add_meta_box(__('Perk Information', 'galaxyfunder'), __('Perk Information', 'galaxyfunder'), array('CFPerkInfoinOrder', 'list_perk_information_in_order_table'), 'shop_order', 'normal', 'low');
    }

    public static function list_perk_information_in_order_table() {
        $orderid = $_GET['post'];
        $perkclaimedvalue = false;
        $createobject = new WC_Order($orderid);
        ?>
        <table class="wp-list-table widefat fixed posts">
            <thead>
            <th>
                <?php _e("Perk Name", 'galaxyfunder'); ?>
            </th>
            <th>
                <?php _e("Perk Associated Product", 'galaxyfunder'); ?>
            </th>
            <th>
                <?php _e("Perk Products", 'galaxyfunder'); ?>
            </th>
        </thead>
        <tbody>
            <?php
            foreach ($createobject->get_items() as $value) {
                $getperkname = get_post_meta($orderid, 'perkname' . $value['product_id'], true);
                $getperkproduct = get_post_meta($orderid, 'perk_choosed_product' . $value['product_id'], true);
                if ($getperkname != '') {
                    ?>
                    <tr>
                        <td><?php echo $getperkname; ?></td>
                        <td><?php echo get_the_title($value['product_id']); ?></td>
                        <td><?php echo $getperkproduct != '' ? get_the_title($getperkproduct) : '---'; ?></td>
                    </tr>
                    <?php
                    $perkclaimedvalue = true;
                }
            }
            if ($perkclaimedvalue != true) {
                ?>
                <tr>
                    <td colspan="3"><?php _e("No Perks Associated for this Order", 'galaxyfunder'); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
        <?php
    }

}

new CFPerkInfoinOrder();
