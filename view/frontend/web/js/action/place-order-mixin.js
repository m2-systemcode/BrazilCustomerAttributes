define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, messageContainer) {
            var billingAddress = quote.billingAddress();

            if (billingAddress['extension_attributes'] === undefined) {
                billingAddress['extension_attributes'] = {};
            }

            if (billingAddress.customAttributes !== undefined) {
                $.each(billingAddress.customAttributes, function (key, value) {
                    var attrCode = value['attribute_code'];
                    var attrValue = value['value'];

                    billingAddress['customAttributes'][attrCode] = value;
                    billingAddress['extension_attributes'][attrCode] = attrValue;
                });
            }

            return originalAction(messageContainer);
        });
    };
});