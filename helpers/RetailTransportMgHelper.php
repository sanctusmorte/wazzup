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
    public function generateMessage(array $message, array $data): array
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
            'Channel' => $data['channelId'],
        ];

        // проверяем цитируется ли сообщение
        if (isset($message['refMessageId'])) {
            $body['Quote'] = [
                'external_id' => $message['refMessageId']
            ];
        }

        Yii::error($message['content'], 'wazzup_telegram_log');

//        if (isset($message['content'])) {
//            $uploadFile = json_decode(Yii::$app->transpot->uploadFileByUrl($data, ['url' => $message['content']]));
//            if (isset($uploadFile['id'])) {
//                $body['Message']['Type'] = 'image';
//                $body['Message']['items'] = [
//                    0 => [
//                        'id' => $uploadFile['id']
//                    ],
//                ];
//            }
//        }

        return $body;
    }
}
