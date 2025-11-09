<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php include "layouts/header/meta.php";include "layouts/header/css.php";?>
    <link rel="stylesheet" href="<?php echo $themePath; ?>/scss/legal.css?v=<?php echo iN_HelpSecure($version); ?>">
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/jquery-v3.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/legal.js"></script>
</head>
<body data-siteurl="<?php echo iN_HelpSecure($base_url); ?>">
<?php
error_reporting(0); 
$step = '';
if(isset($_GET['step']) && $_GET['step'] != '' && strlen(trim($_GET['step'])) != 0){
   $step = $_GET['step'];
}

$cURL = true;
$php = true;
$gd = true;
$disabled = false;
$mysqli = true;
$is_writable = true;
$is_lang_writable = true;
$is_node_json_writable = true;
$mbstring = true;
$is_htaccess = true;
$is_mod_rewrite = true;
$is_sql = true;
$zip = true;
$allow_url_fopen = true;
$exif_read_data = true;
$file_path_info = true;
if (!function_exists('curl_init')) {
    $cURL = false;
    $disabled = true;
}
if (!function_exists('mysqli_connect')) {
    $mysqli = false;
    $disabled = true;
}
if (!extension_loaded('mbstring')) {
    $mbstring = false;
    $disabled = true;
}
if (!extension_loaded('gd') && !function_exists('gd_info')) {
    $gd = false;
    $disabled = true;
}
if (!version_compare(PHP_VERSION, '5.5', '>=')) {
    $php = false;
    $disabled = true;
}
if (!file_exists('.htaccess')) {
    $is_htaccess = false;
    $disabled = true;
}
if(!ini_get('allow_url_fopen') ) {
    $allow_url_fopen = false;
    $disabled = true;
}

if($step == 'requirements' || $step == ''){
?>
<div class="container">
    <div class="beLegalForm">
        <div class="requirement">Requirements</div>
        <div class="checkRequirements">
            <div class="requirementItem">
                <div class="itemone">Name</div>
                <div class="itemtwo">Direction</div>
                <div class="itemthree">Status</div>
            </div>
            <div class="requirementItem">
                <div class="itemone">PHP 5.5+</div>
                <div class="itemtwo">Required PHP version 5.6 or more</div>
                <div class="itemthree">
                <?php echo ($php == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not installed</font>'?>
                </div>
            </div>
            <div class="requirementItem">
                <div class="itemone">cURL</div>
                <div class="itemtwo">Required cURL PHP Extension</div>
                <div class="itemthree">
                <?php echo ($cURL == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not installed</font>'?>
                </div>
            </div>
            <div class="requirementItem">
                <div class="itemone">MySQLi</div>
                <div class="itemtwo">Required MySQLi PHP Extension</div>
                <div class="itemthree">
                <?php echo ($mysqli == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not installed</font>'?>
                </div>
            </div>
            <div class="requirementItem">
                <div class="itemone">GD Library</div>
                <div class="itemtwo">Required GD Library for image cropping</div>
                <div class="itemthree">
                <?php echo ($gd == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not installed</font>'?>
                </div>
            </div>
            <div class="requirementItem">
                <div class="itemone">allow_url_fopen</div>
                <div class="itemtwo">Required allow_url_fopen</div>
                <div class="itemthree">
                <?php echo ($allow_url_fopen == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not installed</font>'?>
                </div>
            </div>
            <div class="requirementItem">
                <div class="itemone">FileInfo</div>
                <div class="itemtwo">Required FileInfo exntesion for FFMPEG</div>
                <div class="itemthree">
                <?php echo ($file_path_info == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not installed</font>'?>
                </div>
            </div>
            <div class="requirementItem">
                <div class="itemone">.htaccess</div>
                <div class="itemtwo">Required .htaccess file for script security <small>(Located in ./ScriptFiles)</small></div>
                <div class="itemthree">
                <?php echo ($is_htaccess == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not installed</font>'?>
                </div>
            </div>
        </div>
        <div class="belegal_n">
        <form action="?step=verify" method="post">
            <button type="submit" class="btn btn-main" id="next-terms" <?php echo ($disabled == true) ? 'disabled': '';?>>Next <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
            <div class="setting-saved-update-alert milinglist"></div>
        </form>
        </div>
    </div>
</div>
<?php }else if($step == 'verify' && $cURL == true || $mysqli == true || $mbstring == true || $php == true || $gd == true || $is_htaccess == true || $allow_url_fopen == true){?>
<div class="container">
    <div class="beLegalForm">
        <div class="payouts_form_container">
            <div class="i_settings_wrapper_item">
                <div class="i_settings_item_title">Confirm your website with your purchase code.</div>
                <div class="i_settings_item_title_for">
                    <input type="text" name="crn_password" class="flnm" id="validate_purchase_code" placeholder="Paste your Purchase Code Here">
                    <button class="i_nex_btn_btn transition lme check" id="button-update">Verify</button>
                </div>
                <div class="checking_notes"></div>
                <div class="i_settings_item_title_warning"></div>
                <div class="i_settings_item_title">How to download your Purchase Code</div>
                <div class="i_settings_item_title_for bgNot">
                    <p><strong>Step 1</strong> – Log into your CodeCanyon account and click your username in the top right corner to access the dropdown. Select the “Downloads” link.</p>
                    <p><strong>Step 2</strong> – Find the Dizzy Purchase in the list of items you have bought.</p>
                    <p><strong>Step 3</strong> – Click the “Download” button to activate the dropdown menu. Select to download the license certificate and purchase code as a PDF or Text file. Open the file to find the purchase code.</p>
                    <p><strong>Example purchase code format:</strong> 91X36x28-xxx5-4X70-x109-x9wc8xxc6X16</p>
                    <p>A license key can only be used on one domain. If you want to use the script in more than one project, you need to <a href="https://codecanyon.net/item/dizzy-support-creators-content-script/31263937" target="blank">purchase</a> a new license.</p></div>
                    <img src="https://dizzy.dizzyscripts.com/cc/dizzy_purchase_code_download_screen.png">
                </div>
            </div>
    </div>
</div>
<?php }else{
    header('Location: ' . route_url(''));
}?>
</body>
</html>
