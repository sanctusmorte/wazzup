<?php 
    use yii\helpers\{
        Html,
        Url
    };
    use yii\bootstrap\ActiveForm;
?>

<div class="card">
    <div class="card-header">
        <div class="card-header__beetwen">
            <h3 class="card-title">Настройки модуля</h3>
        </div>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'method' => 'POST',
            'action' => Url::to(['/setting/save']),
            'options' => [
                'id' => 'setting-form',
            ],
        ]); ?>

            <p><b>Настройки retailCRM</b></p>

        
        <?php ActiveForm::end(); ?>
        <?php $this->registerJs("
            $(document).ready(function(){
                var form = $('#setting-form');

                $(form).find('.btn-submit').on('click', function(){
                    $(form).find('[name=submit]').val(1);
                });

                $(form).on('ajaxComplete', function(){
                    $(form).find('[name=submit]').val(0);
                });

                form.on('afterValidate', function(){
                    if(!form.find('.has-error').length ){
                        $(form).find('[name=submit]').val(0);
                    }
                });
            });
        "); ?>
    </div>
</div>