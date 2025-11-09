<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="languageModalTitle">
    <div class="i_modal_in_in i_sf_box">
        <div class="i_modal_content">
            <div class="purchase_premium_header flex_ tabing border_top_radius mp" id="languageModalTitle">
                <?php echo iN_HelpSecure($LANG['choose_language']); ?>
            </div>

            <div class="purchase_post_details tabing">
                <?php
                if ($languages) {
                    foreach ($languages as $langData) {
                        $languageID = $langData['lang_id'];
                        $languageName = $langData['lang_name'];
                        ?>
                        <div class="payment_method_box chLang textStyle flex_ transition" id="<?php echo iN_HelpSecure($languageID); ?>" role="button" aria-label="<?php echo iN_HelpSecure($LANGNAME[$languageName]); ?>">
                            <div class="i_block_choose">
                                <div class="block_a_status <?php echo ($userLang === $languageName) ? 'blockboxActive' : 'blockboxPassive'; ?>"></div>
                            </div>
                            <?php echo iN_HelpSecure($LANGNAME[$languageName]); ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <div class="i_modal_g_footer">
                <div class="alertBtnLeft no-del transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
        </div>
    </div>
</div>