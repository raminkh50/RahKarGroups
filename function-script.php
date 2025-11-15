<?php

function rknm_loder_wp_enqueue($name_jc,$js,$css,$path_dir,$path_dir_main){

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
    rknm_loder_wp_enqueue('toastr.min','js','css',$path_dir,$path_dir_main);
}
/*لایک و دیس لایک */
if ( is_single() || is_product() ) {
    rknm_loder_wp_enqueue('rknm-like-dislike','js','',$path_dir,$path_dir_main);
    
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

    //wp_enqueue_script('chart-js-fallback',$path_dir.'/assets/js/chart.umd.min.js',[],'v4.4.4', true);
    rknm_loder_wp_enqueue('chart.umd.min','js','',$path_dir,$path_dir_main);
}

if ( is_product() || is_account_page()) {
    /* لیست علاقه‌مندی‌ها */    
    rknm_loder_wp_enqueue('rknm-wishlist','js','css',$path_dir,$path_dir_main);
    rknm_loder_wp_enqueue('toastr.min','js','css',$path_dir,$path_dir_main);
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
    rknm_loder_wp_enqueue('rknm-star-rating','js','css',$path_dir,$path_dir_main);
}
});


// Localize AJAX Script
add_action('wp_enqueue_scripts', function () {
    // Load on all pages since shortcode can be used anywhere
if ( is_product() || is_account_page()) {
    wp_localize_script('jquery', 'rknmWishlistAjax', array( //rknm-wishlist-js
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rknm_wishlist_nonce'),
        'user_id' => get_current_user_id() ? get_current_user_id() : 0,
    ));
}
if (is_cart() /*|| is_account_page()*/) {
        wp_localize_script('jquery', 'rknm_ajax', array( //rknm-cart-js
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rknm_next_purchase_nonce')
        ));
        wp_localize_script('jquery', 'rknm_ajax_cart', array( //rknm-cart-js
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rknm_cart_nonce')
        ));        

}
if ( is_product() || is_account_page()) {
        wp_localize_script( 'rknm-swatches-js', 'wc_add_to_cart_params', 
        array('ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
}, 20);

?>
