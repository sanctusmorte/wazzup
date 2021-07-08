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
use yii\queue\Job;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;


class WazzupJob extends BaseObject implements JobInterface
{
    public $setting;
    public $messages;

    public function __construct($setting, $messages, array $config = [])
    {
        $this->setting = $setting;
        $this->messages = $messages;
        parent::__construct($config);
    }

    public function execute($queue)
    {
        Yii::info($this->messages, __CLASS__);
        Yii::$app->wazzupServiceComponent->handleMessageFromWazzup($this->messages, $this->setting);
    }
}
