jQuery(document).ready(function ($) {
  // Update cart quantity
  function updateCartQuantity(cartItemKey, newQuantity, $quantityWrapper) {
    $quantityWrapper.addClass("loading-dots-quantity");

    $.ajax({
      url: rknm_ajax_cart.ajax_url,
      type: "POST",
      data: {
        action: "rknm_update_cart_quantity",
        cart_item_key: cartItemKey,
        quantity: newQuantity,
        nonce: rknm_ajax_cart.nonce,
      },
      success: function (response) {
        if (response.success) {
          $("#rknm-tabs-cart").html(response.data.html_cart_basket);
          $(".rknm-tab-count-cart").text(response.data.cart_count);
          toastr.success(response.data.message, "", {
            toastClass: "rknm-cart-toast-success",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
        } else {
          toastr.error(response.data.message, "", {
            toastClass: "rknm-cart-toast-error",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
        }
      },
      error: function () {
        toastr.error("خطا در به‌روزرسانی سبد خرید", "", {
          toastClass: "rknm-cart-toast-error",
          toastId: "rknm_cart_toast-" + Date.now(),
        });
      },
      complete: function () {
        $quantityWrapper.removeClass("loading-dots-quantity");
      },
    });
  }

  // Plus button click
  $(document).on("click", ".rknm-plus", function () {
    var $btn = $(this);
    var $wrapper = $btn.closest(".quantity");
    var $input = $wrapper.find(".input-text");
    var cartItemKey = $wrapper.data("cart_item_key");
    var currentVal = parseInt($input.val());
    var max = parseInt($input.attr("max")) || Infinity;

    if (currentVal < max) {
        var newVal = currentVal + 1;
        $input.val(newVal);
        if (cartItemKey) {
            updateCartQuantity(cartItemKey, newVal, $wrapper);
        }
    }
  });

  // Minus button click
  $(document).on("click", ".rknm-minus", function () {
    var $btn = $(this);
    var $wrapper = $btn.closest(".quantity");
    var $input = $wrapper.find(".input-text");
    var cartItemKey = $wrapper.data("cart_item_key");
    var currentVal = parseInt($input.val());

    if (currentVal > 0) {
        var newVal = currentVal - 1;
        $input.val(newVal);
        if (cartItemKey) {
            updateCartQuantity(cartItemKey, newVal, $wrapper);
        }
    }
  });
});
