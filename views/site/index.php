<?php 
    use \yii\helpers\{
        Html,
        Url
    };

    $this->title = Yii::$app->name;
?>

<div class="site-index">
    <?= Html::a('Перейти в настройки', Url::to(['/setting/index']), ['class' => 'btn btn-primary'])?>
</div>