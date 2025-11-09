<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
       <!---->
       <div class="i_general_title_box">
         <?php echo iN_HelpSecure($LANG['email_settings']);?>
       </div>
       <!---->
       <!---->
       <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="myEmailForm">
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['server_type']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="smtpormail"><span class="sm_or_ma text_transform"><?php echo iN_HelpSecure($smtpOrMail);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cp_container">
                            <div class="i_countries_list border_one column flex_">
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($smtpOrMail) == 'smtp' ? 'choosed' : ''; ?>" id='smtp' data-c="smtp" data-type="smtpOrMail">STMP</div>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($smtpOrMail) == 'mail' ? 'choosed' : ''; ?>" id='mail' data-c="mail" data-type="smtpOrMail">MAIL</div>
                            </div>
                            <input type="hidden" name="smtpmail" id="smtp_or_mail" value="<?php echo iN_HelpSecure($smtpOrMail);?>">
                        </div>
                   </div>
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['server_type']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="smtp_encription"><span class="ssl_or_tls text_transform"><?php echo iN_HelpSecure($smtpEncryption);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_ch_container">
                            <div class="i_countries_list border_one column flex_">
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($smtpEncryption) == 'ssl' ? 'choosed' : ''; ?>" id='ssl' data-c="ssl" data-type="smtpEncryption">SSL</div>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($smtpEncryption) == 'tls' ? 'choosed' : ''; ?>" id='tls' data-c="tls" data-type="smtpEncryption">TLS</div>
                            </div>
                            <input type="hidden" name="smtpecript" id="smtp_encription" value="<?php echo iN_HelpSecure($smtpEncryption);?>">
                        </div>
                   </div>
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['website_default_email']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="smtp_host_email" class="i_input flex_" value="<?php echo iN_HelpSecure($smtpEmail);?>">
                 <div class="rec_not"><?php echo iN_HelpSecure($LANG['default_email_not']);?></div>
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['smtp_host']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="smtp_host" class="i_input flex_" value="<?php echo iN_HelpSecure($smtpHost);?>">
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['smtp_username']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="smtp_username" class="i_input flex_" value="<?php echo iN_HelpSecure($smtpUserName);?>">
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['smtp_password']);?></div>
               <div class="irow_box_right">
                 <input type="password" name="smtp_password" class="i_input flex_" value="<?php echo iN_HelpSecure($smtpPassword);?>">
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['smtp_port']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="smtp_port" class="i_input flex_" value="<?php echo iN_HelpSecure($smtpPort);?>">
               </div>
            </div>
            <!---->
            <div class="warning_wrapper warning_one"><?php echo iN_HelpSecure($LANG['all_fields_must_be_filled']);?></div>
            <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']);?></div>
            <div class="i_general_row_box_item flex_ tabing_non_justify">
                <input type="hidden" name="f" value="emailSettings">
                <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings"><?php echo iN_HelpSecure($LANG['save_edit']);?></button>
            </div>
            </form>
       </div>
       <!---->

    </div>
</div>