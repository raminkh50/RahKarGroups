<?php

//کلیه اسکریپتهای سبد خرید  if (function_exists('jdate'))

function rknm_loder_wp_enqueue($name_jc,$js=null,$css=null,$path_dir,$path_dir_main){

    if ($js)  wp_enqueue_script($name_jc.'-js',$path_dir.'/assets/js/'.$name_jc.'.js',['jquery'],
                              filemtime($path_dir_main . '/assets/js/'.$name_jc.'.js'),true);
    if ($css) wp_enqueue_style($name_jc.'-css',$path_dir.'/assets/css/'.$name_jc.'.css',[],
                              filemtime($path_dir_main . '/assets/css/'.$name_jc.'.css'));
}


add_action('wp_enqueue_scripts',function () {
$path_dir=get_stylesheet_directory_uri();
$path_dir_main = get_stylesheet_directory();


// نمایش plus+ minus- دکمه  ///
/*
if ( is_product() || is_cart() || is_shop() || is_product_category() ) {
    rknm_loder_wp_enqueue('custom-quantity','js','',$path_dir,$path_dir_main);
}*/
/*سبد خرید */
if (is_cart() /*|| is_account_page()*/) {
    rknm_loder_wp_enqueue('rknm-cart','js','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-nextcart','js','',$path_dir,$path_dir_main);

    // Enqueue toastr
    wp_enqueue_script('rknm-next-purchase-toastr','https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',['jquery'],'2.1.4',true);
    wp_enqueue_style('rknm-next-purchase-toastr-css','https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',[],'2.1.4');

}
/*لایک و دیس لایک */
if ( is_single() || is_product() ) {
    rknm_loder_wp_enqueue('rknm-like-dislike','js','',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-star-rating','js','css',$path_dir,$path_dir_main);
}
 /*جزئیات سفارش*/
   //if (!is_wc_endpoint_url('order-received') && !is_wc_endpoint_url('view-order')) {
if ( is_account_page() ) {
    rknm_loder_wp_enqueue('rknm-order_detail','','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-order_details_after','','css',$path_dir,$path_dir_main);
}
/**product*/
if ( is_product() ) {
    rknm_loder_wp_enqueue('rknm-add_to_cart_popup','js','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-price_chart','js','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-counter_down','js','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-cross_sells','','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-dynamic_note_scroll','js','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('rknm-swatches','js','',$path_dir,$path_dir_main);

    wp_enqueue_script('chart-js-fallback','https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js',[],'v4.4.4', true);
}

if ( is_product() || is_account_page()) {
    /* لیست علاقه‌مندی‌ها */
    rknm_loder_wp_enqueue('rknm-wishlist','js','css',$path_dir,$path_dir_main);

    wp_enqueue_script('rknm-wishlist-toastr','https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',['jquery'],'2.1.4',true);
    wp_enqueue_style('rknm-wishlist-toastr-css','https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',[],'2.1.4');
}

// نمایش پاپ در صفحه تصویه حساب زمانی که فیلترشکن روشن باشه
if ( is_checkout() ) {
    rknm_loder_wp_enqueue('rknm-vpn','js','css',$path_dir,$path_dir_main);

    add_action('wp_footer', function () {
        ?>
        <div id="rknm-vpn-warning-modal" class="rknm-vpn-warning-modal" style="display: none;">
            <div class="rknm-vpn-warning-modal-content">
                <h4>هشدار</h4>
                <p>لطفاً فیلترشکن خود را خاموش کنید تا بتوانید به درستی از سایت استفاده کنید.</p>
                <div class="rknm-vpn-warning-modal-buttons">
                    <button id="rknm-vpn-warning-modal-close" class="button">باشه</button>
                </div></div></div>
        <?php
    });
}
if ( is_shop() || is_product_category() ) {
    rknm_loder_wp_enqueue('rknm-loop-cart-p-t','js','css',$path_dir,$path_dir_main);
}
});


// Localize AJAX Script
add_action('wp_enqueue_scripts', function () {
    // Load on all pages since shortcode can be used anywhere
if ( is_product() || is_account_page()) {
    wp_localize_script('rknm-wishlist-toastr', 'rknmWishlistAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rknm_wishlist_nonce'),
        'user_id' => get_current_user_id() ? get_current_user_id() : 0,
    ));
}
if (is_cart() /*|| is_account_page()*/) {
        wp_localize_script('jquery', 'rknm_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rknm_next_purchase_nonce')
        ));
        wp_localize_script('jquery', 'rknm_ajax_cart', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rknm_cart_nonce')
        ));

}
if ( is_product() || is_account_page()) {
        wp_localize_script( 'rknm-swatches-js', 'wc_add_to_cart_params',
        array('ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
}, 20);

/**آیجکس سبد خرید */
// Helper function to check if product exists in next purchase list
function rknm_product_exists_in_next_purchase($product_id, $variation_id = 0, $user_id = null) {
    if (!$user_id)   $user_id = get_current_user_id();
    if (!$user_id)   return false;

    $next_purchase_list = get_user_meta($user_id, '_rknm_next_purchase_list', true);
    if (!is_array($next_purchase_list)) {
        return false;
    }
    foreach ($next_purchase_list as $item) {
        if ($item['product_id'] == $product_id && $item['variation_id'] == $variation_id) {
            return true;
        }
    }
    return false;
}

// Helper function to remove product from next purchase list
function rknm_remove_from_next_purchase($product_id, $variation_id = 0, $user_id = null) {
    if (!$user_id)  $user_id = get_current_user_id();
    if (!$user_id)  return false;

    $next_purchase_list = get_user_meta($user_id, '_rknm_next_purchase_list', true);

    if (!is_array($next_purchase_list))   return false;
    $removed = false;
    foreach ($next_purchase_list as $key => $item) {
        if ($item['product_id'] == $product_id && $item['variation_id'] == $variation_id) {
            unset($next_purchase_list[$key]);
            $removed = true;
            break;
        }
    }
    if ($removed) {
        update_user_meta($user_id, '_rknm_next_purchase_list', array_values($next_purchase_list));
        do_action('rknm_removed_from_next_purchase', $product_id, $user_id);
        return $next_purchase_list;
    }
    return false;
}

// Ajax handlers
add_action('wp_ajax_rknm_move_to_next_purchase', 'rknm_ajax_move_to_next_purchase');
add_action('wp_ajax_nopriv_rknm_move_to_next_purchase', 'rknm_ajax_move_to_next_purchase');
function rknm_ajax_move_to_next_purchase() {
    check_ajax_referer('rknm_next_purchase_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'لطفا ابتدا وارد حساب کاربری خود شوید.']);
        return;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $cart_key = isset($_POST['cart_key']) ? sanitize_text_field($_POST['cart_key']) : '';

    if (empty($cart_key)) {
        wp_send_json_error(['message' => 'مشکلی پیش آمده، محصولی برای انتقال یافت نشد.']);
        return;
    }

    // Get cart item to retrieve variation data
    $cart_item = WC()->cart->get_cart_item($cart_key);
    if (!$cart_item) {
        wp_send_json_error(['message' => 'محصول در سبد خرید یافت نشد.']);
        return;
    }

    $user_id = get_current_user_id();
    $next_purchase_list = get_user_meta($user_id, '_rknm_next_purchase_list', true);

    if (!is_array($next_purchase_list)) {
        $next_purchase_list = [];
    }

    // Check if product already exists in next purchase list
    if (rknm_product_exists_in_next_purchase($product_id, $variation_id, $user_id)) {
        wp_send_json_error(['message' => 'این محصول قبلاً در لیست خرید بعدی شما قرار دارد.']);
        return;
    }

    // Add to next purchase list, including variation data
    $item_to_add = [
        'product_id'   => $cart_item['product_id'],
        'variation_id' => $cart_item['variation_id'],
        'quantity'     => $cart_item['quantity'],
        'variation'    => $cart_item['variation'], // This saves the attributes
        'added_date'   => current_time('mysql', 1)
    ];

    $next_purchase_list[] = $item_to_add;
    update_user_meta($user_id, '_rknm_next_purchase_list', $next_purchase_list);

    // Remove from cart
    $removed = WC()->cart->remove_cart_item($cart_key);

    if($removed) {
        do_action('rknm_added_to_next_purchase', $product_id, $user_id);

        // Render the HTML for the new next purchase item
        $item_html = rknm_render_next_purchase_item_html($item_to_add);

        wp_send_json_success([
            'message' => 'محصول به لیست خرید بعدی منتقل شد.',
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'nextcart_count' => count($next_purchase_list),
            'item_html' => $item_html, // Add HTML to the response
        ]);
    } else {
        // If removal failed, revert adding to next purchase list
        array_pop($next_purchase_list);
        update_user_meta($user_id, '_rknm_next_purchase_list', $next_purchase_list);
        wp_send_json_error(['message' => 'خطا در حذف محصول از سبد خرید.']);
    }
}

add_action('wp_ajax_rknm_remove_from_next_purchase', 'rknm_ajax_remove_from_next_purchase');
function rknm_ajax_remove_from_next_purchase() {
    check_ajax_referer('rknm_next_purchase_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_die('User not logged in');
    }

    $product_id = intval($_POST['product_id']);
    $variation_id = intval($_POST['variation_id']);

    $user_id = get_current_user_id();
    //$next_purchase_list = get_user_meta($user_id, '_rknm_next_purchase_list', true);

    // Use helper function to remove from next purchase list
    $next_purchase_list=rknm_remove_from_next_purchase($product_id, $variation_id, $user_id);

    do_action('rknm_removed_from_next_purchase', $product_id, $user_id);

    wp_send_json_success(array(
        'message' => 'محصول از لیست خرید بعدی حذف شد.',
        'nextcart_count' => is_array($next_purchase_list) ? count($next_purchase_list) : '0'
    ));
}

add_action('wp_ajax_rknm_remove_from_cart', 'rknm_ajax_remove_from_cart');
function rknm_ajax_remove_from_cart() {
    check_ajax_referer('rknm_cart_nonce', 'nonce');

    $product_id = intval($_POST['product_id']);
    $variation_id = intval($_POST['variation_id']);
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);

    // Remove from cart
   //$product_cart_id = WC()->cart->generate_cart_id( $product_id );
   //$cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
    if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );

     wp_send_json_success(array(
        //'message' => $cart_item_key,
        'message' => 'محصول از سبد خرید حذف شد.',
        'cart_count' => WC()->cart->get_cart_contents_count()
    ));
}

