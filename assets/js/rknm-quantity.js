jQuery(document).ready(function ($) {
  // Update cart quantity
  function updateCartQuantity(cartItemKey, newQuantity, $quantityWrapper) {
    $quantityWrapper.removeClass("rknm-hidden").addClass("show");
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
        $quantityWrapper.removeClass("show").addClass("rknm-hidden");
      },
    });
  }

  // Plus button click
  $(document).on("click", ".rknm-plus", function () {
    var $btn = $(this);
    var $wrapper = $btn.closest(".quantity");
    var $input = $wrapper.find(".input-text");
    var $dotview = $wrapper.find("#loading-dots");
    var $maxview = $wrapper.find("#quantity_max");
    var cartItemKey = $wrapper.data("cart_item_key");
    var currentVal = parseInt($input.val());
    var max = parseInt($input.attr("max")) || Infinity;
    $maxview.removeClass("show").addClass("rknm-hidden");
    if (currentVal < max) {
      var newVal = currentVal + 1;
      if (newVal == max) {
        $maxview.removeClass("rknm-hidden").addClass("show");
      }
      $input.val(newVal);
      if (cartItemKey) {
        updateCartQuantity(cartItemKey, newVal, $dotview);
      }
    } else {
      $maxview.removeClass("rknm-hidden").addClass("show");
    }
  });

  // Minus button click
  $(document).on("click", ".rknm-minus", function () {
    var $btn = $(this);
    var $wrapper = $btn.closest(".quantity");
    var $input = $wrapper.find(".input-text");
    var $dotview = $wrapper.find("#loading-dots");
    var $maxview = $wrapper.find("#quantity_max");
    var cartItemKey = $wrapper.data("cart_item_key");
    var currentVal = parseInt($input.val());

    if (currentVal > 0) {
      var newVal = currentVal - 1;
      $maxview.removeClass("show").addClass("rknm-hidden");
      $input.val(newVal);
      if (cartItemKey) {
        updateCartQuantity(cartItemKey, newVal, $dotview);
      }
    }
  });

  // Single product add to cart button click
  $(document).on("click", ".single_add_to_cart_button", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var productId = $btn.val();
    var $form = $btn.closest("form.cart");
    var $quantityWrapper = $form.find(".quantity_wrapper");
    var $quantityInput = $quantityWrapper.find(".quantity");

    $btn.addClass("loading");

    $.ajax({
      url: rknm_ajax_cart.ajax_url,
      type: "POST",
      data: {
        action: "rknm_add_to_cart_single_product",
        product_id: productId,
        nonce: rknm_ajax_cart.nonce,
      },
      success: function (response) {
        if (response.success) {
          toastr.success(response.data.message, "", {
            toastClass: "rknm-cart-toast-success",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
          $btn.hide();
          $quantityWrapper.show();
          $quantityInput.data("cart_item_key", response.data.cart_item_key);
        } else {
          toastr.error(response.data.message, "", {
            toastClass: "rknm-cart-toast-error",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
        }
      },
      error: function () {
        toastr.error("خطا در افزودن محصول به سبد خرید", "", {
          toastClass: "rknm-cart-toast-error",
          toastId: "rknm_cart_toast-" + Date.now(),
        });
      },
      complete: function () {
        $btn.removeClass("loading");
      },
    });
  });
});
