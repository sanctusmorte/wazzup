<?php
namespace app\widgets\assets;

use yii\web\AssetBundle;

class ToastWidgetAsset extends AssetBundle {

    public $sourcePath = '@app/widgets/assets';

    public $css = [
        'plugins/toastr/toastr.min.css'
    ];
    public $js = [
        'plugins/toastr/toastr.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}