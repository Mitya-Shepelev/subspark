<?php
global $base_url;
global $payPalPaymentMode;
global $payPalPaymentStatus;
global $payPalPaymentSedboxBusinessEmail;
global $payPalPaymentProductBusinessEmail;
global $payPalCurrency;

global $bitPayPaymentMode;
global $bitPayPaymentStatus;
global $bitPayPaymentNotificationEmail;
global $bitPayPaymentPassword;
global $bitPayPaymentPairingCode;
global $bitPayPaymentLabel;
global $bitPayPaymentCurrency;

global $stripePaymentMode;
global $stripePaymentStatus;
global $stripePaymentTestSecretKey;
global $stripePaymentTestPublicKey;
global $stripePaymentLiveSecretKey;
global $stripePaymentLivePublicKey;
global $stripePaymentCurrency;

global $autHorizePaymentMode;
global $autHorizePaymentStatus;
global $autHorizePaymentTestsApID;
global $autHorizePaymentTestTransitionKey;
global $autHorizePaymentLiveApID;
global $autHorizePaymentLiveTransitionkey;
global $autHorizePaymentCurrency;

global $iyziCoPaymentMode;
global $iyziCoPaymentStatus;
global $iyziCoPaymentTestSecretKey;
global $iyziCoPaymentTestApiKey;
global $iyziCoPaymentLiveApiKey;
global $iyziCoPaymentLiveApiSecret;
global $iyziCoPaymentCurrency;

global $razorPayPaymentMode;
global $razorPayPaymentStatus;
global $razorPayPaymentTestKeyID;
global $razorPayPaymentTestSecretKey;
global $razorPayPaymentLiveKeyID;
global $razorPayPaymentLiveSecretKey;
global $razorPayPaymentCurrency;

global $payStackPaymentMode;
global $payStackPaymentStatus;
global $payStackPaymentTestSecretKey;
global $payStackPaymentTestPublicKey;
global $payStackPaymentLiveSecretKey;
global $payStackPaymentLivePublicKey;
global $payStackPaymentCurrency;
global $currencys;

global $mercadoPagoMode;
global $mercadoPagoPaymentStatus;
global $mercadoPagoTestAccessTokenID;
global $mercadoPagoLiveAccessTokenID;
global $mercadoPagoCurrency;

