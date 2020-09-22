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
            <h3 class="card-title">Настройки магазинов</h3>
        </div>
    </div>
    <div class="card-body">

        <?= $form->field($setting, 'shop_ids[]', [
            'template' => "<div class=\"form-group\">
                {label}
                {input}
            </div>",
        ])->dropDownList($setting->getArrayShops(), ['value' => $setting->getShopValues(), 'class' => 'form-control select2', 'multiple' => true]); ?>
        
    </div>
</div>