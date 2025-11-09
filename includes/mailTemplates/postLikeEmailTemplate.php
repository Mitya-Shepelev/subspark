<?php 

$bodyPostLikeEmail = <<<STARTEMAIL
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
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i" rel="stylesheet" />
  <title>Email Template</title>
  <style type="text/css">
    body {
      margin: 0 !important;
      padding: 0 !important;
      display: block !important;
      min-width: 100% !important;
      width: 100% !important;
      background: #f3f4f6;
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
    .mobile-shell {
      width: 100% !important;
      min-width: 100% !important;
    }
    .container {
      width: 650px;
      min-width: 650px;
      padding: 55px 0;
    }
    .p30-15 {
      padding: 30px 15px !important;
    }
    .pb10 {
      padding-bottom: 10px !important;
    }
    .pb25 {
      padding-bottom: 25px !important;
    }
    .h1 {
      color: #000000;
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 40px;
      line-height: 46px;
      text-align: center;
    }
    .text-center {
      color: #666666;
      font-family: 'Muli', Arial, sans-serif;
      font-size: 16px;
      line-height: 30px;
      text-align: center;
    }
    .text-button {
      background: #fecc7b;
      color: #000000;
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 14px;
      line-height: 18px;
      padding: 12px 30px;
      text-align: center;
      border-radius: 25px;
      text-transform: uppercase;
    }
    .text-footer1 {
      color: #999999;
      font-family: 'Muli', Arial, sans-serif;
      font-size: 14px;
      line-height: 20px;
      text-align: center;
    }
    .text-footer2 {
      color: #999999;
      font-family: 'Muli', Arial, sans-serif;
      font-size: 12px;
      line-height: 26px;
      text-align: center;
    }
    .svg {
      font-size: 0pt;
      line-height: 0pt;
      text-align: left;
      padding: 5px;
    }
    .hear {
        border-radius: 26px 26px 0 0;
    }
    .p30-15 {
        border-radius: 0 0 26px 26px;
    }
  </style>
</head>
<body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f3f4f6">
    <tr>
      <td align="center" valign="top">
        <table class="mobile-shell" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="container">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="hear">
                <tr>
                  <td class="p30-15">
                    <table width="100%">
                      <tr>
                        <td><img src="$siteLogoUrl" width="45" height="45" alt="Logo"></td>
                        <td align="right"><a href="$base_url">$siteName</a></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <table width="100%">
                <tr>
                  <td align="center"><img src="https://media1.giphy.com/media/YondZW6AMjgTEHevF0/200w.gif" alt=""></td>
                </tr>
              </table>
              <table width="100%" bgcolor="#ffffff">
                <tr>
                  <td class="p30-15">
                    <table width="100%">
                      <tr><td class="h1 pb25">$likedYourPost</td></tr>
                      <tr><td class="text-center pb25">$someoneLikedYourPost</td></tr>
                      <tr>
                        <td align="center">
                          <table>
                            <tr>
                              <td class="text-button"><a href="$slugUrl">$clickGoPost</a></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <table width="100%">
                <tr><td class="pb10"></td></tr>
                <tr>
                  <td class="p30-15" bgcolor="#ffffff">
                    <table width="100%">
                      <tr>
                        <td align="center">
                          <table>
                            <tr>
                              <td class="svg"><a href="#">$facebookIcon</a></td>
                              <td class="svg"><a href="#">$twitterIcon</a></td>
                              <td class="svg"><a href="#">$instagramIcon</a></td>
                              <td class="svg"><a href="#">$linkedinIcon</a></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr><td class="text-footer1 pb10">$notQualifyDocument</td></tr>
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