<?php

class CFFrontendSubmission {

    public static function cf_admin_front_end() {
        global $woocommerce;
        $categories = get_terms('product_cat');
        $category_id = array();
        $category_name = array();
        $selectedcategories = array();
        if (!is_wp_error($categories)) {
            if (!empty($categories)) {
                if ($categories != NULL) {
                    foreach ($categories as $value) {
                        $category_id[] = $value->term_id;
                        $category_name[] = $value->name;
                    }
                }
                $selectedcategories = array_combine((array) $category_id, (array) $category_name);
            }
        }
        return apply_filters('woocommerce_frontend_settings', array(
            array(
                'name' => __("Use the Shortcode [crowd_fund_form] to display the Front End Submission Form"),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_new_default'
            ),
            array('type' => 'sectionend', 'id' => '_cf_new_default'),
            array(
                'name' => __('FrontEnd Campaign Submission Form Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_submission'
            ),
            array(
                'name' => __('Front End Submission Method', 'galaxyfunder'),
                'desc' => __('This Controls the Campaign should go for Moderation or Live', 'galaxyfunder'),
                'id' => 'cf_frontend_submission_method',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_frontend_submission_method',
                'type' => 'select',
                'options' => array(
                    '1' => __('Goes for Moderation', 'galaxyfunder'),
                    '2' => __('Goes Live', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('URL to Redirect for Guest', 'galaxyfunder'),
                'desc' => __('Please Enter URL to Redirect if a guest tries this page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_guest_url',
                'std' => wp_login_url(),
                'type' => 'text',
                'newids' => 'cf_submission_camp_guest_url',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable URL Redirection after Campaign is Submitted', 'galaxyfunder'),
                'desc' => __('Please Select the Option to Enable/Disable URL Redirection after Campaign is Submitted', 'galaxyfunder'),
                'id' => 'cf_campiagn_success_redirection_option',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_campiagn_success_redirection_option',
                'type' => 'select',
                'options' => array(
                    '1' => __('Disable', 'galaxyfunder'),
                    '2' => __('Enable', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('URL to Redirect after Campaign is submitted', 'galaxyfunder'),
                'desc' => __('Please Enter URL to Redirect after the campaign is submitted', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_campiagn_success_redirection_url_content',
                'std' => '',
                'type' => 'text',
                'newids' => 'cf_campiagn_success_redirection_url_content',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Display CrowdFunding Type', 'galaxyfunder'),
                'desc' => __('Please Select the Option to Show or Hide CrowdFunding Type in a Backend', 'galaxyfunder'),
                'id' => 'cf_show_hide_crowdfunding_type',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_crowdfunding_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Crowdfunding Type', 'galaxyfunder'),
                'desc' => __('Please Select a type of crowdfunding', 'galaxyfunder'),
                'id' => 'cf_crowdfunding_type_selection',
                'css' => 'min-width:150px;',
                'std' => '',
                'default' => '',
                'newids' => 'cf_crowdfunding_type_selection',
                'type' => 'select',
                'options' => array(
                    '1' => __('Fundraising by CrowdFunding', 'galaxyfunder'),
                    '2' => __('Product Purchase by CrowdFunding', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Products for crowdfunding Campaign', 'galaxyfunder'),
                'desc' => __('Please Select whether to display All Products or selected products in frontend Form', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_frontend_product_selection_type',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('All Products', 'galaxyfunder'), '2' => __('Selected Products', 'galaxyfunder')),
                'newids' => 'cf_frontend_product_selection_type',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Products for frontend Submission', 'galaxyfunder'),
                'desc' => __('Please select the products that you wish to be displayed for product purchase in the frontend form', 'galaxyfunder'),
                'id' => 'cf_frontend_selected_products',
                'css' => 'min-width:150px;',
                'newids' => 'cf_frontend_selected_products',
                'type' => 'selectedproducts_campaign',
            ),
            array(
                'name' => __('Categories for crowdfunding Campaign', 'galaxyfunder'),
                'desc' => __('Please Select whether to display All Categories or selected categories in frontend Form', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_frontend_categories_selection_type',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('All Categories', 'galaxyfunder'), '2' => __('Selected Categories', 'galaxyfunder')),
                'newids' => 'cf_frontend_categories_selection_type',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Categories For Front End Submission', 'galaxyfunder'),
                'desc' => __('Please Select the categories that you wish your campaign must be displayed', 'galaxyfunder'),
                'id' => 'cf_frontend_selected_categories',
                'css' => 'min-width:250px;',
                'newids' => 'cf_frontend_selected_categories',
                'type' => 'multiselect',
                'std' => '',
                'options' => $selectedcategories,
            ),
            array(
                'name' => __('Campaign Title Label', 'galaxyfunder'),
                'desc' => __('Please Enter Campaign Title Label for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_submission_camp_title_label',
                'css' => 'min-width:550px;',
                'std' => 'Campaign Title',
                'type' => 'text',
                'newids' => 'cf_submission_camp_title_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Title input Paceholder', 'galaxyfunder'),
                'desc' => __('Please Enter Campaign Title input Paceholder for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_submission_camp_title_placeholder',
                'css' => 'min-width:550px;',
                'std' => 'Enter the Campaign Title',
                'type' => 'text',
                'newids' => 'cf_submission_camp_title_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Display Campaign End Method Field in Frontend Submission Form', 'galaxyfunder'),
                'desc' => __('Please Select the Option to Show or Hide Campaign End Method in Submission form ', 'galaxyfunder'),
                'id' => 'cf_show_hide_campaign_end_selection_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_campaign_end_selection_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Campaign Duration Label', 'galaxyfunder'),
                'desc' => __('Please Enter Campaign Title Label for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_submission_camp_duration_label',
                'css' => 'min-width:550px;',
                'std' => 'Campaign Duration in Days',
                'type' => 'text',
                'newids' => 'cf_submission_camp_duration_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Duration input Placeholder', 'galaxyfunder'),
                'desc' => __('Please Enter Campaign Title Label for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_submission_camp_duration_placeholder',
                'css' => 'min-width:550px;',
                'std' => 'Enter Campaign Duration in Number of Days',
                'type' => 'text',
                'newids' => 'cf_submission_camp_duration_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Recommended Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Recommended Contribution Label for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_recommendedprice_label',
                'std' => 'Recommended Contribution',
                'type' => 'text',
                'newids' => 'cf_submission_camp_recommendedprice_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Recommended Contribution input Placeholder', 'galaxyfunder'),
                'desc' => __('Please Enter Recommended Contribution input Placeholder for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_recommendedprice_placeholder',
                'std' => 'Enter Recommended Contribution to show at campaign',
                'type' => 'text',
                'newids' => 'cf_submission_camp_recommendedprice_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Hide Recommeded Contribution', 'galaxyfunder'),
                'desc' => __('You can Show or Hide Recommended Contribution', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_recommendedprice_showhide',
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_submission_camp_recommendedprice_showhide',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution Label for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_maximumprice_label',
                'std' => 'Maximum Contribution',
                'type' => 'text',
                'newids' => 'cf_submission_camp_maximumprice_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Contribution input Placeholder', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution input Placehoder for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_maximumprice_placeholder',
                'std' => 'Enter Maximum Contribution to show at campaign page',
                'type' => 'text',
                'newids' => 'cf_submission_camp_maximumprice_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Hide Maximum Contribution', 'galaxyfunder'),
                'desc' => __('You can Show or Hide Maximum Contribution', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_maximumprice_showhide',
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_submission_camp_maximumprice_showhide',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution Label for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_minimumprice_label',
                'std' => 'Minimum Contribution',
                'type' => 'text',
                'newids' => 'cf_submission_camp_minimumprice_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Contribution input Placeholder', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution input Placeholder for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_minimumprice_placeholder',
                'std' => 'Enter Minimum Contribution to show at campaign',
                'type' => 'text',
                'newids' => 'cf_submission_camp_minimumprice_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Hide Minimum Contribution', 'galaxyfunder'),
                'desc' => __('You can Show or Hide Minimum Contribution', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_minimumprice_showhide',
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_submission_camp_minimumprice_showhide',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Display Target Goal When Product Purchase by Crowdfunding is chosen', 'galaxyfunder'),
                'desc' => __('Please Select the Option to Show or Hide Target Goal in Submission form when Product Purchase by Crowdfunding is chosen', 'galaxyfunder'),
                'id' => 'cf_show_hide_target_product_purchase_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_target_product_purchase_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Target Goal Label', 'galaxyfunder'),
                'desc' => __('Please Enter Target Goal Label for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_targetprice_label',
                'std' => 'Goal',
                'type' => 'text',
                'newids' => 'cf_submission_camp_targetprice_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Target Goal input Placeholder', 'galaxyfunder'),
                'desc' => __('Please Enter Target Goal input Placeholder for campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_targetprice_placeholder',
                'std' => 'Enter Target Goal to show at campaign',
                'type' => 'text',
                'newids' => 'cf_submission_camp_targetprice_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Description Label', 'galaxyfunder'),
                'desc' => __('Please Enter Description Label at campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_description_label',
                'std' => 'Description',
                'type' => 'text',
                'newids' => 'cf_submission_camp_description_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Display Add Perk Rule Button in Frontend Submission form', 'galaxyfunder'),
                'desc' => __('Please Select Whether to show or hide Add Perk Rule Button in frontend Submission Form', 'galaxyfunder'),
                'id' => 'cf_show_hide_add_perk_button_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_add_perk_button_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Enable Confirmation Message for Removing Perk Rule', 'galaxyfunder'),
                'desc' => __('By Enabling this Option ask you to Confirm Before Removing the Perk', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_enable_remove_perk_rule',
                'std' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_remove_perk_rule',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Remove Perk Confirmation Message', 'galaxyfunder'),
                'desc' => __('Please Enter Remove Perk Rule Confirmation Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_custom_remove_perk_confirmation_message',
                'std' => 'Are you sure want to do this ?',
                'type' => 'textarea',
                'newids' => 'cf_custom_remove_perk_confirmation_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Add Perk Rule Button Label', 'galaxyfunder'),
                'desc' => __('Please Enter Add Perk Rule Button Caption for Front End Campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_add_perk_rule_caption',
                'std' => 'Add Perk Rule',
                'type' => 'text',
                'newids' => 'cf_add_perk_rule_caption',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Remove Perk Rule Button Label', 'galaxyfunder'),
                'desc' => __('Please Enter Remove Perk Rule Button Caption for Front End Campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px',
                'id' => 'cf_remove_perk_rule_caption',
                'std' => 'Remove Perk Rule',
                'type' => 'text',
                'newids' => 'cf_remove_perk_rule_caption',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Name Label', 'galaxyfunder'),
                'desc' => __('Please Enter Perk Name Label for Front End Submission Form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_custom_perk_name_label',
                'std' => 'Name of Perk',
                'type' => 'text',
                'newids' => 'cf_custom_perk_name_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Amount Label', 'galaxyfunder'),
                'desc' => __('Please Enter Perk Amount Label for Front End Submission Form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_custom_perk_amount_label',
                'std' => 'Perk Amount',
                'type' => 'text',
                'newids' => 'cf_custom_perk_amount_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Description Label', 'galaxyfunder'),
                'desc' => __('Please Enter Perk Description Label for Front End Submission Form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_custom_perk_description_label',
                'std' => 'Description',
                'type' => 'text',
                'newids' => 'cf_custom_perk_description_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Claim Max Count Label', 'galaxyfunder'),
                'desc' => __('Please Enter Perk Claim Max Count for Front End Submission Form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_custom_perk_claim_count_label',
                'std' => 'Perk Claim Max Count',
                'type' => 'text',
                'newids' => 'cf_custom_perk_claim_count_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Estimated Delivery Label', 'galaxyfunder'),
                'desc' => __('Please Enter Perk Delivery Label for Front End', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_custom_perk_delivery_label',
                'std' => 'Estimated Delivery On',
                'type' => 'text',
                'newids' => 'cf_custom_perk_delivery_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Featured Image Label', 'galaxyfunder'),
                'desc' => __('Please Enter Description Label at campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_featuredimage_label',
                'std' => 'Featured Image',
                'type' => 'text',
                'newids' => 'cf_submission_camp_featuredimage_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('I Agree Label', 'galaxyfunder'),
                'desc' => __('Please Enter I Agree Label at campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_camp_i_agree_label',
                'std' => 'I Agree',
                'type' => 'text',
                'newids' => 'cf_submission_camp_i_agree_label',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_submission'),
            array(
                'name' => __('Category Selection Frontend', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_selection_category',
            ),
            array(
                'name' => __('Front End Submission Empty Checkbox', 'galaxyfunder'),
                'desc' => __('Please Enter Front End Submission Empty Field Error Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_frontend_submission_empty_checkbox_error_message',
                'std' => 'Please Select the CheckBox to Continue',
                'type' => 'text',
                'newids' => 'cf_frontend_submission_empty_checkbox_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Category Selection in Frontend Form', 'galaxyfunder'),
                'desc' => __('Select Show/Hide Category Selection Option in Frontend Form', 'galaxyfunder'),
                'id' => 'cf_show_hide_category_selection_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_category_selection_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_selection_category'),
            array(
                'name' => __('Billing and Shipping Details Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_shipping_details'
            ),
            array(
                'name' => __('Show/Hide Billing Details in Frontend Submission Form', 'galaxyfunder'),
                'desc' => __('Please Select Whether to show or hide Billing Details frontend Submission Form', 'galaxyfunder'),
                'id' => 'cf_show_hide_billing_details_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_billing_details_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Shipping Details in Frontend Submission Form', 'galaxyfunder'),
                'desc' => __('Please Select Whether to show or hide Shipping Details frontend Submission Form', 'galaxyfunder'),
                'id' => 'cf_show_hide_shipping_details_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_shipping_details_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_shipping_details'),
            array(
                'name' => __('PayPal Email Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_paypal_email_settings'
            ),
            array(
                'name' => __('Show/Hide PayPal Email Address Field in Frontend Submission Form', 'galaxyfunder'),
                'desc' => __('Please Select Whether to show or hide PayPal Email Address Field frontend Submission Form', 'galaxyfunder'),
                'id' => 'cf_show_paypal_email_id_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_paypal_email_id_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_paypal_email_settings'),
            array(
                'name' => __('Social Promotion Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_social_promotion_settings'
            ),
            array(
                'name' => __('Show/Hide Social Promotion Field in Frontend Submission Form', 'galaxyfunder'),
                'desc' => __('Please Select Whether to show or hide Social Promotion Address Field frontend Submission Form', 'galaxyfunder'),
                'id' => 'cf_show_hide_social_promotion_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_social_promotion_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_social_promotion_settings'),
            array(
                'name' => __('Contributor Table Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_contributor_table_settings'
            ),
            array(
                'name' => __('Show/Hide Contributor Table Settings Field in Frontend Submission Form', 'galaxyfunder'),
                'desc' => __('Please Select Whether to show or hide Contributor Table Settings Field frontend Submission Form', 'galaxyfunder'),
                'id' => 'cf_show_hide_contributor_table_settings_frontend',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_contributor_table_settings_frontend',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_contributor_table_settings'),
            array(
                'name' => __('FrontEnd Campaign Form Submission Message', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_submission_messages'
            ),
            array(
                'name' => __('Front End Submission Status Message', 'galaxyfunder'),
                'desc' => __('Please Enter Front End Submission Status Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_frontend_submission_status_message',
                'std' => 'Submitting',
                'type' => 'text',
                'newids' => 'cf_frontend_submission_status_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Front End Submission Response Success Message', 'galaxyfunder'),
                'desc' => __('Please Enter Front End Submission Response Success Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_frontend_submission_response_message',
                'std' => 'Campaign Submitted',
                'type' => 'text',
                'newids' => 'cf_frontend_submission_response_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Front End Submission Response Error Message', 'galaxyfunder'),
                'desc' => __('Please Enter Front End Submission Response Error Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_frontend_submission_response_error_message',
                'std' => 'Something went wrong please try later',
                'type' => 'text',
                'newids' => 'cf_frontend_submission_response_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Front End Submission Number Field Error Message', 'galaxyfunder'),
                'desc' => __('Please Enter Front End Submission Number Field Error Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_frontend_submission_number_field_error_message',
                'std' => 'Use Number',
                'type' => 'text',
                'newids' => 'cf_frontend_submission_number_field_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Front End Submission Empty Field Message', 'galaxyfunder'),
                'desc' => __('Please Enter Front End Submission Empty Field Error Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_frontend_submission_empty_field_error_message',
                'std' => 'Please Check Above Error',
                'type' => 'text',
                'newids' => 'cf_frontend_submission_empty_field_error_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_submission_messages'),
            array(
                'name' => __('FrontEnd Campaign Submission Page', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_campaign_submission_advanced'
            ),
            array(
                'name' => __('Default CSS (Non Editable)', 'galaxyfunder'),
                'desc' => __('These are element IDs for the Frontend Campaign Submission form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_submission_camp_default_css',
                'std' => '
#cf_campaign_title{
}
#cf_campaign_duration{
}
#cf_campaign_target_value{
}
#cf_campaign_min_price{
}
#cf_campaign_max_price{
}
#cf_campaign_rec_price{
}',
                'type' => 'textarea',
                'newids' => 'cf_submission_camp_default_css',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom CSS', 'galaxyfunder'),
                'desc' => __('Customize the following element IDs of Frontend Campaign Submission form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_submission_camp_custom_css',
                'std' => '
#cf_campaign_title{
}
#cf_campaign_duration{
}
#cf_campaign_target_value{
}
#cf_campaign_min_price{
}
#cf_campaign_max_price{
}
#cf_campaign_rec_price{
}',
                'type' => 'textarea',
                'newids' => 'cf_submission_camp_custom_css',
                'desc_tip' => true,
            ),
            array(
                'name' => __('URL to Redirect for Guest', 'galaxyfunder'),
                'desc' => __('Please Enter URL to Redirect if a guest tries this page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_submission_reset',
                'std' => '',
                'type' => 'submit',
                'newids' => 'cf_submission_reset',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Limit Number of Campaigns per User', 'galaxyfunder'),
                'desc' => __('Please check the checkbox if you wish to limit the number of campaigns created by a user', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_campaign_limit',
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_campaign_limit',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Number of campaigns to be allowed per User', 'galaxyfunder'),
                'desc' => __('Please Enter the Number of campaigns to be allowed per user', 'galaxyfunder'),
                'tip' => '',
//                'css' => 'min-width:550px;',
                'id' => 'cf_campaign_limit_value',
                'std' => '5',
                'type' => 'number',
                'newids' => 'cf_campaign_limit_value',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Limit exceeded Message', 'galaxyfunder'),
                'desc' => __('Please Enter a message to display when the campaign limit exceeds', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_campaign_exceeded_message',
                'std' => 'You cannot create a new campaign ',
                'type' => 'text',
                'newids' => 'cf_campaign_exceeded_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_submission_advanced'),
        ));
    }

    /*
     * default values for frontend submission
     */

    public static function cf_frontend_default_values() {
        global $woocommerce;
        foreach (CFFrontendSubmission::cf_admin_front_end() as $setting) {
            if (isset($setting['newids']) && isset($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {

                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_frontend_reset_values() {
        global $woocommerce;
// var_dump("google google");
        if (isset($_POST['reset'])) {
            foreach (CFFrontendSubmission::cf_admin_front_end() as $setting)
                if (isset($setting['newids']) && ($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
        }
    }

    public static function cf_admin_front_end_settings() {
        woocommerce_admin_fields(CFFrontendSubmission::cf_admin_front_end());
        $cf_campaign_limitcheck = get_option('cf_campaign_limit');
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if ('<?php echo $cf_campaign_limitcheck; ?>' == 'no') {
                    jQuery('#cf_campaign_limit_value').parent().parent().hide();
                    jQuery('#cf_campaign_exceeded_message').parent().parent().hide();
                }
                if (jQuery('#cf_campiagn_success_redirection_option').val() === '1') {
                    jQuery('#cf_campiagn_success_redirection_url_content').parent().parent().hide();
                } else {
                    jQuery('#cf_campiagn_success_redirection_url_content').parent().parent().show();
                }
                jQuery('#cf_campaign_limit').click(function () {
                    jQuery('#cf_campaign_limit_value').parent().parent().toggle();
                    jQuery('#cf_campaign_exceeded_message').parent().parent().toggle();
                });
                jQuery('#cf_campiagn_success_redirection_option').change(function () {
                    jQuery('#cf_campiagn_success_redirection_url_content').parent().parent().toggle();
                });
            });

        </script>
        <?php
    }

    public static function cf_admin_front_end_update_settings() {
        woocommerce_update_options(CFFrontendSubmission::cf_admin_front_end());
    }

    public static function adding_campaign() {
        global $woocommerce;
        $campaign_title = '';
        $campaign_minimum_price = '';
        $campaign_maximum_price = '';
        $campaign_recomended_price = '';
        $campaign_duration = '';
        $campaign_description = '';
        $campaign_target = '';
        $campaign_product_selection = '';
        $campaigner_paypal_email = '';
        $campaigner_social_promotion = '';
        $campaigner_social_promotion_facebook = '';
        $campaigner_social_promotion_twitter = '';
        $campaigner_social_promotion_google = '';
        $campaigner_display_contributor_table = '';
        $campaigner_contributor_anonymous_option = '';
        $campaign_target_end = '';
        $perkrule = '';
        $campaign_use_selected_product_featured_image = '';
        $campaign_crowdfunding_options = '';
        $tmp_file = '';
        $uploadfile = '';
        $author='';
        $adminemail='';
        $creatoremail='';
        if (isset($_POST['crowdfunding_title'])) {
            $campaign_title = $_POST['crowdfunding_title'];
        }
        if (isset($_POST['minimum_price'])) {
            $campaign_minimum_price = $_POST['minimum_price'];
        }
        if (isset($_POST['maximum_price'])) {
            $campaign_maximum_price = $_POST['maximum_price'];
        }
        if (isset($_POST['recomended_price'])) {
            $campaign_recomended_price = $_POST['recomended_price'];
        }
        if (isset($_POST['crowdfunding_duration'])) {
            $campaign_duration = $_POST['crowdfunding_duration'];
        }
        if (isset($_POST['crowdfunding_duration'])) {
            $campaign_duration = $_POST['crowdfunding_duration'];
        }
        if (isset($_POST['campaign_description'])) {
            $campaign_description = $_POST['campaign_description'];
        }
        if (isset($_POST['cf_target_value'])) {
            $campaign_target = $_POST['cf_target_value'];
        }
        if (isset($_POST['_target_end_selection'])) {
            $campaign_target_end = $_POST['_target_end_selection'];
        }
        if (isset($_POST['cf_campaigner_paypal_id'])) {
            $campaigner_paypal_email = $_POST['cf_campaigner_paypal_id'];
        }
        if (isset($_POST['cf_newcampaign_social_sharing'])) {
            $campaigner_social_promotion = $_POST['cf_newcampaign_social_sharing'];
        }
        if (isset($_POST['cf_newcampaign_social_sharing_facebook'])) {
            $campaigner_social_promotion_facebook = $_POST['cf_newcampaign_social_sharing_facebook'];
        }
        if (isset($_POST['cf_newcampaign_social_sharing_twitter'])) {
            $campaigner_social_promotion_twitter = $_POST['cf_newcampaign_social_sharing_twitter'];
        }
        if (isset($_POST['cf_newcampaign_social_sharing_google'])) {
            $campaigner_social_promotion_google = $_POST['cf_newcampaign_social_sharing_google'];
        }
        if (isset($_POST['cf_newcampaign_show_hide_contributors'])) {
            $campaigner_display_contributor_table = $_POST['cf_newcampaign_show_hide_contributors'];
        }
        if (isset($_POST['cf_newcampaign_mark_contributors_anonymous'])) {
            $campaigner_contributor_anonymous_option = $_POST['cf_newcampaign_mark_contributors_anonymous'];
        }

        if (get_option('cf_show_hide_crowdfunding_type') == '1') {
            $campaign_crowdfunding_options = $_POST['crowdfunding_options'];
        } else {
            $campaign_crowdfunding_options = get_option('cf_crowdfunding_type_selection');
        }
        if ($campaign_crowdfunding_options == '2') {
             if (isset($_POST['_target_end_selection1'])) {
             $campaign_target_end = $_POST['_target_end_selection1'];
        }
        }

        if (isset($_POST['cf_product_selection'])) {
            $campaign_product_selection = $_POST['cf_product_selection'];
        }
        if (isset($_POST['use_selected_product_image'])) {
            $campaign_use_selected_product_featured_image = $_POST['use_selected_product_image'];
        }
        $newarray = array();
        if (!isset($_POST['cfperkrulenonce']))
            return;
        if (!wp_verify_nonce($_POST['cfperkrulenonce'], plugin_basename(__FILE__)))
            return;
        if (isset($_POST['perk'])) {
            $perkrule = $_POST['perk'];
        }
        if (get_option('cf_frontend_submission_method') == '1') {
            $arg = array('post_type' => 'product', 'post_content' => $campaign_description, 'post_title' => $campaign_title);
        } else {
            $arg = array('post_type' => 'product', 'post_content' => $campaign_description, 'post_title' => $campaign_title, 'post_status' => 'publish');
        }
//        var_dump($arg);exit;
        $campaign_id = wp_insert_post($arg);


        if (isset($_POST['cf_newcampaign_choose_category'])) {
            wp_set_post_terms($campaign_id, $_POST['cf_newcampaign_choose_category'], 'product_cat');
        }


        if (get_option('cf_frontend_submission_method') == '2') {
            $getdate = date("m/d/Y");
            $gethour = date("h");
            $getminutes = date("i");
            update_post_meta($campaign_id, '_crowdfundingfromdatepicker', $getdate);
            update_post_meta($campaign_id, '_crowdfundingfromhourdatepicker', $gethour);
            update_post_meta($campaign_id, '_crowdfundingfromminutesdatepicker', $getminutes);
            $todatenew = date('m/d/Y', strtotime($getdate . ' + ' . $campaign_duration . 'days'));
            update_post_meta($campaign_id, '_crowdfundingtodatepicker', $todatenew);
            update_post_meta($campaign_id, '_crowdfundingtohourdatepicker', $gethour);
            update_post_meta($campaign_id, '_crowdfundingtominutesdatepicker', $getminutes);
        }
//update_post_meta($campaign_id, 'product_type', 'simple');
        update_post_meta($campaign_id, '_visibility', 'visible');
        wp_set_object_terms($campaign_id, 'simple', 'product_type');

        /* Update the Post Meta of CrowdFunding Options  */
        update_post_meta($campaign_id, '_crowdfunding_options', $campaign_crowdfunding_options);

        /* Update the Post Meta of CrowdFunding Options */
        update_post_meta($campaign_id, '_cf_product_selection', $campaign_product_selection);

        /*
         * Update user Meta Information for Billing Information and this meta information is needed on creating custom order
         *
         */

        $user_ID = get_current_user_id();
        if (isset($_POST['billing_first_name'])) {
            update_user_meta($user_ID, 'billing_first_name', $_POST['billing_first_name']);
        }
        if (isset($_POST['billing_last_name'])) {
            update_user_meta($user_ID, 'billing_last_name', $_POST['billing_last_name']);
        }
        if (isset($_POST['billing_company'])) {
            update_user_meta($user_ID, 'billing_company', $_POST['billing_company']);
        }
        if (isset($_POST['billing_address_1'])) {
            update_user_meta($user_ID, 'billing_address_1', $_POST['billing_address_1']);
        }
        if (isset($_POST['billing_address_2'])) {
            update_user_meta($user_ID, 'billing_address_2', $_POST['billing_address_2']);
        }
        if (isset($_POST['billing_city'])) {
            update_user_meta($user_ID, 'billing_city', $_POST['billing_city']);
        }
        if (isset($_POST['billing_postcode'])) {
            update_user_meta($user_ID, 'billing_postcode', $_POST['billing_postcode']);
        }
        if (isset($_POST['billing_country'])) {
            update_user_meta($user_ID, 'billing_country', $_POST['billing_country']);
        }
        if (isset($_POST['billing_state'])) {
            update_user_meta($user_ID, 'billing_state', $_POST['billing_state']);
        }
        if (isset($_POST['billing_email'])) {
            update_user_meta($user_ID, 'billing_email', $_POST['billing_email']);
        }
        if (isset($_POST['billing_phone'])) {
            update_user_meta($user_ID, 'billing_phone', $_POST['billing_phone']);
        }

        if (isset($_POST['same_as_billing'])) {
            if ($_POST['same_as_billing'] == '1') {
                update_user_meta($user_ID, 'shipping_first_name', $_POST['billing_first_name']);
                update_user_meta($user_ID, 'shipping_last_name', $_POST['billing_last_name']);
                update_user_meta($user_ID, 'shipping_company', $_POST['billing_company']);
                update_user_meta($user_ID, 'shipping_address_1', $_POST['billing_address_1']);
                update_user_meta($user_ID, 'shipping_address_2', $_POST['billing_address_2']);
                update_user_meta($user_ID, 'shipping_city', $_POST['billing_city']);
                update_user_meta($user_ID, 'shipping_postcode', $_POST['billing_postcode']);
                update_user_meta($user_ID, 'shipping_country', $_POST['billing_country']);
                update_user_meta($user_ID, 'shipping_state', $_POST['billing_state']);
            } else {
                update_user_meta($user_ID, 'shipping_first_name', $_POST['shipping_first_name']);
                update_user_meta($user_ID, 'shipping_last_name', $_POST['shipping_last_name']);
                update_user_meta($user_ID, 'shipping_company', $_POST['shipping_company']);
                update_user_meta($user_ID, 'shipping_address_1', $_POST['shipping_address_1']);
                update_user_meta($user_ID, 'shipping_address_2', $_POST['shipping_address_2']);
                update_user_meta($user_ID, 'shipping_city', $_POST['shipping_city']);
                update_user_meta($user_ID, 'shipping_postcode', $_POST['shipping_postcode']);
                update_user_meta($user_ID, 'shipping_country', $_POST['shipping_country']);
                update_user_meta($user_ID, 'shipping_state', $_POST['shipping_state']);
            }
        }
        update_post_meta($campaign_id, 'cf_campaigner_paypal_id', $campaigner_paypal_email);
        update_post_meta($campaign_id, '_crowdfundingsocialsharing', $campaigner_social_promotion);
        update_post_meta($campaign_id, '_crowdfundingsocialsharing_facebook', $campaigner_social_promotion_facebook);
        update_post_meta($campaign_id, '_crowdfundingsocialsharing_twitter', $campaigner_social_promotion_twitter);
        update_post_meta($campaign_id, '_crowdfundingsocialsharing_google', $campaigner_social_promotion_google);
        update_post_meta($campaign_id, '_crowdfunding_showhide_contributor', $campaigner_display_contributor_table);
        update_post_meta($campaign_id, '_crowdfunding_contributor_anonymous', $campaigner_contributor_anonymous_option);

        update_post_meta($campaign_id, '_regular_price', 100);
        update_post_meta($campaign_id, '_price', 100);
        update_post_meta($campaign_id, '_stock_status', 'instock');
        update_post_meta($campaign_id, 'perk', $perkrule);
        update_post_meta($campaign_id, '_crowdfundinggetdescription', $campaign_description);
        update_post_meta($campaign_id, '_crowdfundinggettargetprice', $campaign_target); //updating target price
        update_post_meta($campaign_id, '_crowdfundinggetminimumprice', $campaign_minimum_price);
        update_post_meta($campaign_id, '_crowdfundinggetmaximumprice', $campaign_maximum_price);
        update_post_meta($campaign_id, '_target_end_selection', $campaign_target_end);
        update_post_meta($campaign_id, '_crowdfundingcampaignduration', $campaign_duration);

        update_post_meta($campaign_id, '_crowdfundingcheckboxvalue', 'yes');
        update_post_meta($campaign_id, '_sold_individually', 'yes');
        if (update_post_meta($campaign_id, '_crowdfundinggetrecommendedprice', $campaign_recomended_price)) {
            if (get_option('cf_enable_mail_for_campaign_submission') == 'yes') {
                if (get_option('cf_send_email_to_campaign_creator') == 'yes') {
                    $author = get_post_field('post_author', $campaign_id);
                    $creatoremail = get_the_author_meta('user_email', $author);
                    $newarray[] = $creatoremail;
                }
                if (get_option('cf_send_email_to_site_admin') == 'yes') {
                    $adminemail = get_option('admin_email');
                    $newarray[] = $adminemail;
                }
                if (get_option('cf_send_email_to_others') == 'yes') {
                    $text = trim(get_option('cf_send_email_to_others_mail'));
                    $textAr = explode("\n", $text);
                    $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                    foreach ($textAr as $line) {
                        $newarray[] = $line;
                    }
                }
                if (is_array($newarray)) {
                    foreach ($newarray as $fieldarray) {
                        if (!is_null($fieldarray) || $fieldarray != '') {
                            if(get_option('cf_frontend_submission_method')=='1'){
                                include 'campaign_submission_email.php';
                            }else{
                                $post_id=$campaign_id;
                                include 'approved_campaign_email.php';
                            }
                        }
                    }
                }
            }
            _e(get_option('cf_frontend_submission_response_message'), 'galaxyfunder');
        } else {
            _e(get_option('cf_frontend_submission_response_error_message'), 'galaxyfunder');
        }
        if ($campaign_use_selected_product_featured_image == '1') {
            if(count($_POST['cf_product_selection'])==1){
            update_post_meta($campaign_id, '_use_selected_product_image', 'yes');
            $feat_image = get_post_thumbnail_id($_POST['cf_product_selection'][0]);
            set_post_thumbnail($campaign_id, $feat_image);
            }
        } else {
            $uploaddir = wp_upload_dir();
            if (isset($_FILES['cf_featured_image'])) {
                $tmp_file = $_FILES['cf_featured_image']["tmp_name"];
            }
            if (isset($_FILES['cf_featured_image'])) {
                $uploadfile = $uploaddir['path'] . '/' . $_FILES['cf_featured_image']['name'];
            }
            move_uploaded_file($tmp_file, $uploadfile);
            $wp_filetype = wp_check_filetype(basename($uploadfile), null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploadfile)),
                'post_status' => 'inherit',
            );
            $attach_id = wp_insert_attachment($attachment, $uploadfile); // adding the image to th media
            $attach_data = wp_generate_attachment_metadata($attach_id, $uploadfile);
            $update = wp_update_attachment_metadata($attach_id, $attach_data); // Updated the image details
            set_post_thumbnail($campaign_id, $attach_id);
        }
        exit();
    }

    /*
     * showing form and adding script for the form data collection
     */

    public static function enqueue_script_form() {
        global $woocommerce;
        if ((float) $woocommerce->version <= (float) ('2.2.0')) {
            wp_enqueue_script('cf_chosen_script', plugins_url() . '/gf/js/chosen.jquery.min.js');
            wp_enqueue_style('cf_chosen_style', plugins_url() . '/gf/css/admin.css');
        } else {
            $assets_path = str_replace(array('http:', 'https:'), '', WC()->plugin_url()) . '/assets/';
            wp_enqueue_script('select2');
            wp_enqueue_style('select2', $assets_path . 'css/select2.css');
        }
    }

    public static function shortcode_crowdfunding_form($content) {
        global $woocommerce;

        ob_start();
        $newarray = '';

        if (is_user_logged_in()) {
            $allowed_roles = get_option('cf_campaign_submission_frontend_exclude_role_control');
            $current_role = (wp_get_current_user()->roles);
            if (in_array($current_role[0], $allowed_roles)) {
                $settings = array("teeny" => true, 'media_buttons' => false, 'quicktags' => false, 'textarea_name' => 'campaign_description');
                echo '<style type="text/css">
            ' . get_option('cf_submission_camp_custom_css') . '
            </style>';

                $args = array('post_type' => 'product',
                    'posts_per_page' => '-1',
                     'meta_query'=>array(
                'relation'=>'OR',
                array(
                'key'=>'_crowdfundingcheckboxvalue',
//                'value'=>'true',
                'compare'=> 'NOT EXISTS'           
                ),
                array(
                'key'=>'_crowdfundingcheckboxvalue',
                'value'=>'no',    
                'compare'=> '='           
                ),
                ),
                    
                );
                $getproducts = get_posts($args);
//echo '<pre>';var_dump($getproducts);echo '</pre>';
//$newarray = array();
//error messages to translate
//var_dump($getproducts);
//$newarray = array();

                $userid = get_current_user_id();
                $ship_first_name = get_user_meta($userid, 'shipping_first_name', true);
                $ship_last_name = get_user_meta($userid, 'shipping_last_name', true);
                $ship_company = get_user_meta($userid, 'shipping_company', true);
                $ship_address1 = get_user_meta($userid, 'shipping_address_1', true);
                $ship_address2 = get_user_meta($userid, 'shipping_address_2', true);
                $ship_city = get_user_meta($userid, 'shipping_city', true);
                $ship_country = get_user_meta($userid, 'shipping_country', true);
                $ship_postcode = get_user_meta($userid, 'shipping_postcode', true);
                $ship_state = get_user_meta($userid, 'shipping_state', true);

                /* Billing Information for the Corresponding USER/AUTHOR */
                $bill_first_name = get_user_meta($userid, 'billing_first_name', true);
                $bill_last_name = get_user_meta($userid, 'billing_last_name', true);
                $bill_company = get_user_meta($userid, 'billing_company', true);
                $bill_address1 = get_user_meta($userid, 'billing_address_1', true);
                $bill_address2 = get_user_meta($userid, 'billing_address_2', true);
                $bill_city = get_user_meta($userid, 'billing_city', true);
                $bill_country = get_user_meta($userid, 'billing_country', true);
                $bill_postcode = get_user_meta($userid, 'billing_postcode', true);
                $bill_state = get_user_meta($userid, 'billing_state', true);
                $bill_email = get_user_meta($userid, 'billing_email', true);
                $bill_phone = get_user_meta($userid, 'billing_phone', true);

                if (is_array($newarray) && (is_array($producttitle))) {
                    $output = array_combine($newarray, $producttitle);
                }
                $select_agree_error = __(get_option('cf_frontend_submission_empty_checkbox_error_message'), 'galaxyfunder');
                $check_above_error = __(get_option('cf_frontend_submission_empty_field_error_message'), 'galaxyfunder');
                $use_number_error = __(get_option('cf_frontend_submission_number_field_error_message'), 'galaxyfunder');
                $submitting_text = __(get_option('cf_frontend_submission_status_message'), 'galaxyfunder');
//campaign submission form START
                echo '<form id="crowd_form" method="post" enctype="multipart/form-data">';
                if (get_option('cf_show_hide_crowdfunding_type') == '1') {
                    echo '<p><label>Crowdfunding Type</label></p>
                <p><select name="crowdfunding_options" id="crowdfunding_options">
                    <option value=1>Fundraising by Crowdfunding</option>
                    <option value=2>Product Purchase by Crowdfunding</option>
                </select>
</p>';
                }
                echo '<div class="maindivcf"><div class="cf_newcampaign_title"><p><label>' . get_option("cf_submission_camp_title_label") . '</label></p><p><input type="text" id="cf_campaign_title" name="crowdfunding_title" value="" placeholder="' . get_option("cf_submission_camp_title_placeholder") . '"></p></div>';
                if (get_option('cf_show_hide_campaign_end_selection_frontend') == '1') {
                    ?>
                    <div id="campaign_options1"><p><label><?php _e('Campaign End Method', 'galaxyfunder'); ?></label></p>
                        <p><select name="_target_end_selection" id="_target_end_selection1" class="_target_end_selection">
                                <option class="target_selection_3" value="3"><?php _e('Target Goal', 'galaxyfunder'); ?></option>
                                <option class="target_selection_1" value="1"><?php _e('Target Date', 'galaxyfunder'); ?></option>
                                <option class="target_selection_2" value="2"><?php _e('Campaign Never Ends', 'galaxyfunder'); ?></option>
                            </select></p></div>

                    <div id="campaign_options2"><p><label><?php _e('Campaign End Method', 'galaxyfunder'); ?></label></p>
                        <p>
                            <select name="_target_end_selection1" id="_target_end_selection2" class="_target_end_selection">
                                <option class="target_selection_3" value="3"><?php _e('Target Goal', 'galaxyfunder'); ?></option>
                                <option class="target_selection_1" value="1"><?php _e('Target Date', 'galaxyfunder'); ?></option>
                            </select>
                        </p>
                    </div>
                    <?php
                }
                ?>
                <div class="cf_product_choosing">
                    <p><label><?php _e('Choose Products', 'galaxyfunder'); ?></label></p><p>
                        <select multiple name="cf_product_selection[]" data-placeholder="Choose Product..." id="cf_product_selection" style="width: 400px;">
                            <option value=""></option>
                            <?php
                            if (get_option('cf_frontend_product_selection_type') == '1') {
                                if (is_array($getproducts)) {
                                    foreach ($getproducts as $product) {
//                                        var_dump($product->ID);
                                        $product_type = get_product($product->ID);
                                        if ($product_type->is_type('simple')) {
                                            if (get_post_meta($product->ID, '_crowdfundingcheckboxvalue', true) != 'yes') {
                                                ?>
                                                <option data-price ="<?php echo get_post_meta($product->ID, '_regular_price', true); ?>"



                                                        value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $selected_products = explode(',', get_option('cf_frontend_selected_products'));
                                        foreach ($selected_products as $each_product) {
                                            ?>
                                    <option data-price ="<?php echo get_post_meta($each_product, '_regular_price', true); ?>"
                                            value="<?php echo $each_product; ?>"><?php echo get_the_title($each_product); ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                        </select>
                    </p>
                    <p><label><?php _e('Use Selected Product Featured Image', 'galaxyfunder'); ?></label></p><p><input type="checkbox" name="use_selected_product_image" id="use_selected_product_image" value="1"/><label><?php _e('   (Works only When one Product is Chosen)', 'galaxyfunder'); ?></label></p>
                </div>

                <?php
                echo '<div class="cf_newcampaign_duration"><p><label>' . get_option("cf_submission_camp_duration_label") . '</label></p><p><input type="text" id="cf_campaign_duration" name="crowdfunding_duration" value="" placeholder="' . get_option("cf_submission_camp_duration_placeholder") . '"></p></div>';
                echo '<div class="cf_newcampaign_targetprice"><p><label>' . get_option("cf_submission_camp_targetprice_label") . '</label></p><p><input type="text" id="cf_campaign_target_value" name="cf_target_value" value="" Placeholder="' . get_option("cf_submission_camp_targetprice_placeholder") . '"></p></div>';
                if (get_option("cf_submission_camp_minimumprice_showhide") != "yes") {
                    echo '<div class="cf_newcampaign_minimumprice"><p><label>' . get_option("cf_submission_camp_minimumprice_label") . '</label></p><p><input type="text" id="cf_campaign_min_price" name="minimum_price" value="" placeholder="' . get_option("cf_submission_camp_minimumprice_placeholder") . '"></p></div>';
                }
                if (get_option("cf_submission_camp_maximumprice_showhide") != "yes") {
                    echo '<div class="cf_newcampaign_maximumprice"><p><label>' . get_option("cf_submission_camp_maximumprice_label") . '</label></p><p><input type="text" id="cf_campaign_max_price" name="maximum_price" value="" placeholder="' . get_option("cf_submission_camp_maximumprice_placeholder") . '"></p></div>';
                }
                if (get_option("cf_submission_camp_recommendedprice_showhide") != "yes") {
                    echo '<div class="cf_newcampaign_recommendedprice"><p><label>' . get_option("cf_submission_camp_recommendedprice_label") . '</label></p><p><input type="text" id="cf_campaign_rec_price" name="recomended_price" value="" placeholder="' . get_option("cf_submission_camp_recommendedprice_placeholder") . '"></p></div>';
                }
                echo '<div class="cf_newcampaign_description">';
                echo '<p><label>' . get_option("cf_submission_camp_description_label") . '</label></p>';
//echo '<p>' . wp_editor('', 'campaign_description', $settings) . '</p>';
                echo '<p><textarea rows="5" cols="50" name="campaign_description" id="campaign_description"></textarea></div>';
                wp_nonce_field(plugin_basename(__FILE__), 'cfperkrulenonce');
                ?>
                <div id="meta_inner">
                    <?php $i = 0; ?>
                    <span id="here"></span>
                    <?php if (get_option("cf_show_hide_add_perk_button_frontend") == '1') { ?>
                        <button class="add button-primary" style="margin-top:10px;margin-bottom:10px;"><?php _e(get_option('cf_add_perk_rule_caption'), 'galaxyfunder'); ?></button>
                    <?php } ?>
                <!--                <style type="text/css">
                #cf_product_selection {
                width:250px;
                }
                </style>-->
                    <script>
                        jQuery(document).ready(function () {
                            var countperk = <?php echo $i; ?>;
                            jQuery(".add").click(function () {
                                countperk = countperk + 1;
                                jQuery('#here').append('<div class="panel woocommerce_options_panel" style="display: block;position:relative;"><div class="options_group" style=" border:1px solid black;padding:10px;"><p class="form-field"><label><?php echo get_option('cf_custom_perk_name_label'); ?></label></p><p><input type="text" name="perk[' + countperk + '][name]" class="short" value=""/></p>\n\
                        \n\<p class="form-field"><label><?php echo get_option('cf_custom_perk_amount_label'); ?></label></p><p><input type="text" name="perk[' + countperk + '][amount]" class="short" value=""/></p>\n\
                        <p class="form-field"><label><?php echo get_option('cf_custom_perk_description_label'); ?></label></p><p><textarea rows="3" cols="14" style="height:110px;width:360px;" name="perk[' + countperk + '][description]" class="short" value=""></textarea></p>\n\
                <p class="form-field"><label><?php echo get_option('cf_custom_perk_claim_count_label'); ?></label></p><p><input type ="text" name="perk[' + countperk + '][claimcount]" class="short" value=""/></p>\n\
                <p class="form-field"><label><?php echo get_option('cf_custom_perk_delivery_label'); ?></label></p><p><input type="text" name="perk[' + countperk + '][deliverydate]" id="perkid' + countperk + '" class="short" value=""/></p><button class="remove button-secondary" style="position:absolute;right:0px; bottom:0px; margin-bottom:10px; margin-right:10px;"><?php echo get_option('cf_remove_perk_rule_caption'); ?></button></div></div>');
                                jQuery('#perkid' + countperk).datepicker({
                                    changeMonth: true,
                                });
                                return false;
                            });
                            jQuery(document).on('click', '.remove', function () {
                <?php if (get_option('cf_enable_remove_perk_rule') == 'yes') { ?>
                                    var didConfirm = confirm("<?php echo get_option('cf_custom_remove_perk_confirmation_message'); ?>");
                                    if (didConfirm === true) {
                                        jQuery(this).parent().remove();
                                        return false;
                                    }
                                    return false;
                <?php } else { ?>
                                    jQuery(this).parent().remove();
                <?php } ?>
                            });
                        });</script>
                </div>
                <?php
                echo '<input type="hidden" value=' . wp_create_nonce('upload_thumb') . ' name="_nonce" />';
                echo '<div class="cf_newcampaign_featured"><p><label>' . get_option("cf_submission_camp_featuredimage_label") . '</label></p><p><input type="file" name="cf_featured_image"></p></div>';
//echo '<p><label>Description</label></p><p><textarea rows="5" cols="35" name="campaign_descriptn" placeholder=""></textarea></p>';
                echo '<input type="hidden" name="action" id="action" value="crowdfunding">';
                ?>
                <?php if (get_option("cf_show_hide_billing_details_frontend") == '1') { ?>
                    <div class="cf_newcampaign_billinginfo">
                        <h3><?php __('Billing Information', 'galaxyfunder'); ?></h3>
                        <p><label><?php _e('Billing First Name', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_first_name" id="billing_first_name" value="<?php echo $bill_first_name; ?>"/></p>
                        <p><label><?php _e('Billing Last Name', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_last_name" id="billing_last_name" value="<?php echo $bill_last_name; ?>"/></p>
                        <p><label><?php _e('Billing Company', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_company" id="billing_company" value="<?php echo $bill_company; ?>"/></p>
                        <p><label><?php _e('Billing Address 1', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_address_1" id="billing_address_1" value="<?php echo $bill_address1; ?>"/></p>
                        <p><label><?php _e('Billing Address 2', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_address_2" id="billing_address_2" value="<?php echo $bill_address2; ?>"/></p>
                        <p><label><?php _e('Billing City', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_city" id="billing_city" value="<?php echo $bill_city; ?>"/></p>
                        <p><label><?php _e('Billing Postcode', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_postcode" id="billing_postcode" value="<?php echo $bill_postcode; ?>"/></p>
                        <p><label><?php _e('Billing Country', 'galaxyfunder'); ?></label></p><p><select id="billing_country" style="display:block;" name="billing_country"><?php include('country_selection.php'); ?></select></p>
                        <p><label><?php _e('Billing State', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_state" id="billing_state" value="<?php echo $bill_state; ?>"/></p>
                        <p><label><?php _e('Billing Email', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_email" id="billing_email" value="<?php echo $bill_email; ?>"/></p>
                        <p><label><?php _e('Billing Phone', 'galaxyfunder'); ?></label></p><p><input type="text" name="billing_phone" id="billing_phone" value="<?php echo $bill_phone; ?>"/></p>
                    </div>
                <?php } ?>
                <?php if (get_option("cf_show_hide_shipping_details_frontend") == '1') { ?>
                    <div class="cf_newcampaign_shippinginfo">
                        <h3><?php _e('Shipping Information', 'galaxyfunder'); ?></h3>
                        <p><label><?php _e('Same as Billing Information', 'galaxyfunder'); ?></label></p><p><input type="checkbox" name="same_as_billing" id="same_as_billing" value="1"/></p>
                        <p><label><?php _e('Shipping First Name', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_first_name" id="billing_first_name" value="<?php echo $ship_first_name; ?>"/></p>
                        <p><label><?php _e('Shipping Last Name', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_last_name" id="shipping_last_name" value="<?php echo $ship_last_name; ?>"/></p>
                        <p><label><?php _e('Shipping Company', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_company" id="shipping_company" value="<?php echo $ship_company; ?>"/></p>
                        <p><label><?php _e('Shipping Address 1', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_address_1" id="shipping_address_1" value="<?php echo $ship_address1; ?>"/></p>
                        <p><label><?php _e('Shipping Address 2', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_address_2" id="shipping_address_2" value="<?php echo $ship_address2; ?>"/></p>
                        <p><label><?php _e('Shipping City', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_city" id="shipping_city" value="<?php echo $ship_city; ?>"/></p>
                        <p><label><?php _e('Shipping Postcode', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_postcode" id="shipping_postcode" value="<?php echo $ship_postcode; ?>"/></p>
                        <p><label><?php _e('Shipping Country', 'galaxyfunder'); ?></label></p><p><select id="shipping_country" style="display:block;" name="shipping_country"><?php include('country_selection.php'); ?></select></p>
                        <p><label><?php _e('Shipping State', 'galaxyfunder'); ?></label></p><p><input type="text" name="shipping_state" id="shipping_state" value="<?php echo $ship_state; ?>"/></p>
                    </div>
                <?php } ?>
                <?php if (get_option("cf_show_hide_social_promotion_frontend") == '1') { ?>
                    <div class="cf_newcampaign_social_sharing">
                        <p>
                            <label><?php _e('Enable Social Promotion for this Campaign', 'galaxyfunder'); ?></label>
                        </p>
                        <p>
                            <input type="checkbox" name="cf_newcampaign_social_sharing" id="cf_newcampaign_social_sharing" class="cf_newcampaign_social_sharing" value="yes"/>
                        </p>
                    </div>
                <?php } ?>
                <div class="cf_newcampaign_social_sharing_facebook">
                    <p>
                        <label><?php _e('Enable Social Promotion through Facebook for this Campaign', 'galaxyfunder'); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" name="cf_newcampaign_social_sharing_facebook" id="cf_newcampaign_social_sharing_facebook" class="cf_newcampaign_social_sharing_facebook" value="yes"/>
                    </p>
                </div>
                <div class="cf_newcampaign_social_sharing_twitter">
                    <p>
                        <label><?php _e('Enable Social Promotion through Twitter for this Campaign', 'galaxyfunder'); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" name="cf_newcampaign_social_sharing_twitter" id="cf_newcampaign_social_sharing_twitter" class="cf_newcampaign_social_sharing_twitter" value="yes"/>
                    </p>
                </div>
                <div class="cf_newcampaign_social_sharing_google">
                    <p>
                        <label><?php _e('Enable Social Promotion through Google for this Campaign', 'galaxyfunder'); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" name="cf_newcampaign_social_sharing_google" id="cf_newcampaign_social_sharing_google" class="cf_newcampaign_social_sharing_google" value="yes"/>
                    </p>
                </div>

                <?php
                if (get_option('cf_show_hide_category_selection_frontend') == '1') {
                    if (get_option('cf_frontend_categories_selection_type') == '1') {
                        $terms = get_terms('product_cat', array('hide_empty' => false));
                        if ($terms) {
                            ?>
                            <div class="cf_newcampaign_select_category">
                                <p>
                                    <label>
                                        <?php _e('Choose Category', 'galaxyfunder'); ?>
                                    </label>
                                </p>
                                <p>
                                    <select style="width:300px;" name="cf_newcampaign_choose_category[]" id="cf_newcampaign_choose_category" class="cf_newcampaign_choose_category" multiple="multiple">
                                        <?php
                                        foreach ($terms as $value) {
                                            ?>
                                            <option value="<?php echo $value->term_id; ?>"><?php echo $value->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="cf_newcampaign_select_category">
                            <p>
                                <label>
                                    <?php _e('Choose Category', 'galaxyfunder'); ?>
                                </label>
                            </p>
                            <p>
                                <select style="width:300px;" name="cf_newcampaign_choose_category[]" id="cf_newcampaign_choose_category" class="cf_newcampaign_choose_category" multiple="multiple">
                                    <?php
                                    $selected_categories = get_option('cf_frontend_selected_categories');

                                    foreach ($selected_categories as $each_category) {
                                        $category_object = get_term($each_category, 'product_cat')
                                        ?>
                                        <option value="<?php echo $category_object->term_id; ?>"><?php echo $category_object->name; ?></option>
                                    <?php } ?>
                                </select>
                            </p>
                        </div>
                        <?php
                    }
                }
                ?>
                <?php if (get_option("cf_show_hide_contributor_table_settings_frontend") == '1') { ?>
                    <div class="cf_newcampaign_show_hide_contributors">
                        <p>
                            <label><?php _e('Show Contributor Table for this Campaign', 'galaxyfunder'); ?></label>
                        </p>
                        <p>
                            <input type="checkbox" name="cf_newcampaign_show_hide_contributors" id="cf_newcampaign_show_hide_contributors" class="cf_newcampaign_show_hide_contributors" value="yes"/>
                        </p>
                    </div>
                <?php } ?>
                <div class="cf_newcampaign_mark_contributors_anonymous">
                    <p>
                        <label><?php _e('Mark Contributors as Anonymous for this Campaign', 'galaxyfunder'); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" name="cf_newcampaign_mark_contributors_anonymous" id="cf_newcampaign_mark_contributors_anonymous" class="cf_newcampaign_mark_contributors_anonymous" value="yes"/>
                    </p>
                </div>
                <?php if (get_option("cf_show_paypal_email_id_frontend") == '1') { ?>
                    <div class="cf_newcampaign_paypalid">
                        <p>
                            <label><?php _e('Enter your PayPal ID', 'galaxyfunder'); ?></label>
                        </p>
                        <p>
                            <input type="text" name="cf_campaigner_paypal_id" id="cf_campaigner_paypal_id" value=""/>
                        </p>
                    </div>
                <?php } ?>
                <?php
                echo '<div class="cf_newcampaign_agree">';
                echo '<p><label>' . get_option("cf_submission_camp_i_agree_label") . '</label></p>';
                echo '<p><input type="checkbox" name="cf_newcampaign_show_hide_agree" id="cf_newcampaign_show_hide_agree" class="cf_newcampaign_show_hide_agree" value="yes"/></div>';
                $cf_active_products = CFFrontendSubmission::getcountofactivecampaigns(get_current_user_id());
                $cf_campaign_limit = get_option('cf_campaign_limit_value');
                $cf_campaign_exceeded_message = get_option('cf_campaign_exceeded_message');
                $cf_campaign_limitcheck_front = get_option('cf_campaign_limit');
                if ($cf_campaign_limitcheck_front == 'yes') {
                    if ($cf_active_products >= $cf_campaign_limit) {
                        echo $cf_campaign_exceeded_message;
                    } else {
                        echo '<div class="cf_newcampaign_submit"><p>'
                        . '<input type="submit" id="crowd_submit" name="crowd_submit" value="' . __('Submit Campaign', 'galaxyfunder') . '" />'
                        . '</p></div></div>';
                    }
                } else {
                    echo '<div class="cf_newcampaign_submit"><p><input type="submit" id="crowd_submit" name="crowd_submit" value="' . __('Submit Campaign', 'galaxyfunder') . '"> </p></div></div>';
                }
//echo '</table>';
                echo '</form>';
                echo '<div id="cf_validation"></div>';
                echo '<div id="cf_response"></div>';
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                <?php
                if (get_option('cf_show_hide_crowdfunding_type') == '2') {
                    if (get_option('cf_crowdfunding_type_selection') == '2') {
                        ?>
                                jQuery('.cf_newcampaign_title').show();
                                jQuery('#campaign_options2').show();
                                jQuery('.cf_product_choosing').show();
                                jQuery('#campaign_options1').hide();
                                jQuery('.cf_newcampaign_duration').hide();
                                jQuery('.cf_newcampaign_targetprice').show();
                                jQuery('.cf_newcampaign_minimumprice').hide();
                                jQuery('.cf_newcampaign_maximumprice').hide();
                                jQuery('.cf_newcampaign_recommendedprice').hide();
                                jQuery('.cf_newcampaign_description').show();
                                jQuery('#meta_inner').show();
                                jQuery('.cf_newcampaign_featured').show();
                                //
                                jQuery('.cf_newcampaign_billinginfo').show();
                                jQuery('.cf_newcampaign_shippinginfo').show();
                                jQuery('#billing_country').show();
                                jQuery('#shipping_country').show();
                        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                    jQuery('#billing_country').chosen();
                                    jQuery('#shipping_country').chosen();
                        <?php } else { ?>
                                    jQuery('#billing_country').select2();
                                    jQuery('#shipping_country').select2();
                        <?php } ?>
                                //jQuery('.cf_newcampaign_shippinginfo').hide();
                                jQuery('.cf_newcampaign_submit').show();
                                jQuery('#cf_campaign_target_value').attr('readonly', true);
                    <?php } if (get_option('cf_crowdfunding_type_selection') == '1') { ?>
                                jQuery('.cf_newcampaign_title').show();
                                jQuery('#campaign_options1').show();
                                jQuery('.cf_product_choosing').hide();
                                jQuery('#campaign_options2').hide();
                                jQuery('.cf_newcampaign_duration').hide();
                                jQuery('.cf_newcampaign_targetprice').show();
                                jQuery('.cf_newcampaign_minimumprice').show();
                                jQuery('.cf_newcampaign_maximumprice').show();
                                jQuery('.cf_newcampaign_recommendedprice').show();
                                jQuery('.cf_newcampaign_description').show();
                                jQuery('#meta_inner').show();
                                jQuery('.cf_newcampaign_featured').show();
                                jQuery('.cf_newcampaign_billinginfo').hide();
                                jQuery('.cf_newcampaign_shippinginfo').hide();
                                jQuery('.cf_newcampaign_submit').show();
                                jQuery('#cf_campaign_target_value').attr('readonly', false);
                        <?php
                    }
                }
                ?>

                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery('#cf_product_selection').chosen();
                            jQuery('#crowdfunding_options').chosen();
                            jQuery('._target_end_selection').chosen();
                <?php } else { ?>
                            jQuery('#cf_product_selection').select2();
                            jQuery('#crowdfunding_options').select2();
                            jQuery('._target_end_selection').select2();
                <?php } ?>
                <?php if (get_option('cf_show_hide_crowdfunding_type') == '1') { ?>
                            //jQuery('.maindivcf').hide();
                            jQuery('.cf_newcampaign_title').show();
                            jQuery('#campaign_options1').show();
                            jQuery('.cf_product_choosing').hide();
                            jQuery('#campaign_options2').hide();
                            jQuery('.cf_newcampaign_duration').hide();
                            jQuery('.cf_newcampaign_targetprice').show();
                            jQuery('.cf_newcampaign_minimumprice').show();
                            jQuery('.cf_newcampaign_maximumprice').show();
                            jQuery('.cf_newcampaign_recommendedprice').show();
                            jQuery('.cf_newcampaign_description').show();
                            jQuery('#meta_inner').show();
                            jQuery('.cf_newcampaign_featured').show();
                            jQuery('.cf_newcampaign_billinginfo').hide();
                            jQuery('.cf_newcampaign_shippinginfo').hide();
                            jQuery('.cf_newcampaign_submit').show();
                            jQuery('#cf_campaign_target_value').attr('readonly', false);
                <?php } ?>
                        // jQuery('#billing_country').chosen();
                        //jQuery('#shipping_country').chosen();
                        //jQuery('#cf_product_selection').attr('multiple', '');
                        jQuery('#crowdfunding_options').change(function (e) {
                            jQuery('.maindivcf').show();
                            var updatevalue = jQuery(this).val();
                            //alert(updatevalue);
                            if (updatevalue === '2') {
                                jQuery('.cf_newcampaign_title').show();
                                jQuery('#campaign_options2').show();
                                jQuery('.cf_product_choosing').show();
                                jQuery('#campaign_options1').hide();
                                jQuery('.cf_newcampaign_duration').hide();
                <?php if (get_option("cf_show_hide_target_product_purchase_frontend") == '1') { ?>
                                    jQuery('.cf_newcampaign_targetprice').show();
                <?php } else { ?>
                                    jQuery('.cf_newcampaign_targetprice').hide();
                <?php } ?>
                                jQuery('.cf_newcampaign_minimumprice').hide();
                                jQuery('.cf_newcampaign_maximumprice').hide();
                                jQuery('.cf_newcampaign_recommendedprice').hide();
                                jQuery('.cf_newcampaign_description').show();
                                jQuery('#meta_inner').show();
                                jQuery('.cf_newcampaign_featured').show();
                                //
                                jQuery('.cf_newcampaign_billinginfo').show();
                                jQuery('.cf_newcampaign_shippinginfo').show();
                                jQuery('#billing_country').show();
                                jQuery('#shipping_country').show();
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                    jQuery('#billing_country').chosen();
                                    jQuery('#shipping_country').chosen();
                <?php } else { ?>
                                    jQuery('#billing_country').select2();
                                    jQuery('#shipping_country').select2();
                <?php } ?>
                                //jQuery('.cf_newcampaign_shippinginfo').hide();
                                jQuery('.cf_newcampaign_submit').show();
                                jQuery('#cf_campaign_target_value').attr('readonly', true);
                            } else {

                                jQuery('.cf_newcampaign_title').show();
                                jQuery('#campaign_options1').show();
                                jQuery('.cf_product_choosing').hide();
                                jQuery('#campaign_options2').hide();
                                jQuery('.cf_newcampaign_duration').hide();
                                jQuery('.cf_newcampaign_targetprice').show();
                                jQuery('.cf_newcampaign_minimumprice').show();
                                jQuery('.cf_newcampaign_maximumprice').show();
                                jQuery('.cf_newcampaign_recommendedprice').show();
                                jQuery('.cf_newcampaign_description').show();
                                jQuery('#meta_inner').show();
                                jQuery('.cf_newcampaign_featured').show();
                                jQuery('.cf_newcampaign_billinginfo').hide();
                                jQuery('.cf_newcampaign_shippinginfo').hide();
                                jQuery('.cf_newcampaign_submit').show();
                                jQuery('#cf_campaign_target_value').attr('readonly', false);
                            }

                        });
                        jQuery('._target_end_selection').change(function (e) {
                            var currentvalue = jQuery(this).val();
                            if (currentvalue === '1') {
                                jQuery('.cf_newcampaign_duration').show();
                            } else {
                                jQuery('.cf_newcampaign_duration').hide();
                            }
                        });
                        jQuery('#cf_product_selection').change(function () {
                            // var thisvalue = jQuery('option:selected', this).attr('data-price');
                            var thisvalue = 0;
                            jQuery('#cf_product_selection > option:selected').each(function () {
                                var value = jQuery(this).attr('data-price');
                                thisvalue = parseFloat(thisvalue) + parseFloat(value);
                                thisvalue = thisvalue.toFixed(2);
                            });
                            jQuery("#cf_campaign_target_value").val(thisvalue);
                        });
                    });
                    jQuery(document).ready(function () {
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery('.cf_newcampaign_choose_category').chosen();
                <?php } else { ?>
                            jQuery('.cf_newcampaign_choose_category').select2();
                <?php } ?>
                        jQuery(".cf_newcampaign_mark_contributors_anonymous").hide();
                        jQuery("#cf_newcampaign_show_hide_contributors").change(function () {
                            jQuery(".cf_newcampaign_mark_contributors_anonymous").toggle();
                        });
                    });
                    jQuery(document).ready(function () {
                        jQuery(".cf_newcampaign_social_sharing_facebook").hide();
                        jQuery(".cf_newcampaign_social_sharing_twitter").hide();
                        jQuery(".cf_newcampaign_social_sharing_google").hide();
                        jQuery("#cf_newcampaign_social_sharing").change(function () {
                            jQuery(".cf_newcampaign_social_sharing_facebook").toggle();
                            jQuery(".cf_newcampaign_social_sharing_twitter").toggle();
                            jQuery(".cf_newcampaign_social_sharing_google").toggle();
                        });
                    });
                </script>
                <script type="text/javascript">
                <?php
                if (get_option('cf_campiagn_success_redirection_option') == '2') {
                    ?>
                        var campaign_success_redirect_url = "<?php echo get_option("cf_campiagn_success_redirection_url_content") ?>";
                <?php } else {
                    ?>
                        var campaign_success_redirect_url = '';
                <?php }
                ?>
                </script>
                <?php
                echo '<script type="text/javascript">
              jQuery(document).ready(function(){
                 var options = {
        target:        "#cf_response",      // target element(s) to be updated with server response
        beforeSubmit:  cf_request,     // pre-submit callback
        success:       cf_response,    // post-submit callback
        url: "' . admin_url("admin-ajax.php") . '",            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        action: "crowdfunding"
    };

   // bind form using
    jQuery("#crowd_form").ajaxForm(options);
    function cf_request(formData, jqForm, options) {
    var campaign_title = jQuery("input[name=crowdfunding_title]").fieldValue();
    var campaign_duration = jQuery("input[name=crowdfunding_duration]").fieldValue();
    var campaign_endselection = jQuery("._target_end_selection").val();
    var agree = jQuery("[name=cf_newcampaign_show_hide_agree]:checked").val();

    var campaign_target_price = jQuery("input[name=cf_target_value]").fieldValue();
    var campaign_description = jQuery("#campaign_description").val();
    var campaign_newtype = jQuery("#crowdfunding_options").val();';
                if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                    echo 'var cf_product_selection = jQuery("#cf_product_selection").chosen().val();';
                } else {
                    echo 'var cf_product_selection = jQuery("#cf_product_selection").select2().val();';
                }
                echo 'if(campaign_newtype =="") {
jQuery("#crowdfunding_options").css("border", "1px solid red");
jQuery("#cf_validation").html("<p>' . $check_above_error . '</p>");
return false;
}else {
jQuery("#crowdfunding_options").css("border", "");
}
if(!campaign_title[0]){
jQuery("input#cf_campaign_title").css("border-color", "red");
jQuery("#cf_validation").html("<p>' . $check_above_error . '</p>");
return false;
}else{
jQuery("input#cf_campaign_title").css("border-color", "");
}
if(jQuery("._target_end_selection").val()==="1") {
if(!campaign_endselection[0]) {
jQuery("#_target_end_selection").parent().css("border", "1px solid red");
jQuery("#cf_validation").html("<p>' . $check_above_error . '</p>");
return false;
}else{
jQuery("#_target_end_selection").parent().css("border", "");
}
if(!campaign_duration[0]){
jQuery("input#cf_campaign_duration").css("border-color", "red");
jQuery("#cf_validation").html("<p>' . $check_above_error . '</p>");
return false;
}
else{
jQuery("input#cf_campaign_duration").css("border-color", "");
}
if(!jQuery.isNumeric(campaign_duration[0])){
jQuery("input#cf_campaign_duration").css("border-color", "red");
jQuery("#cf_validation").html("<p>' . $use_number_error . '</p>");
return false;
}
else{
jQuery("input#cf_campaign_duration").css("border-color", "");
}
}
if(!campaign_target_price[0]){
jQuery("input#cf_campaign_target_value").css("border-color", "red");
jQuery("#cf_validation").html("<p>' . $check_above_error . '</p>");
return false;
}else{
jQuery("input#cf_campaign_target_value").css("border-color", "");
}
if(!jQuery.isNumeric(campaign_target_price[0])){
jQuery("input#cf_campaign_target_value").css("border-color", "red");
jQuery("#cf_validation").html("<p>' . $use_number_error . '</p>");
return false;
}
else{
jQuery("input#cf_campaign_target_value").css("border-color", "");
}
if(agree!="yes"){
//alert("testing");
jQuery("#cf_validation").html("<p>' . $select_agree_error . '</p>");
return false;
}
//console.log(campaign_description[0]);
//  if(!campaign_description[0]){
//  jQuery("input#campaign_description").css("border-color","red");
//   jQuery("#cf_validation").html("<p>Please add description for the campaign</p>");
//  return false;
// }else{
//     jQuery("#cf_validation").html("");
//}
jQuery("#cf_validation").html("");
jQuery("#cf_response").html("' . $submitting_text . '");
}


function cf_response(responseText, statusText, xhr, $form) {
//do extra stuff after submit
//alert(statusText);


jQuery("#crowd_form")[0].reset(function() {
jQuery("#crowdfunding_options").prop("selected", function() {
return this.defaultSelected;
});
});

if(statusText=="success") {
if(campaign_success_redirect_url!="") {
window.location = campaign_success_redirect_url;
}
}
//alert(responseText);
//jQuery("#cf_response").html(responseText);
}
});
</script>';
            } else {
                ?> <h4><?php echo __('You can not submit campaign', 'galaxyfunder') ?></h4><?php
            }
        } else {
            $url_to_redirect = get_option("cf_submission_camp_guest_url");
            $newurl_to_redirect = esc_url_raw(add_query_arg('redirect_to', get_permalink(), $url_to_redirect));
            header('Location:' . $newurl_to_redirect);
        }

        $returncontent = ob_get_clean();
        return $returncontent;
    }

    /**
     * Crowdfunding Register Admin Settings Tab
     */
    public static function cf_frontend_submission_tab($settings_tabs) {
        $settings_tabs['frontend'] = __('Frontend Submission', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function cf_enqueue_ajaxform() {
        global $woocommerce;
        wp_enqueue_script('jquery-form', array('jquery'), false, true);
    }

    function testing_order() {
        $args = array(
            'post_type' => 'product',
            'author' => '1',
            'post_status' => 'publish',
        );
//        echo "<pre>";
////        var_dump(get_posts($args));
////        echo "</pre>";
////        var_dump(count(get_posts($args)));
    }

    public static function getcountofactivecampaigns($userid) {
        $mainuserid = $userid == '' ? get_current_user_id() : $userid;
        $args = array(
            'post_type' => 'product',
            'author' => $mainuserid,
            'post_status' => array('draft', 'publish'),
            'posts_per_page' => '-1',
            'meta_value' => 'yes',
            'meta_key' => '_crowdfundingcheckboxvalue'
        );
        $dataofgetposts = get_posts($args);
        $countoftotalcampaigns = count(get_posts($args));
        $listofactivecampaigns = array();

        if (isset($dataofgetposts)) {
            foreach ($dataofgetposts as $eachposts) {
//var_dump($eachposts->ID);
                $mainproduct = new WC_Product($eachposts->ID);
                if ($mainproduct->is_in_stock()) {
                    if (get_post_meta($eachposts->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
                        $listofactivecampaigns[] = $eachposts->ID;
                    }
                }
            }
        }
        return count($listofactivecampaigns);
    }

    public static function selected_products_for_crowdfunding() {
        global $woocommerce;

        if ((float) $woocommerce->version > (float) ('2.2.0')) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="cf_frontend_selected_products"><?php _e('Select Particular Products', 'galaxyfunder'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="hidden" class="wc-product-search" style="width: 100%;" id="cf_frontend_selected_products" name="cf_frontend_selected_products" data-placeholder="<?php _e('Search for a product&hellip;', 'galaxyfunder'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                    $json_ids = array();
                    if (get_option('cf_frontend_selected_products') != "") {
                        $list_of_produts = get_option('cf_frontend_selected_products');
                        if (!is_array($list_of_produts)) {
                            $list_of_produts = explode(',', $list_of_produts);
                            $product_ids = array_filter(array_map('absint', (array) explode(',', get_option('cf_frontend_selected_products'))));
                        } else {
                            $product_ids = $list_of_produts;
                        }
                        if ($product_ids != NULL) {
                            foreach ($product_ids as $product_id) {
                                if (isset($product_id)) {
                                    $product = wc_get_product($product_id);
                                    if (is_object($product)) {
                                        $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                                    }
                                }
                            } echo esc_attr(json_encode($json_ids));
                        }
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" />
                </td>
            </tr>
            <?php
        } else {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="cf_frontend_selected_products"><?php _e('Select Particular Products', 'galaxyfunder'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <select multiple name="cf_frontend_selected_products" style='width:550px;' id='cf_frontend_selected_products' class="cf_frontend_selected_products">
                        <?php
                        if ((array) get_option('cf_frontend_selected_products') != "") {
                            $list_of_produts = (array) get_option('cf_frontend_selected_products');
                            foreach ($list_of_produts as $cf_free_id) {
                                echo '<option value="' . $cf_free_id . '" ';
                                selected(1, 1);
                                echo '>' . ' #' . $cf_free_id . ' &ndash; ' . get_the_title($cf_free_id);
                            }
                        } else {
                            ?>
                            <option value=""></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
        }
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var product_type = jQuery('input:radio[name=cf_frontend_product_selection_type]:checked').val();
                if (product_type == '1') {
                    jQuery("#cf_frontend_selected_products").parent().parent().hide();
                } else {
                    jQuery("#cf_frontend_selected_products").parent().parent().show();
                }
                jQuery('input:radio[name=cf_frontend_product_selection_type]').change(function () {
                    jQuery("#cf_frontend_selected_products").parent().parent().toggle();
                });

            });
        </script>
        <?php
    }

    public static function selected_categories_for_crowdfunding() {
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'frontend') {
                global $woocommerce;
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {

                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery("#cf_frontend_selected_categories").chosen();
                <?php } else { ?>
                            jQuery("#cf_frontend_selected_categories").select2();
                <?php } ?>
                    });

                    jQuery(document).ready(function () {
                        var product_type = jQuery('input:radio[name=cf_frontend_categories_selection_type]:checked').val();
                        if (product_type == '1') {

                            jQuery("#cf_frontend_selected_categories").parent().parent().hide();
                        } else {
                            jQuery("#cf_frontend_selected_categories").parent().parent().show();
                        }
                        jQuery('input:radio[name=cf_frontend_categories_selection_type]').change(function () {
                            jQuery("#cf_frontend_selected_categories").parent().parent().toggle();
                        });
                    });


                </script>
                <?php
            }
        }
    }

    public static function save_selected_products_for_crowdfunding() {
        update_option('cf_frontend_selected_products', $_POST['cf_frontend_selected_products']);
    }

    public static function cf_extension() {
        if (isset($_GET['id'])) {
            if ($_GET['id'] == 1) {
                ?>
                <script type="text/javascript">


                    location.reload();

                </script>


                <?php
            }
        }

        global $woocommerce;
        global $post;
        $target_method = '';
        $targetvalue = '';
        $targetdescription = '';
        $test = 1;
        $date_with_colon = '';
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];

            //echo $product_id;
            $product_link = get_permalink($product_id);
            $target_value_array = get_post_meta($product_id, '_crowdfundinggettargetprice');
            $target_description_array = get_post_meta($product_id, '_crowdfundinggetdescription');
//            var_dump($target_description);

            $target_date = get_post_meta($product_id, '_crowdfundingtodatepicker');
            $targethour = get_post_meta($product_id, '_crowdfundingtohourdatepicker');
            $targetminutes = get_post_meta($product_id, '_crowdfundingtominutesdatepicker');
            foreach ($target_date as $target_date_value) {
                if ($target_date_value == '') {
                    $target_date_show = '';
                } else {
                    $target_date_value = $target_date_value;
                }
            }
            foreach ($target_value_array as $a) {
                $targetvalue = $a;
            }
            foreach ($target_description_array as $desc) {
                $targetdescription = $desc;
            }
            $target_method_array = get_post_meta($product_id, '_target_end_selection');
            foreach ($target_method_array as $target_method_id) {
                // var_dump($target_method_id);

                if ($target_method_id == 3) {

                    $target_method = "Target Goal";
                } elseif ($target_method_id == 2) {
                    $target_method = "Campaign Never Ends";
                } else {
                    $target_method = 'Target Date';
                    foreach ($target_date as $date) {

                        foreach ($targethour as $hour) {
                            foreach ($targetminutes as $minutes) {

                                $date_with_colon = ' : ' . $date . ' ' . $hour . ' : ' . $minutes;
                            }
                        }
                    }
                }
                // echo $target_method;
            }
        }
        ob_start();
        $newarray = '';
        if (is_user_logged_in()) {
            $settings = array("teeny" => true, 'media_buttons' => false, 'quicktags' => false, 'textarea_name' => 'campaign_description');
            echo '<style type="text/css">
            ' . get_option('cf_submission_camp_custom_css') . '
            </style>';

            $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'meta_value' => 'yes', 'meta_key' => '_crowdfundingcheckboxvalue');
            $getproducts = get_posts($args);
            ?>
            <form id="campaign_extension_form" class="campaign_extension_form" action="">
                <fieldset   style="border:1px solid black;padding:10px";>
                    <div id="campaign_options1"><p><label id="targetmethodextension "><h3></h3></label></p>

                        <p style="font-size:24px;" id="targetmethodexist"><h3>Target<h3><?php
                                echo $target_method;
                                echo $date_with_colon;
                                ?> </p>

                                <input type="hidden" id="ajax_hidden_value" value="<?php echo $product_id ?>">
                                <input type="hidden" id="hidden_target_value" value="<?php echo $targetvalue ?>">
                                <input type="hidden" id="hidden_target_description" value="<?php echo $targetdescription ?>">
                                <input type="hidden" id="hidden_target_method" value="<?php echo $target_method ?>">


                                <div id="campaign_options1"><p><label><h3><?php _e(' New Campaign End Method', 'galaxyfunder'); ?></h3></label></p>
                                    <p><select name="_target_end_selection" id="_target_end_selection_extension" class="_target_end_selection">

                                            <option class="target_selection_3" value="3" ><?php _e('Target Goal', 'galaxyfunder'); ?></option>
                                            <option class="target_selection_1" value="1"><?php _e('Target Date', 'galaxyfunder'); ?></option>
                                            <option class="target_selection_2" value="2"><?php _e('Campaign Never Ends', 'galaxyfunder'); ?></option>
                                        </select></p></div>
                                <?php
                                $getdate = date("m/d/Y");
                                $time = date("h");
                                $minutes = date("i");
                                ?>
                                <span class="cf_newcampaign_duration"><p><label><h3><?php _e('Campaign Extension Date', 'galaxyfunder'); ?></h3></label></p><p><input type="text" id="cf_campaign_duration_extension" name="crowdfunding_duration" value="" placeholder="<?php echo $getdate ?>">
                                        <input type="text" id="cf_campaign_hour_duration_extension" maxlength="2" name="crowdfunding_hour_duration" value="" placeholder="<?php echo $time ?>"><?php echo ":" ?>
                                        <input type="text" id="cf_campaign_minutes_duration_extension" maxlength="2" name="crowdfunding_minutes_duration" value="" placeholder="<?php echo $minutes ?>"></p></span></div>
                                <div class="cf_newcampaign_targetprice_exist"><p><label><h3><?php _e('Existing Goal Price:', 'galaxyfunder') . '(' . get_woocommerce_currency_symbol() . ')'; ?></h3></label></p><p><p id="target_value_exist" style="font-size:24px;"><?php echo $targetvalue ?></p>
                                    <div class="cf_newcampaign_targetprice"><p><label><h3><?php _e('New' . get_option('cf_submission_camp_targetprice_label') . ' Price(' . get_woocommerce_currency_symbol() . ')') ?></h3></label></p><p><input type="text" id="cf_campaign_target_value_exist" name="cf_target_value" value="" Placeholder="<?php echo get_option("cf_submission_camp_targetprice_placeholder"); ?>"></p></div>
                                    <div class="cf_newcampaign_description"><p><label><h3><?php _e('Description', 'galaxyfunder'); ?></h3></label></p><p><textarea id="cf_campaign_target_description" name="cf_target_description"><?php echo $targetdescription ?></textarea> </p></div>
                                                


                                                <div class="cf_campaign_extension_submit" style="margin-left:220px;
                                                     ">
                                                    <p >
                                                        <input id="submit_extension" type="button" data-attemptcount="1" value="<?php _e('Submit', 'galaxyfunder'); ?>" name="submit_extension">
                                                    </p>
                                                    <h3  id="div_error" style="margin-right:190px; font-size: 15px;  width: 200px;"> </h3>
                                                </div>
                                        </fieldset>
                                    </form>
                                        <style type="text/css">
                                        #cf_campaign_duration_extension{
                                            width: 150px;
                                            margin-right:5px; 
                                        }
                                        #cf_campaign_hour_duration_extension,#cf_campaign_minutes_duration_extension{
                                            width: 40px;
                                            margin-right:5px; 
                                        }
                                            </style>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function () {
                                            var requestattempt = 0;

                                            jQuery("#submit_extension").click(function () {
                                                var test = '';

                                                var extensiongoal = jQuery('#cf_campaign_target_value_exist').val();
                                                // alert(extensiongoal);
                                                var extensionendmethod = jQuery('#_target_end_selection_extension').val();
                                                var extensiondate = jQuery('#cf_campaign_duration_extension').val();

                                                //alert(extensionendmethod);
                                                if (extensionendmethod == 2 || extensionendmethod == 3)
                                                {
                                                    test = ''
                                                }

                                                if (extensionendmethod == 1)
                                                {
                                                    var extensiondate = jQuery('#cf_campaign_duration_extension').val();
                                                    //alert(extensiondate);
                                                    if (extensiondate == '')
                                                    {
                                                        test = '25';
                                                    }
                                                    else
                                                    {
                                                        test = '';
                                                    }
                                                }

                                                if (extensiongoal != '' && test == '')
                                                {
                                                    if (requestattempt == 0)
                                                    {
                                                        var extensionendmethod = jQuery('#_target_end_selection_extension').val();
                                                        var extensiondate = jQuery('#cf_campaign_duration_extension').val();
                                                        var extensionhour = jQuery('#cf_campaign_hour_duration_extension').val();
                                                        var extensionminutes = jQuery('#cf_campaign_minutes_duration_extension').val();
                                                        var extensiondescription = jQuery('#cf_campaign_target_description').val();
                                                        var productid = jQuery('#ajax_hidden_value').val();
                                                        var targetmethodexist = jQuery('#hidden_target_method').val();
                                                        var target_value_exist = jQuery('#hidden_target_value').val();
                                                        var attemptcount = jQuery(this).data('attemptcount');
                                                        var dataparam = ({
                                                            action: 'updatecontribution',
                                                            targetmethodexist: targetmethodexist,
                                                            extensionendmethod: extensionendmethod,
                                                            extensiondate: extensiondate,
                                                            target_value_exist: target_value_exist,
                                                            extensiongoal: extensiongoal,
                                                            extensiondescription: extensiondescription,
                                                            productid: productid,
                                                            extensionhour: extensionhour,
                                                            extensionminutes: extensionminutes
                                                        });
                                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                                function (response) {
                                                                    //alert(response);
                                                                    if (response == 1)
                                                                    {

                                                                        requestattempt++;
                                                                        jQuery('#div_error').html("submitted");
                                                                        // jQuery(this).data('attemptcount', {2});

                                                                        //alert(attemptcount);
                                                                        document.getElementById('campaign_extension_form').reset();
                                                                        document.getElementById('div_error').style.visibility = "hidden";
                                                                        // jQuery(this).prop("disabled", true);
                                                                        //delay:50000000000;
                                                                        //window.location.replace("<?php echo $product_link ?>");

                                                                        //                                            jQuery("#_target_end_selection_extension").prop("selected", function () {
                                                                        //                                                return this.defaultSelected;
                                                                        //                                            });
                                                                        //('#campaign_extension_form').reset(true);
                                                                    }
                                                                    var newresponse = response.replace(/\s/g, '');
                                                                    if (newresponse === 'success') {
                                                                    }
                                                                });
                                                        //window.location.replace("<?php echo $product_link ?>");
                                                        jQuery('#div_error').html("submitting");
                                                        // delay(5);

                                                        // alert(test);
                                                    }
                                                    if (requestattempt > 0)
                                                    {
                                                        jQuery('#div_error').html("Request Sent Already");
                                                    }


                                                }
                                                else
                                                {
                                                    // jQuery('#cf_campaign_target_value_exist').css("border-color", "red");
                                                    jQuery('#div_error').html("Please Fill All Fields");
                                                }
                                            });


                                        });</script>
                                    <script type = "text/javascript" >
                                        jQuery(document).ready(function () {
            <?php
            if (get_option('cf_show_hide_crowdfunding_type') == '2') {
                if (get_option('cf_crowdfunding_type_selection') == '2') {
                    ?>
                                                    jQuery('.cf_newcampaign_title').show();
                                                    jQuery('#campaign_options2').show();
                                                    jQuery('.cf_product_choosing').show();
                                                    jQuery('#campaign_options1').hide();
                                                    jQuery('.cf_newcampaign_duration').hide();
                                                    jQuery('.cf_newcampaign_targetprice').show();
                                                    jQuery('.cf_newcampaign_minimumprice').hide();
                                                    jQuery('.cf_newcampaign_maximumprice').hide();
                                                    jQuery('.cf_newcampaign_recommendedprice').hide();
                                                    jQuery('.cf_newcampaign_description').show();
                                                    jQuery('#meta_inner').show();
                                                    jQuery('.cf_newcampaign_featured').show();
                                                    //
                                                    jQuery('.cf_newcampaign_billinginfo').show();
                                                    jQuery('.cf_newcampaign_shippinginfo').show();
                                                    jQuery('#billing_country').show();
                                                    jQuery('#shipping_country').show();
                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                                        jQuery('#billing_country').chosen();
                                                        jQuery('#shipping_country').chosen();
                    <?php } else { ?>
                                                        jQuery('#billing_country').select2();
                                                        jQuery('#shipping_country').select2();
                    <?php } ?>
                                                    //jQuery('.cf_newcampaign_shippinginfo').hide();
                                                    jQuery('.cf_newcampaign_submit').show();
                                                    jQuery('#cf_campaign_target_value').attr('readonly', true);
                <?php } if (get_option('cf_crowdfunding_type_selection') == '1') { ?>
                                                    jQuery('.cf_newcampaign_title').show();
                                                    jQuery('#campaign_options1').show();
                                                    jQuery('.cf_product_choosing').hide();
                                                    jQuery('#campaign_options2').hide();
                                                    jQuery('.cf_newcampaign_duration').hide();
                                                    jQuery('.cf_newcampaign_targetprice').show();
                                                    jQuery('.cf_newcampaign_minimumprice').show();
                                                    jQuery('.cf_newcampaign_maximumprice').show();
                                                    jQuery('.cf_newcampaign_recommendedprice').show();
                                                    jQuery('.cf_newcampaign_description').show();
                                                    jQuery('#meta_inner').show();
                                                    jQuery('.cf_newcampaign_featured').show();
                                                    jQuery('.cf_newcampaign_billinginfo').hide();
                                                    jQuery('.cf_newcampaign_shippinginfo').hide();
                                                    jQuery('.cf_newcampaign_submit').show();
                                                    jQuery('#cf_campaign_target_value').attr('readonly', false);
                    <?php
                }
            }
            ?>
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                                jQuery('#cf_product_selection').chosen();
                                                jQuery('#crowdfunding_options').chosen();
                                                jQuery('._target_end_selection').chosen();
            <?php } else { ?>
                                                jQuery('#cf_product_selection').select2();
                                                jQuery('#crowdfunding_options').select2();
                                                jQuery('._target_end_selection').select2();
            <?php } ?>
            <?php if (get_option('cf_show_hide_crowdfunding_type') == '1') { ?>
                                                //jQuery('.maindivcf').hide();
                                                jQuery('.cf_newcampaign_title').show();
                                                jQuery('#campaign_options1').show();
                                                jQuery('.cf_product_choosing').hide();
                                                jQuery('#campaign_options2').hide();
                                                jQuery('.cf_newcampaign_duration').hide();
                                                jQuery('.cf_newcampaign_targetprice').show();
                                                jQuery('.cf_newcampaign_minimumprice').show();
                                                jQuery('.cf_newcampaign_maximumprice').show();
                                                jQuery('.cf_newcampaign_recommendedprice').show();
                                                jQuery('.cf_newcampaign_description').show();
                                                jQuery('#meta_inner').show();
                                                jQuery('.cf_newcampaign_featured').show();
                                                jQuery('.cf_newcampaign_billinginfo').hide();
                                                jQuery('.cf_newcampaign_shippinginfo').hide();
                                                jQuery('.cf_newcampaign_submit').show();
                                                jQuery('#cf_campaign_target_value').attr('readonly', false);
            <?php } ?>
                                            // jQuery('#billing_country').chosen();
                                            //jQuery('#shipping_country').chosen();
                                            //jQuery('#cf_product_selection').attr('multiple', '');
                                            jQuery('#crowdfunding_options').change(function (e) {
                                                jQuery('.maindivcf').show();
                                                var updatevalue = jQuery(this).val();
                                                //alert(updatevalue);
                                                if (updatevalue === '2') {
                                                    jQuery('.cf_newcampaign_title').show();
                                                    jQuery('#campaign_options2').show();
                                                    jQuery('.cf_product_choosing').show();
                                                    jQuery('#campaign_options1').hide();
                                                    jQuery('.cf_newcampaign_duration').hide();
            <?php if (get_option("cf_show_hide_target_product_purchase_frontend") == '1') { ?>
                                                        jQuery('.cf_newcampaign_targetprice').show();
            <?php } else { ?>
                                                        jQuery('.cf_newcampaign_targetprice').hide();
            <?php } ?>
                                                    jQuery('.cf_newcampaign_minimumprice').hide();
                                                    jQuery('.cf_newcampaign_maximumprice').hide();
                                                    jQuery('.cf_newcampaign_recommendedprice').hide();
                                                    jQuery('.cf_newcampaign_description').show();
                                                    jQuery('#meta_inner').show();
                                                    jQuery('.cf_newcampaign_featured').show();
                                                    //
                                                    jQuery('.cf_newcampaign_billinginfo').show();
                                                    jQuery('.cf_newcampaign_shippinginfo').show();
                                                    jQuery('#billing_country').show();
                                                    jQuery('#shipping_country').show();
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                                        jQuery('#billing_country').chosen();
                                                        jQuery('#shipping_country').chosen();
            <?php } else { ?>
                                                        jQuery('#billing_country').select2();
                                                        jQuery('#shipping_country').select2();
            <?php } ?>
                                                    //jQuery('.cf_newcampaign_shippinginfo').hide();
                                                    jQuery('.cf_newcampaign_submit').show();
                                                    jQuery('#cf_campaign_target_value').attr('readonly', true);
                                                } else {

                                                    jQuery('.cf_newcampaign_title').show();
                                                    jQuery('#campaign_options1').show();
                                                    jQuery('.cf_product_choosing').hide();
                                                    jQuery('#campaign_options2').hide();
                                                    jQuery('.cf_newcampaign_duration').hide();
                                                    jQuery('.cf_newcampaign_targetprice').show();
                                                    jQuery('.cf_newcampaign_minimumprice').show();
                                                    jQuery('.cf_newcampaign_maximumprice').show();
                                                    jQuery('.cf_newcampaign_recommendedprice').show();
                                                    jQuery('.cf_newcampaign_description').show();
                                                    jQuery('#meta_inner').show();
                                                    jQuery('.cf_newcampaign_featured').show();
                                                    jQuery('.cf_newcampaign_billinginfo').hide();
                                                    jQuery('.cf_newcampaign_shippinginfo').hide();
                                                    jQuery('.cf_newcampaign_submit').show();
                                                    jQuery('#cf_campaign_target_value').attr('readonly', false);
                                                }

                                            });
                                            jQuery('._target_end_selection').change(function (e) {
                                                var currentvalue = jQuery(this).val();
                                                if (currentvalue === '1') {
                                                    jQuery('.cf_newcampaign_duration').show();
                                                } else {
                                                    jQuery('.cf_newcampaign_duration').hide();
                                                }
                                            });
                                            jQuery('#cf_product_selection').change(function () {
                                                // var thisvalue = jQuery('option:selected', this).attr('data-price');
                                                var thisvalue = 0;
                                                jQuery('#cf_product_selection > option:selected').each(function () {
                                                    var value = jQuery(this).attr('data-price');
                                                    thisvalue = parseFloat(thisvalue) + parseFloat(value);
                                                    thisvalue = thisvalue.toFixed(2);
                                                });
                                                jQuery("#cf_campaign_target_value").val(thisvalue);
                                            });
                                        });
                                        jQuery(document).ready(function () {
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                ?>
                                                jQuery('.cf_newcampaign_choose_category').chosen();
            <?php } else { ?>
                                                jQuery('.cf_newcampaign_choose_category').select2();
            <?php } ?>
                                            jQuery(".cf_newcampaign_mark_contributors_anonymous").hide();
                                            jQuery("#cf_newcampaign_show_hide_contributors").change(function () {
                                                jQuery(".cf_newcampaign_mark_contributors_anonymous").toggle();
                                            });
                                        });
                                        jQuery(document).ready(function () {
                                            jQuery(".cf_newcampaign_social_sharing_facebook").hide();
                                            jQuery(".cf_newcampaign_social_sharing_twitter").hide();
                                            jQuery(".cf_newcampaign_social_sharing_google").hide();
                                            jQuery("#cf_newcampaign_social_sharing").change(function () {
                                                jQuery(".cf_newcampaign_social_sharing_facebook").toggle();
                                                jQuery(".cf_newcampaign_social_sharing_twitter").toggle();
                                                jQuery(".cf_newcampaign_social_sharing_google").toggle();
                                            });
                                        });</script>
                                    <script type="text/javascript">
            <?php
            if (get_option('cf_campiagn_success_redirection_option') == '2') {
                ?>
                                            var campaign_success_redirect_url = "<?php echo get_option("cf_campiagn_success_redirection_url_content") ?>";
            <?php } else {
                ?>
                                            var campaign_success_redirect_url = '';
            <?php }
            ?>
                                                </script>
                                    <?php
                                    '
function cf_response(responseText, statusText, xhr, $form) {
//do extra stuff after submit
//alert(statusText);
jQuery("#crowd_form")[0].reset(function() {
jQuery("#crowdfunding_options").prop("selected", function() {
return this.defaultSelected;
});
});

if(statusText=="success") {
if(campaign_success_redirect_url!="") {
window.location = campaign_success_redirect_url;
}
}
//alert(responseText);
//jQuery("#cf_response").html(responseText);
}
});
</script>';
                                } else {
                                    $url_to_redirect = get_option("cf_submission_camp_guest_url");
                                    $newurl_to_redirect = esc_url_raw(add_query_arg('redirect_to', get_permalink(), $url_to_redirect));
                                    header('Location:' . $newurl_to_redirect);
                                }

                                $returncontent = ob_get_clean();
                                return $returncontent;
                            }

                            public static function ajax_callback() {

                                $exist_goal = $_POST['target_value_exist'];
                                $exist_end_method = $_POST['targetmethodexist'];


                                $new_goal = $_POST['extensiongoal'];
                                //echo $new_goal;

                                $new_description = $_POST['extensiondescription'];

                                $new_end_method = $_POST['extensionendmethod'];
                                //echo $new_end_method;
                                $extensiondate = $_POST['extensiondate'];
                                //echo $extensiondate;
                                $extensionhour = $_POST['extensionhour'];
                                $extensionminutes = $_POST['extensionminutes'];
                                $productid = $_POST['productid'];
                                //echo $productid;
                                $ajaxrequest_array = array(
                                    "productid" => $productid,
                                    "existendmethod" => $exist_end_method,
                                    "newendmethod" => "$new_end_method",
                                    "existgoal" => $exist_goal,
                                    "newgoal" => "$new_goal",
                                    "newdescription" => "$new_description",
                                    "newdate" => "$extensiondate",
                                    "newhour" => $extensionhour,
                                    "newminutes" => $extensionminutes,
                                );


                                $list_of_campaign_modification = array($ajaxrequest_array);

                                $prev_array = get_option('campaign_modification_list');
                                //var_dump($prev_array);
                                if ($prev_array == '') {

                                    update_option('campaign_modification_list', $list_of_campaign_modification);
                                } else {
                                    $updated_array = array_merge($prev_array, $list_of_campaign_modification);
                                    update_option('campaign_modification_list', $updated_array);
                                    //var_dump($updated_array);
                                }
                                echo 1;
                                exit();
//        var_dump($list_of_campaign_modification);
                            }

                            public static function array_check() {

                                $current_price = get_post_meta(2174, '_crowdfundingtotalprice', true);
                                echo $current_price;
//        $test = get_option('list_of_campaign_modification');
//        if ($test == '') {
//            echo 'empty';
//        } else {
//            echo "have value";
//        }
//        $Path = the_permalink();
//        echo $Path . '&productid=2167';
//        echo "<pre>";
//        var_dump($test);
//        echo "</pre>                                  ";
                            }

                        }

                        /**
                         * Adding the setting tab
                         */
                        add_filter('woocommerce_cf_settings_tabs_array', array('CFFrontendSubmission', 'cf_frontend_submission_tab'), 101);
                        add_action('woocommerce_cf_settings_tabs_frontend', array('CFFrontendSubmission', 'cf_admin_front_end_settings'));
                        add_action('woocommerce_update_options_frontend', array('CFFrontendSubmission', 'cf_admin_front_end_update_settings'));
                        add_action('init', array('CFFrontendSubmission', 'cf_frontend_default_values'));
                        add_action('admin_init', array('CFFrontendSubmission', 'cf_frontend_reset_values'), 1);
                        add_action('wp_head', array('CFFrontendSubmission', 'enqueue_script_form'));
                        add_action('woocommerce_admin_field_selectedproducts_campaign', array('CFFrontendSubmission', 'selected_products_for_crowdfunding'));
                        add_action('woocommerce_update_option_selectedproducts_campaign', array('CFFrontendSubmission', 'save_selected_products_for_crowdfunding'));
                        add_action('admin_head', array('CFFrontendSubmission', 'selected_categories_for_crowdfunding'));
                        /*
                         * creating campaign
                         */
                        add_action('wp_ajax_crowdfunding', array('CFFrontendSubmission', 'adding_campaign'));
                        add_action('wp_ajax_nopriv_crowdfunding', array('CFFrontendSubmission', 'adding_campaign'));

                        /*
                         * shortcode for form
                         */

                        add_shortcode('crowd_fund_form', array('CFFrontendSubmission', 'shortcode_crowdfunding_form'));
                        add_shortcode('crowd_fund_extension', array('CFFrontendSubmission', 'cf_extension'));

                        add_action('wp_ajax_nopriv_updatecontribution', array('CFFrontendSubmission', 'ajax_callback'));
                        add_action('wp_ajax_updatecontribution', array('CFFrontendSubmission', 'ajax_callback'));

                        /*
                         * Adding ajax form script
                         */
                        add_action('wp_enqueue_scripts', array('CFFrontendSubmission', 'cf_enqueue_ajaxform'));
                        new CFFrontendSubmission();
                        