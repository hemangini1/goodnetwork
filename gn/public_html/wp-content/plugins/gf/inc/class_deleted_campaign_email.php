<?php

class CampaignDeletedEmail {

    public static function campaign_deleted_email() {
        global $post;
        global $woocommerce;
        $args = array(
            'post_type' => 'product',
            'numberposts' => '-1',
            'post_status' => 'trash,draft,publish',
            'meta_value' => 'yes',
            'meta_key' => '_crowdfundingcheckboxvalue'
        );
//         echo '<pre>'; var_dump(get_posts($args));echo '</pre>';
        foreach (get_posts($args) as $products) {
            $posttype = get_post_type($products->ID);
            if ($posttype == 'product') {
                $products->ID;
                $oldstatusmessage = get_post_status($products->ID);
                if ($oldstatusmessage == 'publish') {
                    $oldstatus = update_post_meta($products->ID, '_cf_old_status', $oldstatusmessage);
                }
                if ($oldstatusmessage == 'trash') {
                    $newstatus = update_post_meta($products->ID, '_cf_new_status', $oldstatusmessage);
                }
            }
        }
    }

    public static function campaign_deleted_status_email_check() {
        global $post;
        global $woocommerce;
        $adminemail='';
        $creatoremail='';
        $author='';
        if (isset($_GET['ids'])) {
            if ($_GET['ids']) {
                $splitids = explode(',', $_GET['ids']);
                $count = count($splitids);
                for ($i = 0; $i < $count; $i++) {
                    $checkvalue = get_post_meta($splitids[$i], '_crowdfundingcheckboxvalue', true);
                    if ($checkvalue == 'yes') {
                        $checkstatus = get_post_meta($splitids[$i], '_stock_status', true);
                        if ($checkstatus == 'instock') {
                            $oldstatus = get_post_meta($splitids[$i], '_cf_old_status', true);
                            $newstatus = get_post_meta($splitids[$i], '_cf_new_status', true);
                            //echo $splitids[$i] . "<br>";
                            if ((($oldstatus == 'publish') && ($newstatus == 'trash'))) {
                                if (get_option('cf_enable_mail_for_campaign_deleted') == 'yes') {
                                    if (get_option('cf_send_email_to_campaign_creator_on_deleted') == 'yes') {
                                        $author = get_post_field('post_author', $splitids[$i]);
                                        $creatoremail = get_the_author_meta('user_email', $author);
                                    }
                                    if (get_option('cf_send_email_to_site_admin_on_deleted') == 'yes') {
                                        $adminemail = get_option('admin_email');
                                    }
                                    $newarray = array($creatoremail, $adminemail);
                                    if (get_option('cf_send_email_to_others_on_deleted') == 'yes') {
                                        $text = trim(get_option('cf_send_email_to_others_mail_on_deleted'));
                                        $textAr = explode("\n", $text);
                                        $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                                        foreach ($textAr as $line) {
                                            $newarray[] = $line;
                                        }
                                    }
                                    foreach ($newarray as $fieldarray) {
                                        if (!is_null($fieldarray) || $fieldarray != '') {
                                            include'deleted_campaign_email.php';
                                        }
                                    }
                                }

                                // echo "Unfortunately Your Campaign Has Been Deleted";
                                $newstatus = update_post_meta($splitids[$i], '_cf_old_status', 'trash');
                            }
                        }
                    }
                }
            }
        }
    }

}

add_action('admin_head', array('CampaignDeletedEmail', 'campaign_deleted_email'));
add_action('admin_head', array('CampaignDeletedEmail', 'campaign_deleted_status_email_check'));
new CampaignDeletedEmail();
