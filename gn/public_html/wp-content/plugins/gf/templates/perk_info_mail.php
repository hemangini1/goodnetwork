<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<p><?php printf(__("Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:", 'woocommerce'), get_option('blogname')); ?></p>
<?php
$orderid = get_option('check');
$order = new WC_Order($orderid);
?>
<h2><?php printf(__('Order #%s', 'woocommerce'), $orderid); ?></h2>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
    <thead>
        <tr>
            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Product', 'woocommerce'); ?></th>
            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Quantity', 'woocommerce'); ?></th>
            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Price', 'woocommerce'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        echo $order->email_order_items_table(true, false, true);
        //echo var_dump($order);
        ?>

    </tbody>
    <tfoot>
        <?php
        if ($totals = $order->get_order_item_totals()) {
            $i = 0;
            foreach ($totals as $total) {
                $i++;
                ?><tr>
                    <th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ($i == 1) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
                    <td style="text-align:left; border: 1px solid #eee; <?php if ($i == 1) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
                </tr><?php
            }
        }
        ?>
    </tfoot>
</table>
<?php
//echo 10;
//echo $orderid;
$perkclaimedvalue = false;
$createobject = new WC_Order($orderid);
?>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
    <thead>
        <tr>
            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_perk_label'); ?></th>
            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_perk_associated_contribution'); ?></th>
            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_Perk_Products'); ?></th>
        </tr>
    </thead>
    <?php
    foreach ($createobject->get_items() as $value) {
        $getperkname = get_post_meta($orderid, 'perkname' . $value['product_id'], true);
        $getperkproduct = get_post_meta($orderid, 'perk_choosed_product' . $value['product_id'], true);
        if ($getperkname != '') {
            ?>
            <tr>
                <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $getperkname; ?></th>
                <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo get_the_title($value['product_id']); ?></th>
                <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $getperkproduct != '' ? get_the_title($getperkproduct) : '---'; ?></th>
            </tr>
            <?php
            $perkclaimedvalue = true;
        }
    }
    if ($perkclaimedvalue != true) {
        ?>
        <tr>
            <th colspan="3" style="text-align: center; border: 1px solid #eee;"> <?php echo get_option('contribution_mail_Perk_perk_empty'); ?> </th>
        </tr>
        <?php
    }
    ?>
</table>
