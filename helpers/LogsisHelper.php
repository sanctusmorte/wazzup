<?php

namespace app\helpers;

use Yii;

use app\models\Setting;

class LogsisHelper 
{
    const TIME_MOSCOW = [
        1 => '10-14',
        2 => '10-18',
        3 => '14-18',
        4 => '18-22'
    ];

    const TIME_SPB = [
        11 => '12-18',
        12 => '18-22',
        13 => '12-22'
    ];

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
     * Генерация данных для передачи заказа в Logsis
     * 
     * @param object $setting
     * @param array $save
     * @return array
     */

    public static function generateSaveData(Setting $setting, array $save): array
    {
        return [
            'key' => $setting->apikey,
            'inner_n' =>  ($setting->prefix_shop) ? $setting->prefix_shop . $save['orderNumber'] : $save['orderNumber'],
            'delivery_date' => $save['delivery']['deliveryDate'],
            'delivery_time' => self::getDeliveryTime($save), 
            'target_name' => self::getCustomerName($save),
            'target_contacts' => $save['customer']['phones'][0] ?? '',
            'target_email' => $save['customer']['email'] ?? '',
            'target_notes' => self::getTargetNotes($save),
            'os' => self::getDeclarated($save),
            'np' => ($save['delivery']['withCod'] ?? false) ? 1 : 0,
            'price_client' => self::getPriceClient($save),
            'price_client_delivery' => $save['delivery']['cost'] ?? 0,
            'order_weight' => ($weight = $save['packages'][0]['weight'] ?? false) ? self::convertingWeight($weight) : 0.5,
            'places_count' => 1,
            'dimension_side1' => ($width = $save['packages'][0]['width'] ?? false) ? self::convertingCabarites($width) : 10,
            'dimension_side2' => ($height = $save['packages'][0]['height'] ?? false) ? self::convertingCabarites($height) : 10,
            'dimension_side3' => ($length = $save['packages'][0]['length'] ?? false) ? self::convertingCabarites($length) : 10,
            'city' => $save['delivery']['deliveryAddress']['city'] ?? '',
            'addr' => self::getAddr($save),
            'post_code' => $save['delivery']['deliveryAddress']['index'] ?? '',
            'sms' => $save['delivery']['extraData']['is_sms'] ?? $setting->is_sms,
            'open_option' => ($save['delivery']['extraData']['is_open'] ?? $setting->is_open) ? 1 : 3,
            'call_option' => ($save['delivery']['extraData']['is_additional_call'] ?? $setting->is_additional_call) ? 1 : 0,
            'docs_option' => ($save['delivery']['extraData']['is_return_doc'] ?? $setting->is_return_doc) ? 1 : 0,
            'partial_option' => ($save['delivery']['extraData']['is_partial_redemption'] ?? $setting->is_partial_redemption) ? 1 : 0,
            'dress_fitting_option' => ($save['delivery']['extraData']['is_fitting'] ?? $setting->is_fitting) ? 1 : 0,
            'lifting_option' => ($save['delivery']['extraData']['is_skid'] ?? $setting->is_skid) ? 1 : 0,
            'cargo_lift' => ($save['delivery']['extraData']['is_cargo_lift'] ?? $setting->is_cargo_lift) ? 1 : 0,
            'goods' => self::getGoods($save)
        ];
    }

    /**
     * Генерация данных для массового получения статусо из Logsis
     * 
     * @param object $setting
     * @return array
     */

    public static function generateStatusData(Setting $setting): array
    {
        return [
            'key' => $setting->apikey,
            'from' => Yii::$app->formatter->asDateTime(time(), 'php:Y-m-d 00:00:00'),
            'to' => Yii::$app->formatter->asDateTime(time(), 'php:Y-m-d 23:59:59'),
        ];
    }

    /**
     * Формирование данных для подтверждения заказа в Logsis
     * 
     * @param object $setting
     * @param array $data
     * @return array
     */


    public static function generateConfirmOrderData(Setting $setting, array $data): array
    {
        return [
            'key' => $setting->apikey,
            'inner_n' => $data['inner_track'],
            'order_id' => $data['order_id']
        ];
    }

    /**
     * Получение времени доставкт 
     * 
     * @param array $data 
     * @return int
     */

