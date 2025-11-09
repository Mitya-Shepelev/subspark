<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['fake_user_generator']); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="generateFakeUser">
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_">
                        <?php echo iN_HelpSecure($LANG['how_many_users_you_want_to_generate']); ?>
                    </div>
                    <div class="irow_box_right">
                        <input type="text" name="n" class="i_input flex_" value="10">
                        <div class="rec_not box_not_padding_top">
                            <?php echo iN_HelpSecure($LANG['min_u_r']); ?>
                        </div>
                    </div>
                </div>
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_">
                        <?php echo iN_HelpSecure($LANG['password']); ?>
                    </div>
                    <div class="irow_box_right">
                        <input type="text" name="p" class="i_input flex_" value="123456789">
                        <div class="rec_not box_not_padding_top">
                            <?php echo iN_HelpSecure($LANG['choose_password']); ?>
                        </div>
                    </div>
                </div>
                <div class="i_settings_wrapper_item successNot">
                    <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
                </div>
                <div class="admin_approve_post_footer">
                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="fake_generaator">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/fakeUserGeneratorHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>