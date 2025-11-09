<?php 

$bodyPointPurchased = <<<STARTEMAIL

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="format-detection" content="date=no" />
  <meta name="format-detection" content="address=no" />
  <meta name="format-detection" content="telephone=no" />
  <meta name="x-apple-disable-message-reformatting" />
  <link href="https://fonts.googleapis.com/css?family=Merriweather:400,400i,700,700i" rel="stylesheet" />
  <title>Email Template</title>
  <style type="text/css">
    body {
      margin: 0 !important;
      padding: 0 !important;
      display: block !important;
      min-width: 100% !important;
      width: 100% !important;
      background: linear-gradient(90deg, #f65169, #fab429);
      -webkit-text-size-adjust: none;
    }
    a {
      color: #000001;
      text-decoration: none;
    }
    p {
      margin: 0 !important;
      padding: 0 !important;
    }
    img {
      -ms-interpolation-mode: bicubic;
    }
    .container {
      width: 650px;
      min-width: 650px;
      padding: 55px 0;
    }
    .text-header {
      color: #999999;
      font-family: 'Merriweather', Georgia, serif;
      font-size: 13px;
      line-height: 18px;
      text-align: right;
    }
    .hero-text {
      font-size: 30pt;
      height: 300px;
      line-height: 30pt;
      text-align: center;
      font-family: 'Merriweather', Georgia, serif;
      background: #f65169;
      color: #ffffff;
    }
    .h1 {
      color: #444444;
      font-family: 'Merriweather', Georgia, serif;
      font-size: 35px;
      line-height: 42px;
      text-align: center;
      padding-bottom: 25px;
    }
    .text-center {
      color: #666666;
      font-family: Arial, sans-serif;
      font-size: 16px;
      line-height: 30px;
      text-align: center;
      padding-bottom: 25px;
    }
    .text-button {
      background: #ffda5c;
      color: #444444;
      font-family: 'Merriweather', Georgia, serif;
      font-size: 14px;
      line-height: 18px;
      padding: 12px 15px;
      text-align: center;
      border-radius: 10px;
      text-transform: uppercase;
    }
    .text-footer1 {
      color: #999999;
      font-family: Arial, sans-serif;
      font-size: 14px;
      line-height: 20px;
      text-align: center;
      padding-bottom: 10px;
    }
    .text-footer2 {
      color: #999999;
      font-family: Arial, sans-serif;
      font-size: 12px;
      line-height: 26px;
      text-align: center;
    }
    .svg {
      padding: 5px;
      width: 25px;
      height: 25px;
    }
  </style>
</head>
<body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fab429">
    <tr>
      <td align="center">
        <table class="container" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <!-- Header -->
              <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tr>
                  <td>
                    <table width="100%">
                      <tr>
                        <td><img src="$siteLogoUrl" width="45" height="45" alt="Logo" /></td>
                        <td class="text-header"><a href="$base_url">$siteName</a></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <!-- Hero -->
              <table width="100%">
                <tr>
                  <td class="hero-text">$planPoint point</td>
                </tr>
              </table>
              <!-- Body -->
              <table width="100%" bgcolor="#ffffff">
                <tr>
                  <td>
                    <table width="100%">
                      <tr><td class="h1">$pointPurchasesuccess</td></tr>
                      <tr><td class="text-center">$pointPurchaseDetails</td></tr>
                      <tr>
                        <td align="center">
                          <table>
                            <tr><td class="text-button"><a href="$base_url">$startUsingYourPoints</a></td></tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <!-- Footer -->
              <table width="100%" bgcolor="#ffffff">
                <tr>
                  <td>
                    <table width="100%">
                      <tr>
                        <td align="center">
                          <table>
                            <tr>
                              <td class="svg">$facebookIcon</td>
                              <td class="svg">$twitterIcon</td>
                              <td class="svg">$instagramIcon</td>
                              <td class="svg">$linkedinIcon</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr><td class="text-footer1">$notQualifyDocument</td></tr>
                      <tr><td class="text-footer2">$businessAddress</td></tr>
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