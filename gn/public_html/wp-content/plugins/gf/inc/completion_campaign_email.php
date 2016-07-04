<?php

global $woocommerce;
global $unsubscribe_link2;
if (get_user_meta($author, 'gf_email_unsub_value', true) != 'yes') {
    $author = get_post_field('post_author', $products->ID);
    $tos = $fieldarray;
    $message_details=get_option('campaign_completion_mail_message');
    $find_array=array('[cf_site_campaign_url]','[cf_site_campaign_shipping_address]');
    $replace_array=array(get_permalink($products->ID),CrowdFunding::fetch_shipping_address($products->ID));
//    var_dump($replace_array);
    $after_replace_message=str_replace($find_array,$replace_array,$message_details);
//    var_dump($after_replace_message);
    $subject = do_shortcode(get_option('campaign_completion_mail_subject'));
    $message = do_shortcode($after_replace_message);
    $create_wpnonce = wp_create_nonce('gf_unsubscribe_' . $author);
    $link_for_unsubscribe = esc_url_raw(add_query_arg(array('userid' => $author, 'unsub' => 'yes', 'nonce' => $create_wpnonce), site_url()));
    
    $unsubscribe_link1 = get_option('gf_unsubscribe_link_for_email');
    $updated_link_unsubscribe = CrowdFunding::add_hyperlink_for_unsub_link($link_for_unsubscribe);
    $unsubscribe_link1 = get_option('gf_unsubscribe_link_for_email');
    $unsubscribe_link2 = str_replace('{gfsitelinkwithid}', $updated_link_unsubscribe, $unsubscribe_link1);
    add_filter('woocommerce_email_footer_text', 'unsubscribe_footer_link');
    ob_start();
    wc_get_template('emails/email-header.php', array('email_heading' => $subject));
    echo $message;
    wc_get_template('emails/email-footer.php');
    $woo_temp_msg = ob_get_clean();
    $mainnames = get_option('woocommerce_email_from_name');
    $mainemails = get_option('woocommerce_email_from_address');
    // To send HTML mail, the Content-type header must be set
    $headerss = 'MIME-Version: 1.0' . "\r\n";
    $headerss .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    // Additional headers
    $headerss .= 'From:' . $mainnames . '  <' . $mainemails . '>' . "\r\n";
    // Mail it
    if ((float) $woocommerce->version <= (float) ('2.2.0')) {
        $mail = wp_mail($tos, $subject, $woo_temp_msg, $headerss);
    } else {
        $mailer = WC()->mailer();
        $mailer->send($tos, $subject, $woo_temp_msg, '', '');
    }
}