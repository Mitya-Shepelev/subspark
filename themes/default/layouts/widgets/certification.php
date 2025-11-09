<div class="i_become_creator_terms_box">
<div class="certification_form_container">
   <div class="certification_form_title"><?php echo iN_HelpSecure($LANG['account_certification']);?></div>
   <div class="certification_form_not"><?php echo iN_HelpSecure($LANG['please_provide_follows_for_certify_your_account']);?></div>
   <div class="certification_form_wrapper">
        <div class="certification_terms">
            <div class="certification_terms_item terms_bg"></div>
            <div class="certification_terms_item">
                <div class="certificate_terms_item_item">
                   <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('78'));?><?php echo iN_HelpSecure($LANG['scan_photo_of_your_id_card']);?>
                </div>
                <div class="certificate_terms_item_item">
                   <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('78'));?><?php echo html_entity_decode($LANG['photo_of_you_holding_id_vard_and_sign']);?>
                </div>
            </div>
        </div>
        <form id="vUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url);?>requests/request.php">
        <div class="certification_file_form" id="sec_one">
            <div class="certification_file_box">
                <label for="id_card"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')); echo iN_HelpSecure($LANG['upload_id_card']);?><input type="file" id="id_card" name="uploading[]" data-id="uploadVerificationFiles" data-type="sec_one" class="editAds_file"></label><?php echo html_entity_decode($LANG['scan_photo_of_your_id_card']);?>
            </div>
            <div class="certificate_file_box_not"><?php echo iN_HelpSecure($LANG['max_size']);?></div>
            <div class="certificate_uploaded_file f_sec_one"></div>
        </div>
        <div class="certification_file_form" id="sec_two">
            <div class="certification_file_box">
                <label for="id_card_two"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')); echo iN_HelpSecure($LANG['upload_id_card']);?><input type="file" id="id_card_two" name="uploading[]" data-id="uploadVerificationFiles" data-type="sec_two" class="editAds_file"></label><?php echo html_entity_decode($LANG['photo_of_you_holding_id_vard_and_sign']);?>
            </div>
            <div class="certificate_file_box_not"><?php echo iN_HelpSecure($LANG['max_size']);?></div>
            <div class="certificate_uploaded_file f_sec_two"></div>
        </div>
        </form>
        <div class="certification_file_form">
            <input type="hidden" id="uploadVal_sec_one">
            <input type="hidden" id="uploadVal_sec_two">
            <div class="certification_file_box">
                 <?php echo html_entity_decode($LANG['accept_terms_of_conditions']);?>
            </div>
        </div>
   </div>
</div>
<div class="i_warning both"><?php echo iN_HelpSecure($LANG['please_upload_all']);?></div>
<div class="i_warning card"><?php echo iN_HelpSecure($LANG['please_upload_id_card']);?></div>
<div class="i_warning photo"><?php echo iN_HelpSecure($LANG['please_upload_id_photo']);?></div>
</div>
<div class="i_become_creator_box_footer">
   <div class="i_nex_btn v_Next transition"><?php echo iN_HelpSecure($LANG['next']);?></div>
</div>