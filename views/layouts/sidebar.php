<?php
use yii\helpers\{
    Html,
    Url
};
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?= Html::a("<img src='https://wazzup24.com/wp-content/uploads/2020/03/logo.svg' class=\"fas fa-truck-moving\">", [Url::to(['/'])], ['class' => 'brand-link']); ?>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <?= Html::a('<i class="fas fa-cogs"></i> <p>Настройки</p>', Url::to(['/setting/index']), ['class' => 'nav-link'])?>
                </li>
            </ul>
        </nav>
    </div>
</aside>
