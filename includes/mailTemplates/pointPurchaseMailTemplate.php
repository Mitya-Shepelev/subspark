<?php

$bodyPointPurchased = <<<STARTEMAIL

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #fab429;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
        }
        .container {
            width: 100%;
            padding: 40px 0;
            background: #fab429;
        }
        .main {
            width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
        }
        .header {
            padding: 30px;
            background: #ffffff;
        }
        .header-logo {
            width: 45px;
            height: 45px;
        }
        .site-name {
            font-size: 14px;
            color: #999999;
            text-align: right;
        }
        .hero {
            height: 300px;
            background: #f65169;
            color: #ffffff;
            text-align: center;
            font-size: 30px;
            line-height: 300px;
            font-weight: bold;
        }
        .content {
            padding: 60px 30px;
            text-align: center;
        }
        .title {
            font-size: 28px;
            color: #444444;
            margin-bottom: 20px;
        }
        .text {
            font-size: 16px;
            color: #666666;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background: #ffda5c;
            color: #444444;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 14px;
            text-transform: uppercase;
        }
        .footer {
            padding: 50px 30px;
            background: #ffffff;
            text-align: center;
            font-size: 14px;
            color: #999999;
        }
        .footer small {
            display: block;
            font-size: 12px;
            line-height: 20px;
        }
        .social-icons img {
            width: 25px;
            height: 25px;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <table class="container" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table class="main" width="650" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="header">
                            <table width="100%">
                                <tr>
                                    <td align="left">
                                        <img src="$siteLogoUrl" alt="Logo" class="header-logo" />
                                    </td>
                                    <td class="site-name">
                                        <a href="$base_url">$siteName</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="hero">
                            $planPoint point
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <div class="title">$pointPurchasesuccess</div>
                            <div class="text">$pointPurchaseDetails</div>
                            <a href="$base_url" class="button">$startUsingYourPoints</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <div class="social-icons">
                                <a href="#" target="_blank">$facebookIcon</a>
                                <a href="#" target="_blank">$twitterIcon</a>
                                <a href="#" target="_blank">$instagramIcon</a>
                                <a href="#" target="_blank">$linkedinIcon</a>
                            </div>
                            <br>
                            <small>$notQualifyDocument</small>
                            <small>$businessAddress</small>
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