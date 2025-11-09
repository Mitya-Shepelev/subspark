<?php 
$bodyVerifyEmail = <<<STARTEMAIL
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Verify Email</title>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f1f1f1">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td bgcolor="#ffffff">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center">
                                                    <h1>e-Verify</h1>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <img src="images/email.png" alt="Verify" width="300" border="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <h2>Please verify your email</h2>
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td align="center" bgcolor="#30e3ca">
                                                                <a href="$theCode" target="_blank">Click Here to verify</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
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