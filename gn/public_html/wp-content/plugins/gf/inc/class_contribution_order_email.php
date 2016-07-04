<?php

class CampaignContributionEmail {

    public static function campaign_contribution_email() {
        global $post;
        global $woocommerce;
         $args = array(
            'post_type' => 'product',
            'numberposts' => '-1',
            'post_status' => 'publish',
            'meta_value' => 'yes',
            'meta_key' => '_crowdfundingcheckboxvalue'
        );
//          echo '<pre>'; var_dump(get_posts($args));echo '</pre>';
        foreach (get_posts($args) as $products) {
            //echo "Google";
            $checkvalue = get_post_meta($products->ID, '_crowdfundingcheckboxvalue', true);
            if ($checkvalue == 'yes') {
                $getfundertotal = get_post_meta($products->ID, '_update_total_funders', true);
                add_post_meta($products->ID, '_update_new_total_funders', $getfundertotal);
                //delete_post_meta($products->ID, '_update_new_total_funders');
                $newfundertotal = get_post_meta($products->ID, '_update_new_total_funders', true);
                $checknewpost = add_post_meta($products->ID, '_newfundtotal', 'false');
                //   echo "Get it into the condition" . "<br>";
                echo $getfundertotal . "<br>";
                if ($getfundertotal > $newfundertotal) {
                    echo "Fund has Been Raised";

                    if (get_option('cf_enable_mail_for_campaign_for_campaign_order') == 'yes') {
//                        var_dump('mail_sent1');
                        if (get_option('cf_send_email_to_campaign_creator_on_campaign_order') == 'yes') {
                            $author = get_post_field('post_author', $products->ID);
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
                                include 'crowdfunding_order_emails.php';
                            }
                        }
                    }
                    update_post_meta($products->ID, '_update_new_total_funders', $getfundertotal);
                }
                if ($checknewpost == 'false') {
                    if ($getfundertotal == '1') {
                        if (get_option('cf_enable_mail_for_campaign_for_campaign_order') == 'yes') {
//                            var_dump('mail_sent2');
                            if (get_option('cf_send_email_to_campaign_creator_on_campaign_order') == 'yes') {
                                $author = get_post_field('post_author', $products->ID);
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
                                    include 'crowdfunding_order_emails.php';
                                }
                            }
                        }
                        delete_post_meta($products->ID, '_newfundtotal');
                        add_post_meta($products->ID, '_newfundtotal', 'true');
                    }
                }
            }
        }
    }

}

//add_action('woocommerce_order_status_completed', array('CampaignContributionEmail', 'newwoocommerce'), 10, 1);
add_action('admin_head', array('CampaignContributionEmail', 'campaign_contribution_email'));
add_action('wp_head', array('CampaignContributionEmail', 'campaign_contribution_email'));
new CampaignContributionEmail();

//add_action('woocommerce_order_status_completed', 'my_function');
/*
 * Do something after WooCommerce sets an order on completed
 */
function my_function($order_id) {
// order object (optional but handy)
    global $order_id;
//    var_dump($order_id);
//    $order = new WC_Order($order_id);
//    var_dump($order);
// do some stuff here
}