add_action('wp_ajax_rknm_add_to_cart_from_next_purchase', 'rknm_ajax_add_to_cart_from_next_purchase');
function rknm_ajax_add_to_cart_from_next_purchase() {
    check_ajax_referer('rknm_next_purchase_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'لطفا ابتدا وارد حساب کاربری خود شوید.']);
        return;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;

    $user_id = get_current_user_id();
    $next_purchase_list = get_user_meta($user_id, '_rknm_next_purchase_list', true);

    if (empty($next_purchase_list) || !is_array($next_purchase_list)) {
        wp_send_json_error(['message' => 'لیست خرید بعدی شما خالی است.']);
        return;
    }

    $item_to_add = null;
    $item_key_to_remove = -1;

    // Find the item in the next purchase list
    foreach ($next_purchase_list as $key => $item) {
        if (isset($item['product_id']) && $item['product_id'] == $product_id &&
            isset($item['variation_id']) && $item['variation_id'] == $variation_id) {
            $item_to_add = $item;
            $item_key_to_remove = $key;
            break;
        }
    }

    if (!$item_to_add) {
        wp_send_json_error(['message' => 'محصول مورد نظر در لیست خرید بعدی یافت نشد.']);
        return;
    }

    $quantity = isset($item_to_add['quantity']) ? $item_to_add['quantity'] : 1;
    $variation_data = isset($item_to_add['variation']) ? $item_to_add['variation'] : [];

    // Add product to cart
    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation_data);

    if ($cart_item_key) {
        // Remove from next purchase list using the found key
        unset($next_purchase_list[$item_key_to_remove]);
        update_user_meta($user_id, '_rknm_next_purchase_list', array_values($next_purchase_list));

        // Render the HTML for the new cart item
        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        ob_start();
        rknm_render_cart_item_html($cart_item_key, $cart_item);
        $item_html = ob_get_clean();

        wp_send_json_success([
            'message'        => 'محصول به سبد خرید اضافه شد.',
            'cart_count'     => WC()->cart->get_cart_contents_count(),
            'nextcart_count' => count($next_purchase_list),
            'item_html'      => $item_html, // Add HTML to the response
        ]);
    } else {
        wp_send_json_error(['message' => 'خطا در افزودن محصول به سبد خرید. ممکن است محصول ناموجود باشد.']);
    }
}

