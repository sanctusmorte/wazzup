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

        return $body;
    }
}