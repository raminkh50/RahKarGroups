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


?>
<div class="quantity">
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
		<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" class="minus_svg" style="width: 18px; height: 18px;" width="24" height="24"><defs><symbol xmlns="http://www.w3.org/2000/svg" id="removeSimple" viewBox="0 0 24 24"><path d="M20 11v2H4v-2h16z"/></symbol></defs><use xlink:href="#removeSimple"/></svg>
	</div>
	<style>

.quantity {
display: flex;
background: #fff;
border-radius: 10px;
box-shadow: -5px 5px 20px -10px #ccc;
padding:6px 4px 2px 4px;

}

.quantity:is(.rknm-btn, .input-text) {
padding: 0 !important;
display: flex;
justify-content:center;
align-items:center;
}
.rknm-btn {
    cursor: pointer;
    flex: 30%;
    text-align: center;
    align-content: center;
}
.rknm-btn:hover svg {
    fill: #f00;
}
.quantity .input-text {
border: none;
font-family: "FontF-Ramin", Sans-Serif;
flex: 40%;
text-align: center!important;
border-radius: 8px;
background: #fbfbfb;
-moz-appearance: textfield;
}
.quantity .input-text::-webkit-inner-spin-button {
-webkit-appearance: none;
}
.quantity .input-text:focus {
outline: none;
background: #f7f7f7;
}

</style>
<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".quantity").forEach((wrapper) => {
    const plusBtn = wrapper.querySelector(".rknm-plus");
    const minusBtn = wrapper.querySelector(".rknm-minus");
    const quantityInput = wrapper.querySelector("input.input-text");
    const max = parseInt(quantityInput?.max) || Infinity;

    if (
      !plusBtn ||
      !minusBtn ||
      !quantityInput ||
      wrapper.dataset.bound === "true"
    )
      return;
    wrapper.dataset.bound = "true";

    plusBtn.addEventListener("click", () => {
      let val = parseInt(quantityInput.value) || 0;
      if (val < max) quantityInput.value = ++val;
    });

    minusBtn.addEventListener("click", () => {
      let val = parseInt(quantityInput.value) || 1;
      if (val > 1) quantityInput.value = --val;
      
    });

    quantityInput.addEventListener("change", () => {
      let val = parseInt(quantityInput.value) || 1;
      if (val < 1) {
        alert("تعداد انتخابی نا معتبر است");
        quantityInput.value = 1;
      } else if (val > max) {
        alert("تعداد بیشتر از موجودی محصول است");
        quantityInput.value = max;
      }
    });
  });
});

</script>
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