    private static function getDeliveryTime(array $data): int 
    {   
        $timeFrom = self::getRegValue($data['delivery']['deliveryTime']['from'], "/[0-9]+/");
        $timeTo = self::getRegValue($data['delivery']['deliveryTime']['to'], "/[0-9]+/");

        if (in_array($data['delivery']['deliveryAddress']['region'], ['Москва город', 'Московская область'])) {

            return self::getTime(self::TIME_MOSCOW, $timeFrom, $timeTo);
        } elseif (in_array($data['delivery']['deliveryAddress']['region'], ['Санкт-Петербург город'])) {

            return self::getTime(self::TIME_SPB, $timeFrom, $timeTo);
        }

        return 2;
    }

    /**
     * Получение  времени из массива 
     * 
     * @param array $time
     * @param integer $from
     * @param integer $to
     * @return integer 
     */

    private static function getTime($time, $from, $to): int
    {
        $fromArr = [];
        $toArr = [];

        foreach ($time as $key => $value) {
            $explode = explode('-', $value);

            $fromArr[] = $explode[0];
            $toArr[] = $explode[1];
        }
        
        $minFrom = self::getMinValueInArray($fromArr, $from);
        $minTo = self::getMinValueInArray($toArr, $to); 
        $timeFlip = array_flip($time);

        if ($minFrom == $minTo) {
            foreach ($time as $key => $value) {
                $explode = explode('-', $value);
    
                if ($explode[1] == $minTo) {
                    return $key;
                } 
                return 2;
            }
        } else {
            return $timeFlip[$minFrom."-".$minTo] ?? 2;
        }
    }

    /**
     * Получение ближайшего значения в массиве
     * 
     * @param array $x
     * @param int $y
     * @return int
     */

    private static function getMinValueInArray(array $x, int $y)
    {
        $x[]=$y;
        sort($x);

        for ($i=0, $return=$x[0]; $i < count($x)-1; $i++) {
            if ($x[$i+1]==$y) {
                if ($i+1>=count($x) || $y-$x[$i] < $x[$i+2]-$y) $return=$x[$i];
                else $return=$x[$i+2];
                break;
            }
        }

        return $return;
    }

    /**
     * Применение регулярного выражения
     * 
     * @param string
     * @param string|null
     * @return string|boolean
     */

    private static function getRegValue($str, $reg) 
    {
        preg_match("$reg", $str, $matches);

        return $matches[0] ?? false;
    }

    /**
     * Формирование товаров
     * 
     * @param array $data
     * @return array
     */

    private static function getGoods(array $data): array 
    {
        $goods = [];

        foreach ($data['packages'] as $package) {
            foreach ($package['items'] as $item) {

                $goods[] = [
                    'articul' => '',
                    'artname' => $item['name'],
                    'count' => $item['quantity'],
                    'weight' => 0.1,
                    'price' => $item['cost'],
                    'nds' => ($vatRate = $item['vatRate'] ?? false) ? self::getNdsCode($item['vatRate']) : 2
                ];
            }
        }

        return $goods;
    }

    /**
     * Получение кода для налога в Logsis
     * 
     * @param string $vatRate
     * @return integer
     */

    private static function getNdsCode(string $vatRate): int
    {
        switch ($vatRate) {
            case 'vat0':
                $nds = 6;
                break;
            case 'vat10':
                $nds = 3;
                break;
            case 'vat20':
                $nds = 7;
                break;
            case 'vat110':
                $nds = 5;
                break;
            case 'vat120':
                $nds = 8;
                break;
            default:
                $nds = 2;
                break;
        }

        return $nds;
    }

    /**
     * Формирование строки адреса для передачи в Logsis
     * 
     * @param array $data
     * @return string
     */

    private static function getAddr(array $data): string 
    {
        $addr = '';

        if (isset($data['delivery']['deliveryAddress']['index']) && $data['delivery']['deliveryAddress']['index']) {
            $addr .= $data['delivery']['deliveryAddress']['index'] . ', ';
        }

        if (isset($data['delivery']['deliveryAddress']['cityType']) && $data['delivery']['deliveryAddress']['cityType']) {
            $addr .= $data['delivery']['deliveryAddress']['cityType'] . ' ';
        }

        if (isset($data['delivery']['deliveryAddress']['city']) && $data['delivery']['deliveryAddress']['city']) {
            $addr .= $data['delivery']['deliveryAddress']['city'] . ', ';
        }

        if (isset($data['delivery']['deliveryAddress']['streetType']) && $data['delivery']['deliveryAddress']['streetType']) {
            $addr .= $data['delivery']['deliveryAddress']['streetType'] . ' ';
        }

        if (isset($data['delivery']['deliveryAddress']['street']) && $data['delivery']['deliveryAddress']['street']) {
            $addr .= $data['delivery']['deliveryAddress']['street'] . ', ';
        }

        if (isset($data['delivery']['deliveryAddress']['building']) && $data['delivery']['deliveryAddress']['building']) {
            $addr .= 'д. ' . $data['delivery']['deliveryAddress']['building'];
        }

        return $addr;
    }

