<?php 
    use yii\helpers\{
        Html,
        Url
    };
?>

<div class="card">
    <div class="card-header">
        <div class="card-header__beetwen">
            <h3 class="card-title">Настройки модуля</h3>
        </div>
    </div>
    <div class="card-body">
        
        <p><b>Настройки retailCRM</b></p>

        <?= $form->field($setting, 'id')->hiddenInput()->label(false); ?>

        <?= $form->field($setting, 'client_id')->hiddenInput()->label(false); ?>

        <input type="hidden" name="submit" value="0"/>

        <?= $form->field($setting, 'retail_api_url', [
                'template' => "<div class=\"form-group\">
                                    {label}
                                    {input}
                                    {error}               
                                </div>",
        ])->textInput(['placeholder' => $setting->getAttributeLabel('retail_api_url')]); ?>

        <?= $form->field($setting, 'retail_api_key', [
                'template' => "<div class=\"form-group\">
                                {label}
                                {input}
                                {error}             
                            </div>",
        ])->textInput(['placeholder' => $setting->getAttributeLabel('retail_api_key')]); ?>

        <p><b>Настройки Logsis</b></p>

        <?= $form->field($setting, 'apikey', [
                'template' => "<div class=\"form-group\">
                                {label}
                                {input}
                                {error}             
                            </div>",
        ])->textInput(['placeholder' => $setting->getAttributeLabel('apikey')]); ?>
        
    </div>
</div>