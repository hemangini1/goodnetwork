<?php

class CFShortcodeGenerator {

    public static function crowdfunding_admin_shortcode_tab($settings_tabs) {
        $settings_tabs['crowdfunding_shortcode'] = __('Shortcode Generator', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_shortcode_admin_options() {
        $newarray = '';
        $output = '';
        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'meta_value' => 'yes', 'meta_key' => '_crowdfundingcheckboxvalue');
        $getproducts = get_posts($args);
        foreach ($getproducts as $product) {
            if (get_post_meta($product->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
                $newarray[] = $product->ID;
                $producttitle[] = $product->post_title;
            }
        }
        if (is_array($newarray) && (is_array($producttitle))) {
            $output = array_combine($newarray, $producttitle);
        }
        return apply_filters('woocommerce_crowdfunding_shortcode_settings', array(
            array(
                'name' => __('Shortcode Label', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_product_button_text_shortcode'
            ),
            array(
                'name' => __('Minimum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Minimum Contribution Label for Product Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'crowdfunding_min_price_shop_page_shortcode',
                'css' => 'min-width:550px;',
                'std' => 'Minimum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_min_price_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_maximum_price_shop_page_shortcode',
                'std' => 'Maximum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_maximum_price_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Goal Label', 'galaxyfunder'),
                'desc' => __('Please Enter Goal Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_target_price_shop_page_shortcode',
                'std' => 'Goal',
                'type' => 'text',
                'newids' => 'crowdfunding_target_price_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_label_shop_page_shortcode',
                'std' => 'Raised',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_label_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Percent Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Percent Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_percent_label_shop_page_shortcode',
                'std' => 'Percent',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_percent_label_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable Title in Shortcode', 'galaxyfunder'),
                'desc' => __('Enable this Option to show the Title in Shortcode', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_enable_title_for_shortcode',
                'std' => 'yes',
                'type' => 'checkbox',
                'newids' => 'crowdfunding_enable_title_for_shortcode',
            ),
            array(
                'name' => __('Enable Description in Shortcode', 'galaxyfunder'),
                'desc' => __('Enable this Option to show the Description in Shortcode', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_enable_description_for_shortcode',
                'std' => 'yes',
                'type' => 'checkbox',
                'newids' => 'crowdfunding_enable_description_for_shortcode',
            ),
            array(
                'name' => __('Enter Number of words to Trim from  Description', 'galaxyfunder'),
                'desc' => __('Enter Number of words to trim from description of product page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;margin-bottom:80px;',
                'id' => 'crowdfunding_number_of_words_to_trim',
                'std' => '10',
                'type' => 'text',
                'newids' => 'crowdfunding_number_of_words_to_trim',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_button_text_shortcode'),
            array(
                'name' => __('Choose Inbuilt/Custom Design', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_product_inbuilt_text_shortcode'
            ),
            array(
                'name' => __('Inbuilt Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Inbuilt Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_shop_design_shortcode',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Inbuilt Design'),
                'newids' => 'cf_inbuilt_shop_design_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select Inbuilt Design', 'galaxyfunder'),
                'desc' => __('This helps to load the inbuilt type', 'galaxyfunder'),
                'id' => 'load_inbuilt_shop_design_shortcode',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'load_inbuilt_shop_design_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Minimal Style', 'galaxyfunder'),
                    '2' => __('IGG Style', 'galaxyfunder'),
                    '3' => __('KS Style', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Select Progress Bar Style', 'galaxyfunder'),
                'desc' => __('This helps to select the Progress bar Style', 'galaxyfunder'),
                'id' => 'shortcode_page_prog_bar_type',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'shortcode_page_prog_bar_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Style 1', 'galaxyfunder'),
                    '2' => __('Style 2', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Campaign Amount Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Target Price in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_raised_amount_show_hide_shortcode',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_amount_show_hide_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Campaign Percentage Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Raised Percentage in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_raised_percentage_show_hide_shortcode',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_percentage_show_hide_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Target Days Left Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Days Left in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_day_left_show_hide_shortcode',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_day_left_show_hide_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Number of Funders Details Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Funders count in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_funders_count_show_hide_shortcode',
                'css' => 'min-width:150px;margin-bottom:40px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_funders_count_show_hide_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Inbuilt CSS (Non Editable)', 'galaxyfunder'),
                'desc' => __('These are element IDs in the Shop Page ', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_shop_page_contribution_table_default_css_shortcode',
                'std' => '#cf_min_price_label { display:none;
 }
 #cf_total_raised_in_percentage { display:none; }
 #cf_total_price_raiser {display:none;}
 #cf_days_remainings {display:none;}
 #cf_max_price_label { display:none; }
 #cf_target_price_label { display:none; }
  #cf_total_price_raised {display:none;}
 #cf_total_price_in_percentage_with_bar {display:none;}
 #cf_total_price_in_percentage {display:none;}  #single_product_contribution_table{
 }
#cf_serial_number_label{
 }
#cf_contributor_label{
 }
#cf_contributor_email_label{
 }
#cf_contribution_label{
 }
#cf_date_label{
 }
#serial_id{
 }
#cf_billing_first_name{
 }
 #cf_billing_email{
 }
 #cf_order_total{
 }
#cf_target_price_labels{ margin-bottom:0px;
 }
#cf_total_price_raise{ float:left;
 }
#cf_total_price_raise span {font-size:17px;
 }
#cf_total_price_in_percent{
 }
