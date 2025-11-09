<?php
$socialNet = $iN->iN_ShowWebsiteSocialSitesList();
if($socialNet){
    foreach($socialNet as $snet){
        $soID = $snet['id'];
        $sKey = $snet['skey'];
        $sPlaceHolder = $snet['place_holder'];
        $socialIcon = $snet['social_icon'];
?>
    <div class="i_wsocial_link_ flex_">
        <div class="i_iw_social_icon flex_ tabing"><a href="<?php echo filter_var($sPlaceHolder , FILTER_VALIDATE_URL);?>" target="blank_" rel="nofollow"><div class="iisocialicon flex_ tabing"><?php echo html_entity_decode($socialIcon);?></div></a></div>
    </div>
<?php }} ?>