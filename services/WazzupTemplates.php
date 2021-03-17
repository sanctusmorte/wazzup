<?php


namespace app\services;


class WazzupTemplates
{
    private function getAllTemplates()
    {
        return [
            // https://epsompro.retailcrm.ru
            'UQBH3_QXEUhV630tl7JvZUpbFbRiqsxm' => [
                'clientId' => 'UQBH3_QXEUhV630tl7JvZUpbFbRiqsxm',
                'templates_active' => true,
                'templates' => [
                    0 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'otkaz_cena_dostavki',
                            "Name" => '🙀 Отказ цена доставки',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Понимаю, стоимость доставки получается дороже, чем Вы ожидали 😕, но мне разрешили сделать Вам персональную скидку 3% на первый заказ. 😎 В этом случае, итоговая сумма может значительно снизится 👍. Готовы оформить заказ?"
                            ]
                        ]
                    ],
                    1 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'DOP_5_shchetki_dlya_tela_dlya_devushek',
                            "Name" => 'ДОП 5 щетки для тела (для девушек)',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "💌 Также хотим предложить дополнить Ваш заказ  - натуральной щеткой для тела. 🦵 Натуральная щетка для тела — это незаменимая вещь в арсенале любой девушки. Сухое растирание не только помогает избавиться от лишних жировых отложений, но и активизирует кровообращение в глубоких слоях кожи. 💃",
                                "\n",
                                "\n",
                                "https://epsom.pro/market/naturalnye-shchetki/",
                                "\n",
                                "\n",
                                "Добавим в заказ? 🙂"
                            ]
                        ]
                    ],
                    2 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Dop_2_skrab',
                            "Name" => 'Доп 2 Скраб',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "💌Также хотим предложить Вам  добавить в заказ скраб с натуральным кофе.",
                                "\n",
                                "☕Бодрящий кофейный коктейль в вашей ванной — уникальный скраб с тонизирующим эффектом. Натуральный молотый кофе стимулирует циркуляцию крови, а эфирные масла виноградной косточки и апельсина питают клетки и стимулируют синтез коллагена. Сейчас как раз на скраб у нас действует скидка 15%😍.",
                                "\n",
                                "\n",
                                "https://epsom.pro/market/solevye_skraby/salt-scrub-coffee-cocktail/"
                            ]
                        ]
                    ],
                    3 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Dop_1_sol',
                            "Name" => 'ДОП 1 Соль',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "💌Также хотим предложить добавить в заказ наш главный продукт - Английская соль. 💎 Эпсомская соль пищевой степени очистки на 99% состоит из магнезии и предназначена для расслабляющих, антицеллюлитных, омолаживающих ванн. 🤳Сейчас у нас действует скидка  до 21% на  английскую соль!🙀. ",
                                "\n",
                                "\n",
                                "🎁 https://epsom.pro/market/anglijskaya_sol/",
                                "\n",
                                "\n",
                                "Добавим в заказ?🙂"
                            ]
                        ]
                    ],
                    4 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Dop_3_sol_s_efirnym_maslom',
                            "Name" => 'ДОП 3 Соль с эфирным маслом',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "💌 Также хотим предложить Вам добавить в заказ - лидера продаж нашего магазина. Соль с цитрусовыми маслами «Slim Citrus» 🍊🍋.",
                                "\n",
                                "Попробуйте, и вы влюбитесь в это средство навсегда😍, ",
                                "🎁 https://epsom.pro/market/anglijskaya_sol/",
                                "\n",
                                "Натуральная соль с цитрусовыми эфирными маслами тонизирует и заряжает энергией на целый день. Бодрящий коктейль из сочного апельсина и спелого лимона поднимет настроение, восстановит силы и даст мотивацию для занятий спортом.🙀Сейчас действует уникальное предложение, скидка 17%. ",
                            ]
                        ]
                    ],
                    5 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Vajldberis',
                            "Name" => 'Вайлдберис',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Здравствуйте! Спасибо большое за отзыв, мы очень рады, что продукция понравилась🙌 Для следующего заказа на нашем сайте https://epsom.pro/  вы можете ввести промокод \"Вайлдбериз\" и получить скидку 300 руб. на заказ. Скидка будет действовать даже на акционные товары🤗",
                            ]
                        ]
                    ],
                    6 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'U_menya_sertifikat',
                            "Name" => 'У меня сертификат',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Здравствуйте! Мы очень рады, что вы написали нам 😊 Пройти тест, получить рецепты и скидку вы можете по ссылке https://epsom.pro/akcii/sertifikat-epsom-pro/",
                            ]
                        ]
                    ],
                    7 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Zaderzhka_dostavki',
                            "Name" => 'Задержка доставки',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "В связи с большим количеством заявок во время проведения распродажи, срок доставки может быть увеличен на 1-2 дня. Приносим свои извинения за временные неудобства 🙏",
                            ]
                        ]
                    ],
                    8 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Zaderzhka_obrabotki_zakazov',
                            "Name" => 'Задержка обработки заказов',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Здравствуйте!",
                                "\n",
                                "\n",
                                "Благодарим Вас за заказ товаров в магазине Epsom.pro!",
                                "\n",
                                "\n",
                                "В связи с большим количеством заявок во время проведения распродажи, срок обработки заказов может составлять до 2-х дней. Приносим свои извинения за временные неудобства.",
                                "\n",
                                "\n",
                                "Не волнуйтесь, мы обязательно с Вами свяжемся!💙"
                            ]
                        ]
                    ],
                    9 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'DOP_Bombochki',
                            "Name" => 'ДОП Бомбочки',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "💌Также хотим предложить Вам must have нашего магазина \"Набор из 3 бомбочек для ванн\"💣🎉🎊. Попробуйте и насладитесь ароматом натуральных масел и ингредиентов входящих в состав наших бомбочек. 🎀Набор упакован в подарочную упаковку и станет прекрасным подарком для вас или ваших близких. 🧺. Сейчас на набор у нас действует дополнительная скидка 17%.🙀",
                                "\n",
                                "\n",
                                "https://epsom.pro/market/bombochki_dlya_vannoy_windsor/",
                                "\n",
                                "\n",
                                "Добавим в заказ?",
                            ]
                        ]
                    ],
                    10 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'DOP_3_Magn_maslo',
                            "Name" => 'ДОП 3 Магн.масло',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "💌Также хотим предложить Вам must have нашего магазина - магниевое масло🛀 Попробуйте, и вы влюбитесь в это средство навсегда, оно с лёгкостью поможет вам убрать мышечную и суставную боль, восполнить дефицит магния в организме, а также улучшит состояние кожи и волос.",
                                "\n",
                                "\n",
                                "https://epsom.pro/market/magnievoe-maslo/magnievoe-maslo-magnesium-oil-obem-200-ml/",
                                "\n",
                                "\n",
                                "Добавим в заказ?🙂",
                            ]
                        ]
                    ],
                    11 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Spasibo_samovyvoz',
                            "Name" => '/Спасибо самовывоз',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Оформляем в доставку, итоговая стоимость 00000 руб.  Вам придет SMS-сообщение, когда можно будет забирать заказ. Срок хранения 3 дня. Если нужно продлить, пишите нам)) Спасибо за заказ🌸 Ждем вас снова!",
                            ]
                        ]
                    ],
                    12 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Spasibo_SDEK_Pochta_RF',
                            "Name" => '/Спасибо СДЭК/Почта РФ',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Оформляем в доставку, итоговая стоимость 00000 руб.  Отправим трек-номер отслеживания, когда присвоится к посылке.  Спасибо за заказ🌸 Ждем вас снова!",
                            ]
                        ]
                    ],
                    13 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Sostav_proizvoditel',
                            "Name" => '/Состав, производитель',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Состав английской соли для ванн Salt Of The Earth – 99,5% чистый сульфат магния. Степень очистки Ч - чистая, пищевая. Продукция сертифицирована - документы по ссылке https://yadi.sk/d/w5MQqH6jeEUDrQ.Английская соль Salt of the Earth производится в России на хим. заводе, что позволяет добиться максимальной чистоты состава и соответствие ТУ и ГОСТ. Подходит для детских ванн, для флоатинга, применяется в косметологии и пользуется спросом в спортивных и оздоровительных организациях.",
                            ]
                        ]
                    ],
                    14 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Dozirovka',
                            "Name" => '/Дозировка',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "▶ 100-300 г будет достаточно на ванну для ребенка. Рекомендации педиатров по продолжительности - около 10 минут при температуре не выше 36°С.",
                                "\n",
                                "▶ Для взрослых 300-500 г,  температура 37-38°С и продолжительность 15-20 минут. 500 г и выше - это худеющим (особенно, кто проходит курс массажа), спортсменам, танцорам и т.п.",
                                "\n",
                                "\n",
                                "☝ Это наша рекомендация. Вы можете использовать столько соли, сколько захотите. Кому-то этого магния нужно больше, кому-то хватает и 200 г."
                            ]
                        ]
                    ],
                    15 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Oformlenie_zakaza',
                            "Name" => '/Оформление заказа',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Для оформления заказа напишите: ",
                                "\n",
                                "- имя",
                                "\n",
                                "- номер телефона",
                                "\n",
                                "- e-mail (для информирования о статусе заказа)",
                                "\n",
                                "- адрес доставки",
                                "\n",
                                "- какую соль и в каком весе выбрали",
                            ]
                        ]
                    ],
                    16 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Gotov_k_vidache',
                            "Name" => '/Готов к выдаче',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Добрый день 😊",
                                "\n",
                                "Ваш заказ готов к выдаче. Подскажите, когда удобно будет забрать?",
                                "\n",
                                "С уважением, интернет-магазин Epsom.pro",
                            ]
                        ]
                    ],
                    17 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Trek_SDEK',
                            "Name" => '/Трек СДЭК',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Здравствуйте 😊",
                                "\n",
                                "\n",
                                "Ваш заказ от интернет-магазина Epsom.pro в пути. ",
                                "\n",
                                "\n",
                                "Отследить статус доставки можно на сайте https://www.cdek.ru/ru/tracking трек-номер Вашего заказа 00000000",
                            ]
                        ]
                    ],
                    18 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Trek_Pochta_Rossii',
                            "Name" => '/Трек Почта России',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Здравствуйте 😊",
                                "\n",
                                "\n",
                                "Ваш заказ от интернет-магазина Epsom.pro в пути. ",
                                "\n",
                                "\n",
                                "Отследить статус доставки можно на сайте  https://www.pochta.ru/tracking трек-номер Вашего заказа 00000000",
                            ]
                        ]
                    ],
                    19 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'PVZ_Piter',
                            "Name" => '/ПВЗ Питер',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "г.Санкт-Петербург, Люботинский проспект, 5",
                                "\n",
                                "(м. Электросила, 20 мин. пешком).",
                                "\n",
                                "Режим работы пункта самовывоза",
                                "\n",
                                "Пн-Пт: 10:00 — 20:00",
                                "\n",
                                "Сб-Вс: 10:00 — 18:00(проход через БЦ закрыт - только через шлагбаум)",
                                "\n",
                                "Тел: +7 (812) 407-15-78",
                                "\n",
                                "Пункт выдачи Доставка Клаб.",
                                "\n",
                                "Зайти за шлагбаум, налево до конца.",
                            ]
                        ]
                    ],
                    20 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'PVZ_Marina_roshcha',
                            "Name" => '/ПВЗ Марьина роща',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "г.Москва, м. Марьина роща, 2-ой Стрелецкий проезд д. 10",
                                "\n",
                                "\n",
                                "Вам придет SMS-сообщение, когда заказ можно забирать. Срок хранения заказа 3 дня. Если нужно будет продлить, напишите нам))",
                                "\n",
                                "\n",
                                "От ст. м. Марьина Роща ( выход №1 ) пройдите в сторону ТЦ Райкин Плаз а до светофора 110 м. , далее перейдите через ул. Шереметьевскую и по ул. 3-ий проезд Марьиной Рощи пройдите примерно 600 м. до пересечения с ул. 2-ой Стрелецкий проезд, поверните направо и пройдите еще 150 м. В здании жилого дома перед магазином \"Пятерочка\" со стороны улицы будет крыльцо (на входе табличка \"DOSTAVKA-CLUB\") - по коридору направо наш пункт выдачи заказов.",
                                "\n",
                                "\n",
                                "Режим работы:",
                                "\n",
                                "Пн-Пт: 10:00 — 20:00",
                                "\n",
                                "Сб-Вс: 10:00 — 18:00",
                                "\n",
                                "Тел: +7 (495) 150-07-18",
                            ]
                        ]
                    ],
                    21 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'PVZ_Paveleckaya',
                            "Name" => '/ПВЗ Павелецкая',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "м. Павелецкая, 6-й Монетчиковский переулок 8, с1.",
                                "\n",
                                "(ст. м. Павелецкая радиальная, выход 2 и 5 минут пешком, 430 м)",
                                "\n",
                                "\n",
                                "Вам придет SMS-сообщение, когда заказ можно забирать. Срок хранения заказа 3 дня. Если нужно будет продлить, напишите нам))",
                                "\n",
                                "\n",
                                "Режим работы:",
                                "\n",
                                "Пн-Пт: 10:00 — 20:00",
                                "\n",
                                "Сб-Вс: 10:00 — 18:00",
                                "\n",
                                "Тел: +7 (495) 150-07-18",
                            ]
                        ]
                    ],
                    22 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'PVZ_Kozhuhovkaya',
                            "Name" => '/ПВЗ Кожуховкая',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Заказ  готов к выдаче сегодня. Склад по адресу: г. Москва, ул. Южнопортовая, 7, строение 2 ",
                                "\n",
                                "\n",
                                "*Как пройти*: ст. м. Кожуховская. Пешком 500 метров. Выход в город №2. Перейти на другую сторону дороги по переходу. И идти далее прямо по улице Южнопортовая до зданий 7с25 и 7с9 между ними проход на территорию. ",
                                "\n",
                                "*Как доехать*: Съезд с третьего кольца в сторону Южнопортовой улицы. Для проезда на территорию на пункте охраны нужно сказать кодовое слово:❗ «ЗАПОЛНЯЕМ!!!!!!»❗. На территорию можно заехать на авто бесплатно. Есть парковка. ",
                                "\n",
                                "Ссылка на карту: https://yandex.ru/maps/-/CBVAFFHZlA",
                                "\n",
                                "\n",
                                "*На проходной сказать*: «В компанию Интеграл». После проходной увидите здание с надписью ",
                                "\n",
                                "\"Шинсервис\", идти/ехать нужно вдоль здания, до конца. Далее будет здание с надписью \"Мастер SHINA\", там нужно повернуть направо, наше здание будет по правой стороне. На двери есть вывеска \"Интеграл\".",
                                "\n",
                                "\n",
                                "Часы работы склада: *понедельник-воскресенье с 09:00-20:00*, без выходных и перерыва",
                                "\n",
                                "Срок хранения - 7 дней Если будете не успевать - сообщите, продлим.",
                                "\n",
                                "+7 (495) 150-13-31 номер склада - они подскажут, как пройти и найти их",
                            ]
                        ]
                    ],
                    23 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Ssylka_na_oplatu',
                            "Name" => '/Ссылка на оплату ',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Отправляю ссылку на оплату .............................................",
                                "\n",
                                "Напишите, пожалуйста, как оплатите, чтобы мы могли передать заказ в доставку.",
                            ]
                        ]
                    ],
                    24 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Ssylka_na_oplatu',
                            "Name" => '/Ссылка на оплату',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Общая сумма к оплате 0000 руб",
                                "\n",
                                "\n",
                                "Оплата - способы:",
                                "\n",
                                "• ссылка для оплаты картой онлайн",
                                "\n",
                                "• картой при получении",
                                "\n",
                                "• наличными при получении",
                                "\n",
                                "Какой способ выбираете?"
                            ]
                        ]
                    ],
                    25 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Intervaly_Moskva_Spb',
                            "Name" => '/Интервалы Москва Спб',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Возможные интервалы для доставки курьером:",
                                "\n",
                                "Понедельник-пятница:",
                                "\n",
                                "• 10:00-14:00",
                                "\n",
                                "• 14:00-18:00",
                                "\n",
                                "• 18:00-22:00",
                                "\n",
                                "Суббота, воскресенье:",
                                "\n",
                                "• 10:00-14:00",
                                "\n",
                                "• 14:00-18:00",
                                "\n",
                                "Какой вариант будет удобным?",
                            ]
                        ]
                    ],
                    26 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Nedozvon',
                            "Name" => '/Недозвон',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Здравствуйте!",
                                "\n",
                                "Меня зовут ....... , интернет-магазин Epsom.pro.",
                                "\n",
                                "Не получилось дозвониться до вас по номеру 000000000 для подтверждения заказа.",
                                "\n",
                                "Когда лучше перезвонить?",
                            ]
                        ]
                    ],
                    27 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Spasibo_kurier',
                            "Name" => '/Спасибо курьер',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Оформляем в доставку, итоговая стоимость 00000 руб. Курьер позвонит в день доставки. Спасибо за заказ🌸 Ждем вас снова!",
                            ]
                        ]
                    ],
                    28 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'PVZ_Moskva_VSE',
                            "Name" => '/ПВЗ Москва ВСЕ',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Бесплатные пункты самовывоза : ",
                                "\n",
                                "1) м. Кожуховская",
                                "\n",
                                "Ул. Южнопортовая 7 с2",
                                "\n",
                                "\n",
                                "2) м. Павелецкая",
                                "\n",
                                "6-ой Монетчиковский переулок 8с1",
                                "\n",
                                "\n",
                                "3) м. Марьина роща",
                                "\n",
                                "2-ой Стрелецкий проезд д. 10",
                            ]
                        ]
                    ],
                    29 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Ne_zvonit_regiony',
                            "Name" => '/Не звонить регионы',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "(ИМЯ КЛИЕНТА), здравствуйте!",
                                "\n",
                                "Вас приветствует интернет-магазин Epsom.pro🛀 Меня зовут (ИМЯ СОТРУДНИКА).",
                                "\n",
                                "Вы сделали заказ в нашем магазине, хочу обсудить доставку.",
                                "\n",
                                "\n",
                                "Стоимость и варианты доставки в ваш город:",
                                "\n",
                                "СДЭК курьер - 000 руб.",
                                "\n",
                                "СДЭК самовывоз - 000 руб.",
                                "\n",
                                "Почта России - 000 руб. ",
                                "\n",
                                "\n",
                                "Пункты самовывоза СДЭК:",
                                "\n",
                                "\n",
                                "Какой способ доставки удобнее?",
                            ]
                        ]
                    ],
                    30 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'Ne_zvonit_Moskva_Piter_2',
                            "Name" => '/Не звонить Москва/Питер',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                json_decode('{"var" : "first_name"}', 1),
                                ", здравствуйте!",
                                "\n",
                                "Вас приветствует интернет-магазин Epsom.pro🛀 Меня зовут ",
                                json_decode('{"var" : "custom"}', 1),
                                "\n",
                                "Вы сделали заказ в нашем магазине, хочу обсудить доставку.",
                                "\n",
                                "Вы сделали заказ на нашем сайте, хочу обсудить доставку. Вам удобно будет забрать в бесплатном пункте самовывоза или курьера отправить? Стоимость доставки курьером ",
                                json_decode('{"var" : "custom"}', 1),
                                " руб."
                            ]
                        ]
                    ],
                ]
            ],
            // тестово на локалке
            'LefGAK37KO9iB3290r3nXxZYkyRdaDue' => [
                'clientId' => 'LefGAK37KO9iB3290r3nXxZYkyRdaDue',
                'templates_active' => true,
                'templates' => [
                    30 => [
                        "channelId" => 12,
                        "templateInfo" => [
                            "Code" => 'Ne_zvonit_Moskva_Piter_2',
                            "Name" => '/Не звонить Москва/Питер',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                json_decode('{"var" : "first_name"}', 1),
                                ", здравствуйте!",
                                "\n",
                                "Вас приветствует интернет-магазин Epsom.pro🛀 Меня зовут ",
                                json_decode('{"var" : "custom"}', 1),
                                "\n",
                                "Вы сделали заказ в нашем магазине, хочу обсудить доставку.",
                                "\n",
                                "Вы сделали заказ на нашем сайте, хочу обсудить доставку. Вам удобно будет забрать в бесплатном пункте самовывоза или курьера отправить? Стоимость доставки курьером ",
                                json_decode('{"var" : "custom"}', 1),
                                " руб."
                            ]
                        ]
                    ],
                ]
            ],
        ];
    }

    /**
     * @param $clientId
     * @return array
     */
    public function getTemplatesByClientId($clientId): array
    {
        $needTemplates = [];

        $allTemplates = $this->getAllTemplates();
        if (isset($allTemplates[trim($clientId)])) {
            if ($allTemplates[trim($clientId)]['templates_active'] === true) {
                $needTemplates =  $allTemplates[trim($clientId)]['templates'];
            }
        }

        return $needTemplates;
    }
}
