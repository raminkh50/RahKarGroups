<?php
/*پاپ آپ بعد از اضافه شدن محصول به سبد خرید*/
add_action('wp_ajax_rknm_add_to_cart', 'rknm_add_to_cart_callback');
add_action('wp_ajax_nopriv_rknm_add_to_cart', 'rknm_add_to_cart_callback');
function rknm_add_to_cart_callback() {
    if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
        wp_send_json_error(['message' => 'شناسه محصول نامعتبر']);
    }

    $product_name =sanitize_text_field($_POST['name']); //strval($_POST['title']);
    $price = intval($_POST['price']) ;
    $product_id = intval($_POST['product_id']);
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $variation = [];
    
    if ($variation_id) {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'attribute_') === 0) {
                $variation[$key] = sanitize_text_field($value);
            }
        }
    }
    /*$product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'محصول یافت نشد']);
    }*/
    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    if ($cart_item_key) {
    $image = $variation_id ? wp_get_attachment_image_src(get_post_thumbnail_id($variation_id), 'thumbnail') :
                             wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
    $image_url = $image ? $image[0] : wc_placeholder_img_src();
    //$price = $variation_id ? wc_get_product($variation_id)->get_price_html() : $product->get_price_html();
    
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        wp_send_json_success([
            'image' => $image_url,
            'title' => $product_name,//$product->get_name(),
            'price' => $price,
            'cart_count' => WC()->cart->get_cart_contents_count()
        ]);
    } else {
        wp_send_json_error(['message' => 'خطا در افزودن به سبد خرید']);
    }
}

add_action('wp_footer', 'rknm_add_cart_popup');
function rknm_add_cart_popup() {
    if (!is_product())  return;
    ?>
    <div id="rknmCartPopup" class="rknm-cart-popup" style="display: none;">
        <div class="rknm-cart-popup-content">
            <button class="rknm-cart-popup-close">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 15L1 1" stroke="#3C3D45" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M1 15L15 1" stroke="#3C3D45" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </button>
            <h2 class="rknm-cart-popup-title">این کالا به سبد خرید اضافه شد</h2>
            <hr class="rknm-cart-popup-divider">
            <div class="rknm-cart-popup-product">
                <img id="rknmPopupProductImage" src="" alt="محصول"title="محصول" class="rknm-cart-popup-image">
                <div class="rknm-cart-popup-details">
                    <h3 id="rknmPopupProductTitle" class="Title_Product_H"></h3>
                    <span id="rknmPopupProductPrice" class="rknm-price-main"></span>
                    <span id="rknmPopupProductcount" class="cart-items-count"></span>
                </div>
            </div>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="rknm-cart-popup-button">برو به سبد خرید</a>
        </div>
    </div>

    <?php
}
?>
