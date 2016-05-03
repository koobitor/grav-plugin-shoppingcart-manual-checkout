(function() {

    /***********************************************************/
    /* Handle Proceed to Payment
    /***********************************************************/
    jQuery(function() {
        jQuery(document).on('proceedToPayment', function(event, ShoppingCart) {
            if (ShoppingCart.gateway != 'manual_checkout') {
                return;
            }

            var order = {
                products: storejs.get('grav-shoppingcart-basket-data'),
                data: storejs.get('grav-shoppingcart-checkout-form-data'),
                shipping: storejs.get('grav-shoppingcart-shipping-method'),
                payment: 'manual',
                token: storejs.get('grav-shoppingcart-order-token').token,
                amount: ShoppingCart.totalOrderPrice.toString(),
                gateway: ShoppingCart.gateway
            };

            jQuery.ajax({
                url: ShoppingCart.settings.baseURL + ShoppingCart.settings.urls.saveOrderURL + '/task:pay',
                data: order,
                type: 'POST'
            })
            .success(function(redirectUrl) {
                ShoppingCart.clearCart();
                window.location = redirectUrl;
            })
            .error(function() {
                alert('Payment not successful. Please contact us.');
            });
        });

    });

})();