// Helper function to render a single next purchase item HTML
function rknm_render_next_purchase_item_html($item) {
    $product_id = $item['product_id'];
    $variation_id = isset($item['variation_id']) ? $item['variation_id'] : 0;
    $product = wc_get_product($variation_id ? $variation_id : $product_id);

    if (!$product) {
        return '';
    }

    ob_start();
    ?>
    <a href="<?php echo esc_url($product->get_permalink()); ?>" style="width: 20%;">
        <div class="rknm-next-purchase-item" data-product-id="<?php echo esc_attr($product_id); ?>" data-variation-id="<?php echo esc_attr($variation_id); ?>">
            <div class="rknm-next-purchase-image"><?php echo $product->get_image(array(200, 200)); ?></div>
            <div class="rknm-next-purchase-details">
                <h3 class="Title_Product_H product-name"><?php echo esc_html($product->get_name()); ?></h3>
                <span class="rknm-price-main"><?php echo $product->get_price_html(); ?></span>
                 <?php if ($product->is_type('variation')): ?>
                    <div class="rknm_attribute">
                        <?php echo wc_get_formatted_variation($item['variation'], true); ?>
                    </div>
                <?php endif; ?>
                <div class="rknm-next-purchase-actions">
                    <button class="rknm-nextcart-add-btn" data-product-id="<?php echo esc_attr($product_id); ?>" data-variation-id="<?php echo esc_attr($variation_id); ?>">
                        <img src="/rknm/pic/basket-10.svg" alt="افزودن به سبد" title="افزودن به سبد">
                        افزودن به سبد
                    </button>
                    <button class="rknm-nextcart-remove-btn" data-product-id="<?php echo esc_attr($product_id); ?>" data-variation-id="<?php echo esc_attr($variation_id); ?>">
                        <img src="/rknm/pic/recyclebin.svg" alt="حذف" title="حذف">
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </a>
    <?php
    return ob_get_clean();
}

