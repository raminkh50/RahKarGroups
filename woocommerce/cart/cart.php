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
        <div id="rknm-cart-remove-modal" class="rknm-cart-remove-modal rknm-modal-hidden">
            <div class="rknm-cart-remove-modal-content">
                <div class="rknm-cart-remove-modal-header">
                    <h4>حذف کالا</h4>
                </div>
                <div class="rknm-cart-remove-modal-body">
                    <p class="rknm-cart-remove-modal-message">آیا مطمئن هستید که می‌خواهید این محصول را از لیست خرید بعدی حذف کنید؟</p>
                </div>
                <div class="rknm-cart-remove-modal-footer">
                    <button id="rknm-cart-remove-modal-cancel"   class="rknm-cart-remove-modal-cancel">انصراف</button>
                    <button id="rknm-cart-remove-modal-confirm" class="rknm-cart-remove-modal-confirm">حذف</button>
                </div>
            </div>
        </div>
    <!-- Remove Confirmation Popup of next Purchase-->
        <div id="rknm-nextcart-remove-modal" class="rknm-nextcart-remove-modal rknm-modal-hidden">
            <div class="rknm-cart-remove-modal-content">
                <div class="rknm-cart-remove-modal-header">
                    <h4>حذف کالا</h4>
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
<?php cart_basket($cart_item_s,$count_cart); ?>
</div> <!--//rknm-tabs-cart--->

<div id="rknm-tabs-nextcart" class="tabscontent">
<?php next_purchase($next_purchase_list,$count_nextcart); ?>
</div> <!--//rknm-tabs-nextcart--->

</form>
<?php

//} else return  do_shortcode('[elementor-template id="162"]');  'شما هنوز وارد نشده اید نکرده‌اید !!!';

