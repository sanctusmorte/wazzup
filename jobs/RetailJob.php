<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.03.2021
 * Time: 13:24
 */

namespace app\jobs;

use Yii;


class RetailJob extends \yii\base\BaseObject implements \yii\queue\Job
{
    public $setting;
    public $message;

    public function __construct($setting, $message, array $config = [])
    {
        $this->setting = $setting;
        $this->message = $message;
        parent::__construct($config);
    }

    public function execute($queue)
    {

        Yii::$app->retailTransportMgService->handleMessageFromRetail($this->message, $this->setting);
    }
}
