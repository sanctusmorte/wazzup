<?php

namespace app\helpers;

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
    public function generateMessage($message, $channelId): array
    {
        $data =  [
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
            'Channel' => $channelId,
        ];

        // проверяем цитируется ли сообщение
        if (isset($message['refMessageId'])) {
            $data['Quote'] = [
                'external_id' => $message['refMessageId']
            ];
        }

        return $data;
    }
}
