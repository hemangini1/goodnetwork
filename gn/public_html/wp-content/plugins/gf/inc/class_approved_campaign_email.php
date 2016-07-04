<?php

class CampaignApprovedEmail {

    public static function campaign_approved_email() {
        global $post_id;
        global $woocommerce;
        $adminemail='';
        $creatoremail='';
        $author='';
        $posttype = get_post_type($post_id);
        if ($posttype == 'product') {


            $oldstatusmessage = get_post_status($post_id);
            if ($oldstatusmessage == 'draft') {
                $oldstatus = update_post_meta($post_id, '_cf_old_status', $oldstatusmessage);
            }
            if ($oldstatusmessage == 'publish') {
                $newstatus = update_post_meta($post_id, '_cf_new_status', $oldstatusmessage);
            }
        }
    }

    public static function campaign_status_email_check() {
        global $post_id;
        global $woocommerce;
        $oldstatus = get_post_meta($post_id, '_cf_old_status', true);
        $newstatus = get_post_meta($post_id, '_cf_new_status', true);
        $checkvalue = get_post_meta($post_id, '_crowdfundingcheckboxvalue', true);
        if ($checkvalue == 'yes') {
            if (($oldstatus == 'draft') && ($newstatus == 'publish')) {
                // echo "Status Changed from Draft to Published";
                $getdate = date("m/d/Y");
                $hour=date("H");
                $minutes=date("i");
                $frompublishdate = update_post_meta($post_id, '_crowdfundingfromdatepicker', $getdate);
                update_post_meta($post_id, '_crowdfundingfromhourdatepicker', $hour);
                update_post_meta($post_id, '_crowdfundingfromminutesdatepicker', $minutes);
                update_post_meta($post_id, '_crowdfundingtohourdatepicker', $hour);
                update_post_meta($post_id, '_crowdfundingtominutesdatepicker', $minutes);
                $campaign_duration = get_post_meta($post_id, '_crowdfundingcampaignduration', true);
                $todatenew = date('m/d/Y', strtotime($frompublishdate . ' + ' . $campaign_duration . ' days'));
                update_post_meta($post_id, '_crowdfundingtodatepicker', $todatenew);
                if (get_option('cf_enable_mail_for_campaign_approved') == 'yes') {
                    if (get_option('cf_send_email_to_campaign_creator_on_approved') == 'yes') {
                        $author = get_post_field('post_author', $post_id);
                        $creatoremail = get_the_author_meta('user_email', $author);
                    }
                    if (get_option('cf_send_email_to_site_admin_on_approved') == 'yes') {
                        $adminemail = get_option('admin_email');
                    }
                    $newarray = array($creatoremail, $adminemail);
                    if (get_option('cf_send_email_to_others_on_approved') == 'yes') {
                        $text = trim(get_option('cf_send_email_to_others_mail_on_approved'));
                        $textAr = explode("\n", $text);
                        $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                        foreach ($textAr as $line) {
                            $newarray[] = $line;
                        }
                    }
                    foreach ($newarray as $fieldarray) {
                        if (!is_null($fieldarray) || $fieldarray != '') {
//                        $msg = "First line of text\nSecond line of text";
//                        $msg = wordwrap($msg, 70);
//                        mail($fieldarray, "My subject", $msg);
                            include'approved_campaign_email.php';
                        }
                    }
                }

                update_post_meta($post_id, '_cf_old_status', 'publish');
            }
        }
    }

}

add_action('admin_head', array('CampaignApprovedEmail', 'campaign_approved_email'));
add_action('admin_head', array('CampaignApprovedEmail', 'campaign_status_email_check'));
new CampaignApprovedEmail();
