<?php

class FPCrowdFundingContribution {

    public static function cf_replace_add_cart($add_to_cart_text) {
        global $post;
        //  echo $post->ID;
        $postmeta = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
        $checkstockstatus = get_post_meta($post->ID, '_stock_status', true);
        if ($postmeta == 'yes') {
            if ($checkstockstatus == 'instock') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.add_to_cart_button').each(function () {
                            var newvar = jQuery(this).attr('data-product_id');
                            //alert(newvar);
                            jQuery(this).addClass('newone' + newvar);
                        });
                    });
                </script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.button').each(function () {
                            var newvar = jQuery(this).attr('data-product_id');
                            //alert(newvar);
                            if (!jQuery(this).hasClass('newone' + newvar)) {
                                jQuery(this).addClass('outofstock' + newvar);
                            }
                        });
                    });
                </script>
                <?php
            }
            $add_to_cart_text = get_option('cf_add_to_cart_label');
            return $add_to_cart_text;
        }
        return $add_to_cart_text;
    }

    public static function cf_hide_qty_in_product() {
        global $post;
        $postmeta = @get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
        if ($postmeta == 'yes') {
            echo '<script>jQuery(document).ready(function(){jQuery(".qty").css("display","none");});</script>';
        }
    }
    
    public static function redirect_to_product_page_if_product_has_campaign($add_to_cart_text, $product) {
        $postmeta = get_post_meta($product->id, '_crowdfundingcheckboxvalue', true);
        if($postmeta == 'yes'){
//            $checkstockstatus = get_post_meta($product->id, '_stock_status', true);
//            if($checkstockstatus == 'instock'){
                $add_to_cart_text = sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>', esc_url(get_permalink($product->id)), esc_attr($product->id), esc_attr($product->get_sku()), $product->is_purchasable() && $product->is_in_stock() ? '' : '', esc_attr($product->product_type), esc_html(get_option('cf_add_to_cart_label')));
//            }
//            else{
//                $add_to_cart_text = sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>', esc_url(get_permalink($product->id)), esc_attr($product->id), esc_attr($product->get_sku()), $product->is_purchasable() && $product->is_in_stock() ? '' : '', esc_attr($product->product_type), esc_html('Campaign Closed'));
//            }
        }
        return $add_to_cart_text;
    }

}

add_filter('woocommerce_product_single_add_to_cart_text', array('FPCrowdFundingContribution', 'cf_replace_add_cart'));
add_filter('add_to_cart_text', array('FPCrowdFundingContribution', 'cf_replace_add_cart'));
//add_filter('woocommerce_product_add_to_cart_text', array('FPCrowdFundingContribution', 'cf_replace_add_cart'));
add_filter('woocommerce_loop_add_to_cart_link', array('FPCrowdFundingContribution', 'redirect_to_product_page_if_product_has_campaign'), 10, 2);
add_action('wp_head', array('FPCrowdFundingContribution', 'cf_hide_qty_in_product'));
