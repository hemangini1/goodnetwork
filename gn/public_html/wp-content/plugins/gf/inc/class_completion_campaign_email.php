<?php

class CampaignCompletionEmail {

    public static function campaign_completion_email() {
        global $post;
        global $woocommerce;
        global $products;
        $author='';
        $adminemail='';
        $creatoremail='';
        $timezone = wc_timezone_string();
        if ($timezone != '') {
            $timezonedate = date_default_timezone_set($timezone);
        } else {
            $timezonedate = date_default_timezone_set('UTC');
        }
//        var_dump(get_post_meta(225,'_crowdfundingcheckboxvalue',true));
        $args = array(
            'post_type' => 'product',
            'numberposts' => '-1',
            'post_status' => 'publish',
            'meta_value' => 'yes',
            'meta_key' => '_crowdfundingcheckboxvalue'
        );

//         echo '<pre>'; var_dump(get_posts($args));echo '</pre>';

        foreach (get_posts($args) as $products) {
            $posttype = get_post_type($products->ID);
            if ($posttype == 'product') {
                $checkvalue = get_post_meta($products->ID, '_crowdfundingcheckboxvalue', true);
                $targetendselection = get_post_meta($products->ID, '_target_end_selection', true);
                if ($checkvalue == 'yes') {
                    if ($targetendselection == '1') {
                        $checkstatus = get_post_meta($products->ID, '_stock_status', true);
                        if ($checkstatus == 'instock') {
                            $gettargetdate = get_post_meta($products->ID, '_crowdfundingtodatepicker', true);
                            $gettargethour = get_post_meta($products->ID, '_crowdfundingtohourdatepicker', true);
                            $gettargetminutes = get_post_meta($products->ID, '_crowdfundingtominutesdatepicker', true);
                            if ($gettargetdate != '') {
                                if ($gettargethour != '' || $gettargetminutes != '') {
                                    $time = $gettargethour . ':' . $gettargetminutes . ':' . '00';
                                    $datestr = $gettargetdate . $time; //Your date
                                } else {
                                    $datestr = $gettargetdate . "23:59:59";
                                }//Your date

                                $date = strtotime($datestr); //Converted to a PHP date (a second count)
                                if ($date < time()) {
                                    if (get_option('cf_enable_mail_for_campaign_completed') == 'yes') {
                                        if (get_post_meta($products->ID, '_crowdfunding_options', 'true') == '2') {
                                            $crowdtargetprice = get_post_meta($products->ID, '_crowdfundinggettargetprice', true);
                                            $crowdtotalprice = get_post_meta($products->ID, '_crowdfundingtotalprice', true);
                                            $checkstatus = get_post_meta($products->ID, '_stock_status', true);
                                            if ($crowdtotalprice >= $crowdtargetprice) {
                                                include('create_custom_order.php');
                                            }
                                        }
                                        if (get_option('cf_send_email_to_campaign_creator_on_completed') == 'yes') {
                                            $author = get_post_field('post_author', $products->ID);
                                            $creatoremail = get_the_author_meta('user_email', $author);
                                        }
                                        if (get_option('cf_send_email_to_site_admin_on_completed') == 'yes') {
                                            $adminemail = get_option('admin_email');
                                        }
                                        $newarray = array($creatoremail, $adminemail);
                                        if (get_option('cf_send_email_to_others_on_completed') == 'yes') {
                                            $text = trim(get_option('cf_send_email_to_others_mail_on_completed'));
                                            $textAr = explode("\n", $text);
                                            $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                                            foreach ($textAr as $line) {
                                                $newarray[] = $line;
                                            }
                                        }
                                        foreach ($newarray as $fieldarray) {
                                            if (!is_null($fieldarray) || $fieldarray != '') {
                                                include 'completion_campaign_email.php';
                                            }
                                        }
                                    }
                                    update_post_meta($products->ID, '_stock_status', 'outofstock');
                                }
                            }
                        }
                    }if ($targetendselection == '3') {
                        $crowdtargetprice = get_post_meta($products->ID, '_crowdfundinggettargetprice', true);
                        $crowdtotalprice = get_post_meta($products->ID, '_crowdfundingtotalprice', true);
                        $checkstatus = get_post_meta($products->ID, '_stock_status', true);
                        if ($crowdtotalprice >= $crowdtargetprice) {
                            if ($checkstatus == 'instock') {
                                if (get_option('cf_enable_mail_for_campaign_completed') == 'yes') {

                                    if (get_post_meta($products->ID, '_crowdfunding_options', 'true') == '2') {
                                        $crowdtargetprice = get_post_meta($products->ID, '_crowdfundinggettargetprice', true);
                                        $crowdtotalprice = get_post_meta($products->ID, '_crowdfundingtotalprice', true);
                                        $checkstatus = get_post_meta($products->ID, '_stock_status', true);
                                        if ($crowdtotalprice >= $crowdtargetprice) {
                                            $newproducttype[1][] = $products->ID;
                                            update_option('productids', $newproducttype);
                                            include('create_custom_order.php');
                                        }
                                    }
                                    if (get_option('cf_send_email_to_campaign_creator_on_completed') == 'yes') {
                                        $author = get_post_field('post_author', $products->ID);
                                        $creatoremail = get_the_author_meta('user_email', $author);
                                    }
                                    if (get_option('cf_send_email_to_site_admin_on_completed') == 'yes') {
                                        $adminemail = get_option('admin_email');
                                    }
                                    $newarray = array($creatoremail, $adminemail);
                                    if (get_option('cf_send_email_to_others_on_completed') == 'yes') {
                                        $text = trim(get_option('cf_send_email_to_others_mail_on_completed'));
                                        $textAr = explode("\n", $text);
                                        $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                                        foreach ($textAr as $line) {
                                            $newarray[] = $line;
                                        }
                                    }
                                    foreach ($newarray as $fieldarray) {
                                        if (!is_null($fieldarray) || $fieldarray != '') {
                                            include 'completion_campaign_email.php';
                                        }
                                    }
                                }
                                update_post_meta($products->ID, '_stock_status', 'outofstock');
                            }
                        }
                    }if ($targetendselection == '4') {
                        $crowdtargetprice = get_post_meta($products->ID, '_crowdfundinggettargetprice', true);
                        $crowdtotalprice = get_post_meta($products->ID, '_crowdfundingtotalprice', true);
                        $checkstatus = get_post_meta($products->ID, '_stock_status', true);
                        $gettargetdate = get_post_meta($products->ID, '_crowdfundingtodatepicker', true);
                        $gettargethour = get_post_meta($products->ID, '_crowdfundingtohourdatepicker', true);
                        $gettargetminutes = get_post_meta($products->ID, '_crowdfundingtominutesdatepicker', true);
                        if ($gettargetdate != '') {
                            if ($gettargethour != '' || $gettargetminutes != '') {
                                $time = $gettargethour . ':' . $gettargetminutes . ':' . '00';
                                $datestr = $gettargetdate . $time; //Your date
                            } else {
                                $datestr = $gettargetdate . "23:59:59";
                            }//Your date
                            $date = strtotime($datestr); //Converted to a PHP date (a second count)
                            if ($crowdtotalprice >= $crowdtargetprice || $date < time()) {
                                if ($checkstatus == 'instock') {
                                    $gettargetdate = get_post_meta($products->ID, '_crowdfundingtodatepicker', true);
                                    if (get_option('cf_enable_mail_for_campaign_completed') == 'yes') {
                                        if (get_post_meta($products->ID, '_crowdfunding_options', 'true') == '2') {
                                            $crowdtargetprice = get_post_meta($products->ID, '_crowdfundinggettargetprice', true);
                                            $crowdtotalprice = get_post_meta($products->ID, '_crowdfundingtotalprice', true);
                                            $checkstatus = get_post_meta($products->ID, '_stock_status', true);
                                            if ($crowdtotalprice >= $crowdtargetprice) {
                                                $newproducttype[1][] = $products->ID;
                                                update_option('productids', $newproducttype);
                                                include('create_custom_order.php');
                                            }
                                        }
                                        if (get_option('cf_send_email_to_campaign_creator_on_completed') == 'yes') {
                                            $author = get_post_field('post_author', $products->ID);
                                            $creatoremail = get_the_author_meta('user_email', $author);
                                        }
                                        if (get_option('cf_send_email_to_site_admin_on_completed') == 'yes') {
                                            $adminemail = get_option('admin_email');
                                        }
                                        $newarray = array($creatoremail, $adminemail);
                                        if (get_option('cf_send_email_to_others_on_completed') == 'yes') {
                                            $text = trim(get_option('cf_send_email_to_others_mail_on_completed'));
                                            $textAr = explode("\n", $text);
                                            $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                                            foreach ($textAr as $line) {
                                                $newarray[] = $line;
                                            }
                                        }
                                        foreach ($newarray as $fieldarray) {
                                            if (!is_null($fieldarray) || $fieldarray != '') {
                                                include 'completion_campaign_email.php';
                                            }
                                        }
                                    }
                                    update_post_meta($products->ID, '_stock_status', 'outofstock');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

add_action('init', array('CampaignCompletionEmail', 'campaign_completion_email'));
new CampaignCompletionEmail();
