<div class="th_middle">
    <div class="pageMiddle">
        <?php
        // Output main content container with dynamic post type
        echo '<div id="moreType" data-type="' . $page . '">';

        // Display saved posts header
        echo '<div class="i_postSavedHeader isave_svg tabing_non_justify flex_">'
            . $iN->iN_SelectedMenuIcon('63')
            . $LANG['saved_items']
            . '</div>';

        // Include post list for saved items
        include("posts/htmlPosts.php");

        // Close main content container
        echo '</div>';
        ?>
    </div>
</div>