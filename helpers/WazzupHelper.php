<?php

namespace app\helpers;

use Yii;

/**
 * Class WazzupHelper
 * @package app\helpers
 */
class WazzupHelper
{
    /**
     * @param array $data
     * @param array $retailMessage
     * @return array
     */
    public function generateMessage(array $data, array $retailMessage): array
    {
        $body =  [
            'channelId' => $data['channelId'],
            'chatType' => $data['chatType'],
            'chatId' => $retailMessage['data']['external_user_id'],
            'text' => $retailMessage['data']['content']
        ];

        //Yii::error($retailMessage, 'wazzup_telegram_log');

        if (isset($retailMessage['data']['quote_external_id'])) {
            $body['refMessageId'] = $retailMessage['data']['quote_external_id'];
        }

        //Yii::error($body, 'wazzup_telegram_log');

        return $body;
    }

    /**
     * @param array $data
     * @param array $retailMessage
     * @param string $imageUrl
     * @return array
     */
    public function generateMessageForImage(array $data, array $retailMessage, string $imageUrl): array
    {
        $body =  [
            'channelId' => $data['channelId'],
            'chatType' => $data['chatType'],
            'chatId' => $retailMessage['data']['external_user_id'],
            'content' => $imageUrl,
        ];

        return $body;
    }
}
