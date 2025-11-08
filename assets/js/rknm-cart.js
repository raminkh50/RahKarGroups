/* all click */
jQuery(document).ready(function ($) {
  // Configure toastr
  /*
  toastr.options = {
    timeOut: 3000,
    extendedTimeOut: 1000,
    closeButton: true,
    progressBar: true,
    newestOnTop: true,
    preventDuplicates: false,
    positionClass: "toast-bottom-right" // "toast-top-right"

    //showMethod: "fadeIn",
    //hideMethod: "fadeOut",
    //iconClass: null,
  };
*/
  //btn back page //
  /*
  $(document).on("click", ".btn_back", function (e) {
    window.history.go(-1);
    return false;
  });
*/
  // Move to Next Purchase
  $(document).on("click", ".rknm-move-btn", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var productId = $btn.data("product-id");
    var variationId = $btn.data("variation-id");
    var cartKey = $btn.data("cart-key");

    $btn.prop("disabled", true).text("در حال انتقال...");

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
          // Remove cart item from display and append new item to next cart
          $btn.closest(".rknm-oreder-cart-item").fadeOut(300, function() { $(this).remove(); });

          if (response.data.item_html) {
              $('.rknm-next-purchase-grid').append(response.data.item_html);
              // If the "empty" message is visible, hide it
              if ($('.rknm-next-purchase-products .rknm-empty-cart').is(':visible')) {
                  $('.rknm-next-purchase-products .rknm-empty-cart').hide();
              }
          }

          // Update cart count
          $(".rknm-tab-count-cart").text(response.data.cart_count);
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
          $btn.prop("disabled", false).text("انتقال به خرید بعدی   >");
        }
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

        // Show error toast
        toastr.error(errorMessage, "", {
          toastClass: "rknm-cart-toast-error",
          toastId: "rknm_cart_toast-" + Date.now(),
        });

        $btn.prop("disabled", false).text("انتقال به خرید بعدی   >");
      },
    });
  });

  // Add to Cart from Next Purchase
  $(document).on("click", ".rknm-nextcart-add-btn", function (e) {
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
          // Remove item from display and append new item to cart
          $btn.closest(".rknm-next-purchase-item").parent('a').fadeOut(300, function() { $(this).remove(); });

          if (response.data.item_html) {
              $('.rknm-r21').append(response.data.item_html);
              // If the "empty" message is visible, hide it
              if ($('#rknm-tabs-cart .rknm-empty-cart').is(':visible')) {
                  $('#rknm-tabs-cart .rknm-empty-cart').hide();
              }
          }

          // Update cart count
          $(".rknm-tab-count-cart").text(response.data.cart_count);
          $(".rknm-tab-count-nextcart").text(response.data.nextcart_count);

          // Remove loading state
          $btn.removeClass("loading").prop("disabled", false);

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

        $btn.removeClass("loading").prop("disabled", false);
      },
    });
  });

  // Remove from Next Purchase
  $(document).on("click", ".rknm-nextcart-remove-btn", function (e) {
    e.preventDefault();

    var $btn = $(this);
    var productId = $btn.data("product-id");
    var variationId = $btn.data("variation-id");

    // Show custom confirmation popup
    $("#rknm-nextcart-remove-modal").removeClass("rknm-modal-hidden").addClass("show");

    // Handle confirm button
    $("#rknm-nextcart-remove-modal-confirm")
      .off("click")
      .on("click", function () {
        var $confirmBtn = $(this);
        $confirmBtn
          .addClass("loading")
          .prop("disabled", true)
          .text("در حال حذف...");
        //$btn.prop("disabled", true).text("در حال حذف...");

        //console.log("Loading state added to confirm button"); // Debug log

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
              // Update cart count
              $(".rknm-tab-count-nextcart").text(response.data.nextcart_count);

              // Remove loading state
              $confirmBtn.removeClass("loading").prop("disabled", false);

              // Hide and reset the modal
              $("#rknm-nextcart-remove-modal")
                  .removeClass("show")
                  .addClass("rknm-modal-hidden");

              // Show success toast
              toastr.success("محصول با موفقیت حذف شد", "", {
                toastClass: "rknm-cart-toast-success",
                toastId: "rknm_cart_toast-" + Date.now(),
              });
            }
          },
          error: function () {
            // Show error toast
            toastr.error("خطا در حذف محصول", "", {
              toastClass: "rknm-cart-toast-error",
              toastId: "rknm_cart_toast-" + Date.now(),
            });
            /** */
            $btn.prop("disabled", false).text("حذف");
            $confirmBtn.removeClass("loading").prop("disabled", false);
            $("#rknm-nextcart-remove-modal")
              .removeClass("show")
              .addClass("rknm-modal-hidden");
          },
        });
      });

    // Handle cancel button
    $("#rknm-nextcart-remove-modal-cancel")
      .off("click")
      .on("click", function () {
        $("#rknm-nextcart-remove-modal")
          .removeClass("show")
          .addClass("rknm-modal-hidden");
      });
  });

  // Remove from Cart
  $(document).on("click", ".rknm-cart-remove-btn", function (e) {
    e.preventDefault();
    //e.stopImmediatePropagation();
    var $btn = $(this);
    var productId = $btn.data("product-id");
    var variationId = $btn.data("variation-id");
    var cartitemkey = $btn.data("cart_item_key");

    // Show custom confirmation popup
    $("#rknm-cart-remove-modal").removeClass("rknm-modal-hidden").addClass("show");

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
              $(".rknm-tab-count-cart").text(response.data.cart_count); // Update cart count
              $confirmBtn
                .removeClass("loading")
                .prop("disabled", false)
                .text("حذف");
              $("#rknm-cart-remove-modal")
                .removeClass("show")
                .addClass("rknm-modal-hidden");

              toastr.success("محصول با موفقیت حذف شد", "", {
                toastClass: "rknm-cart-toast-success",
                toastId: "rknm_cart_toast-" + Date.now(),
              }); // Show success toast
            }
          },
          error: function () {
            $btn.closest(".rknm-oreder-cart-item").fadeOut();
            $confirmBtn
              .removeClass("loading")
              .prop("disabled", false)
              .text("حذف");
            $("#rknm-cart-remove-modal")
              .removeClass("show")
              .addClass("rknm-modal-hidden");
            toastr.error("خطا در حذف محصول", "", {
              toastClass: "rknm-cart-toast-error",
              toastId: "rknm_cart_toast-" + Date.now(),
            }); // Show error toast
          },
        });
      });

    // Handle cancel button
    $("#rknm-cart-remove-modal-cancel")
      .off("click")
      .on("click", function () {
        $("#rknm-cart-remove-modal")
          .removeClass("show")
          .addClass("rknm-modal-hidden");
      });
  });

  // Close popup when clicking outside
  $(document).on("click", ".rknm-nextcart-remove-modal", function (e) {
    if ($(e.target).hasClass("rknm-nextcart-remove-modal")) {
      $("#rknm-nextcart-remove-modal")
        .removeClass("show")
        .attr("style", "display: none;");
    }
  });
  $(document).on("click", "#rknm-cart-remove-modal", function (e) {
    if ($(e.target).hasClass("rknm-cart-remove-modal")) {
      $("#rknm-cart-remove-modal")
        .removeClass("show")
        .attr("style", "display: none;");
    }
  });
  /*
  // Prevent popup close when clicking inside content
  $(".rknm-nextcart-remove-modal-content").on("click", function (e) {
    e.stopPropagation();
  });

  // Close popup when clicking close button
  $(".rknm-nextcart-remove-modal-close").on("click", function () {
    $("#rknm-nextcart-remove-modal").removeClass("show").attr("style", "display: none;");
  });
*/
  // Add All to Cart from Next Purchase
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
          // Remove all items from display
          $(".rknm-next-purchase-item").fadeOut();
          // Update cart count
          $(".rknm-tab-count-cart").text(response.data.cart_count);
          $(".rknm-tab-count-nextcart").text(response.data.nextcart_count);

          // Remove loading state
          $btn.removeClass("loading").prop("disabled", false);

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
});