<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle);?></title>
    <?php
       include("layouts/header/meta.php");
       include("layouts/header/css.php");
       include("layouts/header/javascripts.php");
    ?>
</head>
<body>
<?php if($logedIn == 0){ include('layouts/login_form.php'); }?>
<?php include("layouts/header/header.php");?>
<div class="wrapper bCreatorBg">
    <div class="i_become_creator_container"> 
        <div class="i_modal_content">
            <!--Register Header-->
            <div class="i_login_box_header">
                <div class="i_login_box_wellcome_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14'));?></div>
                <div class="i_welcome_back">
                    <div class="i_lBack"><?php echo iN_HelpSecure($LANG['reset_your_password']);?></div>
                    <div class="i_lnot"><?php echo iN_HelpSecure($LANG['reset_pass_not']);?></div>
                </div>
            </div>
            <!--/Register Header-->
            <div class="i_direct_register i_register_box_">
            <form enctype="multipart/form-data" method="post" id='iresetpass' autocomplete="off"> 
                <div class="i_settings_item_title_for flex_ extra_style">
                    <div class="i_re_box">
                        <div class="i_settings_item_title i_settings_item_title_extra_with"><?php echo iN_HelpSecure($LANG['your_new_pass']);?></div>
                        <div class="i_settings_item_title_for i_settings_item_title_for_style">
                           <input type="password" name="pnew" class="flnm min_with" placeholder="<?php echo iN_HelpSecure($LANG['write_your_new_pass']);?>">
                       </div>
                    </div>
                    <div class="i_re_box">
                        <div class="i_settings_item_title i_settings_item_title_extra_with"><?php echo iN_HelpSecure($LANG['re_new_pass']);?><input type="hidden" name="ac" value="<?php echo iN_HelpSecure($activationCode);?>"></div>
                        <div class="i_settings_item_title_for i_settings_item_title_for_style">
                           <input type="password" name="repnew" class="flnm min_with" placeholder="<?php echo iN_HelpSecure($LANG['re_re_new_pass']);?>">
                        </div>
                    </div>
                </div> 
                <div class="i_settings_wrapper_item warning_success">
                    <?php echo iN_HelpSecure($LANG['your_pass_changed_success_login']);?>
                </div>
                <div class="i_settings_wrapper_item warning_not_mach">
                    <?php echo iN_HelpSecure($LANG['new_pass_not_match']);?>
                </div>
                <div class="i_settings_wrapper_item successNot">
                    <?php echo iN_HelpSecure($LANG['profile_updated_success']);?>
                </div>
                <div class="i_settings_wrapper_item no_new_password_wrote">
                    <?php echo iN_HelpSecure($LANG['no_new_password_wrote']);?>
                </div>
                <div class="i_settings_wrapper_item minimum_character_not">
                    <?php echo iN_HelpSecure($LANG['minimum_character_not']);?>
                </div>
                <div class="i_settings_wrapper_item not_contain_whitespace">
                    <?php echo iN_HelpSecure($LANG['not_contain_whitespace']);?>
                </div>
                <div class="form_group">
                    <input type="hidden" name="f" value="iresetpass">
                    <div class="i_login_button i_res_button"><button type="submit"><?php echo iN_HelpSecure($LANG['reset_now']);?></button></div>
                </div>
            </form>
            </div>
        </div> 
    </div>
</div>
</body>
</html>
