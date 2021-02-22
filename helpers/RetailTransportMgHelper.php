<?php

namespace app\helpers;


class RetailTransportMgHelper
{
    public function generateMessage($message, $channelId): array
    {
        return [
            'Message' => [
                'ExternalId' => $message['messageId'],
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
    }
}
