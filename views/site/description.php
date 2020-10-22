<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Интеграция Logsis и retailCRM';

?>
<div class="description-index">

    <div class="body-content" style="font-size:18px">


            <div class="col-lg-10 col-md-10 col-md-offset-1" style="margin-top: 3%;margin-bottom: 3%;">
                <h2><?= $this->title ?></h2>

                <p>Модуль интеграции RBK.money с retailCRM.</p>
                <h3>Подробная инструкция по установке и активации</h3>

                <ul>
                    <li>Иметь свой личный кабинет в <a target="_blank" href="http://cab.logsis.ru">Logsis</a>.</li>
                    <li>
                        <p>Создать свой API ключ Logsis. Для этого перейти по <a target="_blank" href="http://cab.logsis.ru/edit-profile-dash">ссылке</a>, потом  перейти на вкладку Профиль</p>
                        
                        <img width="100%" src="/img/image5.png" alt="">
                        <p>Скопировать API ключ</p>

                        <img width="100%" src="/img/image6.png" alt="">
                    </li>
                    <li>
                        <p>Потом перейти в retailCRM  модуль Logsis там начать на кнопку “перейти в личный кабинет”.</p>
                        <img width="100%" src="/img/image8.png" alt="">
                    </li>
                    <li>
                        <p>Сгенерировать API-ключ в retailCRM для этого перейти в раздел Администрирование > Интеграция > Ключи доступа к API и создать API-ключ.  (в создаваемом API-ключе, в поле «Разрешенные методы API», должны быть активированы методы «Доставки» и «Интеграция».)</p>
                        <img width="100%" src="/img/image1.png" alt="">
                        <p>После нажатия открываются настройки доставки, где необходимо ввести раннее созданный API-ключ, а также адрес Вашей системы и нажать на кнопку «Сохранить».</p>
                    </li>
                    <li>
                        <p>В личном кабинете Logsis ввести ссылку на ваш аккаунт retailCRM, API ключ retailCRM и API ключ Logsis.</p>
                        <img width="100%" src="/img/image2.png" alt="">
                        <p>Во вкладке “Магазин” ввести магазин который вы ввели в 2 пункте.</p>
                        <img width="100%" src="/img/image3.png" alt="">
                    </li>
                    <li>
                        <p>Во вкладке “общие настройки” установить нужные вам настройки.</p>
                        <img width="100%" src="/img/image7.png" alt="">
                    </li>
                    <li>
                        <p>Во вкладке “настройках статусов” настроить соотношение статусов в logsis и retailCRM.</p>
                        <img width="100%" src="/img/image4.png" alt="">
                    </li>
                    <li>Для проверки работы модуля мы создаем заказ в retailCRM выбираем тип доставки Logsis, обязательно при этом выбрать магазин, тот который был указан в API КЛЮЧЕ из 4 пункта, после этого нажимаем на кнопочку “выбрать тариф”, после выбора тарифа, сохраняем заказ и проверяем отобразился ли он в вашем личном кабинете в <a target="_blank" href="http://cab.logsis.ru/orders2/olist">Logsis.</a></li>
                </ul>

                <p>
                    О поддержке модуля Вы можете узнать в разделе <code>Поддержка</code> 
                    (<?= Html::a('перейти', ['/site/support']) ?>).
                </p>

            </div>
        </div>

    </div>
</div>
