/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'Bleez_Correios/js/model/shipping-rates-validator',
        'Bleez_Correios/js/model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        correiosShippingRatesValidator,
        correiosShippingRatesValidationRules
    ) {
        'use strict';
        defaultShippingRatesValidator.registerValidator('correios', correiosShippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('correios', correiosShippingRatesValidationRules);
        return Component;
    }
);
