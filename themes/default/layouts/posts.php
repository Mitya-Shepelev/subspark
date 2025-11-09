<div class="th_middle">
    <div class="greetalert hidden"></div>
    <div class="pageMiddle">
        <?php 
        // If user is not logged in, show welcome box
        if ($logedIn === '0') { 
            include __DIR__ . '/posts/welcomebox.php';
        } else {
            if ($page !== 'profile') {
                // Announcement box
                include __DIR__ . '/widgets/announcement.php';

                // Show stories if enabled
                if ($iN->iN_StoryData($userID, '1') === 'yes') {
                    include __DIR__ . '/storie/stories.php';
                }

                // Show post form if allowed
                if ($normalUserCanPost === 'yes' || $feesStatus === '2') {
                    include __DIR__ . '/posts/postForm.php';
                }
            }
        }

        // Random box logic (ads or suggested users)
        $files = ['suggestedusers', 'ads'];
        shuffle($files);
        include __DIR__ . '/random_boxs/' . iN_HelpSecure($files[0]) . '.php';

        // Post category (null-safe)
        $pCat = $pCat ?? null;

        // Boosted post display
        if ($boostedPostEnableDisable === 'yes' && $iN->iN_CheckHaveBoostedPostAllTheSite() > 0) {
            include __DIR__ . '/posts/boostedPost.php';
        }

        // Show pinned posts only on profile page
        if ($page === 'profile') {
            include __DIR__ . '/posts/pinedPosts.php';
        }

        // Posts output block
        ?>
        <div id="moreType" data-type="<?php echo iN_HelpSecure($page); ?>" data-po="<?php echo iN_HelpSecure($pCat); ?>">
            <?php include __DIR__ . '/posts/htmlPosts.php'; ?>
        </div>
    </div>
</div>
