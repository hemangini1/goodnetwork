<?php

class CFPerkMetaBox {

    public static function add_perk_meta_box() {
        add_meta_box('CFPerkMetaBox::perk_reward_meta_box', __('Create Perk Rule', 'galaxyfunder'), 'CFPerkMetaBox::perk_reward_meta_box', 'product', 'normal', 'core');
    }

    public static function perk_reward_meta_box() {
        global $woocommerce;
        global $post;
        $productids = array();
        $producttitles = array();
        $args = array(
            'post_type' => 'product',
            'numberposts' => '-1',
            'post_status' => 'publish',
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
//         echo '<pre>'; var_dump(get_posts($args));echo '</pre>';
        $combined = array();
        $getproducts = get_posts($args);
        $newvariable = array();

        $timezone = wc_timezone_string();
        if ($timezone != '') {
            $timezonedate = date_default_timezone_set($timezone);
        } else {
            $timezonedate = date_default_timezone_set('UTC');
        }

        if (is_array($getproducts)) {
            foreach ($getproducts as $product) {
                $product_type = get_product($product->ID);
                if ($product_type->is_type('simple')) {
                    if (get_post_meta($product->ID, '_crowdfundingcheckboxvalue', true) != 'yes') {
                        $productids[] = $product->ID;
                        $producttitles[] = $product->post_title;
                    }
                } else {
                    if ($product_type->is_type('variable')) {
                        if (is_array($product_type->get_available_variations())) {
                            foreach ($product_type->get_available_variations() as $getvariation) {
                                $productids[] = $getvariation['variation_id'];
                                $producttitles[] = get_the_title($getvariation['variation_id']);
                            }
                        }
                    }
                }
            }
        }
        if (is_array($productids) && is_array($producttitles)) {
            @$combined = array_combine((array) $productids, (array) $producttitles);
        }
//        var_dump($combined);

        wp_nonce_field(plugin_basename(__FILE__), 'cfperkrulenonce');
        ?>
        <div id="meta_inner">
        <?php
        $perkrule = get_post_meta($post->ID, 'perk', true);

        //var_dump($perkrule);
        if (is_array($perkrule)) {
            foreach ($perkrule as $i => $perk) {
                ?>
                    <div class="panel woocommerce_options_panel" style="display: block;">
                        <div class="options_group"  style="border-bottom: 1px solid #DFDFDF !important; border-top: 1px solid #FFFFFF !important; padding-bottom:10px; margin-bottom:10px;">
                            <p class="form-field">
                                <label>Name of Perk</label>
                                <input type="text" name="perk[<?php echo $i; ?>][name]" class="short" value="<?php echo $perk['name']; ?>"/>
                            </p>
                            <p class="form-field _cf_selection_fields "><label for="_cf_selection_fields">Choose Products</label>
                                <select name="perk[<?php echo $i; ?>][choose_products]" data-placeholder="Choose Product..." style="width:250px;" id="perkproductselection<?php echo $i; ?>">
                                    <option value=""></option>
                <?php
                if (is_array($combined)) {
                    foreach ($combined as $newvalue => $key) {
                        // echo "<option data-price = " . get_post_meta($newvalue, "_regular_price", true) . " " . (get_post_meta($post->ID, 'perk[' . $i . '][choose_product]', true) == $key) ? "selected=selected" : "" . "value=" . $newvalue . ">" . $key . "</option>";
                        // echo "<option value=" . $newvalue . " " . ($perk['choose_product'] == $newvalue) ? "selected=selected" : "" . ">" . $key . "</option>";
                        //echo $newvalue;
                        ?>
                                            <option data-price ="<?php echo get_post_meta($newvalue, '_regular_price', true); ?>" <?php
                                            if ($perk['choose_products'] == $newvalue) {
                                                echo "selected=selected";
                                            }
                                            ?> value="<?php echo $newvalue; ?>"><?php echo $key; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                </select>
                            </p>
                            <p class="form-field">
                                <label>Perk Amount</label>
                                <input type="text" name="perk[<?php echo $i; ?>][amount]" id="perkamount<?php echo $i; ?>" class="short" value="<?php echo $perk['amount']; ?>"/>
                            </p>

                            <p class = "form-field">
                                <label>Description</label>
                                <textarea rows = "3" cols = "14" style = "height:110px;
                                          width:360px;
                                          " name = "perk[<?php echo $i;
                            ?>][description]" class = "short"><?php echo $perk['description'];
                            ?></textarea>
                            </p>
                <!--                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    if(jQuery(".cf_limit_perk_count").val() == "cf_unlimited"){
                                           //alert('hi');
                                        jQuery('.test').parent().hide();
                                    }else{
                                        jQuery('.test').parent().show();
                                    }
                                    jQuery(".cf_limit_perk_count").change(function(){
                                       if(jQuery(this).val() == "cf_unlimited"){
                                           //alert('hi');
                                           jQuery('.test').parent().hide();
                                       }else{
                                        jQuery('.test').parent().show();
                                       }
                                    });
                                });
                            </script>-->
                            <p class="form-field">
                                <label>Limit Perk Claim</label>
                                <select name="perk[<?php echo $i; ?>][limitperk]" id="perk_limitation<?php echo $i; ?>" class="cf_limit_perk_count">
                                    <option value="cf_limited" <?php echo $perk['limitperk'] == 'cf_limited' ? 'selected=selected' : ''; ?>>Limited</option>
                                    <option value="cf_unlimited" <?php echo $perk['limitperk'] == 'cf_unlimited' ? 'selected=selected' : ''; ?>>Unlimited</option>
                                </select>

                            </p>
                            <p class="form-field">
                                <label>Perk Claim Max Count</label>
                                <input type ="text" name="perk[<?php echo $i; ?>][claimcount]" id="perk_claimcount<?php echo $i; ?>" class="short test" value="<?php echo $perk['claimcount']; ?>"/>
                            </p>
                            <p class="form-field">
                                <label>Estimated Delivery on</label>
                                <input type="text" name="perk[<?php echo $i; ?>][deliverydate]" id="perkid<?php echo $i; ?>" class="short" value="<?php echo $perk['deliverydate']; ?>"/>
                            </p>
                            <span class="remove button-secondary">Remove Perk Rule</span>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery('#perkid' + <?php echo $i; ?>).datepicker({
                                changeMonth: true,
                                //                            onClose: function(selectedDate) {
                                //                                jQuery("#_crowdfundingfromdatepicker").datepicker("option", "maxDate", selectedDate);
                                //                            }
                            });
                            jQuery(document).on('change', '#perkproductselection<?php echo $i; ?>', function () {
                                var thisvalue = jQuery('option:selected', this).attr('data-price');
                                jQuery("#perkamount<?php echo $i; ?>").val(thisvalue);
                            }

                            );
                            if (jQuery('#perk_limitation<?php echo $i; ?>').val() == 'cf_unlimited') {
                                jQuery('#perk_claimcount<?php echo $i; ?>').parent().hide();
                            } else {
                                jQuery('#perk_claimcount<?php echo $i; ?>').parent().show();
                            }
                            jQuery(document).on('change', '#perk_limitation<?php echo $i; ?>', function () {
                                if (jQuery(this).val() == 'cf_unlimited') {
                                    jQuery('#perk_claimcount<?php echo $i; ?>').parent().hide();
                                } else {
                                    jQuery('#perk_claimcount<?php echo $i; ?>').parent().show();
                                }
                            });
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                jQuery("#perkproductselection<?php echo $i; ?>").chosen();
                <?php } else { ?>
                                jQuery("#perkproductselection<?php echo $i; ?>").select2();
                <?php } ?>
                        });</script>

                <?php
                $i = $i + 1;
            }
        }
        ?>
            <span id="here"></span>
            <span class="add button-primary"><?php _e('Add Perk Rule'); ?></span>
            <script>
                jQuery(document).ready(function () {

                    jQuery(".add").click(function () {
                        var countperk = Math.round(new Date().getTime() + (Math.random() * 100));
                        jQuery('#here').append('<div class="panel woocommerce_options_panel" style="display: block;"><div class="options_group" style="border-bottom: 1px solid #DFDFDF !important; border-top: 1px solid #FFFFFF !important;padding-bottom:10px; margin-bottom:10px;"><p class="form-field"><label>Name of Perk</label><input type="text" name="perk[' + countperk + '][name]" class="short" value=""/></p>\n\
                \n\<p class="form-field _cf_selection_field " style="display: block;"><label for="_cf_product_selection">Choose Products</label><select class="perkselect' + countperk + '" name="perk[' + countperk + '][choose_products]" data-placeholder="Choose Product..." style="width:250px;" id="_cf_product_selection' + countperk + '"><option value=""></option><?php
        if (is_array($combined)) {
            foreach ($combined as $newvalue => $key) {
                // echo "<option data-price = " . get_post_meta($key, "_regular_price", true) . " " . (get_post_meta($post->ID, 'perk[' . $i . '][choose_product]', true) == $key) ? "selected=selected" : "" . "value=" . $key . ">" . $newvalue . "</option>";
                echo "<option data-price=" . get_post_meta($newvalue, '_regular_price', true) . " value=" . $newvalue . ">" . addslashes($key) . "</option>";
            }
        }
        ?>\n\
        </select></p><p class="form-field"><label>Perk Amount</label><input type="text" id="perkamount' + countperk + '" name="perk[' + countperk + '][amount]" class="short" value=""/></p>\n\
                <p class="form-field"><label>Description</label><textarea rows="3" cols="14" style="height:110px;width:360px;" name="perk[' + countperk + '][description]" class="short" value=""></textarea></p>\n\
                    <p class="form-field"><label>Limit Perk Claim</label><select name="perk[' + countperk + '][limitperk]" id="perk_limitation' + countperk + '" class="cf_limit_perk_count"><option value ="cf_limited" >Limited</option><option value ="cf_unlimited">Unlimited</option></select></p>\n\
        <p class="form-field"><label>Perk Claim Max Count</label><input type ="text" id="perk_claimcount' + countperk + '" name="perk[' + countperk + '][claimcount]" class="short test"  value=""/></p>\n\
         <p class="form-field"><label>Estimated Delivery on</label><input type="text" name="perk[' + countperk + '][deliverydate]" id="perkid' + countperk + '" class="short" value=""/></p><span class="remove button-secondary">Remove Perk Rule</span></div></div>');
                        jQuery(document).on('change', '#_cf_product_selection' + countperk, function () {
                            var thisvalue = jQuery('option:selected', this).attr('data-price');
                            jQuery("#perkamount" + countperk).val(thisvalue);
                        }
                        );
                        if (jQuery('#perk_limitation' + countperk).val() == 'cf_unlimited') {
                            jQuery('#perk_claimcount' + countperk).parent().hide();
                        } else {
                            jQuery('#perk_claimcount' + countperk).parent().show();
                        }
                        jQuery(document).on('change', '#perk_limitation' + countperk, function () {
                            if (jQuery(this).val() == 'cf_unlimited') {
                                jQuery('#perk_claimcount' + countperk).parent().hide();
                            } else {
                                jQuery('#perk_claimcount' + countperk).parent().show();
                            }
                        });
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery('.perkselect' + countperk).chosen();
        <?php } else { ?>
                            jQuery('.perkselect' + countperk).select2();
        <?php } ?>
                        jQuery('#perkid' + countperk).datepicker({
                            changeMonth: true,
                            //                            onClose: function(selectedDate) {
                            //                                jQuery("#_crowdfundingfromdatepicker").datepicker("option", "maxDate", selectedDate);
                            //                            }
                        });
                        return false;
                    });
                    jQuery(document).on('click', '.remove', function () {
                        //                        alert("You Clicked");
                        //                        console.log(jQuery(this).parent());
                        jQuery(this).parent().remove();
                    });
                });</script>
        </div><?php
    }

    public static function perk_save_dynamic_data($post_id) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!isset($_POST['cfperkrulenonce']))
            return;
        if (!wp_verify_nonce($_POST['cfperkrulenonce'], plugin_basename(__FILE__)))
            return;
        $perkrule = $_POST['perk'];
        update_post_meta($post_id, 'perk', $perkrule);
