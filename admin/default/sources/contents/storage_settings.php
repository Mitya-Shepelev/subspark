<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
       
       <div class="i_general_title_box">
         <?php echo iN_HelpSecure($LANG['storage']);?>(Amazon S3)
       </div>
        
       <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="storageSettings">
             
            <div class="i_general_row_box_item flex_ column tabing__justify" >
                <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                  <label class="el-switch el-switch-yellow" for="sstat">
                    <input type="checkbox" name="s3Status" class="sstat" id="sstat" <?php echo iN_HelpSecure($s3Status) == '1' ? 'value="1" checked="checked"' : 'value="0"';?>>
                    <span class="el-switch-style"></span>
                  </label>
                  <div class="i_chck_text"><?php echo iN_HelpSecure($LANG['s3_status']);?></div>
                  <input type="hidden" name="s3Status" id="stats3" value="<?php echo iN_HelpSecure($s3Status);?>">
                </div>
                <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['s3_status_not']);?></div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['server_type']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="s3update"><span class="s3choosed"><?php echo iN_HelpSecure($S3REGIONS[$s3Region]);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_s3_container">
                            <div class="i_countries_list border_one column flex_">
                              
                              <?php foreach($S3REGIONS as $s3r => $value){?>
                                <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($s3Region) == '' . $s3r . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($s3r); ?>' data-c="<?php echo iN_HelpSecure($value);?>" data-type="s3set"><?php echo iN_HelpSecure($value);?></div>
                              <?php }?>
                              
                            </div>
                            <input type="hidden" name="s3region" id="s3region" value="<?php echo iN_HelpSecure($s3Region);?>">
                        </div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3Bucket']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="s3Bucket" class="i_input flex_" value="<?php echo iN_HelpSecure($s3Bucket);?>">
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3Key']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="s3Key" class="i_input flex_" value="<?php echo iN_HelpSecure($s3Key);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3sKey']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="s3sKey" class="i_input flex_" value="<?php echo iN_HelpSecure($s3SecretKey);?>">
               </div>
            </div>

            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['storage_public_base']);?></div>
               <div class="irow_box_right column flex_">
                 <input type="text" name="s3PublicBase" class="i_input flex_" placeholder="https://cdn.example.com/" value="<?php echo iN_HelpSecure($s3PublicBase ?? '');?>">
                 <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['storage_public_base_not']);?></div>
               </div>
            </div>
            
            <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']);?></div>
            <div class="i_general_row_box_item flex_ tabing_non_justify">
                <input type="hidden" name="f" value="s3Settings">
                <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings"><?php echo iN_HelpSecure($LANG['save_edit']);?></button>
            </div>
            </form>
       </div>
        
    </div>
</div>
