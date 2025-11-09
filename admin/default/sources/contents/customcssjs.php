<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify">
    <div class="i_general_title_box">
      <?php echo iN_HelpSecure($LANG['custom_css_js']);?>
    </div>

    <div class="i_general_row_box column flex_ box_not_padding_top_package" id="general_conf">
      <form enctype="multipart/form-data" method="post" id="customCodes">
        <div class="net_earn_title flex_ tabing_non_justify margin_bottom_custom_css_js box_custom_padding_left">
          <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('118'));?>
          <?php echo iN_HelpSecure($LANG['header_custom_css_styles']);?>
        </div>
        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <textarea name="customCss" id="custom-css"><?php echo iN_SecureTextareaOutput($customHeaderCSSCode);?></textarea>
        </div>

        <div class="net_earn_title flex_ tabing_non_justify margin_bottom_custom_css_js box_custom_padding_left">
          <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('119'));?>
          <?php echo iN_HelpSecure($LANG['header_custom_javascript']);?>
        </div>
        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <textarea name="customHeaderJs" id="custom-js"><?php echo iN_SecureTextareaOutput($customHeaderJsCode);?></textarea>
        </div>

        <div class="net_earn_title flex_ tabing_non_justify margin_bottom_custom_css_js box_custom_padding_left">
          <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('119'));?>
          <?php echo iN_HelpSecure($LANG['footer_custom_javascript']);?>
        </div>
        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <textarea name="customFooterJs" id="customfooter-js"><?php echo iN_SecureTextareaOutput($customFooterJsCode);?></textarea>
        </div>

        <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['saved_successfully']);?></div>
        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <input type="hidden" name="f" value="customCodes">
          <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
            <?php echo iN_HelpSecure($LANG['save_edit']);?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/customCodesEditor.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>