//        $newone = get_post_meta($post_id, 'orderids', true);
//        foreach ($newone as $oneorderid) {
//            if (get_post_meta($oneorderid, 'perk_maincontainer' . $post_id, true) != '') {
//                $getperkprice[] = get_post_meta($oneorderid, 'perk_maincontainer' . $post_id, true) . "<br>";
//            }
//        }
//
//        $j = 0;
//        $perkrule = get_post_meta($post_id, 'perk', true);
//        foreach ($perkrule as $perk) {
//            foreach ($getperkprice as $price) {
//                if ($perk['amount'] != (int) $price) {
//                    echo $length = count(array_keys($getperkprice, (int) $price));
//                    $claimcount = get_post_meta($post_id, 'perk[' . $j . '][claimcount]', true);
//                    $currentcount = get_post_meta($post_id, 'perk[' . $j . '][update_perk_claim]', true);
//                    if ($claimcount > $currentcount) {
//                        // delete_post_meta($post_id, 'perk[' . $j . '][update_perk_claim]');
//                    }
//                }
//            }
//            $j++;
//        }
    }

    public static function get_attribute_slug($varidid) {
        $get_productid = get_product($varidid);
        $get_variations = $get_productid->get_variation_attributes();
        foreach ($get_variations as $key => $value) {
            $var[] = $key . " : " . $value;
        }
        return $var;
    }

    public static function cf_perk_rule_front_end() {
        global $post, $product;
        global $woocommerce;
        $perkrule = get_post_meta($post->ID, 'perk', true);
        $perk_image_Size = get_option('cf_perk_url__image_type_size');
        $i = 0;
        $getperkprice = array();
        if (is_array($perkrule)) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
            //                    jQuery('.perkrulequantity').attr('readonly', 'true');
            <?php if (get_option('cf_perk_selection_type') == '1') { ?>
                        var getlistofperksquantity;
                        var listiteration;
                        jQuery('.perkrule').click(function (event) {
                            jQuery('.perkrule').removeClass("selected");
                            jQuery(this).addClass('selected');
                            var getamount = jQuery(this).attr('cf_data-amount');
                            // alert(getamount);
                            var getchoosedproduct = jQuery(this).attr('data-choose_products');
                            jQuery('.single_add_to_cart_button').attr('data-perk', getamount);
                            jQuery('.addfundraiser<?php echo $post->ID; ?>').data('perk', getamount);
                            var productid = jQuery(this).attr('data-productid');
                            var getname = jQuery(this).attr('data-perkname');
                            jQuery('.addfundraiser<?php echo $post->ID; ?>').val(getamount * jQuery(this).attr('data-quantity'));
                            var getdataquantity = jQuery(this).attr('data-quantity');
                            var dataiteration = jQuery(this).attr('data-iteration');
                            jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                            jQuery('.subdivquantity').show();
                            if (jQuery(this).hasClass('selected')) {
                                jQuery(this).find('.subdivquantity').hide();
                            }

                            var perkiteration = jQuery(this).attr('data-iteration');
                            listiteration = perkiteration;
                            console.log(listiteration);
                            var mainiteration = jQuery(this).attr('data-iteration');
                            var getmyquantity = getdataquantity;

                            getlistofperksquantity = (mainiteration + "_" + getmyquantity);
                            console.log(getlistofperksquantity);
                            if (getamount === '') {
                                var dataparam = ({
                                    action: 'selectperkoption',
                                    session_destroy: true
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {
                                            var newresponse = response.replace(/\s/g, '');
                                            if (newresponse === 'success') {
                                                //location.reload();
                                            }
                                        });
                                return false;
                            } else {
                                var whichperk = jQuery(this).attr('id');
                                var dataparam = ({
                                    action: 'selectperkoption',
                                    getamount: getamount,
                                    getname: getname,
                                    productid: productid,
                                    explodequantity: getdataquantity + '_' + getamount,
                                    choosedproduct: getchoosedproduct,
                                    listiteration: listiteration,
                                    getlistofperksquantity: getlistofperksquantity,
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {
                                            var newresponse = response.replace(/\s/g, '');
                                            if (newresponse === 'success') {
                                                //location.reload();
                                            }
                                        });
                                return false;
                            }
                        });
            <?php } else { ?>
                        var pvalue = [];
                        var listproduct = [];
                        var productids = [];
                        var listperkname = [];
                        var prkamount = [];
                        var quantitys = [];
                        var perknamequantity = [];
                        var explodequantity = [];
                        var getlistofperksquantity = [];
                        var maindatas = [];
                        var listiteration = [];
                        jQuery(document).on('click', '.perkrule', function () {
                //                        jQuery('.perkrule').click(function (event) {
                            // console.log(jQuery(this).find('.perkrulequantity').val());
                            if (jQuery(this).hasClass('selected')) {
                                jQuery(this).removeClass('selected');
                            } else {
                                jQuery(this).addClass('selected');
                                //jQuery('.noperk').removeClass('selected');
                            }
                            if (jQuery(this).attr('data-quantity')) {
                                var getamount = jQuery(this).attr('cf_data-amount') * jQuery(this).attr('data-quantity');
                            }
                            var getchoosedproduct = jQuery(this).attr('data-choose_products');
                            if (jQuery.inArray(getchoosedproduct, listproduct) === -1) {
                                listproduct.push(getchoosedproduct);
                            } else {
                                listproduct = jQuery.grep(listproduct, function (value) {
                                    return value !== getchoosedproduct;
                                });
                            }

                            var getcurrentquantity = jQuery(this).attr('data-quantity');
                            var gtamnt = jQuery(this).attr('cf_data-amount');
                            if (jQuery.inArray(getcurrentquantity + '_' + gtamnt, explodequantity) === -1) {
                                explodequantity.push(getcurrentquantity + '_' + gtamnt);
                            } else {
                                explodequantity = jQuery.grep(explodequantity, function (value) {
                                    return value !== getcurrentquantity + '_' + gtamnt;
                                });
                            }
                            //  console.log(explodequantity);
                            var getquantity = jQuery(this).find('.perkrulequantity').val();
                            if (jQuery.inArray(getquantity, perknamequantity) === -1) {
                                perknamequantity.push(getquantity);
                            }
                            var perkiteration = jQuery(this).attr('data-iteration');
                            if (jQuery.inArray(perkiteration, listiteration) === -1) {
                                listiteration.push(perkiteration);
                            } else {
                                listiteration = jQuery.grep(listiteration, function (value) {
                                    return value !== perkiteration;
                                });
                            }
                            console.log(listiteration);
                            var mainiteration = jQuery(this).attr('data-iteration');
                            var getmyquantity = jQuery(this).find('.perkrulequantity').val();
                            if (jQuery.inArray(mainiteration + "_" + getmyquantity, getlistofperksquantity) === -1) {
                                getlistofperksquantity.push(mainiteration + "_" + getmyquantity);
                            } else {
                                getlistofperksquantity = jQuery.grep(getlistofperksquantity, function (value) {

                                    return value !== (mainiteration + "_" + getmyquantity);
                                });
                            }
                            //console.log(getlistofperksquantity);
                            var getiteration = jQuery(this).attr('data-iteration');
                            var indiamount = jQuery(this).attr('cf_data-amount');
                            if (jQuery.inArray(getiteration + "_" + indiamount, prkamount) === -1) {
                                prkamount.push(indiamount);
                            } else {
                                prkamount = jQuery.grep(prkamount, function (value) {
                                    return value !== (getiteration + "_" + indiamount);
                                });
                            }
                            var getproductid = jQuery(this).attr('data-productid');
                            if (jQuery.inArray(getproductid, productids) === -1) {
                                productids.push(getproductid);
                            } else {
                                productids = jQuery.grep(productids, function (value) {
                                    return value !== getproductid;
                                });
                            }
                            var getperkname = jQuery(this).attr('data-perkname');
                            if (jQuery.inArray(getperkname, listperkname) === -1) {
                                listperkname.push(getperkname);
                            } else {
                                listperkname = jQuery.grep(listperkname, function (value) {
                                    return value !== getperkname;
                                });
                            }
                            jQuery('.single_add_to_cart_button').attr('data-perk', getamount);
                            var productid = jQuery(this).attr('data-productid');
                            var getname = jQuery(this).attr('data-perkname');
                            if (jQuery(this).attr('data-quantity')) {
                                if (jQuery(this).hasClass('selected')) {

                                }
                            }
                            var elementValue = jQuery(this).attr('cf_data-amount') * jQuery(this).attr('data-quantity');
                            jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                            if (jQuery(this).hasClass('selected')) {
                                var indnames = jQuery(this).attr('data-perkname');
                                var indamount = jQuery(this).attr('cf_data-amount');
                                jQuery(this).find('.subdivquantity').hide();
                            } else {
                                jQuery(this).find('.subdivquantity').show();
                            }
                            //pvalue = [jQuery(this).attr('data-iteration')];
                            if (jQuery.inArray(getiteration + "_" + elementValue, pvalue) === -1) {
                                pvalue.push(getiteration + "_" + elementValue);
                            } else {
                                pvalue = jQuery.grep(pvalue, function (value) {
                                    return value !== (getiteration + "_" + elementValue);
                                });
                            }
                            var total = 0;
                            for (var i = 0; i < pvalue.length; i++) {
                                // alert(jQuery('#perkrulequantityvalue' + i).val());

                                total += parseFloat(pvalue[i].split('_')[1]);
                            }
                            jQuery('.addfundraiser<?php echo $post->ID; ?>').val(total);
                            jQuery('.single_add_to_cart_button').attr('data-perk', total);
                            if ((total === 0) || elementValue === '') {
                                var dataparam = ({
                                    action: 'selectperkoption',
                                    session_destroy: true
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {
                                            var newresponse = response.replace(/\s/g, '');
                                            if (newresponse === 'success') {
                                                //jQuery('.perkrule').removeClass('selected');
                                                // jQuery('.noperk').addClass('selected');
                                                //jQuery(this).addClass('selected');
                                            }
                                        });
                                return false;
                            } else {
                                var dataparam = ({
                                    action: 'selectperkoption',
                                    getamount: total,
                                    getname: listperkname,
                                    productid: productid,
                                    sendquantity: perknamequantity,
                                    choosedproduct: listproduct,
                                    explodequantity: explodequantity,
                                    indnames: indnames,
                                    indamount: indamount,
                                    listamount: prkamount,
                                    listiteration: listiteration,
                                    getlistofperksquantity: getlistofperksquantity,
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {
                                            var newresponse = response.replace(/\s/g, '');
                                            if (newresponse === 'success') {
                                            }
                                        });
                                return false;
                            }
                        });
            <?php } ?>
                    jQuery('.perkrule a').click(function (evt) {
                        evt.stopPropagation();
                    });
                    jQuery('.perkrule .subdivquantity').click(function (evt) {
                        evt.stopPropagation();
                    });
                    jQuery('.perkrulequantity').val('1');
                    jQuery('.perkrule').attr('data-quantity', '1');
                    jQuery('.cfplus').click(function () {
                        var parentselector = jQuery(this).parent().parent().attr('id');
                        var getvalue = jQuery('#' + parentselector).attr('cf_data-amount');
                        var getiteration = jQuery('#' + parentselector).attr('data-iteration');
                        var getquantityvalue = parseInt(jQuery('#perkrulequantityvalue' + getiteration).val());
                        if (jQuery(this).attr("data-limit") == 'limited') {
                            var max_claim = jQuery(this).attr("data-max");
                            if (getquantityvalue < max_claim) {
                                jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue + 1);
                            }
                            else {
                                jQuery('#perkrulequantityvalue' + getiteration).val(max_claim);
                            }
                        }
                        else {
                            jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue + 1);
                        }
                        if (getquantityvalue < 1) {
                            jQuery('#perkrulequantityvalue' + getiteration).val(1);
                        }
                        var newupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                        jQuery(this).parent().parent().attr('data-quantity', parseInt(newupdate));
                        return false;
                    });
                    jQuery('.cfminus').click(function () {

                        var parentselector = jQuery(this).parent().parent().attr('id');
                        var getvalue = jQuery('#' + parentselector).attr('cf_data-amount');
                        var getiteration = jQuery('#' + parentselector).attr('data-iteration');
                        var getquantityvalue = parseInt(jQuery('#perkrulequantityvalue' + getiteration).val());
                        if (jQuery(this).attr("data-limit") == 'limited') {
                            var max_claim = jQuery(this).attr("data-max");
                            if (getquantityvalue > max_claim) {
                                jQuery('#perkrulequantityvalue' + getiteration).val(max_claim);
                            }
                            else {
                                jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                if (getquantityvalue > 1) {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                    var minusupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                                    jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                } else {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(1);
                                    var minusupdate = jQuery('.perkrulequantity').val();
                                    jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                }
                            }
                        }
                        else {
                            if (getquantityvalue > 1) {
                                jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                var minusupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                                jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                            } else {
                                jQuery('#perkrulequantityvalue' + getiteration).val(1);
                                var minusupdate = jQuery('.perkrulequantity').val();
                                jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                            }
                        }
                        return false;
                    });
                });</script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('.perkrule').each(function () {
                        var newperkruleamount = jQuery(this).attr('cf_data-amount');
                        var compareruleamount = '<?php echo get_post_meta($post->ID, 'perk_maincontainer' . $post->ID, true); ?>';
                        jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                        if (newperkruleamount === compareruleamount) {
                            // jQuery('.perkrule').hide();
                            jQuery(this).show();
            //                            jQuery('.addfundraiser<?php echo $post->ID; ?>').val(compareruleamount);
                            // jQuery(this).removeClass('perkrule');
                            //jQuery(this).addClass('nodropclass');
                        }
                    });
                });</script>
            <?php
            if (get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
                ?>
                <div id="informationperk"></div>
                <h3><?php echo get_option('cf_perk_head_label'); ?></h3>
                <?php if (get_option('cf_perk_selection_type') == '1') { ?>
                    <div class="perkrule noperk" id="perk_maincontainer" data-productid="<?php echo $post->ID; ?>" cf_data-amount="">
                    <?php echo get_option('cf_no_perk_label'); ?>
                    </div>
                    <?php } ?>
                <?php
                foreach ($perkrule as $i => $perk) {
                    $perkname = str_replace('', '_', $perk['name']);
                    $newcounterclaim = (int) get_post_meta($post->ID, $perkname . $perk['amount'] . 'update_perk_claim', true);
                    $targetclaim = $perk['claimcount'];

                    $is_unlimited = $perk['limitperk'];
                    if ($is_unlimited == 'cf_limited') {
                        $myperk = str_replace('', '_', $perk['name']);
                        $perk1 = get_post_meta($post->ID, $myperk . $perk['amount'] . 'update_perk_claim', true);
                        $total_perks = $perk['claimcount'];
                        $max = $total_perks - $perk1;
                        if (($targetclaim > $newcounterclaim) && ($targetclaim != '')) {
                            ?>
                            <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>" data-choose_products ="<?php echo $perk['choose_products']; ?>">
                            <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                    <?php if (get_option('cf_perk_quantity_display_selection') == '1') { ?>
                                        <div class="subdivquantity">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                        </div>
                                    <?php
                                } elseif (get_option('cf_perk_quantity_display_selection') == '2') {
                                    ?>
                                        <div class="subdivquantity" style="float:right;">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                        </div>

                                    <?php
                                }
                            }
                            ?>
                                <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                    <h5 class="h5perkrule">
                                        <span class="perkquantity"></span> * <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                    </h5>
                            <?php } else { ?>
                                    <h5 class="h5perkrule">
                                    <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                    </h5>
                                    <?php } ?>
                                <h6 class="h6perkrule">
                                <?php
                                if ($perk['choose_products'] != '') {
                                    $products = get_product($perk['choose_products']);
                                    if ($products->is_type('simple')) {
                                        if (get_option('cf_perk_url_type') == '2') {
                                            if (get_option('cf_perk_url__image_type') == '3') {

                                                echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                                echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                            } elseif (get_option('cf_perk_url__image_type') == '2') {

                                                echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            } else {
                                                echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                            }
                                        } else {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                        }
                                    } else {
                                        $return_attribute_slug = CFPerkMetaBox::get_attribute_slug($perk['choose_products']);
                                        $link = implode("&", $return_attribute_slug);
                                        $link1 = str_replace('attribute_', '', $link);
                                        $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink($products->parent->id));
                                        if (get_option('cf_perk_url_type') == '2') {
                                            if (get_option('cf_perk_url__image_type') == '3') {
                                                echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                                echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                            } elseif (get_option('cf_perk_url__image_type') == '2') {
                                                echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            } else {
                                                echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                            }
                                        } else {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                        }
                                    }
                                } else {
                                    echo $perk['name'];
                                }
                                ?>
                                </h6>
                                <p class="form-field perkruledescription">
                                    <?php echo $perk['description']; ?>
                                </p>
                                <p class="form-field perkruleclaimprize">
                                    <strong><?php
                                    $newperkname = str_replace('', '_', $perk['name']);
                                    $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                    if ($newcounter == '') {
                                        $newcounter = 0;
                                    } else {
                                        $newcounter = $newcounter;
                                    }
                                    echo $newcounter;
                                    ?>  <?php echo get_option('cf_out_of_claimed_label'); ?>  <?php echo $perk['claimcount']; ?> </strong>
                                </p>
                                <p class="form-field perkruledelivery">
                                    <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                                </p>
                                        <?php
                                        if (get_option('cf_perk_quantity_display_selection') == '3') {
                                            ?>
                                    <div class="subdivquantity" style="float:left;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                    </div>

                                <?php
                            } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                ?>
                                    <div class="subdivquantity" style="float:right;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                    </div>
                                <?php
                            }
                            ?>
                            </div>
                                <?php
                            } else {
                                ?>
                            <div class="disableperkrule" id="perk_maincontainer<?php echo $i; ?>" data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>">
                                <h5 class="h5perkrule">
                            <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                </h5>
                                <h6 class="h6perkrule">
                                <?php echo $perk['name']; ?>
                                </h6>
                                <p class="form-field perkruledescription">
                            <?php echo $perk['description']; ?>
                                </p>
                                <p class="form-field perkruleclaimprize">
                                    <strong><?php
                            $newperkname = str_replace('', '_', $perk['name']);
                            $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                            if ($newcounter == '') {
                                $newcounter = 0;
                            } else {
                                $newcounter = $newcounter;
                            }
                            echo $newcounter;
                            ?>

                                        claimed out of <?php echo $perk['claimcount']; ?> </strong>
                                </p>
                                <p class="form-field perkruledelivery">
                                    <label>Estimated Delivery:</label> <em><?php echo $perk['deliverydate']; ?></em>
                                </p>
                            </div>
                                        <?php
                                    }
                                } elseif ($is_unlimited == 'cf_unlimited') {
                                    ?>
                        <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>" data-choose_products ="<?php echo $perk['choose_products']; ?>">
                        <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                            <?php if (get_option('cf_perk_quantity_display_selection') == '1') { ?>
                                    <div class="subdivquantity">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>
                                <?php
                            } elseif (get_option('cf_perk_quantity_display_selection') == '2') {
                                ?>
                                    <div class="subdivquantity" style="float:right;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>

                                <?php
                            }
                        }
                        ?>
                            <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                <h5 class="h5perkrule">
                                    <span class="perkquantity"></span> * <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                </h5>
                        <?php } else { ?>
                                <h5 class="h5perkrule">
                            <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                </h5>
                            <?php } ?>
                            <h6 class="h6perkrule">
                            <?php
                            if ($perk['choose_products'] != '') {
                                $products = get_product($perk['choose_products']);
                                if ($products->is_type('simple')) {
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {

                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                } else {
                                    $return_attribute_slug = CFPerkMetaBox::get_attribute_slug($perk['choose_products']);
                                    $link = implode("&", $return_attribute_slug);
                                    $link1 = str_replace('attribute_', '', $link);
                                    $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink($products->parent->id));
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                }
                            } else {
                                echo $perk['name'];
                            }
                            ?>
                            </h6>
                            <p class="form-field perkruledescription">
                                <?php echo $perk['description']; ?>
                            </p>
                            <p class="form-field perkruleclaimprize">
                                <strong><?php
                                $newperkname = str_replace('', '_', $perk['name']);
                                $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                if ($newcounter == '') {
                                    $newcounter = 0;
                                } else {
                                    $newcounter = $newcounter;
                                }
                                echo $newcounter;
                                ?>  <?php echo get_option('cf_out_of_claimed_unlimited_label'); ?>   </strong>
                            </p>
                            <p class="form-field perkruledelivery">
                                <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                            </p>
                                <?php
                                if (get_option('cf_perk_quantity_display_selection') == '3') {
                                    ?>
                                <div class="subdivquantity" style="float:left;">
                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                </div>

                                        <?php
                                    } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                        ?>
                                <div class="subdivquantity" style="float:right;">
                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                </div>
                                        <?php
                                    }
                                    ?>
                        </div>
                            <?php
                        } else {
                            ?>

                        <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>" data-choose_products ="<?php echo $perk['choose_products']; ?>">
                        <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                            <?php if (get_option('cf_perk_quantity_display_selection') == '1') { ?>
                                    <div class="subdivquantity">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>
                                    <?php
                                } elseif (get_option('cf_perk_quantity_display_selection') == '2') {
                                    ?>
                                    <div class="subdivquantity" style="float:right;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>

                                <?php
                            }
                        }
                        ?>

                            <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                <h5 class="h5perkrule">
                                    <span class="perkquantity"></span> * <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                </h5>
                        <?php } else { ?>
                                <h5 class="h5perkrule">
                            <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                </h5>
                            <?php } ?>
                            <h6 class="h6perkrule">
                            <?php
                            if ($perk['choose_products'] != '') {
                                $products = get_product($perk['choose_products']);
                                if ($products->is_type('simple')) {
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image($perk_image_Size) . '</div></a>';
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image($perk_image_Size) . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                } else {
                                    $return_attribute_slug = CFPerkMetaBox::get_attribute_slug($perk['choose_products']);

                                    $link = implode("&", $return_attribute_slug);
                                    $link1 = str_replace('attribute_', '', $link);
                                    $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink($products->parent->id));
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px"> ' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                }
                            } else {
                                echo $perk['name'];
                            }
                            ?>
                            </h6>
                            <p class="form-field perkruledescription">
                                <?php echo $perk['description']; ?>
                            </p>
                            <p class="form-field perkruleclaimprize">
                                <strong><?php
                                $newperkname = str_replace('', '_', $perk['name']);
                                $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                if ($newcounter == '') {
                                    $newcounter = 0;
                                } else {
                                    $newcounter = $newcounter;
                                }
                                echo $newcounter;
                                ?>  <?php echo get_option('cf_out_of_claimed_label'); ?>  <?php echo $perk['claimcount']; ?> </strong>
                            </p>
                            <p class="form-field perkruledelivery">
                                <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                            </p>
                                <?php
                                if (get_option('cf_perk_quantity_display_selection') == '3') {
                                    ?>
                                <div class="subdivquantity" style="float:left;">
                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                </div>

                                    <?php
                                } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                    ?>
                                <div class="subdivquantity" style="float:right;">
                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                </div>
                                        <?php
                                    }
                                    ?>
                        </div>
                                    <?php
                                }
                                $i = $i + 1;
                            }
                        }
                    }
                }

                public static function cf_perk_rule_front_end_shortcode() {
                    global $post;
                    if ($post->post_type == 'product') {
                        echo self::cf_perk_rule_front_end();
                    } elseif ($post->post_type == 'page') {
//            $product_id = intval(preg_replace('/[^0-9]+/', '', $post->post_content), 10);
                        $regex = '~\[([^]]*)\]~';
                        preg_match_all($regex, $post->post_content, $matches);
                        if (!empty($matches[1])) {
                            $shortcode = strchr(implode($matches[1]), "product_page");
                            if ($shortcode != "") {
                                $product_id = intval(preg_replace('/[^0-9]+/', '', $shortcode), 10);
                            }
                        } else {
                            $regex1 = '~\{([^}]*)\}~';
                            preg_match_all($regex1, $post->post_content, $matches);
                            if (!empty($matches[1])) {
                                $shortcode = strchr(implode($matches[1]), "product_page");
                                if ($shortcode != "") {
                                    $product_id = intval(preg_replace('/[^0-9]+/', '', $shortcode), 10);
                                }
                            }
                        }
                        $perkrule = get_post_meta($product_id, 'perk', true);
                        $perk_image_Size = get_option('cf_perk_url__image_type_size');
                        $i = 0;
                        $getperkprice = array();
                        if (is_array($perkrule)) {
                            ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                //                    jQuery('.perkrulequantity').attr('readonly', 'true');
                <?php if (get_option('cf_perk_selection_type') == '1') { ?>
                            var getlistofperksquantity;
                            var listiteration;
                            jQuery('.perkrule').click(function (event) {
                                jQuery('.perkrule').removeClass("selected");
                                jQuery(this).addClass('selected');
                                var getamount = jQuery(this).attr('cf_data-amount');
                                // alert(getamount);
                                var getchoosedproduct = jQuery(this).attr('data-choose_products');
                                jQuery('.single_add_to_cart_button').attr('data-perk', getamount);
                                jQuery('.addfundraiser<?php echo $product_id; ?>').data('perk', getamount);
                                var productid = jQuery(this).attr('data-productid');
                                var getname = jQuery(this).attr('data-perkname');
                                jQuery('.addfundraiser<?php echo $product_id; ?>').val(getamount * jQuery(this).attr('data-quantity'));
                                var getdataquantity = jQuery(this).attr('data-quantity');
                                var dataiteration = jQuery(this).attr('data-iteration');
                                jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                                jQuery('.subdivquantity').show();
                                if (jQuery(this).hasClass('selected')) {
                                    jQuery(this).find('.subdivquantity').hide();
                                }

                                var perkiteration = jQuery(this).attr('data-iteration');
                                listiteration = perkiteration;
                                console.log(listiteration);
                                var mainiteration = jQuery(this).attr('data-iteration');
                                var getmyquantity = getdataquantity;

                                getlistofperksquantity = (mainiteration + "_" + getmyquantity);
                                console.log(getlistofperksquantity);
                                if (getamount === '') {
                                    var dataparam = ({
                                        action: 'selectperkoption',
                                        session_destroy: true
                                    });
                                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                            function (response) {
                                                var newresponse = response.replace(/\s/g, '');
                                                if (newresponse === 'success') {
                                                    //location.reload();
                                                }
                                            });
                                    return false;
                                } else {
                                    var whichperk = jQuery(this).attr('id');
                                    var dataparam = ({
                                        action: 'selectperkoption',
                                        getamount: getamount,
                                        getname: getname,
                                        productid: productid,
                                        explodequantity: getdataquantity + '_' + getamount,
                                        choosedproduct: getchoosedproduct,
                                        listiteration: listiteration,
                                        getlistofperksquantity: getlistofperksquantity,
                                    });
                                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                            function (response) {
                                                var newresponse = response.replace(/\s/g, '');
                                                if (newresponse === 'success') {
                                                    //location.reload();
                                                }
                                            });
                                    return false;
                                }
                            });
                <?php } else { ?>
                            var pvalue = [];
                            var listproduct = [];
                            var productids = [];
                            var listperkname = [];
                            var prkamount = [];
                            var quantitys = [];
                            var perknamequantity = [];
                            var explodequantity = [];
                            var getlistofperksquantity = [];
                            var maindatas = [];
                            var listiteration = [];
                            jQuery(document).on('click', '.perkrule', function () {
                    //                        jQuery('.perkrule').click(function (event) {
                                // console.log(jQuery(this).find('.perkrulequantity').val());
                                if (jQuery(this).hasClass('selected')) {
                                    jQuery(this).removeClass('selected');
                                } else {
                                    jQuery(this).addClass('selected');
                                    //jQuery('.noperk').removeClass('selected');
                                }
                                if (jQuery(this).attr('data-quantity')) {
                                    var getamount = jQuery(this).attr('cf_data-amount') * jQuery(this).attr('data-quantity');
                                }
                                var getchoosedproduct = jQuery(this).attr('data-choose_products');
                                if (jQuery.inArray(getchoosedproduct, listproduct) === -1) {
                                    listproduct.push(getchoosedproduct);
                                } else {
                                    listproduct = jQuery.grep(listproduct, function (value) {
                                        return value !== getchoosedproduct;
                                    });
                                }

                                var getcurrentquantity = jQuery(this).attr('data-quantity');
                                var gtamnt = jQuery(this).attr('cf_data-amount');
                                if (jQuery.inArray(getcurrentquantity + '_' + gtamnt, explodequantity) === -1) {
                                    explodequantity.push(getcurrentquantity + '_' + gtamnt);
                                } else {
                                    explodequantity = jQuery.grep(explodequantity, function (value) {
                                        return value !== getcurrentquantity + '_' + gtamnt;
                                    });
                                }
                                //  console.log(explodequantity);
                                var getquantity = jQuery(this).find('.perkrulequantity').val();
                                if (jQuery.inArray(getquantity, perknamequantity) === -1) {
                                    perknamequantity.push(getquantity);
                                }
                                var perkiteration = jQuery(this).attr('data-iteration');
                                if (jQuery.inArray(perkiteration, listiteration) === -1) {
                                    listiteration.push(perkiteration);
                                } else {
                                    listiteration = jQuery.grep(listiteration, function (value) {
                                        return value !== perkiteration;
                                    });
                                }
                                console.log(listiteration);
                                var mainiteration = jQuery(this).attr('data-iteration');
                                var getmyquantity = jQuery(this).find('.perkrulequantity').val();
                                if (jQuery.inArray(mainiteration + "_" + getmyquantity, getlistofperksquantity) === -1) {
                                    getlistofperksquantity.push(mainiteration + "_" + getmyquantity);
                                } else {
                                    getlistofperksquantity = jQuery.grep(getlistofperksquantity, function (value) {

                                        return value !== (mainiteration + "_" + getmyquantity);
                                    });
                                }
                                //console.log(getlistofperksquantity);
                                var getiteration = jQuery(this).attr('data-iteration');
                                var indiamount = jQuery(this).attr('cf_data-amount');
                                if (jQuery.inArray(getiteration + "_" + indiamount, prkamount) === -1) {
                                    prkamount.push(indiamount);
                                } else {
                                    prkamount = jQuery.grep(prkamount, function (value) {
                                        return value !== (getiteration + "_" + indiamount);
                                    });
                                }
                                var getproductid = jQuery(this).attr('data-productid');
                                if (jQuery.inArray(getproductid, productids) === -1) {
                                    productids.push(getproductid);
                                } else {
                                    productids = jQuery.grep(productids, function (value) {
                                        return value !== getproductid;
                                    });
                                }
                                var getperkname = jQuery(this).attr('data-perkname');
                                if (jQuery.inArray(getperkname, listperkname) === -1) {
                                    listperkname.push(getperkname);
                                } else {
                                    listperkname = jQuery.grep(listperkname, function (value) {
                                        return value !== getperkname;
                                    });
                                }
                                jQuery('.single_add_to_cart_button').attr('data-perk', getamount);
                                var productid = jQuery(this).attr('data-productid');
                                var getname = jQuery(this).attr('data-perkname');
                                if (jQuery(this).attr('data-quantity')) {
                                    if (jQuery(this).hasClass('selected')) {

                                    }
                                }
                                var elementValue = jQuery(this).attr('cf_data-amount') * jQuery(this).attr('data-quantity');
                                jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                                if (jQuery(this).hasClass('selected')) {
                                    var indnames = jQuery(this).attr('data-perkname');
                                    var indamount = jQuery(this).attr('cf_data-amount');
                                    jQuery(this).find('.subdivquantity').hide();
                                } else {
                                    jQuery(this).find('.subdivquantity').show();
                                }
                                //pvalue = [jQuery(this).attr('data-iteration')];
                                if (jQuery.inArray(getiteration + "_" + elementValue, pvalue) === -1) {
                                    pvalue.push(getiteration + "_" + elementValue);
                                } else {
                                    pvalue = jQuery.grep(pvalue, function (value) {
                                        return value !== (getiteration + "_" + elementValue);
                                    });
                                }
                                var total = 0;
                                for (var i = 0; i < pvalue.length; i++) {
                                    // alert(jQuery('#perkrulequantityvalue' + i).val());

                                    total += parseFloat(pvalue[i].split('_')[1]);
                                }
                                jQuery('.addfundraiser<?php echo $product_id; ?>').val(total);
                                jQuery('.single_add_to_cart_button').attr('data-perk', total);
                                if ((total === 0) || elementValue === '') {
                                    var dataparam = ({
                                        action: 'selectperkoption',
                                        session_destroy: true
                                    });
                                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                            function (response) {
                                                var newresponse = response.replace(/\s/g, '');
                                                if (newresponse === 'success') {
                                                    //jQuery('.perkrule').removeClass('selected');
                                                    // jQuery('.noperk').addClass('selected');
                                                    //jQuery(this).addClass('selected');
                                                }
                                            });
                                    return false;
                                } else {
                                    var dataparam = ({
                                        action: 'selectperkoption',
                                        getamount: total,
                                        getname: listperkname,
                                        productid: productid,
                                        sendquantity: perknamequantity,
                                        choosedproduct: listproduct,
                                        explodequantity: explodequantity,
                                        indnames: indnames,
                                        indamount: indamount,
                                        listamount: prkamount,
                                        listiteration: listiteration,
                                        getlistofperksquantity: getlistofperksquantity,
                                    });
                                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                            function (response) {
                                                var newresponse = response.replace(/\s/g, '');
                                                if (newresponse === 'success') {
                                                }
                                            });
                                    return false;
                                }
                            });
                <?php } ?>
                        jQuery('.perkrule a').click(function (evt) {
                            evt.stopPropagation();
                        });
                        jQuery('.perkrule .subdivquantity').click(function (evt) {
                            evt.stopPropagation();
                        });
                        jQuery('.perkrulequantity').val('1');
                        jQuery('.perkrule').attr('data-quantity', '1');
                        jQuery('.cfplus').click(function () {
                            var parentselector = jQuery(this).parent().parent().attr('id');
                            var getvalue = jQuery('#' + parentselector).attr('cf_data-amount');
                            var getiteration = jQuery('#' + parentselector).attr('data-iteration');
                            var getquantityvalue = parseInt(jQuery('#perkrulequantityvalue' + getiteration).val());
                            if (jQuery(this).attr("data-limit") == 'limited') {
                                var max_claim = jQuery(this).attr("data-max");
                                if (getquantityvalue < max_claim) {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue + 1);
                                }
                                else {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(max_claim);
                                }
                            }
                            else {
                                jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue + 1);
                            }
                            if (getquantityvalue < 1) {
                                jQuery('#perkrulequantityvalue' + getiteration).val(1);
                            }
                            var newupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                            jQuery(this).parent().parent().attr('data-quantity', parseInt(newupdate));
                            return false;
                        });
                        jQuery('.cfminus').click(function () {

                            var parentselector = jQuery(this).parent().parent().attr('id');
                            var getvalue = jQuery('#' + parentselector).attr('cf_data-amount');
                            var getiteration = jQuery('#' + parentselector).attr('data-iteration');
                            var getquantityvalue = parseInt(jQuery('#perkrulequantityvalue' + getiteration).val());
                            if (jQuery(this).attr("data-limit") == 'limited') {
                                var max_claim = jQuery(this).attr("data-max");
                                if (getquantityvalue > max_claim) {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(max_claim);
                                }
                                else {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                    if (getquantityvalue > 1) {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                        var minusupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                                        jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                    } else {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(1);
                                        var minusupdate = jQuery('.perkrulequantity').val();
                                        jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                    }
                                }
                            }
                            else {
                                if (getquantityvalue > 1) {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                    var minusupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                                    jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                } else {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(1);
                                    var minusupdate = jQuery('.perkrulequantity').val();
                                    jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                }
                            }
                            return false;
                        });
                    });</script>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.perkrule').each(function () {
                            var newperkruleamount = jQuery(this).attr('cf_data-amount');
                            var compareruleamount = '<?php echo get_post_meta($product_id, 'perk_maincontainer' . $product_id, true); ?>';
                            jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                            if (newperkruleamount === compareruleamount) {
                                // jQuery('.perkrule').hide();
                                jQuery(this).show();
                //                            jQuery('.addfundraiser<?php echo $product_id; ?>').val(compareruleamount);
                                // jQuery(this).removeClass('perkrule');
                                //jQuery(this).addClass('nodropclass');
                            }
                        });
                    });</script>
                <?php
                if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {
                    ?>
                    <div id="informationperk"></div>
                    <h3><?php echo get_option('cf_perk_head_label'); ?></h3>
                    <?php if (get_option('cf_perk_selection_type') == '1') { ?>
                        <div class="perkrule noperk" id="perk_maincontainer" data-productid="<?php echo $product_id; ?>" cf_data-amount="">
                        <?php echo get_option('cf_no_perk_label'); ?>
                        </div>
                    <?php } ?>
                    <?php
                    foreach ($perkrule as $i => $perk) {
                        $perkname = str_replace('', '_', $perk['name']);
                        $newcounterclaim = (int) get_post_meta($product_id, $perkname . $perk['amount'] . 'update_perk_claim', true);
                        $targetclaim = $perk['claimcount'];

                        $is_unlimited = $perk['limitperk'];
                        if ($is_unlimited == 'cf_limited') {
                            $myperk = str_replace('', '_', $perk['name']);
                            $perk1 = get_post_meta($product_id, $myperk . $perk['amount'] . 'update_perk_claim', true);
                            $total_perks = $perk['claimcount'];
                            $max = $total_perks - $perk1;
                            if (($targetclaim > $newcounterclaim) && ($targetclaim != '')) {
                                ?>
                                <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $product_id; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>" data-choose_products ="<?php echo $perk['choose_products']; ?>">
                                <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                    <?php if (get_option('cf_perk_quantity_display_selection') == '1') { ?>
                                            <div class="subdivquantity">
                                                <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                                <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                            </div>
                                        <?php
                                    } elseif (get_option('cf_perk_quantity_display_selection') == '2') {
                                        ?>
                                            <div class="subdivquantity" style="float:right;">
                                                <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                                <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                            </div>

                                        <?php
                                    }
                                }
                                ?>
                                <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                        <h5 class="h5perkrule">
                                            <span class="perkquantity"></span> * <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                        </h5>
                                <?php } else { ?>
                                        <h5 class="h5perkrule">
                                    <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                        </h5>
                                <?php } ?>
                                    <h6 class="h6perkrule">
                                    <?php
                                    if ($perk['choose_products'] != '') {
                                        $products = get_product($perk['choose_products']);
                                        if ($products->is_type('simple')) {
                                            if (get_option('cf_perk_url_type') == '2') {
                                                if (get_option('cf_perk_url__image_type') == '3') {
                                                    echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                                    echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                                } elseif (get_option('cf_perk_url__image_type') == '2') {
                                                    echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                                } else {
                                                    echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                                }
                                            } else {
                                                echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                            }
                                        } else {
                                            $return_attribute_slug = CFPerkMetaBox::get_attribute_slug($perk['choose_products']);
                                            $link = implode("&", $return_attribute_slug);
                                            $link1 = str_replace('attribute_', '', $link);
                                            $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink($products->parent->id));
                                            if (get_option('cf_perk_url_type') == '2') {
                                                if (get_option('cf_perk_url__image_type') == '3') {
                                                    echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                                    echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                                } elseif (get_option('cf_perk_url__image_type') == '2') {
                                                    echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                                } else {
                                                    echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                                }
                                            } else {
                                                echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                            }
                                        }
                                    } else {
                                        echo $perk['name'];
                                    }
                                    ?>
                                    </h6>
                                    <p class="form-field perkruledescription">
                                        <?php echo $perk['description']; ?>
                                    </p>
                                    <p class="form-field perkruleclaimprize">
                                        <strong><?php
                                        $newperkname = str_replace('', '_', $perk['name']);
                                        $newcounter = get_post_meta($product_id, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                        if ($newcounter == '') {
                                            $newcounter = 0;
                                        } else {
                                            $newcounter = $newcounter;
                                        }
                                        echo $newcounter;
                                        ?>  <?php echo get_option('cf_out_of_claimed_label'); ?>  <?php echo $perk['claimcount']; ?> </strong>
                                    </p>
                                    <p class="form-field perkruledelivery">
                                        <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                                    </p>
                                        <?php
                                        if (get_option('cf_perk_quantity_display_selection') == '3') {
                                            ?>
                                        <div class="subdivquantity" style="float:left;">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                        </div>

                                            <?php
                                        } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                            ?>
                                        <div class="subdivquantity" style="float:right;">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                        </div>
                                            <?php
                                        }
                                        ?>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="disableperkrule" id="perk_maincontainer<?php echo $i; ?>" data-productid="<?php echo $product_id; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>">
                                    <h5 class="h5perkrule">
                                            <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                    </h5>
                                    <h6 class="h6perkrule">
                                            <?php echo $perk['name']; ?>
                                    </h6>
                                    <p class="form-field perkruledescription">
                                            <?php echo $perk['description']; ?>
                                    </p>
                                    <p class="form-field perkruleclaimprize">
                                        <strong><?php
                                $newperkname = str_replace('', '_', $perk['name']);
                                $newcounter = get_post_meta($product_id, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                if ($newcounter == '') {
                                    $newcounter = 0;
                                } else {
                                    $newcounter = $newcounter;
                                }
                                echo $newcounter;
                                ?>

                                            claimed out of <?php echo $perk['claimcount']; ?> </strong>
                                    </p>
                                    <p class="form-field perkruledelivery">
                                        <label>Estimated Delivery:</label> <em><?php echo $perk['deliverydate']; ?></em>
                                    </p>
                                </div>
                                <?php
                            }
                        } elseif ($is_unlimited == 'cf_unlimited') {
                            ?>
                            <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $product_id; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>" data-choose_products ="<?php echo $perk['choose_products']; ?>">
                                <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                    <?php if (get_option('cf_perk_quantity_display_selection') == '1') { ?>
                                        <div class="subdivquantity">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                        </div>
                                    <?php
                                } elseif (get_option('cf_perk_quantity_display_selection') == '2') {
                                    ?>
                                        <div class="subdivquantity" style="float:right;">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                        </div>

                                    <?php
                                }
                            }
                            ?>
                                        <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                    <h5 class="h5perkrule">
                                        <span class="perkquantity"></span> * <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                    </h5>
                                        <?php } else { ?>
                                    <h5 class="h5perkrule">
                                            <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                    </h5>
                            <?php } ?>
                                <h6 class="h6perkrule">
                            <?php
                            if ($perk['choose_products'] != '') {
                                $products = get_product($perk['choose_products']);
                                if ($products->is_type('simple')) {
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                } else {
                                    $return_attribute_slug = CFPerkMetaBox::get_attribute_slug($perk['choose_products']);
                                    $link = implode("&", $return_attribute_slug);
                                    $link1 = str_replace('attribute_', '', $link);
                                    $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink($products->parent->id));
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                }
                            } else {
                                echo $perk['name'];
                            }
                            ?>
                                </h6>
                                <p class="form-field perkruledescription">
                                    <?php echo $perk['description']; ?>
                                </p>
                                <p class="form-field perkruleclaimprize">
                                    <strong><?php
                                    $newperkname = str_replace('', '_', $perk['name']);
                                    $newcounter = get_post_meta($product_id, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                    if ($newcounter == '') {
                                        $newcounter = 0;
                                    } else {
                                        $newcounter = $newcounter;
                                    }
                                    echo $newcounter;
                                    ?>  <?php echo get_option('cf_out_of_claimed_unlimited_label'); ?>   </strong>
                                </p>
                                <p class="form-field perkruledelivery">
                                    <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                                </p>
                                    <?php
                                    if (get_option('cf_perk_quantity_display_selection') == '3') {
                                        ?>
                                    <div class="subdivquantity" style="float:left;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>

                                        <?php
                                    } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                        ?>
                                    <div class="subdivquantity" style="float:right;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>
                                        <?php
                                    }
                                    ?>
                            </div>
                                    <?php
                                } else {
                                    ?>

                            <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $product_id; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo $perk['amount']; ?>" data-choose_products ="<?php echo $perk['choose_products']; ?>">
                                    <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                        <?php if (get_option('cf_perk_quantity_display_selection') == '1') { ?>
                                        <div class="subdivquantity">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                        </div>
                                            <?php
                                        } elseif (get_option('cf_perk_quantity_display_selection') == '2') {
                                            ?>
                                        <div class="subdivquantity" style="float:right;">
                                            <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                            <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                            <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                        </div>

                                                <?php
                                            }
                                        }
                                        ?>

                            <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                    <h5 class="h5perkrule">
                                        <span class="perkquantity"></span> * <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                    </h5>
                                <?php } else { ?>
                                    <h5 class="h5perkrule">
                                <?php echo CrowdFunding::get_woocommerce_formatted_price($perk['amount']); ?>
                                    </h5>
                            <?php } ?>
                                <h6 class="h6perkrule">
                            <?php
                            if ($perk['choose_products'] != '') {
                                $products = get_product($perk['choose_products']);
                                if ($products->is_type('simple')) {
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {
                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . get_product($perk['choose_products'])->get_title() . '</div></a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                } else {
                                    $return_attribute_slug = CFPerkMetaBox::get_attribute_slug($perk['choose_products']);
                                    $link = implode("&", $return_attribute_slug);
                                    $link1 = str_replace('attribute_', '', $link);
                                    $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink($products->parent->id));
                                    if (get_option('cf_perk_url_type') == '2') {
                                        if (get_option('cf_perk_url__image_type') == '3') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        } elseif (get_option('cf_perk_url__image_type') == '2') {
                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_Size . 'px">' . get_product($perk['choose_products'])->get_image() . '</div></a>';
                                        } else {
                                            echo '<a class="linkedclass" href=' . $url . "&" . $link . ' target="_blank">' . get_product($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                        }
                                    } else {
                                        echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                    }
                                }
                            } else {
                                echo $perk['name'];
                            }
                            ?>
                                </h6>
                                <p class="form-field perkruledescription">
                                <?php echo $perk['description']; ?>
                                </p>
                                <p class="form-field perkruleclaimprize">
                                    <strong><?php
                                $newperkname = str_replace('', '_', $perk['name']);
                                $newcounter = get_post_meta($product_id, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                if ($newcounter == '') {
                                    $newcounter = 0;
                                } else {
                                    $newcounter = $newcounter;
                                }
                                echo $newcounter;
                                ?>  <?php echo get_option('cf_out_of_claimed_label'); ?>  <?php echo $perk['claimcount']; ?> </strong>
                                </p>
                                <p class="form-field perkruledelivery">
                                    <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                                </p>
                                    <?php
                                    if (get_option('cf_perk_quantity_display_selection') == '3') {
                                        ?>
                                    <div class="subdivquantity" style="float:left;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>

                                        <?php
                                    } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                        ?>
                                    <div class="subdivquantity" style="float:right;">
                                        <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                        <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                        <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                    </div>
                                        <?php
                                    }
                                    ?>
                            </div>
                                    <?php
                                }
                                $i = $i + 1;
                            }
                        }
                    }
                }
            }

            public static function galaxy_funder_update_perk_rule() {
                global $post;
                session_start();
                if ((isset($_POST['productid'])) && (isset($_POST['getamount'])) && (isset($_POST['getname']))) {
// echo var_dump($_POST['getname']);
                    $_SESSION['perkcontainer' . $_POST['productid']] = 'perk_maincontainer' . $_POST['productid'];
                    $_SESSION['productid' . $_POST['productid']] = $_POST['productid'];
                    $_SESSION['amount' . $_POST['productid']] = $_POST['getamount'];
                    $_SESSION['perkname' . $_POST['productid']] = $_POST['getname'];
                    $_SESSION['explodequantity' . $_POST['productid']] = $_POST['explodequantity'];
                    if (isset($_POST['listamount'])) {
                        $_SESSION['listamount' . $_POST['productid']] = $_POST['listamount'];
                    }
                    $_SESSION['choosedproduct' . $_POST['productid']] = $_POST['choosedproduct'];
                    if (isset($_POST['listiteration'])) {
                        $_SESSION['listiteration' . $_POST['productid']] = (array) $_POST['listiteration'];
                    }
                    if (isset($_POST['getlistofperksquantity'])) {
                        $_SESSION['getlistofquantities' . $_POST['productid']] = (array) $_POST['getlistofperksquantity'];
                    }

                    // update_user_meta($user_ID, 'perk_maincontainer' . $_POST['productid'], $_POST['getamount']);
                    echo "success";
                } else {
                    session_destroy();
                    echo "success";
                }
                exit();
            }

            public static function getvalueinarray() {

                global $post;

                $getperkquantity = get_post_meta(313, 'explodequantity', true);
                foreach ($getperkquantity as $perkquantity) {
                    $exp_quantity = explode('_', $perkquantity);
                    $listquantity[] = $exp_quantity[0];
                    $listofamount[] = $exp_quantity[1];
                }
//        var_dump($listquantity) . "<br>";
//        var_dump($listofamount);

                $perkrule = get_post_meta($post->ID, 'perk', true);
                $i = 0;
                foreach ($perkrule as $perk) {

                    foreach ($listofamount as $amount) {
                        if ($perk['amount'] == (int) $amount) {
                            $length = count(array_keys($listofamount, (int) $amount));
                            $claimcount = (int) $perk['claimcount'];
                            $perkquantity = (int) $listquantity[$i];
                            echo $perkquantity . "<br>";
                            $quantitylog[] = $perk['name'] . ' x ' . $perkquantity;
                            $perkname = str_replace('', '_', $perk['name']);
// $currentcount = (int) get_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', true);
                            if ($claimcount > $currentcount) {
//  update_post_meta($item['product_id'], $perkname . $perk['amount'] . 'update_perk_claim', $length + $currentcount);
                            }
                            $i++;
                        }
                    }
                }
            }

            public static function galaxyfunder_sessionhandler($order_id, $order_posted) {
                session_start();
                $sessionname = "";
                $sessionproduct = "";
                $sessionprice = "";
                $perkname = "";
                $listamount = "";
                $perkchoosedproduct = "";
                $explodequantity = "";
                $listofiteration = "";
                $qtyiteration = "";


                $order = new WC_Order($order_id);
                // var_dump($order->get_items());
                foreach ($order->get_items() as $item) {
                    if (isset($_SESSION['perkcontainer' . $item['product_id']])) {
                        $sessionname = $_SESSION['perkcontainer' . $item['product_id']];
                    }
                    if (isset($_SESSION['productid' . $item['product_id']])) {
                        $sessionproduct = $_SESSION['productid' . $item['product_id']];
                    }
                    if (isset($_SESSION['amount' . $item['product_id']])) {
                        $sessionprice = $_SESSION['amount' . $item['product_id']];
                    }
                    if (isset($_SESSION['perkname' . $item['product_id']])) {
                        $perkname = $_SESSION['perkname' . $item['product_id']];
                    }
                    if (isset($_SESSION['listamount' . $item['product_id']])) {
                        $listamount = $_SESSION['listamount' . $item['product_id']];
                    }
                    if (isset($_SESSION['choosedproduct' . $item['product_id']])) {
                        $perkchoosedproduct = $_SESSION['choosedproduct' . $item['product_id']];
                    }
                    if (isset($_SESSION['explodequantity' . $item['product_id']])) {
                        $explodequantity = $_SESSION['explodequantity' . $item['product_id']];
                    }
                    if (isset($_SESSION['listiteration' . $item['product_id']])) {
                        $listofiteration = $_SESSION['listiteration' . $item['product_id']];
                    }
                    if (isset($_SESSION['getlistofquantities' . $item['product_id']])) {
                        $qtyiteration = $_SESSION['getlistofquantities' . $item['product_id']];
                    }
                    update_post_meta($order_id, 'perkname' . $item['product_id'], $perkname);
                    update_post_meta($order_id, $sessionname, $sessionprice);
                    update_post_meta($order_id, "perk_maincontainer" . $item['product_id'] . $order_id, $sessionproduct);
                    update_post_meta($order_id, 'perk_choosed_product' . $item['product_id'], $perkchoosedproduct);

//            update_post_meta($item['product_id'], 'getlistofquantities_testing', $qtyiteration);

                    update_post_meta($order_id, 'getlistofquantities', $qtyiteration);
                    update_post_meta($order_id, 'listiteration', $listofiteration != '' ? $listofiteration : '');

                    update_post_meta($order_id, 'perk_list_amount' . $item['product_id'], $listamount);
                    update_post_meta($order_id, 'explodequantity' . $item['product_id'], $explodequantity);
                }
                session_destroy();
            }

        }

        add_action('woocommerce_checkout_update_order_meta', array('CFPerkMetaBox', 'galaxyfunder_sessionhandler'), 10, 2);

        $cf_perk_showhide = get_option('cf_perk_table_show_hide');
        if ($cf_perk_showhide == 1) {
            if (get_option('cf_perk_table_position') == '1') {
                add_action('woocommerce_before_single_product_summary', array('CFPerkMetaBox', 'cf_perk_rule_front_end'));
            } elseif (get_option('cf_perk_table_position') == '2') {
                add_action('woocommerce_after_single_product', array('CFPerkMetaBox', 'cf_perk_rule_front_end'));
            } else {
                add_action('woocommerce_after_single_product_summary', array('CFPerkMetaBox', 'cf_perk_rule_front_end'));
            }
        }
        add_action('add_meta_boxes', array('CFPerkMetaBox', 'add_perk_meta_box'));
        add_action('save_post', array('CFPerkMetaBox', 'perk_save_dynamic_data'));
        add_shortcode('displayperk', array('CFPerkMetaBox', 'cf_perk_rule_front_end_shortcode'));
//add_action('wp_head', array('CFPerkMetaBox', 'cf_perk_rule_front_end_shortcode'));
        add_action('wp_ajax_nopriv_selectperkoption', array('CFPerkMetaBox', 'galaxy_funder_update_perk_rule'));
        add_action('wp_ajax_selectperkoption', array('CFPerkMetaBox', 'galaxy_funder_update_perk_rule'));
        