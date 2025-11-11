<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

$user_id = get_current_user_id();
$next_purchase_list = get_user_meta($user_id, '_rknm_next_purchase_list', true);
$count_nextcart=is_array($next_purchase_list) ? count($next_purchase_list) : '0';	

$cart_item_s = WC()->cart;
$count_cart=$cart_item_s->get_cart_contents_count();

?>
        <div id="rknm-cart-del-modal" class="rknm-cart-remove-modal rknm-hidden">
            <div class="rknm-cart-remove-modal-content">
                <div class="rknm-cart-remove-modal-header">
                    <h4>حذف کالا</h4>
                    <button id="rknm-cart-remove-modal-close" class="rknm-cart-remove-modal-close"><?php echo $close_svg; ?></button>
                </div>
                <div class="rknm-cart-remove-modal-body">
                    <p class="rknm-cart-remove-modal-message">آیا مطمئن هستید که می‌خواهید این محصول را از سبد خرید حذف کنید؟</p>
                </div>
                <div class="rknm-cart-remove-modal-footer">
                    <button id="rknm-cart-remove-modal-cancel"   class="rknm-cart-remove-modal-cancel">انصراف</button>
                    <button id="rknm-cart-remove-modal-confirm" class="rknm-cart-remove-modal-confirm">حذف</button>
                </div>
            </div>
        </div>
    <!-- Remove Confirmation Popup of next Purchase-->
        <div id="rknm-nextcart-del-modal" class="rknm-cart-remove-modal rknm-hidden">
            <div class="rknm-cart-remove-modal-content">
                <div class="rknm-cart-remove-modal-header">
                 <h4>حذف کالا</h4>
                 <button id="rknm-cartnext-remove-modal-close" class="rknm-cart-remove-modal-close"><?php echo $close_svg; ?></button>
                </div>
                <div class="rknm-cart-remove-modal-body">
                    <p class="rknm-cart-remove-modal-message">آیا مطمئن هستید که می‌خواهید این محصول را از لیست خرید بعدی حذف کنید؟</p>
                </div>
                <div class="rknm-cart-remove-modal-footer">
                    <button id="rknm-nextcart-remove-modal-cancel"   class="rknm-nextcart-remove-modal-cancel">انصراف</button>
                    <button id="rknm-nextcart-remove-modal-confirm" class="rknm-nextcart-remove-modal-confirm">حذف</button>
                </div>
            </div>
        </div>

<div class="tabs">
   <button id="btn_back" class="btn_back" > <img src="/rknm/pic/right.svg" alt="بازگشت به صفحه قبل"title="بازگشت به صفحه قبل"></button>
  <button class="tabslinks" onclick="opentabs(event, 'rknm-tabs-cart')" id="defaultOpen"> سبد خرید <span class="rknm-tab-count rknm-tab-count-cart" > <?php echo esc_attr($count_cart);?></span></button>
  <button class="tabslinks" onclick="opentabs(event, 'rknm-tabs-nextcart')"> خرید بعدی <span class="rknm-tab-count rknm-tab-count-nextcart" > <?php echo esc_attr($count_nextcart);?> </span></button>
</div>

<form class="woocommerce-cart-form cart"  action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<div id="rknm-tabs-cart" class="tabscontent">
<?php cart_basket(WC()->cart, WC()->cart->get_cart_contents_count()); ?>
</div> <!--//rknm-tabs-cart--->

<div id="rknm-tabs-nextcart" class="tabscontent">
<?php 
$next_purchase_list = get_user_meta(get_current_user_id(), '_rknm_next_purchase_list', true);
next_purchase($next_purchase_list, is_array($next_purchase_list) ? count($next_purchase_list) : 0); 
?>
</div> <!--//rknm-tabs-nextcart--->

</form>
<?php

?>
