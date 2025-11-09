<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
       <!---->
       <div class="i_general_title_box">
         <?php echo iN_HelpSecure($LANG['billing_informations']);?>
       </div>
       <!---->
       <!---->
       <div class="i_general_row_box column flex_" id="business_conf">
         <form enctype="multipart/form-data" method="post" id="siteBusinessInformation">
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['campany']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="site_campany" class="i_input flex_" value="<?php echo iN_HelpSecure($siteCampany);?>">
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['country']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="site_country" id="gsearchsimple" class="i_input countr flex_" value="<?php echo iN_HelpSecure($COUNTRIES[$siteCountry]);?>">
                 <input type="hidden" name="country_code" id="newCountry" value="<?php echo iN_HelpSecure($siteCountry);?>">
                 <div class="i_choose_country">
                     <div class="i_countries_list border_one column flex_" id="simple">
                     <?php foreach($COUNTRIES as $country => $value){?>
                        <div class="i_s_country transition border_one gsearch <?php echo iN_HelpSecure($siteCountry) == '' . $country . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($country); ?>' data-c="<?php echo iN_HelpSecure($value);?>"><?php echo iN_HelpSecure($value); ?></div>
                     <?php }?>
                     </div>
                 </div>
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['city']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="site_city" class="i_input flex_" value="<?php echo iN_HelpSecure($siteCity);?>">
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['business_address']);?></div>
               <div class="irow_box_right">
                 <textarea type="text" name="site_business_address" class="i_textarea flex_ border_one"><?php echo iN_HelpSecure($businessAddress);?></textarea>
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['post_code']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="site_post_code" class="i_input flex_" value="<?php echo iN_HelpSecure($sitePostCode);?>">
               </div>
            </div>
            <!---->
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['vat']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="site_vat" class="i_input flex_" value="<?php echo iN_HelpSecure($siteVat);?>">
               </div>
            </div>
            <!---->
            <div class="warning_wrapper warning_one"><?php echo iN_HelpSecure($LANG['all_fields_must_be_filled']);?></div>
            <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']);?></div>
            <!---->
            <div class="i_general_row_box_item flex_ tabing_non_justify">
                <input type="hidden" name="f" value="updateBusiness">
                <button type="submit" name="submit" class="i_nex_btn_btn transition"><?php echo iN_HelpSecure($LANG['save_edit']);?></button>
            </div>
         </form>
       </div>
       <!---->
    </div>
</div>