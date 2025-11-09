<div class="settings_main_wrapper">
    <div class="i_settings_wrapper_in i_inline_table">
        <div class="i_settings_wrapper_title">
            <div class="i_settings_wrapper_title_txt flex_">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('138')); ?>
                <?php echo iN_HelpSecure($LANG['block_country']); ?>
            </div>
            <div class="i_moda_header_nt">
                <?php echo iN_HelpSecure($LANG['block_country_note']); ?>
            </div>
        </div>
        <div class="i_settings_wrapper_items">
            <div class="nonePoint">
                <?php
                $previous = null;
                foreach ($COUNTRIES as $value => $o) {
                    $firstLetter = substr($iN->url_slugies($o), 0, 1);
                    if ($previous !== $firstLetter) {
                        if ($value != '0') {
                            echo '</div><div class="i_first_letter"><div class="i_a_body"><div class="i_h_in">' . strtoupper($firstLetter) . '</div></div></div><div class="i_b_country_container">';
                        }
                    }
                    $previous = $firstLetter;
                    if ($value != '0') {
                        $cbClass = '';
                        if ($iN->iN_CheckCountryBlocked($userID, $value) == 1) {
                            $cbClass = 'chsed';
                        }
                        echo '<div class="i_block_country_item transition bCountry ' . $cbClass . '" id="' . $value . '" data-c="' . $value . '">' . $o . '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>