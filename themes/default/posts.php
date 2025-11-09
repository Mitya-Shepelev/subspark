<div class="th_middle">
    <div class="pageMiddle">
        <?php 
        // Show welcome box if user is not logged in
        if ($logedIn == 0) {
            include("posts/welcomebox.php");
        } else {
            // Only show post form if not on profile page
            if ($page != 'profile') {
                echo html_entity_decode($verStatus);

                // Check if user is allowed to post
                if ($normalUserCanPost == 'yes') {
                    include("posts/postForm.php");
                } elseif ($feesStatus == '2') {
                    include("posts/postForm.php");
                }
            }
        }

        // Load a random suggestion box (e.g., suggestedusers)
        $files = array(
            1 => 'suggestedusers'
        );
        shuffle($files);

        for ($i = 0; $i < 1; $i++) {
            include "random_boxs/{$files[$i]}.php";
        }

        // Show current live streams if Agora is active and not on profile page
        if ($agoraStatus == '1' && $page != 'profile') {
            include("live/current_live_streamings.php");
        }

        // Load main post HTML content
        echo '<div id="moreType" data-type="' . $page . '">';
        include("posts/htmlPosts.php");
        echo '</div>';
        ?>
    </div>
</div>