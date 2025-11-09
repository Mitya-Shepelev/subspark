<div class="Message_stickersContainer">
    <div class="Message_stickers_wrapper">
        <?php 
            $dataStickers = $iN->iN_GetActiveStickers();
            if($dataStickers){
                foreach($dataStickers as $dSticker){
                    $stickerID = $dSticker['sticker_id'];
                    $stickerURL = $dSticker['sticker_url'];
                    echo '
                    <div class="sticker transition MaddSticker" id="'.$stickerID.'" data-id="'.$id.'">
                        <img src="'.$stickerURL.'">
                    </div>'
                    ;
                }
            }
        ?>
    </div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>themes/<?php echo iN_HelpSecure($currentTheme);?>/js/slimscroll.js?v=<?php echo iN_HelpSecure($version);?>"></script>
</div>