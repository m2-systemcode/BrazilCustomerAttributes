define([
    'underscore',
    'ko',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'inputMask',
    'mage/url',
], function (_, ko, registry, Abstract, jquery, mask, url) {
    'use strict';

    return Abstract.extend({
        defaults: {
            loading: ko.observable(false)
        },

        initialize: function () {
            this._super();

            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    },
                    clearIfNotMatch: true
                };

            jquery('#'+this.uid).mask(SPMaskBehavior, spOptions);

            return this;
        }
    });
});