<div class="Message_stickersContainer">
    <div class="Message_stickers_wrapper">
        <div class="giphy_results_container_conversation">
            <?php
            if (!empty($giphyKey)) {
                $giphyTrendKey = 'covid';
                $apiUrl = 'https://api.giphy.com/v1/gifs/trending?api_key=' . urlencode($giphyKey) . '&limit=25&rating=pg';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                $response = curl_exec($ch);

                if (curl_errno($ch) || !$response) {
                    echo '<div class="no-gif-found">' . $LANG['could_not_fined_gif'] . '</div>';
                    curl_close($ch);
                    exit();
                }

                curl_close($ch);
                $json = json_decode($response);

                if (!isset($json->data) || !is_array($json->data) || count($json->data) === 0) {
                    echo '<div class="no-gif-found">' . $LANG['could_not_fined_gif'] . '</div>';
                    exit();
                }

                foreach ($json->data as $gif) {
                    $giphyImageUrl = $gif->images->fixed_height->url ?? '';
                    if ($giphyImageUrl) {
                        echo '<img class="mrGif transition" data-id="' . iN_HelpSecure($id) . '" src="' . iN_HelpSecure($giphyImageUrl) . '">';
                    }
                }
            } else {
                echo iN_HelpSecure($LANG['please_add_giphy_api_key_from_admin']);
            }
            ?>
        </div>
    </div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>themes/<?php echo iN_HelpSecure($currentTheme);?>/js/slimscroll.js?v=<?php echo iN_HelpSecure($version);?>"></script>
</div>
