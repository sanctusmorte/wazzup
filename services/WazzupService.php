<?php

namespace app\services;

use app\helpers\RetailTransportMgHelper;
use Yii;

/**
 * Class WazzupService
 * @package app\services
 */
class WazzupService
{
    private $settingService;
    private $retailTransportMgHelper;

    /**
     * WazzupService constructor.
     * @param SettingService $settingService
     * @param RetailTransportMgHelper $retailTransportMgHelper
     */
    public function __construct(SettingService $settingService, RetailTransportMgHelper $retailTransportMgHelper)
    {
        $this->settingService = $settingService;
        $this->retailTransportMgHelper = $retailTransportMgHelper;
    }

    /**
     * Выставляем ссылку ("url") веб-хука, на которую Wazzup будет присылать различную информацию
     * (Новые сообщения, статусы сообщений, информация об изменении каналов)
     * @param $setting
     */
    public function putUrlWebHook($setting)
    {
        Yii::$app->wazzup->putUrlWebHook($setting);
    }

    /**
     * Обрабатываем сообщение из Wazzup
     * Сверяем статус ("status") сообщения
     * @param $wazzupMessages
     */
    public function handleMessageFromWazzup($wazzupMessages, $existSetting)
    {
        foreach ($wazzupMessages as $message) {
            if ($message['status'] === 99) {
                $this->sentMessageToRetailCrm($message, $existSetting);
            }
        }
    }

    /**
     * Посылаем сообщение из Wazzup в RetailCRM
     * @param $message
     * @param $existSetting
     */
    public function sentMessageToRetailCrm($message, $existSetting)
    {
        $needChannelId = $this->settingService->getChannelDataByChannelId($message['channelId'], $existSetting);
        if ($needChannelId !== null) {
            $body =  $this->retailTransportMgHelper->generateMessage($message, $needChannelId, $existSetting);
            Yii::$app->transport->sentMessageToRetailCrm($existSetting, $body);
        }
    }

}
