<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
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
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

// Get the cart item key from the arguments
$cart_item_key = isset($args['cart_item_key']) ? $args['cart_item_key'] : '';
?>
<div class="quantity" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>">
    <?php
    /**
     * Hook to output something before the quantity input field.
     *
     * @since 7.2.0
     */
    do_action( 'woocommerce_before_quantity_input_field' );
    ?>
    <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>
    <div class="rknm-btn rknm-plus">
        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" class="plus_svg" style="width: 18px; height: 18px;" width="24" height="24"><defs><symbol xmlns="http://www.w3.org/2000/svg" id="addSimple" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M13 4h-2v7H4v2h7v7h2v-7h7v-2h-7V4z" clip-rule="evenodd"/></symbol></defs><use xlink:href="#addSimple"/></svg>
    </div> <!-- $quantity -->
    <input
        type="<?php echo esc_attr( $type ); ?>"
        <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
        id="<?php echo esc_attr( $input_id ); ?>"
        class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
        name="<?php echo esc_attr( $input_name ); ?>"
        value="<?php echo esc_attr( $input_value  ); ?>"
        aria-label="<?php esc_attr_e( 'Product quantity', 'woocommerce' ); ?>"
        <?php if ( in_array( $type, array( 'text', 'search', 'tel', 'url', 'email', 'password' ), true ) ) : ?>
            size="4"
        <?php endif; ?>
        min="<?php echo esc_attr( $min_value ); ?>"
        <?php if ( 0 < $max_value ) : ?>
            max="<?php echo esc_attr( $max_value ); ?>"
        <?php endif; ?>
        <?php if ( ! $readonly ) : ?>
            step="<?php echo esc_attr( $step ); ?>"
            placeholder="<?php echo esc_attr( $placeholder ); ?>"
            inputmode="<?php echo esc_attr( $inputmode ); ?>"
            autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
        <?php endif; ?>
    />
    <div class="rknm-btn rknm-minus">
		<?php if ($input_value > 1): ?>
			<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" class="minus_svg"
				style="width: 18px; height: 18px;" width="24" height="24" fill="currentColor">
				<defs>
					<symbol xmlns="http://www.w3.org/2000/svg" id="removeSimple" viewBox="0 0 24 24">
						<path d="M20 11v2H4v-2h16z" />
					</symbol>
				</defs>
				<use xlink:href="#removeSimple" />
			</svg>
		<?php else: ?>
			<svg clase="rknm-cart-remove-btn" id="rknm-cart-del-btn" data-product-id="" data-variation-id=""
				data-cart_item_key="" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
				xmlns="http://www.w3.org/2000/svg">
				<path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				<path
					d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z"
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				<path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				<path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		<?php endif; ?>
    </div>
    <?php
    /**
     * Hook to output something after quantity input field
     *
     * @since 3.6.0
     */
    do_action( 'woocommerce_after_quantity_input_field' );
    ?>
</div>
<?php