// Helper function to render a single cart item HTML
function rknm_render_cart_item_html($cart_item_key, $cart_item) {
    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
    $tomanred='<img src="/rknm/pic/toman-red.svg" alt="تومان"title="تومان">';
    $toman2='<img src="/rknm/pic/toman2.svg" alt="تومان"title="تومان">';

    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
        ?>
        <div class="rknm-oreder-cart-item cart_item" data-product-id="<?php echo esc_attr($product_id); ?>" data-variation-id="<?php echo esc_attr($cart_item['variation_id']); ?>">
            <div class="rknm-r211">
                <div style="width:200px;height:200px;justify-items:center;">
                    <a href="<?php echo esc_url($product_permalink); ?>"><?php echo $_product->get_image(array(200, 200)); ?></a>
                </div>
            </div>
            <div class="rknm-r212">
                <h3 class="Title_Product_H product-name"><?php echo esc_html($_product->get_name()); ?></h3>
                <div class="rknm_attribute">
                    <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                </div>
                <span class="rknm_garanty">
                   <img src="/rknm/pic/separ.svg" alt="گارانتی" title="گارانتی">
                   <?php echo get_post_meta($product_id, '_rknm_seller_garanti', true); ?>
                </span>
                <span class="rknm_shop"><img src="/rknm/pic/shop.svg" alt="فروشنده" title="فروشنده"> آمتیس </span>
                <div><?php echo rknm_express_shipping_func(['id' => $product_id, 'limit' => 4]); ?></div>
            </div>
            <div class="rknm-r213">
                <div class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
                    <?php
                    if ($_product->is_sold_individually()) {
                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                    } else {
                        $product_quantity = woocommerce_quantity_input(
                            array(
                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                'input_value'  => $cart_item['quantity'],
                                'max_value'    => $_product->get_max_purchase_quantity(),
                                'min_value'    => '0',
                                'product_name' => $_product->get_name(),
                            ),
                            $_product,
                            false
                        );
                    }
                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                    ?>
                    <div class="product-remove">
                        <button class="rknm-cart-remove-btn" data-product-id="<?php echo esc_attr($product_id); ?>" data-variation-id="<?php echo esc_attr($cart_item['variation_id']); ?>" data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>"><img src="/rknm/pic/recyclebin-red.svg" alt="حذف" title="حذف"></button>
                    </div>
                </div>
            </div>
            <div class="rknm-r214">
                <div>
                  <span class="product-sale_subtotal" style="color:red ;font-size:0.9rem;">
                    <?php echo wc_price(($_product->get_regular_price() - $_product->get_sale_price()) * $cart_item['quantity']).$tomanred; ?>&nbsp; تخفیف
                  </span><br>
                  <span class="product-subtotal" style="color:#000;font-size:1.3rem;font-weight:500;">
                    <?php echo wc_price($_product->get_sale_price() * $cart_item['quantity']).$toman2; ?>
                  </span>
                </div>
            </div>
            <div class="rknm-r215">
                <div><?php echo rknm_add_move_to_next_purchase_button($cart_item_key, $product_id, $cart_item['variation_id']); ?></div>
            </div>
            <div class="rknm-r216">
                <hr style=" margin:10px -20px;  border-top: 1px solid #E6E6E6; ">
            </div>
        </div>
        <?php
    }
}


