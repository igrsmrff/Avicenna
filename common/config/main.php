<?php
return [
    'name' => 'Avicenna',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],

        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6LdeWCMTAAAAAGahbDctyKJRuoE9fOboP6RXbU3H',
            'secret' => '6LdeWCMTAAAAADyiWA1DqoYKo10_2Hl8UNsXRToe',
        ],

        'mailerr' => [
            'class' => '\YarCode\Yii2\Mailgun\Mailer',
            'domain' => 'api.mailgun.net/v3/sandboxdf7af0f5e80b4aada11f7a6212ef1ad9.mailgun.org/messages ',
            'apiKey' => 'api:key-0af3a1fbe3f4a9dabe3fc5000cb721e7',
        ],
    ],
];
