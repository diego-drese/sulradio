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
			'username'      => 'de2dc7572f0fdb',
			'email'         => 'from@example.com',
			'pass'          => 'f9199ef5bf77e6',
			'from_name'     => 'from@example.com',
			'provider'      => 'mailtrap',
        ],
		'sulradio@outlook.com' => [
			'username'      => 'sulradio@outlook.com',
			'email'         => 'sulradio@outlook.com',
			'pass'          => '040816Srd',
			'from_name'     => 'Sead',
			'provider'      => 'outlook',
		],'sead.sulradio@outlook.com' => [
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
        ],'outlook' => [
            'host'      => 'smtp.office365.com',
            'port'      => '587',
            'encryption' => 'tls',
        ]
    ],

];
