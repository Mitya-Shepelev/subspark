<?php

$bodyCommentEmail = <<<STARTEMAIL
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
    <title>Comment Notification</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600,700" rel="stylesheet" />

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            min-width: 100% !important;
            width: 100% !important;
            background: #ffffff;
            font-family: 'Raleway', Arial, sans-serif;
        }

        a {
            text-decoration: none;
            color: #000000;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f65169;
            padding: 40px 0;
        }

        .email-container {
            width: 650px;
            margin: 0 auto;
            background-color: #f0f1f5;
            border-radius: 12px;
            overflow: hidden;
        }

        .email-header {
            background-color: #f0f2f5;
            padding: 20px 30px;
        }

        .site-logo {
            width: 45px;
            height: 45px;
        }

        .site-name {
            font-size: 14px;
            color: #000000;
        }

        .comment-section {
            background-color: #d8dbdf;
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .comment-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .comment-name {
            font-size: 22px;
            font-weight: 500;
            color: #000000;
            margin-bottom: 10px;
        }

        .comment-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #000000;
        }

        .comment-message {
            padding: 0 30px 30px 30px;
            font-size: 15px;
            color: #000000;
            line-height: 1.8;
        }

        .footer {
            padding: 30px;
            background-color: #f0f1f5;
        }

        .footer-logo {
            width: 45px;
            height: 45px;
        }

        .footer-icons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 13px;
            color: #889cbe;
        }

        @media screen and (max-width: 480px) {
            .email-container {
                width: 100% !important;
                border-radius: 0 !important;
            }

            .comment-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-icons {
                justify-content: center;
            }

            .footer-text {
                text-align: center;
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
                                    <td>
                                        <img src="$siteLogoUrl" class="site-logo" alt="Logo" />
                                    </td>
                                    <td align="right" class="site-name">
                                        <a href="$base_url">$siteName</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Comment Info -->
                    <tr>
                        <td class="comment-section">
                            <img src="$commentedUserAvatar" class="comment-avatar" alt="Avatar" />
                            <div>
                                <div class="comment-name">$commentedUserFullName</div>
                                <div class="comment-label">$commentedBelow</div>
                            </div>
                        </td>
                    </tr>

                    <!-- Comment Message -->
                    <tr>
                        <td class="comment-message">
                            $commentE
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <img src="$siteLogoUrl" class="footer-logo" alt="Logo" />
                                    </td>
                                    <td align="right">
                                        <div class="footer-icons">
                                            <a href="#">$facebookIcon</a>
                                            <a href="#">$twitterIcon</a>
                                            <a href="#">$instagramIcon</a>
                                            <a href="#">$linkedinIcon</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="footer-text">
                                        $notQualifyDocument<br>$businessAddress
                                    </td>
                                </tr>
                            </table>
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