////////
function cart_basket($cart_item_s,$count_cart){
$tomanred='<img src="/rknm/pic/toman-red.svg" alt="تومان"title="تومان">';
$toman2='<img src="/rknm/pic/toman2.svg" alt="تومان"title="تومان">';
if ( $count_cart>0 ) :

$sum_sale_price = 0;
$sum_regular_price = 0;
$total_payment = 0;
$count=0;
?>
<div class="rknm-m cart">
<!-------r----->
<div class="rknm-r">
<div class="rknm-r1" style="display:flex;flex-direction:row;width:100%;align-items:normal;padding:10px;
text-align:right;"> سبد خرید شما <?php echo esc_attr($count_cart); ?> مرسوله    |
</div> <!-- //rknm-r1 -->
<div class="rknm-r2" style="display:flex;flex-direction:row;width:100%;align-items:normal;padding:10px;text-align:right;">
<div class="rknm-r21" style="display:flex;flex-direction:row;width:100%;align-items:normal;padding:10px;text-align:right;flex-wrap:wrap;">
<?php
global $product;
//--loop Calculate product discounts

foreach ($cart_item_s->get_cart() as $cart_item_key => $cart_item) :

   $product = $cart_item['data'];
   $quantity = $cart_item['quantity'];

   //$variation_id1 = $product->get_id();
   //$product_id1= $cart_item['product_id'];

   $variation_id_cart = $product->get_id();
   $product_id_cart= $cart_item['product_id'];

   if ($product_id_cart==$variation_id_cart)    $variation_id_cart =0;

   $link = $product->get_permalink( $cart_item );
   $product_name=$product->get_title();
   $r_regular_price=$product->get_regular_price();
   $r_sale_price =$product->get_sale_price();
   $sum_regular_price += $r_regular_price * $quantity;
   $sum_sale_price += $r_sale_price * $quantity;
   //$colors = $product->get_attributes();
   //foreach ($colors as $value) ;

   //print_r($product);

?>
<!--<div class="woocommerce-cart-form__cart-item woocommerce_cart_item_class "-->
<div class="rknm-oreder-cart-item cart_item"
 data-product-id=" <?php echo esc_attr($product_id_cart); ?>" data-variation-id="<?php echo esc_attr($variation_id_cart); ?>">
<div class="rknm-r211">
<div style="width:200px;height:200px;justify-items:center;">
  <a href="<?php echo esc_url($link); ?>"><?php echo $product->get_image(array(200, 200)); ?></a>
</div>
</div> <!--//rknm-r211 -->
<div class="rknm-r212">
<h3 class="Title_Product_H product-name"><?php echo esc_attr($product_name); ?></h3>
<div class="rknm_attribute">
<?php
if ($product->is_type('variation')) :

    $attributes = $product->get_attributes();//$product->get_attributes()s[pa_color]
    //print_r($attributes);
    foreach ($attributes as $value) :

      echo '<span class="color-circle" style="margin-left:18px;background:#'. esc_attr($value).';"> </span>'.esc_attr($product->get_attribute_summary()).'<br>';

    endforeach;
    endif; ?>
</div>

<span class="rknm_garanty">
   <img src="/rknm/pic/separ.svg" alt="گارانتی"title="گارانتی">
   <?php echo get_post_meta($product_id_cart, '_rknm_seller_garanti', true); ?>
</span>
<span class="rknm_shop"><img src="/rknm/pic/shop.svg" alt="فروشنده"title="فروشنده"> آمتیس </span>

<div><?php echo rknm_express_shipping_func(['id'=> $product_id_cart,'limit' => 4 ]); ?></div>

</div> <!--//rknm-r212-->
<div class="rknm-r213">

<div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
<?php
    if ( $product->is_sold_individually() ) {
        $min_quantity = 1;
        $max_quantity = 1;
    } else {
        $min_quantity = 0;
        $max_quantity = $product->get_max_purchase_quantity();
    }

    $product_quantity = woocommerce_quantity_input(
        array(
            'input_name'   => "cart[{$cart_item_key}][qty]",
            'input_value'  => $quantity,
            'max_value'    => $max_quantity,
            'min_value'    => $min_quantity,
            'product_name' => $product_name,
        ),
        $product,
        false
    );

 echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
?>
<div class="product-remove">
    <button  class="rknm-cart-remove-btn" data-product-id=" <?php echo esc_attr($product_id_cart); ?>" data-variation-id="<?php echo esc_attr($variation_id_cart); ?>" data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>" ><img src="/rknm/pic/recyclebin-red.svg" alt="حذف"title="حذف">
    </button>
</div>

</div>
</div> <!--//rknm-r213 -->
<div class="rknm-r214">
<div>
  <span class="product-sale_subtotal" style="color:red ;font-size:0.9rem;">
    <?php echo wc_price(($r_regular_price-$r_sale_price)*$quantity).$tomanred; ?>&nbsp; تخفیف
  </span><br>
  <span class="product-subtotal" style="color:#000;font-size:1.3rem;font-weight:500;">
    <?php echo wc_price($r_sale_price*$quantity).$toman2; ?>
  </span>
</div>
</div> <!--//rknm-r214 -->
<div class="rknm-r215">
<div> <?php echo rknm_add_move_to_next_purchase_button(/*$product_name, $cart_item,*/ $cart_item_key,$product_id_cart,$variation_id_cart); ?></div>
</div> <!--//rknm-r215 -->
<div class="rknm-r216">
<hr style=" margin:10px -20px;  border-top: 1px solid #E6E6E6; ">
</div> <!--//rknm-r216 -->
</div> <!--//rknm-oreder-cart-item -->
<?php $count++;
 endforeach; ?>
<!--//--loop end-->

</div> <!--//rknm-r21-->
</div> <!--//rknm-r2-->
</div> <!--//rknm-r-->
<!----l--->
<div class="rknm-l">
<div class="rknm-l1">
<?php
    $sum_sale_price = $cart_item_s->get_discount_total()+ $sum_sale_price ;
    $save_payment =   $sum_regular_price-$sum_sale_price;
?>
<span style="width:100%;display:flex;justify-content: space-between;font-size:.8rem;">
			قیمت کالاها (<?php echo $count_cart; ?>) <div><?php echo wc_price($sum_regular_price).$toman2; ?></div></span>
<span style="width:100%;color:#000;display:flex;justify-content: space-between;font-size:.8rem;">
			جمع سبد خرید <div><?php echo wc_price($sum_sale_price).$toman2; ?></div></span>
<span style="width:100%;color:green;display:flex;justify-content: space-between;font-size:.9rem;">
			سود شما از خرید (<?php echo $count_cart; ?>) <div><?php echo wc_price($save_payment).$toman2; ?></div></span>
<div class="rknm-cart-main-payment">
		  <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="rknm-cart-popup-button">تایید و تکمیل سفارش</a>
</div>

</div> <!--//rknm-l1-->
<div class="rknm-l2">
<?php echo free_shipping_progress_func(); ?>
<span style="font-size:.75rem;">هزینه این سفارش هنوز پرداخت نشده‌ و در صورت اتمام موجودی، کالاها از سبد حذف می‌شوند</span>
</div> <!--//rknm-l2-->
<div class="rknm-l3">
از هدایای ویژه ما ، در فروشگاه استفاده کنید
</div> <!--//rknm-l3-->
<div class="rknm-l4">
با مهرتان آینده کودکان را بسازید
مشاهده
کمک به تامین نیازهای کودکان جامانده از تحصیل
</div> <!--//rknm-l4-->
</div> <!--//rknm-l-->

</div> <!-- //rknm-m-->
<div class="rknm-cart-mob-payment">
<div style="width: 60%;">
		  <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="rknm-cart-popup-button">تایید و تکمیل سفارش</a>
</div>
<div style="width:40%;display:flex;flex-direction:column;align-items:flex-end;">
<span  style="color:gray;font-size:.8rem;">	جمع سبد خرید </span>
<span  style="color:#000;font-size:1.3rem;"><?php echo wc_price($sum_sale_price); ?></span>
</div>
</div> <!--//rknm-cart-mob-payment-->

<?php else :?>
    <div class="rknm-empty-cart">
	  <img src="/rknm/pic/order-empty.svg" style="width:200px;height:180px;" alt="سبد خرید خالی است!"title="سبد خرید خالی است!">
      <h4 style="color:#333;margin:15px 0 10px 0;font-size:1.2rem;font-weight:600;">سبد خرید شما خالی است!</h4>
      <p style="color:#999; line-height:1.6; max-width:500px; margin-left:auto; margin-right:auto;">می‌توانید برای مشاهده محصولات بیشتر به صفحات زیر بروید:</p>
      <p class="return-to-shop"><a class="button wc-backward" href="/shop">بازدید از فروشگاه</a></p>
    </div>


<?php
  endif;
}

