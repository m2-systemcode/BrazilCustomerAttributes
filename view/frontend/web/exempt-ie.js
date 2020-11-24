/*jshint browser:true jquery:true expr:true*/
define([
    'jquery',
], function ($) {
    'use strict';

    $.widget('mage.ie', {
        options: {
            ieFieldSelector: '#ie',
            exemptIeFieldSelector: '#exemptIe',
        },

        /**
         * Create widget
         * @private
         */
        _create: function () {
            this.element.on('change', $.proxy(function () {
                this._checkChoice();
            }, this));

            this._checkChoice(true);
        },

        /**
         * Check choice
         * @private
         */
        _checkChoice: function (create = false) {
            const ieField = $(this.options.ieFieldSelector);
            const ieFieldValue = ieField.val();
            const exemptIeField = $(this.options.exemptIeFieldSelector);
            const exemptValue = $.mage.__('EXEMPT');

            if (exemptValue === ieFieldValue && create === true) {
                exemptIeField.attr('checked', true);
                ieField.val(exemptValue).attr('readonly', true);
                return;
            }

            if (exemptIeField.is(':checked')) {
                ieField.val(exemptValue).attr('readonly', true);
                return;
            }

            exemptIeField.attr('checked', false);
            ieField.val('').attr('readonly', false);
        },
    });

    $('#exempt_ie').mage('ie');

    return $.mage.ie;
});
