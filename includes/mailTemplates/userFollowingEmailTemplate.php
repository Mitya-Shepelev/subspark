<?php
$bodyUserFollowEmailTemplate = <<<STARTEMAIL
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .main-table {
            width: 100%;
            background-color: #f0f2f5;
        }
        .email-container {
            width: 650px;
            background-color: #ffffff;
            border-radius: 12px;
        }
        .logo-cell img {
            display: block;
        }
        .user-avatar {
            border-radius: 50%;
        }
        .user-name {
            font-family: 'Raleway', Arial, sans-serif;
            font-size: 22px;
            color: #ffffff;
            font-weight: 500;
        }
        .follow-text {
            font-family: 'Raleway', Arial, sans-serif;
            font-size: 12px;
            color: #ffffff;
            text-transform: uppercase;
        }
        .footer-text {
            font-family: 'Raleway', Arial, sans-serif;
            font-size: 14px;
            color: #889cbe;
        }
    </style>
</head>
<body>
    <table class="main-table" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="email-container" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <table width="100%" cellpadding="20" cellspacing="0" bgcolor="#d8dbdf">
                                <tr>
                                    <td class="logo-cell">
                                        <img src="$siteLogoUrl" width="45" height="45" alt="logo">
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="30" cellspacing="0" bgcolor="#fab429">
                                <tr>
                                    <td width="80">
                                        <img src="$fuserAvatar" width="80" height="80" alt="avatar" class="user-avatar">
                                    </td>
                                    <td width="30"></td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td class="user-name">
                                                    <a href="$slugUrl" target="_blank">$lUserFullName</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="follow-text">
                                                    $startedFollow
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="30" cellspacing="0">
                                <tr>
                                    <td>
                                        <table width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="25">
                                                    <a href="#" target="_blank">$facebookIcon</a>
                                                </td>
                                                <td width="25">
                                                    <a href="#" target="_blank">$twitterIcon</a>
                                                </td>
                                                <td width="25">
                                                    <a href="#" target="_blank">$instagramIcon</a>
                                                </td>
                                                <td width="25">
                                                    <a href="#" target="_blank">$linkedinIcon</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="footer-text" align="left">
                                        $businessAddress
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