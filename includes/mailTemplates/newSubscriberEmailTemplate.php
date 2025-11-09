<?php

$bodyNewSubscriberEmailTemplate = <<<STARTEMAIL
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="format-detection" content="telephone=no" />
    <title>New Subscriber</title>
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,700" rel="stylesheet" />
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100% !important;
            background: linear-gradient(90deg, #f65169, #fab429);
            font-family: 'Merriweather', Georgia, serif;
            -webkit-text-size-adjust: none;
        }

        a {
            text-decoration: none;
            color: #000001;
        }

        .email-wrapper {
            width: 100%;
            background-color: #fab429;
            padding: 55px 0;
        }

        .email-container {
            width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px 12px 0 0;
        }

        .email-header {
            padding: 30px;
            border-radius: 12px 12px 0 0;
        }

        .site-logo {
            width: 45px;
            height: 45px;
        }

        .site-name {
            text-align: right;
            font-size: 13px;
            color: #999999;
        }

        .hero-section {
            height: 300px;
            text-align: center;
            background-color: #f65169;
            color: #ffffff;
        }

        .hero-section img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-top: 30px;
        }

        .email-body {
            padding: 60px 30px;
        }

        .email-title {
            text-align: center;
            font-size: 35px;
            line-height: 42px;
            color: #444444;
            margin-bottom: 25px;
        }

        .email-text {
            text-align: center;
            font-size: 16px;
            line-height: 30px;
            color: #666666;
            margin-bottom: 25px;
        }

        @media screen and (max-width: 480px) {
            .email-container {
                width: 100% !important;
                border-radius: 0 !important;
            }

            .site-name {
                text-align: center !important;
            }

            .email-body {
                padding: 30px 15px !important;
            }

            .email-title {
                font-size: 28px !important;
                line-height: 36px !important;
            }

            .email-text {
                font-size: 14px !important;
                line-height: 24px !important;
            }
        }
    </style>
</head>
<body>
    <table class="email-wrapper" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <table class="email-container" cellspacing="0" cellpadding="0" border="0">
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <table width="100%">
                                <tr>
                                    <td align="left">
                                        <img src="$siteLogoUrl" class="site-logo" alt="Logo" />
                                    </td>
                                    <td class="site-name">
                                        <a href="$base_url">$siteName</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Hero Image -->
                    <tr>
                        <td class="hero-section">
                            <img src="$fuserAvatar" alt="User Avatar" />
                        </td>
                    </tr>

                    <!-- Message -->
                    <tr>
                        <td class="email-body">
                            <div class="email-title">$gotNewSubscriber</div>
                            <div class="email-text">$morePostForSubscriber</div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
STARTEMAIL;

?>