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
                ],
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
