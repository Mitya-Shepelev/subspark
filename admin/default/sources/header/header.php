<div class="hitHeader border_one flex_">
    <div class="tabing flex_ border_two clps">
        <div class="collapse_left">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('100')); ?>
        </div>
    </div>
    <div class="header_right_menu flex_ tabing">
        <div class="header_right_item flex_ tabing">
            <a href="<?php echo iN_HelpSecureUrl(filter_var($base_url)); ?>">
                <div class="item_icon border_two flex_ tabing">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('99')); ?>
                </div>
            </a>
        </div>
        <div class="header_right_item">
            <a href="<?php echo iN_HelpSecureUrl(filter_var($base_url . $userName)); ?>">
                <div class="item_icon border_two flex_ tabing">
                    <img src="<?php echo iN_HelpSecureUrl(filter_var($userAvatar)); ?>" alt="Avatar" loading="lazy">
                </div>
            </a>
        </div>
    </div>
</div>