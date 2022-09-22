<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array"
    |
    */
    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective management. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array"
    |
    */
    'mailers' => [

        // used for Admin-Mails
        'admin' => [
            'transport'  => 'smtp',
            'host'       => env('ADMIN_MAIL_HOST'),
            'port'       => env('ADMIN_MAIL_PORT'),
            'encryption' => env('ADMIN_MAIL_ENCRYPTION'),
            'username'   => env('ADMIN_MAIL_USERNAME'),
            'password'   => env('ADMIN_MAIL_PASSWORD'),
            'timeout'    => null,
            'from' => [     // read by "mailer"
                'address' => 'noreply@example.com',
                'name'    => 'Example - Administration',
            ],
            'replyTo' => [  // read by "mailer"
                'address' => 'info@example.com',
                'name'    => 'Example Company',
            ],
            'to' => [       // custom app specific parameter
                'address' => 'admin@example.com',
                'name'    => 'Administatrion',
            ],
        ],

    ],

];
