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


class TemplateCreatelJob extends BaseObject implements JobInterface
{
    public $setting;
    public $existTemplate;

    public function __construct($setting, $existTemplate, array $config = [])
    {
        $this->setting = $setting;
        $this->existTemplate = $existTemplate;
        parent::__construct($config);
    }

    public function execute($queue)
    {
        Yii::$app->transport->createTemplateInRetailCrm($this->setting, $this->existTemplate);
    }
}
