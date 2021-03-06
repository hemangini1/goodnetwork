<?php
/* /
  Plugin Name: Galaxy Funder
  Plugin URI:
  Description:  Galaxy Funder is a WooCommerce Crowdfunding System.
  Version: 7.5
  Author: Fantastic Plugins
  Author URI:
  / */

//require_once(ABSPATH . 'wp-admin/includes/screen.php');
class CrowdFunding {

    public function __construct() {

        add_action('woocommerce_order_status_' . CrowdFunding::get_order_status_for_contribution(), array($this, 'update_perk_claim_main_function'));

        add_action('woocommerce_order_status_' . CrowdFunding::get_order_status_for_contribution(), array($this, 'crowdfunding_adminpage_values'), 1, 100);

        add_action('admin_head', array($this, 'crowdfunding_sold_individually'));

        add_action('admin_head', array($this, 'add_script_to_admin'));

        if (get_option('cf_donation_table_position') == '1') {
            add_action('woocommerce_before_single_product_summary', array($this, 'crowdfunding_table'));
        } elseif (get_option('cf_donation_table_position') == '2') {
            add_action('woocommerce_after_single_product', array($this, 'crowdfunding_table'));
        } else {
            add_action('woocommerce_after_single_product_summary', array($this, 'crowdfunding_table'));
        }
        add_shortcode('gf_funders_table_for_campaign', array($this, 'crowdfunding_table_shortcode'));

        if (get_option('cf_author_info_table_position') == '1') {
            add_action('woocommerce_before_single_product_summary', array($this, 'get_author_information'));
        } elseif (get_option('cf_author_info_table_position') == '2') {
            add_action('woocommerce_after_single_product', array($this, 'get_author_information'));
        } else {
            add_action('woocommerce_after_single_product_summary', array($this, 'get_author_information'));
        }

        add_filter('woocommerce_get_availability', array($this, 'crowdfunding_change_out_of_stock_caption'));

        /**
         * Hook is woocommerce_settings_tabs_array to register the settings tab in admin settings
         */
        add_filter('woocommerce_cf_settings_tabs_array', array($this, 'crowdfunding_admin_tab'), 100);


        add_filter('woocommerce_cf_settings_tabs_array', array($this, 'crowdfunding_admin_new_tab'), 150);

        /**
         * WooCommerce Update Options for Crowdfunding
         */
        add_action('woocommerce_update_options_crowdfunding', array($this, 'crowdfunding_update_settings'));

        /**
         * Init the Default Settings on Page Load for Custom Field in Admin Settings
         */
        add_action('init', array($this, 'crowdfunding_default_settings'));

        /**
         * Settings Tab for CrowdFunding Registering Admin Settings
         */
        add_action('woocommerce_cf_settings_tabs_crowdfunding', array($this, 'crowdfunding_register_admin_settings'));

        /**
         * Hook to Check the WooCommerce Plugin is active or not using when admin is initialized
         */
        //add_action('admin_init', array($this, 'check_woocommerce_is_active'));

        /**
         * Hook to override the price in product page using woocommerce_get_price_html
         */
        add_filter('woocommerce_get_price_html', array($this, 'crowdfunding_remove_product_pricing'), 10, 2);

        /**
         * Add Admin Options for Custom Field in WooCommerce Product Options General Product Data
         */
        add_action('woocommerce_product_options_pricing', array($this, 'crowdfunding_add_custom_field_admin_settings'));

        /**
         * Process the Custom WooCommerce General fields (Saving the data on click save changes/update)
         */
        add_action('woocommerce_process_product_meta', array($this, 'crowdfunding_save_product_post'));

        /**
         * Load jQuery library using enqueue scripts method
         */
        add_action('wp_enqueue_scripts', array($this, 'crowdfunding_woocommerce_enqueue_scripts'));

        /**
         * Load jQuery library in admin page with enqueue scripts method
         */
        add_action('admin_enqueue_scripts', array($this, 'crowdfundingadminenqueuescript'));

        /**
         * Append the Script on Head Part of HTML using hook wp_head
         */
        add_action('woocommerce_before_add_to_cart_button', array($this, 'add_crowdfunding_input_field'));

        add_action('woocommerce_before_add_to_cart_button', array($this, 'add_script_to_crowdfunding_input_field'));

        /**
         * Append the Script on Head Part of admin HTML using hook admin_head
         */
        add_action('admin_head', array($this, 'crowdfunding_show_hide_custom_field'));

        /**
         * Get the Ajax Request for CrowdFunding add to cart
         */
        //add_action('wp_ajax_nopriv_singleproductcrowdfundingprice', array($this, 'crowdfunding_single_product_add_to_cart'));
        //add_action('wp_ajax_singleproductcrowdfundingprice', array($this, 'crowdfunding_single_product_add_to_cart'));

        /**
         * Alter the cart price on using woocommerce_before_calculate_totals
         */
        add_action('woocommerce_before_calculate_totals', array($this, 'crowdfunding_alter_cart_prices'));

        /**
         * Ajax Request for Altering the Cart Prices
         */
        //add_action('wp_ajax_nopriv_singleproductcrowdfundingprice', array($this, 'crowdfunding_alter_cart_prices'));
        //add_action('wp_ajax_singleproductcrowdfundingprice', array($this, 'crowdfunding_alter_cart_prices'));


        /**
         * Append the Script to the Head Tag
         */
        add_action('wp_head', array($this, 'crowdfunding_custom_product_price_update_to_cart'));

        /*
         * Call to set the Crowdfunding amount as the Product Price
         */
        add_action('woocommerce_add_to_cart', array($this, 'set_gf_contribution_amount_session'), 1, 5);

        /*
         * Call to change the contribution amount as the Product Price
         *
         */
        add_action('woocommerce_before_calculate_totals', array($this, 'set_gf_contribution_amount_as_product_price'), 1, 1);

        /* Call for saving the contributor's name in the contribution order id */

        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_gf_contributor_name_in_order'));



        if (get_option('cf_load_woocommerce_template') == '1') {
            add_filter('woocommerce_locate_template', array($this, 'crowdfunding_woocommerce_locate_template'), 1, 3);
        }

        add_action('wp_head', array($this, 'new_stock_checker'));

        add_action('admin_init', array($this, 'reset_changes_crowdfunding'));

        add_action('plugins_loaded', array($this, 'cf_translate_file'));

        add_action('admin_head', array($this, 'crowdfunding_head_script'));

        add_action('admin_menu', array($this, 'register_my_custom_submenu_page'));

        add_action('init', array($this, 'cf_do_output_buffer'));

        add_action('woocommerce_single_product_summary', array($this, 'checkingfrontend_galaxy'));

        add_action('woocommerce_single_product_summary', array($this, 'cf_css_function'));

        add_action('admin_head', array($this, 'select_roles_to_enable_frontend_campaign_submission'));

        if (get_option('cf_add_to_cart_redirection') == '2') {

            add_filter('woocommerce_add_to_cart_redirect', array($this, 'cf_add_to_cart_redirect_to_checkout'));
        }

        if (get_option('cf_add_to_cart_redirection') == '1') {

            add_filter('woocommerce_add_to_cart_redirect', array($this, 'cf_add_to_cart_redirect_to_cart'));
        }

        add_action('woocommerce_email_after_order_table', array($this, 'add_perk_info_in_mail'), 10, 1);
    }

    /**
     * Check the WooCommerce Plugin is Active or Not If not in active deactivate Crowdfunding Plugin Also
     */
    public static function check_woocommerce_is_active() {
        $woocommerce = "woocommerce/woocommerce.php";
        $mainpluginpath = "gf/crowdfunding.php";
        if (!is_plugin_active($woocommerce)) {
            deactivate_plugins($mainpluginpath);
        }
    }

    public static function select_roles_to_enable_frontend_campaign_submission() {
        global $woocommerce;
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'crowdfunding_callback') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery('#cf_campaign_submission_frontend_exclude_role_control').chosen();
                <?php } else { ?>
                            jQuery('#cf_campaign_submission_frontend_exclude_role_control').select2();
                <?php } ?>
                    });
                </script>
                <?php
            }
        }
    }

    public static function add_perk_info_in_mail($order) {
        $status = $order->post_status;
        $w_status = str_replace('wc-', '', $status);
        if (is_array(get_option('cf_add_contribution'))) {
            foreach (get_option('cf_add_contribution') as $selected_status) {
                $my_status = $selected_status;
            }
        } else {
            $my_status = get_option('cf_add_contribution');
        }
        if ($w_status == $my_status) {
            $items = $order->get_items();
            foreach ($items as $item) {
                $product_id = $item['product_id'];
                if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {
                    echo self::perk_table($order->id);
                }
            }
        }
    }

    public static function perk_table($order_id) {
        $order = new WC_Order($order_id);
        $items = $order->get_items();
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {
                ob_start();
                ?>
                <table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_perk_label'); ?></th>
                            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_perk_associated_contribution'); ?></th>
                            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_Perk_Products'); ?></th>
                            <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_perk_quantity'); ?></th>
                        </tr>
                    </thead>
                    <?php
                    $explode = get_post_meta($order_id, 'getlistofquantities', true);
                    if (is_array($explode)) {
                        foreach ($explode as $exp) {
                            $iter = explode('_', $exp);
                            $iteration_id = $iter[0];
                            $quantity = $iter[1];
                            $getallcampaignperks = get_post_meta($product_id, 'perk', true);
                            $perkname = $getallcampaignperks[$iteration_id]['name'];
                            $perkproduct = $getallcampaignperks[$iteration_id]['choose_products'];
                            ?>
                            <tr>
                                <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $perkname; ?></th>
                                <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo get_the_title($product_id); ?></th>
                                <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $perkproduct != '' ? get_the_title($perkproduct) : '---'; ?></th>
                                <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $quantity != '' ? $quantity : '---'; ?></th>
                            </tr>
                            <?php
                            $perkclaimedvalue = true;
                        }
                    } else {
                        ?>
                        <tr>
                            <th colspan="4" style="text-align: center; border: 1px solid #eee;"> <?php echo get_option('contribution_mail_Perk_perk_empty'); ?> </th>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
                $perk_table = ob_get_clean();
                return $perk_table;
            }
        }
    }

    public static function cf_css_function() {
        global $product;
        if (get_post_meta($product->id, '_crowdfundingcheckboxvalue', true) == 'yes') {
            ?>
            <div>
                <style type="text/css" >
                    p.stock .out-of-stock {
                        float:left !important;
                    }

                    .cf_container_galaxy{
                        min-height:60px;
                        height:auto;
                        width:auto;
                    }

                    .cf_product_selection {
                        width:250px;
                    }
                    .chosen-results {
                        clear: both !important;
                    }

                    p.stock .out-of-stock {
                        float:left !important;
                    }

                    .cf_container_galaxy{
                        /*          border:1px solid red;*/
                        min-height:60px;
                        height:auto;
                        width:auto;
                    }
                    p.submit {
                        display:none;
                    }
                    #mainforms {
                        display:none;
                    }
                    .newone<?php echo $post->ID; ?> {
                        display:none !important;
                    }
                    .outofstock<?php echo $post->ID; ?> {
                        //display:none !important;
                    }
                    #cf_product_selection {
                        width:250px;
                    }

                    .perkrule {
                        display:inline-table;
                        background:#ccc;
                        border-radius: 10px;
                        padding-left:10px;
                        padding-right:10px;
                        margin-bottom:10px;
                        width:100%;
                    }
                    .disableperkrule {
                        display:inline-table;
                        background:#ccc;
                        border-radius: 10px;
                        padding-left:10px;
                        padding-right:10px;
                        margin-bottom:10px;
                        width:100%;
                    }
                    .h5perkrule {
                        margin:5px 0;
                    }
                    .h6perkrule {
                        margin-top:10px;
                        margin-bottom:20px;
                        padding-bottom:10px;
                        border-bottom:1px solid #fbf9ee;
                    }
                    .perkruledescription {
                        margin-bottom:10px;
                    }
                    .perkruleclaimprize {
                        margin-bottom:14px;
                    }
                    .perkrule:hover {
                        background: #99ccff;
                        cursor:pointer;
                    }
                    .selected {
                        background:#99ccff;
                    }
                    .nodropclass {
                        display:inline-table;
                        background:#99ccff;
                        border-radius: 10px;
                        padding-left:10px;
                        padding-right:10px;
                        margin-bottom:10px;
                        width:100%;
                        cursor:no-drop;
                    }

                    .perkrule {
                        display:inline-table;
                        background:#ccc;
                        border-radius: 10px;
                        padding-left:10px;
                        padding-right:10px;
                        margin-bottom:10px;
                        width:100%;
                    }
                    .disableperkrule {
                        display:inline-table;
                        background:#ccc;
                        border-radius: 10px;
                        padding-left:10px;
                        padding-right:10px;
                        margin-bottom:10px;
                        width:100%;
                    }
                    .h5perkrule {
                        margin:5px 0;
                    }
                    .h6perkrule {
                        margin-top:10px;
                        margin-bottom:20px;
                        padding-bottom:10px;
                        border-bottom:1px solid #fbf9ee;
                    }
                    .perkruledescription {
                        margin-bottom:10px;
                    }
                    .perkruleclaimprize {
                        margin-bottom:14px;
                    }
                    .perkrule:hover {
                        background: #99ccff;
                        cursor:pointer;
                    }
                    .selected {
                        background:#99ccff;
                    }
                    .nodropclass {
                        display:inline-table;
                        background:#99ccff;
                        border-radius: 10px;
                        padding-left:10px;
                        padding-right:10px;
                        margin-bottom:10px;
                        width:100%;
                        cursor:no-drop;
                    }
                    .cf_price {
                        color:#85AD74;
                        display:block;
                        font-weight:400;
                        margin-bottom:0.5em;
                    }
                    .fb_edge_widget_with_comment span.fb_edge_comment_widget iframe.fb_ltr {
                        display: none !important;
                    }
                    .fb-like{
                        height: 20px !important;
                        overflow: hidden !important;
                    }
                    .fb_iframe_widget {
                        display:inline-flex !important;
                    }
                    .twitter-share-button {
                        width:88px !important;
                    }
                </style>
            </div>
            <?php
        }
    }

//load template
    public static function load_email_template($orderid) {
        update_option('check', $orderid);
        define('SUBSCRIPTION_TEMPLATE_PATH', plugin_dir_path(__FILE__) . 'templates/');
        $subject = get_option('fp_renewal_subscription_subject');
        $message = get_option('fp_renewal_subscription_message');
        ob_start();
        if (function_exists('wc_get_template')) {
            wc_get_template('emails/email-header.php', array('email_heading' => "$subject"));
            $order = new WC_Order($orderid);

            wc_get_template('perk_info_mail.php', $args = array('email_message' => "$message"), $template_path = '', SUBSCRIPTION_TEMPLATE_PATH);
            wc_get_template('emails/email-footer.php');
        }
        $new = ob_get_clean();
        return $new;
    }

    public static function prevent_header_already_sent_problem() {
        ob_start();
    }

