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
            <h3 class="card-title">Общие настройки</h3>
        </div>
    </div>
    <div class="card-body">

        <div class="row">

            <div class="col-md-12">
                <h4>Настройки заявки в заказе по умолчанию.</h4>
            </div>

            <div class="col-md-6">
                <?= $form->field($setting, 'cost_delivery', [
                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                        {error}               
                                    </div>",
                ])->textInput(['placeholder' => $setting->getAttributeLabel('cost_delivery')]); ?>

                <?= $form->field($setting, 'prefix_shop', [
                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                        {error}               
                                    </div>",
                ])->textInput(['placeholder' => $setting->getAttributeLabel('prefix_shop')]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($setting, 'markup', [
                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                        {error}               
                                    </div>",
                ])->textInput(['placeholder' => $setting->getAttributeLabel('markup')]); ?>
            </div>
        </div>

        <div class="checkbox-block">
            <?= $form->field($setting, 'is_single_cost')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_partial_redemption')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_sms')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_open')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_additional_call')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_return_doc')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_assessed_value')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_skid')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>

            <?= $form->field($setting, 'is_payment_type')->checkbox([
                'template' => "<div class=\"icheck-primary d-inline\">
                    {input}
                    {label}
                </div>",
                'value' => 1,
            ]); ?>
        </div>
    </div>
</div>