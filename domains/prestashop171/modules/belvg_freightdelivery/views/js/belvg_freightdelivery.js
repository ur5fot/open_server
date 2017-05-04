$(function () {

    var baseUrl = prestashop.urls.base_url;

    $(".js-data-example-ajax").select2({
        placeholder: 'Выберете город',
        /* allowClear: true,*/
        ajax: {
            url: baseUrl + 'modules/belvg_freightdelivery/classes/' + 'AjaxRequest.php',
            dataType: 'json',
            delay: 250,
            type: 'GET',
            data: function (params) {
                // console.log('data', params);
                return {
                    request: 'getCitiesByString',
                    param1: params.term
                };
            },
            processResults: function (data, params) {


                params.page = params.page || 1;
                // console.log('processResults', arguments);

                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.length
                    }
                };
            },
            success: function (data) {
                // console.log('select2 ajax', data);
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            // console.log('select2 escapeMarkup', markup);

            return markup;

        }, // let our custom formatter work
        minimumInputLength: 2,
        templateResult: function (data, elem) {
            if (data.id === '') {
                return 'Custom styled placeholder text';
            }
            // console.log('templateResult',arguments);

            return data.descriptionRu;
        },
        templateSelection: function (data, container) {
            // console.log('templateSelection ', arguments);
            if (data.id === '') {
                return 'Выберете город';
            }
            return data.descriptionRu;




        }

    });

    $(".js-data-example-ajax").on('select2:select', function (e) {
        var $this = $(this);
        console.log('select2:select', arguments);
        // console.log('select2:select', $this.val());
        $.ajax({
            url: baseUrl + 'modules/belvg_freightdelivery/classes/' + 'AjaxRequest.php',
            dataType: 'json',
            type: 'GET',
            data: {
                request: 'getCityWarehouse',
                param1: $this.val()
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

                $(".js-example-data-array").empty();
                console.log(selects);
                $(".js-example-data-array").select2({
                    placeholder: "Выберите отделение",
                    data: selects,
                    templateSelection: function (_data, container) {
                        console.log('js-example-basic-single templateSelection ', arguments);
                       /* if (_data.id === '') {
                            return 'Выберите отделение';
                        }*/
                        if (_data.paramters) {
                            $('.result').html('<h2>' + _data.paramters.CityDescriptionRu + '</h2> <p>' + _data.paramters.DescriptionRu + '</p>');
                            return _data.text;
                        };

                    }
                });

                /*$(".js-example-data-array").select2({
                 data:selects,
                 templateSelection:function (datas, container) {
                 console.log('js-example-basic-single templateSelection ', arguments);
                 if (data.id !== 1) {
                 $('.result').html('<h2>' + datas.data.CityDescriptionRu + '</h2> <p>' + datas.data.DescriptionRu + '</p>');
                 // return data.text;
                 }
                 return data.text;
                 }
                 });*/
                $(".js-example-data-array").prop("disabled", false);

                // console.log('select2 success',data);
                /* $('.result').html('');
                 $.each(data.data,function (i, item) {
                 // console.log(item);
                 $('.result').append('<p>' + item.DescriptionRu + '</p>' )
                 });*/

            }

        })
    });
    $(".js-example-data-array").select2({
        placeholder: "Выберите отделение"
    });
    $(".js-example-data-array").prop("disabled", true);


    /*$(".js-example-basic-single").on('select2:select', function (e) {
     console.log('.js-example-basic-single select2:select', arguments);
     });*/

    /*
     {
     "id": "value attribute" || "option text",
     "text": "label attribute" || "option text",
     "element": HTMLOptionElement
     }
     */


    // $(".js-data-example-ajax").trigger('select2:select');


});
