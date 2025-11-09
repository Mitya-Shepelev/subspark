<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['giphy_settings']); ?>
        </div>

        <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="updateGiphy">
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_">
                        <?php echo iN_HelpSecure($LANG['g_api_key']); ?>
                    </div>
                    <div class="irow_box_right">
                        <input type="text" name="giphyKey" class="i_input flex_" value="<?php echo iN_HelpSecure($giphyKey); ?>">
                        <div class="rec_not box_not_padding_top">
                            <a href="https://developers.giphy.com/dashboard/" target="_blank">https://developers.giphy.com/dashboard/</a>
                        </div>
                    </div>
                </div>

                <div class="i_settings_wrapper_item successNot">
                    <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
                </div>

                <div class="admin_approve_post_footer">
                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="updateGiphy">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
