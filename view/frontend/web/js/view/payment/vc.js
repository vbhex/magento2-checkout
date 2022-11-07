define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push({
                type: 'vbhex_checkout',
                component: 'Vbhex_Checkout/js/view/payment/method-renderer/vc'
            });
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
