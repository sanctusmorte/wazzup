<?php
use yii\helpers\{
    Html,
    Url
};
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?= Html::a("<img src='https://wazzup24.com/wp-content/uploads/2020/03/logo.svg' class=\"fas fa-truck-moving\">", [Url::to(['/'])], ['class' => 'brand-link']); ?>

    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="d-flex" href="/setting/index">
                        <i style="font-size: 24px; min-width: 35px; text-align: right;" class="fas fa-cogs mr-4"></i>
                        <p class="m-0">Настройки</p>
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="d-flex" href="https://im-business.com/contact" target="_blank">
                        <i style="font-size: 24px; min-width: 35px; text-align: right;" class="fas fa-address-book mr-4"></i>
                        <p class="m-0">Контакты</p>
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="d-flex" href="https://im-business.com/marketplace/logisticheskaya-karta-dlya-retailcrm/" target="_blank">
                        <i style="font-size: 24px; min-width: 35px; text-align: right;" class="fas fa-file-alt mr-4"></i>
                        <p class="m-0">Описание</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
