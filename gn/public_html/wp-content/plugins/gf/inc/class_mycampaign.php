<?php

//error_reporting(0);

class FPCrowdFundingMycampaign {

//adding tab
    public static function cf_my_account_tab($settings_tab) {
        $settings_tab['my_account'] = __('My Account Page', 'galaxyfunder');
        return $settings_tab;
    }

    public static function cf_admin_my_campaign_options() {
        global $woocommerce;
        return apply_filters('woocommerce_my_account_settings', array(
            array(
                'name' => __("Use the Shortcode [crowd_fund_extension] to display the Campaign Extension Form"),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_new_extn_shortcode'
            ),
            array(
                'name' => __("Use the Shortcode [cf_mycampaign_table] to display the Campaign table"),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_contributor_shortcode'
            ),
            array(
                'name' => __('My Account Page Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_my_campaign'
            ),
            array(
                'name' => __('My Campaign Table Show/Hide', 'galaxyfunder'),
                'desc' => __('This Controls the My Campaign Table Show or Hide', 'galaxyfunder'),
                'id' => 'cf_display_mycampaign_table',
                'css' => 'min-width:150px;',
                'std' => 'on', // WooCommerce < 2.0
                'default' => 'on', // WooCommerce >= 2.0
                'newids' => 'cf_display_mycampaign_table',
                'type' => 'select',
                'options' => array(
                    'on' => __('Show', 'galaxyfunder'),
                    'off' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Title of My Campaign', 'galaxyfunder'),
                'desc' => __('Change My Campaign Title in My Account Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_mycampaign_title',
                'css' => 'min-width:550px;',
                'std' => 'My Campaigns',
                'type' => 'text',
                'newids' => 'cf_mycampaign_title',
                'desc_tip' => true,
            ),
            array(
                'name' => __('S.No Label', 'galaxyfunder'),
                'desc' => __('Change S.No Caption in Single in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_mycampaign_serial_number_label',
                'css' => 'min-width:550px;',
                'std' => 'S.No',
                'type' => 'text',
                'newids' => 'cf_mycampaign_serial_number_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Label', 'galaxyfunder'),
                'desc' => __('Change Campaign Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_campaign_label',
                'std' => 'Campaign',
                'type' => 'text',
                'newids' => 'cf_mycampaign_campaign_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Date Label', 'galaxyfunder'),
                'desc' => __('Change Date Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_date_label',
                'std' => 'Date',
                'type' => 'text',
                'newids' => 'cf_mycampaign_date_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Goal Label', 'galaxyfunder'),
                'desc' => __('Change Goal Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_goal_label',
                'std' => 'Goal',
                'type' => 'text',
                'newids' => 'cf_mycampaign_goal_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Fund Raised Label', 'galaxyfunder'),
                'desc' => __('Change Fund Raised Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_raised_label',
                'std' => 'Raised',
                'type' => 'text',
                'newids' => 'cf_mycampaign_raised_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Fund Raised Percentage Label', 'galaxyfunder'),
                'desc' => __('Change Fund Raised Percentage Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_raised_percent_label',
                'std' => 'Raised %',
                'type' => 'text',
                'newids' => 'cf_mycampaign_raised_percent_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Extension Label', 'galaxyfunder'),
                'desc' => __('', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_extension_label',
                'std' => 'Extend Campaign',
                'type' => 'text',
                'newids' => 'cf_mycampaign_extension_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Extension Link Label', 'galaxyfunder'),
                'desc' => __('', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_extension_link_label',
                'std' => 'Contribution Extension',
                'type' => 'text',
                'newids' => 'cf_mycampaign_extension_link_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Page Id of Contribution Extension Link ', 'galaxyfunder'),
                'desc' => __('', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_extension_pageid',
                'std' => '',
                'type' => 'text',
                'newids' => 'cf_mycampaign_extension_pageid',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Funders Label', 'galaxyfunder'),
                'desc' => __('Change Funders in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_funders_label',
                'std' => 'Funders',
                'type' => 'text',
                'newids' => 'cf_mycampaign_funders_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Status Label', 'galaxyfunder'),
                'desc' => __('Change Funders in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_status_label',
                'std' => 'Status',
                'type' => 'text',
                'newids' => 'cf_mycampaign_status_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Table Position', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_mycampaign_table_position',
                'options' => array('1' => __('Start of My Account', 'galaxyfunder'), '2' => __('End of My Account', 'galaxyfunder')),
                'class' => 'cf_mycampaign_table_position',
                'std' => '2',
                'newids' => 'cf_mycampaign_table_position',
            ),
            array(
                'name' => __('Show/Hide Your Subscribe Link', 'galaxyfunder'),
                'desc' => __('Show/Hide Your Subscribe Link if you want to display it in my account page', 'galaxyfunder'),
                'id' => 'gf_show_hide_your_subscribe_link',
                'newids' => 'gf_show_hide_your_subscribe_link',
                'class' => 'gf_show_hide_your_subscribe_link',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Subscribe Link Message', 'galaxyfunder'),
                'desc' => __('This Message will be displayed on the option to Unsubscribe from Galaxy Funder Emails', 'galaxyfunder'),
                'id' => 'gf_unsubscribe_message_myaccount_page',
                'css' => 'min-width:550px;',
                'std' => 'Unsubscribe here To Stop Receiving Email',
                'type' => 'textarea',
                'newids' => 'gf_unsubscribe_message_myaccount_page',
                'class' => 'gf_unsubscribe_message_myaccount_page',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_my_campaign'),
        ));
    }

    public static function cf_admin_my_campaign_settings() {
        woocommerce_admin_fields(FPCrowdFundingMycampaign::cf_admin_my_campaign_options());
    }

    public static function cf_admin_my_campaign_update_settings() {
        woocommerce_update_options(FPCrowdFundingMycampaign::cf_admin_my_campaign_options());
    }

    public static function cf_default_my_account_page() {
        global $woocommerce;
        foreach (FPCrowdFundingMycampaign::cf_admin_my_campaign_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_my_account_campaign() {
        if (get_option('cf_display_mycampaign_table') == "on") {
            global $woocommerce;
            global $post;
            
             $timezone=wc_timezone_string();
               if($timezone!='')
               {
                   $timezonedate=  date_default_timezone_set($timezone);
               }
               else{
                   $timezonedate=  date_default_timezone_set('UTC');
               }

            echo '<h2>' . get_option("cf_mycampaign_title") . '</h2>';
            $user_ID = get_current_user_id();
            $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'author' => $user_ID, 'post_status' => 'draft,publish');
            ?>


            <?php

            $listmycampaign = new WP_Query($args);
            // The Loop
            if ($listmycampaign->have_posts()) {
                $x = 0;
                // echo '<table id="example">';
                echo '<p> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size">

									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';

                echo ' <table class = "example demo shop_table my_account_orders table-bordered"  data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">';
                echo '<thead><tr><th data-toggle="false" data-sort-initial = "false">' . get_option('cf_mycampaign_serial_number_label') . '</th><th>' . get_option('cf_mycampaign_campaign_label') . '</th><th data-hide="phone">' . get_option('cf_mycampaign_date_label') . '</th><th data-hide = "phone">' . get_option("cf_mycampaign_goal_label") . '</th><th data-hide="phone">' . get_option("cf_mycampaign_raised_label") . '</th><th data-hide="phone">' . get_option("cf_mycampaign_raised_percent_label") . '</th><th data-hide="phone" >' . get_option("cf_mycampaign_extension_label") . '</th><th data-hide="phone,tablet">' . get_option("cf_mycampaign_funders_label") . '</th><th data-hide="phone,tablet">' . get_option("cf_mycampaign_status_label") . '</th></tr></thead><tbody>';
                $i = 0;
                while ($listmycampaign->have_posts()) {

                    $x = 0;
                    $y = 0;
                    $listmycampaign->the_post();
                    if (get_post_meta(get_the_ID(), '_crowdfundingcheckboxvalue', true) == 'yes') {
                        $i++;
                        echo '<tr id=1>';
                        echo '<td id=2>' . $i . '</td>';
                        echo '<td id=3><a href="' . get_permalink(get_the_ID()) . '" target="_blank">' . get_the_title() . '</a></td>';
                        echo '<td id=4>' . get_the_date() . '</td>';
                        echo '<td id=5>' . CrowdFunding::get_woocommerce_formatted_price(get_post_meta(get_the_ID(), '_crowdfundinggettargetprice', true)) . '</td>';
                        if (get_post_meta(get_the_ID(), '_crowdfundingtotalprice', true) == '') {
                            $total_price = CrowdFunding::get_woocommerce_formatted_price(0);
                        } else {
                            $total_price = CrowdFunding::get_woocommerce_formatted_price(get_post_meta(get_the_ID(), '_crowdfundingtotalprice', true));
                        }

                        echo '<td id=6>' . $total_price . '</td>';
                        if (get_post_meta(get_the_ID(), '_crowdfundinggoalpercent', true) == '') {
                            $percentage_value = 0;
                        } else {
                            $percentage_value = get_post_meta(get_the_ID(), '_crowdfundinggoalpercent', true);
                        }
                        echo '<td id=7>' . $percentage_value . '%</td>';
                        $product_id = get_the_ID();
                        $target_method_array = get_post_meta($product_id, '_target_end_selection');
                        foreach ($target_method_array as $target_method_id) {
                            // var_dump($target_method_id);
                            $row = '';
                            if ($target_method_id == 3) {

                                $target_method = "Target Goal";
                                $contributed_amount = $order_total = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                $target_value_array = get_post_meta($product_id, '_crowdfundinggettargetprice');
                                foreach ($target_value_array as $target_end_amount) {
                                    $contributed_amount = $order_total = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                    if ($contributed_amount > $target_end_amount) {


                                        // update_option('campaign_modification_list', $row);
                                        echo '<td id=8>Campaign closed</td>';
                                        $x++;
                                    }
                                }
                            } elseif ($target_method_id == 2) {
                                $target_method = "Campaign Never Ends";
                            } else {
                                $target_method = 'Target Date';
                                //check campaign status
                                $todate = get_post_meta($product_id, '_crowdfundingtodatepicker', true);
                                $tohours = get_post_meta($product_id, '_crowdfundingtohourdatepicker', true);
                                $tominutes = get_post_meta($product_id, '_crowdfundingtominutesdatepicker', true); //Your date
                                if ($tohours != '' || $tominutes != '') {
                                    $time = $tohours . ':' . $tominutes . ':' . '00';
                                    $datestr = $todate . $time; //Your date
                                } else {
                                    $datestr = $todate . "23:59:59";
                                } //Your date
                                $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference
                                if (get_post_status($product_id) == 'publish') {
                                    if ($date >= time()) {
                                        $diff = $date - time(); //time returns current time in seconds
                                        $days = ceil($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                        //$hours = round(($diff - $days * 60 * 60 * 24) / (60 * 60));
                                        //Report
                                        if ($days > 1) {
                                            //_e($days . "days to go", "galaxyfunder");
                                            // echo $days . __('days to go', 'galaxyfunder');
                                        } else {
                                            // _e($days . "day to go", "galaxyfunder");
                                            //echo $days . __('day to go', 'galaxyfunder');
                                        }
                                    } else {

                                        // echo 2;
                                        //echo $status_inc;
                                        //_e("Campaign Closed", "galaxyfunder");
                                        //echo "campaign closed";
                                        $compaign_modification_array = array_filter(get_option('campaign_modification_list'));


                                        foreach ($compaign_modification_array as $check_array) {
                                            if ((array_search($product_id, $check_array) == true)) {
                                                //echo "yes";
                                                //  unset($check_array);
                                            }
                                            // $row[] = @$check_array;
                                        }

                                        // update_option('campaign_modification_list', $row);
                                        //echo __('Campaign Closed', 'galaxyfunder');
                                        echo '<td id=9>campaign closed</td>';
                                        $x++;
                                    }
                                }
                            }
                            if ($x == 0 && $y == 0) {
                                $compaign_modification_array = get_option('campaign_modification_list');
                                if (is_array($compaign_modification_array)) {
                                    if (!is_null($compaign_modification_array)) {
                                        $product_id = get_the_ID();
                                        $page_id = get_option('cf_mycampaign_extension_pageid');
                                        foreach ($compaign_modification_array as $array_values) {
                                            if (is_array($array_values)) {
                                                if ((array_search($product_id, $array_values)) == true) {

                                                    echo '<td id=10>Request Pending</td>';
                                                    $y++;
                                                }
                                            }
                                        }
                                    }
                                }
                                $page_id = get_option('cf_mycampaign_extension_pageid');
                                if (empty($compaign_modification_array)) {
                                    echo '<td id=11><a href="' . esc_url_raw(add_query_arg(array('product_id' => $product_id), get_permalink($page_id))) . '">' . get_option('cf_mycampaign_extension_link_label') . ' </a></td>';
                                }
                                if (!empty($compaign_modification_array)) {
                                    //if(!is_array($array_values)){
                                    //var_dump($array_values);
                                    $array_value = isset($array_values) ? $array_values : array("");
                                    //var_dump($array_value);
                                    if ((array_search($product_id, $array_value)) != true) {

                                        echo '<td id=12> <a href=" ' . esc_url_raw(add_query_arg(array('product_id' => $product_id), get_permalink($page_id))) . '">' . get_option('cf_mycampaign_extension_link_label') . ' </a></td>';
                                    }
                                    //}
                                }

                                if (get_post_meta(get_the_ID(), '_update_total_funders', true) == '') {
                                    $total_funders = 0;
                                } else {
                                    $total_funders = get_post_meta(get_the_ID(), '_update_total_funders', true);
                                }
                                echo '<td id=13>' . $total_funders . '</td>';
                                //same date function from backend
                                echo '<td id=14>';
                                $todate = get_post_meta(get_the_ID(), '_crowdfundingtodatepicker', true); //Your date
                                $tohours = get_post_meta(get_the_ID(), '_crowdfundingtohourdatepicker', true);
                                $tominutes = get_post_meta(get_the_ID(), '_crowdfundingtominutesdatepicker', true);
                                if ($tohours != '' || $tominutes != '') {
                                    $time = $tohours . ':' . $tominutes . ':' . '00';
                                    $datestr = $todate . $time; //Your date
                                } else {
                                    $datestr = $todate . "23:59:59";
                                } //Your date
                                $date = strtotime($datestr);
                                //Converted to a PHP date (a second count)
                                if (get_post_status(get_the_ID()) == 'publish') {
                                    if ($target_method_id != '3' && $target_method_id != '2') {
                                        if ($date >= time()) {
                                            $diff = $date - time(); //time returns current time in seconds
                                            $days = ceil($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                            //$hours = round(($diff - $days * 60 * 60 * 24) / (60 * 60));
                                            //Report
                                            if ($days > 1) {
                                                //_e($days . "days to go", "galaxyfunder");
                                                echo $days . __(' days to go', 'galaxyfunder');
                                            } else {
                                                // _e($days . "day to go", "galaxyfunder");
                                                echo $days . __(' day to go', 'galaxyfunder');
                                            }
                                        }
                                    } elseif ($target_method_id == '3' || $target_method_id == '2') {
                                        echo __('---');
                                    } else {
                                        //_e("Campaign Closed", "galaxyfunder");
                                        echo __('Campaign Closed', 'galaxyfunder');
                                    }
                                } else {
                                    $chstatus = get_post_status(get_the_ID());
                                    if ($chstatus == 'draft') {
                                        echo __('Pending Review', 'galaxyfunder');
                                    }
                                }
                            }
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                echo '</tbody>
                        <tfoot>
                        <tr style = "clear:both;">
                        <td colspan = "7">
                        <div class = "pagination pagination-centered"></div>
                        </td>
                        </tr>
                        </tfoot>
                        </table>';
            } else {
                // no posts found
            }
            /* Restore original Post Data */
            wp_reset_postdata();
        }
    }

    public static function cf_my_account_campaign_shortcode() {
        if (get_option('cf_display_mycampaign_table') == "on") {
            global $woocommerce;
            global $post;

            echo '<h2>' . get_option("cf_mycampaign_title") . '</h2>';
            $user_ID = get_current_user_id();
            $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'author' => $user_ID, 'post_status' => 'draft,publish');
            ?>


            <?php
            $timezone=wc_timezone_string();
               if($timezone!='')
               {
                   $timezonedate=  date_default_timezone_set($timezone);
               }
               else{
                   $timezonedate=  date_default_timezone_set('UTC');
               }

            $listmycampaign = new WP_Query($args);
            // The Loop
            if ($listmycampaign->have_posts()) {
                $x = 0;
                // echo '<table id="example">';
                echo '<p> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                <select id="change-page-size">

									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';

                echo ' <table class = "example demo shop_table my_account_orders table-bordered"  data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">';
                echo '<thead><tr><th data-toggle="false" data-sort-initial = "false">' . get_option('cf_mycampaign_serial_number_label') . '</th><th>' . get_option('cf_mycampaign_campaign_label') . '</th><th data-hide="phone">' . get_option('cf_mycampaign_date_label') . '</th><th data-hide = "phone">' . get_option("cf_mycampaign_goal_label") . '</th><th data-hide="phone">' . get_option("cf_mycampaign_raised_label") . '</th><th data-hide="phone">' . get_option("cf_mycampaign_raised_percent_label") . '</th><th data-hide="phone" >' . get_option("cf_mycampaign_extension_label") . '</th><th data-hide="phone,tablet">' . get_option("cf_mycampaign_funders_label") . '</th><th data-hide="phone,tablet">' . get_option("cf_mycampaign_status_label") . '</th></tr></thead><tbody>';
                $i = 0;
                while ($listmycampaign->have_posts()) {

                    $x = 0;
                    $y = 0;
                    $listmycampaign->the_post();
                    if (get_post_meta(get_the_ID(), '_crowdfundingcheckboxvalue', true) == 'yes') {
                        $i++;
                        echo '<tr id=1>';
                        echo '<td id=2>' . $i . '</td>';
                        echo '<td id=3><a href="' . get_permalink(get_the_ID()) . '" target="_blank">' . get_the_title() . '</a></td>';
                        echo '<td id=4>' . get_the_date() . '</td>';
                        echo '<td id=5>' . CrowdFunding::get_woocommerce_formatted_price(get_post_meta(get_the_ID(), '_crowdfundinggettargetprice', true)) . '</td>';
                        if (get_post_meta(get_the_ID(), '_crowdfundingtotalprice', true) == '') {
                            $total_price = CrowdFunding::get_woocommerce_formatted_price(0);
                        } else {
                            $total_price = CrowdFunding::get_woocommerce_formatted_price(get_post_meta(get_the_ID(), '_crowdfundingtotalprice', true));
                        }

                        echo '<td id=6>' . $total_price . '</td>';
                        if (get_post_meta(get_the_ID(), '_crowdfundinggoalpercent', true) == '') {
                            $percentage_value = 0;
                        } else {
                            $percentage_value = get_post_meta(get_the_ID(), '_crowdfundinggoalpercent', true);
                        }
                        echo '<td id=7>' . $percentage_value . '%</td>';
                        $product_id = get_the_ID();
                        $target_method_array = get_post_meta($product_id, '_target_end_selection');
                        foreach ($target_method_array as $target_method_id) {
                            // var_dump($target_method_id);
                            $row = '';
                            if ($target_method_id == 3) {

                                $target_method = "Target Goal";
                                $contributed_amount = $order_total = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                $target_value_array = get_post_meta($product_id, '_crowdfundinggettargetprice');
                                foreach ($target_value_array as $target_end_amount) {
                                    $contributed_amount = $order_total = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                    if ($contributed_amount > $target_end_amount) {


                                        // update_option('campaign_modification_list', $row);
                                        echo '<td id=8>Campaign closed</td>';
                                        $x++;
                                    }
                                }
                            } elseif ($target_method_id == 2) {
                                $target_method = "Campaign Never Ends";
                            } else {
                                $target_method = 'Target Date';
                                //check campaign status
                                $todate = get_post_meta($product_id, '_crowdfundingtodatepicker', true); //Your date
                                $tohours = get_post_meta($product_id, '_crowdfundingtohourdatepicker', true);
                                $tominutes = get_post_meta($product_id, '_crowdfundingtominutesdatepicker', true);
                                if ($tohours != '' || $tominutes != '') {
                                    $time = $tohours . ':' . $tominutes . ':' . '00';
                                    $datestr = $todate . $time; //Your date
                                } else {
                                    $datestr = $todate . "23:59:59";
                                } //Your date
                                $date = strtotime($datestr); //Converted to a PHP date (a second count)
//Calculate difference
                                if (get_post_status($product_id) == 'publish') {
                                    if ($date >= time()) {
                                        $diff = $date - time(); //time returns current time in seconds
                                        $days = ceil($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                        //$hours = round(($diff - $days * 60 * 60 * 24) / (60 * 60));
                                        //Report
                                        if ($days > 1) {
                                            //_e($days . "days to go", "galaxyfunder");
                                            // echo $days . __('days to go', 'galaxyfunder');
                                        } else {
                                            // _e($days . "day to go", "galaxyfunder");
                                            //echo $days . __('day to go', 'galaxyfunder');
                                        }
                                    } else {

                                        // echo 2;
                                        //echo $status_inc;
                                        //_e("Campaign Closed", "galaxyfunder");
                                        //echo "campaign closed";
                                        $compaign_modification_array = array_filter(get_option('campaign_modification_list'));


                                        foreach ($compaign_modification_array as $check_array) {
                                            if ((array_search($product_id, $check_array) == true)) {
                                                //echo "yes";
                                                //  unset($check_array);
                                            }
                                            // $row[] = @$check_array;
                                        }

                                        // update_option('campaign_modification_list', $row);
                                        //echo __('Campaign Closed', 'galaxyfunder');
                                        echo '<td id=9>campaign closed</td>';
                                        $x++;
                                    }
                                }
                            }
                            if ($x == 0 && $y == 0) {
                                $compaign_modification_array = get_option('campaign_modification_list');
                                if (is_array($compaign_modification_array)) {
                                    if (!is_null($compaign_modification_array)) {
                                        $product_id = get_the_ID();
                                        $page_id = get_option('cf_mycampaign_extension_pageid');
                                        foreach ($compaign_modification_array as $array_values) {
                                            if (is_array($array_values)) {
                                                if ((array_search($product_id, $array_values)) == true) {

                                                    echo '<td id=10>Request Pending</td>';
                                                    $y++;
                                                }
                                            }
                                        }
                                    }
                                }
                                $page_id = get_option('cf_mycampaign_extension_pageid');
                                if (empty($compaign_modification_array)) {
                                    echo '<td id=11><a href="' . esc_url_raw(add_query_arg(array('product_id' => $product_id), get_permalink($page_id))) . '">' . get_option('cf_mycampaign_extension_link_label') . ' </a></td>';
                                }
                                if (!empty($compaign_modification_array)) {
                                    //if(!is_array($array_values)){
                                    //var_dump($array_values);
                                    $array_value = isset($array_values) ? $array_values : array("");
                                    //var_dump($array_value);
                                    if ((array_search($product_id, $array_value)) != true) {

                                        echo '<td id=12> <a href=" ' . esc_url_raw(add_query_arg(array('product_id' => $product_id), get_permalink($page_id))) . '">' . get_option('cf_mycampaign_extension_link_label') . ' </a></td>';
                                    }
                                    //}
                                }

                                if (get_post_meta(get_the_ID(), '_update_total_funders', true) == '') {
                                    $total_funders = 0;
                                } else {
                                    $total_funders = get_post_meta(get_the_ID(), '_update_total_funders', true);
                                }
                                echo '<td id=13>' . $total_funders . '</td>';
                                //same date function from backend
                                echo '<td id=14>';
                                $todate = get_post_meta(get_the_ID(), '_crowdfundingtodatepicker', true); //Your date
                                $tohours = get_post_meta(get_the_ID(), '_crowdfundingtohourdatepicker', true);
                                $tominutes = get_post_meta(get_the_ID(), '_crowdfundingtominutesdatepicker', true);
                                if ($tohours != '' || $tominutes != '') {
                                    $time = $tohours . ':' . $tominutes . ':' . '00';
                                    $datestr = $todate . $time; //Your date
                                } else {
                                    $datestr = $todate . "23:59:59";
                                } //Your date
                                $date = strtotime($datestr);
                                 //Converted to a PHP date (a second count)
                                if (get_post_status(get_the_ID()) == 'publish') {
                                    if ($target_method_id != '3' && $target_method_id != '2') {
                                        if ($date >= time()) {
                                            $diff = $date - time(); //time returns current time in seconds
                                            $days = ceil($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                            //$hours = round(($diff - $days * 60 * 60 * 24) / (60 * 60));
                                            //Report
                                            if ($days > 1) {
                                                //_e($days . "days to go", "galaxyfunder");
                                                echo $days . __(' days to go', 'galaxyfunder');
                                            } else {
                                                // _e($days . "day to go", "galaxyfunder");
                                                echo $days . __(' day to go', 'galaxyfunder');
                                            }
                                        }
                                    } elseif ($target_method_id == '3' || $target_method_id == '2') {
                                        echo __('---');
                                    } else {
                                        //_e("Campaign Closed", "galaxyfunder");
                                        echo __('Campaign Closed', 'galaxyfunder');
                                    }
                                } else {
                                    $chstatus = get_post_status(get_the_ID());
                                    if ($chstatus == 'draft') {
                                        echo __('Pending Review', 'galaxyfunder');
                                    }
                                }
                            }
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                echo '</tbody>
                        <tfoot>
                        <tr style = "clear:both;">
                        <td colspan = "7">
                        <div class = "pagination pagination-centered"></div>
                        </td>
                        </tr>
                        </tfoot>
                        </table>';
            } else {
                // no posts found
            }
            /* Restore original Post Data */
            wp_reset_postdata();
        }
    }

    public static function crowdfunding_enqueue_scripts() {
        wp_register_script('jqueryfootable', plugins_url('gf/js/footable.js'));
        wp_register_script('jqueryfootablesort', plugins_url('gf/js/footable.sort.js'));
        wp_register_script('jqueryfootablepaging', plugins_url('gf/js/footable.paginate.js'));
        wp_register_script('jqueryfootablefilter', plugins_url('gf/js/footable.filter.js'));
        wp_register_style('jqueryfootablecss', plugins_url('gf/css/footable.core.css'));
        wp_register_style('bootstrapnavigation', plugins_url('gf/css/bootstrap.css'));
        wp_register_style('progressstyle2css', plugins_url('gf/css/mystyle.css'));
        wp_enqueue_script('jquery');
        wp_enqueue_script('jqueryfootable');
        wp_enqueue_script('jqueryfootablepaging');
        wp_enqueue_script('jqueryfootablefilter');
        wp_enqueue_style('jqueryfootablecss');
        wp_enqueue_style('bootstrapnavigation');
        wp_enqueue_style('progressstyle2css');
        wp_enqueue_script('jqueryfootablesort');
    }

    public static function add_script_to_head() {
        if (!is_product()) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('.example').footable().bind('footable_filtering', function (e) {
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
            <?php

        }
    }

}

if (get_option('cf_mycampaign_table_position') === '2') {
    add_action('woocommerce_after_my_account', array('FPCrowdFundingMycampaign', 'cf_my_account_campaign'));
} else if (get_option('cf_mycampaign_table_position') === '1') {
    add_action('woocommerce_before_my_account', array('FPCrowdFundingMycampaign', 'cf_my_account_campaign'));
}
add_shortcode('cf_mycampaign_table', array('FPCrowdFundingMycampaign', 'cf_my_account_campaign_shortcode'));
add_action('wp_head', array('FPCrowdFundingMycampaign', 'add_script_to_head'));
/**
 * Adding the setting tab my account
 */
add_filter('woocommerce_cf_settings_tabs_array', array('FPCrowdFundingMycampaign', 'cf_my_account_tab'), 103);
add_action('woocommerce_cf_settings_tabs_my_account', array('FPCrowdFundingMycampaign', 'cf_admin_my_campaign_settings'));
add_action('woocommerce_update_options_my_account', array('FPCrowdFundingMycampaign', 'cf_admin_my_campaign_update_settings'));
add_action('init', array('FPCrowdFundingMycampaign', 'cf_default_my_account_page'));
add_action('wp_enqueue_scripts', array('FPCrowdFundingMyCampaign', 'crowdfunding_enqueue_scripts'));
add_action('admin_enqueue_scripts', array('FPCrowdFundingMyCampaign', 'crowdfunding_enqueue_scripts'));
