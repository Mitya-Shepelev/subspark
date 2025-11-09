<div class="th_middle">
    <div class="pageMiddle">
        <?php
        // Check if the 'live' GET parameter exists and is not empty
        if (isset($_GET['live']) && !empty($_GET['live'])) {
            // Secure the input using a custom safe function (preferred over mysqli_real_escape_string)
            $liveListType = iN_HelpSecure($_GET['live']);

            // Determine which live stream list to include based on the type
            switch ($liveListType) {
                case 'paid':
                    include 'live/paidLiveStreamingList.php';
                    break;

                case 'free':
                    include 'live/freeLiveStreamingList.php';
                    break;

                case 'both':
                    include 'live/allLiveStreamingList.php';
                    break;

                default:
                    // Redirect to base URL if invalid type
                    header('Location: ' . route_url(''));
                    exit;
            }
        }
        ?>
    </div>
</div>
