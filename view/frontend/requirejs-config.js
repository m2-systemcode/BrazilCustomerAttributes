/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            changePersonType: 'SystemCode_BrazilCustomerAttributes/change-person-type',
            exemptIe: 'SystemCode_BrazilCustomerAttributes/exempt-ie',
            inputMask: 'SystemCode_BrazilCustomerAttributes/jquery.mask',
            stringUtils: 'SystemCode_BrazilCustomerAttributes/string-utils'
        },
    },
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'SystemCode_BrazilCustomerAttributes/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'SystemCode_BrazilCustomerAttributes/js/action/create-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'SystemCode_BrazilCustomerAttributes/js/action/create-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'SystemCode_BrazilCustomerAttributes/js/action/place-order-mixin': true
            },
            'mage/validation': {
                'SystemCode_BrazilCustomerAttributes/js/widget/create-customer-account-mixin': true
            }
        }
    }
};
