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
            return this;
        },

        onUpdate: function () {

        }
    });
});