function next_purchase($next_purchase_list,$count_nextcart){
?>
<!--<div class="rknm-next-purchase-account-page">-->
<div class="rknm-next-purchase-account-header">
<div class="rknm-next-purchase-header-content">
<div class="rknm-next-purchase-header-text">
<h2>لیست خرید بعدی شما</h2>
<p>کالا های موجود در خرید بعدی.</p>
</div>
<div class="rknm-next-purchase-header-actions">
<button class="rknm-nextcart-add-all-to-cart-btn" id="rknmAddAllToCart">
<!--<img src="/rknm/pic/basket-10.svg" style="stroke:'currentColor';width:16px;height:16px;" alt="انتقال همه به سبد خرید"title="انتقال همه به سبد خرید">
-->
<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M9 22C9.55228 22 10 21.5523 10 21C10 20.4477 9.55228 20 9 20C8.44772 20 8 20.4477 8 21C8 21.5523 8.44772 22 9 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M20 22C20.5523 22 21 21.5523 21 21C21 20.4477 20.5523 20 20 20C19.4477 20 19 20.4477 19 21C19 21.5523 19.4477 22 20 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M1 1H5L7.68 14.39C7.77144 14.8504 8.02191 15.264 8.38755 15.5583C8.75318 15.8526 9.2107 16.009 9.68 16H19.4C19.8693 16.009 20.3268 15.8526 20.6925 15.5583C21.0581 15.264 21.3086 14.8504 21.4 14.39L23 6H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
انتقال همه به سبد خرید  >
</button>
<!--</div>-->
</div>
</div>
<?php if (empty($next_purchase_list)) : ?>
    <div class="rknm-empty-cart">
      <img src="/rknm/pic/empty-sfl.webp" style="width:200px;height:180;" alt="لیست خرید بعدی شما خالی است!"title="لیست خرید بعدی شما خالی است!">
      <h4 style="color:#333;margin:15px 0 10px 0;font-size:1.2rem;font-weight:600;">لیست خرید بعدی شما خالی است!</h4>
      <p style="color:#999; line-height:1.6; max-width:500px; margin-left:auto; margin-right:auto;">شما می‌توانید محصولاتی که به سبد خرید خود افزوده‌اید و فعلا قصد خرید آن‌ها را ندارید، در لیست خرید بعدی قرار داده و هر زمان مایل بودید آن‌ها را به سبد خرید اضافه کرده و خرید آن‌ها را تکمیل کنید.</p>
    </div>

     <?php else : ?>
        <div class="rknm-next-purchase-products">
        <div class="rknm-next-purchase-grid">

        <?php foreach ($next_purchase_list as $item) :
            $product_id_nextcart = $item['product_id'];
            $variation_id_nextcart = isset($item['variation_id']) ? $item['variation_id'] : 0;

            $product = $variation_id_nextcart ?  wc_get_product($variation_id_nextcart)  : wc_get_product($product_id_nextcart);

            if (!$product) continue;

        ?>  <a href="<?php echo esc_url($product->get_permalink()); ?>" style="width: 20%;">
            <div class="rknm-next-purchase-item" data-product-id="<?php echo esc_attr($product_id_nextcart); ?>" data-variation-id="<?php echo esc_attr($variation_id_nextcart); ?>">
            <div class="rknm-next-purchase-image"><?php echo $product->get_image(array(200, 200)); ?></div>
            <div class="rknm-next-purchase-details">
            <h3 class="Title_Product_H product-name"><?php echo esc_html($product->get_title());?></h3>
            <span class="rknm-price-main"><?php echo $product->get_price_html(); ?></span>
            <div class="rknm-next-purchase-actions">
            <button class="rknm-nextcart-add-btn" data-product-id="<?php echo esc_attr($product_id_nextcart); ?>" data-variation-id="<?php echo esc_attr($variation_id_nextcart); ?>">
            <img src="/rknm/pic/basket-10.svg" alt="افزودن به سبد" title="افزودن به سبد">
            افزودن به سبد</button>
            <button class="rknm-nextcart-remove-btn" data-product-id="<?php echo esc_attr($product_id_nextcart); ?>" data-variation-id="<?php echo esc_attr($variation_id_nextcart);?>">
            <img src="/rknm/pic/recyclebin.svg" alt="حذف"title="حذف">
            حذف</button>
            </div>
            </div>
            </div>
        </a>
        <?php endforeach; ?>

        </div>
        </div>
    <?php endif; ?>

    </div>
        <?php
 }

// Add "Move to Next Purchase" button to cart
function rknm_add_move_to_next_purchase_button( $cart_item_key,$product_id,$variation_id) {

//    if (!is_user_logged_in() || !is_cart()) { return 'لطفا وارد شوید!';   }
    $button = '<div class="rknm-move-to-next-purchase">';
    $button .= '<button class="rknm-move-btn" data-product-id="' . esc_attr($product_id) . '" data-variation-id="' . esc_attr($variation_id) . '" data-cart-key="' . esc_attr($cart_item_key) . '">';
    $button .= 'انتقال به خرید بعدی &ensp;>';
    $button .= '</button>';
    $button .= '</div>';

    return  $button;
}

?>