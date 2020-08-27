<?php 

namespace app\widgets;

use Yii;
use app\widgets\assets\ToastWidgetAsset;

class ToastWidget extends \yii\base\Widget {
    
    public function init() {
        ToastWidgetAsset::register( $this->getView() );

        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $message) {
            switch ($type) {
                case 'success':
                    $js = "toastr.success('$message')";
                    $session->removeFlash($type);
                    break;
                case 'error':
                    $js = "toastr.error('$message')";
                    $session->removeFlash($type);
                    break;
                case 'warning':
                    $js = "toastr.warning('$message')";
                    $session->removeFlash($type);
                    break;
                case 'info':
                    $js = "toastr.info('$message')";
                    $session->removeFlash($type);
                    break;
            }
        }

        if (isset($js)) {
            $this->getView()->registerJs($js);
        }
        parent::init();
    }
}

?>