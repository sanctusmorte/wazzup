<?php


namespace app\services;


class WazzupTemplates
{
    private function getAllTemplates()
    {
        return [
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
        var_dump($clientId);
        exit;

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