    /**
     * Сотавления комментария
     * 
     * @param array $data
     * @return string
     */

    private static function getTargetNotes(array $data): string 
    {
        $notes = '';

        if (isset($data['delivery']['deliveryAddress']['flat']) && $data['delivery']['deliveryAddress']['flat']) {
            $notes .= 'кв./офис ' . $data['delivery']['deliveryAddress']['flat'] . ', ';
        }

        if (isset($data['delivery']['deliveryAddress']['floor']) && $data['delivery']['deliveryAddress']['floor']) {
            $notes .= 'эт. ' . $data['delivery']['deliveryAddress']['floor'] . ', ';
        }

        if (isset($data['delivery']['deliveryAddress']['block']) && $data['delivery']['deliveryAddress']['block']) {
            $notes .= 'п-д. ' . $data['delivery']['deliveryAddress']['block'] . ', ';
        }

        if (isset($data['delivery']['deliveryAddress']['house']) && $data['delivery']['deliveryAddress']['house']) {
            $notes .= 'стр. ' . $data['delivery']['deliveryAddress']['house'] . ', ';
        }

        if (isset($data['delivery']['deliveryAddress']['housing']) && $data['delivery']['deliveryAddress']['housing']) {
            $notes .= 'корп. ' . $data['delivery']['deliveryAddress']['housing'];
        }

        return $notes;
    }

    /**
     * Получение суммы наложенного платежа
     * 
     * @param array $data
     * @return int|float
     */

    private static function getPriceClient(array $data)
    {
        $price_client = 0;

        foreach ($data['packages'] as $package) {
            foreach ($package['items'] as $item) {
                $price_client += $item['cod']*$item['quantity'];
            }
        }

        if (isset($data['delivery']['cod']) && $data['delivery']['cod']) {
            $price_client += $data['delivery']['cod'];
        }

        return $price_client;
    }

    /**
     *  Получение оценочной стоимости заказа retailCRM
     * 
     * @param array $data
     * @return int|float
     */

    private static function getDeclarated(array $data)
    {   
        $declarated = 0;

        foreach ($data['packages'][0]['items'] ?? false as $item) {
            $declarated += $item['declaredValue'] ?? 0;
        }

        return $declarated;
    }

    /**
     * Получение имени из данных заказа retailCRM
     * 
     * @param array $data
     * @return string
     */

    private static function getCustomerName(array $data): string
    {
        $name = '';

        if (isset($data['customer']['firstName']) && $data['customer']['firstName']) {
            $name .= $data['customer']['firstName'];
        }

        if (isset($data['customer']['patronymic']) && $data['customer']['patronymic']) {
            $name .= " " . $data['customer']['patronymic'];
        }

        if (isset($data['customer']['lastName']) && $data['customer']['lastName']) {
            $name .= " " . $data['customer']['lastName'];
        }

        return $name;
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

        if (isset($data['deliveryAddress']['region']) && $data['deliveryAddress']['region']) {
            $address .= $data['deliveryAddress']['region'] . ', ';
        }

        if (isset($data['deliveryAddress']['cityType']) && $data['deliveryAddress']['cityType']) {
            $address .= $data['deliveryAddress']['cityType'] . ' ';
        }

        if (isset($data['deliveryAddress']['city']) && $data['deliveryAddress']['city']) {
            $address .= $data['deliveryAddress']['city'] . ', ';
        }

        if (isset($data['deliveryAddress']['streetType']) && $data['deliveryAddress']['streetType']) {
            $address .= $data['deliveryAddress']['streetType'] . ' ';
        }

        if (isset($data['deliveryAddress']['streetType']) && $data['deliveryAddress']['streetType']) {
            $address .= $data['deliveryAddress']['streetType'] . ' ';
        }

        if (isset($data['deliveryAddress']['street']) && $data['deliveryAddress']['street']) {
            $address .= $data['deliveryAddress']['street'];
        }

        return $address;
    }
}