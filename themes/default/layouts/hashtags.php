<div class="th_middle">
    <div class="pageMiddle">
        <?php
        // Get the talking about value from the database
        $talingAbout = $iN->iN_CaltulateHashFromDatabase($pageFor);

        // Prepare post container with type and hash
        $hash = $iN->url_Hash($pageFor);
        ?>
        <div id="moreType" data-type="<?php echo iN_HelpSecure($page); ?>" data-hash="<?php echo iN_HelpSecure($hash); ?>">
            <div class="i_postSavedHeader isave_svg">
                <span class="isave_svg tabing_non_justify flex_">
                    <?php echo $iN->iN_SelectedMenuIcon('135') . iN_HelpSecure($pageFor); ?>
                </span>
                <div class="i_postHashHeader tabing_non_justify flex_">
                    <?php
                    echo iN_HelpSecure(
                        preg_replace(
                            '/{.*?}/',
                            $talingAbout,
                            $LANG['talking_about']
                        )
                    );
                    ?>
                </div>
            </div>
            <?php include 'posts/htmlPosts.php'; ?>
        </div>
    </div>
</div>