#cf_total_price_in_percent_with_bar{width: 100%;
 height:12px;
 background-color: #ffffff;
 border-radius:10px;
 border:1px solid #000000;
 clear:both;
 }
 #cf_percent_bar{ height:10px;
 border-radius:10px;
 background-color: green;
 }
 #cf_price_new_date_remain small { font-style:italic;  }
 #cf_price_new_date_remain { margin-bottom:0px;
 float:left; }
 #singleproductinputfieldcrowdfunding{color:green;
 }
 #cf_target_price_labels {font-style:italic;
 font-size:20px;
 }
 #cf_update_total_funders {margin-bottom:0px; float:right; }
 #cf_total_raised_percentage {float:right;
 font-size:16px !important;
 margin-bottom:0px;
}
',
                'type' => 'textarea',
                'newids' => 'cf_shop_page_contribution_table_default_css_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Custom Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_shop_design_shortcode',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('2' => __('Custom Design', 'galaxyfunder')),
                'newids' => 'cf_inbuilt_shop_design_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom CSS', 'galaxyfunder'),
                'desc' => __('Customize the following element IDs of Frontend Campaign Submission form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_shop_page_contribution_table_custom_css_shortcode',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_shop_page_contribution_table_custom_css_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select Campaigns', 'galaxyfunder'),
                'desc' => __('Select your Campaign in the List Box', 'galaxyfunder'),
                'id' => 'load_inbuilt_shortcode_generators',
                'css' => 'min-width:550px;',
                'class' => 'chosen-style',
                'std' => '', // WooCommerce < 2.0
                'default' => '', // WooCommerce >= 2.0
                'newids' => 'load_inbuilt_shortcode_generators',
                'type' => 'multiselect',
                'options' => $output,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_inbuilt_text_shortcode'),
            array(
                'name' => __('Shortcode', 'galaxyfunder'),
                'type' => 'title',
                'class' => 'newh1tag',
                'desc' => '<pre id="hiddenpro" style="display:none;"></pre><pre id="productidshortcode"></pre>',
                'id' => '_product_generated_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_generated_shortcode'),
            array(
                'name' => __('Use the Shortcode [galaxyfunder_my_campaign] for displaying the Campaigns created by the User', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_my_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_my_campaign_shortcode'),
            array(
                'name' => __('Use the Shortcode [galaxyfunder_all_campaign_list] for displaying the Campaigns Created by all Members', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_all_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_all_campaign_shortcode'),
            array(
                'name' => __('Use the Shortcode [gf_funders_table_for_campaign] for displaying the Funders table in Campaign Page', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_funders_of_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_funders_of_campaign_shortcode'),
        ));
    }

    public static function crowdfunding_process_shortcode_admin_settings() {
        woocommerce_admin_fields(CFShortcodeGenerator::crowdfunding_shortcode_admin_options());
    }

    public static function crowdfunding_process_shortcode_update_settings() {
        woocommerce_update_options(CFShortcodeGenerator::crowdfunding_shortcode_admin_options());
    }

    public static function crowdfunding_shortcode_default_settings() {
        global $woocommerce;
        foreach (CFShortcodeGenerator::crowdfunding_shortcode_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_shortcode_reset_values() {
        global $woocommerce;
        if (isset($_POST['reset'])) {
            foreach (CFShortcodeGenerator::crowdfunding_shortcode_admin_options() as $setting)
                if (isset($setting['newids']) && ($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
        }
    }

    public static function cf_shortcode_extract_code($atts) {
        if (!is_shop()) {
            $content = '';
            extract(shortcode_atts(array(
                'id' => '',
                            ), $atts));
            ob_start();

            $timezone = wc_timezone_string();
            if ($timezone != '') {
                $timezonedate = date_default_timezone_set($timezone);
            } else {
                $timezonedate = date_default_timezone_set('UTC');
            }
            $order_total = get_post_meta($id, '_crowdfundingtotalprice', true);
            $getdate = date("m/d/Y");
            $gethour = date("h");
            $getminutes = date("i");
            $fromdate = get_post_meta($id, '_crowdfundingfromdatepicker', true);
            $todate = get_post_meta($id, '_crowdfundingtodatepicker', true);
            $tohours = get_post_meta($id, '_crowdfundingtohourdatepicker', true);
            $tominutes = get_post_meta($id, '_crowdfundingtominutesdatepicker', true);
            $fromhours = get_post_meta($id, '_crowdfundingfromhourdatepicker', true);
            $fromminutes = get_post_meta($id, '_crowdfundingfromminutesdatepicker', true);
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
                update_post_meta($id, '_crowdfundingfromdatepicker', $getdate);
                update_post_meta($id, '_crowdfundingfromhourdatepicker', $gethour);
                update_post_meta($id, '_crowdfundingfromminutesdatepicker', $getminutes);
            }
//  if ($todate != '') {
            if ($tohours != '' || $tominutes != '') {
                $time = $tohours . ':' . $tominutes . ':' . '00';
                $datestr = $todate . $time; //Your date
            } else {
                $datestr = $todate . "23:59:59";
            } //Your date
            $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference
            $content_post = get_post($id);
            if (isset($content_post->post_content)) {
                $content = $content_post->post_content;
            }
            if (get_option('crowdfunding_enable_title_for_shortcode') == 'yes') {
                $enabletitle = '<h3>' . get_the_title($id) . '</h3>';
            }
            if (get_option('crowdfunding_enable_description_for_shortcode') == 'yes') {
                $enabledescription = '<p style="margin-bottom:10px;">' . wp_trim_words($content, get_option('crowdfunding_number_of_words_to_trim')) . '</p>';
            }
            $payyourpricelabel = '';
            $count = '';
            $count2 = '';
            $counter = '';
            $totalfield = '';
            $totalfieldss = '';
            $totalfielder = '';
            $inbuilt_designs = '';
            $totalpledgedpercentage = '';
            $totalpledgedpercentages = '';
            $checktotalfunders = get_post_meta($id, '_update_total_funders', true);
            if ($checktotalfunders != '') {
                $gettotalfunders = '<span  id="cf_get_total_funders" class="cf_price" style="float:right">' . get_post_meta($id, '_update_total_funders', true) . '<small> ' . get_option('cf_funder_label') . '</small> </span>';
            } else {
                $gettotalfunders = '<span  id="cf_get_total_funders" class="cf_price"  style="float:right"> 0 <small>' . get_option('cf_funder_label') . '</small> </span>';
            }
            $datestr = $todate . " 23:59:59"; //Your date
            $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference
            if ($tohours != '' || $tominutes != '') {
                $time = $tohours . ':' . $tominutes . ':' . '00';
                $datestr = $todate . $time; //Your date
            } else {
                $datestr = $todate . "23:59:59";
            } //Your date
            $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference
            if ($date >= time()) {
                $diff = $date - time();
                $day = ceil($diff / 86400);
                $days = floor($diff / 86400);
                $hours = $diff / 3600 % 24;
                $minutes = $diff / 60 % 60;
                $sec = $diff % 60;

//                            $diff = $date - time(); //time returns current time in seconds
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
                        $new_date_remain = '<span  id="cf_days_remaining" class="cf_price" style="float:left">' . $day . 'days' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                    } else {
                        $new_date_remain = '<span  id="cf_days_remaining" class="cf_price" style="float:left">' . $day . ' day ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                    }
                } elseif (get_option('cf_campaign_day_time_display') == '2') {
                    if ($days > 1) {
                        $new_date_remain = '<span  id="cf_days_remaining" class="cf_price" style="float:left">' . $days . ' days ' . $hour . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                    } else {
                        $new_date_remain = '<span  id="cf_days_remaining" class="cf_price" style="float:left">' . $days . ' day ' . $hour . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                    }
                } else {
                    if ($days > 1) {
                        $new_date_remain = '<span  id="cf_days_remaining" class="cf_price" style="float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                    } else {
                        $new_date_remain = '<span  id="cf_days_remaining" class="cf_price" style="float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                    }
                }
            } else {
                $new_date_remain = '<span  style = "float:left"> 0 ' . __('day Left', 'galaxyfunder') . '</span>';
            }
            ?>
            <!--            <style type="text/css">
                            .cf_price {
                                color:#85AD74;
                                display:block;
                                font-weight:400;
                                margin-bottom:0.5em;
                            }
                        </style>-->
            <?php
            $inbuilt_designs = get_option("cf_inbuilt_shop_design_shortcode");
            $load_designs = get_option('load_inbuilt_shop_design_shortcode');
            if ($inbuilt_designs == '1') {
                if ($load_designs == '1') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                    </style>
                    <?php
                }
                if ($load_designs == '2') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                    </style>
                    <?php
                }
                if ($load_designs == '3') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                    </style>
                    <?php
                }
            }
            if ($inbuilt_designs == '2') {
                ?>
                <style type="text/css">
                <?php echo get_option('cf_shop_page_contribution_table_custom_css_shortcode'); ?>
                </style>

                <?php
            }
            ?>
            <?php
            $products = get_product($id);
            $checkvalue = get_post_meta($id, '_crowdfundingcheckboxvalue', true);
            $minimumvalue = get_post_meta($id, '_crowdfundinggetminimumprice', true);
            $recommendedvalue = get_post_meta($id, '_crowdfundinggetrecommendedprice', true);
            $maximumvalue = get_post_meta($id, '_crowdfundinggetmaximumprice', true);
            $targetvalue = get_post_meta($id, '_crowdfundinggettargetprice', true);
            $minpricelabel = get_option('crowdfunding_min_price_shop_page_shortcode');
            $maximumpricelabel = get_option('crowdfunding_maximum_price_shop_page_shortcode');
            $targetpricelabel = get_option('crowdfunding_target_price_shop_page_shortcode');
            $totalpricelabel = get_option('crowdfunding_totalprice_label_shop_page_shortcode');
            $totalpricepercentlabel = get_option('crowdfunding_totalprice_percent_label_shop_page_shortcode');
            $hideminimum = get_post_meta($id, '_crowdfundinghideminimum', true);
            $hidemaximum = get_post_meta($id, '_crowdfundinghidemaximum', true);
            $hidetarget = get_post_meta($id, '_crowdfundinghidetarget', true);
            $news = '';
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
                    $minimumfield = "<p  id = 'cf_min_price_label' class='cf_price'><label>" . $minpricecaption . $colonmin . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($minimumvalue) . "</span></p>";
                } else {
                    $minimumfield = '';
                }
            } else {
                $minimumfield = '';
            }
            if ($hidemaximum != 'yes') {
                if ($maximumvalue != '') {
                    $maximumfield = " <p  id = 'cf_max_price_label' class='cf_price'><label>" . $maxpricecaption . $colonmax . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($maximumvalue) . "</span></p>";
                } else {
                    $maximumfield = '';
                }
            } else {
                $maximumfield = '';
            }
            if ($hidetarget != 'yes') {
                if ($targetvalue != '') {
                    $targetfield = " <p  id = 'cf_target_price_label' class='cf_price'><label>" . $targetpricecaption . $colontarget . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($targetvalue) . "</span></p>";
                } else {
                    $targetfield = '';
                }
            } else {
                $targetfield = '';
            }
            if (get_option('cf_raised_amount_show_hide_shortcode') == '1') {
                if ($order_total != '') {
                    $totalfield = "<p  id = 'cf_total_price_raised' class='cf_price'><label>" . $totalpricecaption . $colontotal . " " . "</label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></p>";
                }
                if ($order_total != '') {
                    $totalfieldss = "<span  id = 'cf_total_price_raise' class='cf_price' style = 'float:left;
'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></span>";
                }
            }
            if (get_option('cf_raised_percentage_show_hide_shortcode') == '1') {
                $totalpledgedpercent = "<p id = 'cf_total_price_in_percentage' class='cf_price'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
            }
            if ($count2 > 0) {
                $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                if ($counter >= 100) {
                    $counter = 100;
                }
                $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: ' . $counter . '%;
"></div></div>';
            } else {
                $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                if ($counter >= 100) {
                    $counter = 100;
                }
                $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: 0%;
"></div></div>';
            }

            if ($count2 > 0) {
                $progress_bar_type = get_option('shortcode_page_prog_bar_type');
                if ($progress_bar_type == '1') {
                    $totalpledgedpercent = "<p  id = 'cf_total_price_in_percent' class='cf_price'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                    $totalpledgedpercentss = '<div id = "cf_total_price_in_percent_with_bar" style = "float:left">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentages = $totalpledgedpercentss . '<div id = "cf_percent_bar" style = "width: ' . $counter . '%;
"></div></div>';
                } else {
                    $totalpledgedpercent = "<p  id = 'cf_total_price_in_percent' class='cf_price'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                    $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: ' . $counter . '%;clear:both;"><span class= "currentpledgegoal"> </span></span></div>';
                }
            } else {
                $progress_bar_type = get_option('shortcode_page_prog_bar_type');
                if ($progress_bar_type == '1') {
                    $totalpledgedpercent = "<p  id = 'cf_total_price_in_percent' class='cf_price'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                    $totalpledgedpercentss = '<div id = "cf_total_price_in_percent_with_bar" style = "float:left">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentages = $totalpledgedpercentss . '<div id = "cf_percent_bar" style = "width: 0%;
"></div></div>';
                } else {
                    $totalpledgedpercent = "<p  id = 'cf_total_price_in_percent' class='cf_price'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                    $totalpledgedpercentss = '<div class= "pledgetracker" style="clear:both;">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: 0%;clear:both;"><span class= "currentpledgegoal"> </span></span></div>';
                }
            }

            if ($order_total != '') {
                $totalfielder = "<span  id = 'cf_total_price_raiser' class='cf_price'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "<small> PLEDGED </small></span></span>";
            }

            if ($date >= time()) {
                $diff = $date - time();
                $day = ceil($diff / 86400);
                $days = floor($diff / 86400);
                $hours = $diff / 3600 % 24;
                $minutes = $diff / 60 % 60;
                $sec = $diff % 60;

//                            $diff = $date - time(); //time returns current time in seconds
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
                        $new_date_remains = '<span  id = "cf_days_remainings" class="cf_price" style = "float:left">' . $day . 'days' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                    } else {
                        $new_date_remains = '<span  id = "cf_days_remainings" class="cf_price" style = "float:left">' . $day . ' day ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                    }
                } elseif (get_option('cf_campaign_day_time_display') == '2') {
                    if ($days > 1) {
                        $new_date_remains = '<span  id = "cf_days_remainings" class="cf_price" style = "float:left">' . $days . ' days ' . $hour . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                    } else {
                        $new_date_remains = '<span  id = "cf_days_remainings" class="cf_price" style = "float:left">' . $days . ' day ' . $hour . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                    }
                } else {
                    if ($days > 1) {
                        $new_date_remains = '<span  id = "cf_days_remainings" class="cf_price" style = "float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                    } else {
                        $new_date_remains = '<span  id = "cf_days_remainings" class="cf_price" style = "float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                    }
                }
            } else {
                $new_date_remains = '<span  id = "cf_days_remainings" class="cf_price" style = "float:left"> 0 ' . __('TO GO', 'galaxyfunder') . '</span>';
            }
            $cfcounter = '<span  id = "cf_total_raised_in_percentage" class="cf_price" style = "">' . $count . '<small>' . __(' FUNDED', 'galaxyfunder') . '</small></span>';

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
            $countercf = '<span  id = "cf_total_raised_percentage" class="cf_price" style = "float:right">' . $count . '</span>';


            if ($checkvalue == 'yes') {
                $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                if ($thumbnail != false || $thumbnail != '') {
                    $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                    $width = "22.05%";
                    ?>

                    <?php
                }
                if ($thumbnail == false || $thumbnail == '') {
                    $url = plugins_url();
                    $thumbnail = $url . "/woocommerce/assets/images/placeholder.png";
                    $width = "100%";
                }
//var_dump($thumbnail);
                ?>
                <style type="text/css">
                    .woocommerce ul.products li.product a img, .woocommerce-page ul.products li.product a img {
                        width:150px !important;
                    }
                </style>

                <?php
                $news = '
                <ul class="products" style="float:left;padding-bottom:0px;padding-top:0px;width:170px;">
                    <li class="product" style="display:table;width:' . $width . ';margin-bottom:0px;">
                    <a href=' . get_permalink($id) . '>
                        <img style="display:list-item;" width="150" height="150" src="' . $thumbnail . '" alt="Placeholder" class="woocommerce-placeholder wp-post-image"/>
' . $enabletitle . $enabledescription . '
' . $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains . '
</a>
</li>
                </ul>
           ';
            }
            $newflush = ob_get_contents();
            ob_end_clean();
            return '<div class="woocommerce" style="display:inline-block">' . $news . '</div>' . $newflush;
        }
    }

    public static function admin_enqueue_script() {
        global $woocommerce;
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'crowdfunding_shortcode') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {

                        var selected_style = jQuery('#load_inbuilt_shop_design_shortcode').val();
                        if (selected_style == '1') {
                            jQuery('#cf_day_left_show_hide_shortcode').parent().parent().hide();
                            jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                        }
                        else if (selected_style == '2') {
                            jQuery('#cf_day_left_show_hide_shortcode').parent().parent().show();
                            jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().show();
                            jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                            jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();
                        } else {
                            jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                        }
                        jQuery('#load_inbuilt_shop_design_shortcode').change(function () {
                            var selected_styles = jQuery('#load_inbuilt_shop_design_shortcode').val();
                            if (selected_styles == '1') {
                                jQuery('#cf_day_left_show_hide_shortcode').parent().parent().hide();
                                jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                                jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();

                            }
                            if (selected_styles == '2') {
                                jQuery('#cf_day_left_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();
                            }
                            if (selected_styles == '3') {
                                jQuery('#cf_day_left_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                                jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();
                            }
                            //alert(jQuery('#load_inbuilt_design').val());
                            // alert("hai");
                        });

                        jQuery(".chosen-style").attr('multiple', '');
                        jQuery('.chosen-style').attr('data-placeholder', 'Search for a Campaign...')
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery(".chosen-style").chosen();


                            var newvalue = jQuery(".chosen-style").chosen().val();
                <?php } else { ?>
                            jQuery('body').trigger('wc-enhanced-select-init');
                            //  jQuery('.chosen-style').select2();
                            var newvalue = jQuery(".chosen-style").select2().val();
                <?php } ?>
                        //console.log(newvalue);
                        if (newvalue !== null) {
                            for (var i = 0; i < newvalue.length; i++) {
                                // alert(newvalue[i]);
                                if ((newvalue[i] !== null)) {
                                    jQuery('#productidshortcode').append('[galaxyfunder_campaign id="' + newvalue[i] + '"]<br>');
                                }
                            }
                        }
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery(".chosen-style").chosen().change(function (e, params) {
                                var newvalue = jQuery(".chosen-style").chosen().val();
                                jQuery('.chosen-style').trigger("chosen:updated");

                                if (newvalue !== null) {
                                    for (var i = 0;
                                            i < newvalue.length;
                                            i++) {
                                        if (i === 0) {
                                            jQuery('#productidshortcode').empty();
                                        }
                                        if ((newvalue[i] !== null)) {
                                            jQuery('#productidshortcode').append('[galaxyfunder_campaign id="' + newvalue[i] + '"]<br>');
                                        }
                                    }
                                } else {
                                    jQuery('#productidshortcode').empty("");
                                }
                            });
                <?php } else { ?>
                            jQuery(".chosen-style").change(function () {
                                var newvalue = jQuery('#load_inbuilt_shortcode_generators').val();
                                newvalue = jQuery('#hiddenpro').text(newvalue);
                                newvalue = jQuery('#hiddenpro').text();
                                jQuery('#hiddenpro').css('display', 'none');
                                newvalue = newvalue.split(',');
                                if (newvalue !== null) {
                                    for (var i = 0;
                                            i < newvalue.length;
                                            i++) {
                                        if (i === 0) {
                                            jQuery('#productidshortcode').empty();
                                        }
                                        if ((newvalue[i] !== "null")) {
                                            jQuery('#productidshortcode').append('[galaxyfunder_campaign id="' + newvalue[i] + '"]<br>');
                                        }
                                        else {
                                            jQuery('#productidshortcode').empty();
                                        }
                                    }
                                }

                            });

                <?php } ?>
                    });
                </script>
                <?php
            }
        }
    }

    public static function cf_list_user_campaigns() {
        ob_start();
        if (is_user_logged_in()) {
            $user_ID = get_current_user_id();
            $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'author' => $user_ID, 'post_status' => 'draft,publish');
            $listmycampaign = new WP_Query($args);

            $timezone = wc_timezone_string();
            if ($timezone != '') {
                $timezonedate = date_default_timezone_set($timezone);
            } else {
                $timezonedate = date_default_timezone_set('UTC');
            }

            if ($listmycampaign->have_posts()) {
                while ($listmycampaign->have_posts()) {
                    $listmycampaign->the_post();
                    //echo get_the_ID() . "<br>";
                    $order_total = get_post_meta(get_the_ID(), '_crowdfundingtotalprice', true);
                    $getdate = date("m/d/Y");
                    $gethour = date("h");
                    $getminutes = date("i");
                    $fromdate = get_post_meta(get_the_ID(), '_crowdfundingfromdatepicker', true);
                    $todate = get_post_meta(get_the_ID(), '_crowdfundingtodatepicker', true);
                    $tohours = get_post_meta(get_the_ID(), '_crowdfundingtohourdatepicker', true);
                    $tominutes = get_post_meta(get_the_ID(), '_crowdfundingtominutesdatepicker', true);
                    $fromhours = get_post_meta(get_the_ID(), '_crowdfundingfromhourdatepicker', true);
                    $fromminutes = get_post_meta(get_the_ID(), '_crowdfundingfromminutesdatepicker', true);
                    $campaign_end_method = get_post_meta(get_the_ID(), '_target_end_selection', true);
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
                        update_post_meta(get_the_ID(), '_crowdfundingfromdatepicker', $getdate);
                        update_post_meta(get_the_ID(), '_crowdfundingfromhourdatepicker', $gethour);
                        update_post_meta(get_the_ID(), '_crowdfundingfromminutesdatepicker', $getminutes);
                    }
//  if ($todate != '') {
                    if ($tohours != '' || $tominutes != '') {
                        $time = $tohours . ':' . $tominutes . ':' . '00';
                        $datestr = $todate . $time; //Your date
                    } else {
                        $datestr = $todate . "23:59:59";
                    }//Your date
                    $date = strtotime($datestr); //Converted to a PHP date (a second count)
                    $newid = get_the_ID();
                    $payyourpricelabel = '';
                    $count = '';
                    $count2 = '';
                    $counter = '';
                    $totalfield = '';
                    $totalfieldss = '';
                    $totalfielder = '';
                    $inbuilt_designs = '';
                    $news = '';
                    $id = '';
                    $totalpledgedpercentage = '';
                    $totalpledgedpercentages = '';
                    if (get_option('cf_funders_count_show_hide_shortcode') == '1') {
                        $checktotalfunders = get_post_meta(get_the_ID(), '_update_total_funders', true);
                        if ($checktotalfunders != '') {
                            $gettotalfunders = '<span class="price" id="cf_get_total_funders" style="float:right">' . get_post_meta(get_the_ID(), '_update_total_funders', true) . '<small> ' . get_option('cf_funder_label') . '</small> </span>';
                        } else {
                            $gettotalfunders = '<span class="price" id="cf_get_total_funders"  style="float:right"> 0 <small>' . get_option('cf_funder_label') . '</small> </span>';
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
                    if (get_option('cf_day_left_show_hide_shortcode') == '1') {
                        if ($campaign_end_method == '1' || $campaign_end_method == '4') {
                            if ($date >= time()) {
                                $diff = $date - time();
                                $day = ceil($diff / 86400);
                                $days = floor($diff / 86400);
                                $hours = $diff / 3600 % 24;
                                $minutes = $diff / 60 % 60;
                                $sec = $diff % 60;
//                              $diff = $date - time(); //time returns current time in seconds
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
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $day . 'days' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $day . ' day ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                    if ($days > 1) {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' days ' . $hour . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' day ' . $hour . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } else {
                                    if ($days > 1) {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                }
                            } else {
                                $new_date_remain = '<span class = "price" id = "cf_days_remaining" style = "float:left"> 0 ' . __(' day Left', 'galaxyfunder') . '</span>';
                            }
                        } else {
                            $new_date_remain = '';
                        }
                    }
                    ?>

                    <?php
                    $inbuilt_designs = get_option("cf_inbuilt_shop_design_shortcode");
                    $load_designs = get_option('load_inbuilt_shop_design_shortcode');
                    if ($inbuilt_designs == '1') {
                        if ($load_designs == '1') {
                            ?>
                            <style type="text/css">
                            <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                            </style>
                            <?php
                        }
                        if ($load_designs == '2') {
                            ?>
                            <style type="text/css">
                            <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                            </style>
                            <?php
                        }
                        if ($load_designs == '3') {
                            ?>
                            <style type="text/css">
                            <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                            </style>
                            <?php
                        }
                    }
                    if ($inbuilt_designs == '2') {
                        ?>
                        <style type="text/css">
                        <?php echo get_option('cf_shop_page_contribution_table_custom_css_shortcode'); ?>
                        </style>
                        <?php
                    }

                    $products = get_product(get_the_ID());
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
                    }
                    if ($hidemaximum != 'yes') {
                        if ($maximumvalue != '') {
                            $maximumfield = " <p class = 'price' id = 'cf_max_price_label'><label>" . $maxpricecaption . $colonmax . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($maximumvalue) . "</span></p>";
                        } else {
                            $maximumfield = '';
                        }
                    }
                    if ($hidetarget != 'yes') {
                        if ($targetvalue != '') {
                            $targetfield = " <p class = 'price' id = 'cf_target_price_label'><label>" . $targetpricecaption . $colontarget . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($targetvalue) . "</span></p>";
                        } else {
                            $targetfield = '';
                        }
                    }
                    if (get_option('cf_raised_amount_show_hide_shortcode') == '1') {
                        if ($order_total != '') {
                            $totalfield = "<p class = 'price' id = 'cf_total_price_raised'><label>" . $totalpricecaption . $colontotal . " " . "</label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></p>";
                        }
                        if ($order_total != '') {
                            $totalfieldss = "<span class = 'price' id = 'cf_total_price_raise' style = 'float:left;
'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></span>";
                        }
                    }

                    if (get_option('cf_raised_percentage_show_hide_shortcode') == '1') {
                        $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percentage'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                    }

                    if ($count2 > 0) {
                        $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                        if ($counter >= 100) {
                            $counter = 100;
                        }
                        $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: ' . $counter . '%;
"></div></div>';
                    } else {
                        $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                        if ($counter >= 100) {
                            $counter = 100;
                        }
                        $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: 0%;
"></div></div>';
                    }

                    if ($count2 > 0) {
                        $progress_bar_type = get_option('shortcode_page_prog_bar_type');
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
                            $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: ' . $counter . '%;clear:both;"><span class= "currentpledgegoal"> </span></span></div>';
                        }
                    } else {
                        $progress_bar_type = get_option('shortcode_page_prog_bar_type');
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
                            $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: 0%;clear:both;"><span class= "currentpledgegoal"> </span></span></div>';
                        }
                    }

                    if ($order_total != '') {
                        $totalfielder = "<span class = 'price' id = 'cf_total_price_raiser'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "<small> PLEDGED </small></span></span>";
                    }
                    if (get_option('cf_day_left_show_hide_shortcode') == '1') {
                        if ($campaign_end_method != '2' && $campaign_end_method != '3') {
                            if ($date >= time()) {
                                $diff = $date - time();
                                $day = ceil($diff / 86400);
                                $days = floor($diff / 86400);
                                $hours = $diff / 3600 % 24;
                                $minutes = $diff / 60 % 60;
                                $sec = $diff % 60;

//                              $diff = $date - time(); //time returns current time in seconds
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
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $day . 'days' . " <small >" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $day . ' day ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                    if ($days > 1) {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' days ' . $hour . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' day ' . $hour . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                } else {
                                    if ($days > 1) {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    } else {
                                        $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                    }
                                }
                            } else {
                                $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left"> 0 ' . __(' DAY TO GO', 'galaxyfunder') . '</span>';
                            }
                        } else {
                            $new_date_remains = '';
                        }
                    }
                    if (get_option('cf_raised_percentage_show_hide_shortcode') == '1') {
                        $cfcounter = '<span class = "price" id = "cf_total_raised_in_percentage" style = "">' . $count . ' ' . '<small>' . __('FUNDED', 'galaxyfunder') . '</small></span>';
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
                    if (get_option('cf_raised_percentage_show_hide_shortcode') == '1') {
                        $countercf = '<span class = "price" id = "cf_total_raised_percentage" style = "float:right">' . $count . '</span>';
                    }
                    if ($checkvalue == 'yes') {
                        $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                        if ($thumbnail != false || $thumbnail != '') {
                            $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                            $width = "22.05%";
                            ?>

                            <?php
                        }
                        if ($thumbnail == false || $thumbnail == '') {
                            $thumbnail_url = get_the_post_thumbnail($newid, array(150, 150));
                            //echo "<pre>";
                            //echo $thumbnail_url;
                            //echo "<pre>";
                        }
//var_dump($thumbnail);
                        ?>
                        <style type="text/css">
                            .woocommerce ul.products li.product a img, .woocommerce-page ul.products li.product a img {
                                width:130px !important; height:130px !important;
                            }

                        </style>
                        <?php
                        $enabletitle = '<h3>' . get_the_title($newid) . '</h3>';
                        if ($checkvalue == 'yes') {

// $new = $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains;
                            if ($thumbnail != '') {
                                $news = '

                    <li class="product" style="width:170px;">
                    <a href=' . get_permalink($newid) . '>
                     <img src="' . $thumbnail . '"/>

' . $enabletitle . '
' . $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains . $new_date_remains . '
</a>
</li>

           ';
                            } else {
                                $url = plugins_url();
                                $thumbnail = $url . "/woocommerce/assets/images/placeholder.png";
                                $width = "100%";
                                $news = '

                    <li class="product" style="width:170px;">
                    <a href=' . get_permalink($newid) . '>

                       <img style="display:list-item;" width="180" height="150" src="' . $thumbnail . '" alt="Placeholder" class="woocommerce-placeholder wp-post-image"/>
' . $enabletitle . '
' . $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains . '
</a>
</li>

           ';
                            }
                            echo $news;
                        }
                    }
                }
            } else {
                echo "Sorry No Campaigns Found";
            }
            wp_reset_postdata();
        } else {
            echo "Please Login to see your Campaign";
        }
        $newflush = ob_get_contents();
        ob_end_clean();
        return '<div class="woocommerce" ><ul class="products" >' . $newflush . '</ul></div>';
    }

    public static function list_all_users_campaign() {
        ob_start();

        $user_list = get_users();
        //foreach ($user_list as $each_user){
        // $user_id = $each_user -> ID;
        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'draft,publish');
        $listmycampaign = new WP_Query($args);

        if ($listmycampaign->have_posts()) {
            while ($listmycampaign->have_posts()) {
                $listmycampaign->the_post();
                //echo get_the_ID() . "<br>";

                $timezone = wc_timezone_string();
                if ($timezone != '') {
                    $timezonedate = date_default_timezone_set($timezone);
                } else {
                    $timezonedate = date_default_timezone_set('UTC');
                }
                $order_total = get_post_meta(get_the_ID(), '_crowdfundingtotalprice', true);
                $getdate = date("m/d/Y");
                $gethour = date("h");
                $getminutes = date("i");
                $fromdate = get_post_meta(get_the_ID(), '_crowdfundingfromdatepicker', true);
                $todate = get_post_meta(get_the_ID(), '_crowdfundingtodatepicker', true);
                $tohours = get_post_meta(get_the_ID(), '_crowdfundingtohourdatepicker', true);
                $tominutes = get_post_meta(get_the_ID(), '_crowdfundingtominutesdatepicker', true);
                $fromhours = get_post_meta(get_the_ID(), '_crowdfundingfromhourdatepicker', true);
                $fromminutes = get_post_meta(get_the_ID(), '_crowdfundingfromminutesdatepicker', true);
                $campaign_end_method = get_post_meta(get_the_ID(), '_target_end_selection', true);
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
                    update_post_meta(get_the_ID(), '_crowdfundingfromdatepicker', $getdate);
                    update_post_meta(get_the_ID(), '_crowdfundingfromhourdatepicker', $gethour);
                    update_post_meta(get_the_ID(), '_crowdfundingfromminutesdatepicker', $getminutes);
                }
//  if ($todate != '') {
                if ($tohours != '' || $tominutes != '') {
                    $time = $tohours . ':' . $tominutes . ':' . '00';
                    $datestr = $todate . $time; //Your date
                } else {
                    $datestr = $todate . "23:59:59";
                } //Your date
                $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference
// if (($fromdate == $getdate || $fromdate < $getdate) && ($date >= time())) {
                $newid = get_the_ID();
                $payyourpricelabel = '';
                $count = '';
                $count2 = '';
                $counter = '';
                $totalfield = '';
                $totalfieldss = '';
                $totalfielder = '';
                $inbuilt_designs = '';
                $news = '';
                $id = '';
                $totalpledgedpercentage = '';
                $totalpledgedpercentages = '';
                if (get_option('cf_funders_count_show_hide_shortcode') == '1') {
                    $checktotalfunders = get_post_meta(get_the_ID(), '_update_total_funders', true);
                    if ($checktotalfunders != '') {
                        $gettotalfunders = '<span class="price" id="cf_get_total_funders" style="float:right">' . get_post_meta(get_the_ID(), '_update_total_funders', true) . '<small> ' . get_option('cf_funder_label') . '</small> </span>';
                    } else {
                        $gettotalfunders = '<span class="price" id="cf_get_total_funders"  style="float:right"> 0 <small>' . get_option('cf_funder_label') . '</small> </span>';
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
                if (get_option('cf_day_left_show_hide_shortcode') == '1') {
                    if ($campaign_end_method == '1' || $campaign_end_method == '4') {
                        if ($date >= time()) {
                            $diff = $date - time();
                            $day = ceil($diff / 86400);
                            $days = floor($diff / 86400);
                            $hours = $diff / 3600 % 24;
                            $minutes = $diff / 60 % 60;
                            $sec = $diff % 60;

//                              $diff = $date - time(); //time returns current time in seconds
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
                                    $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $day . 'days' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                } else {
                                    $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $day . ' day ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                }
                            } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                if ($days > 1) {
                                    $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' days ' . $hour . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                } else {
                                    $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' day ' . $hour . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                }
                            } else {
                                if ($days > 1) {
                                    $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                } else {
                                    $new_date_remain = '<span class="price" id="cf_days_remaining" style="float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small>" . __('left', 'galaxyfunder') . "</small>" . '</span>';
                                }
                            }
                        } else {
                            $new_date_remain = '<span class = "price" id = "cf_days_remaining" style = "float:left"> 0 ' . __(' day Left', 'galaxyfunder') . '</span>';
                        }
                    } else {
                        $new_date_remain = '';
                    }
                }
                ?>

                <?php
                $inbuilt_designs = get_option("cf_inbuilt_shop_design_shortcode");
                $load_designs = get_option('load_inbuilt_shop_design_shortcode');
                if ($inbuilt_designs == '1') {
                    if ($load_designs == '1') {
                        ?>
                        <style type="text/css">
                        <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                        </style>
                        <?php
                    }
                    if ($load_designs == '2') {
                        ?>
                        <style type="text/css">
                        <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                        </style>
                        <?php
                    }
                    if ($load_designs == '3') {
                        ?>
                        <style type="text/css">
                        <?php echo get_option('cf_shop_page_contribution_table_default_css_shortcode'); ?>
                        </style>
                        <?php
                    }
                }
                if ($inbuilt_designs == '2') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_shop_page_contribution_table_custom_css_shortcode'); ?>
                    </style>
                    <?php
                }
                $products = get_product(get_the_ID());
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
                }
                if ($hidemaximum != 'yes') {
                    if ($maximumvalue != '') {
                        $maximumfield = " <p class = 'price' id = 'cf_max_price_label'><label>" . $maxpricecaption . $colonmax . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($maximumvalue) . "</span></p>";
                    } else {
                        $maximumfield = '';
                    }
                }
                if ($hidetarget != 'yes') {
                    if ($targetvalue != '') {
                        $targetfield = " <p class = 'price' id = 'cf_target_price_label'><label>" . $targetpricecaption . $colontarget . " " . " </label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($targetvalue) . "</span></p>";
                    } else {
                        $targetfield = '';
                    }
                }
                if (get_option('cf_raised_amount_show_hide_shortcode') == '1') {
                    if ($order_total != '') {
                        $totalfield = "<p class = 'price' id = 'cf_total_price_raised'><label>" . $totalpricecaption . $colontotal . " " . "</label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></p>";
                    }
                    if ($order_total != '') {
                        $totalfieldss = "<span class = 'price' id = 'cf_total_price_raise' style = 'float:left;
'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "</span></span>";
                    }
                }
                if (get_option('cf_raised_percentage_show_hide_shortcode') == '1') {
                    $totalpledgedpercent = "<p class = 'price' id = 'cf_total_price_in_percentage'><label>" . $totalpricepercentcaption . $colontotalpercent . "" . "</label><span class = 'amount'>" . $count . "</span></p>";
                }
                if ($count2 > 0) {
                    $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: ' . $counter . '%;
"></div></div>';
                } else {
                    $totalpledgedpercents = $totalpledgedpercent . '<div id = "cf_total_price_in_percentage_with_bar" style = "">';
                    if ($counter >= 100) {
                        $counter = 100;
                    }
                    $totalpledgedpercentage = $totalpledgedpercents . '<div id = "cf_percentage_bar" style = "width: 0%;
"></div></div>';
                }
                if ($count2 > 0) {
                    $progress_bar_type = get_option('shortcode_page_prog_bar_type');
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
                        $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: ' . $counter . '%;clear:both;"><span class= "currentpledgegoal"> </span></span></div>';
                    }
                } else {
                    $progress_bar_type = get_option('shortcode_page_prog_bar_type');
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
                        $totalpledgedpercentages = $totalpledgedpercentss . '<span style="width: 0%;clear:both;"><span class= "currentpledgegoal"> </span></span></div>';
                    }
                }

                if (get_option('cf_raised_amount_show_hide_shortcode') == '1') {
                    if ($order_total != '') {
                        $totalfielder = "<span class = 'price' id = 'cf_total_price_raiser'><label></label><span class = 'amount'>" . CrowdFunding::get_woocommerce_formatted_price($order_total) . "<small> PLEDGED </small></span></span>";
                    }
                }
                if (get_option('cf_day_left_show_hide_shortcode') == '1') {
                    if ($campaign_end_method == '1' || $campaign_end_method == '4') {
                        if ($date >= time()) {
                            $diff = $date - time();
                            $day = ceil($diff / 86400);
                            $days = floor($diff / 86400);
                            $hours = $diff / 3600 % 24;
                            $minutes = $diff / 60 % 60;
                            $sec = $diff % 60;

//                            $diff = $date - time(); //time returns current time in seconds
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
                                    $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $day . 'days' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                } else {
                                    $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $day . ' day ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                }
                            } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                if ($days > 1) {
                                    $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' days ' . $hour . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                } else {
                                    $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' day ' . $hour . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                }
                            } else {
                                if ($days > 1) {
                                    $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' days ' . $hours . $minutes . ' minutes ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                } else {
                                    $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left">' . $days . ' day ' . $hours . $minutes . ' minutes ' . " <small>" . __('TO GO', 'galaxyfunder') . "</small>" . '</span>';
                                }
                            }
                        } else {
                            $new_date_remains = '<span class = "price" id = "cf_days_remainings" style = "float:left"> 0 ' . __(' DAY TO GO', 'galaxyfunder') . '</span>';
                        }
                    } else {
                        $new_date_remain = '';
                        $new_date_remains = '';
                    }
                }
                if (get_option('cf_raised_percentage_show_hide_shortcode') == '1') {
                    $cfcounter = '<span class = "price" id = "cf_total_raised_in_percentage" style = "">' . $count . ' ' . '<small>' . __('FUNDED', 'galaxyfunder') . '</small></span>';
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
                if (get_option('cf_raised_percentage_show_hide_shortcode') == '1') {
                    $countercf = '<span class = "price" id = "cf_total_raised_percentage" style = "float:right">' . $count . '</span>';
                }
                if ($checkvalue == 'yes') {
                    $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                    if ($thumbnail != false || $thumbnail != '') {
                        $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                        $width = "22.05%";
                        ?>

                        <?php
                    }
                    if ($thumbnail == false || $thumbnail == '') {
                        $thumbnail_url = get_the_post_thumbnail($newid, array(150, 150));
//                            $url = plugins_url();
//                            $thumbnail = $url . "/woocommerce/assets/images/placeholder.png";
//                            $width = "100%";
                    }
//var_dump($thumbnail);
                    ?>
                    <style type="text/css">
                        .woocommerce ul.products li.product a img, .woocommerce-page ul.products li.product a img {
                            width:130px !important; height:130px !important;
                        }

                    </style>
                    <?php
                    $enabletitle = '<h3>' . get_the_title($newid) . '</h3>';
                    if ($checkvalue == 'yes') {

// $new = $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains;
                        if ($thumbnail != '') {
                            $news = '

                    <li class="product" style="width:170px;">
                    <a href=' . get_permalink($newid) . '>
                     <img src="' . $thumbnail . '"/>

' . $enabletitle . '
' . $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains . '
</a>
</li>

           ';
                        } else {
                            $url = plugins_url();
                            $thumbnail = $url . "/woocommerce/assets/images/placeholder.png";
                            $width = "100%";
                            $news = '

                    <li class="product" style="width:170px;">
                    <a href=' . get_permalink($newid) . '>

                       <img style="display:list-item;" width="180" height="150" src="' . $thumbnail . '" alt="Placeholder" class="woocommerce-placeholder wp-post-image"/>
' . $enabletitle . '
' . $minimumfield . $maximumfield . $targetfield . $totalfield . $totalpledgedpercentage . $totalfieldss . $countercf . $totalpledgedpercentages . $new_date_remain . $gettotalfunders . $totalpledgedpercentageser . $cfcounter . $totalfielder . $new_date_remains . '
</a>
</li>

           ';
                        }
                        echo $news;
                    }
                }
            }
        } else {
            echo "Sorry No Campaigns Found";
        }
        wp_reset_postdata();
        //}

        $newflush = ob_get_contents();
        ob_end_clean();
        return '<div class="woocommerce" > <ul class="products" style="">' . $newflush . '</ul></div>';
    }

}

add_shortcode('galaxyfunder_my_campaign', array('CFShortcodeGenerator', 'cf_list_user_campaigns'));
add_shortcode('galaxyfunder_all_campaign_list', array('CFShortcodeGenerator', 'list_all_users_campaign'));
add_shortcode('galaxyfunder_campaign', array('CFShortcodeGenerator', 'cf_shortcode_extract_code'));
add_filter('widget_text', 'do_shortcode');
new CFShortcodeGenerator();
add_action('woocommerce_update_options_crowdfunding_shortcode', array('CFShortcodeGenerator', 'crowdfunding_process_shortcode_update_settings'));
add_action('init', array('CFShortcodeGenerator', 'crowdfunding_shortcode_default_settings'));
add_action('woocommerce_cf_settings_tabs_crowdfunding_shortcode', array('CFShortcodeGenerator', 'crowdfunding_process_shortcode_admin_settings'));
add_filter('woocommerce_cf_settings_tabs_array', array('CFShortcodeGenerator', 'crowdfunding_admin_shortcode_tab'), 1500);
add_action('admin_init', array('CFShortcodeGenerator', 'cf_shortcode_reset_values'), 2);
add_action('admin_head', array('CFShortcodeGenerator', 'admin_enqueue_script'));
