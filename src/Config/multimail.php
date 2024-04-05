<?php

return [
    /*
    |--------------------------------------------------------------------------
    | List your email providers
    |--------------------------------------------------------------------------
    |
    | Enjoy a life with multimail
    |
    */
    'use_default_mail_facade_in_tests' => true,

    'emails'  => [
        'from@example.com' => [
			'username'      => 'cac88c3d066236',
			'email'         => 'from@example.com',
			'pass'          => '0efbd6d5bc128e',
			'from_name'     => 'from@example.com',
			'provider'      => 'mailtrap',
        ],

		'no-reply@sulradio.com.br' => [
			'username'      => 'MS_wk5FOi@sulradio.com.br',
			'email'         => 'no-reply@sulradio.com.br',
			'pass'          => 'OLXAquJiMULTWrcX',
			'from_name'     => 'Sead',
			'provider'      => 'mailersend',
		],

        'sulradio@outlook.com' => [
            'username'      => 'sulradio@outlook.com',
            'email'         => 'sulradio@outlook.com',
            'pass'          => '040816Srd',
            'from_name'     => 'Sead',
            'provider'      => 'outlook',
        ],

        'sead.sulradio@outlook.com' => [
            'username'      => 'sead.sulradio@outlook.com',
            'email'         => 'sead.sulradio@outlook.com',
            'pass'          => '040816Srd',
            'from_name'     => 'Sead',
            'provider'      => 'outlook',
        ],
    ],

    'provider' => [
        'mailtrap' => [
            'host'      => 'smtp.mailtrap.io',
            'port'      => '2525',
            'encryption' => 'tls',
        ],
        'outlook' => [
            'host'      => 'smtp.office365.com',
            'port'      => '587',
            'encryption' => 'tls',
        ],
        'mailersend' => [
            'host'      => 'smtp.mailersend.net',
            'port'      => '587',
            'encryption' => 'tls',
        ]
    ],

];
