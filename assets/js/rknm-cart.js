jQuery(document).ready(function ($) {
  toastr.options = {
    timeOut: 3000,
    extendedTimeOut: 1000,
    closeButton: true,
    progressBar: true,
    newestOnTop: true,
    preventDuplicates: false,
    //positionClass: "toast-bottom-right" // "toast-top-right"
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
    iconClass: null,
  };

  //1-btn back page
  $(document).on("click", "#btn_back", function (e) {
    e.preventDefault(); // Prevent default button action
    window.history.go(-1);
    return false;
  });

  //2-Cart --> CartNext
  $(document).on("click", "#rknm-cart-to-cartnext-btn", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var productId = $btn.data("product-id");
    var variationId = $btn.data("variation-id");
    var cartKey = $btn.data("cart-key");

    //$btn.prop("disabled", true).text("در حال انتقال...");
    $btn.addClass("loading").prop("disabled", true);
    $.ajax({
      url: rknm_ajax.ajax_url,
      type: "POST",
      data: {
        action: "rknm_move_to_next_purchase",
        product_id: productId,
        variation_id: variationId,
        cart_key: cartKey,
        nonce: rknm_ajax.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Replace the content of both tabs with the updated HTML
          $("#rknm-tabs-cart").html(response.data.html_cart_basket);
          $(".rknm-tab-count-cart").text(response.data.cart_count);
          $("#rknm-tabs-nextcart").html(response.data.html_next_purchase);
          $(".rknm-tab-count-nextcart").text(response.data.nextcart_count);
          toastr.success(response.data.message, "", {
            toastClass: "rknm-cart-toast-success",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
        } else {
          toastr.error(response.data.message, "", {
            toastClass: "rknm-cart-toast-error",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
          $btn.removeClass("loading").prop("disabled", false);
        }
        // Always re-enable the button
        //$btn.prop("disabled", false).text("انتقال به خرید بعدی >");
      },
      error: function (xhr) {
        var errorMessage = "خطا در انتقال محصول";
        if (
          xhr.responseJSON &&
          xhr.responseJSON.data &&
          xhr.responseJSON.data.message
        ) {
          errorMessage = xhr.responseJSON.data.message;
        }
        toastr.error(errorMessage, "", {
          toastClass: "rknm-cart-toast-error",
          toastId: "rknm_cart_toast-" + Date.now(),
        });

        //$btn.prop("disabled", false).text("انتقال به خرید بعدی   >");
        $btn.removeClass("loading").prop("disabled", false);
      },
    });
  });

  //3-CartNext --> Cart
  $(document).on("click", "#rknm-cartnext-to-cart-btn", function (e) {
    e.preventDefault();

    var $btn = $(this);
    var productId = $btn.data("product-id");
    var variationId = $btn.data("variation-id");

    $btn.addClass("loading").prop("disabled", true);

    $.ajax({
      url: rknm_ajax.ajax_url,
      type: "POST",
      data: {
        action: "rknm_add_to_cart_from_next_purchase",
        product_id: productId,
        variation_id: variationId,
        nonce: rknm_ajax.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Replace the content of both tabs with the updated HTML
          $("#rknm-tabs-cart").html(response.data.html_cart_basket);
          $(".rknm-tab-count-cart").text(response.data.cart_count);
          $("#rknm-tabs-nextcart").html(response.data.html_next_purchase);
          $(".rknm-tab-count-nextcart").text(response.data.nextcart_count);
          // Show success toast
          toastr.success(response.data.message, "", {
            toastClass: "rknm-cart-toast-success",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
        } else {
          // Show error toast
          toastr.error(response.data.message, "", {
            toastClass: "rknm-cart-toast-error",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
        }
        // Always re-enable the button
        $btn.removeClass("loading").prop("disabled", false);
      },
      error: function (xhr) {
        var errorMessage = "خطا در افزودن محصول";
        if (
          xhr.responseJSON &&
          xhr.responseJSON.data &&
          xhr.responseJSON.data.message
        ) {
          errorMessage = xhr.responseJSON.data.message;
        }
        toastr.error(errorMessage, "", {
          toastClass: "rknm-cart-toast-error",
          toastId: "rknm_cart_toast-" + Date.now(),
        });
        $btn.removeClass("loading").prop("disabled", false);
      },
    });
  });

  //4-Del(CartNext)
  $(document).on("click", "#rknm-cartnext-del-btn", function (e) {
    e.preventDefault();

    var $btn = $(this);
    var productId = $btn.data("product-id");
    var variationId = $btn.data("variation-id");

    // Show custom confirmation popup
    $("#rknm-nextcart-del-modal").removeClass("rknm-hidden").addClass("show");

    // Handle confirm button
    $("#rknm-nextcart-remove-modal-confirm")
      .off("click")
      .on("click", function () {
        var $confirmBtn = $(this);
        $confirmBtn
          .addClass("loading")
          .prop("disabled", true)
          .text("در حال حذف...");

        $.ajax({
          url: rknm_ajax.ajax_url,
          type: "POST",
          data: {
            action: "rknm_remove_from_next_purchase",
            product_id: productId,
            variation_id: variationId,
            nonce: rknm_ajax.nonce,
          },
          success: function (response) {
            if (response.success) {
              // Remove item from display
              $btn.closest(".rknm-next-purchase-item").fadeOut();

              $("#rknm-tabs-nextcart").html(response.data.html_next_purchase);
              $(".rknm-tab-count-nextcart").text(response.data.nextcart_count);

              // Remove loading state
              $confirmBtn.removeClass("loading").prop("disabled", false);

              // Hide and reset the modal
              $("#rknm-nextcart-del-modal")
                .removeClass("show")
                .addClass("rknm-hidden");

              // Show success toast
              toastr.success(response.data.message, "", {
                toastClass: "rknm-cart-toast-success",
                toastId: "rknm_cart_toast-" + Date.now(),
              });
            }
          },
          error: function () {
            // Show error toast
            toastr.error(response.data.message, "", {
              toastClass: "rknm-cart-toast-error",
              toastId: "rknm_cart_toast-" + Date.now(),
            });
            /** */
            $btn.prop("disabled", false).text("حذف");
            $confirmBtn.removeClass("loading").prop("disabled", false);
            $("#rknm-nextcart-del-modal")
              .removeClass("show")
              .addClass("rknm-hidden");
          },
        });
      });

    // Handle cancel button
    $("#rknm-nextcart-remove-modal-cancel")
      .off("click")
      .on("click", function () {
        $("#rknm-nextcart-del-modal")
          .removeClass("show")
          .addClass("rknm-hidden");
      });
  });

  //5-Del(Cart)
  $(document).on("click", "#rknm-cart-del-btn", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var productId = $btn.data("product-id");
    var variationId = $btn.data("variation-id");
    var cartitemkey = $btn.data("cart_item_key");

    // Show custom confirmation popup
    $("#rknm-cart-del-modal").removeClass("rknm-hidden").addClass("show");
    // Handle confirm button
    $("#rknm-cart-remove-modal-confirm")
      .off("click")
      .on("click", function () {
        var $confirmBtn = $(this);
        $confirmBtn
          .addClass("loading")
          .prop("disabled", true)
          .text("در حال حذف...");

        $.ajax({
          url: rknm_ajax_cart.ajax_url,
          type: "POST",
          data: {
            action: "rknm_remove_from_cart",
            product_id: productId,
            variation_id: variationId,
            cart_item_key: cartitemkey,
            nonce: rknm_ajax_cart.nonce,
          },

          success: function (response) {
            if (response.success) {
              // Remove item from display
              $btn.closest(".rknm-oreder-cart-item").fadeOut();
              $("#rknm-tabs-cart").html(response.data.html_cart_basket);
              $(".rknm-tab-count-cart").text(response.data.cart_count);
              $confirmBtn
                .removeClass("loading")
                .prop("disabled", false)
                .text("حذف");
              $("#rknm-cart-del-modal")
                .removeClass("show")
                .addClass("rknm-hidden");
              toastr.success(response.data.message, "", {
                toastClass: "rknm-cart-toast-success",
                toastId: "rknm_cart_toast-" + Date.now(),
              });
            }
          },
          error: function () {
            $btn.closest(".rknm-oreder-cart-item").fadeOut();
            $confirmBtn
              .removeClass("loading")
              .prop("disabled", false)
              .text("حذف");
            $("#rknm-cart-del-modal")
              .removeClass("show")
              .addClass("rknm-hidden");
            toastr.error(response.data.message, "", {
              toastClass: "rknm-cart-toast-error",
              toastId: "rknm_cart_toast-" + Date.now(),
            });
          },
        });
      });

    // Handle cancel button
    $("#rknm-cart-remove-modal-cancel")
      .off("click")
      .on("click", function () {
        $("#rknm-cart-del-modal").removeClass("show").addClass("rknm-hidden");
      });
  });

  //6-All CartNext --> Cart
  $(document).on("click", "#rknmAddAllToCart", function (e) {
    e.preventDefault();
    var $btn = $(this);

    $btn.addClass("loading").prop("disabled", true);

    $.ajax({
      url: rknm_ajax.ajax_url,
      type: "POST",
      data: {
        action: "rknm_add_all_to_cart_from_next_purchase",
        nonce: rknm_ajax.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Replace the content of both tabs with the updated HTML
          $("#rknm-tabs-cart").html(response.data.html_cart_basket);
          $(".rknm-tab-count-cart").text(response.data.cart_count);
          if (response.data.html_next_purchase) {
            $("#rknm-tabs-nextcart").html(response.data.html_next_purchase);
            $(".rknm-tab-count-nextcart").text(response.data.nextcart_count);
          }
          // Show success toast
          toastr.success(response.data.message, "", {
            toastClass: "rknm-cart-toast-success",
            toastId: "rknm_cart_toast-" + Date.now(),
          });
        } else {
          // Show error toast
          toastr.error(response.data.message, "", {
            toastClass: "rknm-cart-toast-error",
            toastId: "rknm_cart_toast-" + Date.now(),
          });

          $btn.removeClass("loading").prop("disabled", false);
        }
      },
      error: function () {
        // Show error toast
        toastr.error("خطا در افزودن محصولات به سبد خرید", "", {
          toastClass: "rknm-cart-toast-error",
          toastId: "rknm_cart_toast-" + Date.now(),
        });

        $btn.removeClass("loading").prop("disabled", false);
      },
    });
  });

  // Close popup when clicking outside
  $(document).on("click", ".rknm-cart-remove-modal", function (e) {
    if ($(e.target).hasClass("rknm-cart-remove-modal")) {
      $(".rknm-cart-remove-modal").removeClass("show").addClass("rknm-hidden");
    }
  });

  // Close popup when clicking close button
  $(".rknm-cart-remove-modal-close").on("click", function () {
    $(".rknm-cart-remove-modal").removeClass("show").addClass("rknm-hidden");
  });

  /* tabs */
  // Make the opentabs function available globally
  window.opentabs = function (evt, tabsName) {
    let i, tabscontent, tabslinks;
    tabscontent = document.getElementsByClassName("tabscontent");
    for (i = 0; i < tabscontent.length; i++) {
      tabscontent[i].style.display = "none";
    }
    tabslinks = document.getElementsByClassName("tabslinks");
    for (i = 0; i < tabslinks.length; i++) {
      tabslinks[i].className = tabslinks[i].className.replace(" active", "");
    }
    // Use jQuery to show the tab content to avoid issues after AJAX replacement
    $("#" + tabsName).show();
    evt.currentTarget.className += " active";
  };

  // Get the element with id="defaultOpen" and click on it
  if (document.getElementById("defaultOpen")) {
    document.getElementById("defaultOpen").click();
  }

  //7-Update cart quantity
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
      updateCartQuantity(cartItemKey, currentVal + 1, $wrapper);
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
      updateCartQuantity(cartItemKey, currentVal - 1, $wrapper);
    }
  });
});
