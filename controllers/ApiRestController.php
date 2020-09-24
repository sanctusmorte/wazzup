<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;

class ApiRestController extends Controller  
{

    public function init() 
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function behaviors() 
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \app\models\CorsCustom::className()
            ]
        ]);

        return $behaviors;
    }
}