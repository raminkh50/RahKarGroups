jQuery(document).ready(function ($) {
  // Update cart quantity
  function updateCartQuantity(cartItemKey, newQuantity, $quantityWrapper) {
    var $dotview = $quantityWrapper.find("#loading-dots");
    $dotview.removeClass("rknm-hidden").addClass("show");
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
          // Check if we are on a single product page
          if ($("body").hasClass("single-product")) {
            if (response.data.item_removed) {
              $quantityWrapper.closest(".quantity_wrapper").addClass("rknm-hidden");
              $(".single_add_to_cart_button").removeClass("rknm-hidden");
            } else {
              $quantityWrapper.replaceWith(response.data.quantity_html);
            }
          } else {
            // Cart page logic
            $("#rknm-tabs-cart").html(response.data.html_cart_basket);
            $(".rknm-tab-count-cart").text(response.data.cart_count);
          }

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
        $dotview.removeClass("show").addClass("rknm-hidden");
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
      if (cartItemKey) {
        updateCartQuantity(cartItemKey, newVal, $wrapper);
      } else {
        $input.val(newVal);
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
      if (cartItemKey) {
        updateCartQuantity(cartItemKey, newVal, $wrapper);
      } else {
        $input.val(newVal);
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
          $btn.addClass("rknm-hidden");
          $quantityWrapper.removeClass("rknm-hidden");
          $quantityInput.attr("data-cart_item_key", response.data.cart_item_key);
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
