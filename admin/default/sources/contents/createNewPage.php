<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify">
    <div class="i_general_title_box">
      <?php echo iN_HelpSecure($LANG['edit_package']); ?>
    </div>

    <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
      <form enctype="multipart/form-data" method="post" id="createNewPage">
        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['page_title']); ?>
          </div>
          <div class="irow_box_right">
            <input type="text" name="page_title" class="i_input flex_">
          </div>
        </div>
        <div class="warning_wrapper warning_one">
          <?php echo iN_HelpSecure($LANG['page_title_please']); ?>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['seo_url']); ?>
          </div>
          <div class="irow_box_right">
            <input type="text" name="page_seo_url" class="i_input flex_">
          </div>
        </div>
        <div class="warning_wrapper warning_two">
          <?php echo iN_HelpSecure($LANG['seo_url_please']); ?>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['page_content']); ?>
          </div>
          <div class="irow_box_right">
            <textarea name="editor" id="editor"></textarea>
          </div>
        </div>

        <div class="admin_approve_post_footer">
          <div class="i_become_creator_box_footer">
            <input type="hidden" name="f" value="createNewPage">
            <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
              <?php echo iN_HelpSecure($LANG['save_edit']); ?>
            </button>
          </div>
        </div>
      </form>
      <script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/tinymce/tinymce.min.js"></script>
      <script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/tinymce/tinyMceHandle.js"></script>
    </div>
  </div>
</div>