//mail sending process
    public static function woocommerce_mail_perk($orderid) {
        $order = new WC_Order($orderid);
        $items = $order->get_items();
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {

                $message = CrowdFunding::load_email_template($orderid);

                $to = get_post_meta($orderid, '_billing_email', true);

                $subject = get_option('fp_renewal_subscription_subject');

                $mailer = WC()->mailer();

                $mailer->send($to, $subject, $message, '', '');
            }
        }
    }

    /*
     * Crowdfunding author information of created campaign in a single product page
     */

    public static function get_author_information() {
        global $post;
        $author_id = $post->post_author;
        $user_email = get_the_author_meta('user_email', $author_id);
        $firstname = get_the_author_meta('first_name', $author_id);
        $lastname = get_the_author_meta('last_name', $author_id);
        $nickname = get_the_author_meta('user_nicename', $author_id);
        $biography = get_the_author_meta('description', $author_id);
        $news = get_the_author_meta('country', $author_id);
        $getpostmeta = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
        if ($getpostmeta == 'yes') {
            if (get_option('cf_author_table_show_hide') == '1') {
                ?>
                <h3><?php echo get_option('cf_author_info_heading'); ?></h3>
                <table>
                    <tr><td><?php
                            if (get_option('cf_avatar_show_hide') == '1') {
                                echo get_avatar($author_id, get_option('cf_avatar_width_height'));
                            }
                            ?></td><td>
                            <?php
                            if (get_option('cf_author_name_show_hide') == '1') {
                                if ($firstname != '') {
                                    if (function_exists('bp_core_get_user_domain')) {
                                        if (get_option('cf_check_buddypress_link_is_active') == '1') {
                                            echo get_option('cf_author_name_label');
                                            ?>: <a href="<?php echo bp_core_get_user_domain($author_id); ?>"> <?php echo $firstname . " " . $lastname; ?></a><br><?php
                                            } else {
                                                echo get_option('cf_author_name_label');
                                                ?>: <?php echo $firstname . " " . $lastname; ?><br>
                                            <?php
                                        }
                                    } else {
                                        echo get_option('cf_author_name_label');
                                        ?>: <?php echo $firstname . " " . $lastname; ?><br>
                                        <?php
                                    }
                                } else {
                                    if ($nickname != '') {
                                        if (function_exists('bp_core_get_user_domain')) {
                                            if (get_option('cf_check_buddypress_link_is_active') == '1') {
                                                echo get_option('cf_author_name_label');
                                                ?>: <a href="<?php echo bp_core_get_user_domain($author_id); ?>"><?php echo $nickname; ?></a><br> <?php
                                            } else {
                                                echo get_option('cf_author_name_label');
                                                ?>: <?php echo $nickname; ?><br>
                                                <?php
                                            }
                                        } else {
                                            echo get_option('cf_author_name_label');
                                            ?>: <?php echo $nickname; ?><br>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                            <?php
                            if (get_option('cf_author_nick_name_show_hide') == '1') {
                                if ($nickname != '') {
                                    ?>
                                    <?php echo get_option('cf_author_nick_name_label'); ?>: <?php echo $nickname; ?><br>
                                    <?php
                                }
                            }
                            ?>

                            <?php
                            if (get_option('cf_author_email_show_hide') == '1') {
                                if ($user_email != '') {
                                    ?>
                                    <?php echo get_option('cf_author_email_label'); ?>: <?php echo $user_email; ?><br>
                                    <?php
                                }
                            }
                            ?>
                            <?php
                            if (get_option('cf_author_biography_show_hide') == '1') {
                                if ($biography != '') {
                                    ?>
                                    <?php echo get_option('cf_author_biography_label'); ?>: <?php echo $biography; ?><br>
                                    <?php
                                }
                            }
                            ?>
                            <?php
                            if (function_exists('userpro_get_badge')) {
                                if (get_option('cf_check_userpro_country_is_active') == '1') {
                                    if (get_user_meta($author_id, 'country', true) != '') {
                                        if (get_option('cf_author_country_show_hide') == '1') {
                                            echo get_option('cf_author_country_label');
                                            ?>: <?php
                                            echo userpro_get_badge('country_big', $author_id);
                                            echo get_user_meta($author_id, 'country', true);
                                        }
                                    }
                                }
                            }
                            ?>
                        </td></tr>
                </table>
                <?php
            }
        }
    }

//get the order status from option
    public static function get_order_status_for_contribution() {
        $string = (array) get_option('cf_add_contribution');

        foreach ($string as $value) {

            $order_status = $value;

            return $order_status;
        }
    }

    public static function fetch_shipping_address($post_id) {
        ob_start();
        $userid = get_post_field('post_author', $post_id);
        /* Shipping Information for the Corresponding USER/AUTHOR */
        $ship_first_name = get_user_meta($userid, 'shipping_first_name', true);
        $ship_last_name = get_user_meta($userid, 'shipping_last_name', true);
        $ship_company = get_user_meta($userid, 'shipping_company', true);
        $ship_address1 = get_user_meta($userid, 'shipping_address_1', true);
        $ship_address2 = get_user_meta($userid, 'shipping_address_2', true);
        $ship_city = get_user_meta($userid, 'shipping_city', true);
        $ship_country = get_user_meta($userid, 'shipping_country', true);
        $ship_postcode = get_user_meta($userid, 'shipping_postcode', true);
        $ship_state = get_user_meta($userid, 'shipping_state', true);
        ?>
        <table cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <th scope="col" ><?php _e('Shipping Address', 'galaxyfunder'); ?></th>
                </tr>
                <tr>
                    <td scope="col"><?php echo $ship_company; ?></td>
                </tr>
                <tr>
                    <td scope="col"><?php echo $ship_first_name . ' ' . $ship_last_name; ?></td>
                </tr>
                <tr>
                    <td scope="col"><?php echo $ship_address1; ?></td>
                </tr>
                <tr>
                    <td scope="col"><?php echo $ship_address2; ?></td>
                </tr>
                <tr>
                    <td scope="col"><?php echo $ship_city . '-' . $ship_postcode; ?></td>
                </tr>
                <tr>
                    <td scope="col"><?php echo $ship_state; ?></td>
                </tr>
                <tr>
                    <td scope="col"><?php echo $ship_country; ?></td>
                </tr>
            </tbody>
        </table>

        <?php
        $shipping_address = ob_get_clean();
        return $shipping_address;
    }

    public static function get_woocommerce_formatted_price($price) {
        if (function_exists('woocommerce_price')) {
            return woocommerce_price($price);
        } else {
            if (function_exists('wc_price')) {
                return wc_price($price);
            }
        }
    }

    /**
     * Crowdfunding table function to display list of donaters in this product it is applicable only the order has made completed
     */
    public static function crowdfunding_table() {
        global $post;
        global $woocommerce;
        $products = array();
        $i = 0;
        ?>
        <?php $cf_display_show_hide = get_option('cf_display_search_box'); ?>
        <?php $cf_page_size_show_hide = get_option('cf_display_page_size'); ?>

        <?php $cf_button_color = get_option('cf_button_color'); ?>
        <?php $cf_button_text_color = get_option('cf_button_text_color'); ?>
        <?php $cf_selected_button_color = get_option('cf_selected_button_color'); ?>
        <?php $cf_selected_button__text_color = get_option('cf_selected_button_text_color'); ?>
        <style type="text/css">
            .cf_amount_button{
                float:left;
                width: 85px;
                margin-right:10px;
                margin-top:10px;
                height:50px;
                border: 1px solid #ddd;
                background: #<?php echo $cf_button_color; ?>;
                color:#<?php echo $cf_button_text_color; ?>;
                text-align: center;
                padding-top: 10px;
                cursor: pointer;
                <?php if (get_option('cf_button_box_shadow') == '1') { ?>
                    box-shadow: 3px 3px 2px  #888888;
                <?php } else { ?>
                    box-shadow: none;
                <?php } ?>
            }
            .cf_amount_button_clicked{
                background: #<?php echo $cf_selected_button_color; ?>;
                color:#<?php echo $cf_selected_button__text_color; ?>;
            }
        </style>
        <?php
        $cf_search_show_hide = get_option('cf_display_search_box');
        $cf_page_size_show_hide = get_option('cf_display_page_size');
        $showhide_serialnumber = get_option('cf_serial_number_show_hide');
        $showhide_contributorimage = get_option('cf_contributor_image_show_hide');
        $showhide_contributorimagesize = get_option('cf_contributor_image__size_label');
        $showhide_contributorname = get_option('cf_contributor_name_show_hide');
        $showhide_contributoremail = get_option('cf_contributor_email_show_hide');
        $showhide_contribution = get_option('cf_contribution_show_hide');
        $showhide_date = get_option('cf_date_column_show_hide');
        $showhide_perkname = get_option('cf_perk_name_column_show_hide');
        $showhide_perkamount = get_option('cf_perk_amount_column_show_hide');
        $getpostmeta = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
        if ($getpostmeta == 'yes') {
            $showcontributors = get_post_meta($post->ID, '_crowdfunding_showhide_contributor', true);
            if ($showcontributors == 'yes') {
                if (get_option('cf_display_donation_table') == 'on') {
                    ?>
                    <?php
                    if (function_exists('wc_get_order_statuses')) {
                        $getpoststatus = array_keys(wc_get_order_statuses());
                    } else {
                        $getpoststatus = 'publish';
                    }

                    $listofcontributedorderids = array_unique(array_filter((array) get_post_meta($post->ID, 'orderids', true)));
                    if (is_array($listofcontributedorderids)) {
                        foreach ($listofcontributedorderids as $order) {

                            $myorderid = $order;
                            $order = new WC_Order($order);
                            foreach ($order->get_items() as $item) {
                                $products = array();
                                $product_id = $item['product_id'];
                                $products[] = $product_id;
                                if (in_array($post->ID, $products)) {
                                    $newpostid = $post->ID;
                                    if ($order->status == CrowdFunding::get_order_status_for_contribution()) {
                                        if ($i == 0) {
                                            if ($cf_search_show_hide == 'on' && $cf_page_size_show_hide == 'off') {

                                                echo '<p class = "single_product_contribution_table" style="display:inline-table;"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . '</p>';
                                            }
                                            if ($cf_page_size_show_hide == 'on' && $cf_search_show_hide == 'off') {
                                                echo '<p style="display:inline-table;margin-left:220px;"> ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size" style="display:inline-table">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                                            }
                                            if ($cf_search_show_hide == 'on' && $cf_page_size_show_hide == 'on') {

                                                echo '<p style="display:inline-table"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                                            }
                                            ?>
                                            <script type="text/javascript">
                                                jQuery(function () {
                                                    jQuery('.single_product_contribution_table').footable();
                                                    jQuery('.single_product_contribution_table').footable().bind('footable_filtering', function (e) {
                                                        var selected = jQuery('.filter-status').find(':selected').text();
                                                        if (selected && selected.length > 0) {
                                                            e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                                                            e.clear = !e.filter;
                                                        }
                                                    });
                                                    jQuery('#change-page-size').change(function (e) {
                                                        e.preventDefault();
                                                        var pageSize = jQuery(this).val();
                                                        jQuery('.footable').data('page-size', pageSize);
                                                        jQuery('.footable').trigger('footable_initialized');
                                                    });


                                                });
                                            </script>
                                            <table class = "single_product_contribution_table  demo shop_table my_account_orders table-bordered" data-page-navigation=".pagination" id="single_product_contribution_table" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">
                                                <thead>
                                                    <tr>
                                                        <?php if ($showhide_serialnumber == '1') { ?>
                                                            <th class="cf_serial_number_label" id="cf_serial_number_label" data-toggle="true" data-sort-initial = "true"><?php echo get_option('cf_serial_number_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contributorimage == '1') { ?>
                                                            <th class="cf_contributor_image_label" id="cf_contributor_image_label"><?php echo get_option('cf_contributor_image_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contributorname == '1') { ?>
                                                            <th class="cf_contributor_label" id="cf_contributor_label"><?php echo get_option('cf_contributor_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contributoremail == '1') { ?>
                                                            <th class="cf_contributor_email_label" id="cf_contributor_email_label"><?php echo get_option('cf_contributor_email_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contribution == '1') { ?>
                                                            <th class="cf_contribution_label" id="cf_contribution_label" data-hide="phone"><?php echo get_option('cf_donation_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_perkname == '1') { ?>
                                                            <th class="cf_contribution_perk_name" id="cf_contribution_perk_name" data-hide="phone"><?php echo get_option('cf_perk_name_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_perkamount == '1') { ?>
                                                            <th class="cf_contribution_perk_amount" id="cf_contribution_perk_amount" data-hide="phone"><?php echo get_option('cf_perk_amount_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if (get_option('cf_perk_quantity_column_show_hide') == '1') { ?>
                                                            <th class="cf_perkquantity" id="cf_perk_label" data-hide="phone,tablet"><?php echo get_option('cf_perk_quantity_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_date == '1') { ?>
                                                            <th class="cf_date_label" id="cf_date_label" data-hide="phone,tablet"><?php echo get_option('cf_date_label'); ?></th>
                                                        <?php } ?>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                }
                                                $i++;
                                                ?>
                                                <tr>
                                                    <?php if ($showhide_serialnumber == '1') { ?>
                                                        <td class='serial_id' data-value="<?php echo $i; ?>" id='serial_id'><?php echo $i; ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($showhide_contributorimage == '1') { ?>
                                                        <td class="cf_billing_name_image" id="cf_billing_name_image"> <?php echo get_avatar($order->billing_email, $showhide_contributorimagesize); ?>
                                                        <?php } ?>
                                                        <?php if ($showhide_contributorname == '1') { ?>
                                                        <td class='cf_billing_first_name' id='cf_billing_first_name'>
                                                            <?php
                                                            if (get_post_meta($myorderid, 'contributor_list_for_campaign', true) == '') {
                                                                if (get_post_meta($order->id, 'My Checkbox', true) == '1') {
                                                                    echo 'Anonymous';
                                                                } else {
                                                                    $mark_contributor_anonymous = get_post_meta($post->ID, '_crowdfunding_contributor_anonymous', true);
                                                                    if ($mark_contributor_anonymous == 'yes') {
                                                                        echo 'Anonymous';
                                                                    } else {
                                                                        echo $order->billing_first_name . "&nbsp;" . $order->billing_last_name;
                                                                    }
                                                                }
                                                            } else {
                                                                if (get_post_meta($order->id, 'My Checkbox', true) == '1') {
                                                                    echo 'Anonymous';
                                                                } else {
                                                                    $mark_contributor_anonymous = get_post_meta($post->ID, '_crowdfunding_contributor_anonymous', true);
                                                                    if ($mark_contributor_anonymous == 'yes') {
                                                                        echo 'Anonymous';
                                                                    } else {
                                                                        echo get_post_meta($myorderid, 'contributor_list_for_campaign', true);
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    &nbsp;
                                                    <?php if ($showhide_contributoremail == '1') { ?>
                                                        <td class='cf_billing_email' id='cf_billing_email'><?php echo $order->billing_email; ?></td>
                                                    <?php } ?>
                                                    <?php if ($showhide_contribution == '1') { ?>
                                                        <td class='cf_order_total' id='cf_order_total'><?php echo CrowdFunding::get_woocommerce_formatted_price($item['line_total']); ?><br></td>
                                                    <?php } ?>
                                                    <?php if ($showhide_perkname == '1') { ?>
                                                        <td class="cf_contribution_perk_name" id="cf_contribution_perk_name"><?php
                                                            $cfperkname = get_post_meta($myorderid, 'perkname' . $post->ID, true);
                                                            if (!is_array($cfperkname)) {
                                                                if ($cfperkname != '') {
                                                                    $cfperkname = $cfperkname;
                                                                } else {
                                                                    $cfperkname = '-';
                                                                }
                                                                echo $cfperkname;
                                                            } else {
                                                                echo implode(', ', $cfperkname);
                                                            }
                                                            ?></td>
                                                    <?php } ?>
                                                    <?php if ($showhide_perkamount == '1') { ?>
                                                        <td class="cf_contribution_perk_amount" id="cf_contribution_perk_amount"><?php
                                                            $cfperkamount = (int) get_post_meta($myorderid, 'perk_maincontainer' . $post->ID, true);
                                                            if ($cfperkamount != 0) {
                                                                $cfperkamount = CrowdFunding::get_woocommerce_formatted_price($cfperkamount);
                                                            } else {
                                                                $cfperkamount = '-';
                                                            }
                                                            echo $cfperkamount;
                                                            ?></td>
                                                    <?php } ?>
                                                    <?php if (get_option('cf_perk_quantity_column_show_hide') == '1') { ?>
                                                        <td class="cf_perk_quantity" id="cf_perk_quantity">
                                                            <?php
                                                            $quantity_perk = get_post_meta($myorderid, 'explodequantity' . $post->ID, true);
                                                            $qty = array();
                                                            if ($quantity_perk != "") {
                                                                if (is_array($quantity_perk)) {
                                                                    foreach ($quantity_perk as $perkqty) {
                                                                        $explode = explode('_', $perkqty);
                                                                        $qty[] = $explode[0];
                                                                    }
                                                                    echo implode(',', $qty) . "<br>";
                                                                    unset($qty);
                                                                } else {
                                                                    $explode = explode('_', $quantity_perk);
                                                                    $qty = $explode[0];
                                                                    echo $qty;
                                                                }
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($showhide_date == '1') { ?>
                                                        <td class='cf_order_date' id='cf_order_date'><?php echo $order->order_date; ?></td>
                                                    <?php } ?>

                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="clear:both;">
                                <td colspan="7">
                                    <div class="pagination pagination-centered"></div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php
                }
            }
        }
    }

    public static function crowdfunding_table_shortcode() {
        global $post;
        global $woocommerce;
        $products = array();
        $i = 0;
        ?>
        <?php $cf_display_show_hide = get_option('cf_display_search_box'); ?>
        <?php $cf_page_size_show_hide = get_option('cf_display_page_size'); ?>
        <?php $cf_button_color = get_option('cf_button_color'); ?>
        <?php $cf_button_text_color = get_option('cf_button_text_color'); ?>
        <?php $cf_selected_button_color = get_option('cf_selected_button_color'); ?>
        <?php $cf_selected_button__text_color = get_option('cf_selected_button_text_color'); ?>
        <style type="text/css">
            .cf_amount_button{
                float:left;
                width: 85px;
                margin-right:10px;
                margin-top:10px;
                height:50px;
                border: 1px solid #ddd;
                background: #<?php echo $cf_button_color; ?>;
                color:#<?php echo $cf_button_text_color; ?>;
                text-align: center;
                padding-top: 10px;
                cursor: pointer;
                <?php if (get_option('cf_button_box_shadow') == '1') { ?>
                    box-shadow: 3px 3px 2px  #888888;
                <?php } else { ?>
                    box-shadow: none;
                <?php } ?>
            }
            .cf_amount_button_clicked{
                background: #<?php echo $cf_selected_button_color; ?>;
                color:#<?php echo $cf_selected_button__text_color; ?>;
            }
        </style>
        <?php
        $cf_search_show_hide = get_option('cf_display_search_box');
        $cf_page_size_show_hide = get_option('cf_display_page_size');
        $showhide_serialnumber = get_option('cf_serial_number_show_hide');
        $showhide_contributorname = get_option('cf_contributor_name_show_hide');
        $showhide_contributoremail = get_option('cf_contributor_email_show_hide');
        $showhide_contribution = get_option('cf_contribution_show_hide');
        $showhide_date = get_option('cf_date_column_show_hide');
        $showhide_perkname = get_option('cf_perk_name_column_show_hide');
        $showhide_perkamount = get_option('cf_perk_amount_column_show_hide');
        $getpostmeta = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
        if ($getpostmeta == 'yes') {
            if (get_option('cf_display_donation_table') == 'on') {
                ?>
                <?php
                if (function_exists('wc_get_order_statuses')) {
                    $getpoststatus = array_keys(wc_get_order_statuses());
                } else {
                    $getpoststatus = 'publish';
                }

                $listofcontributedorderids = array_unique(array_filter((array) get_post_meta($post->ID, 'orderids', true)));
                if (is_array($listofcontributedorderids)) {
                    foreach ($listofcontributedorderids as $order) {

                        $myorderid = $order;
                        $order = new WC_Order($order);
                        foreach ($order->get_items() as $item) {
                            $products = array();
                            $product_id = $item['product_id'];
                            $products[] = $product_id;
                            if (in_array($post->ID, $products)) {
                                $newpostid = $post->ID;
                                if ($order->status == CrowdFunding::get_order_status_for_contribution()) {
                                    if ($i == 0) {

                                        if ($cf_search_show_hide == 'on' && $cf_page_size_show_hide == 'off') {

                                            echo '<p class = "single_product_contribution_table" style="display:inline-table;"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . '</p>';
                                        }
                                        if ($cf_page_size_show_hide == 'on' && $cf_search_show_hide == 'off') {
                                            echo '<p style="display:inline-table;margin-left:220px;"> ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size" style="display:inline-table">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                                        }
                                        if ($cf_search_show_hide == 'on' && $cf_page_size_show_hide == 'on') {

                                            echo '<p style="display:inline-table"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                                        }
                                        ?>
                                        <table class = "single_product_contribution_table  demo shop_table my_account_orders table-bordered" data-page-navigation=".pagination" id="single_product_contribution_table" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">
                                            <thead>
                                                <tr>
                                                    <?php if ($showhide_serialnumber == '1') { ?>
                                                        <th class="cf_serial_number_label" id="cf_serial_number_label" data-toggle="true" data-sort-initial = "true"><?php echo get_option('cf_serial_number_label'); ?></th>
                                                    <?php } ?>
                                                    <?php if ($showhide_contributorname == '1') { ?>
                                                        <th class="cf_contributor_label" id="cf_contributor_label"><?php echo get_option('cf_contributor_label'); ?></th>
                                                    <?php } ?>
                                                    <?php if ($showhide_contributoremail == '1') { ?>
                                                        <th class="cf_contributor_email_label" id="cf_contributor_email_label"><?php echo get_option('cf_contributor_email_label'); ?></th>
                                                    <?php } ?>
                                                    <?php if ($showhide_contribution == '1') { ?>
                                                        <th class="cf_contribution_label" id="cf_contribution_label" data-hide="phone"><?php echo get_option('cf_donation_label'); ?></th>
                                                    <?php } ?>
                                                    <?php if ($showhide_perkname == '1') { ?>
                                                        <th class="cf_contribution_perk_name" id="cf_contribution_perk_name" data-hide="phone"><?php echo get_option('cf_perk_name_label'); ?></th>
                                                    <?php } ?>
                                                    <?php if ($showhide_perkamount == '1') { ?>
                                                        <th class="cf_contribution_perk_amount" id="cf_contribution_perk_amount" data-hide="phone"><?php echo get_option('cf_perk_amount_label'); ?></th>
                                                    <?php } ?>
                                                    <?php if (get_option('cf_perk_quantity_column_show_hide') == '1') { ?>
                                                        <th class="cf_perkquantity" id="cf_perk_label" data-hide="phone,tablet"><?php echo get_option('cf_perk_quantity_label'); ?></th>
                                                    <?php } ?>
                                                    <?php if ($showhide_date == '1') { ?>
                                                        <th class="cf_date_label" id="cf_date_label" data-hide="phone,tablet"><?php echo get_option('cf_date_label'); ?></th>
                                                    <?php } ?>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                            }
                                            $i++;
                                            ?>
                                            <tr>
                                                <?php if ($showhide_serialnumber == '1') { ?>
                                                    <td class='serial_id' data-value="<?php echo $i; ?>" id='serial_id'><?php echo $i; ?>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($showhide_contributorname == '1') { ?>
                                                    <td class='cf_billing_first_name' id='cf_billing_first_name'>
                                                        <?php
                                                        if (get_post_meta($myorderid, 'contributor_list_for_campaign', true) == '') {
                                                            if (get_post_meta($order->id, 'My Checkbox', true) == '1') {
                                                                echo 'Anonymous';
                                                            } else {
                                                                $mark_contributor_anonymous = get_post_meta($post->ID, '_crowdfunding_contributor_anonymous', true);
                                                                if ($mark_contributor_anonymous == 'yes') {
                                                                    echo 'Anonymous';
                                                                } else {
                                                                    echo $order->billing_first_name . "&nbsp;" . $order->billing_last_name;
                                                                }
                                                            }
                                                        } else {
                                                            if (get_post_meta($order->id, 'My Checkbox', true) == '1') {
                                                                echo 'Anonymous';
                                                            } else {
                                                                $mark_contributor_anonymous = get_post_meta($post->ID, '_crowdfunding_contributor_anonymous', true);
                                                                if ($mark_contributor_anonymous == 'yes') {
                                                                    echo 'Anonymous';
                                                                } else {
                                                                    echo get_post_meta($myorderid, 'contributor_list_for_campaign', true);
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                <?php } ?>
                                                &nbsp;
                                                <?php if ($showhide_contributoremail == '1') { ?>
                                                    <td class='cf_billing_email' id='cf_billing_email'><?php echo $order->billing_email; ?></td>
                                                <?php } ?>
                                                <?php if ($showhide_contribution == '1') { ?>
                                                    <td class='cf_order_total' id='cf_order_total'><?php echo CrowdFunding::get_woocommerce_formatted_price($item['line_total']); ?><br></td>
                                                <?php } ?>
                                                <?php if ($showhide_perkname == '1') { ?>
                                                    <td class="cf_contribution_perk_name" id="cf_contribution_perk_name"><?php
                                                        $cfperkname = get_post_meta($myorderid, 'perkname' . $post->ID, true);
                                                        if (!is_array($cfperkname)) {
                                                            if ($cfperkname != '') {
                                                                $cfperkname = $cfperkname;
                                                            } else {
                                                                $cfperkname = '-';
                                                            }
                                                            echo $cfperkname;
                                                        } else {
                                                            echo implode(', ', $cfperkname);
                                                        }
                                                        ?></td>
                                                <?php } ?>
                                                <?php if ($showhide_perkamount == '1') { ?>
                                                    <td class="cf_contribution_perk_amount" id="cf_contribution_perk_amount"><?php
                                                        $cfperkamount = (int) get_post_meta($myorderid, 'perk_maincontainer' . $post->ID, true);
                                                        if ($cfperkamount != 0) {
                                                            $cfperkamount = CrowdFunding::get_woocommerce_formatted_price($cfperkamount);
                                                        } else {
                                                            $cfperkamount = '-';
                                                        }
                                                        echo $cfperkamount;
                                                        ?></td>
                                                <?php } ?>
                                                <?php if (get_option('cf_perk_quantity_column_show_hide') == '1') { ?>
                                                    <td class="cf_perk_quantity" id="cf_perk_quantity">
                                                        <?php
                                                        $quantity_perk = get_post_meta($myorderid, 'explodequantity' . $post->ID, true);
                                                        if ($quantity_perk != "") {
                                                            if (is_array($quantity_perk)) {
                                                                foreach ($quantity_perk as $perkqty) {
                                                                    $explode = explode('_', $perkqty);
                                                                    $qty[] = $explode[0];
                                                                }
                                                                echo implode(',', $qty) . "<br>";
                                                                unset($qty);
                                                            } else {
                                                                $explode = explode('_', $quantity_perk);
                                                                $qty = $explode[0];
                                                                echo $qty;
                                                            }
                                                        } else {
                                                            echo '-';
                                                        }
                                                        ?>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($showhide_date == '1') { ?>
                                                    <td class='cf_order_date' id='cf_order_date'><?php echo $order->order_date; ?></td>
                                                <?php } ?>

                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="clear:both;">
                            <td colspan="7">
                                <div class="pagination pagination-centered"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?php
            }
        }
    }

    public static function add_out_of_stock() {
        global $post;
        global $woocommerce;
        $products = array();
        $i = 0;
        $checkvalue = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
        if ($checkvalue == 'yes') {
            if (get_option('cf_display_donation_table') == 'on') {
                if (function_exists('wc_get_order_statuses')) {
                    $getpoststatus = array_keys(wc_get_order_statuses());
                } else {
                    $getpoststatus = 'publish';
                }

                foreach (get_posts(array(
                    'post_type' => 'shop_order',
                    'posts_per_page' => '-1',
                    'post_status' => $getpoststatus,
                ))as $order) {
                    $order = new WC_Order($order->ID);
                    foreach ($order->get_items() as $item) {
                        $products = array();
                        $product_id = $item['product_id'];
                        $products[] = $product_id;
                        if (in_array($post->ID, $products)) {
                            $newpostid = $post->ID;
                            if ($order->status == CrowdFunding::get_order_status_for_contribution()) {

                            }
                        }
                    }
                }

                $targetvalue = get_post_meta($post->ID, '_crowdfundinggettargetprice', true);
                $ordertotal = get_post_meta($post->ID, '_crowdfundingtotalprice', true);
            }
        }
    }

    public static function crowdfunding_remove_product_pricing($price, $product) {
        global $post;
        global $woocommerce;
        $inbuilt_designs = '';
        $new_date_remain = '';
        $gettotalfunders = '';
        $order_total = get_post_meta($product->id, '_crowdfundingtotalprice', true);
        $getdate = date("m/d/Y");
        $gethour = date("h");
        $getminutes = date("i");
        $fromdate = get_post_meta($product->id, '_crowdfundingfromdatepicker', true);
        $todate = get_post_meta($product->id, '_crowdfundingtodatepicker', true);
        $tohours = get_post_meta($product->id, '_crowdfundingtohourdatepicker', true);
        $tominutes = get_post_meta($product->id, '_crowdfundingtominutesdatepicker', true);
        $fromhours = get_post_meta($product->id, '_crowdfundingfromhourdatepicker', true);
        $fromminutes = get_post_meta($product->id, '_crowdfundingfromminutesdatepicker', true);

        $timezone = wc_timezone_string();
        if ($timezone != '') {
            $timezonedate = date_default_timezone_set($timezone);
        } else {
            $timezonedate = date_default_timezone_set('UTC');
        }

        if ($fromdate != '') {
            if ($fromhours == '' || $fromminutes == '') {
                $fromdate = $fromdate . "23:59:59";
            } else {
                $time = $fromhours . ':' . $fromminutes . ':' . '00';
                $fromdate = $fromdate . $time;
            }
        } else {
            if ($tohours == '' || $tominutes == '') {
                $fromdate = $getdate;
            } else {
                $fromdate = $getdate;
                $fromhour = $gethour;
                $fromminutes = $getminutes;
            }
            update_post_meta($product->id, '_crowdfundingfromdatepicker', $getdate);
            update_post_meta($product->id, '_crowdfundingfromhourdatepicker', $gethour);
            update_post_meta($product->id, '_crowdfundingfromminutesdatepicker', $getminutes);
        }
//  if ($todate != '') {
        if ($tohours != '' || $tominutes != '') {
            $time = $tohours . ':' . $tominutes . ':' . '00';
            $datestr = $todate . $time; //Your date
        } else {
            $datestr = $todate . "23:59:59";
        }//Your date
        $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference
        $newid = $product->id;
        $crowdfunding_options = get_post_meta($newid, '_crowdfunding_options', true);
        $crowdfunding_end_method = get_post_meta($newid, '_target_end_selection', true);
        if (function_exists('get_product')) {
            if (is_product()) {
                $inbuilt_design = get_option("cf_inbuilt_design");
                $load_design = get_option('load_inbuilt_design');
                if ($inbuilt_design == '1') {
                    if ($load_design == '1') {
                        ?>
                        <style type="text/css">
                        <?php echo get_option('cf_single_product_contribution_table_default_css'); ?>
                        </style>
                        <?php
                    }
                    if ($load_design == '2') {
                        //echo '<pre>';var_dump(get_option('cf_single_product_contribution_table_default_css'));echo '</pre>';
                        ?>
                        <style type="text/css">
                        <?php
                        echo get_option('cf_single_product_contribution_table_default_css');
                        ?>
                        </style>
                        <?php
                    }
                    if ($load_design == '3') {
                        ?>
                        <style type="text/css">
                        <?php echo get_option('cf_single_product_contribution_table_default_css'); ?>
                        </style>
                        <?php
                    }
                }
                if ($inbuilt_design == '2') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_single_product_contribution_table_custom_css'); ?>
                    </style>
                    <?php ?>

                    <?php
                }
                $checktotalfunders = get_post_meta($product->id, '_update_total_funders', true);
                if (get_option('cf_funders_count_show_hide') == '1') {
                    if ($checktotalfunders != '') {
                        $gettotalfunders = '<p class="price" id="cf_update_total_funders">' . get_post_meta($product->id, '_update_total_funders', true) . '<small> ' . get_option('cf_funder_label') . '</small> </p>';
                    } else {
                        $gettotalfunders = '<p class="price" id="cf_update_total_funders"> 0 <small> ' . get_option('cf_funder_label') . '</small> </p>';
                    }
                }
                if ($tohours != '' || $tominutes != '') {
                    $time = $tohours . ':' . $tominutes . ':' . '00';
                    $datestr = $todate . $time; //Your date
                } else {
                    $datestr = $todate . "23:59:59";
                } //Your date
                $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference

                if (get_option('cf_day_left_show_hide') == '1') {
                    if ($crowdfunding_end_method == '1' || $crowdfunding_end_method == '4') {
                        if ($date >= time()) {
                            $diff = $date - time();
                            $day = ceil($diff / 86400);
                            $days = floor($diff / 86400);
                            $hours = $diff / 3600 % 24;
                            $minutes = $diff / 60 % 60;

                            $daysinhour = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                            $hour = ceil(($diff - $daysinhour * 60 * 60 * 24) / (60 * 60));
//Report
                            if ($hours > 1) {
                                $hours = $hours . ' hours ';
                            } else {
                                $hours = $hours . ' hour ';
                            }

                            if ($hour > 1) {
                                $hour = $hour . ' hours ';
                            } else {
                                $hour = $hour . ' hour ';
                            }
                            if (get_option('cf_campaign_day_time_display') == '1') {
                                if ($day > 1) {
                                    $new_date_remain = '<p class="price" id="cf_price_new_date_remain">' . $day . 'days' . " <small style='font-style:italic'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                } else {
                                    $new_date_remain = '<p class="price" id="cf_price_new_date_remain">' . $day . ' day ' . " <small style='font-style:italic'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                }
                            } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                if ($days > 1) {
                                    $new_date_remain = '<p class="price" id="cf_price_new_date_remain">' . $days . ' days ' . $hour . " <small style='font-style:italic'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                } else {
                                    $new_date_remain = '<p class="price" id="cf_price_new_date_remain">' . $days . ' day ' . $hour . " <small style='font-style:italic'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                }
                            } else {
                                if ($days > 1) {
                                    $new_date_remain = '<p class="price" id="cf_price_new_date_remain">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small style='font-style:italic' color='red'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                } else {
                                    $new_date_remain = '<p class="price" id="cf_price_new_date_remain">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small style='font-style:italic' color='red'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                }
                            }
                        } else {

                        }
                    } else {
                        $new_date_remain = '';
                    }
                }
                $products = get_product($product->id);
                $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                $minimumvalue = get_post_meta($newid, '_crowdfundinggetminimumprice', true);
                $recommendedvalue = get_post_meta($newid, '_crowdfundinggetrecommendedprice', true);
                $maximumvalue = get_post_meta($newid, '_crowdfundinggetmaximumprice', true);
                $targetvalue = get_post_meta($newid, '_crowdfundinggettargetprice', true);
                $minpricelabel = get_option('crowdfunding_min_price_tab_product');
                $recommendedpricelabel = get_option('crowdfunding_recommended_price_tab_product');
                $maximumpricelabel = get_option('crowdfunding_maximum_price_tab_product');
                $targetpricelabel = get_option('crowdfunding_target_price_tab_product');
                $totalpricelabel = get_option('crowdfunding_totalprice_label');
                $totalpricepercentlabel = get_option('crowdfunding_totalprice_percent_label');
                $payyourpricelabel = get_option('crowdfunding_payyourprice_price_tab_product');
                $hideminimum = get_post_meta($newid, '_crowdfundinghideminimum', true);
                $hidemaximum = get_post_meta($newid, '_crowdfundinghidemaximum', true);
                $hidetarget = get_post_meta($newid, '_crowdfundinghidetarget', true);
                $count2 = '';
                $count = '';
                $counter = '';
                $totalfield = '';
                $totalfieldss = '';
                $totalfielders = '';
                $totalpledgedpercentw = '';
                $countercf = '';
                $totalpledgedpercentages = '';

                $gettotalfunderers = '';
                $new_date_remains = '';
                if (($order_total != '') && ($targetvalue > 0)) {
                    $count1 = $order_total / $targetvalue;
                    $count2 = $count1 * 100;
                    $counter = number_format($count2, 0);
                    $count = $counter . "%";
                } else {
                    $count = "0%";
                }
                if ($minpricelabel != '') {
                    $minpricecaption = $minpricelabel;
                    $colonmin = " : ";
                }
                if ($maximumpricelabel != '') {
                    $maxpricecaption = $maximumpricelabel;
                    $colonmax = " : ";
                }
                if ($targetpricelabel != '') {
                    $targetpricecaption = $targetpricelabel;
                    $colontarget = " : ";
                }
                if ($totalpricelabel != '') {
                    $totalpricecaption = $totalpricelabel;
                    $colontotal = " : ";
                }
                if ($totalpricepercentlabel != '') {
                    $totalpricepercentcaption = $totalpricepercentlabel;
                    $colontotalpercent = " : ";
                }
                if ($payyourpricelabel != '') {
                    $payyourpricecaption = $payyourpricelabel;
                    $colonpay = " : ";
                }
                if ($hideminimum != 'yes') {
                    if ($minimumvalue != '') {
                        $minimumfield = "<p class = 'price' id='cf_min_price_label'><label>" . $minpricecaption . $colonmin . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($minimumvalue) . "</span></p>";
                    } else {
                        $minimumfield = '';
                    }
                } else {
                    $minimumfield = '';
                }
                if ($hidemaximum != 'yes') {
                    if ($maximumvalue != '') {
                        $maximumfield = " <p class = 'price' id='cf_max_price_label'><label>" . $maxpricecaption . $colonmax . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($maximumvalue) . "</span></p>";
                    } else {
                        $maximumfield = '';
                    }
                } else {
                    $maximumfield = '';
                }
                if ($hidetarget != 'yes') {
                    if ($targetvalue != '') {
                        $targetfield = " <p class = 'price' id='cf_target_price_label'><label>" . $targetpricecaption . $colontarget . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($targetvalue) . "</span></p>";
                    } else {
                        $targetfield = '';
                    }
                } else {
                    $targetfield = '';
                }
                if (get_option('cf_raised_amount_show_hide') == '1') {
                    if ($order_total != '') {
                        $totalfield = "<p class='price' id='cf_total_price_raised'><label>" . $totalpricecaption . $colontotal . " " . "</label><span class='amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></p>";
                    }
                    if ($order_total != '') {
                        $totalfieldss = "<p class='price' id='cf_total_price_raise' style='margin-bottom:0px;' ><span class='amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span> </p>";
                    }
                }
                if (get_option('load_inbuilt_design') == '1') {
                    if ($count2 > 0) {
                        $progress_bar_type = get_option('single_product_prog_bar_type');
                        if ($progress_bar_type == '1') {
                            if (get_option('cf_raised_percentage_show_hide') == '1') {
                                $totalpledgedpercentw = "<p class='price' id='cf_total_price_in_percentage'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            }
                            $totalpledgedpercentw .= '<div id="cf_total_price_in_percentage_with_bar" style="">';
                            if ($counter >= 100) {
                                $counter = 100;
                            }
                            $totalpledgedpercentw .= '<div id="cf_percentage_bar" style="width: ' . $counter . '%;">&nbsp;</div></div>';
                        } else {
                            if (get_option('cf_raised_percentage_show_hide') == '1') {
                                $totalpledgedpercentw = "<p class='price' id='cf_total_price_in_percentage'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            }
                            $totalpledgedpercent = "<p class='price' id='cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                            if ($counter >= 100) {
                                $counter = 100;
                            }
                            $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: ' . $counter . '%;clear:both;"><span class= "currentpledgegoal"> </span></span></div></div>';
                        }
                    } else {
                        $progress_bar_type = get_option('single_product_prog_bar_type');
                        if ($progress_bar_type == '1') {
                            if (get_option('cf_raised_percentage_show_hide') == '1') {
                                $totalpledgedpercentw = "<p class='price' id='cf_total_price_in_percentage'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            }
                            $totalpledgedpercentw .= '<div id="cf_total_price_in_percentage_with_bar" style="">';

                            $totalpledgedpercentw .= '<div id="cf_percentage_bar" style="width: 0%;">&nbsp;</div></div>';
                        } else {
                            if (get_option('cf_raised_percentage_show_hide') == '1') {
                                $totalpledgedpercentw = "<p class='price' id='cf_total_price_in_percentage'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            }
                            $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                            if ($counter >= 100) {
                                $counter = 100;
                            }
                            $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: 0px;clear:both;"><span class= "currentpledgegoal"> </span></span></div></div>';
                        }
                    }
                } else {
                    if ($count2 > 0) {
                        $progress_bar_type = get_option('single_product_prog_bar_type');
                        if ($progress_bar_type == '1') {
                            $totalpledgedpercent = "<p class='price' id='cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            $totalpledgedpercentss = '<div id="cf_total_price_in_percent_with_bar" style="">';
                            if ($counter >= 100) {
                                $counter = 100;
                            }
                            $totalpledgedpercentages = $totalpledgedpercentss . '<div id="cf_percent_bar" style="width: ' . $counter . '%; clear:both;"></div></div>';
                        } else {
                            $totalpledgedpercent = "<p class='price' id='cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                            if ($counter >= 100) {
                                $counter = 100;
                            }
                            $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: ' . $counter . '%;clear:both;"><span class= "currentpledgegoal"> </span></span></div></div>';
                        }
                    } else {
                        $progress_bar_type = get_option('single_product_prog_bar_type');
                        if ($progress_bar_type == '1') {
                            $totalpledgedpercent = "<p class='price' id='cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            $totalpledgedpercentss = '<div id="cf_total_price_in_percent_with_bar" style="">';
                            if ($counter >= 100) {
                                $counter = 100;
                            }
                            $totalpledgedpercentages = $totalpledgedpercentss . '<div id="cf_percent_bar" style="width: 0%; clear:both;"></div></div>';
                        } else {
                            $totalpledgedpercent = "<p class='price' id='cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class='amount'>" . $count . "</span></p>";
                            $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                            if ($counter >= 100) {
                                $counter = 100;
                            }
                            $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: 0px;clear:both;"><span class= "currentpledgegoal"> </span></span></div></div>';
                        }
                    }
                }
                if ($hidetarget != 'yes') {
                    if ($targetvalue != '') {
                        $targetfields = " <p class = 'price' id='cf_target_price_labels' ><label> " . __('Raised of', 'galaxyfunder') . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($targetvalue) . "</span> " . __('Goal', 'galaxyfunder') . "</p>";
                    } else {
                        $targetfields = '';
                    }
                } else {
                    $targetfields = '';
                }
                if (get_option('cf_raised_amount_show_hide') == '1') {
                    if ($order_total != '') {
                        $totalfielders = "<p class='price' id='cf_total_price_raiser' ><label></label><span class='amount'><span id='cf_total_pricer_raiser'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></span></p>";
                    }
                }
                if (get_option('cf_day_left_show_hide') == '1') {
                    if ($crowdfunding_end_method != '2' && $crowdfunding_end_method != '3') {
                        if ($date >= time()) {
                            $diff = $date - time();
                            $day = ceil($diff / 86400);
                            $days = floor($diff / 86400);
                            $hours = $diff / 3600 % 24;
                            $minutes = $diff / 60 % 60;
                            $sec = $diff % 60;

//                            $diff = $date - time(); //time returns current time in seconds
                            $daysinhour = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                            $hour = ceil(($daysinhour - $days * 60 * 60 * 24) / (60 * 60));

//Report
                            if ($hours > 1) {
                                $hours = $hours . "<small style='font-style:italic','size:0px'>" . ' hours ' . "</small>";
                            } else {
                                $hours = $hours . "<small style='font-style:italic','size:0px'>" . ' hour ' . "</small>";
                            }

                            if ($hour > 1) {
                                $hour = $hour . "<small style='font-style:italic','size:0px'>" . ' hours ' . "</small>";
                            } else {
                                $hour = $hour . "<small style='font-style:italic','size:0px'>" . ' hour ' . "</small>";
                            }
                            if (get_option('cf_campaign_day_time_display') == '1') {
                                if ($day > 1) {
                                    $new_date_remains = '<p class="price" id="cf_days_remainings"><span id="newdateremains">' . $day . "<small style='font-style:italic','size:10px'>" . 'days' . "</small>" . " </span><small style='display:block;'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                } else {
                                    $new_date_remains = '<p class="price" id="cf_days_remainings"><span id="newdateremains">' . $day . "<small style='font-style:italic'>" . ' day ' . "</small>" . "</span> <small style='display:block;'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                }
                            } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                if ($days > 1) {
                                    $new_date_remains = '<p class="price" id="cf_days_remainings"><span id="newdateremains">' . $days . "<small style='font-style:italic'>" . ' days ' . "</small>" . $hour . " </span><small style='display:block;'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                } else {
                                    $new_date_remains = '<p class="price" id="cf_days_remainings"><span id="newdateremains">' . $days . "<small style='font-style:italic'>" . ' day ' . "</small>" . $hour . "</span> <small style='display:block;'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                }
                            } else {
                                if ($days > 1) {
                                    $new_date_remains = '<p class="price" id="cf_days_remainings"><span id="newdateremains">' . $days . "<small style='font-style:italic','size:0px'>" . ' days ' . "</small>" . $hours . $minutes . "<small style='font-style:italic','size:0px'>" . ' minutes ' . "</small>" . "</span> <small style='display:block; color='red'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                } else {
                                    $new_date_remains = '<p class="price" id="cf_days_remainings"><span id="newdateremains">' . $days . "<small style='font-style:italic','size:0px'>" . ' day ' . "</small>" . $hours . $minutes . "<small style='font-style:italic','size:0px'>" . ' minutes ' . "</small>" . "</span> <small style='display:block; color='red'>" . __('left', 'galaxyfunder') . "</small>" . '</p>';
                                }
                            }
                        } else {

                        }
                    } else {
                        $new_date_remains = '';
                    }
                }
                $cfcounters = '<p class="price" id="cf_total_raised_in_percentage" style="">' . $count . '<small style="display:block;"> ' . __('FUNDED', 'galaxyfunder') . '</small></p>';

                if ($count2 > 0) {
                    $totalpledgedpercentsser = '<div id="cf_total_price_in_percenter_with_bar" style="float:left">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentageser = $totalpledgedpercentsser . '<div id="cf_percenter_bar" style="width: ' . $counter . '%;"></div></div>';
                } else {
                    $totalpledgedpercentsser = '<div id="cf_total_price_in_percenter_with_bar" style="float:left">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentageser = $totalpledgedpercentsser . '<div id="cf_percenter_bar" style="width: 0%;"></div></div>';
                }

                if (get_option('cf_funders_count_show_hide') == '1') {
                    if ($checktotalfunders != '') {
                        $gettotalfunderers = '<p class="price" id="cf_update_total_funderers"><span class="totalfundercounts">' . get_post_meta($product->id, '_update_total_funders', true) . '</span><small style="display:block;"> ' . get_option('cf_funder_label') . '</small> </p>';
                    } else {
                        $gettotalfunderers = '<p class="price" id="cf_update_total_funderers"><span class="totalfundercounts"> 0 </span><small style="display:block;"> ' . get_option('cf_funder_label') . '</small> </p>';
                    }
                }
                if ($hidetarget != 'yes') {
                    if ($targetvalue != '') {
                        $targetfielders = " <p class = 'price' id='cf_target_price_labelers' ><small><label> " . __('Raised of', 'galaxyfunder') . "  </label><span class = 'amount'> &nbsp;" . CrowdFunding::get_woocommerce_formatted_price($targetvalue) . " &nbsp;</span> " . __('Goal', 'galaxyfunder') . "</small></p>";
                    } else {
                        $targetfielders = '';
                    }
                } else {
                    $targetfielders = '';
                }
                if ($checkvalue == 'yes') {
                    if (get_option('cf_raised_percentage_show_hide') == '1') {
                        $countercf = '<p class="price" id="cf_total_raised_percentage" style="">' . $count . '</p>';
                    }
                    $new = $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentw . $totalfieldss . $countercf . $totalpledgedpercentages . $targetfields . $new_date_remain . $gettotalfunders . $gettotalfunderers . $totalfielders . $targetfielders . $new_date_remains;
                    return $new;
                }
            } else {
                if (is_shop() || is_product_category() || is_single() || is_front_page() || is_home()) {
                    $payyourpricelabel = '';
                    $count = '';
                    $count2 = '';
                    $counter = '';
                    $totalfield = '';
                    $totalfieldss = '';
                    $totalfielder = '';
                    $totalpledgedpercent = '';
                    $countercf = '';
                    $cfcounter = '';
                    $new_date_remains = '';
                    $totalpledgedpercentage = '';
                    $totalpledgedpercentages = '';
                    $enabledescription = '';
                    $enabledescription_below_stylebar = '';
                    $content = '';

                    $timezone = wc_timezone_string();
                    if ($timezone != '') {
                        $timezonedate = date_default_timezone_set($timezone);
                    } else {
                        $timezonedate = date_default_timezone_set('UTC');
                    }

                    $content_post = get_post($product->id);
                    if (get_option('crowdfunding_description_type') == '1') {
                        if (isset($content_post->post_content)) {
                            $content = $content_post->post_content;
                        }
                    } else {
                        if (isset($content_post->post_excerpt)) {
                            $content = $content_post->post_excerpt;
                        }
                    }
                    if (get_option('crowdfunding_description_on_shop_page') == 'above_stylebar') {
                        $enabledescription = '<div>' . wp_trim_words($content, get_option('crowdfunding_description_words_count')) . '</div>';
                    }
                    if (get_option('crowdfunding_description_on_shop_page') == 'below_stylebar') {
                        $enabledescription_below_stylebar = '<div style="float:left;">' . wp_trim_words($content, get_option('crowdfunding_description_words_count')) . '</div>';
                    }
                    if (get_option('cf_funders_count_show_hide_shop') == '1') {
                        $checktotalfunders = get_post_meta($product->id, '_update_total_funders', true);
                        if ($checktotalfunders != '') {
                            $gettotalfunders = '<span class="price" id="cf_get_total_funders" style="float:right">' . get_post_meta($product->id, '_update_total_funders', true) . '<small> ' . get_option('cf_funder_label') . '</small> </span>';
                        } else {
                            $gettotalfunders = '<span class="price" id="cf_get_total_funders"  style="float:right"> 0 <small>' . get_option('cf_funder_label') . '</small> </span>';
                        }
                    }
                    if ($tohours != '' || $tominutes != '') {
                        $time = $tohours . ':' . $tominutes . ':' . '00';
                        $datestr = $todate . $time; //Your date
                    } else {
                        $datestr = $todate . "23:59:59";
                    }//Your date
                    $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference

                    if (get_option('cf_day_left_show_hide_shop') == '1') {
                        if ($crowdfunding_end_method == '1' || $crowdfunding_end_method == '4') {
                            if ($date >= time()) {
                                $diff = $date - time();
                                $day = ceil($diff / 86400);
                                $days = floor($diff / 86400);
                                $hours = $diff / 3600 % 24;
                                $minutes = $diff / 60 % 60;
                                $sec = $diff % 60;

                                $daysinhour = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                $hour = ceil(($diff - $daysinhour * 60 * 60 * 24) / (60 * 60));

//Report
                                if ($hours > 1) {
                                    $hours = $hours . ' hours ';
                                } else {
                                    $hours = $hours . ' hour ';
                                }

                                if ($hour > 1) {
                                    $hour = $hour . ' hours ';
                                } else {
                                    $hour = $hour . ' hour ';
                                }
                                if (get_option('cf_campaign_day_time_display') == '1') {
                                    if ($day > 1) {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $day . ' days' . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $day . ' day ' . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                    if ($days > 1) {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' days ' . $hour . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' day ' . $hour . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } else {
                                    if ($days > 1) {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small style='font-style:italic' >" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small style='font-style:italic' >" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                }
                            } else {
                                $new_date_remain = '<p class="price" id="cf_days_remaining" style="float:left"> 0 ' . __('day left', 'galaxyfunder') . '</p>';
                            }
                        } else {
                            $new_date_remain = '';
                        }
                    }
                    ?>
                    <?php
                    $inbuilt_designs = get_option("cf_inbuilt_shop_design");
                    $load_designs = get_option('load_inbuilt_shop_design');
                    if ($inbuilt_designs == '1') {
                        if ($load_designs == '1') {
                            ?>
                            <style type="text/css">
                            <?php echo get_option('cf_shop_page_contribution_table_default_css'); ?>
                            </style>
                            <?php
                        }
                        if ($load_designs == '2') {
                            ?>
                            <style type="text/css">
                            <?php echo get_option('cf_shop_page_contribution_table_default_css'); ?>
                            </style>
                            <?php
                        }
                        if ($load_designs == '3') {
                            ?>
                            <style type="text/css">
                            <?php echo get_option('cf_shop_page_contribution_table_default_css'); ?>
                            </style>
                            <?php
                        }
                    }
                    if ($inbuilt_designs == '2') {
                        ?>
                        <style type="text/css">
                        <?php echo get_option('cf_shop_page_contribution_table_custom_css'); ?>
                        </style>
                        <?php
                    }

                    $products = get_product($product->id);
                    $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                    $minimumvalue = get_post_meta($newid, '_crowdfundinggetminimumprice', true);
                    $recommendedvalue = get_post_meta($newid, '_crowdfundinggetrecommendedprice', true);
                    $maximumvalue = get_post_meta($newid, '_crowdfundinggetmaximumprice', true);
                    $targetvalue = get_post_meta($newid, '_crowdfundinggettargetprice', true);
                    $minpricelabel = get_option('crowdfunding_min_price_shop_page');
                    $maximumpricelabel = get_option('crowdfunding_maximum_price_shop_page');
                    $targetpricelabel = get_option('crowdfunding_target_price_shop_page');
                    $totalpricelabel = get_option('crowdfunding_totalprice_label_shop_page');
                    $totalpricepercentlabel = get_option('crowdfunding_totalprice_percent_label_shop_page');
                    $hideminimum = get_post_meta($newid, '_crowdfundinghideminimum', true);
                    $hidemaximum = get_post_meta($newid, '_crowdfundinghidemaximum', true);
                    $hidetarget = get_post_meta($newid, '_crowdfundinghidetarget', true);

                    if (($order_total != '') && ($targetvalue > 0)) {
                        $count1 = $order_total / $targetvalue;
                        $count2 = $count1 * 100;
                        $counter = number_format($count2, 0);
                        $count = $counter . "%";
                    } else {
                        $count = "0%";
                    }
                    if ($minpricelabel != '') {
                        $minpricecaption = $minpricelabel;
                        $colonmin = " : ";
                    }
                    if ($maximumpricelabel != '') {
                        $maxpricecaption = $maximumpricelabel;
                        $colonmax = " : ";
                    }
                    if ($targetpricelabel != '') {
                        $targetpricecaption = $targetpricelabel;
                        $colontarget = " : ";
                    }
                    if ($totalpricelabel != '') {
                        $totalpricecaption = $totalpricelabel;
                        $colontotal = " : ";
                    }
                    if ($totalpricepercentlabel != '') {
                        $totalpricepercentcaption = $totalpricepercentlabel;
                        $colontotalpercent = " : ";
                    }
                    if ($payyourpricelabel != '') {
                        $payyourpricecaption = $payyourpricelabel;
                        $colonpay = " : ";
                    }
                    if ($hideminimum != 'yes') {
                        if ($minimumvalue != '') {
                            $minimumfield = "<p class = 'price' id = 'cf_min_price_label'><label>" . $minpricecaption . $colonmin . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($minimumvalue) . "</span></p>";
                        } else {
                            $minimumfield = '';
                        }
                    } else {
                        $minimumfield = '';
                    }
                    if ($hidemaximum != 'yes') {
                        if ($maximumvalue != '') {
                            $maximumfield = " <p class = 'price' id = 'cf_max_price_label'><label>" . $maxpricecaption . $colonmax . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($maximumvalue) . "</span></p>";
                        } else {
                            $maximumfield = '';
                        }
                    } else {
                        $maximumfield = '';
                    }
                    if ($hidetarget != 'yes') {
                        if ($targetvalue != '') {
                            $targetfield = " <p class = 'price' id = 'cf_target_price_label'><label>" . $targetpricecaption . $colontarget . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($targetvalue) . "</span></p>";
                        } else {
                            $targetfield = '';
                        }
                    } else {
                        $targetfield = '';
                    }
                    if (get_option('cf_raised_amount_show_hide_shop') == '1') {
                        if ($order_total != '') {
                            $totalfield = "<p class = 'price' id = 'cf_total_price_raised'><label>" . $totalpricecaption . $colontotal . " " . "</label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></p>";
                        }

                        if ($order_total != '') {
                            $totalfieldss = "<span class = 'price' id = 'cf_total_price_raise' style = 'float:left;
'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></span>";
                        }
                    }
                    if (get_option('cf_raised_percentage_show_hide_shop') == '1') {
                        $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percentage'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                    }
                    if (get_option('load_inbuilt_shop_design') == '1') {
                        if ($count2 > 0) {
                            $progress_bar_type = get_option('shop_page_prog_bar_type');
                            if ($progress_bar_type == '1') {
                                $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: ' . $counter . '%;
"></div></div>';
                            } else {
                                $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                                $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: ' . $counter . '%;clear:both;"><span class= "currentpledgegoal"> </span></span><</div>';
                            }
                        } else {
                            $progress_bar_type = get_option('shop_page_prog_bar_type');
                            if ($progress_bar_type == '1') {
                                $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: 0%;
"></div></div>';
                            } else {
                                $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                                $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentages = $totalpledgedpercentss . '<span style=" style = "width: 0%;
"></span></div>';
                            }
                        }
                    } else {
                        if ($count2 > 0) {
                            $progress_bar_type = get_option('shop_page_prog_bar_type');
                            if ($progress_bar_type == '1') {

                                $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                                $totalpledgedpercentss = '<div id = "cf_total_price_in_percent_with_bar" style = "float:left">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentages = $totalpledgedpercentss . '<div id = "cf_percent_bar" style = "width: ' . $counter . '%;
"></div></div>';
                            } else {
                                $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                                $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: ' . $counter . '%;clear:both;"><span class= "currentpledgegoal"> </span></span><</div>';
                            }
                        } else {
                            $progress_bar_type = get_option('shop_page_prog_bar_type');
                            if ($progress_bar_type == '1') {
                                $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                                $totalpledgedpercentss = '<div id = "cf_total_price_in_percent_with_bar" style = "float:left">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentages = $totalpledgedpercentss . '<div id = "cf_percent_bar" style = "width: 0%;
"></div></div>';
                            } else {
                                $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percent'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                                $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                                if ($counter >= 100) {
                                    $counter = 100;
                                }
                                $totalpledgedpercentages = $totalpledgedpercentss . '<span style=" style = "width: 0%;
"></span></div>';
                            }
                        }
                    }
                    if (get_option('cf_raised_amount_show_hide_shop') == '1') {
                        if ($order_total != '') {
                            $totalfielder = "<span class = 'price' id = 'cf_total_price_raiser'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "<small> PLEDGED </small></span></span>";
                        }
                    }
                    if (get_option('cf_day_left_show_hide_shop') == '1') {
                        if ($crowdfunding_end_method == '1' || $crowdfunding_end_method == '4') {
                            if ($date >= time()) {
                                $diff = $date - time();
                                $day = ceil($diff / 86400);
                                $days = floor($diff / 86400);
                                $hours = $diff / 3600 % 24;
                                $minutes = $diff / 60 % 60;
                                $sec = $diff % 60;

                                $daysinhour = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                $hour = ceil(($diff - $daysinhour * 60 * 60 * 24) / (60 * 60));

//Report
                                if ($hours > 1) {
                                    $hours = $hours . ' hours ';
                                } else {
                                    $hours = $hours . ' hour ';
                                }

                                if ($hour > 1) {
                                    $hour = $hour . ' hours ';
                                } else {
                                    $hour = $hour . ' hour ';
                                }
                                if (get_option('cf_campaign_day_time_display') == '1') {
                                    if ($day > 1) {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $day . ' days' . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $day . ' day ' . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                    if ($days > 1) {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' days ' . $hour . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' day ' . $hour . " <small style='font-style:italic'>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } else {
                                    if ($days > 1) {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small style='font-style:italic' >" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small style='font-style:italic' >" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                }
                            } else {
                                $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left"> 0 ' . __('day left', 'galaxyfunder') . '</span>';
                            }
                        } else {
                            $new_date_remains = '';
                        }
                    }
                    if (get_option('cf_raised_percentage_show_hide_shop') == '1') {
                        $cfcounter = '<span class = "price" id = "cf_total_raised_in_percentage" style = "">' . $count . '<small>' . __(' FUNDED', 'galaxyfunder') . '</small></span>';
                    }
                    if ($count2 > 0) {
                        $totalpledgedpercentsser = '<div id = "cf_total_price_in_percenter_with_bar" style = "float:left">';
                        if ($counter >= 100) {
                            $counter = 100;
                        }
                        $totalpledgedpercentageser = $totalpledgedpercentsser . '<div id = "cf_percenter_bar" style = "width: ' . $counter . '%;
"></div></div>';
                    } else {
                        $totalpledgedpercentsser = '<div id = "cf_total_price_in_percenter_with_bar" style = "float:left">';
                        if ($counter >= 100) {
                            $counter = 100;
                        }
                        $totalpledgedpercentageser = $totalpledgedpercentsser . '<div id = "cf_percenter_bar" style = "width: 0%;
"></div></div>';
                    }
                    if (get_option('cf_raised_percentage_show_hide_shop') == '1') {
                        $countercf = '<span class = "price" id = "cf_total_raised_percentage" style = "float:right">' . $count . '</span>';
                    }
                    if ($checkvalue == 'yes') {
                        ?>
                        <?php
                        $new = $enabledescription . $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains . $enabledescription_below_stylebar;

                        return $new;
                    }
                }
            }
        }
        return $price;
    }

    public static function new_stock_checker() {

        global $post;
        $timezone = wc_timezone_string();
        if ($timezone != '') {
            $timezonedate = date_default_timezone_set($timezone);
        } else {
            $timezonedate = date_default_timezone_set('UTC');
        }
        if (is_product() || is_page()) {
            $newid = $post->ID;
            $getdate = date("m/d/Y");
            $gethour = date("h");
            $getminutes = date("i");
            $fromdate = get_post_meta($post->ID, '_crowdfundingfromdatepicker', true);
            $todate = get_post_meta($post->ID, '_crowdfundingtodatepicker', true);
            $tohours = get_post_meta($post->ID, '_crowdfundingtohourdatepicker', true);
            $tominutes = get_post_meta($post->ID, '_crowdfundingtominutesdatepicker', true);
            $fromhours = get_post_meta($post->ID, '_crowdfundingfromhourdatepicker', true);
            $fromminutes = get_post_meta($post->ID, '_crowdfundingfromminutesdatepicker', true);
            if ($fromdate != '') {
                if ($fromhours == '' || $fromminutes == '') {
                    $fromdate = $fromdate . "23:59:59";
                } else {
                    $time = $fromhours . ':' . $fromminutes . ':' . '00';
                    $fromdate = $fromdate . $time;
                }
            } else {
                if ($tohours == '' || $tominutes == '') {
                    $fromdate = $getdate;
                } else {
                    $fromdate = $getdate;
                    $fromhour = $gethour;
                    $fromminutes = $getminutes;
                }
                update_post_meta($post->ID, '_crowdfundingfromdatepicker', $getdate);
                update_post_meta($post->ID, '_crowdfundingfromhourdatepicker', $gethour);
                update_post_meta($post->ID, '_crowdfundingfromminutesdatepicker', $getminutes);
            }
            if ($tohours != '' || $tominutes != '') {
                $time = $tohours . ':' . $tominutes . ':' . '00';
                $datestr = $todate . $time; //Your date
            } else {
                $datestr = $todate . "23:59:59";
            } //Your date
            $date = strtotime($datestr); //Converted to a PHP date (a second count)
            $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);

            if ($checkvalue == 'yes') {
                $gettargetendselection = get_post_meta($post->ID, '_target_end_selection', true);
                if ($gettargetendselection == '1') {
                    ?>

                    <?php
                    if ((strtotime($fromdate) == strtotime($getdate) || strtotime($fromdate) < strtotime($getdate)) && ($date >= time())) {

                    } elseif ((strtotime($fromdate) > strtotime($getdate)) && ($date >= time())) {
                        update_post_meta($post->ID, '_stock_status', 'instock');
                    } else {
                        update_post_meta($post->ID, '_stock_status', 'outofstock');
                    }
                }
                if ($gettargetendselection == '2') {

                }

                if ($gettargetendselection == '3') {
                    $targetprice = get_post_meta($post->ID, '_crowdfundinggettargetprice', true);
                    $totalprice = get_post_meta($post->ID, '_crowdfundingtotalprice', true);
                    if (($totalprice == $targetprice) || ($totalprice > $targetprice)) {
                        update_post_meta($post->ID, '_stock_status', 'outofstock');
                    }
                }
                if ($gettargetendselection == '4') {
                    $targetprice = get_post_meta($post->ID, '_crowdfundinggettargetprice', true);
                    $totalprice = get_post_meta($post->ID, '_crowdfundingtotalprice', true);
                    $fromdatestrtotime = strtotime($fromdate);
                    $todatestrtotime = strtotime($datestr);
                    if (($totalprice == $targetprice) || ($totalprice > $targetprice) || ($todatestrtotime < time())) {
                        update_post_meta($post->ID, '_stock_status', 'outofstock');
                    }
                }
            }
        }
    }

    /**
     * Crowdfunding Register Admin Settings Tab
     */
    public static function crowdfunding_admin_tab($settings_tabs) {
        $settings_tabs['crowdfunding'] = __('General', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_admin_new_tab($settings_tabs) {
        $settings_tabs['crowdfunding_listtable'] = __('Campaigns', 'galaxyfunder');
        return $settings_tabs;
    }

    /**
     * Crowdfunding Add Custom Field to the CrowdFunding Admin Settings
     */
    public static function crowdfunding_admin_fields() {
        global $woocommerce;
        global $wp_roles;
        $all_roles = $wp_roles->role_names;
        foreach ($wp_roles->role_names as $key => $roles) {
            $keys[] = $key;
        }
        if (function_exists('wc_get_order_statuses')) {
            $get_status = wc_get_order_statuses();
            $string_rplaced_keys = str_replace('wc-', '', array_keys(wc_get_order_statuses()));
            $array_values = array_values($get_status);
            $combined_array = array_combine($string_rplaced_keys, $array_values);
        } else {
            $taxanomy = 'shop_order_status';
            $orderstatus = '';
            $orderslugs = '';
            $term_args = array(
                'hide_empty' => false,
                'orderby' => 'date',
            );
            $tax_terms = get_terms($taxanomy, $term_args);
            foreach ($tax_terms as $getterms) {
                $orderstatus[] = @$getterms->name;
                $orderslugs[] = @$getterms->slug;
            }
            $array_values = array_combine((array) $orderslugs, (array) $orderstatus);
            $combined_array = array_combine((array) $orderslugs, (array) $orderstatus);
        }
        return apply_filters('woocommerce_crowdfunding_settings', array(
            array(
                'name' => __('Add to Cart Button Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_add_to_cart_button'
            ),
            array(
                'name' => __('Add to Cart Button Label', 'galaxyfunder'),
                'desc' => __('Please Enter Add to cart Button Label to show', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_add_to_cart_label',
                'css' => 'min-width:550px;
',
                'std' => 'Contribute',
                'type' => 'text',
                'newids' => 'cf_add_to_cart_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redirect after Contribution', 'galaxyfunder'),
                'desc' => __('Please Select the place you want redirect after Contribution', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_add_to_cart_redirection',
                'css' => '',
                'std' => '3',
                'type' => 'radio',
                'options' => array(
                    '1' => __('Cart Page', 'galaxyfunder'),
                    '2' => __('Checkout Page', 'galaxyfunder'),
                    '3' => __('None', 'galaxyfunder'),),
                'newids' => 'cf_add_to_cart_redirection',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_add_to_cart_button'),
            array(
                'name' => __('Campaign Out of Stock Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_out_of_stock'
            ),
            array(
                'name' => __('Out of Stock Label', 'galaxyfunder'),
                'desc' => __('Please Enter Out of Stock Label', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_outofstock_label',
                'css' => 'min-width:550px;margin-bottom:40px;',
                'std' => 'Campaign Closed',
                'type' => 'text',
                'newids' => 'cf_outofstock_label',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_out_of_stock'),
            array(
                'name' => __('Display Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_display_settings'
            ),
            array(
                'name' => __("Select roles which can enable front end campaign submission form", "galaxyfunder"),
                'desc' => __('Select roles which can able to submit front end campaign submission form', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_campaign_submission_frontend_exclude_role_control',
                'css' => 'min-width:150px;',
                'std' => $keys,
                'type' => 'multiselect',
                'options' => $all_roles,
                'newids' => 'cf_campaign_submission_frontend_exclude_role_control',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show Campaigns in Shop Page', 'galaxyfunder'),
                'desc' => __('This helps to Show/Hide the Campaigns in Shop Page', 'galaxyfunder'),
                'id' => 'cf_campaign_in_shop_page',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_campaign_in_shop_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raise Contributions when order status becomes', 'galaxyfunder'),
                'desc' => __('Please Select the Order status to Raise Contributions', 'galaxyfunder'),
                'id' => 'cf_add_contribution',
                'css' => 'min-width:150px;',
                'std' => array('completed'),
                'type' => 'select',
                'options' => $combined_array,
                'newids' => 'cf_add_contribution',
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_display_settings'),
            array(
                'name' => __('Restriction for Cart', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_restriction_for_cart'
            ),
            array(
                'name' => __('Don\'t Allow Other Products to Cart when Cart Contain CrowdFunding Campaign ', 'galaxyfunder'),
                'desc' => __('This helps to Stop Other Products added to Cart when CrowdFunding Campaign in Cart', 'galaxyfunder'),
                'id' => 'cf_campaign_restrict_other_products',
                'css' => '',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_campaign_restrict_other_products',
                'type' => 'select',
                'options' => array('1' => __('Enable', 'galaxyfunder'), '2' => __('Disable', 'galaxyfunder')),
            ),
            array(
                'name' => __('Cart Error Message', 'galaxyfunder'),
                'desc' => __('This helps to Show your Error Message when add some other products to cart', 'galaxyfunder'),
                'id' => 'cf_campaign_restrict_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Multiple Items are not Allowed when cart contain CrowdFunding Campaign Product',
                'default' => 'Multiple Items are not Allowed when cart contain CrowdFunding Campaign Product',
                'newids' => 'cf_campaign_restrict_error_message',
                'type' => 'textarea',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_restriction_for_cart'),
            array(
                'name' => __('Checkout Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_checkout'
            ),
            array(
                'name' => __('Show/hide Mark as Anonymous Checkbox in Checkout page ', 'galaxyfunder'),
                'desc' => '',
                'id' => 'cf_show_hide_mark_anonymous_checkbox',
                'css' => '',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_mark_anonymous_checkbox',
                'type' => 'select',
                'options' => array('1' => __('Show', 'galaxyfunder'), '2' => __('Hide', 'galaxyfunder')),
            ),
            array(
                'name' => __('Mark as Anonymous text', 'galaxyfunder'),
                'desc' => __('Please enter your custom caption', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_checkout_textbox',
                'css' => 'min-width:350px;margin-bottom:40px;',
                'std' => 'Mark as Anonymous ',
                'default' => 'Mark as Anonymous',
                'newids' => 'cf_checkout_textbox',
                'type' => 'text',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_checkout'),
            array(
                'name' => __('Price Button Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_button_settings'
            ),
            array(
                'name' => __('Button color ', 'galaxyfunder'),
                'desc' => __('Please enter the Button color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_button_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => 'FF8C00',
                'default' => 'FF8C00',
                'newids' => 'cf_button_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Button Text color ', 'galaxyfunder'),
                'desc' => __('Please enter the Button Text color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_button_text_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => '000000',
                'default' => '000000',
                'newids' => 'cf_button_text_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Selected Button color ', 'galaxyfunder'),
                'desc' => __('Please enter the Selected Button color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_selected_button_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => 'f00',
                'default' => 'f00',
                'newids' => 'cf_selected_button_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Selected Button Text color ', 'galaxyfunder'),
                'desc' => __('Please enter the Selected Button Text color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_selected_button_text_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => 'fff',
                'default' => 'fff',
                'newids' => 'cf_selected_button_text_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Box Shadow', 'galaxyfunder'),
                'desc' => __('Box Shadow show or hide option', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_button_box_shadow',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('Show', 'galaxyfunder'), '2' => __('Hide', 'galaxyfunder')),
                'newids' => 'cf_button_box_shadow',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_button_settings'),
            array(
                'name' => __('Troubleshoot', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_troubleshoot'
            ),
            array(
                'name' => __('Load Template', 'galaxyfunder'),
                'desc' => __('Troubleshooting the Problem by change the Option to load Template Files from Various Places', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_load_woocommerce_template',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('From Plugin', 'galaxyfunder'), '2' => __('From Theme', 'galaxyfunder')),
                'newids' => 'cf_load_woocommerce_template',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_troubleshoot'),
            array(
                'name' => __('Troubleshoot SSL', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => 'cf_product_ssl_troubleshoot'
            ),
            array(
                'name' => __('Load Ajax from', 'galaxyfunder'),
                'desc' => __('Force SSL for Admin Cause Galaxy Funder to Stop Working because of https to http is causing problem', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_load_ajax_from_ssl',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('From Admin URL', 'galaxyfunder'), '2' => __('From Site URL', 'galaxyfunder')),
                'newids' => 'cf_load_ajax_from_ssl',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => 'cf_product_ssl_troubleshoot'),
        ));
    }

    /**
     * Crowdfunding Add Custom Field to the CrowdFunding WooCommerce General Tab Admin Settings
     */
    public static function crowdfunding_add_custom_field_admin_settings() {
        global $post;
        global $woocommerce;
        echo '<div class="options_group hide_if_grouped">';
        echo '</div>';
        echo '<div class="options_group hide_if_grouped">';
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundingcheckboxvalue',
                    'wrapper_class' => '',
//                    'value'=>'no',
                    'label' => __('Enable Galaxy Funder', 'galaxyfunder'),
                    'description' => __('Enable Galaxy Funder if you want users to contribute for your job by donating money', 'galaxyfunder')
                )
        );
        if ((float) $woocommerce->version <= (float) ('2.2.0')) {
            woocommerce_wp_select(array(
                'id' => '_crowdfunding_options',
                'class' => 'crowdfunding_options',
                'label' => __('CrowdFunding Type', 'galaxyfunder'),
                'options' => array(
                    '' => '',
                    '1' => __('Fundraising by CrowdFunding', 'galaxyfunder'),
                    '2' => __('Product Purchase by CrowdFunding', 'galaxyfunder'),
                )
                    )
            );
        } else {
            woocommerce_wp_select(array(
                'id' => '_crowdfunding_options',
                'class' => 'crowdfunding_options',
                'label' => __('CrowdFunding Type', 'galaxyfunder'),
                'options' => array(
                    '1' => __('Fundraising by CrowdFunding', 'galaxyfunder'),
                    '2' => __('Product Purchase by CrowdFunding', 'galaxyfunder'),
                )
                    )
            );
        }
        woocommerce_wp_text_input(
                array(
                    'id' => '_crowdfundinggetminimumprice',
                    'name' => '_crowdfundinggetminimumprice',
                    'label' => __('Minimum Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundinghideminimum',
                    'wrapper_class' => '',
                    'label' => __('Hide Minimum Price', 'galaxyfunder'),
                    'description' => __('')
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_crowdfundinggetrecommendedprice',
                    'name' => '_crowdfundinggetrecommendedprice',
                    'label' => __('Recommended Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_crowdfundinggetmaximumprice',
                    'name' => '_crowdfundinggetmaximumprice',
                    'label' => __('Maximum Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundinghidemaximum',
                    'wrapper_class' => '',
                    'label' => __('Hide Maximum Price', 'galaxyfunder'),
                    'description' => __('')
                )
        );

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => '-1',
            'post_status' => array('publish'),
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_crowdfundingcheckboxvalue',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_crowdfundingcheckboxvalue',
                    'value' => 'no',
                    'compare' => '='
                ),
            ),
        );
        $getproducts = get_posts($args);
        ?>

        <p class="form-field _cf_selection_field " style="display: block;"><label for="_cf_product_selection">Choose Products</label>
            <select multiple name="_cf_product_selection[]" data-placeholder="Choose Product..." style="width:250px;" id="_cf_product_selection">
                <option value=""></option>
                <?php
                if (is_array($getproducts)) {
                    foreach ($getproducts as $product) {
                        $product_type = get_product($product->ID);

                        if (($product_type->is_type('simple'))) {

                            if (get_post_meta($product->ID, '_crowdfundingcheckboxvalue', true) != 'yes') {
                                ?>
                                <option data-price ="<?php echo get_post_meta($product->ID, '_regular_price', true); ?>" <?php
                                if (NULL != get_post_meta($post->ID, '_cf_product_selection', true)) {
                                    foreach (get_post_meta($post->ID, '_cf_product_selection', true) as $value) {
                                        if ($value == $product->ID) {
                                            echo "selected=selected";
                                        }
                                    }
                                }
                                ?> value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                                        <?php
                                    }
                                } else {
                                    if (($product_type->is_type('variable'))) {
                                        if (is_array($product_type->get_available_variations())) {
                                            foreach ($product_type->get_available_variations() as $getvariation) {
                                                ?>
                                        <option data-price ="<?php echo get_post_meta($getvariation['variation_id'], '_regular_price', true); ?>" <?php
                                        if (NULL != get_post_meta($post->ID, '_cf_product_selection', true)) {
                                            foreach (get_post_meta($post->ID, '_cf_product_selection', true) as $value) {
                                                if ($value == $getvariation['variation_id']) {
                                                    echo "selected=selected";
                                                }
                                            }
                                        }
                                        ?>value="<?php echo $getvariation['variation_id']; ?>"><?php echo get_the_title($getvariation['variation_id']); ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        ?>
            </select>
        </p>
        <?php
        woocommerce_wp_text_input(
                array(
                    'id' => '_crowdfundinggettargetprice',
                    'name' => '_crowdfundinggettargetprice',
                    'label' => __('Target Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                )
        );

        woocommerce_wp_checkbox(
                array(
                    'id' => '_use_selected_product_image',
                    'wrapper_class' => '',
                    'label' => __('Use Selected Product Featured Image', 'galaxyfunder'),
                    'description' => __('Check this Option to use the Featured Image of Selected Product (Works only When one Product is Chosen.)', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundinghidetarget',
                    'wrapper_class' => '',
                    'label' => __('Hide Target Price', 'galaxyfunder'),
                    'description' => __('')
                )
        );
        woocommerce_wp_select(array(
            'id' => '_target_end_selection',
            'class' => 'target_end_selection',
            'label' => __('Campaign End Method', 'galaxyfunder'),
            'options' => array(
                '3' => __('Target Goal', 'galaxyfunder'),
                '1' => __('Target Date', 'galaxyfunder'),
                '4' => __('Target Goal & Date', 'galaxyfunder'),
                '2' => __('Campaign Never Ends', 'galaxyfunder'),
            )
        ));
        ?>
        <div><p class="form-field _crowdfundingfromdatepicker_field ">
                <label for="_crowdfundingfromdatepicker"><?php echo __('From Date', 'galaxyfunder') ?></label>
                <input id="_crowdfundingfromdatepicker" type="text" placeholder='From date'value="<?php echo get_post_meta($post->ID, '_crowdfundingfromdatepicker', true); ?>" name="_crowdfundingfromdatepicker" style="">
                <input id="_crowdfundingfromhourdatepicker" type="text" size='0' maxlength='2'placeholder='HH' value="<?php echo get_post_meta($post->ID, '_crowdfundingfromhourdatepicker', true); ?>" name="_crowdfundingfromhourdatepicker" style="">
                <input id="_crowdfundingfromminutesdatepicker" type="text" size='0' maxlength='2'placeholder='MM' value="<?php echo get_post_meta($post->ID, '_crowdfundingfromminutesdatepicker', true); ?>" name="_crowdfundingfromminutesdatepicker" style="">
            </p></div>
        <div><p class="form-field _crowdfundingtodatepicker_field ">
                <label for="_crowdfundingtodatepicker"><?php echo __('To Date', 'galaxyfunder') ?></label>
                <input id="_crowdfundingtodatepicker" type="text" placeholder='To date' value="<?php echo get_post_meta($post->ID, '_crowdfundingtodatepicker', true); ?>" name="_crowdfundingtodatepicker" style="">
                <input id="_crowdfundingtohourdatepicker" type="text"  placeholder='HH'value="<?php echo get_post_meta($post->ID, '_crowdfundingtohourdatepicker', true); ?>" size='1' maxlength='2' max='60' min='0' name="_crowdfundingtohourdatepicker" style="">
                <input id="_crowdfundingtominutesdatepicker" type="text" placeholder='MM' value="<?php echo get_post_meta($post->ID, '_crowdfundingtominutesdatepicker', true); ?>" size='1' max=60 min='0' maxlength='2'name="_crowdfundingtominutesdatepicker" style="">

            </p></div>
        <?php
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundingsocialsharing',
                    'wrapper_class' => '',
                    'label' => __('Enable Social Promotion', 'galaxyfunder'),
                    'description' => __('Enable Social Promotion for this Campaign', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundingsocialsharing_facebook',
                    'wrapper_class' => '',
                    'label' => __('Enable Social Promotion Through Facebook', 'galaxyfunder'),
                    'description' => __('Enable Social Promotion for this Campaign Through Facebook', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundingsocialsharing_twitter',
                    'wrapper_class' => '',
                    'label' => __('Enable Social Promotion Through Twitter', 'galaxyfunder'),
                    'description' => __('Enable Social Promotion for this Campaign Through Twitter', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfundingsocialsharing_google',
                    'wrapper_class' => '',
                    'label' => __('Enable Social Promotion Through Google+', 'galaxyfunder'),
                    'description' => __('Enable Social Promotion for this Campaign Through Google+', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfunding_showhide_contributor',
                    'wrapper_class' => '',
                    'label' => __('Show Contributor Table', 'galaxyfunder'),
                    'description' => __('Enable this option to display the contributors for this Campaign', 'galaxyfunder')
                )
        );
        woocommerce_wp_checkbox(
                array(
                    'id' => '_crowdfunding_contributor_anonymous',
                    'wrapper_class' => '',
                    'label' => __('Mark Contributors as Anonymous', 'galaxyfunder'),
                    'description' => __('Enable this option to display the contributors Name as Anonymous for this Campaign', 'galaxyfunder')
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => 'cf_campaigner_paypal_id',
                    'wrapper_class' => '',
                    'label' => __('Campaigner PayPal ID', 'galaxyfunder'),
                    'description' => __('')
                )
        );
        woocommerce_wp_select(
                array(
                    'id' => 'buttonstyles_galaxy',
                    'wrapper_class' => '',
                    'label' => __('Select Style', 'galaxyfunder'),
                    'description' => __('Select style for displaying price', 'galaxyfunder'),
                    'options' => array(
                        'default' => __('Editable Textbox', 'galaxyfunder'),
                        'default_non_editable' => __('Non-Editable Textbox', 'galaxyfunder'),
                        'radio' => __('Predefined Buttons', 'galaxyfunder'),
                        'dropdown' => __('Predefined ListBox', 'galaxyfunder'),
                    )
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_amount1_galaxy',
                    'name' => '_amount1_galaxy',
                    'label' => __('Amount 1 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_amount2_galaxy',
                    'name' => '_amount2_galaxy',
                    'label' => __('Amount 2 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_amount3_galaxy',
                    'name' => '_amount3_galaxy',
                    'label' => __('Amount 3 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_amount4_galaxy',
                    'name' => '_amount4_galaxy',
                    'label' => __('Amount 4 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_amount5_galaxy',
                    'name' => '_amount5_galaxy',
                    'label' => __('Amount 5 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_amount6_galaxy',
                    'name' => '_amount6_galaxy',
                    'label' => __('Amount 6 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_recomended_amount_galaxy',
                    'name' => '_recomended_amount_galaxy',
                    'label' => __('Recomended Amount (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                )
        );
        echo '</div>';
        ?>
        <style type="text/css">
            #_crowdfundingfromdatepicker,#_crowdfundingtodatepicker{
                width: 100px;
                margin-right:5px;
            }
            #_crowdfundingfromhourdatepicker,#_crowdfundingtohourdatepicker{
                width: 40px;
                margin-right:5px;
            }
            #_crowdfundingfromminutesdatepicker,#_crowdfundingtominutesdatepicker{
                width: 40px;
                margin-right:5px;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var style_galaxy = document.getElementById("buttonstyles_galaxy");
                var style_selected_galaxy = style_galaxy.options[style_galaxy.selectedIndex].value;
                if (style_selected_galaxy == 'radio' || style_selected_galaxy == 'dropdown') {
                    jQuery("#_amount1_galaxy").parent().css("display", "block");
                    jQuery("#_amount2_galaxy").parent().css("display", "block");
                    jQuery("#_amount3_galaxy").parent().css("display", "block");
                    jQuery("#_amount4_galaxy").parent().css("display", "block");
                    jQuery("#_amount5_galaxy").parent().css("display", "block");
                    jQuery("#_amount6_galaxy").parent().css("display", "block");
                    jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                } else if (style_selected_galaxy == 'default_non_editable') {

                    jQuery("#_amount1_galaxy").parent().css("display", "none");
                    jQuery("#_amount2_galaxy").parent().css("display", "none");
                    jQuery("#_amount3_galaxy").parent().css("display", "none");
                    jQuery("#_amount4_galaxy").parent().css("display", "none");
                    jQuery("#_amount5_galaxy").parent().css("display", "none");
                    jQuery("#_amount6_galaxy").parent().css("display", "none");
                    jQuery("#_recomended_amount_galaxy").parent().css("display", "block");
                } else {
                    jQuery("#_amount1_galaxy").parent().css("display", "none");
                    jQuery("#_amount2_galaxy").parent().css("display", "none");
                    jQuery("#_amount3_galaxy").parent().css("display", "none");
                    jQuery("#_amount4_galaxy").parent().css("display", "none");
                    jQuery("#_amount5_galaxy").parent().css("display", "none");
                    jQuery("#_amount6_galaxy").parent().css("display", "none");
                    jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                }
                jQuery('#buttonstyles_galaxy').change(function () {
                    var style_galaxy = document.getElementById("buttonstyles_galaxy");
                    var style_selected_galaxy = style_galaxy.options[style_galaxy.selectedIndex].value;
                    if (style_selected_galaxy == 'radio' || style_selected_galaxy == 'dropdown') {
                        jQuery("#_amount1_galaxy").parent().css("display", "block");
                        jQuery("#_amount2_galaxy").parent().css("display", "block");
                        jQuery("#_amount3_galaxy").parent().css("display", "block");
                        jQuery("#_amount4_galaxy").parent().css("display", "block");
                        jQuery("#_amount5_galaxy").parent().css("display", "block");
                        jQuery("#_amount6_galaxy").parent().css("display", "block");
                        jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                    } else if (style_selected_galaxy == 'default_non_editable') {
                        jQuery("#_recomended_amount_galaxy").parent().css("display", "block");
                        jQuery("#_amount1_galaxy").parent().css("display", "none");
                        jQuery("#_amount2_galaxy").parent().css("display", "none");
                        jQuery("#_amount3_galaxy").parent().css("display", "none");
                        jQuery("#_amount4_galaxy").parent().css("display", "none");
                        jQuery("#_amount5_galaxy").parent().css("display", "none");
                        jQuery("#_amount6_galaxy").parent().css("display", "none");

                    } else {
                        jQuery("#_amount1_galaxy").parent().css("display", "none");
                        jQuery("#_amount2_galaxy").parent().css("display", "none");
                        jQuery("#_amount3_galaxy").parent().css("display", "none");
                        jQuery("#_amount4_galaxy").parent().css("display", "none");
                        jQuery("#_amount5_galaxy").parent().css("display", "none");
                        jQuery("#_amount6_galaxy").parent().css("display", "none");
                        jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                    }

                });
                var is_checked = jQuery("#_crowdfundingsocialsharing:checked").val() ? true : false;
                if (is_checked == false) {
                    //                    alert(is_checked);
                    jQuery('#_crowdfundingsocialsharing_facebook').parent().hide();
                    jQuery('#_crowdfundingsocialsharing_twitter').parent().hide();
                    jQuery('#_crowdfundingsocialsharing_google').parent().hide();
                } else {
                    jQuery('#_crowdfundingsocialsharing_facebook').parent().show();
                    jQuery('#_crowdfundingsocialsharing_twitter').parent().show();
                    jQuery('#_crowdfundingsocialsharing_google').parent().show();
                }
                jQuery("#_crowdfundingsocialsharing").on('click', function (e) {
                    var is_checked = jQuery("#_crowdfundingsocialsharing:checked").val() ? true : false;
                    if (is_checked == false) {
                        //                    alert(is_checked);
                        jQuery('#_crowdfundingsocialsharing_facebook').parent().hide();
                        jQuery('#_crowdfundingsocialsharing_twitter').parent().hide();
                        jQuery('#_crowdfundingsocialsharing_google').parent().hide();
                    } else {
                        jQuery('#_crowdfundingsocialsharing_facebook').parent().show();
                        jQuery('#_crowdfundingsocialsharing_twitter').parent().show();
                        jQuery('#_crowdfundingsocialsharing_google').parent().show();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Save the Meta Information of Custom Field CrowdFunding
     */
    public static function crowdfunding_save_product_post($post_id) {
        $woocommerce_radiobox = $_POST['_target_end_selection'];
        update_post_meta($post_id, '_target_end_selection', $woocommerce_radiobox);
        $woocommerce_checkbox = isset($_POST['_crowdfundingcheckboxvalue']) ? 'yes' : 'no';
        $woocommerce_socialsharing = isset($_POST['_crowdfundingsocialsharing']) ? 'yes' : 'no';
        $woocommerce_socialsharing_facebook = isset($_POST['_crowdfundingsocialsharing_facebook']) ? 'yes' : 'no';
        $woocommerce_socialsharing_twitter = isset($_POST['_crowdfundingsocialsharing_twitter']) ? 'yes' : 'no';
        $woocommerce_socialsharing_google = isset($_POST['_crowdfundingsocialsharing_google']) ? 'yes' : 'no';
        $woocommerce_showhide_contributors = isset($_POST['_crowdfunding_showhide_contributor']) ? 'yes' : 'no';
        $woocommerce_mark_contributor_anonymous = isset($_POST['_crowdfunding_contributor_anonymous']) ? 'yes' : 'no';
        $selected_product_checkbox = isset($_POST['_use_selected_product_image']) ? 'yes' : 'no';

        if ($_POST['_use_selected_product_image']) {
            if (count($_POST['_cf_product_selection']) == 1) {
//                var_dump(count($_POST['_cf_product_selection']));exit();
                update_post_meta($post_id, '_use_selected_product_image', $selected_product_checkbox);
                $feat_image = get_post_thumbnail_id(implode($_POST['_cf_product_selection']));
                set_post_thumbnail($post_id, $feat_image);
            } else {
                $selected_product_checkbox = 'no';
                update_post_meta($post_id, '_use_selected_product_image', $selected_product_checkbox);
                delete_post_thumbnail($post_id);
            }
        }

        update_post_meta($post_id, '_crowdfundingcheckboxvalue', $woocommerce_checkbox);
        update_post_meta($post_id, '_crowdfundingsocialsharing', $woocommerce_socialsharing);
        update_post_meta($post_id, '_crowdfundingsocialsharing_facebook', $woocommerce_socialsharing_facebook);
        update_post_meta($post_id, '_crowdfundingsocialsharing_twitter', $woocommerce_socialsharing_twitter);
        update_post_meta($post_id, '_crowdfundingsocialsharing_google', $woocommerce_socialsharing_google);
        update_post_meta($post_id, '_crowdfunding_showhide_contributor', $woocommerce_showhide_contributors);
        update_post_meta($post_id, '_crowdfunding_contributor_anonymous', $woocommerce_mark_contributor_anonymous);

        $woocommerce_fundraising_type = $_POST['_crowdfunding_options'];
        update_post_meta($post_id, '_crowdfunding_options', $woocommerce_fundraising_type);

        $woocommerce_select_products = $_POST['_cf_product_selection'];
        update_post_meta($post_id, '_cf_product_selection', $woocommerce_select_products);


        $woocommerce_minimumprice = $_POST['_crowdfundinggetminimumprice'];
        update_post_meta($post_id, '_crowdfundinggetminimumprice', $woocommerce_minimumprice);
        $woocommerce_crowdfundinghideminimum = isset($_POST['_crowdfundinghideminimum']) ? 'yes' : 'no';
        update_post_meta($post_id, '_crowdfundinghideminimum', $woocommerce_crowdfundinghideminimum);
        $woocommerce_recommendedprice = $_POST['_crowdfundinggetrecommendedprice'];
        update_post_meta($post_id, '_crowdfundinggetrecommendedprice', $woocommerce_recommendedprice);
        $woocommerce_maximumprice = $_POST['_crowdfundinggetmaximumprice'];
        update_post_meta($post_id, '_crowdfundinggetmaximumprice', $woocommerce_maximumprice);
        $woocommerce_crowdfundinghidemaximum = isset($_POST['_crowdfundinghidemaximum']) ? 'yes' : 'no';
        update_post_meta($post_id, '_crowdfundinghidemaximum', $woocommerce_crowdfundinghidemaximum);
        $woocommerce_targetprice = $_POST['_crowdfundinggettargetprice'];
        update_post_meta($post_id, '_crowdfundinggettargetprice', $woocommerce_targetprice);
        $woocommerce_crowdfundinghidetarget = isset($_POST['_crowdfundinghidetarget']) ? 'yes' : 'no';
        update_post_meta($post_id, '_crowdfundinghidetarget', $woocommerce_crowdfundinghidetarget);
        $crowdfundingfromdatepicker = $_POST['_crowdfundingfromdatepicker'];
        update_post_meta($post_id, '_crowdfundingfromdatepicker', $crowdfundingfromdatepicker);
        $crowdfundingfromhourdatepicker = $_POST['_crowdfundingfromhourdatepicker'];
        update_post_meta($post_id, '_crowdfundingfromhourdatepicker', $crowdfundingfromhourdatepicker);
        $crowdfundingfromminutesdatepicker = $_POST['_crowdfundingfromminutesdatepicker'];
        update_post_meta($post_id, '_crowdfundingfromminutesdatepicker', $crowdfundingfromminutesdatepicker);
        $crowdfundingtodatepicker = $_POST['_crowdfundingtodatepicker'];
        update_post_meta($post_id, '_crowdfundingtodatepicker', $crowdfundingtodatepicker);
        $crowdfundingtohourdatepicker = $_POST['_crowdfundingtohourdatepicker'];
        update_post_meta($post_id, '_crowdfundingtohourdatepicker', $crowdfundingtohourdatepicker);
        $crowdfundingtotimedatetimepicker = $_POST['_crowdfundingtominutesdatepicker'];
        update_post_meta($post_id, '_crowdfundingtominutesdatepicker', $crowdfundingtotimedatetimepicker);

        $crowdfunding_campaign_email = $_POST['cf_campaigner_paypal_id'];
        update_post_meta($post_id, 'cf_campaigner_paypal_id', $crowdfunding_campaign_email);
        $woocommerce_selectbox1_galaxy = $_POST['buttonstyles_galaxy'];
        update_post_meta($post_id, 'buttonstyles_galaxy', $woocommerce_selectbox1_galaxy);
        $woocommerce_amount1_galaxy = $_POST['_amount1_galaxy'];
        update_post_meta($post_id, '_amount1_galaxy', $woocommerce_amount1_galaxy);
        $woocommerce_amount2_galaxy = $_POST['_amount2_galaxy'];
        update_post_meta($post_id, '_amount2_galaxy', $woocommerce_amount2_galaxy);
        $woocommerce_amount3_galaxy = $_POST['_amount3_galaxy'];
        update_post_meta($post_id, '_amount3_galaxy', $woocommerce_amount3_galaxy);
        $woocommerce_amount4_galaxy = $_POST['_amount4_galaxy'];
        update_post_meta($post_id, '_amount4_galaxy', $woocommerce_amount4_galaxy);
        $woocommerce_amount5_galaxy = $_POST['_amount5_galaxy'];
        update_post_meta($post_id, '_amount5_galaxy', $woocommerce_amount5_galaxy);
        $woocommerce_amount6_galaxy = $_POST['_amount6_galaxy'];
        update_post_meta($post_id, '_amount6_galaxy', $woocommerce_amount6_galaxy);
        $woocommerce_recomended_amt_galaxy = $_POST['_recomended_amount_galaxy'];
        update_post_meta($post_id, '_recomended_amount_galaxy', $woocommerce_recomended_amt_galaxy);
        $amount_collection_galaxy = array($woocommerce_amount1_galaxy, $woocommerce_amount2_galaxy, $woocommerce_amount3_galaxy, $woocommerce_amount4_galaxy, $woocommerce_amount5_galaxy, $woocommerce_amount6_galaxy);
        update_post_meta($post_id, 'ppcollection_galaxy', $amount_collection_galaxy);
    }

    /**
     * Enqueueing script of jQuery
     */
    public static function crowdfunding_woocommerce_enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('cfjqueryuistyle', plugins_url('/css/jquery-ui.css', __FILE__));
        wp_enqueue_script('cfcustomscript', plugins_url('/js/customscript.js', __FILE__));
        wp_enqueue_script('cfdatepickerscript', plugins_url('/js/datepicker.js', __FILE__));
    }

    public static function add_crowdfunding_input_field() {
        global $post;
        global $woocommerce;
        global $product;
        @$newid = $product->id;
        $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
        $checkmethod = get_post_meta($newid, '_target_end_selection', true);
        $payyourpricelabel = get_option('crowdfunding_payyourprice_price_tab_product');

        $timezone = wc_timezone_string();
        if ($timezone != '') {
            $timezonedate = date_default_timezone_set($timezone);
        } else {
            $timezonedate = date_default_timezone_set('UTC');
        }
        if ($payyourpricelabel != '') {
            $payyourpricecaption = $payyourpricelabel;
            $colonpay = ":";
        }
        $recommendedvalue = get_post_meta($newid, '_crowdfundinggetrecommendedprice', true);
        if ($checkvalue == 'yes') {

            $getdate = date("m/d/Y");
            $gethour = date("h");
            $getminutes = date("i");
            $fromdate = get_post_meta($product->id, '_crowdfundingfromdatepicker', true);
            $todate = get_post_meta($product->id, '_crowdfundingtodatepicker', true);
            $tominutes = get_post_meta($product->id, '_crowdfundingtominutesdatepicker', true);
            $tohours = get_post_meta($product->id, '_crowdfundingtohourdatepicker', true);
            $fromminutes = get_post_meta($product->id, '_crowdfundingfromminutesdatepicker', true);
            $fromhours = get_post_meta($product->id, '_crowdfundingfromhourdatepicker', true);
            $checkmethod = get_post_meta($product->id, '_target_end_selection', true);
            if ($fromdate != '') {
                if ($fromhours == '' || $fromminutes == '') {
                    $fromdate = $fromdate . "23:59:59";
                } else {
                    $time = $fromhours . ':' . $fromminutes . ':' . '00';
                    $fromdate = $fromdate . $time;
                }
            } else {
                if ($tohours == '' || $tominutes == '') {
                    $fromdate = $getdate;
                } else {
                    $fromdate = $getdate;
                    $fromhour = $gethour;
                    $fromminutes = $getminutes;
                }
                update_post_meta($product->id, '_crowdfundingfromdatepicker', $getdate);
                update_post_meta($product->id, '_crowdfundingfromhourdatepicker', $gethour);
                update_post_meta($product->id, '_crowdfundingfromminutesdatepicker', $getminutes);
            }
            if ($tohours != '' || $tominutes != '') {
                $time = $tohours . ':' . $tominutes . ':' . '00';

                $datestr = $todate . $time; //Your date
            } else {
                $datestr = $todate . "23:59:59";
            }
            $date = strtotime($datestr);
//Converted to a PHP date (a second count)
            if ((strtotime($fromdate) > time()) && ($date > time())) {
                if ($checkmethod == '1' || $checkmethod == '4') {
                    $message = get_option('cf_campaign_start_message');
                    $fromdate = date('d-m-Y H:i:s', strtotime($fromdate));
                    $org_message = str_replace('{from_date}', $fromdate, $message);
                    echo $org_message;
                    echo '<script type="text/javascript">'
                    . 'jQuery(document).ready(function(){'
                    . 'jQuery(".single_add_to_cart_button").hide();'
                    . 'jQuery("#cf_price_new_date_remain").hide();'
                    . 'jQuery("#singleproductinputfieldcrowdfunding").hide();'
                    . '});'
                    . '</script>';
                }
            } //Converted to a PHP date (a second count)
            ?>
            <style type="text/css">
                .qty{
                    display: none
                }
            </style>
            <p class="singleproductinputfieldcrowdfunding" id="singleproductinputfieldcrowdfunding"style = 'margin-bottom:10px;float:left;'><?php
                echo $payyourpricecaption . ' ' . $colonpay;
                echo get_woocommerce_currency_symbol();
                ?>
                <input style='width:80px;' type='number' min='1' step='1' class='addfundraiser<?php echo $newid; ?>' name='addfundraiser<?php echo $newid; ?>' value='<?php echo $recommendedvalue; ?>'/></p>
            <input type='hidden' name='productid' id='productid' value='<?php echo $newid; ?>'/>

            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery(".single_add_to_cart_button").attr("data-productid", "<?php echo $newid; ?>");
                    jQuery('<?php
            if (get_post_meta($product->id, "_crowdfundingcheckboxvalue", true) == "yes") {
                if (get_option("cf_check_show_hide_contributor_name") == "1") {
                    ?> <div class="contributor_name_field_value"><label for="cf_contributor_name_field_value"><?php echo get_option("cf_contributor_name_caption"); ?></label><input type="text" name="cf_contributor_name_field_value" id="cf_contributor_name_field_value" class="cf_contributor_name_field_value" value="" /><br><small><?php _e(get_option("cf_contributor_name_description"), "galaxyfunder"); ?></small></div><?php
                }
            }
            ?>').appendTo(".singleproductinputfieldcrowdfunding");
            <?php if (get_option('display_select_box_crowdfunding') == 'top') { ?>
                        jQuery('.singleproductinputfieldcrowdfunding').before("<div class = 'singlecrowdfunding<?php echo $newid; ?>' style = 'float:left;'></div>");
            <?php } ?>
            <?php if (get_option('display_select_box_crowdfunding') == 'bottom') { ?>
                        jQuery('.singleproductinputfieldcrowdfunding').after("<div class = 'singlecrowdfunding<?php echo $newid; ?>' style = 'float:left;'></div>");
            <?php } ?>
                });
            </script>
            <?php
        }
    }

    public static function add_script_to_crowdfunding_input_field() {
        global $post;
        global $woocommerce;
        global $product;
        $newid = $product->id;
        $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
        $checkmethod = get_post_meta($newid, '_target_end_selection', true);
        $payyourpricelabel = get_option('crowdfunding_payyourprice_price_tab_product');
        if ($payyourpricelabel != '') {
            $payyourpricecaption = $payyourpricelabel;
            $colonpay = ":";
        }
        $recommendedvalue = get_post_meta($newid, '_crowdfundinggetrecommendedprice', true);
        if ($checkvalue == 'yes') {
            $contribute_style = get_post_meta($product->id, 'buttonstyles_galaxy', true);
            if (($contribute_style == 'radio') || ($contribute_style == 'dropdown')) {
                ?>
                <style type="text/css">
                    .singleproductinputfieldcrowdfunding{
                        display: none
                    }
                </style>
                <?php
            } else {
                ?>
                <style type="text/css">
                    .singleproductinputfieldcrowdfunding{
                        display: block
                    }
                </style>
                <?php
            }

            if ($contribute_style == 'dropdown') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        var vari = jQuery('.cf_amount_dropdown').attr('cf_data-amount');
                        jQuery('.addfundraiser<?php echo $product->id ?>').val(vari);
                    });
                </script>
                <?php
            }

            if ($contribute_style == 'default_non_editable') {
                $amount = get_post_meta($product->id, '_recomended_amount_galaxy', true);
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.addfundraiser<?php echo $product->id ?>').val('<?php echo $amount ?>');
                        jQuery('.addfundraiser<?php echo $product->id ?>').attr('readonly', true);
                    });
                </script>
                <?php
            }
        }
    }

    public static function checkingfrontend_galaxy() {
        global $post;
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function () {
                jQuery('.cf_amount_button').click(function () {
                    var newdata_galaxy = jQuery(this).attr('cf_data-amount');
                    jQuery('.addfundraiser<?php echo $post->ID; ?>').val(newdata_galaxy);
                    jQuery('.cf_amount_button').removeClass('cf_amount_button_clicked');
                    jQuery(this).addClass('cf_amount_button_clicked');
                });
                jQuery('.cf_amount_dropdown').click(function () {
                    var newdata_galaxy = jQuery(this).attr('cf_data-amount');
                    jQuery('.addfundraiser<?php echo $post->ID; ?>').val(newdata_galaxy);

                });
                if (!jQuery.trim(jQuery('.cf_container_galaxy').html()).length) {
                    jQuery('.cf_container_galaxy').css("display", "none");
                } else {
                    jQuery('.cf_container_galaxy').css("display", "block");
                }

            });
        </script>
        <?php
        $hidebutton_galaxy = get_post_meta($post->ID, 'buttonstyles_galaxy', true);
        if (is_array(get_post_meta($post->ID, 'ppcollection_galaxy', true))) {
            ?>
            <?php if ($hidebutton_galaxy == 'radio') { ?>
                <div class="cf_container_galaxy">
                <?php } elseif ($hidebutton_galaxy == 'dropdown') { ?>
                    <select>
                    <?php } else { ?>
                        <div style="display:none">
                        <?php } ?>
                        <?php
                        foreach (array_filter(get_post_meta($post->ID, 'ppcollection_galaxy', true)) as $value) {

                            if ($hidebutton_galaxy == 'radio') {
                                ?>

                                <div class="cf_amount_button" cf_data-amount ='<?php echo $value; ?>' ><?php echo get_woocommerce_currency_symbol() . $value; ?></div>

                            <?php } elseif ($hidebutton_galaxy == 'dropdown') {
                                ?>
                                <option class="cf_amount_dropdown" cf_data-amount ='<?php echo $value; ?>' ><?php echo get_woocommerce_currency_symbol() . $value; ?></option>
                            <?php } else {
                                ?>
                                <div class="cf_amount_button" cf_data-amount ='<?php echo $value; ?>' ><?php echo get_woocommerce_currency_symbol() . $value; ?></div>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($hidebutton_galaxy == 'radio') { ?>
                        </div >
                    <?php } elseif ($hidebutton_galaxy == 'dropdown') { ?>
                    </select>
                <?php } else { ?>
                </div >
            <?php } ?>
            <?php
        }
    }

    public static function crowdfunding_show_hide_custom_field() {
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#product-type').change(function () {
                    var selectvalue = jQuery(this).val();
                    if ((selectvalue === 'simple') || (selectvalue === 'subscription')) {
                        jQuery('._crowdfundingcheckboxvalue_field').css('display', 'block');
                        jQuery('._crowdfundinggetminimumprice_field').css('display', 'block');
                        jQuery('._crowdfundinghideminimum_field').css('display', 'block');
                        jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'block');
                        jQuery('._crowdfundinggetmaximumprice_field').css('display', 'block');
                        jQuery('._crowdfundinghidemaximum_field').css('display', 'block');
                    } else {
                        jQuery('._crowdfundingcheckboxvalue_field').css('display', 'none');
                        jQuery('._crowdfundinggetminimumprice_field').css('display', 'none');
                        jQuery('._crowdfundinghideminimum_field').css('display', 'none');
                        jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'none');
                        jQuery('._crowdfundinggetmaximumprice_field').css('display', 'none');
                        jQuery('._crowdfundinghidemaximum_field').css('display', 'none');
                    }
                });
                jQuery('#product-type').each(function () {
                    var selectvalue = jQuery(this).val();
                    if ((selectvalue === 'simple') || (selectvalue === 'subscription')) {
                        jQuery('._crowdfundingcheckboxvalue_field').css('display', 'block');
                        jQuery('._crowdfundinggetminimumprice_field').css('display', 'block');
                        jQuery('._crowdfundinghideminimum_field').css('display', 'block');
                        jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'block');
                        jQuery('._crowdfundinggetmaximumprice_field').css('display', 'block');
                        jQuery('._crowdfundinghidemaximum_field').css('display', 'block');
                    } else {
                        jQuery('._crowdfundingcheckboxvalue_field').css('display', 'none');
                        jQuery('._crowdfundinggetminimumprice_field').css('display', 'none');
                        jQuery('._crowdfundinghideminimum_field').css('display', 'none');
                        jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'none');
                        jQuery('._crowdfundinggetmaximumprice_field').css('display', 'none');
                        jQuery('._crowdfundinghidemaximum_field').css('display', 'none');
                    }
                });
            });</script>

        <?php
    }

    /**
     * Enqueue Script for admin page to perform jquery actions.
     */
    public static function crowdfundingadminenqueuescript() {
        global $woocommerce;
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-datepicker');

        wp_enqueue_script('cfcustomscript', plugins_url('/js/customscript.js', __FILE__));
        wp_enqueue_script('cfdatepickerscript', plugins_url('/js/datepicker.js', __FILE__));
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'crowdfunding_callback') {
                wp_enqueue_script('jscolor', plugins_url('/jscolor/jscolor.js', __FILE__));
            }
        }
    }

    public static function crowdfunding_head_script() {
        global $post;
        global $woocommerce;
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
        <?php
        if (isset($post->ID)) {
            if (( get_post_type($post->ID) == 'product') || (isset($_GET['post_type']) && $_GET['post_type'] == 'product')) {
                ?>
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery('.crowdfunding_options').chosen();
                            jQuery('#_cf_product_selection').chosen();
                            jQuery('.target_end_selection').chosen();
                <?php } else { ?>
                            jQuery('.crowdfunding_options').select2();
                            jQuery('#_cf_product_selection').select2();
                            jQuery('.target_end_selection').select2();
                <?php } ?>

                <?php if (get_post_meta($post->ID, '_crowdfunding_options', true) == '') { ?>
                            jQuery('._crowdfundinggetminimumprice_field').hide();
                            jQuery('._crowdfundinghideminimum_field').hide();
                            jQuery('._crowdfundinggetrecommendedprice_field').hide();
                            jQuery('._crowdfundinghiderecommendedprice_field').hide();
                            jQuery('._crowdfundinggetmaximumprice_field').hide();
                            jQuery('._crowdfundinghidemaximum_field').hide();
                            jQuery('._cf_selection_field').hide();
                            jQuery('._crowdfundinggettargetprice_field').hide();

                            jQuery('._crowdfundinghidetarget_field').hide();

                            jQuery('._target_end_selection_field').hide();
                            jQuery('._crowdfundingfromdatepicker_field').hide();
                            jQuery('._crowdfundingtodatepicker_field').hide();
                <?php } elseif (get_post_meta($post->ID, '_crowdfunding_options', true) == '1') { ?>
                            jQuery('._crowdfundinggetminimumprice_field').show();
                            jQuery('._crowdfundinghideminimum_field').show();
                            jQuery('._crowdfundinggetrecommendedprice_field').show();
                            jQuery('._crowdfundinghiderecommendedprice_field').show();
                            jQuery('._crowdfundinggetmaximumprice_field').show();
                            jQuery('._crowdfundinghidemaximum_field').show();
                            jQuery('._cf_selection_field').hide();
                            jQuery('._use_selected_product_image_field').hide();
                            jQuery('._crowdfundinggettargetprice_field').show();
                            jQuery('._crowdfundinghidetarget_field').show();
                            jQuery('._target_end_selection_field').show();
                            jQuery('._crowdfundingfromdatepicker_field').hide();
                            jQuery('._crowdfundingtodatepicker_field').hide();
                            jQuery('#_crowdfundinggettargetprice').attr('readonly', false);
                <?php } else { ?>
                            jQuery('._crowdfundinggetminimumprice_field').show();
                            jQuery('._crowdfundinghideminimum_field').show();
                            jQuery('._crowdfundinggetrecommendedprice_field').show();
                            jQuery('._crowdfundinghiderecommendedprice_field').show();
                            jQuery('._crowdfundinggetmaximumprice_field').show();
                            jQuery('._crowdfundinghidemaximum_field').show();
                            jQuery('._cf_selection_field').show();
                            jQuery('._use_selected_product_image_field').show()
                            jQuery('._crowdfundinggettargetprice_field').show();
                            jQuery('._crowdfundinghidetarget_field').show();
                            jQuery('._target_end_selection_field').show();
                            jQuery('._crowdfundingfromdatepicker_field').hide();
                            jQuery('._crowdfundingtodatepicker_field').hide();
                            jQuery('#_crowdfundinggettargetprice').attr('readonly', true);
                <?php } ?>

                        if (jQuery('#_crowdfunding_options').val() == '1') {
                            jQuery('._crowdfundinggetminimumprice_field').show();
                            jQuery('._crowdfundinghideminimum_field').show();
                            jQuery('._crowdfundinggetrecommendedprice_field').show();
                            jQuery('._crowdfundinghiderecommendedprice_field').show();
                            jQuery('._crowdfundinggetmaximumprice_field').show();
                            jQuery('._crowdfundinghidemaximum_field').show();
                            jQuery('._cf_selection_field').hide();
                            jQuery('._use_selected_product_image_field').hide();
                            jQuery('._crowdfundinggettargetprice_field').show();
                            jQuery('._crowdfundinghidetarget_field').show();
                            jQuery('._target_end_selection_field').show();
                            jQuery('._crowdfundingfromdatepicker_field').hide();
                            jQuery('._crowdfundingtodatepicker_field').hide();
                            jQuery('#_crowdfundinggettargetprice').attr('readonly', false);
                        }
                        jQuery('#_crowdfunding_options').change(function (e) {
                            jQuery('.maindivcf').show();
                            var updatevalue = jQuery(this).val();
                            //alert(updatevalue);
                            if (updatevalue === '2') {
                                jQuery('._crowdfundinggetminimumprice_field').show();
                                jQuery('._crowdfundinghideminimum_field').show();
                                jQuery('._crowdfundinggetrecommendedprice_field').show();
                                jQuery('._crowdfundinghiderecommendedprice_field').show();
                                jQuery('._crowdfundinggetmaximumprice_field').show();
                                jQuery('._crowdfundinghidemaximum_field').show();
                                jQuery('._cf_selection_field').show();
                                jQuery('._use_selected_product_image_field').show();
                                jQuery('._crowdfundinggettargetprice_field').show();
                                jQuery('._crowdfundinghidetarget_field').show();
                                jQuery('._target_end_selection_field').show();
                                jQuery('._crowdfundingfromdatepicker_field').hide();
                                jQuery('._crowdfundingtodatepicker_field').hide();
                                jQuery('#_crowdfundinggettargetprice').attr('readonly', true);


                            } else {
                                jQuery('._crowdfundinggetminimumprice_field').show();
                                jQuery('._crowdfundinghideminimum_field').show();
                                jQuery('._crowdfundinggetrecommendedprice_field').show();
                                jQuery('._crowdfundinghiderecommendedprice_field').show();
                                jQuery('._crowdfundinggetmaximumprice_field').show();
                                jQuery('._crowdfundinghidemaximum_field').show();
                                jQuery('._cf_selection_field').hide();
                                jQuery('._use_selected_product_image_field').hide();
                                jQuery('._crowdfundinggettargetprice_field').show();
                                jQuery('._crowdfundinghidetarget_field').show();
                                jQuery('._target_end_selection_field').show();
                                jQuery('._crowdfundingfromdatepicker_field').hide();
                                jQuery('._crowdfundingtodatepicker_field').hide();
                                jQuery('#_crowdfundinggettargetprice').attr('readonly', false);
                            }

                        });

                        jQuery('#_target_end_selection').change(function (e) {
                            var currentvalue = jQuery(this).val();
                            if (currentvalue === '1' || currentvalue === '4') {
                                jQuery('._crowdfundingfromdatepicker_field').show();
                                jQuery('._crowdfundingtodatepicker_field').show();
                            } else {
                                jQuery('._crowdfundingfromdatepicker_field').hide();
                                jQuery('._crowdfundingtodatepicker_field').hide();
                            }
                        });
                <?php if (get_post_meta($post->ID, '_target_end_selection', true) == '1' || get_post_meta($post->ID, '_target_end_selection', true) == '4') { ?>
                            jQuery('._crowdfundingfromdatepicker_field').show();
                            jQuery('._crowdfundingtodatepicker_field').show();
                <?php } else {
                    ?>
                            jQuery('._crowdfundingfromdatepicker_field').hide();
                            jQuery('._crowdfundingtodatepicker_field').hide();
                <?php }
                ?>
                        jQuery('#_cf_product_selection').change(function () {
                            var thisvalue = 0;
                            jQuery('#_cf_product_selection > option:selected').each(function () {
                                var value = jQuery(this).attr('data-price');
                                thisvalue = parseFloat(thisvalue) + parseFloat(value);
                                thisvalue = thisvalue.toFixed(2);
                            });
                            jQuery("#_crowdfundinggettargetprice").val(thisvalue);
                        });
                        jQuery("#_crowdfunding_showhide_contributor").ready(function () {
                            jQuery('#_crowdfunding_showhide_contributor').change(function () {
                            });
                        });

                <?php
            }
        }
        ?>
            });
        </script>
        <?php
    }

    public static function crowdfunding_single_product_add_to_cart($cart_object) {
        if (isset($_POST['cfmainproductvalue'])) {
            update_option('add_custom_price' . $_POST['productid'], $_POST['cfmainproductvalue']);

            /* Custom Price in cookies for Crowdfunding start */
            $timezone = wc_timezone_string();
            if ($timezone != '') {
                $timezonedate = date_default_timezone_set($timezone);
            } else {
                $timezonedate = date_default_timezone_set('UTC');
            }
            setcookie('add_custom_price' . $_POST['productid'], $_POST['cfmainproductvalue'], time() + 60 * 60 * 24 * 2, '/');
            setcookie('add_custom_id' . $_POST['productid'], $_POST['productid'], time() + 60 * 60 * 24 * 2, '/');
            /* Custom Cookies end for Crowdfunding  end */

            update_option('add_custom_id' . $_POST['productid'], $_POST['productid']);
            get_option('add_custom_price' . $_POST['productid']);
            global $woocommerce;
            $cart_object = $woocommerce->cart;
            $woocommerce->cart->add_to_cart($_POST['productid']);
            echo "success";
        }
        exit();
    }

    public static function crowdfunding_alter_cart_prices($cart_object) {

        global $woocommerce;
        $cart_object = $woocommerce->cart;
        foreach ($cart_object->cart_contents as $key => $value) {
            if (isset($_COOKIE['add_custom_id' . $value['product_id']])) {
                if ($value['product_id'] == $_COOKIE['add_custom_id' . $value['product_id']]) {
                    $checkvalueid = get_post_meta($value['product_id'], '_crowdfundingcheckboxvalue', true);
                    if ($value['data']->product_type != 'variable') {
                        if (($checkvalueid == 'yes')) {
                            $value['data']->price = $_COOKIE['add_custom_price' . $value['product_id']];
                        }
                    }
                }
            }
        }
    }

    public static function crowdfunding_format_price_in_decimal($price) {
        if (function_exists('woocommerce_format_decimal')) {
            return woocommerce_format_decimal($price, 2);
        }
        if (function_exists('wc_format_decimal')) {
            return wc_format_decimal($price, 2);
        }
    }

    public static function crowdfunding_custom_product_price_update_to_cart() {
        global $post;
        global $woocommerce;
        $newid = '';
        if (isset($post)) {
            if ($post->post_type == 'product') {
                $newid = @$post->ID;
            } else {
                $regex = '~\[([^]]*)\]~';
                preg_match_all($regex, $post->post_content, $matches);
                if (!empty($matches[1])) {
                    $shortcode = strchr(implode($matches[1]), "product_page");
                    if ($shortcode != "") {
                        $newid = intval(preg_replace('/[^0-9]+/', '', $shortcode), 10);
                    }
                } else {
                    $regex1 = '~\{([^}]*)\}~';
                    preg_match_all($regex1, $post->post_content, $matches);
                    if (!empty($matches[1])) {
                        $shortcode = strchr(implode($matches[1]), "product_page");
                        if ($shortcode != "") {
                            $newid = intval(preg_replace('/[^0-9]+/', '', $shortcode), 10);
                        }
                    }
                }
            }
            $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
            $singleminimum = get_post_meta($newid, '_crowdfundinggetminimumprice', true);
            $singlemaximum = get_post_meta($newid, '_crowdfundinggetmaximumprice', true);
            $singlerecommend = get_post_meta($newid, '_crowdfundinggetrecommendedprice', true);
            if (function_exists('get_product')) {

                if ($checkvalue == 'yes') {
                    $getdate = date("m/d/Y");
                    $gethour = date("h");
                    $getminutes = date("i");
                    $fromdate = get_post_meta($newid, '_crowdfundingfromdatepicker', true);
                    $todate = get_post_meta($newid, '_crowdfundingtodatepicker', true);
                    $tohours = get_post_meta($newid, '_crowdfundingtohourdatepicker', true);
                    $tominutes = get_post_meta($newid, '_crowdfundingtominutesdatepicker', true);
                    $fromhours = get_post_meta($newid, '_crowdfundingfromhourdatepicker', true);
                    $fromminutes = get_post_meta($newid, '_crowdfundingfromminutesdatepicker', true);
                    if ($fromdate != '') {
                        if ($fromhours == '' || $fromminutes == '') {
                            $fromdate = $fromdate . "23:59:59";
                        } else {
                            $time = $fromhours . ':' . $fromminutes . ':' . '00';
                            $fromdate = $fromdate . $time;
                        }
                    } else {
                        if ($tohours == '' || $tominutes == '') {
                            $fromdate = $getdate;
                        } else {
                            $fromdate = $getdate;
                            $fromhour = $gethour;
                            $fromminutes = $getminutes;
                        }
                        update_post_meta($newid, '_crowdfundingfromdatepicker', $getdate);
                        update_post_meta($newid, '_crowdfundingfromhourdatepicker', $gethour);
                        update_post_meta($newid, '_crowdfundingfromminutesdatepicker', $getminutes);
                    }
                    if ($tohours != '' || $tominutes != '') {
                        $time = $tohours . ':' . $tominutes . ':' . '00';
                        $datestr = $todate . $time; //Your date
                    } else {
                        $datestr = $todate . "23:59:59";
                    }//Your date
                    $date = strtotime($datestr); //Converted to a PHP date (a second count)
                    //Calculate difference
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            var productid = <?php echo $newid; ?>;
                            jQuery('.single_add_to_cart_button').click(function () {
                                var productids = jQuery(this).attr('data-productid');
                                var mainproduct = jQuery('.addfundraiser' + productids).val();
                                var dataperk = jQuery('.single_add_to_cart_button').attr('data-perk');
                                if (parseFloat(mainproduct) < dataperk) {
                                    jQuery('.singlecrowdfunding' + productid).fadeIn();
                                    jQuery('.singlecrowdfunding' + productid).html("<?php echo get_option('cf_perk_error_message'); ?>");
                                    jQuery('.singlecrowdfunding' + productid).fadeOut(3000);
                                    return false;
                                }
                                var quantitycount = jQuery('.quantitycount' + productids).val();
                    <?php if ($singlemaximum != '') { ?>
                                    if (mainproduct > <?php echo CrowdFunding::crowdfunding_format_price_in_decimal($singlemaximum); ?>) {
                        <?php if (get_option('display_error_message_on_alert_box') == 'yes') { ?>
                                            // alert("<?php echo do_shortcode(get_option('cf_max_price_error_msg')); ?>");

                        <?php } ?>

                                        jQuery('.singlecrowdfunding' + productids).fadeIn();
                                        jQuery('.singlecrowdfunding' + productids).html("<?php echo do_shortcode(get_option('cf_max_price_error_msg')); ?>");
                                        jQuery('.singlecrowdfunding' + productids).fadeOut(3000);
                                        return false;
                                    }
                    <?php } ?>
                    <?php if ($singleminimum != '') { ?>
                                    if (mainproduct < <?php echo CrowdFunding::crowdfunding_format_price_in_decimal($singleminimum); ?>) {
                        <?php if (get_option('display_error_message_on_alert_box') == 'yes') { ?>
                                            //     alert("<?php echo do_shortcode(get_option('cf_min_price_error_msg')); ?>");
                        <?php } ?>

                                        jQuery('.singlecrowdfunding' + productids).fadeIn();
                                        jQuery('.singlecrowdfunding' + productids).html("<?php echo do_shortcode(get_option('cf_min_price_error_msg')); ?>");
                                        jQuery('.singlecrowdfunding' + productids).fadeOut(3000);
                                        return false;
                                    }
                    <?php } ?>
                                if ((mainproduct === '')) {
                    <?php if (get_option('display_error_message_on_alert_box') == 'yes') { ?>
                                        //  alert("<?php echo do_shortcode(get_option('cf_min_price_error_msg')); ?>");
                    <?php } ?>
                                    jQuery('.singlecrowdfunding' + productids).fadeIn();
                                    jQuery('.singlecrowdfunding' + productids).html("<?php echo do_shortcode(get_option('cf_min_price_error_msg')); ?>");
                                    jQuery('.singlecrowdfunding' + productids).fadeOut(3000);
                                    return false;
                                }
                                else {
                                    var checknumber = jQuery.isNumeric(mainproduct);
                                    if (checknumber === true) {

                                    } else {
                    <?php if (get_option('display_error_message_on_alert_box') == 'yes') { ?>
                                            //  alert("<?php echo get_option('cf_input_price_error_message'); ?>");
                    <?php } ?>

                                        jQuery('.singlecrowdfunding' + productids).fadeIn();
                                        jQuery('.singlecrowdfunding' + productids).html("<?php echo get_option('cf_input_price_error_message'); ?>");
                                        jQuery('.singlecrowdfunding' + productids).fadeOut(3000);
                                        return false;
                                    }
                                }
                            });
                        });
                    </script>
                    <?php
                }
            }
        }
    }

    /**
     * Set The Contribution Amount in Session
     *
     */
    public static function set_gf_contribution_amount_session($cart_item_key, $product_id = null, $quantity = null, $variation_id = null, $variation = null) {
        //echo 1;
        //echo $cart_item_key;
        //echo $_POST['cf_contributor_name_field_value'];
        if (isset($_POST['addfundraiser' . $product_id])) {
            WC()->session->set($cart_item_key . '_get_galaxyfunder_contributionamount', $_POST['addfundraiser' . $product_id]);
        } else {
            WC()->session->__unset($cart_item_key . '_get_galaxyfunder_contributionamount');
        }

        if (isset($_POST['cf_contributor_name_field_value'])) {
            WC()->session->set($cart_item_key . '_get_galaxyfunder_contributorname', $_POST['cf_contributor_name_field_value']);
        } else {
            WC()->session->__unset($cart_item_key . '_get_galaxyfunder_contributorname');
        }
    }

    /*
     * Change the Contribution amount as the Product Price
     *
     */

    public static function set_gf_contribution_amount_as_product_price($cart_object) {

        foreach ($cart_object->cart_contents as $key => $value) {
            if (WC()->session->__isset($key . '_get_galaxyfunder_contributionamount')) {
                $value['data']->price = WC()->session->get($key . '_get_galaxyfunder_contributionamount');
            }
        }
    }

    /*
     * Save the Contributor name from session in order id
     *
     */

    public static function save_gf_contributor_name_in_order($order_id) {
        global $woocommerce;
        $current_cart_contents = $woocommerce->cart->cart_contents;
        foreach ($current_cart_contents as $key => $value) {
            if (WC()->session->get($key . '_get_galaxyfunder_contributorname')) {
                $previous_contributors = get_post_meta($value['product_id'], 'contributor_list_for_campaign', true);
                $contributor_name = WC()->session->get($key . '_get_galaxyfunder_contributorname');
                if (isset($contributor_name)) {
                    update_post_meta($order_id, 'contributor_list_for_campaign', $contributor_name);
                }
            }
        }
    }

    /**
     * Registering Custom Field Admin Settings of Crowdfunding in woocommerce admin fields funtion
     */
    public static function crowdfunding_register_admin_settings() {
        woocommerce_admin_fields(CrowdFunding::crowdfunding_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in crowdfunding
     */
    public static function crowdfunding_update_settings() {
        woocommerce_update_options(CrowdFunding::crowdfunding_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function crowdfunding_default_settings() {
        global $woocommerce;
        foreach (CrowdFunding::crowdfunding_admin_fields() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function crowdfunding_woocommerce_locate_template($template, $template_name, $template_path) {

        global $product;
        if (isset($product->id)) {
            $newid = $product->id;
            $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
            $plugin_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/';
            if ($checkvalue == 'yes') {
                if (file_exists($plugin_path . $template_name)) {
                    $template = $plugin_path . $template_name;
                    return $template;
                }
            }
        }
        return $template;
    }

    public static function register_my_custom_submenu_page() {
        global $my_admin_page;

        $my_admin_page = add_submenu_page('woocommerce', __('Galaxy Funder', 'galaxyfunder'), __('Galaxy Funder', 'galaxyfunder'), 'manage_options', 'crowdfunding_callback', array('CrowdFunding', 'my_custom_submenu_page_callback'));
    }

    public static function cf_do_output_buffer() {
        ob_start();
    }

    public static function alter_script_in_crowdfunding() {
        global $my_admin_page;
//  echo $_GET['page'];
        if (isset($_GET['page'])) {
            if (($_GET['page'] == 'crowdfunding_callback')) {
                $array[] = 'woocommerce_page_' . $_GET['page'];

                return $array;
            } else {
                $array[] = '';
                return $array;
            }
        }
    }

    public static function reset_changes_crowdfunding() {
        global $woocommerce;
        if (!empty($_POST['reset'])) {
            if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-reset_settings'))
                die(__('Action failed. Please refresh the page and retry.', 'galaxyfunder'));
            if (isset($_POST['reset'])) {
                foreach (CrowdFunding::crowdfunding_admin_fields() as $setting) {
                    if (isset($setting['newids']) && ($setting['std'])) {
                        delete_option($setting['newids']);
                        add_option($setting['newids'], $setting['std']);
                    }
                }
            }
            delete_transient('woocommerce_cache_excluded_uris');
            $redirect = esc_url_raw(add_query_arg(array('saved' => 'true')));
            if (isset($_POST['reset'])) {
                wp_safe_redirect($redirect);
                exit;
            }
        }
    }

    public static function my_custom_submenu_page_callback() {
        global $woocommerce, $woocommerce_settings, $current_section, $current_tab;
        $tabs = "";
        do_action('woocommerce_cf_settings_start');
        $current_tab = ( empty($_GET['tab']) ) ? 'crowdfunding' : sanitize_text_field(urldecode($_GET['tab']));
        $current_section = ( empty($_REQUEST['section']) ) ? '' : sanitize_text_field(urldecode($_REQUEST['section']));
        if (!empty($_POST['save'])) {
            if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-settings'))
                die(__('Action failed. Please refresh the page and retry.', 'galaxyfunder'));

            if (!$current_section) {
                switch ($current_tab) {
                    default :
                        if (isset($woocommerce_settings[$current_tab]))
                            woocommerce_update_options($woocommerce_settings[$current_tab]);
                        do_action('woocommerce_update_options_' . $current_tab);
                        break;
                }

                do_action('woocommerce_update_options');
                if ($current_tab == 'general' && get_option('woocommerce_frontend_css') == 'yes') {

                }
            } else {
                do_action('woocommerce_update_options_' . $current_tab . '_' . $current_section);
            }
            delete_transient('woocommerce_cache_excluded_uris');
            $redirect = esc_url_raw(add_query_arg(array('saved' => 'true')));
            if (isset($_POST['subtab'])) {
                wp_safe_redirect($redirect);
                exit;
            }
        }
// Get any returned messages
        $error = ( empty($_GET['wc_error']) ) ? '' : urldecode(stripslashes($_GET['wc_error']));
        $message = ( empty($_GET['wc_message']) ) ? '' : urldecode(stripslashes($_GET['wc_message']));

        if ($error || $message) {

            if ($error) {
                echo '<div id="message" class="error fade"><p><strong>' . esc_html($error) . '</strong></p></div>';
            } else {
                echo '<div id="message" class="updated fade"><p><strong>' . esc_html($message) . '</strong></p></div>';
            }
        } elseif (!empty($_GET['saved'])) {

            echo '<div id="message" class="updated fade"><p><strong>' . __('Your settings have been saved.', 'galaxyfunder') . '</strong></p></div>';
        }
        ?>
        <div class="wrap woocommerce">
            <form method="post" id="mainform" action="" enctype="multipart/form-data">
                <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div><h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                    <?php
                    $tabs = apply_filters('woocommerce_cf_settings_tabs_array', $tabs);

                    foreach ($tabs as $name => $label) {
                        // echo $current_tab;
                        echo '<a href="' . admin_url('admin.php?page=crowdfunding_callback&tab=' . $name) . '" class="nav-tab ';
                        if ($current_tab == $name)
                            echo 'nav-tab-active';
                        echo '">' . $label . '</a>';
                    }
                    do_action('woocommerce_cf_settings_tabs');
                    ?>
                </h2>

                <?php
                switch ($current_tab) :
                    case "crowdfunding_listtable" :
                        CrowdFunding::crowdfunding_adminpage();
                        break;
                    case "perk_info" :
                        CrowdFunding::perk_info_check();
                        break;
                    case "Contribution extension" :

                        CFContributioneextension::cf_contribution_extension_table();
                        break;
                    default :
                        do_action('woocommerce_cf_settings_tabs_' . $current_tab);
                        break;
                endswitch;
                ?>

                <p class="submit">
                    <?php if (!isset($GLOBALS['hide_save_button'])) : ?>




                        <input name="save" id="saveid"  class="button-primary" type="submit" value="<?php _e('Save changes', 'woocommerce'); ?>" />
                        <?php
                        if (isset($_GET['tab'])) {
                            $tab_name = $_GET['tab'];
                            if ($tab_name == 'perk_info' || $tab_name == 'Contribution extension') {
                                ?>
                                <script type="text/javascript">
                                    jQuery('#saveid').css("display", "none");

                                </script>

                                <?php
                            }
                        }
                        ?>
                    <?php endif; ?>
                    <input type="hidden" name="subtab" id="last_tab" />
                    <?php wp_nonce_field('woocommerce-settings', '_wpnonce', true, true); ?>
                </p>

            </form>
            <form method="post" id="mainforms" action="" enctype="multipart/form-data" style="float: left; margin-top: -52px; margin-left: 159px;">


                <input name="reset" id="resetid" class="button-secondary" type="submit" value="<?php _e('Reset', 'woocommerce'); ?>"/>
                <?php wp_nonce_field('woocommerce-reset_settings', '_wpnonce', true, true); ?>
                <?php
                if (isset($_GET['tab'])) {
                    $tab_name = $_GET['tab'];
                    if ($tab_name == 'perk_info' || $tab_name == 'Contribution extension') {
                        ?>
                        <script type="text/javascript">
                            jQuery('#resetid').css("display", "none");

                        </script>

                        <?php
                    }
                }
                ?>
            </form>

        </div>
        <?php
    }

    public static function crowdfunding_newadmin_values() {
        global $post;
        global $woocommerce;
        foreach (get_posts('post_type=shop_order&numberposts=-1&post_status=publish')as $neworder) {
            $order = new WC_Order($neworder->ID);
            foreach ($order->get_items() as $newitem) {
                $productid = $newitem['product_id'];
                if (get_post_meta($productid, '_crowdfundingcheckboxvalue', true) == 'yes') {
                    $listallproductids[] = $productid;
                    $listordertotals[] = $newitem['line_total'];
                }
            }
        }

        $getproductcounts = count($listallproductids);
        $combined = array();
        if ($getproductcounts > 0) {
            for ($i = 0; $i < $getproductcounts; $i++) {
                if (!array_key_exists($listallproductids[$i], $combined)) {
                    $combined[$listallproductids[$i]] = $listordertotals[$i];
                } else {
                    $combined[$listallproductids[$i]] += $listordertotals[$i];
                }
            }
        }


        foreach ($listallproductids as $oneproductid) {

        }
    }

    public static function crowdfunding_adminpage_values($orderid) {
        global $post;
        global $woocommerce;
        $products = array();
        $order_totalss = array();
        $productcounts = array();
        $productorderids = array();
        $arraycounter = array();
        $order_ids = array();

        $orderobject = new WC_Order($orderid);
        foreach ($orderobject->get_items() as $eachitem) {

            $checkcrowdfunding = get_post_meta($eachitem['product_id'], '_crowdfundingcheckboxvalue', true);
            if ($checkcrowdfunding == 'yes') {
                $getoldorderids = (array) get_post_meta($eachitem['product_id'], 'orderids', true);
                $currentorderids = array($orderid);
                $currentorderids = array_merge((array) $currentorderids, $getoldorderids);
                update_post_meta($eachitem['product_id'], 'orderids', $currentorderids);
            }
        }

        foreach (get_posts('post_type=shop_order&numberposts=-1&post_status=publish') as $order) {
            $order = new WC_Order($order->ID);
            $neworderid = $order->ID;
            if ($order->status == CrowdFunding::get_order_status_for_contribution()) {
                foreach ($order->get_items() as $item) {

                    $product_id = $item['product_id'];
                    $products[] = $product_id;
                    $productcounts[] = $item['product_id'];
                    $order_totalss[] = $item['line_total'];
                    if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {
                        $order_ids[] = $order->id;
                    }
                }
            }
        }
        update_option('updated_orderids', $order_ids);
        $combined = array();

        $productCount = count($productcounts);
        if ($productCount > 0) {
            for ($i = 0; $i < $productCount; $i++) {

                if (!array_key_exists($productcounts[$i], $combined)) {
                    $combined[$productcounts[$i]] = $order_totalss[$i];
                } else {
                    $combined[$productcounts[$i]] += $order_totalss[$i];
                }
            }if (is_array($productcounts)) {
                $arraycounter = array_count_values($productcounts);
                foreach ($productcounts as $newcount) {
                    if (get_post_meta($newcount, '_crowdfundingcheckboxvalue', true) == 'yes') {
                        update_post_meta($newcount, '_crowdfundingtotalprice', $combined[$newcount]);
                        $gettotalraisedamount = get_post_meta($newcount, '_crowdfundingtotalprice', true);
                        $gettargetgoalamount = get_post_meta($newcount, '_crowdfundinggettargetprice', true);
                        $getordertotal = get_post_meta($newcount, '_crowdfundingtotalprice', true);
                        if ($gettargetgoalamount != '') {
                            if (($getordertotal != '') && ($gettargetgoalamount > 0)) {
                                $count1 = $getordertotal / $gettargetgoalamount;
                                $count2 = $count1 * 100;
                                $counter = number_format($count2, 0);
                                update_post_meta($newcount, '_crowdfundinggoalpercent', $counter);
                            }
                        }
                        update_post_meta($newcount, '_update_total_funders', $arraycounter[$newcount]);
                    }
                }
            }
        }
    }

    public static function perk_info_check() {
        $orderid = $_GET['order_id'];

        $order_id = $orderid;

        global $post;
        echo $post;
        global $woocommerce;
        $products = $order_id;
        $i = 0;
        ?>
        <style type="text/css">
        <?php $cf_button_color = get_option('cf_button_color'); ?>
        <?php $cf_button_text_color = get_option('cf_button_text_color'); ?>
        <?php $cf_selected_button_color = get_option('cf_selected_button_color'); ?>
        <?php $cf_selected_button__text_color = get_option('cf_selected_button_text_color'); ?>


            .cf_amount_button{
                float:left;
                width: 85px;
                margin-right:10px;
                margin-top:10px;
                height:50px;
                border: 1px solid #ddd;
                background: #<?php echo $cf_button_color; ?>;
                color:#<?php echo $cf_button_text_color; ?>;
                text-align: center;
                padding-top: 10px;
                cursor: pointer;
        <?php if (get_option('cf_button_box_shadow') == '1') { ?>
                    box-shadow: 3px 3px 2px  #888888;
        <?php } else { ?>
                    box-shadow: none;
        <?php } ?>
            }
            .cf_amount_button_clicked{
                background: #<?php echo $cf_selected_button_color; ?>;
                color:#<?php echo $cf_selected_button__text_color; ?>;
            }
        </style>
        <?php
        $showhide_serialnumber = get_option('cf_serial_number_show_hide');
        $showhide_contributorname = get_option('cf_contributor_name_show_hide');
        $showhide_contributoremail = get_option('cf_contributor_email_show_hide');
        $showhide_contribution = get_option('cf_contribution_show_hide');
        $showhide_date = get_option('cf_date_column_show_hide');
        $showhide_perkname = get_option('cf_perk_name_column_show_hide');
        $showhide_perkamount = get_option('cf_perk_amount_column_show_hide');
        ?>
        <?php
        if (function_exists('wc_get_order_statuses')) {
            $getpoststatus = array_keys(wc_get_order_statuses());
        } else {
            $getpoststatus = 'publish';
        }

        $listofcontributedorderids = array_unique(array_filter((array) get_post_meta($order_id, 'orderids', true)));
        if (is_array($listofcontributedorderids)) {
            foreach ($listofcontributedorderids as $order) {

                $myorderid = $order;
                $order = new WC_Order($order);
                foreach ($order->get_items() as $item) {
                    $products = array();
                    $product_id = $item['product_id'];
                    $products[] = $product_id;
                    if (in_array($order_id, $products)) {
                        $newpostid = $order_id;
                        $funding_options = get_post_meta($item['product_id'], '_crowdfunding_options', true);
                        $userid = get_post_field('post_author', $products);
                        /* Shipping Information for the Corresponding USER/AUTHOR */
                        $ship_first_name = get_user_meta($userid, 'shipping_first_name', true);
                        $ship_last_name = get_user_meta($userid, 'shipping_last_name', true);
                        $ship_company = get_user_meta($userid, 'shipping_company', true);
                        $ship_address1 = get_user_meta($userid, 'shipping_address_1', true);
                        $ship_address2 = get_user_meta($userid, 'shipping_address_2', true);
                        $ship_city = get_user_meta($userid, 'shipping_city', true);
                        $ship_country = get_user_meta($userid, 'shipping_country', true);
                        $ship_postcode = get_user_meta($userid, 'shipping_postcode', true);
                        $ship_state = get_user_meta($userid, 'shipping_state', true);
                        $ship_email = get_user_meta($userid, 'billing_email', true);

                        $bill_first_name = get_user_meta($userid, 'billing_first_name', true);
                        $bill_last_name = get_user_meta($userid, 'billing_last_name', true);
                        $bill_company = get_user_meta($userid, 'billing_company', true);
                        $bill_address1 = get_user_meta($userid, 'billing_address_1', true);
                        $bill_address2 = get_user_meta($userid, 'billing_address_2', true);
                        $bill_city = get_user_meta($userid, 'billing_city', true);
                        $bill_country = get_user_meta($userid, 'billing_country', true);
                        $bill_postcode = get_user_meta($userid, 'billing_postcode', true);
                        $bill_state = get_user_meta($userid, 'billing_state', true);
                        $bill_phone = get_user_meta($userid, 'billing_phone', true);


                        if ($order->status == CrowdFunding::get_order_status_for_contribution()) {
                            if ($i == 0) {

                                echo '<p style="display:inline-table"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                                ?>
                                <table class="wp-list-table widefat fixed posts" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" id="campaign_monitor" cellspacing="0">
                                    <h3> <?php echo __('Campaign Contribution Table', 'galaxyfunder'); ?></h3>
                                    <thead>
                                        <tr>

                                            <th class="cf_serial_number_label" id="cf_serial_number_label" data-toggle="true" data-sort-initial = "true"><?php echo get_option('cf_serial_number_label'); ?></th>


                                            <th class="cf_contributor_label" id="cf_contributor_label"><?php echo get_option('cf_contributor_label'); ?></th>





                                            <th class="cf_contribution_label" id="cf_contribution_label" data-hide="phone"><?php echo get_option('cf_donation_label'); ?></th>


                                            <th class="cf_contribution_perk_name" id="cf_contribution_perk_name" data-hide="phone"><?php echo get_option('cf_perk_name_label'); ?></th>


                                            <th class="cf_contribution_perk_amount" id="cf_contribution_perk_amount" data-hide="phone"><?php echo get_option('cf_perk_amount_label'); ?></th>


                                            <th class="cf_perkquantity" id="cf_perk_label" data-hide="phone,tablet"><?php echo get_option('cf_perk_quantity_label'); ?></th>


                                            <th class="cf_date_label" id="cf_date_label" data-hide="phone,tablet"><?php echo get_option('cf_date_label'); ?></th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    }
                                    $i++;
                                    ?>
                                    <tr>
                                        <?php if ($showhide_serialnumber == '1') { ?>
                                            <td class='serial_id' data-value="<?php echo $i; ?>" id='serial_id'><?php echo $i; ?>
                                            </td>
                                        <?php } ?>
                                        <?php if ($showhide_contributorname == '1') { ?>
                                            <td class='cf_billing_first_name' id='cf_billing_first_name'>
                                                <?php
                                                if (get_post_meta($myorderid, 'contributor_list_for_campaign', true) == '') {
                                                    if (get_post_meta($order->id, 'My Checkbox', true) == '1') {
                                                        echo 'Anonymous';
                                                    } else {
                                                        $mark_contributor_anonymous = get_post_meta($order_id, '_crowdfunding_contributor_anonymous', true);
                                                        if ($mark_contributor_anonymous == 'yes') {
                                                            echo 'Anonymous';
                                                        } else {
                                                            echo $order->billing_first_name . "&nbsp;" . $order->billing_last_name;
                                                        }
                                                    }
                                                } else {
                                                    if (get_post_meta($order->id, 'My Checkbox', true) == '1') {
                                                        echo 'Anonymous';
                                                    } else {
                                                        $mark_contributor_anonymous = get_post_meta($order_id, '_crowdfunding_contributor_anonymous', true);
                                                        if ($mark_contributor_anonymous == 'yes') {
                                                            echo 'Anonymous';
                                                        } else {
                                                            echo get_post_meta($myorderid, 'contributor_list_for_campaign', true);
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                        <?php } ?>
                                        &nbsp;
                                        <?php if ($showhide_contributoremail == '1') { ?>
                                            <td class='cf_billing_email' id='cf_billing_email'><?php echo $order->billing_email; ?></td>
                                        <?php } ?>
                                        <?php if ($showhide_contribution == '1') { ?>
                                            <td class='cf_order_total' id='cf_order_total'><?php echo CrowdFunding::get_woocommerce_formatted_price($item['line_total']); ?><br></td>
                                        <?php } ?>
                                        <?php if ($showhide_perkname == '1') { ?>
                                            <td class="cf_contribution_perk_name" id="cf_contribution_perk_name"><?php
                                                $cfperkname = get_post_meta($myorderid, 'perkname' . $order_id, true);
                                                if (!is_array($cfperkname)) {
                                                    if ($cfperkname != '') {
                                                        $cfperkname = $cfperkname;
                                                    } else {
                                                        $cfperkname = '-';
                                                    }
                                                    echo $cfperkname;
                                                } else {
                                                    echo implode(', ', $cfperkname);
                                                }
                                                ?></td>
                                        <?php } ?>
                                        <?php if ($showhide_perkamount == '1') { ?>
                                            <td class="cf_contribution_perk_amount" id="cf_contribution_perk_amount"><?php
                                                $cfperkamount = (int) get_post_meta($myorderid, 'perk_maincontainer' . $order_id, true);
                                                if ($cfperkamount != 0) {
                                                    $cfperkamount = CrowdFunding::get_woocommerce_formatted_price($cfperkamount);
                                                } else {
                                                    $cfperkamount = '-';
                                                }
                                                echo $cfperkamount;
                                                ?></td>
                                        <?php } ?>

                                        <td class="cf_perk_quantity" id="cf_perk_quantity">
                                            <?php
                                            $total_contribution = $item['line_total'];
                                            $perk_amount = (int) get_post_meta($myorderid, 'perk_maincontainer' . $order_id, true);
                                            if ($perk_amount > 0) {
                                                $perk_quantity = $total_contribution / $perk_amount;

                                                echo $perk_quantity;
                                            } else {

                                                echo'-';
                                            }
                                            ?>
                                        </td>

                                        <?php if ($showhide_date == '1') { ?>
                                            <td class='cf_order_date' id='cf_order_date'><?php echo $order->order_date; ?></td>
                                        <?php } ?>

                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr style="clear:both;">
                    <td colspan="7">
                        <div class="pagination pagination-centered"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </table>
        <div class="pagination pagination-centered"></div>

        <?php if ($funding_options == '2') { ?>
            <h3> <?php echo __('Campaign Contributor Billing & Shipping Information', 'galaxyfunder'); ?></h3>
            <div><b><?php echo _e('Compaign Creator Email ID : ', 'galaxyfunder'); ?></b><?php echo $ship_email; ?></div>
            <div>
                <table class="widefat wp-list_shipping-table" cellspacing="0" style="width:500px;height:370px;border: 10px solid #ddd;display:inline-block;" >
                    <tbody>
                        <tr>
                            <td scope="col" ><h3><?php _e('Shipping Address', 'galaxyfunder'); ?></h3></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_company; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_first_name . ' ' . $ship_last_name; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_address1; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_address2; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_city . '-' . $ship_postcode; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_state; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_country; ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="widefat wp-list_shipping-table" cellspacing="0" style="width:500px;border: 10px solid #ddd;float:left" >
                    <tbody>
                        <tr>
                            <td scope="col" ><h3><?php _e('Billing Address', 'galaxyfunder'); ?></h3></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $ship_company; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $bill_first_name . ' ' . $bill_last_name; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $bill_address1; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $bill_address2; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $bill_city . '-' . $bill_postcode; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $bill_state; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $bill_country; ?></td>
                        </tr>
                        <tr>
                            <td scope="col"><?php echo $bill_phone; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        }
// }
//}
    }

    public static function crowdfunding_adminpage() {
        ?>
        <h3><?php echo __('List of Campaigns', 'galaxyfunder'); ?></h3>
        <?php
        global $post;
        global $woocommerce;
        $i = 0;
        $j = 0;
        $args = array('post_type' => 'product', 'numberposts' => '-1', 'post_status' => 'publish', 'meta_value' => 'yes', 'meta_key' => '_crowdfundingcheckboxvalue');
        foreach (get_posts($args) as $productid) {
            $order = new WC_Order($productid);
            $order_id = $order->get_order_number();
            $prev = get_option('perk_qua');
            $count = $order->get_item_count();
            $checkifproductiscampaign = get_post_meta($productid->ID, '_crowdfundingcheckboxvalue', true);
            $gettotalraisedamount = get_post_meta($productid->ID, '_crowdfundingtotalprice', true);
            $gettargetgoalamount = get_post_meta($productid->ID, '_crowdfundinggettargetprice', true);
            $getraisedamountpercent = get_post_meta($productid->ID, '_crowdfundinggoalpercent', true);
            $getfundertotal = get_post_meta($productid->ID, '_update_total_funders', true);
            $gettargetdate = get_post_meta($productid->ID, '_crowdfundingtodatepicker', true);
            $checkvalue = get_post_meta($productid->ID, '_crowdfundingcheckboxvalue', true);
            $count = get_post_meta($productid->ID, 'update_perk_claim_count', true);
            $tohours = get_post_meta($productid->ID, '_crowdfundingtohourdatepicker', true);
            $tominutes = get_post_meta($productid->ID, '_crowdfundingtominutesdatepicker', true);
            $checkmethod = get_post_meta($productid->ID, '_target_end_selection', true);
            $todate = get_post_meta($productid->ID, '_crowdfundingtodatepicker', true);

            $timezone = wc_timezone_string();
            if ($timezone != '') {
                $timezonedate = date_default_timezone_set($timezone);
            } else {
                $timezonedate = date_default_timezone_set('UTC');
            }

            if ($i == 0) {
                echo '<p style="display:inline-table"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                ?>

                <table class="wp-list-table widefat fixed posts" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" id="campaign_monitor" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope='col' data-toggle="true" class='manage-column column-serial_number'  style="">
                                <a href="#"><span><?php echo __('S.No', 'galaxyfunder'); ?></span>

                            </th>
                            <th scope='col' id='campaign_name' class='manage-column column-campaign_name'  style=""><?php echo __('Campaign Name', 'galaxyfunder'); ?></th>
                            <th scope='col' id='campaign_creator' data-hide="phone" class='manage-column column-campaign_creator'  style=""><?php echo __('Campaign Creator', 'galaxyfunder'); ?></th>
                            <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                                <a href="#">                            <span><?php echo __('Date', 'galaxyfunder'); ?></span>

                                </a>
                            </th>
                            <th scope="col" id="campaign_target_goal" data-hide="phone" class="manage-column column-campaign_target_goal" style="">
                                <a href="#">
                                    <span><?php echo __('Goal', 'galaxyfunder'); ?></span>

                                </a>
                            </th>
                            <th scope='col' id='campaign_target_total' data-hide="phone" class='manage-column column-campaign_target_total'  style="">
                                <a href="#">
                                    <span><?php echo __('Raised', 'galaxyfunder'); ?></span>

                                </a>
                            </th>
                            <th scope="col" id="campaign_raised_percent" data-hide="phone" class="manage-column column-campaign_target_raised" style="">
                                <a href="#">
                                    <span><?php echo __('Raised %', 'galaxyfunder'); ?></span>

                                </a>
                            </th>
                            <th scope="col" id="campaign_count_funders" data-hide="phone,tablet" class="manage-column column-campaign_funders_count" style="">
                                <a href="#">
                                    <span><?php echo get_option('cf_funder_label'); ?></span>

                                </a>

                            <th scope="col"  id="campaign_status" data-hide="phone,tablet" class="manage-column column-campaign_status" style="">
                                <a href="#">
                                    <span><?php echo __('Status', 'galaxyfunder'); ?></span>

                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="the-list">
                        <?php
                    }
                    $i++;
                    if ($checkifproductiscampaign == 'yes') {
                        $j++;
                        // echo $productid->ID . "<br>";
                        if ($j % 2 != 0) {
                            $name = 'alternate';
                        } else {
                            $name = '';
                        }
                        ?>
                        <tr id="post-141" class="type-shop_order status-publish post-password-required hentry <?php echo $name; ?> iedit author-self level-0" valign="top">
                            <td>
                                <?php echo $j; ?>
                            </td>
                            <?php
                            if ($gettotalraisedamount != 0) {
                                ?>
                                <td class="campaign_creator_name">
                                    <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=crowdfunding_callback&tab=perk_info&order_id=' . $order_id); ?>" >
                                        <?php
                                        echo $productid->post_title;
                                        ?>
                                    </a>
                                </td>
                                <?php
                            }
                            if ($gettotalraisedamount == 0) {
                                ?>
                                <td class="campaign_creator_name">
                                    <?php
                                    echo $productid->post_title;
                                    ?>
                                    </a>
                                </td>

                            <?php } ?>
                            <td>
                                <?php
                                $author_id = $productid->post_author;
                                echo the_author_meta('user_nicename', $author_id);
                                ?>
                            </td>
                            <td class = "order_date column-order_date"><abbr title = "<?php echo $productid->post_date; ?>"><?php echo $productid->post_date; ?></abbr></td>
                            <td class="campaign_target_goal"><?php
                                if ($gettargetgoalamount != '') {
                                    echo CrowdFunding::get_woocommerce_formatted_price($gettargetgoalamount);
                                } else {
                                    echo CrowdFunding::get_woocommerce_formatted_price('0');
                                }
                                ?></td>
                            <td class = "order_total column-order_total"><?php
                                if ($gettotalraisedamount != '') {
                                    echo CrowdFunding::get_woocommerce_formatted_price($gettotalraisedamount);
                                } else {
                                    echo CrowdFunding::get_woocommerce_formatted_price("0");
                                }
                                ?>
                            </td>
                            <td class="order_total column-order_percent">
                                <?php
                                if ($getraisedamountpercent != '') {
                                    echo $getraisedamountpercent . "%";
                                } else {
                                    echo "0%";
                                }
                                ?>
                            </td>
                            <td class="funder_total column-order-total">
                                <?php
                                if ($getfundertotal != '') {
                                    echo $getfundertotal;
                                } else {
                                    echo "0";
                                }
                                ?>
                            </td>

                            <td class="campaign_status column-order-status">
                                <?php
                                if ($tohours != '' || $tominutes != '') {
                                    $time = $tohours . ':' . $tominutes . ':' . '00';
                                    $datestr = $todate . $time; //Your date
                                } else {
                                    $datestr = $todate . "23:59:59";
                                } //Your date
                                $date = strtotime($datestr); ////Converted to a PHP date (a second count)
//Calculate difference

                                if ($checkmethod == '1' || $checkmethod == '4') {
                                    if ($date >= time()) {
                                        $diff = $date - time(); //time returns current time in seconds
                                        $days = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                        $hours = ceil(($diff - $days * 60 * 60 * 24) / (60 * 60));
                                        //Report
                                        if ($hours > 1) {
                                            $hours = $hours . ' hours ';
                                        } else {
                                            $hours = $hours . ' hour ';
                                        }

                                        if ($days > 1) {
                                            _e($days . " days " . $hours . " to go", "galaxyfunder");
                                        } else {
                                            _e($days . " day " . $hours . " to go", "galaxyfunder");
                                        }
                                    }
                                } elseif ($checkmethod == '3' || $checkmethod == '2') {

                                    _e("---");
                                } else {
                                    _e("Campaign Closed", "galaxyfunder");
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
            <?php
        }

        public static function cf_translate_file() {
            load_plugin_textdomain('galaxyfunder', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        public static function crowdfunding_change_out_of_stock_caption($availability) {
            global $post;
            $checkvalue = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
            if ($checkvalue == 'yes') {
                $checkstatus = get_post_meta($post->ID, '_stock_status', true);
                if ($checkstatus == 'outofstock') {
                    $availability['availability'] = str_ireplace('Out of stock', get_option('cf_outofstock_label'), $availability['availability']);
                }
            }

            return $availability;
        }

        public static function crowdfunding_sold_individually() {
            global $post_id;

            $checkoption = get_post_meta($post_id, '_crowdfundingcheckbox', true);
            if ($checkoption == 'yes') {
                update_post_meta($post_id, '_sold_individually', 'yes');
            }
        }

        public static function add_script_to_admin() {
            ?>
            <script type="text/javascript">
                jQuery(function () {
                    jQuery('#campaign_monitor').footable();
                    jQuery('#campaign_monitor').footable().bind('footable_filtering', function (e) {
                        var selected = jQuery('.filter-status').find(':selected').text();
                        if (selected && selected.length > 0) {
                            e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                            e.clear = !e.filter;
                        }
                    });
                    jQuery('#change-page-size').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });


                });
            </script>
            <style type='text/css'>
                .widefat td, .widefat th {
                    /*text-align:center;*/
                }
            </style>
            <?php
        }

        public static function get_perk_claim_count_for_galaxy() {
            $order_id = '180';
            $order = new WC_Order($order_id);
            $neworderid = array($order_id);

            return;
            foreach ($order->get_items() as $item) {
                if (get_post_meta($item['product_id'], 'orderids', true) != '') {
                    $meta = get_post_meta($item['product_id'], 'orderids', true);
                    $newordereddata = array_merge($meta, $neworderid);
                    update_post_meta($item['product_id'], 'orderids', $newordereddata);
                } else {
                    update_post_meta($item['product_id'], 'orderids', true);
                }

                $getorderids = get_post_meta($item['product_id'], 'orderids', true);
                $perkrule = get_post_meta($item['product_id'], 'perk', true);
                if (is_array($perkrule)) {
                    foreach ($perkrule as $key => $perk) {



                        $perkname = str_replace('', '_', $perk['name']);
                        $currentcount = (int) get_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', true);
                        if ($perk['limitperk'] == 'cf_limited') {
                            if ($claimcount > $currentcount) {
                                update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length);
                            }
                        } else {
                            if ($perk['limitperk'] == 'cf_unlimited') {
                                update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length);
                            }
                        }
                    }
                }
            }
        }

        public static function update_perk_claim_main_function($order_id) {
            $order = new WC_Order($order_id);
            foreach ($order->get_items() as $eachitem) {
                $checkcrowdfundingisenable = get_post_meta($eachitem['product_id'], '_crowdfundingcheckboxvalue', true);
                if ($checkcrowdfundingisenable == 'yes') {
                    self::main_perk_claim_updation($order_id, $eachitem['product_id']);
                }
            }
        }

        public static function main_perk_claim_updation($orderid, $productid) {
            $getlistofperksorder = get_post_meta($orderid, 'getlistofquantities', true);
            $listofperkiteration = get_post_meta($orderid, 'listofiteration', true);
            $getallcampaignperks = get_post_meta($productid, 'perk', true);
            update_post_meta($orderid, 'testing_order_meta2', $getlistofperksorder);
            foreach ($getlistofperksorder as $eachiteration) {
                $eachiterationkey = explode('_', $eachiteration);
                $perkname = $getallcampaignperks[$eachiterationkey[0]]['name'];
                $perkname = str_replace('', '_', $perkname);
                $amount = $getallcampaignperks[$eachiterationkey[0]]['amount'];
                $getcountofpreviousclaimed = get_post_meta($productid, $perkname . $amount . 'update_perk_claim', true);
                $currentclaimedcount = $eachiterationkey[1];

                $overalllength = $getcountofpreviousclaimed + $currentclaimedcount;
                $perkclaimcount = $eachiterationkey[0]['claimcount'];
                $limitationofperk = $eachiterationkey[0]['limitperk'];
                if ($limitationofperk == 'cf_limited') {
                    if ($perkclaimcount > $getcountofpreviousclaimed) {
                        update_post_meta($productid, $perkname . $amount . 'update_perk_claim', $overalllength);
                    }
                } else {
                    update_post_meta($productid, $perkname . $amount . 'update_perk_claim', $overalllength);
                }

                $getchoosedproducts = $getallcampaignperks[$eachiterationkey[0]]['choose_products'];
                if ($getchoosedproducts != '') {
                    self::add_additional_item_to_order($orderid, $getchoosedproducts, $eachiterationkey[1]);
                }
            }
        }

        public static function add_additional_item_to_order($order_id, $choosedproduct, $qty) {
            $order = new WC_Order($order_id);
            $item_id = wc_add_order_item($order_id, array(
                'order_item_name' => get_the_title($choosedproduct), //get_the_title($perk['choose_products']),
                'order_item_type' => 'line_item'
            ));
            if ($item_id) {
                wc_add_order_item_meta($item_id, '_product_id', $choosedproduct);
                wc_add_order_item_meta($item_id, '_line_total', $regularprice);
                wc_add_order_item_meta($item_id, '_line_subtotal', $regularprice);
                wc_add_order_item_meta($item_id, '_line_tax', '0');
                wc_add_order_item_meta($item_id, '_line_subtotal_tax', '0');
                wc_add_order_item_meta($item_id, '_qty', $qty);
            }

            if (sizeof($order->get_items()) > 0) {
                foreach ($order->get_items() as $item) {
                    $_product = $order->get_product_from_item($item);
                    if ($_product && $_product->exists() && $_product->is_downloadable()) {
                        $downloads = $_product->get_files();
                        foreach (array_keys($downloads) as $download_id) {
                            wc_downloadable_file_permission($download_id, $item['product_id'], $order);
                        }
                    }
                }
            }
            update_post_meta($order_id, '_download_permissions_granted', 1);
        }

        public static function increase_perk_claim_count_for_order($orderid, $productid) {
            $getlistofperks = get_post_meta($productid, 'perk', true);
            $getlistoforders = get_post_meta($productid, 'orderids', true);
            $getlistofperksandqty = get_post_meta($orderid, 'getlistofquantities', true);
            $listofperkiteration = get_post_meta($orderid, 'listiteration', true);
            $getallordersperks = array();
            if (!empty($getlistoforders)) {
                foreach ($getlistoforders as $value) {


                    $getallordersperks[] = (array) get_post_meta($value, '_perk_iteration_id');
                }
                $getcountvalues = UniverseFunder_OrderFunction::get_number_of_perk_iteration_ids($getallordersperks);
                update_post_meta($productid, '_listofperkclaimed', $getcountvalues);
            }
        }

        public static function get_number_of_perk_iteration_ids($mainvalue) {
            $newarray = array();
            if (is_array($mainvalue)) {
                foreach ($mainvalue as $value) {
                    if (is_array($value)) {
                        foreach ($value as $mainkey) {
                            $newarray[$mainkey][] = $mainkey;
                        }
                    } else {
                        $newarray[$value][] = $value;
                    }
                }
            }
            return $newarray;
        }

        public static function add_hyperlink_for_unsub_link($unsub_url) {
            $new_unsub_url = "<a href= '" . $unsub_url . "'>" . $unsub_url . "</a>";
            return $new_unsub_url;
        }

        function cf_add_to_cart_redirect_to_checkout() {
            if (get_post_meta($_POST['add-to-cart'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                return get_permalink(get_option('woocommerce_checkout_page_id'));
            }
        }

        function cf_add_to_cart_redirect_to_cart() {
            if (isset($_POST['add-to-cart'])) {
                if (get_post_meta($_POST['add-to-cart'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                    return get_permalink(get_option('woocommerce_cart_page_id'));
                }
            }
        }

    }

    function get_session_name_and_values() {
        session_start();
        echo "<pre>";
        echo "</pre>";
    }

    function checkmetainperkfp() {
        echo "<pre>";
        echo "</pre>";
    }

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if (is_multisite()) {
        // This Condition is for Multi Site WooCommerce Installation
        if (!is_plugin_active_for_network('woocommerce/woocommerce.php') && (!is_plugin_active('woocommerce/woocommerce.php'))) {
            if (is_admin()) {
                $variable = "<div class='error'><p> Galaxy Funder will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>";
                echo $variable;
            }
            return;
        }
    } else {
        // This Condition is for Single Site WooCommerce Installation
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            if (is_admin()) {
                $variable = "<div class='error'><p> Galaxy Funder will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>";
                echo $variable;
            }
            return;
        }
    }





    new CrowdFunding();

    if (isset($_GET['page'])) {
        if (($_GET['page'] == 'crowdfunding_callback')) {

        }
    }

    /* functions for front end campaign submission */
    include_once 'inc/class_frontend_submission.php';
    include_once 'inc/class_email_settings.php';
    include 'inc/class_approved_campaign_email.php';
    include_once 'inc/class_rejected_campaign_email.php';
    include_once 'inc/class_deleted_campaign_email.php';
    include_once 'inc/class_completion_campaign_email.php';
    include_once 'inc/class_single_product_page.php';
    include_once 'inc/class_shop_page.php';
    include_once 'inc/class_error_message.php';
    include_once 'inc/class_mycampaign.php';
    include_once 'inc/class_contribution.php';
    include_once 'inc/class_perk_metabox.php';
    include_once 'inc/class_shortcode_generator.php';
    include_once 'inc/class_campaign_payment.php';
    include_once 'inc/class_perk_information_order.php';
    include_once 'inc/contribution_extension.php';

    function crowdfunding_send_order_email_to_creator($order_id) {
        $order = new WC_Order($order_id);
        global $product_id;

        foreach ($order->get_items() as $item) {
            $product_id = $item['product_id'];
            $checkvalue = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
            if ($checkvalue == 'yes') {
                if (get_option('cf_enable_mail_for_campaign_for_campaign_order') == 'yes') {
                    if (get_option('cf_send_email_to_campaign_creator_on_campaign_order') == 'yes') {
                        $author = get_post_field('post_author', $product_id);
                        $creatoremail = get_the_author_meta('user_email', $author);
                    }
                    if (get_option('cf_send_email_to_site_admin_on_campaign_order') == 'yes') {
                        $adminemail = get_option('admin_email');
                    }
                    $newarray = array($creatoremail, $adminemail);
                    if (get_option('cf_send_email_to_others_on_campaign_order') == 'yes') {
                        $text = trim(get_option('cf_send_email_to_others_mail_on_campaign_order'));
                        $textAr = explode("\n", $text);
                        $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                        foreach ($textAr as $line) {
                            $newarray[] = $line;
                        }
                    }
                    foreach ($newarray as $fieldarray) {
                        if (!is_null($fieldarray) || $fieldarray != '') {
                            global $woocommerce;
                            global $unsubscribe_link2;
                            if (get_user_meta($author, 'gf_email_unsub_value', true) != 'yes') {
                                $author = get_post_field('post_author', $product_id);
                                $tos = $fieldarray;
                                $subject = do_shortcode(get_option('contribution_mail_subject'));
                                $message = $message = do_shortcode(get_option('contribution_mail_message'));
                                $create_wpnonce = wp_create_nonce('gf_unsubscribe_' . $author);
                                $link_for_unsubscribe = esc_url_raw(add_query_arg(array('userid' => $author, 'unsub' => 'yes', 'nonce' => $create_wpnonce), site_url()));
                                ;
                                $unsubscribe_link1 = get_option('gf_unsubscribe_link_for_email');
                                $updated_link_unsubscribe = CrowdFunding::add_hyperlink_for_unsub_link($link_for_unsubscribe);
                                $unsubscribe_link1 = get_option('gf_unsubscribe_link_for_email');
                                $unsubscribe_link2 = str_replace('{gfsitelinkwithid}', $updated_link_unsubscribe, $unsubscribe_link1);
                                add_filter('woocommerce_email_footer_text', 'unsubscribe_footer_link');
                                ob_start();
                                wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                                echo $message;
                                wc_get_template('emails/email-footer.php');
                                $woo_temp_msg = ob_get_clean();
                                $mainnames = get_option('woocommerce_email_from_name');
                                $mainemails = get_option('woocommerce_email_from_address');
// To send HTML mail, the Content-type header must be set
                                $headerss = 'MIME-Version: 1.0' . "\r\n";
                                $headerss .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
                                $headerss .= 'From:' . $mainnames . '  <' . $mainemails . '>' . "\r\n";
// Mail it

                                if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                                    $mail = wp_mail($tos, $subject, $woo_temp_msg, $headerss);
                                } else {
                                    $mailer = WC()->mailer();
                                    $mailer->send($tos, $subject, $woo_temp_msg, '', '');
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    add_action('woocommerce_order_status_' . CrowdFunding::get_order_status_for_contribution(), 'crowdfunding_send_order_email_to_creator');

    function add_shortcode_campaign_order($order_id) {
        global $product_id;
        $order = new WC_Order($order_id);
        return get_the_title($product_id);
    }

    add_shortcode('cf_site_contributed_campaign_name', 'add_shortcode_campaign_order');

    function cf_add_screen_ids($array) {
        $wc_screen_id = sanitize_title(__('WooCommerce', 'woocommerce'));
        $newarray = $array;
        $checkids = $wc_screen_id . "_page_crowdfunding_callback";
        $newarray[] = $checkids;
        return $newarray;
    }

    add_filter('woocommerce_screen_ids', 'cf_add_screen_ids', 9, 1);

    function cf_hide_shop_page_crowdfunding($query) {
        if (!$query->is_main_query())
            return;
        if (!$query->is_post_type_archive())
            return;
        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'meta_value' => 'yes', 'meta_key' => '_crowdfundingcheckboxvalue');
        $products = get_posts($args);
        foreach ($products as $product) {
            $product->ID;
            $checkproduct = get_product($product->ID);
            if ($checkproduct->is_type('simple')) {
                $checkgalaxy = get_post_meta($product->ID, '_crowdfundingcheckboxvalue', true);
                if ($checkgalaxy == 'yes') {
                    $crowdproductid[] = $product->ID;
                }
            }
        }
        if (!is_admin() && is_shop()) {
            $query->set('post__not_in', $crowdproductid);
        }
    }

    if (get_option('cf_campaign_in_shop_page') == '2') {
        add_action('pre_get_posts', 'cf_hide_shop_page_crowdfunding');
    }

    function cf_add_session_for_contributor_name() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('.single_add_to_cart_button').click(function () {
                    var contributor_name = jQuery('.cf_contributor_name_field_value').val();
                    var dataparam = ({
                        action: 'getcontributorname',
                        contributor_name: contributor_name
                    });
    <?php
    if (get_option('cf_load_ajax_from_ssl') == '2') {
        $ajaxurl = site_url('/wp-admin/admin-ajax.php');
    } else {
        $ajaxurl = admin_url('admin-ajax.php');
    }
    ?>

                    jQuery.post("<?php echo $ajaxurl; ?>", dataparam,
                            function (response) {
                                //alert(response);
                                var newresponse = response.replace(/\s/g, '');
                                if (newresponse === 'success') {

                                }
                            });
                });
            });
        </script>
        <?php
    }

    function cf_get_response_session_ajax_request() {
        global $post;
        session_start();
        if ((isset($_POST['contributor_name']))) {
            $_SESSION['contributor_name'] = $_POST['contributor_name'];
            echo "success";
        }
        exit();
    }

    function cf_add_contributor_name_field_frontend() {
        global $post;
        if (get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
            if (get_option('cf_check_show_hide_contributor_name') == '1') {
                ?>
                <div class="contributor_name_field_value" style="display: inline-block;margin-bottom:30px;width:100%;">
                    <label for="cf_contributor_name_field_value">
                        <?php echo get_option('cf_contributor_name_caption'); ?>
                    </label>
                    <input type="text" name='cf_contributor_name_field_value' id="cf_contributor_name_field_value" class="cf_contributor_name_field_value" value=""/>
                    <small><?php _e('(First Name Last Name will be used if left empty)', 'galaxyfunder'); ?></small>
                </div>
                <?php
            }
        }
    }

    function cf_custom_get_email_address() {
        global $woocommerce;
        foreach ($woocommerce->cart->cart_contents as $item) {
            $emailid = get_post_meta($item['product_id'], 'cf_campaigner_paypal_id', true);
            $checkcrowdfunding = get_post_meta($item['product_id'], '_crowdfundingcheckboxvalue', true);
            if ($checkcrowdfunding == 'yes') {
                if (get_option('cf_enable_paypal_campaign_email_id') == 'yes') {
                    if (!empty($emailid)) {
                        return $emailid;
                    } else {
                        $paypalsettings = get_option('woocommerce_paypal_settings');
                        return $paypalsettings['email'];
                    }
                } else {
                    $paypalsettings = get_option('woocommerce_paypal_settings');
                    return $paypalsettings['email'];
                }
            } else {
                $paypalsettings = get_option('woocommerce_paypal_settings');
                return $paypalsettings['email'];
            }
        }
    }

    function cf_custom_override_paypal_email($paypal_args) {
        global $woocommerce;
        $paypal_args['business'] = cf_custom_get_email_address();
        return $paypal_args;
    }

    add_filter('woocommerce_paypal_args', 'cf_custom_override_paypal_email', 100, 1);

    function cf_crowdfunding_update_post_meta_order_delete($post_id) {
        $post_type = get_post_type($post_id);
        $post_status = get_post_status($post_id);

        if ("shop_order" == $post_type) {
            $order = new WC_Order($post_id);
            if (in_array($post_id, (array) get_option('updated_orderids'))) {
                $post_status = str_replace('wc-', '', $post_status);

                if (($post_status == CrowdFunding::get_order_status_for_contribution()) || ($post_status == 'refunded')) {

                    foreach ($order->get_items() as $item) {

                        $check_crowdfunding_enabled = get_post_meta($item['product_id'], '_crowdfundingcheckboxvalue', true);
                        if ($check_crowdfunding_enabled == 'yes') {
                            $check_order_ids = get_option('contribution_orderids');
                            if (in_array($order->ID, (array) $check_order_ids)) {
                                $old_totals = get_post_meta($item['product_id'], '_crowdfundingtotalprice', true);
                                $totals = $old_totals - $item['line_total'];


                                $getoverallgoalamount = get_post_meta($item['product_id'], '_crowdfundingtotalprice', true);
                                $currenttargetgoal = get_post_meta($item['product_id'], '_crowdfundinggettargetprice', true);

                                if ($getoverallgoalamount >= $currenttargetgoal) {
// get stock status
                                    $stockstatus = get_post_meta($item['product_id'], '_stock_status', true);

                                    if ($stockstatus == 'outofstock') {
                                        if ($getoverallgoalamount > $totals) {
                                            update_post_meta($item['product_id'], '_stock_status', 'instock');
                                        }
                                    }
                                }
                                update_post_meta($item['product_id'], '_crowdfundingtotalprice', $totals);
                                $gettargetgoalamount = get_post_meta($item['product_id'], '_crowdfundinggettargetprice', true);
                                $getordertotal = get_post_meta($item['product_id'], '_crowdfundingtotalprice', true);
                                if ($gettargetgoalamount != '') {
                                    if (($getordertotal != '') && ($gettargetgoalamount > 0)) {
                                        $count1 = $getordertotal / $gettargetgoalamount;
                                        $count2 = $count1 * 100;
                                        $counter = number_format($count2, 0);
                                        update_post_meta($item['product_id'], '_crowdfundinggoalpercent', $counter);
                                    }
                                }
                                $oldfunderscount = get_post_meta($item['product_id'], '_update_total_funders', true);
                                update_post_meta($item['product_id'], '_update_total_funders', $oldfunderscount - 1);
                            }
                        }
                    }
                    global $post;
                    global $woocommerce;
                    $products = array();
                    $order_totalss = array();
                    $productcounts = array();
                    $arraycounter = array();
//$i = 0;
                    if (function_exists('wc_get_order_statuses')) {
                        $getpoststatus = array('wc-completed', 'wc-refunded');
                    } else {
                        $getpoststatus = 'publish';
                    }
                    foreach (get_posts(array(
                        'post_type' => 'shop_order',
                        'posts_per_page' => '-1',
                        'post_status' => $getpoststatus,
                    ))as $order) {
                        $order = new WC_Order($order->ID);
                        $getmyorderstatus = get_post_status($order->ID);
                        $orderstatus = str_replace('wc-', '', $getmyorderstatus);
                        if (($orderstatus == CrowdFunding::get_order_status_for_contribution()) || ($orderstatus == 'refunded')) {
                            foreach ($order->get_items() as $item) {
                                $product_id = $item['product_id'];
                                $products[] = $product_id;
                                $productcounts[] = $item['product_id'];
                                $order_totalss[] = $item['line_total'];
                            }
                        }
                    }

                    $combined = array();
                    $productCount = count($productcounts);
                    if ($productCount > 0) {
                        for ($i = 0; $i < $productCount; $i++) {

                            if (!array_key_exists($productcounts[$i], $combined)) {
                                $combined[$productcounts[$i]] = $order_totalss[$i];
                            } else {
                                $combined[$productcounts[$i]] += $order_totalss[$i];
                            }
                        }if (is_array($productcounts)) {
                            $arraycounter = array_count_values($productcounts);
                            foreach ($productcounts as $newcount) {
                                if (get_post_meta($newcount, '_crowdfundingcheckboxvalue', true) == 'yes') {
                                    update_post_meta($newcount, '_crowdfundingtotalprice', $combined[$newcount]);
                                    $gettotalraisedamount = get_post_meta($newcount, '_crowdfundingtotalprice', true);
                                    $gettargetgoalamount = get_post_meta($newcount, '_crowdfundinggettargetprice', true);
                                    $getordertotal = get_post_meta($newcount, '_crowdfundingtotalprice', true);
                                    if ($gettargetgoalamount != '') {
                                        if (($getordertotal != '') && ($gettargetgoalamount > 0)) {
                                            $count1 = $getordertotal / $gettargetgoalamount;
                                            $count2 = $count1 * 100;
                                            $counter = number_format($count2, 0);
                                            update_post_meta($newcount, '_crowdfundinggoalpercent', $counter);
                                        }
                                    }
                                    update_post_meta($newcount, '_update_total_funders', $arraycounter[$newcount]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function delete_claim_count_for_perk($post) {
        if ("shop_order" == $post->post_type) {
            $order = new WC_Order($post->ID);
            $neworderid = array($post->ID);
            $i = 0;
            foreach ($order->get_items() as $item) {
                if (metadata_exists('post', $item['product_id'], 'orderids') == '1') {
                    $meta = get_post_meta($item['product_id'], "orderids", true);
                    $newordereddata = array_merge($meta, $neworderid);
                    update_post_meta($item['product_id'], "orderids", $newordereddata);
                } else {
                    update_post_meta($item['product_id'], "orderids", $neworderid);
                }
                $newone = get_post_meta($item['product_id'], 'orderids', true);
                foreach ($newone as $oneorderid) {
                    if (get_post_meta($oneorderid, 'perk_maincontainer' . $item['product_id'], true) != '') {
                        $getperkprice[] = get_post_meta($oneorderid, 'perk_maincontainer' . $item['product_id'], true);
                    }
                    if (get_post_meta($oneorderid, 'perk_list_amount' . $item['product_id'], true) != '') {
                        $listperkprice[] = get_post_meta($oneorderid, 'perk_list_amount' . $item['product_id'], true);
                    }
                }

                $getperkpricew = get_post_meta($post->ID, 'perk_list_amount' . $item['product_id'], true);
                foreach ($getperkpricew as $values) {
                    $newtest[] = $values;
                }

                $getperkquantity = get_post_meta($post->ID, 'explodequantity' . $item['product_id'], true);
                foreach ($getperkquantity as $perkquantity) {
                    $exp_quantity = explode('_', $perkquantity);
                    $listquantity[] = $exp_quantity[0];
                    $listofamount[] = $exp_quantity[1];
                }
                $newcount = array_unique($getperkprice);
                $j = 0;
                $perkrule = get_post_meta($item['product_id'], 'perk', true);
                foreach ($perkrule as $perk) {

                    $cfperkname = get_post_meta($post->ID, 'perkname' . $item['product_id'], true);

                    if (get_option('cf_perk_selection_type') == '1') {
                        $myquantity = get_post_meta($post->ID, 'explodequantity' . $item['product_id'], true);
                        $get_quantity = explode('_', $myquantity);
                        $listquantitys = $get_quantity[0];
                        $listofamounts = $get_quantity[1];
                        foreach ($getperkprice as $price) {
                            if ($perk['amount'] == (int) $price) {
                                $length = count(array_keys($getperkprice, (int) $price));
                                $claimcount = (int) $perk['claimcount'];
                                if ($perk['amount'] == (int) $listofamounts) {
                                    $countquantitylog = $perk['name'] . ' x ' . $listquantitys;
                                    update_post_meta($post->ID, 'perkquantity' . $item['product_id'], $countquantitylog);
                                }
                                $perkname = str_replace('', '_', $perk['name']);
                                $currentcount = (int) get_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', true);
                                if ($perk['limitperk'] == 'cf_limited') {
                                    if ($claimcount > $currentcount) {
                                        update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length - $currentcount);
                                    }
                                } else {
                                    if ($perk['limitperk'] == 'cf_unlimited') {
                                        update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length - $currentcount);
                                    }
                                }
                            }
                        }
                    } else {

                        if (get_option('cf_perk_quantity_selection') == '2') {
                            foreach ($newtest as $price) {
                                if ($perk['amount'] == (int) $price) {
                                    $length = count(array_keys($newtest, (int) $price));
                                    $claimcount = (int) $perk['claimcount'];
                                    $perkname = str_replace('', '_', $perk['name']);
                                    $currentcount = (int) get_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', true);

                                    if ($perk['limitperk'] == 'cf_limited') {
                                        if ($claimcount > $currentcount) {

                                            update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length - $currentcount);
                                        }
                                    } else {
                                        if ($perk['limitperk'] == 'cf_unlimited') {
                                            update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length - $currentcount);
                                        }
                                    }
                                }
                            }
                        } else {

                            foreach ($listofamount as $amount) {
                                if ($perk['amount'] == (int) $amount) {
                                    $length = count(array_keys($listofamount, (int) $amount));
                                    $claimcount = (int) $perk['claimcount'];
                                    $perkquantity = (int) $listquantity[$i];
                                    $quantitylog[] = $perk['name'] . ' x ' . $perkquantity;
                                    $perkname = str_replace('', '_', $perk['name']);
                                    $currentcount = (int) get_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', true);
                                    if ($perk['limitperk'] == 'cf_limited') {
                                        if ($claimcount > $currentcount) {
                                            update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length - $currentcount);
                                        }
                                    } else {
                                        if ($perk['limitperk'] == 'cf_unlimited') {
                                            update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length - $currentcount);
                                        }
                                    }
                                    $i++;
                                }
                            }
                            update_post_meta($post->ID, 'perkquantity' . $item['product_id'], $quantitylog);
                        }
                    }
                    $regularprice = get_post_meta($item['product_id'], '_regular_price', true);
                    $j++;
                }
            }
        }
    }

    add_action('wp_trash_post', 'cf_crowdfunding_update_post_meta_order_delete');
    add_action('woocommerce_order_status_refunded', 'cf_crowdfunding_update_post_meta_order_delete');

    function cf_restrict_add_to_cart_products($passed, $product_id, $quantity, $variation_id = '', $variations = '') {
        global $woocommerce;
        if (2 == 2) {
            $cart_count = $woocommerce->cart->cart_contents_count;
            $cart_object = $woocommerce->cart;
            foreach ($cart_object->cart_contents as $key => $value) {
                if (get_post_meta($value['product_id'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                    if ($cart_count > 0) {
                        wc_add_notice(__(get_option('cf_campaign_restrict_error_message')), 'error');
                    }
                    $passed = false;
                }
            }
        }
        return $passed;
    }

    function cf_crowdfunding_validation_input_field() {
        ?>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#_crowdfundinggetminimumprice[type=text],#_crowdfundinggetrecommendedprice[type=text],#_crowdfundinggetmaximumprice[type=text],#_crowdfundinggetrecommendedprice[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });
                    return this;
                });

                jQuery('body').on('keyup change', '#_crowdfundinggetminimumprice[type=text],#_crowdfundinggetrecommendedprice[type=text],#_crowdfundinggetmaximumprice[type=text],#_crowdfundinggetrecommendedprice[type=text]', function () {
                    var value = jQuery(this).val();
                    var regex = new RegExp("[^\-0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                    var newvalue = value.replace(regex, '');

                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                    }
                    return this;
                });

                jQuery(function () {
                    jQuery('#buttonstyles_galaxy').change(function () {
                        var selectvalue = jQuery(this).val();
                        if (selectvalue == "default_non_editable") {
                            if (jQuery("#_recomended_amount_galaxy").val() == "") {
                                jQuery("#_recomended_amount_galaxy").keydown(function () {
                                    if (jQuery(this).val() == "") {
                                        //if(!jQuery(this).focus()){
                                        //if(!jQuery(this).is(':focus')){
                                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                                            var offset = jQuery(this).position();
                                            jQuery(this).after('<div class="wc_error_tip">' + "Recomended Amount Cannot be Empty" + '</div>');
                                            jQuery('.wc_error_tip')
                                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                                    .css('top', offset.top + jQuery(this).height())
                                                    .fadeIn('100');
                                        }
                                    }

                                });
                                return this;
                            }
                        }
                    });
                });





                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });
                });
            });
        </script>
        <?php
    }

    add_action('admin_head', 'cf_crowdfunding_validation_input_field');

    add_action('admin_head', 'cf_crowdfunding_validation_input_field');

    /**
     * Add checkbox field to the checkout
     * */
    add_action('woocommerce_after_order_notes', 'my_custom_checkout_field');

    function my_custom_checkout_field($checkout) {
        foreach (WC()->session->get('cart') as $key => $value) {
            if (get_post_meta($value['product_id'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                if (get_option('cf_show_hide_mark_anonymous_checkbox') == '1') {
                    echo '<div id="my-new-field">';
                    woocommerce_form_field('my_checkbox', array(
                        'type' => 'checkbox',
                        'class' => array('input-checkbox'),
                        'label' => get_option('cf_checkout_textbox'),
                        'required' => false,
                            ), $checkout->get_value('my_checkbox'));
                    echo '</div>';
                }
            }
        }
    }

    function unsubscribe_footer_link() {
        global $unsubscribe_link2;

        return $unsubscribe_link2;
    }

    /*
     * Update the Unsubscribe user meta for User
     *
     */

    function update_gf_email_unsubscribe_meta() {
        if (isset($_GET['userid']) && isset($_REQUEST['nonce'])) {
            $user_id = $_GET['userid'];
            if (($_GET['userid']) && ($_GET['unsub'] == 'yes')) {
                update_user_meta($_GET['userid'], 'gf_email_unsub_value', 'yes');
                wp_safe_redirect(site_url());
                exit();
            }
        }
    }

    /* For Unsubscribe option in My account Page */

    function subscribe_option_in_my_account_page() {
        if (get_option('gf_show_hide_your_subscribe_link') == '1') {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#gf_email_subscribeoption').click(function () {
                        var subscribe = jQuery('#gf_email_subscribeoption').is(':checked') ? 'yes' : 'no';
                        var getcurrentuser =<?php echo get_current_user_id(); ?>;
                        var data = {
                            action: 'gf_subscribevalue',
                            subscribe: subscribe,
                            getcurrentuser: getcurrentuser,
                            //dataclicked:dataclicked,
                        };
        <?php
        if (get_option('cf_load_ajax_from_ssl') == '2') {
            $ajaxurl = site_url('/wp-admin/admin-ajax.php');
        } else {
            $ajaxurl = admin_url('admin-ajax.php');
        }
        ?>
                        jQuery.post("<?php echo $ajaxurl; ?>", data,
                                function (response) {
                                    //var newresponse = response.replace(/\s/g, '');
                                    if (response === '2') {
                                        alert("Successfully Unsubscribed...");
                                    } else {
                                        alert("Successfully Subscribed...");
                                    }
                                });

                    });
                });
            </script>
            <br><h3><input type="checkbox" name="gf_email_subscribeoption" id="gf_email_subscribeoption" value="yes" <?php checked("yes", get_user_meta(get_current_user_id(), 'gf_email_unsub_value', true)); ?>/>    <?php echo get_option('gf_unsubscribe_message_myaccount_page'); ?></h3>
            <?php
        }
    }

    /*
     * Update the subscribe option from checkbox
     *
     */


    /*
     * Update the manually subscribe option
     *
     */

    function gf_get_sub_value() {
        if ($_POST['getcurrentuser'] && $_POST['subscribe'] == 'no') {
            update_user_meta($_POST['getcurrentuser'], 'gf_email_unsub_value', 'no');
            echo "1";
        } else {
            update_user_meta($_POST['getcurrentuser'], 'gf_email_unsub_value', 'yes');
            echo "2";
        }

        exit();
    }

    function fbgf_total_orderids($productid) {

        $orderids = array_unique(array_filter((array) get_post_meta($productid, 'orderids', true)));
        return $orderids;
    }

    function fbgf_sum_of_totals($productid) {
        $total = '';
        $listofcontributedorderids = array_unique(array_filter((array) get_post_meta($productid, 'orderids', true)));
        if (is_array($listofcontributedorderids)) {
            foreach ($listofcontributedorderids as $order) {
                $order = new WC_Order($order);
                foreach ($order->get_items() as $item) {
                    if ($item['product_id'] == $productid) {
                        $total += $item['line_total'];
                    }
                }
            }
        }
        return $total;
    }

    add_action('wp_ajax_gf_subscribevalue', 'gf_get_sub_value');
    /*
     * Display the Subscribe option in My Account Page
     *
     */

    add_action('woocommerce_before_my_account', 'subscribe_option_in_my_account_page');

    /*
     * Call to update the Unsubscribe User Meta
     */

    add_action('wp_head', 'update_gf_email_unsubscribe_meta');

    /* Update the order meta with field value
     * */
    add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

    function my_custom_checkout_field_update_order_meta($order_id) {
        if (isset($_POST['my_checkbox']))
            update_post_meta($order_id, 'My Checkbox', esc_attr(isset($_POST['my_checkbox'])));
    }

    if (get_option('cf_campaign_restrict_other_products') == '1') {
        add_action('woocommerce_add_to_cart_validation', 'cf_restrict_add_to_cart_products', 10, 5);
    }
