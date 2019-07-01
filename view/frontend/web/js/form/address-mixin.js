require([
    'jquery',
    'inputMask',
    'mage/url'
], function ($, mask, url) {
    $("#postcode").mask('00000-000', {clearIfNotMatch: true});
    $('#postcode').change(function(){
        zipcode = $(this).val().replace('-', '');
        var ajaxurl = url.build("brcustomer/consult/address/zipcode/"+zipcode);

        $.ajax({
            url: ajaxurl,
            dataType: 'json',
            timeout: 4000,
            async: true
        }).done(function (data) {
            if(data.error){
                // TODO
            }else{
                $("#street_1").val(data.street);
                $("#street_3").val(data.neighborhood);
                $("#street_4").val(data.complement);
                $("#city").val(data.city);
                $("#country").val('BR');
                $("#region_id").val(data.uf);
            }
            $('#checkout-loader').remove();
        }).error(function(){});
    });

    var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            },
            clearIfNotMatch: true
        };

    $('#telephone').mask(SPMaskBehavior, spOptions);
});