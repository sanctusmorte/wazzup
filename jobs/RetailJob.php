<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.03.2021
 * Time: 13:24
 */

namespace app\jobs;

use app\services\RetailTransportMgService;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class RetailJob extends BaseObject implements JobInterface
{
    public $setting;
    public $message;
    public $retailTransportMgService;

    public function __construct(array $config = [])
    {

        parent::__construct($config);
    }

    public function execute($queue)
    {
        $this->retailTransportMgService->handleMessageFromRetail($this->message, $this->setting);
    }
}
