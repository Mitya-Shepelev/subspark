<div class="i_sub_box_container" id="<?php echo iN_HelpSecure($followingUserID); ?>" data-last="<?php echo iN_HelpSecure($followingID); ?>">
    <div class="i_sub_box_wrp_prof flex_">
        <!-- User Avatar -->
        <div class="i_sub_box_avatar">
            <img 
                class="isubavatar" 
                src="<?php echo iN_HelpSecure($flUUserAvatar); ?>" 
                alt="<?php echo iN_HelpSecure($flUUserFullName); ?>" 
                loading="lazy"
            >
        </div>

        <!-- User Info -->
        <div class="i_sub_box_name_time">
            <div class="i_sub_box_name">
                <a 
                    href="<?php echo iN_HelpSecure($base_url . $flUUserName); ?>" 
                    aria-label="<?php echo iN_HelpSecure($flUUserFullName); ?>"
                >
                    <?php echo iN_HelpSecure($flUUserFullName); ?>
                </a>
            </div>
            <div class="i_sub_box_unm">
                @<?php echo iN_HelpSecure($flUUserName); ?>
            </div>
        </div>

        <!-- Follow Button -->
        <div class="i_sub_flw">
            <div 
                class="i_follow flex_ tabing i_fw<?php echo iN_HelpSecure($followingUserID); ?> <?php echo html_entity_decode($flwrBtn); ?> transition unSubU" 
                id="i_btn_like_item" 
                data-u="<?php echo iN_HelpSecure($followingUserID); ?>" 
                role="button" 
                tabindex="0" 
                aria-pressed="false" 
                aria-label="Follow/Unfollow"
            >
                <?php echo html_entity_decode($flwBtnIconText); ?>
            </div>
        </div>
    </div>
</div>