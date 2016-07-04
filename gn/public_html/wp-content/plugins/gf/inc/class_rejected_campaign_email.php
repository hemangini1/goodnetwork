<?php

class CampaignRejectedEmail {

    public static function campaign_rejected_email() {
        global $post;
        global $woocommerce;
        $author='';
        $adminemail='';
        $creatoremail='';
         $args = array(
            'post_type' => 'product',
            'numberposts' => '-1',
            'post_status' => 'trash,draft,publish',
            'meta_value' => 'yes',
            'meta_key' => '_crowdfundingcheckboxvalue'
        );
        foreach (get_posts($args) as $products) {
            $posttype = get_post_type($products->ID);
            if ($posttype == 'product') {
                $products->ID;
                $oldstatusmessage = get_post_status($products->ID);
                if ($oldstatusmessage == 'draft') {
                    $oldstatus = update_post_meta($products->ID, '_cf_old_status', $oldstatusmessage);
                }
                if ($oldstatusmessage == 'trash') {
                    $newstatus = update_post_meta($products->ID, '_cf_new_status', $oldstatusmessage);
                }
            }
        }
    }

    public static function campaign_rejection_status_email_check() {
        global $post;
        global $woocommerce;
        if (isset($_GET['ids'])) {
            if ($_GET['ids']) {
                $splitids = explode(',', $_GET['ids']);
                $count = count($splitids);
                for ($i = 0; $i < $count; $i++) {
                    $checkvalue = get_post_meta($splitids[$i], '_crowdfundingcheckboxvalue', true);
                    if ($checkvalue == 'yes') {
                        $oldstatus = get_post_meta($splitids[$i], '_cf_old_status', true);
                        $newstatus = get_post_meta($splitids[$i], '_cf_new_status', true);
                        if ((($oldstatus == 'draft') && ($newstatus == 'trash'))) {
                            if (get_option('cf_enable_mail_for_campaign_rejected') == 'yes') {
                                if (get_option('cf_send_email_to_campaign_creator_on_rejected') == 'yes') {
                                    $author = get_post_field('post_author', $splitids[$i]);
                                    //do_shortcode('[cf_site_campaign_name]');
                                    //do_shortcode(get_option('rejected_mail_message'));
                                    $creatoremail = get_the_author_meta('user_email', $author);
                                }
                                if (get_option('cf_send_email_to_site_admin_on_rejected') == 'yes') {
                                    $adminemail = get_option('admin_email');
                                }
                                $newarray = array($creatoremail, $adminemail);
                                if (get_option('cf_send_email_to_others_on_rejected') == 'yes') {
                                    $text = trim(get_option('cf_send_email_to_others_mail_on_rejected'));
                                    $textAr = explode("\n", $text);
                                    $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                                    foreach ($textAr as $line) {
                                        $newarray[] = $line;
                                    }
                                }
                                foreach ($newarray as $fieldarray) {
                                    if (!is_null($fieldarray) || $fieldarray != '') {
                                        include'rejected_campaign_email.php';
                                    }
                                }
                            }

                            $newstatus = update_post_meta($splitids[$i], '_cf_old_status', 'trash');
                        }
                    }
                }
            }
        }
    }

}

add_action('admin_head', array('CampaignRejectedEmail', 'campaign_rejected_email'));
add_action('admin_head', array('CampaignRejectedEmail', 'campaign_rejection_status_email_check'));
new CampaignRejectedEmail();
