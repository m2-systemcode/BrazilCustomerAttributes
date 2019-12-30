define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';

    return function (setBillingInformationAction) {
        return wrapper.wrap(setBillingInformationAction, function (originalAction, messageContainer) {

            if (messageContainer.custom_attributes != undefined) {
                $.each(messageContainer.custom_attributes , function( key, value ) {
                    messageContainer['custom_attributes'][key] = value;
                });

            }
            return originalAction(messageContainer);
        });
    };
});
