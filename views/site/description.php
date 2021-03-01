<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Интеграция Logsis и RetailCRM';

?>
<div class="description-index">
<div class="body-content" style="font-size: 18px;">
<div class="col-lg-10 col-md-10 col-md-offset-1" style="margin-top: 3%; margin-bottom: 3%;">
<h2>&nbsp;</h2>
<p>Модуль интеграции Logsis с RetailCRM.</p>
<h3>Подробная инструкция по установке и активации</h3>
<ul>
<li>1.Иметь свой личный кабинет в <a href="http://cab.logsis.ru" target="_blank" rel="noopener">Logsis</a>.</li>
<li>
<p>2.Создать свой API ключ Logsis. Для этого перейти по <a href="http://cab.logsis.ru/edit-profile-dash" target="_blank" rel="noopener">ссылке</a>, потом перейти на вкладку Профиль</p>
<img src="/img/image5.png" alt="" width="100%" />
<p>Скопировать API ключ</p>
<img src="/img/image6.png" alt="" width="100%" /></li>
<li>
<p>3.Потом перейти в RetailCRM модуль Logsis там начать на кнопку &ldquo;перейти в личный кабинет&rdquo;.</p>
<img src="/img/image8.png" alt="" width="100%" /></li>
<li>
<p>4.Сгенерировать API-ключ в RetailCRM для этого перейти в раздел Администрирование &gt; Интеграция &gt; Ключи доступа к API и создать API-ключ с разрешенными методами:</p>
<ul>
    <li>/api/integration-modules/{code}</li>
    <li>/api/integration-modules/{code}/edit</li>
    <li>/api/reference/sites</li>
    <li>/api/reference/payment-types</li>
    <li>/api/orders/statuses</li>
    <li>/api/reference/statuses</li>
    <li>/api/orders</li>
    <li>/api/orders/{externalId}</li>
    <li>/api/orders/{externalId}/edit</li>
</ul>
<br />
<p>После нажатия открываются настройки доставки, где необходимо ввести раннее созданный API-ключ, а также адрес Вашей системы и нажать на кнопку &laquo;Сохранить&raquo;.</p>
</li>
<li>
<p>5.В личном кабинете Logsis ввести ссылку на ваш аккаунт RetailCRM, API ключ RetailCRM и API ключ Logsis.</p>
<img src="/img/image2.png" alt="" width="100%" />
<p>Во вкладке &ldquo;Магазин&rdquo; ввести магазин который вы ввели в 4 пункте.</p>
<img src="/img/image3.png" alt="" width="100%" /></li>
<li>
<p>6.Во вкладке &ldquo;общие настройки&rdquo; установить нужные вам настройки.</p>
<img src="/img/image7.png" alt="" width="100%" /></li>
<li>
<p>Во вкладке &ldquo;настройках статусов&rdquo; настроить соотношение статусов в logsis и RetailCRM.</p>
<img src="/img/image4.png" alt="" width="100%" /></li>
<li>7. Для проверки работы модуля мы создаем заказ в RetailCRM выбираем тип доставки Logsis, обязательно при этом выбрать магазин, тот который был указан в API КЛЮЧЕ из 4 пункта, после этого нажимаем на кнопочку &ldquo;выбрать тариф&rdquo;, после выбора тарифа, сохраняем заказ и проверяем отобразился ли он в вашем личном кабинете в <a href="http://cab.logsis.ru/orders2/olist" target="_blank" rel="noopener">Logsis.</a></li>
</ul>
<p>О поддержке модуля Вы можете узнать в разделе <code>Поддержка</code> (<?= Html::a('перейти', ['/site/support']) ?>).</p>
</div>
</div>
</div>
