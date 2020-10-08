<?php 
    use yii\helpers\{
        Html,
        Url,
        ArrayHelper
    };

    $arrayRetailToLogsisStatus = ArrayHelper::map($setting->retailToLogsisStatus, 'logsis_status_id', 'order_status_id');
?>

<div class="card">
    <div class="card-header">
        <div class="card-header__beetwen">
            <h3 class="card-title">Настройки статусов</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <table class="table table-bordered table-hover dataTable" role="grid" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Статус в Logsis</th>
                            <th>Статус в retailCRM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Новый заказ</td>
                            <td width="50%">
                                <?= $form->field($setting, 'order_statuses[1]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[1] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Подтвержден</td>
                            <td width="50%">
                                <?= $form->field($setting, 'order_statuses[2]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[2] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>На складе</td>
                            <td width="50%">
                                <?= $form->field($setting, 'order_statuses[3]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[3] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>На доставке</td>
                            <td width="50%">
                                <?= $form->field($setting, 'order_statuses[4]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[4] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Доставлен</td>
                            <td>
                                <?= $form->field($setting, 'order_statuses[5]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' =>$arrayRetailToLogsisStatus[5] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Частичный отказ</td>
                            <td>
                                <?= $form->field($setting, 'order_statuses[6]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[6] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Полный отказ</td>
                            <td>
                                <?= $form->field($setting, 'order_statuses[7]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[7] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Отмена</td>
                            <td>
                                <?= $form->field($setting, 'order_statuses[8]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[8] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>На пути в город доставки</td>
                            <td>
                                <?= $form->field($setting, 'order_statuses[9]', [
                                    'template' => "<div class=\"form-group\">
                                        {label}
                                        {input}
                                    </div>",
                                ])->dropDownList($setting->getArrayOrderStatuses(), ['prompt' => 'Выберите статус..', 'value' => $arrayRetailToLogsisStatus[9] ?? null, 'class' => 'form-control select2', 'multiple' => false])->label(false); ?>
                            </td>
                        </tr>             
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>