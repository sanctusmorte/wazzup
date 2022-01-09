<?php


namespace app\services;


class WazzupTemplates
{
    private function getAllTemplates()
    {
        return [
            // тестово на локалке
            '999' => [
                'clientId' => 'taZRdm-GodKTb41kkGxDvLYjj44BNSif',
                'templates_active' => true,
                'templates' => [
                    0 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'greetings',
                            "Name" => 'greetings',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Hi ",
                                json_decode('{"var" : "first_name"}', 1),
                                ",",
                                "\n",
                                "\n",
                                "This is TheMaxFlights",
                               // json_decode('{"var" : "custom"}', 1),
                                "\n",
                                "\n",
                                "We received your request and will be working on it.",
                                "\n",
                                "\n",
                                "How would it be more convenient for you to receive our offer? Via email or here?"
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

        $clientId = 'taZRdm-GodKTb41kkGxDvLYjj44BNSif';

        if (isset($allTemplates[999])) {
            if ($allTemplates[999]['templates_active'] === true) {
                $needTemplates =  $allTemplates[999]['templates'];
            }
        }

        return $needTemplates;
    }
}
