<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('151'));?><?php echo iN_HelpSecure($LANG['earned_points']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
    <div class="payouts_form_container">
        <!---->
        <div class="next_payout">
            <div class="next_payout_title"><?php echo iN_HelpSecure($LANG['how_to_earn_points']);?></div>
            <div class="next_payout_not">
                <?php echo iN_HelpSecure($LANG['how_to_earn_not']);?>
                <?php echo html_entity_decode($LANG['how_to_earn_not_list']);?>
            </div>
        </div>
        <!---->
    <div class="point_earn_list_wrapper">
        <?php
           $pointFeatures = $iN->iN_GetUserEarnPointList($userID);
           if($pointFeatures){
              foreach($pointFeatures as $pda){
                  $iaType = $pda['i_af_type'];
                  $iaAmount = $pda['i_af_amount'];
                  if($iaType == 'comment'){
                     $tyIcon = $iN->iN_SelectedMenuIcon('20');
                  }else if($iaType == 'post_like'){
                    $tyIcon = $iN->iN_SelectedMenuIcon('18');
                  }else if($iaType == 'comment_like'){
                    $tyIcon = $iN->iN_SelectedMenuIcon('17');
                  }else {
                    $tyIcon = $iN->iN_SelectedMenuIcon('67');
                  }
                  $theEarnTitle = $iaType.'_earn';
                  $totalPointToday = (float) ($iN->iN_TodayEarnedPoint($userID, $iaType) ?? 0);
                  $totalPointAll = (float) ($iN->iN_CalculateTotalPointTypeEarningAll($userID, $iaType) ?? 0);
        ?>
            <!---->
            <div class="point_earn_box_cont">
                  <div class="point_earn_box_cont_in">
                      <div class="point_earn_icon_cont flex_ tabing">
                          <div class="point_earn_icon_wrp flex_ tabing"><?php echo html_entity_decode($tyIcon);?></div>
                      </div>
                      <div class="point_earn_footer">
                          <div class="point_earn_title_item"><?php echo iN_HelpSecure($LANG[$theEarnTitle]);?></div>
                          <div class="point_earn_list_wrp">
                              <div class="earn_title_point"><?php echo iN_HelpSecure($LANG['today_earned_point']);?> <?php echo iN_HelpSecure(number_format($totalPointToday, 2, ',', '.'));?></div>
                              <div class="earn_title_point"><?php echo iN_HelpSecure($LANG['total_earned_point']);?> <?php echo iN_HelpSecure(number_format($totalPointAll, 2, ',', '.'));?></div>
                          </div>
                      </div>
                  </div>
            </div>
            <!---->
        <?php } }?>
    </div>
    <div class="tabing flex_">
        <div class="iu_affilate_link">
            <div class="move_my_point_to_balance move_my_point_earn"><?php echo iN_HelpSecure($LANG['move_earnings_to_point_balance']);?></div>
        </div>
    </div>
    <div class="minimum_amount flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60'));?><?php echo iN_HelpSecure($LANG['not_for_point_earn']);?></div>
    <div class="i_settings_wrapper_item successNot">

    </div>
</div>
    </div>
  </div>
</div>
<?php
  $commentPCount = (float) ($iN->iN_CalculateTotalPointTypeEarningAll($userID, 'comment') ?? 0);
  $postPLikeCount = (float) ($iN->iN_CalculateTotalPointTypeEarningAll($userID, 'post_like') ?? 0);
  $commentPLikeCount = (float) ($iN->iN_CalculateTotalPointTypeEarningAll($userID, 'comment_like') ?? 0);
  $newPostPCount = (float) ($iN->iN_CalculateTotalPointTypeEarningAll($userID, 'new_post') ?? 0);
  $allTotalPoint = $commentPCount + $postPLikeCount + $newPostPCount + $commentPLikeCount;
?> 
<script>
  window.earnedPointData = {
    allTotal: "<?php echo $allTotalPoint; ?>",
    alertSuccessSelector: ".successNot"
  };
</script>
