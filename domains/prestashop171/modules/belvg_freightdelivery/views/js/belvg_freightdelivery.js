$(function () {
    var $searchCity = $('.js-search-city');
    var $newPost = $('.js-new-post');
    // var $newPost = $('.js-new-post');

    var $city = $('input[name=city]');
    var $address2 = $('input[name=address2]');
    // var $address2 = $('<input name="address2" type="text" value="">');

    // $city.parent().hide();
    // $address2.parent().hide();

    $searchCity.select2({
        language: "ru",
        placeholder: 'Выберите город',
        ajax: {
            url: 'modules/belvg_freightdelivery/classes/AjaxRequest.php',
            dataType: 'json',
            delay: 500,
            type: 'GET',
            data: function (params) {
                // console.log('data', params);
                return {
                    request: 'getCitiesByString',
                    param1: params.term
                };
            },
            processResults: function (data, params) {
                // console.log('processResults', arguments);
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.length
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 3,
        templateResult: function (data, elem) {
            // console.log('templateResult',arguments);

            if (!data.disabled) {
                var tpl = _.template($('#tpl-new-post-list-city').html());
                return $(elem).html(tpl(data));
            }
        },
        templateSelection: function (data, container) {
            // console.log('templateSelection ', arguments);
            if (data.id === '') {
                return 'Выберете город';
            }
            return data.descriptionRu;
        }

    });

var e ;
    $searchCity.on('select2:select', function (ee) {

        var $this = $(this);
        e = ee;
        console.log('select2:select', e);
        $city.val(e.params.data.id);
        $address2.val(e.params.data.warehouse);

    });

    if ($('#checkout-delivery-step').is('.-current')) {

       /* var cityData =  JSON.parse(prestashop.customer.addresses[prestashop.cart.id_address_delivery].address2);

        console.log(cityData);*/

        $('.checkout--_partials--steps--shipping_options-list').show(300);
        if (+prestashop.customer.addresses[prestashop.cart.id_address_delivery].address2) {
          /*  console.log(prestashop.customer.addresses[prestashop.cart.id_address_delivery].city);*/
            $.ajax({
                url: 'modules/belvg_freightdelivery/classes/AjaxRequest.php',
                dataType: 'json',
                type: 'GET',
                data: {
                    request: 'getCityWarehouse',
                    param1: prestashop.customer.addresses[prestashop.cart.id_address_delivery].city
                },
                success: function (data, elem) {
                    console.log('select2:select ajax success', arguments);
                    var selects = [];


                    $.map(data.data, function (item, i) {
                        selects.push({
                            id: item.Ref,
                            text: item.DescriptionRu,
                            paramters: item

                        })
                    });

                    $newPost
                        .removeClass('hide')
                        .empty()
                        .select2({
                            language: "ru",
                            placeholder: "",
                            data: selects,
                            templateSelection: function (_data, container) {
                                // console.log('s-example-data-array templateSelection ', arguments);

                                if (_data.paramters) {

                                    var tpl = _.template($('#tpl-new-post-dis').html());
                                    $('.result')
                                        .hide()
                                        .html(tpl(_data.paramters))
                                        .show(300);

                                    return _data.text;

                                }
                            }
                        })
                        .prop("disabled", false);

                    $('.new-post-wrapp').show(300);

                    $('#delivery_option_5')
                        .click()
                        .parent('.delivery-option')
                        .show(300);

                }

            })
        } else {
            $('.new-post-wrapp').hide(300);

            $('#delivery_option_3').click();
            $('#delivery_option_5')
                .parent('.delivery-option')
                .hide(300);

            $newPost.prop("disabled", true)
        }
    }

    /*$newPost.select2({
     placeholder: "Выберите отделение"
     });
     $newPost.prop("disabled", true);*/

});
