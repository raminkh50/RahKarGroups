<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ($product->is_in_stock()): ?>

    <?php do_action('woocommerce_before_add_to_cart_form'); ?>

    <form class="cart"
        action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
        method="post" enctype='multipart/form-data'>
        <?php do_action('woocommerce_before_add_to_cart_button'); ?>

        <?php
        $cart_item = rknm_find_product_in_cart($product->get_id());
        $is_in_cart = $cart_item !== false;
        ?>

        <div class="quantity_wrapper <?php echo $is_in_cart ? '' : 'rknm-hidden'; ?>">
            <?php
            do_action('woocommerce_before_add_to_cart_quantity');
            if ($product->is_sold_individually()) {
                $max_quantity = 2;
            } else {
                $max_quantity = $product->get_max_purchase_quantity();
            }
            woocommerce_quantity_input(
                array(
                    'min_value'   => 1,
                    'max_value'   => $max_quantity,
                    'input_value' => $is_in_cart ? $cart_item['quantity'] : 1,
                    'cart_item_key' => $is_in_cart ? $cart_item['cart_item_key'] : ''
                )
            );
            do_action('woocommerce_after_add_to_cart_quantity');
            ?>
        </div>

        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
            class="single_add_to_cart_button button alt<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> <?php echo $is_in_cart ? 'rknm-hidden' : ''; ?>">
            <?php echo esc_html($product->single_add_to_cart_text()); ?>
        </button>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    </form>

    <?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif; ?>
