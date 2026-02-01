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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],



        'make' => [
        'webhook_url' => env('MAKE_WEBHOOK_URL', null),
        'webhook_url_property' => env('MAKE_WEBHOOK_URL_PROPERTY', null),
        'webhook_url_account' => env('MAKE_WEBHOOK_URL_ACCOUNT', null),
        'webhook_url_customer' => env('MAKE_WEBHOOK_URL_CUSTOMER'),
        'webhook_url_investor' => env('MAKE_WEBHOOK_URL_INVESTOR'),
        'webhook_url_qb_sales_receipt' => env('MAKE_SALES_RECEIPT', null),
        'webhook_url_property_fetch' => env('MAKE_WEBHOOK_URL_PROPERTY_FETCH'),
        'webhook_url_inventory_fetch' => env('MAKE_WEBHOOK_URL_INVENTORY_FETCH'),
        'webhook_url_kyc' => env('MAKE_WEBHOOK_URL_KYC'),
        'webhook_url_payments' => env('MAKE_WEBHOOK_URL_PAYMENTS'),
        'webhook_url_qb_rent_payment' => env('MAKE_WEBHOOK_RENT_URL_PAYMENTS_PAYMENTS'),
    ],


];
