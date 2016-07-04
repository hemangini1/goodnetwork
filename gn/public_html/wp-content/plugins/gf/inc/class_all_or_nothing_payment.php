<?php

add_action('plugins_loaded', 'woocommerce_galaxy_paypal_preapproval_init');

function woocommerce_galaxy_paypal_preapproval_init() {
    if (!class_exists('WC_Payment_Gateway'))
        return;

    class WC_Galaxy_PayPal_Pre_Approval extends WC_Payment_Gateway {

        function __construct() {

            $this->id = 'galaxy_paypal_preapproval';
            $this->method_title = 'PayPal Adaptive Preapproval Payment';
            $this->has_fields = true;
            $this->icon = plugins_url('images/paypalpre.jpg', __FILE__);
            // $this->has_fields();
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->test_mode = $this->get_option('test_mode');
            $this->crontiming = $this->get_option('crontiming');
            $this->cronhowtime = $this->get_option('cronhowtime');
            $this->enable_disable = $this->get_option('enable_disable');
            $this->securityuser_id = $this->get_option('security_user_id');
            $this->securitypassword = $this->get_option('security_password');
            $this->securitysignature = $this->get_option('security_signature');
            $this->securityapplication_id = $this->get_option('security_application_id');
            $this->pri_r_paypal_mail = $this->get_option('pri_r_paypal_mail');
            $this->preapproval_days = $this->get_option('preapproval_days');
            $this->errormsg = $this->get_option('errormsg');
            $this->cronpurchase = $this->get_option('cronpurchase');
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'fp_cron_preapproval_job'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'fp_cron_preapproval_job'));
        }

//Cron Job
        function fp_cron_preapproval_job() {

            delete_option('takepreapproval');
            wp_clear_scheduled_hook('fp_cron_preapproval');
             $timezone=wc_timezone_string();
               if($timezone!='')
               {
                   $timezonedate=  date_default_timezone_set($timezone);
               }
               else{
                   $timezonedate=  date_default_timezone_set('UTC');
               }

            if (wp_next_scheduled('fp_cron_preapproval') == false) {
                wp_schedule_event(time(), 'hourlys', 'fp_cron_preapproval');
            }
        }

