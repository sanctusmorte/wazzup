<?php

namespace app\helpers;

use Yii;

use app\models\Setting;

class LogsisHelper 
{

    /**
     * Генерация данных для метода calculate
     * 
     * @param array $calculate
     * @return array
     */

    public static function generateCalculateData(Setting $setting, array $calculate): array
    {
        $q = [
            'apikey' => $setting->apikey,
            'nds' =>  $setting->is_nds,
            'address' => self::generateAddress($calculate),
            'cod_card' => 0,
            'cod_cash' => $calculate['declaredValue'] ?? '',
            'os' => $calculate['declaredValue'] ?? '',
            'weight' => $calculate['packages'][0]['weight'],
            'dimension_side1' => $calculate['packages'][0]['width'] ?? '',
            'dimension_side2' => $calculate['packages'][0]['height'] ?? '',
            'dimension_side3' => $calculate['packages'][0]['length'] ?? '',
            'is_return' => $setting->is_partial_return,
            'floor' => '',
            'cargo_lift' => $setting->is_cargo_lift,
            'options' => [
                'buyback' => '',
                'call' => $setting->is_additional_call,
                'equipment' => $setting->is_partial_redemption,
                'fitting' => $setting->is_fitting,
                'opening' => $setting->is_open,
                'packaging' => $setting->is_packing,
                'returned_doc' => $setting->is_return_doc,
                'skid_kgt' => $setting->is_skid,
                'sms' => $setting->is_sms
            ]
        ];

        // return $q;
        // echo "<pre>"; print_r($q); die;
        echo "<pre>"; print_r($setting);
        echo "<pre>"; print_r($calculate); die;
    }

    /**
     * Генерация адреса
     * 
     * @param array $data
     * @return string 
     */

    private static function generateAddress(array $data): string
    {
        $address = '';

        if ($data['deliveryAddress']['region']) {
            $address .= $data['deliveryAddress']['region'] . ', ';
        }

        if ($data['deliveryAddress']['cityType']) {
            $address .= $data['deliveryAddress']['cityType'] . ' ';
        }

        if ($data['deliveryAddress']['city']) {
            $address .= $data['deliveryAddress']['city'] . ', ';
        }

        if ($data['deliveryAddress']['text']) {
            $address .= $data['deliveryAddress']['text'] . ' ';
        }

        return $address;
    }
}