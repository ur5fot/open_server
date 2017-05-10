$(function () {
    var $searchCity = $('.js-search-city');
    var $newPost = $('.js-new-post');
    // var $newPost = $('.js-new-post');
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


    $searchCity.on('select2:select', function (e) {
        var $this = $(this);
        // console.log('select2:select', arguments);
        $('.checkout--_partials--steps--shipping_options-list').show(300);
        if (+e.params.data.warehouse) {
            $.ajax({
                url: 'modules/belvg_freightdelivery/classes/AjaxRequest.php',
                dataType: 'json',
                type: 'GET',
                data: {
                    request: 'getCityWarehouse',
                    param1: $this.val()
                },
                success: function (data, elem) {
                    // console.log('select2:select ajax success', arguments);
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
    });
    /*$newPost.select2({
     placeholder: "Выберите отделение"
     });
     $newPost.prop("disabled", true);*/

});
