<?php


namespace app\services;

use app\models\Setting;
use Yii;

class RetailService
{

    public function handleMessageFromRetail($retailMessage)
    {
        if ($retailMessage['status'] === 99) {
            $this->sentMessageToRetailCrm($message);
        }
    }
}
