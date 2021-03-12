<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.03.2021
 * Time: 13:24
 */

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;


class RetailJob extends BaseObject implements RetryableJobInterface
{
    public $setting;
    public $message;

    public function __construct($setting, $message, array $config = [])
    {
        $this->setting = $setting;
        $this->message = $message;
        parent::__construct($config);
    }

    public function getTtr()
    {
        return 60;
    }

    public function canRetry($attempt, $error)
    {
        return ($attempt < 5) && ($error instanceof TemporaryException);
    }

    public function execute($queue)
    {
        $result = Yii::$app->retailTransportMgService->handleMessageFromRetail($this->message, $this->setting);
    }
}
