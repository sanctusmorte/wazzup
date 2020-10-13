<?php 
    use yii\helpers\{
        Html,
        Url,
        ArrayHelper
    };
?>

<div class="card">
    <div class="card-header">
        <div class="card-header__beetwen">
            <h3 class="card-title">Настройки способов оплат</h3>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <table class="table table-bordered table-hover dataTable" role="grid" >
                    <thead>
                        <tr>
                            <th>Тип оплаты</th>
                            <th>Разрешить использовать</th>
                            <th>Наложенным платежом</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $paymentTypesSetting = $setting->paymentTypesSetting; ?>
                        <?php if ($paymentTypes = $setting->paymentTypes):?>
                            <?php foreach ($paymentTypes as $paymentType):?>

                                <?php foreach ($paymentTypesSetting ?? [] as $paymentTypeSetting):?>
                                    <?php if ($paymentTypeSetting->payment_type_id == $paymentType->id):?>
                                        <?php 
                                            $setting->payment_types[$paymentType->id] = $paymentTypeSetting->active;
                                            $setting->payment_types_cod[$paymentType->id] = $paymentTypeSetting->cod;    
                                        ?>
                                    <?php endif;?>
                                <?php endforeach;?>

                                <tr>
                                    <td><?= $paymentType->name ?></td>
                                    <td>
                                        <?= $form->field($setting, "payment_types[".$paymentType->id."]")->checkbox([
                                            'template' => "<div class=\"icheck-primary d-inline\">
                                                {input}
                                                {label}
                                            </div>",
                                            'value' => 1,
                                        ])->label(false); ?>
                                    </td>
                                    <td>
                                        <?= $form->field($setting, "payment_types_cod[".$paymentType->id."]")->checkbox([
                                            'template' => "<div class=\"icheck-primary d-inline\">
                                                {input}
                                                {label}
                                            </div>",
                                            'value' => 1,
                                        ])->label(false); ?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>