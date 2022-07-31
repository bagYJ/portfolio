<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'nice' => [
        'niceOtcMid' => env('NICE_OTC_MID'),
        'niceOtcPw' => env('NICE_OTC_PW'),
        'niceOtcKey' => env('NICE_OTC_KEY'),
        'licenseKey' => env('NICE_LICENSE_KEY'),
        'mid' => env('NICE_MID'),
        'cancelId' => env('NICE_CANCEL_ID'),
        'cancelPwd' => env('NICE_CANCEL_PWD'),
        'isSsl' => env('NICE_IS_SSL'),
        'logPath' => "/private_data/owinlog/nice",
    ],
    'fdk' => [
        'keyData' => env('FDK_KEY_DATA'),
        'keyDataBillKey' => env('FDK_KEY_DATA_BILL_KEY'),
        'mxId' => env('FDK_MX_ID'),
        'mxIdBillKey' => env('FDK_MX_ID_BILL_KEY'),
        'fdkTest' => env('FDK_TEST'),
        'sendHost' => env('FDK_SEND_HOST'),
        'authSendPath' => "/jsp/common/pay.jsp",
        'certSendPath' => "/jsp/common/req.jsp",
    ],
    'kcp' => [
        'kcpId' => env('KCP_ID'),
        'gConfGwUrl' => env('G_CONF_GW_URL'),
        'gConfSiteCd' => env('G_CONF_SITE_CD'),
        'gConfSiteKey' => env('G_CONF_SITE_KEY'),
        'gConfSiteName' => 'OWIN',
        'gConfLogPath' => "/private_data/owinlog/kcp",
        'gConfHomeDir' => '/var/www/owinapi/common/application/libraries',
        'limitCard' => array(
            '8122',
            '8123',
            '8124',
            '8186',
            '8188',
            '8885',
            'BTTD',
            'BTXC',
            'CC07',
            'CC08',
            'CC61',
            'CC62'
        ), //한도초과 코드
        'robberyCard' => array('BTR6', 'CC43', 'CC44', 'CC45'), //도난카드
    ],
    'uplus' => [
        'cstPlatform' => env('CST_PLATFORM'),
        'lgdMertKey' => env('LGD_MERTKEY'),
        'lgdMid' => env('LGD_MID'),
        'cstMid' => 'lgdacomxpay',
        'pCstPlatform' => env('PICK_CST_PLATFORM'),
        'pLgdMertKey' => env('PICK_LGD_MERTKEY'),
        'pLgdMid' => env('PICK_LGD_MID'),
        'pCstMid' => 'lgdacomxpay',
        'configPath' => '/var/www/owinapi/common/application/helpers/lgdacom',
        'oilConfigPath' => '/var/www/owinapi/common/application/helpers/lgdacom_oil',
        'authRul' => 'payreq_crossplatform',
        'customFirstPay' => 'SC0010',
    ],
];
