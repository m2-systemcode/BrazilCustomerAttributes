/*jshint browser:true jquery:true expr:true*/
define([
    'jquery',
    'jquery/ui',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('mage.changePersonType', {
        options: {
            individualSelector: '[data-role=type-individual]',
            corporateSelector: '[data-role=type-corporation]',
            individualContainer: '[data-container=type-individual]',
            corporateContainer: '[data-container=type-corporation]',
            changeFirstnameLabel: false,
            changeLastnameLabel: false
        },

        /**
         * Create widget
         * @private
         */
        _create: function () {
            this.element.on('change', $.proxy(function () {
                this._checkChoice();
            }, this));

            this._checkChoice();
        },

        /**
         * Check choice
         * @private
         */
        _checkChoice: function () {
            if ($(this.options.corporateSelector).is(':checked')) {
                this._showCorporate();
            }  else {
                this._showIndividual();
            }
        },

        /**
         * Show individual input fields
         * @private
         */
        _showIndividual: function () {
            $(this.options.individualContainer).show();
            $(this.options.corporateContainer).hide();

            if(this.options.changeFirstnameLabel){
                $(".field-name-firstname label span").text($.mage.__('First Name'));
            }

            if(this.options.changeLastnameLabel){
                $(".field-name-lastname label span").text($.mage.__('Last Name'));
            }
        },

        /**
         * Show corporate input fields
         * @private
         */
        _showCorporate: function () {
            $(this.options.corporateContainer).show();
            $(this.options.individualContainer).hide();

            if(this.options.changeFirstnameLabel){
                $(".field-name-firstname label span").text($.mage.__('Social Name'));
            }

            if(this.options.changeLastnameLabel){
                $(".field-name-lastname label span").text($.mage.__('Trade Name'));
            }
        }
    });

    return $.mage.changePersonType;
});