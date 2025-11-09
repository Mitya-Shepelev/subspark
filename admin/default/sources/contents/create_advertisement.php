<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify">
    <div class="i_general_title_box flex_ tabing_non_justify">
      <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('132')); ?>
      <?php echo iN_HelpSecure($LANG['create_advertisement']); ?>
    </div>

    <div class="i_general_row_box column flex_ box_not_padding_top_package" id="general_conf">
      <div class="i_general_row_box_item flex_ column tabing__justify">
        <div class="i_langs_wrapper tabing">
          <div class="i_general_row_box_item flex_ tabing_non_justify" id="sec_logo">
            <div class="irow_box_left tabing flex_">
              <?php echo iN_HelpSecure($LANG['ads_image']); ?>
            </div>
            <div class="irow_box_right">
              <div class="certification_file_box">
                <form id="adsUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/request/request.php">
                  <label for="ad_image">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')); ?>
                    <?php echo iN_HelpSecure($LANG['u_ads_image']); ?>
                    <input type="file" id="ad_image" name="uploading[]" data-id="adsFile" data-type="adsType" class="editAds_file">
                  </label>
                </form>
                <div class="success_tick tabing flex_ adsType">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                </div>
              </div>
              <div class="rec_not"><?php echo iN_HelpSecure($LANG['recommend_ads_image']); ?></div>
            </div>
          </div>

          <form enctype="multipart/form-data" method="post" id="adsDForm">
            <div class="i_general_row_box_item flex_ tabing_non_justify">
              <div class="irow_box_left tabing flex_">
                <?php echo iN_HelpSecure($LANG['ads_title']); ?>
              </div>
              <div class="irow_box_right">
                <input type="text" name="ads_title" class="i_input flex_">
              </div>
            </div>

            <div class="i_general_row_box_item flex_ tabing_non_justify">
              <div class="irow_box_left tabing flex_">
                <?php echo iN_HelpSecure($LANG['ads_redirect_url']); ?>
              </div>
              <div class="irow_box_right">
                <input type="text" name="ads_url" class="i_input flex_">
              </div>
            </div>

            <div class="i_general_row_box_item flex_ tabing_non_justify">
              <div class="irow_box_left tabing flex_">
                <?php echo iN_HelpSecure($LANG['ads_description']); ?>
              </div>
              <div class="irow_box_right">
                <textarea name="ads_description" class="i_textarea flex_ border_one"></textarea>
              </div>
              <input type="hidden" name="adsFile" id="adsFile" value="">
            </div>

            <div class="warning_wrapper ppk_wraning"><?php echo iN_HelpSecure($LANG['you_must_fill_all_ads_to_public']); ?></div>
            <div class="warning_wrapper warning_one"><?php echo iN_HelpSecure($LANG['url_is_not_valid']); ?></div>
            <div class="warning_wrapper warning_two"><?php echo iN_HelpSecure($LANG['upload_ads_image_not']); ?></div>
            <div class="warning_wrapper warning_tree"><?php echo iN_HelpSecure($LANG['please_add_ads_title']); ?></div>
            <div class="i_settings_wrapper_item successNot"><?php echo html_entity_decode($LANG['add_saved']); ?></div>

            <div class="i_general_row_box_item flex_ tabing_non_justify">
              <input type="hidden" name="f" value="adsDForm">
              <button type="submit" name="submit" class="i_nex_btn_btn transition" id="adsDForm">
                <?php echo iN_HelpSecure($LANG['save_edit']); ?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>