add_action('wp_ajax_rknm_add_all_to_cart_from_next_purchase', 'rknm_ajax_add_all_to_cart_from_next_purchase');
function rknm_ajax_add_all_to_cart_from_next_purchase() {
    check_ajax_referer('rknm_next_purchase_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_die('User not logged in');
    }

    $user_id = get_current_user_id();
    $next_purchase_list = get_user_meta($user_id, '_rknm_next_purchase_list', true);

    if (empty($next_purchase_list) || !is_array($next_purchase_list)) {
        wp_send_json_error(array(
            'message' => 'لیست خرید بعدی خالی است.'
        ));
    }

    $success_count = 0;
    $error_count = 0;
    $added_products = [];

    foreach ($next_purchase_list as $item) {
        $product_id = intval($item['product_id']);
        $variation_id = isset($item['variation_id']) ? intval($item['variation_id']) : 0;
        //if($product_id==$variation_id) $variation_id=0;

        // Check if product exists and is purchasable
        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) {
            $error_count++;
            continue;
        }

        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart($product_id, 1, $variation_id);

        if ($cart_item_key) {
            $success_count++;
            $added_products[] = array(
                'product_id' => $product_id,
                'variation_id' => $variation_id
            );
        } else {
            $error_count++;
        }
    }

    // Remove all successfully added products from next purchase list
    foreach ($added_products as $product) {
        $next_purchase_list=rknm_remove_from_next_purchase($product['product_id'], $product['variation_id'], $user_id);
    }

    if ($success_count > 0) {
        $message = sprintf('تعداد %d محصول با موفقیت به سبد خرید اضافه شد.', $success_count);
        if ($error_count > 0) {
            $message .= sprintf(' تعداد %d محصول به دلیل عدم موجودی یا خطا اضافه نشد.', $error_count);
        }

        wp_send_json_success(array(
            'message' => $message,
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'nextcart_count' => $error_count
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'هیچ محصولی به سبد خرید اضافه نشد.'
        ));
    }
}



?>