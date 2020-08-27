<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AdminLteAsset extends AssetBundle 
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/fontawesome-free/css/all.min.css',
        'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'plugins/glyphicon-v1.0/style.css',
        'plugins/select2/css/select2.min.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.css',
        'plugins/jqvmap/jqvmap.min.css',
        'plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
        'plugins/daterangepicker/daterangepicker.css',
        'plugins/summernote/summernote-bs4.css',
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700',
        'dist/css/adminlte.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.0.3/nouislider.min.css',
        'css/site.css'
    ];
    public $js = [
        'plugins/bootstrap/js/bootstrap.bundle.min.js',
        'plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'plugins/select2/js/select2.full.min.js',
        'dist/js/adminlte.js',
        'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.0.3/nouislider.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js',
        'js/app.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