//Admin Setting For Preapproval
        function init_form_fields() {

            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'galaxyfunder'),
                    'type' => 'checkbox',
                    'label' => __('PayPal Adaptive Preapproval Payment', 'galaxyfunder'),
                    'default' => 'yes'),
                'title' => array(
                    'title' => __('Title:', 'galaxyfunder'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'galaxyfunder'),
                    'default' => __('PayPal Pre Approval', 'galaxyfunder')
                ),
                'description' => array(
                    'title' => __('Description', 'galaxyfunder'),
                    'type' => 'textarea',
                    'default' => 'Pay with PayPal Preapproval Payment. You can pay with your credit card if you don\'t have a PayPal account',
                    'desc_tip' => true,
                    'description' => __('This controls the description which the user sees during checkout.', 'galaxyfunder'),
                ),
                'enable_disable' => array(
                    'title' => __('Cron for Automatic Processing of Preapproval Payment', 'galaxyfunder'),
                    'type' => 'checkbox',
                    'label' => __('Enable', 'galaxyfunder'),
                    'default' => 'yes',
                    'id' => 'cronshowhide',
                ),
                'crontiming' => array(
                    'title' => __('Cron Time Type', 'galaxyfunder'),
                    'type' => 'select',
                    'label' => __('PayPal preapproval', 'galaxyfunder'),
                    'default' => 'hours',
                    'options' => array('minutes' => __('minutes', 'galaxyfunder'), 'hours' => __('hours', 'galaxyfunder'), 'days' => __('days', 'galaxyfunder')),
                //'class' => 'cron_preapproval_type',
                ),
                'cronhowtime' => array(
                    'title' => __('Cron Time', 'galaxyfunder'),
                    'name' => __('preapprovaltimeset', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '3',
                //'class' => 'cron_preapproval_type',
                //'clone_id' => 'cron_preapproval_time',
                ),
                'cronpurchase' => array(
                    'title' => __('Number of Days for Processing Preapproval Payment', 'galaxyfunder'),
                    'name' => __('preapprovaldaysfrom', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '30',
                    'placeholder' => __('Enter the Number of Days', 'galaxyfunder'),
                    'description' => __('Number of Days for Processing Preapproval Payment after Date of Purchase', 'galaxyfunder'),
                ),
                'securityuser_id' => array(
                    'title' => __('API User ID', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please enter your API User ID associated with your paypal account', 'galaxyfunder'),
                ),
                'securitypassword' => array(
                    'title' => __('API Password', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please enter your API Password associated with your paypal account', 'galaxyfunder'),
                ),
                'securitysignature' => array(
                    'title' => __('API Signature', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please enter your API Signature associated with your paypal account', 'galaxyfunder'),
                ),
                'securityapplication_id' => array(
                    'title' => __('Application ID', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please enter your Application ID created with your paypal account', 'galaxyfunder'),
                ),
                'pri_r_paypal_mail' => array(
                    'title' => __('Receiver PayPal Mail', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please enter the receiver  paypal mail', 'galaxyfunder'),
                ),
                'preapproval_days' => array(
                    'title' => __('Validity of Preapproval Payment in Days', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '60',
                    'description' => __('If left empty or less than zero, 60 days will be set', 'galaxyfunder'),
                    'placeholder' => __('maximum 365 Days', 'galaxyfunder'),
                ),
                'errormsg' => array(
                    'title' => __('Error Message in Cart', 'galaxyfunder'),
                    'desc' => __('Enter the Message which will displayed if cart items has more than one product', 'galaxyfunder'),
                    'type' => 'textarea',
                    'default' => 'A Preapproval enabled  Product has been removed from your cart. Due to payment gateway restrictions, products and Preapproval enabled Product can not be purchased at the same time.',
                ),
                'test_mode' => array(
                    'title' => __('PayPal Pre Approval sandbox', 'galaxyfunder'),
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Pre Approval sandbox', 'galaxyfunder'),
                    'default' => 'no',
                    'description' => sprintf(__('PayPal Adaptive sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>.', 'preapproval'), 'https://developer.paypal.com/'),
                ),
            );
        }

//Process of Preapproval Payment Request Preapprovalkey

        function process_payment($order_id) {
            $order = new WC_Order($order_id);
            $order->reduce_order_stock();
            $success_url = $this->get_return_url($order);
            $cancel_url = str_replace("&amp;", "&", $order->get_cancel_order_url());
            $security_user_id = $this->securityuser_id;
            $security_password = $this->securitypassword;
            $security_signature = $this->securitysignature;
            $security_application_id = $this->securityapplication_id;

            if (empty($this->preapproval_days) || $this->preapproval_days == 0) {
                $Preapprovaldays = 60;
            } else {
                $Preapprovaldays = $this->preapproval_days;
            }


            if ("yes" == $this->test_mode) {

                $paypal_pay_action_url = "https://svcs.sandbox.paypal.com/AdaptivePayments/Preapproval";
                $paypal_pay_auth_without_key_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-preapproval&preapprovalkey=";
            } else {
                $paypal_pay_action_url = "https://svcs.paypal.com/AdaptivePayments/Preapproval";
                $paypal_pay_auth_without_key_url = "https://www.paypal.com/cgi-bin/webscr?cmd=_ap-preapproval&preapprovalkey=";
            }

            $ipnNotificationUrl = esc_url_raw(add_query_arg(array('ipn' => 'set', 'self_custom' => $order_id), home_url('/')));

            if (get_option('timezone_string') != '') {
                $timezonedate = date_default_timezone_set(get_option('timezone_string'));
            } else {
                $timezonedate = date_default_timezone_set('UTC');
            }

            $startDate = gmdate("Y-m-d\TH:i:s\Z");
            $endDate = gmdate("Y-m-d\TH:i:s\Z", strtotime('+' . $Preapprovaldays . ' days'));
            // $endDate = date('Y-m-d',strtotime('+364 days'));
            //var_dump($newEndingDate);
            $headers_array = array("X-PAYPAL-SECURITY-USERID" => $security_user_id,
                "X-PAYPAL-SECURITY-PASSWORD" => $security_password,
                "X-PAYPAL-SECURITY-SIGNATURE" => $security_signature,
                "X-PAYPAL-APPLICATION-ID" => $security_application_id,
                "X-PAYPAL-REQUEST-DATA-FORMAT" => "NV",
                "X-PAYPAL-RESPONSE-DATA-FORMAT" => "JSON",
            );

            $maincode = array(
                'returnUrl' => $success_url,
                'cancelUrl' => $cancel_url,
                'startingDate' => $startDate,
                'endingDate' => $endDate,
                'maxAmountPerPayment' => $order->get_total(),
                'maxNumberOfPayments' => '1',
                'maxTotalAmountofAllPayments' => $order->get_total(),
                'currencyCode' => get_woocommerce_currency(),
                'custom' => $order_id,
                'requestEnvelope.errorLanguage' => 'en_US',
                'ipnNotificationUrl' => $ipnNotificationUrl,
            );

            $pay_result = wp_remote_request($paypal_pay_action_url, array('method' => 'POST', 'timeout' => 20, 'headers' => $headers_array, 'body' => $maincode));
            $jso = json_decode($pay_result['body']);
            $payment_url = "$paypal_pay_auth_without_key_url" . $jso->preapprovalKey;
            update_post_meta($order_id, 'preapprovalKey', $jso->preapprovalKey);
            update_post_meta($order_id, 'startdateprekey', $startDate);
            update_post_meta($order_id, 'enddateprekey', $endDate);
            update_post_meta($order_id, 'totalamount', $order->get_total());
            //update_post_meta($order_id,'namecck',$order->get_items());
            return array('result' => 'success', 'redirect' => $payment_url);
        }

    }

    function woocommerce_galaxy_paypal_preapproval_methods($methods) {
        $methods[] = 'WC_Galaxy_PayPal_Pre_Approval';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_galaxy_paypal_preapproval_methods');
}
