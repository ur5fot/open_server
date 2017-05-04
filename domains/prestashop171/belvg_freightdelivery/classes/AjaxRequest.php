<?php

include_once '../../../config/config.inc.php';

class AjaxRequest {
    /*
     * param1 it is cityName or cityId depend on requestName
     */

    private $result;
    private $requestAPI_URL = 'https://api.novaposhta.ua/v2.0/json/';
    private $additional_headers = ['Content-Type: application/json'];

    function __construct($requestMethodName, $param1 = false) {
        if (method_exists($this, $requestMethodName)) {
            $this->result = $this->$requestMethodName($param1);
        } else {
            $this->result = json_encode(false);
        }
        print_r($this->result);
    }

    private function getCitiesByString($requestCityName = "") {
        //return __FUNCTION__."<br>".$requestCityName;

        if ($requestCityName != "" && $requestCityName != null && !is_numeric($requestCityName)) {
            $select = Db::getInstance()->executeS(
                    'SELECT id, deliveryRef, descriptionRu FROM `ps_rposoft_nova_poshta_locations_delivery` WHERE descriptionRu LIKE "%' . $requestCityName . '%"'
            );
            return json_encode($select);
        }
        return json_encode(false);
    }

    private function getCityWarehouse($requestCityRef = null) {
        //return __FUNCTION__ . "<br>" . $requestCityId;

        if ($requestCityRef != "" && $requestCityRef != null && !is_numeric($requestCityRef)) {
            $data = "
{
    \"modelName\": \"AddressGeneral\",
    \"calledMethod\": \"getWarehouses\",
    \"methodProperties\": {
        \"CityRef\": \"$requestCityRef\"
    },
    \"apiKey\": \"f792d700f752e8c039cbf07801323474\"
}";

            $response = $this->sendPostRequest(
                    $this->requestAPI_URL, $data, $this->additional_headers
            );
            return $response;
        }
        return json_encode(false);
    }

    private function sendPostRequest($url, $data, $additional_headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $additional_headers);
        return curl_exec($ch);
    }
}

new AjaxRequest($_GET['request'], $_GET['param1']);
