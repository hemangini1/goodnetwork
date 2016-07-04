<?php

class CFEmailSettings {

    public static function crowdfunding_admin_email_tab($settings_tabs) {
        $settings_tabs['crowdfunding_emails'] = __('Mail', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_mailer_admin_options() {
        global $woocommerce;
        return apply_filters('woocommerce_crowdfunding_email_options', array(
            array(
                'name' => __('Email Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_mailer'
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_mailer'),
            array(
                'name' => __('Campaign Submission Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_submission_template',
            ),
            array(
                'name' => __('Send Email on Campaign Submission', 'galaxyfunder'),
                'desc' => __(''),
                'id' => 'cf_enable_mail_for_campaign_submission',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_submission',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator',
            ),
            array(
                'name' => __(''),
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin',
            ),
            array(
                'name' => __(''),
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others',
            ),
            array(
                'name' => __(''),
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Submission Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of your Campaign Submission Mail', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_submission_email_subject',
                'css' => 'min-width:550px',
                'std' => 'Campaign Submission for [cf_campaign_name] is submitted',
                'type' => 'text',
                'newids' => 'campaign_submission_email_subject',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Submission Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_submission_email_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi,<br>The Campaign [cf_campaign_name] on [cf_site_title] is Successfully Submitted. Please wait until admin has approved your campaign you will be notified either campaign is approved or rejected.<br> Thanks.',
                'type' => 'textarea',
                'newids' => 'campaign_submission_email_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_mail_settings'),
            array(
                'name' => __('Campaign Approval Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_approved_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Approved', 'galaxyfunder'),
                'desc' => __(''),
                'id' => 'cf_enable_mail_for_campaign_approved',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_approved',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator','galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_approved',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_approved',
            ),
            array(
                'name' => __(''),
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_approved',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_approved',
            ),
            array(
                'name' => __(''),
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_approved',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_approved',
            ),
            array(
                'name' => __(''),
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_approved',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_approved',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Approved Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Approved Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'approved_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Congragulation!!! Your Created Campaign [campaign_name] has been Approved',
                'type' => 'text',
                'newids' => 'approved_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Approved Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign Approved', 'galaxyfunder'),
                'tip' => '',
                'id' => 'approved_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi,<br> Congragulation!!! The Campaign [campaign_name] on [cf_site_title] is Approved.<br> [cf_site_campaign_url] <br> [cf_site_campaign_shipping_address] Thanks.',
                'type' => 'textarea',
                'newids' => 'approved_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_approved_mail_template'),
            array(
                'name' => __('Campaign Rejection Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_rejected_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Rejected', 'galaxyfunder'),
                'desc' => __(''),
                'id' => 'cf_enable_mail_for_campaign_rejected',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_rejected',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_rejected',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_rejected',
            ),
            array(
                'name' => __(''),
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_rejected',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_rejected',
            ),
            array(
                'name' => __(''),
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_rejected',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_rejected',
            ),
            array(
                'name' => __(''),
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_rejected',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_rejected',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Rejected Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Rejected Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'rejected_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Your Created Campaign [cf_site_campaign_name] has been rejected',
                'type' => 'text',
                'newids' => 'rejected_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Rejected Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign [cf_site_campaign_name] Rejection', 'galaxyfunder'),
                'tip' => '',
                'id' => 'rejected_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi, <br> We are Sorry this Campaign [cf_site_campaign_name] could not meet the standards and hence it is rejected',
                'type' => 'textarea',
                'newids' => 'rejected_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_rejected_mail_template'),
            array(
                'name' => __('Campaign Completion Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_completion_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Completion', 'galaxyfunder'),
                'desc' => __(''),
                'id' => 'cf_enable_mail_for_campaign_completed',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_completed',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_completed',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_completed',
            ),
            array(
                'name' => __(''),
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_completed',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_completed',
            ),
            array(
                'name' => __(''),
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_completed',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_completed',
            ),
            array(
                'name' => __(''),
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_completed',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_completed',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Completion Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Campaign Completion Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_completion_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Congragulations!!! Your Created Campaign [campaign_name] has reached the Goal',
                'type' => 'text',
                'newids' => 'campaign_completion_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Completion Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign [cf_site_campaign_completion] Completion', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_completion_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi, <br> Congragulations!!! Your Created Campaign [cf_site_campaign_completion] has reached the goal :) <br> [cf_site_campaign_url] <br> [cf_site_campaign_shipping_address]',
                'type' => 'textarea',
                'newids' => 'campaign_completion_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_completion_mail_template'),
            array(
                'name' => __('Campaign Deletion Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_deletion_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Deleted', 'galaxyfunder'),
                'desc' => __(''),
                'id' => 'cf_enable_mail_for_campaign_deleted',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_deleted',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_deleted',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_deleted',
            ),
            array(
                'name' => __(''),
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_deleted',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_deleted',
            ),
            array(
                'name' => __(''),
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_deleted',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_deleted',
            ),
            array(
                'name' => __(''),
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_deleted',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_deleted',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Deletion Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Campaign Deletion Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'deleted_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'We are Sorry Unfortunately your Created Campaign [campaign_name] was Deleted or Removed',
                'type' => 'text',
                'newids' => 'deleted_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Deletion Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign [cf_site_campaign_name] Deletion', 'galaxyfunder'),
                'tip' => '',
                'id' => 'deleted_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi there, <br> We are Sorry Unfortunately your Approved Campaign was Deleted or Removed <br> Contact Support for More Info',
                'type' => 'textarea',
                'newids' => 'deleted_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_deletion_mail_template'),
            array(
                'name' => __('Campaign Contribution Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_contribution_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Order', 'galaxyfunder'),
                'desc' => __(''),
                'id' => 'cf_enable_mail_for_campaign_for_campaign_order',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_for_campaign_order',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_campaign_order',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_campaign_order',
            ),
            array(
                'name' => __(''),
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_campaign_order',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_campaign_order',
            ),
            array(
                'name' => __(''),
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_campaign_order',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_campaign_order',
            ),
            array(
                'name' => __(''),
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_campaign_order',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_campaign_order',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contribution Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Campaign Deletion Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Hi, Your Campaign [cf_site_contributed_campaign_name] has raised the fund',
                'type' => 'text',
                'newids' => 'contribution_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contribution Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign Deletion', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi there, <br> Your Created Campaign  [cf_site_contributed_campaign_name]  has raised the Fund',
                'type' => 'textarea',
                'newids' => 'contribution_mail_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Unsubscribe Link Message for Email', 'galaxyfunder'),
                'desc' => __('This message will be displayed a the Unsubscribe message in Galaxy Funder Emails', 'galaxyfunder'),
                'id' => 'gf_unsubscribe_link_for_email',
                'css' => 'min-width:550px;',
                'std' => 'If you want to unsubscribe from your mail,click here...{gfsitelinkwithid}',
                'type' => 'textarea',
                'newids' => 'gf_unsubscribe_link_for_email',
                'class' => 'gf_unsubscribe_link_for_email',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_contribution_mail_template'),
            array(
                'name' => __('Perk Information Order Reciept Customization', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_contribution_mail_reciept',
            ),
            array(
                'name' => __('Perk Name Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Name ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_perk_label',
                'css' => 'min-width:550px',
                'std' => 'Perk Name',
                'type' => 'text',
                'newids' => 'contribution_mail_perk_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Associated Contribution Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Associated Contribution ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_perk_associated_contribution',
                'css' => 'min-width:550px',
                'std' => 'Perk Associated Contribution',
                'type' => 'text',
                'newids' => 'contribution_mail_perk_associated_contribution',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Products Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Products ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_Perk_Products',
                'css' => 'min-width:550px',
                'std' => 'Perk Products',
                'type' => 'text',
                'newids' => 'contribution_mail_Perk_Products',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Quantity Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Quantity ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_perk_quantity',
                'css' => 'min-width:550px',
                'std' => 'Perk Quantity',
                'type' => 'text',
                'newids' => 'contribution_mail_perk_quantity',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message To Show When There Is No Perk Product In Order', 'galaxyfunder'),
                'desc' => __('Please enter Label To Show When There Is No Perk Product In Order', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_Perk_perk_empty',
                'css' => 'min-width:550px',
                'std' => 'No Perk Associated In This Order',
                'type' => 'text',
                'newids' => 'contribution_mail_Perk_perk_empty',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_contribution_mail_reciept'),
        ));
    }

    public static function crowdfunding_process_admin_settings() {
        woocommerce_admin_fields(CFEmailSettings::crowdfunding_mailer_admin_options());
    }

    public static function crowdfunding_process_update_settings() {
        woocommerce_update_options(CFEmailSettings::crowdfunding_mailer_admin_options());
    }

    public static function crowdfunding_mail_default_settings() {
        global $woocommerce;
        foreach (CFEmailSettings::crowdfunding_mailer_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_email_reset_values() {
        global $woocommerce;
        // var_dump("google google");
        if (isset($_POST['reset'])) {
            foreach (CFEmailSettings::crowdfunding_mailer_admin_options() as $setting)
                if (isset($setting['newids']) && ($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
        }
    }

    public static function cf_check_loop() {
        $text = trim(get_option('cf_send_email_to_others_mail'));
        $textAr = explode("\n", $text);
        $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
        if (get_option('cf_send_email_to_campaign_creator') == 'yes') {
            $author = get_post_field('post_author', $campaign_id);
            $creatoremail = "pts.rajesh@gmail.com";
        }
        if (get_option('cf_send_email_to_site_admin') == 'no') {
            $adminemail = get_option('admin_email');
        }
        if (get_option('cf_send_email_to_others') == 'yes') {

        }
        $newarray = array($creatoremail, $adminemail);

        foreach ($textAr as $line) {
            $newarray[] = $line;
        }
        foreach ($newarray as $fieldarray => $key) {
            if (is_null($fieldarray) || $fieldarray == '') {
                unset($newarray[$key]);
            } else {

            }
        }
//        var_dump($newarray);
    }

    public static function add_shortcode_site_name() {
        return get_option('blogname');
    }

    public static function add_shortcode_campaign_name() {
        return $_POST['crowdfunding_title'];
    }

    public static function add_shortcode_main_campaign_name() {
        global $post;
        return get_the_title($post);
    }

    public static function add_shortcode_campaign_name_for_rej_del() {
        global $splitids;
        if ($_GET['ids']) {
            $splitids = explode(',', $_GET['ids']);
            $count = count($splitids);
            for ($i = 0; $i < $count; $i++) {
                $oldstatus = get_post_meta($splitids[$i], '_cf_old_status', true);
                $newstatus = get_post_meta($splitids[$i], '_cf_new_status', true);
                if ((($oldstatus == 'draft') && ($newstatus == 'trash'))) {
                    return get_the_title($splitids[$i]);
                }
                if ((($oldstatus == 'publish') && ($newstatus == 'trash'))) {
                    return get_the_title($splitids[$i]);
                }
            }
        }
    }

    public static function add_shortcode_campaign_name_for_completion() {
        global $products;
        return get_the_title($products->ID);
    }

}

add_shortcode('cf_site_campaign_completion', array('CFEmailSettings', 'add_shortcode_campaign_name_for_completion'));
add_shortcode('cf_site_campaign_name', array('CFEmailSettings', 'add_shortcode_campaign_name_for_rej_del'));
add_shortcode('cf_site_title', array('CFEmailSettings', 'add_shortcode_site_name'));
add_shortcode('cf_campaign_name', array('CFEmailSettings', 'add_shortcode_campaign_name'));
add_shortcode('campaign_name', array('CFEmailSettings', 'add_shortcode_main_campaign_name'));
add_action('woocommerce_update_options_crowdfunding_emails', array('CFEmailSettings', 'crowdfunding_process_update_settings'));
add_action('init', array('CFEmailSettings', 'crowdfunding_mail_default_settings'));
add_action('woocommerce_cf_settings_tabs_crowdfunding_emails', array('CFEmailSettings', 'crowdfunding_process_admin_settings'));
add_filter('woocommerce_cf_settings_tabs_array', array('CFEmailSettings', 'crowdfunding_admin_email_tab'), 104);
add_action('admin_init', array('CFEmailSettings', 'cf_email_reset_values'), 2);
//add_action('admin_head', array('CFEmailSettings', 'cf_check_loop'));
new CFEmailSettings();
