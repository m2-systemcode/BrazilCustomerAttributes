define([
    'jquery',
    'stringUtils'
], function($, stringUtils) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-cpf',
            function(value) {
                return stringUtils.taxvat.isValidCPF(value);
            },
            $.mage.__('Invalid CPF.'),
        );

        $.validator.addMethod(
            'validate-cnpj',
            function(value) {
                return stringUtils.taxvat.isValidCNPJ(value);
            },
            $.mage.__('Invalid CNPJ.'),
        );
    };
});
