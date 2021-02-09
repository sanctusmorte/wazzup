<?php 
    $this->title = 'Настройки';

    use yii\bootstrap\ActiveForm;
    use yii\helpers\{
        Url,
        Html
    };
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit"></i>
            Панель управления
        </h3>
    </div>
    <div class="card-body">

        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'method' => 'POST',
            'action' => Url::to(['/setting/save']),
            'options' => [
                'id' => 'module-form',
            ],
        ]); ?>

            <div class="tab-content">
                <div class="tab-pane active" id="module" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $this->render('_module-form', [
                                'setting' => $setting,
                                'form' => $form
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <?php if($setting->isNewRecord):?>
                    <?= Html::submitButton('<i class="far fa-save"></i> Сохранить', ['class' => 'btn btn-success pull-right btn-submit']) ?>
                <?php else:?>
                    <?= Html::submitButton('<i class="fas fa-sync"></i> Обновить настройки', ['class' => 'btn btn-primary pull-right btn-submit']) ?>
                <?php endif;?>
            </div>

        <?php ActiveForm::end(); ?>
        <?php $this->registerJs("
            $(document).ready(function(){
                var form = $('#module-form');

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

<div class="setting-index">
    
   

</div>