// Config Paths
$inoraPaymentConfig = [

    /* Base Path of app
    ------------------------------------------------------------------------- */
    'base_url' =>  $base_url,

    'payments' => [
        /* Gateway Configuration key
        ------------------------------------------------------------------------- */
        'gateway_configuration' => [
            'paypal' => [
                'enable'                        => $payPalPaymentStatus === 1 ? true : false,
                'testMode'                      => ($payPalPaymentMode != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'gateway'                       => 'Paypal', //payment gateway name
                'paypalSandboxBusinessEmail'        => $payPalPaymentSedboxBusinessEmail, //paypal sandbox business email
                'paypalProductionBusinessEmail'     => $payPalPaymentProductBusinessEmail, //paypal production business email
                'currency'                  => $payPalCurrency, //currency
                'currencySymbol'              => $currencys[$payPalCurrency],
                'paypalSandboxUrl'          => 'https://www.sandbox.paypal.com/cgi-bin/webscr', //paypal sandbox test mode Url
                'paypalProdUrl'             => 'https://www.paypal.com/cgi-bin/webscr', //paypal production mode Url
                'notifyIpnURl'              => 'payment-response.php', //paypal ipn request notify Url
                'cancelReturn'              => 'payment-response.php', //cancel payment Url
                'callbackUrl'               => 'payment-response.php', //callback Url after payment successful
                'privateItems'              => []
            ],
            'paystack' => [
                'enable'                    => $payStackPaymentMode === 1 ? true : false,
                'testMode'                  => ($payStackPaymentStatus != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Paystack', //payment gateway name
                'currency'                  => $payStackPaymentCurrency, //currency
                'currencySymbol'              => $currencys[$payStackPaymentCurrency],
                'paystackTestingSecretKey'         => $payStackPaymentTestSecretKey, //paystack testing secret key
                'paystackTestingPublicKey'         => $payStackPaymentTestPublicKey, //paystack testing public key
                'paystackLiveSecretKey'         => $payStackPaymentLiveSecretKey, //paystack live secret key
                'paystackLivePublicKey'         => $payStackPaymentLivePublicKey, //paystack live public key
                'callbackUrl'               => $base_url.'payment-response.php', //callback Url after payment successful
                'privateItems'              => [
                                                $payStackPaymentTestSecretKey,
                                                $payStackPaymentTestPublicKey
                                            ]
            ],
            'stripe'    => [
                'enable'                    => $stripePaymentStatus === 1 ? true : false,
                'testMode'                  => ($stripePaymentMode != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Stripe', //payment gateway name
                'locale'                    => 'auto', //set local as auto
                'allowRememberMe'           => false, //set remember me ( true or false)
                'currency'                  => $stripePaymentCurrency, //currency
                'currencySymbol'              => $currencys[$stripePaymentCurrency],
                'paymentMethodTypes'         => [
                    // before activating additional payment methods
                    // make sure that these methods are enabled in your stripe account
                    // https://dashboard.stripe.com/settings/payments
                    'card',
                    //'ideal',
                    // 'bancontact',
                    // 'giropay',
                    // 'p24',
                    // 'eps'
                ],
                'stripeTestingSecretKey'    => $stripePaymentTestSecretKey, //Stripe testing Secret Key
                'stripeTestingPublishKey'   => $stripePaymentTestPublicKey, //Stripe testing Publish Key
                'stripeLiveSecretKey'       => $stripePaymentLiveSecretKey, //Stripe Secret live Key
                'stripeLivePublishKey'      => $stripePaymentLivePublicKey, //Stripe live Publish Key
                'callbackUrl'               => 'payment-response.php', //callback Url after payment successful
                'privateItems'              => [
                    'stripeTestingPublishKey',
                    'stripeLivePublishKey'
                ]
            ],
            'razorpay'    => [
                'enable'                    => $razorPayPaymentMode === 1 ? true : false,
                'testMode'                  => ($razorPayPaymentStatus != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Razorpay', //payment gateway name
                'merchantname'              => 'John', //merchant name
                'themeColor'                => '#1e88e5', //set razorpay widget theme color
                'currency'                  => $razorPayPaymentCurrency, //currency
                'currencySymbol'              => $currencys[$razorPayPaymentCurrency],
                'razorpayTestingkeyId'      => $razorPayPaymentTestKeyID, //razorpay testing Api Key
                'razorpayTestingSecretkey'  => $razorPayPaymentTestSecretKey, //razorpay testing Api Secret Key
                'razorpayLivekeyId'         => $razorPayPaymentLiveKeyID, //razorpay live Api Key
                'razorpayLiveSecretkey'     => $razorPayPaymentLiveSecretKey, //razorpay live Api Secret Key
                'callbackUrl'               => $base_url.'payment-response.php', //callback Url after payment successful
                'privateItems'              => [
                                                'razorpayTestingSecretkey',
                                                'razorpayLiveSecretkey'
                                            ]
            ],
            'iyzico'    => [
                'enable'                    => $iyziCoPaymentMode === 1 ? true : false,
                'testMode'                  => ($iyziCoPaymentStatus != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Iyzico', //payment gateway name
                'conversation_id'           => 'CONVERS' . uniqid(), //generate random conversation id
                'currency'                  => $iyziCoPaymentCurrency, //currency
                'currencySymbol'              => $currencys[$iyziCoPaymentCurrency],
                'subjectType'               => 1, // credit
                'txnType'                   => 2, // renewal
                'subscriptionPlanType'      => 1, //txn status
                'iyzicoTestingSecretkey'    => $iyziCoPaymentTestSecretKey, //iyzico testing Secret Key
                'iyzicoTestingApiKey'       => $iyziCoPaymentTestApiKey, //iyzico live Api Key
                'iyzicoLiveApiKey'          => $iyziCoPaymentLiveApiSecret, //iyzico live Api Key
                'iyzicoLiveSecretkey'       => $iyziCoPaymentLiveApiKey, //iyzico live Secret Key
                'iyzicoSandboxModeUrl'      => 'https://sandbox-api.iyzipay.com', //iyzico Sandbox test mode Url
                'iyzicoProductionModeUrl'   => 'https://api.iyzipay.com', //iyzico production mode Url
                'callbackUrl'               => 'payment-response.php', //callback Url after payment successful
                'privateItems'              => [
                                                'iyzicoTestingApiKey',
                                                'iyzicoTestingSecretkey',
                                                'iyzicoLiveApiKey',
                                                'iyzicoLiveSecretkey'
                                            ]
            ],
            'authorize-net'    => [
                'enable'                         => $autHorizePaymentMode === 1 ? true : false,
                'testMode'                       => ($autHorizePaymentStatus  != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'gateway'                        => 'Authorize.net', //payment gateway name
                'reference_id'                   => 'REF' . uniqid(), //generate random conversation id
                'currency'                       => $autHorizePaymentCurrency, //currency
                'currencySymbol'                 => $currencys[$autHorizePaymentCurrency],
                'type'                           => 'individual',
                'txnType'                        => 'authCaptureTransaction',
                'authorizeNetTestApiLoginId'     => $autHorizePaymentTestsApID, //authorize-net testing Api login id
                'authorizeNetTestTransactionKey' => $autHorizePaymentTestTransitionKey, //Authorize.net testing transaction key
                'authorizeNetLiveApiLoginId'     => $autHorizePaymentLiveApID, //Authorize.net live Api login id
                'authorizeNetLiveTransactionKey' => $autHorizePaymentLiveTransitionkey, //Authorize.net live transaction key
                'callbackUrl'                    => 'payment-response.php', //callback Url after payment successful
                'privateItems'                  => [
                                                    'authorizeNetTestApiLoginId',
                                                    'authorizeNetTestTransactionKey',
                                                    'authorizeNetLiveApiLoginId',
                                                    'authorizeNetLiveTransactionKey'
                                                ]
            ],
            'bitpay'    => [
                'enable'                        => $bitPayPaymentMode === 1 ? true : false,
                'testMode'                      => ($bitPayPaymentStatus != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'notificationEmail'             => $bitPayPaymentNotificationEmail, // Merchant Email
                'gateway'                       => 'BitPay', //payment gateway name
                'currency'                      => $bitPayPaymentCurrency, //currency
                'currencySymbol'                => $currencys[$bitPayPaymentCurrency], //currency Symbol
                'password'                      => $bitPayPaymentPassword, // Password for "EncryptedFilesystemStorage"
                'pairingCode'                   => $bitPayPaymentPairingCode, // Your pairing Code
                'pairinglabel'                  => $bitPayPaymentLabel, // Your Pairing Label
                'callbackUrl'                   => 'payment-response.php', //callback Url after payment successful
                'privateItems'                  => ['pairingCode', 'pairinglabel', 'password']
            ],
            'mercadopago' => [
                'enable'                        => $mercadoPagoPaymentStatus === 1 ? true : false,
                'testMode'                      => ($mercadoPagoMode != 1 ? true : false), //test mode or product mode (boolean, true or false)
                'gateway'                       => 'Mercado Pago', //payment gateway name
                'currency'                      => $mercadoPagoCurrency, //currency
                'currencySymbol'                => $currencys[$mercadoPagoCurrency], //currency Symbol
                'testAccessToken'               => $mercadoPagoTestAccessTokenID,
                'liveAccessToken'               => $mercadoPagoLiveAccessTokenID,
                'callbackUrl'                   => 'payment-response.php', //callback Url after payment successful
                'privateItems'                  => ['testAccessToken', 'liveAccessToken']
            ]
        ],
    ],

];

return compact("inoraPaymentConfig");