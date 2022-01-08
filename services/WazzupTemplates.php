<?php


namespace app\services;


class WazzupTemplates
{
    private function getAllTemplates()
    {
        return [
            // тестово на локалке
            'taZRdm-GodKTb41kkGxDvLYjj44BNSif' => [
                'clientId' => 'taZRdm-GodKTb41kkGxDvLYjj44BNSif',
                'templates_active' => true,
                'templates' => [
                    0 => [
                        "channelId" => 5,
                        "templateInfo" => [
                            "Code" => 'first_message',
                            "Name" => 'First message',
                            "Enabled" => true,
                            "Type" => "text",
                            "Template" => [
                                "Hi, ",
                                json_decode('{"var" : "first_name"}', 1),

                                "\n",
                                "This is ",
                                json_decode('{"var" : "custom"}', 1),
                                "from TheMaxFlights",
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
