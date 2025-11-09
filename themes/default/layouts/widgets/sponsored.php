<?php
$numberShow = rand(1, $showNumberOfAds);
 $activeAds = $iN->iN_ShowAds($numberShow);
 if($activeAds){
?>
<div class="sp_wrp">
<div class="suggested_products">
<div class="i_right_box_header">
<?php echo $LANG['sponsored'];?>
</div>
<div class="i_sponsorad">
<?php
foreach($activeAds as $aAds){
  $activeAdsTitle = $aAds['ads_title'];
  $activeAdsImage = $aAds['ads_image'];
  $activeAdsUrl = $aAds['ads_url'];
  $activeAdsDescription = $aAds['ads_desc'];
  $adsImageUrl = $activeAdsImage;
?>
<!--SPONSORED ADS-->
<a href="<?php echo html_entity_decode($activeAdsUrl);?>" target="blank_" class="transition">
    <div class="i_sponsored_container">
        <div class="i_sponsored_image">
            <img src="<?php echo html_entity_decode($adsImageUrl);?>"/>
        </div>
        <div class="i_sponsored_title_and_desc">
            <div class="i_sponsored_title">
                <?php echo iN_HelpSecure($activeAdsTitle);?>
            </div>
            <div class="i_sponsored_ads_link">
                <?php echo iN_HelpSecure($iN->iN_getHost($activeAdsUrl));?>
            </div>
        </div>
    </div>
    </a>
    <!--/SPONSORED ADS-->
<?php }?>
 </div></div></div>
<?php }
?>