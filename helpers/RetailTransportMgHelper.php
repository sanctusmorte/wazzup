<?php

namespace app\helpers;

use Yii;

/**
 * Class RetailTransportMgHelper
 * @package app\helpers
 */
class RetailTransportMgHelper
{
    /**
     * @param $message
     * @param $channelId
     * @return array
     */
    public function generateMessage(array $message, $needChannelId, $existSetting): array
    {
        $body =  [
            'Message' => [
                'external_id' => $message['messageId'],
                'Type' => 'text',
                'Text' => $message['text']
            ],
            'User' => [
                'Firstname' => $message['authorName'],
                'external_id' => $message['chatId'],
                'nickname' => $message['authorName'],
            ],
            'Channel' => $needChannelId,
        ];

        // проверяем цитируется ли сообщение
        if (isset($message['refMessageId'])) {
            $body['Quote'] = [
                'external_id' => $message['refMessageId']
            ];
        }

        if (isset($message['content'])) {
            $uploadFile = json_decode(Yii::$app->transport->uploadFileByUrl($existSetting, ['url' => $message['content']]), 1);
            if (isset($uploadFile['id'])) {
                $body['Message']['Type'] = 'image';
                $body['Message']['items'] = [
                    0 => [
                        'id' => $uploadFile['id']
                    ],
                ];
            }
        }

        return $body;
    }
}
