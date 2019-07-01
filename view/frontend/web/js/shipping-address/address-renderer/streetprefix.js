define([
    'underscore',
    'ko',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract'
], function (_, ko, registry, Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            loading: ko.observable(false)
        },

        initialize: function () {
            this._super();
            return this;
        }
    });
});