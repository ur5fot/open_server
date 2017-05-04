var freightDelivery = {
    init: function () {

        if ($("input.delivery_option_radio:checked").val() == freightdelivery_carrier_id + ',') {
            $("#freight_company_details").show();
        } else {
            $("#freight_company_details").hide();
        }

        $(document).on('change', 'textarea[name=freight_company_details]', function (e) {
            var freight_company_details = $(this).val();

            $.ajax({
                type: 'POST',
                headers: {"cache-control": "no-cache"},
                url: freightdelivery_controller,
                async: true,
                cache: false,
                dataType: 'json',
                data: 'action=saveFreightComplanyDetails'
                        + '&details=' + freight_company_details,
                success: function (jsonData)
                {
                    /*if (jsonData.details) {
                     $('textarea[name=freight_company_details]').animate({borderTopColor: 'green'});
                     console.log(jsonData.details);
                     }*/
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (textStatus !== 'abort')
                        alert("TECHNICAL ERROR: unable to save your Freight Company details \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
                }
            });
        });
    },

    getWarehouses: function ()
    {
        //console.log('asd');
        //console.log($('#deliveryCity').val());
        
        var data = `
{
    "modelName": "AddressGeneral",
    "calledMethod": "getWarehouses",
    "methodProperties": {
        "CityRef": "`+$('#deliveryCity').val()+`"
    },
    "apiKey": "f792d700f752e8c039cbf07801323474"
}`;
        
        console.log(data);
        //return;
        
        
        $.ajax({
                type: 'POST',
                headers: {"Content-Type": "application/json"},
                url: 'https://api.novaposhta.ua/v2.0/json/',
                async: false,
                dataType: 'json',
                data: data,
                success: function (jsonData)
                {
                    console.log(jsonData);
                    $('#warehouses').empty();
                    $.each(jsonData.data, function(key, value)
                    {
                        //console.log(value);
                        $('#warehouses').append('<option value="'+value.Ref+'">'+value.DescriptionRu+'</option>');
                    });
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error ajax POST request Nova Poshta');
                }
            });
        
        
        
        
        
        
        
        
        
        
        
        
    },
    
    check: function()
    {
        $('.delivery-options').on('click', '.delivery-option', showDeliveryElements);

        showDeliveryElements();

        function showDeliveryElements()
        {
            if (document.getElementById('delivery_option_' + freightdelivery_carrier_id).checked) {
                $("#freight_company_details").show();
            } else {
                $("#freight_company_details").hide();
            }
        }
    }
}

//when document is loaded...
$(document).ready(function () {
    freightDelivery.init();
    freightDelivery.check();
    freightDelivery.getWarehouses();
});

$('#deliveryCity').change(function ()
{
    freightDelivery.getWarehouses();
});