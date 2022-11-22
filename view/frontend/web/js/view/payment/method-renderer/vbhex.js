define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component, $) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Vbhex_Checkout/payment/vbhex',
                selectedCoin: ''
            },
            /** Returns send check to info */
            getMailingAddress: function() {
                return window.checkoutConfig.payment.checkmo.mailingAddress;
            },
            initObservable: function () {

                this._super()
                    .observe([
                        'selectedCoin'
                    ]);
                return this;
            },

            getCode: function() {
                return 'vbhex_checkout';
            },

            getData: function() {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'selected_coin': this.selectedCoin()
                    }
                };
            },

            validCoins: function() {
               return _.map(window.checkoutConfig.payment.sample_gateway1.validCoins, function(value, key) {
                    return {
                        'value': value.entity_id,
                        'selected_coin': value.symbol
                        // 'selected_coin': value.entity_id +' - '+value.symbol
                    }
                });
            },

            getDescription: function() {
                return window.checkoutConfig.payment.sample_gateway1.payment_description;
            },

            isWallets: function(){
                return window.checkoutConfig.payment.sample_gateway1.isWallets;
            },

            singleSeller: function(){
                return window.checkoutConfig.payment.sample_gateway1.singleSeller;
            }
        });
    }
);
