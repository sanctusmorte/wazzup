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
        return [
            'apikey' => $setting->apikey,
            'nds' =>  $setting->is_nds,
            'address' => self::generateAddress($calculate),
            'cod_card' => 0,
            'cod_cash' => $calculate['declaredValue'] ?? '',
            'os' => $calculate['declaredValue'] ?? '',
            'weight' => ($weight = $calculate['packages'][0]['weight'] ?? false) ? self::convertingWeight($weight) : '',
            'dimension_side1' => ($width = $calculate['packages'][0]['width'] ?? false) ? self::convertingCabarites($width) : '',
            'dimension_side2' => ($height = $calculate['packages'][0]['height'] ?? false) ? self::convertingCabarites($height) : '',
            'dimension_side3' => ($length = $calculate['packages'][0]['length'] ?? false) ? self::convertingCabarites($length) : '',
            'is_return' => $calculate['extraData']['is_partial_return'] ?? $setting->is_partial_return,
            'floor' => '',
            'cargo_lift' => $calculate['extraData']['is_cargo_lift'] ?? $setting->is_cargo_lift,
            'options' => [
                'buyback' => '',
                'call' => $calculate['is_additional_call'] ?? $setting->is_additional_call,
                'equipment' => $calculate['is_partial_redemption'] ?? $setting->is_partial_redemption,
                'fitting' => $calculate['is_fitting'] ?? $setting->is_fitting,
                'opening' => $calculate['is_open'] ?? $setting->is_open,
                'packaging' => $calculate['is_packaging'] ?? $setting->is_packaging,
                'returned_doc' => $calculate['is_return_doc'] ?? $setting->is_return_doc,
                'skid_kgt' => $calculate['is_skid'] ?? $setting->is_skid,
                'sms' => $calculate['is_sms'] ?? $setting->is_sms
            ]
        ];
    }

    /**
     * Перевод габаритов из мм в см (в retailCRM мм, в Logsis см)
     * 
     * @param float|int
     * @return float|int
     */

    private static function convertingCabarites($value)
    {
        return $value / 10;
    }

    /**
     * Перевод граммов в кг (в retailCRM г, в Logsis кг)
     * 
     * @param float|int
     * @return float|int
     */

    private static function convertingWeight($value)
    {
        return $value / 1000;
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