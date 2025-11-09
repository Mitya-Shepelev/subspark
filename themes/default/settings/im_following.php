<?php
$totalPages = ceil($totalFollowingUsers / $paginationLimit);
if (isset($_GET["page-id"])) {
	$pagep = (int)$_GET["page-id"];
	if ($pagep <= 0) { $pagep = 1; }
} else {
	$pagep = 1;
}
?>
<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('139')); ?><?php echo iN_HelpSecure($LANG['you_are_following']) . '(' . $totalFollowingUsers . ')'; ?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['you_are_following_not']); ?></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container i_tab_padding">
       <?php
            $followingUsers = $iN->iN_FollowingUsersListPage($userID, $paginationLimit, $pagep);
            if ($followingUsers) {
            	foreach ($followingUsers as $flU) {
                  $followingUserID = $flU['fr_two'];
                  $followingUserData = $iN->iN_GetUserDetails($followingUserID);
                  $flUUserName = $followingUserData['i_username'] ?? null;
                  $flUUserFullName = $followingUserData['i_user_fullname'] ?? null;
                  $flUUserAvatar = $iN->iN_UserAvatar($followingUserID, $base_url);
            		$getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $followingUserID);
            		if ($getFriendStatusBetweenTwoUser == 'flwr') {
            			$flwrBtn = 'i_btn_like_item_flw f_p_follow';
            			$flwBtnIconText = $iN->iN_SelectedMenuIcon('66') . $LANG['unfollow'];
            		} else {
            			$flwrBtn = 'i_btn_like_item free_follow';
            			$flwBtnIconText = $iN->iN_SelectedMenuIcon('66') . $LANG['follow'];
            		}
		?>
            <!--SUBSCRIBER-->
              <div class="i_sub_box_container">
                 <div class="i_sub_box_wrp flex_">
                    <div class="i_sub_box_avatar">
                        <img class="isubavatar" src="<?php echo iN_HelpSecure($flUUserAvatar); ?>">
                    </div>
                       <div class="i_sub_box_name_time">
                        <div class="i_sub_box_name"><a href="<?php echo iN_HelpSecure($base_url . $flUUserName); ?>"><?php echo iN_HelpSecure($flUUserFullName); ?></a></div>
                        <div class="i_sub_box_unm">@<?php echo iN_HelpSecure($flUUserName); ?></div>
                       </div>
                    <div class="i_sub_flw"><div class="i_follow flex_ tabing i_fw<?php echo iN_HelpSecure($followingUserID); ?> <?php echo html_entity_decode($flwrBtn); ?> transition unSubU" id="i_btn_like_item" data-u="<?php echo iN_HelpSecure($followingUserID); ?>"><?php echo html_entity_decode($flwBtnIconText); ?></div></div>
                 </div>
              </div>
            <!--/SUBSCRIBER-->
        <?php }} else {echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . $LANG['no_one_you_subscribed'] . '</div></div>';}?>
        </div>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <?php if (ceil($totalFollowingUsers / $paginationLimit) > 0): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo iN_HelpSecure($pagep) - 1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a></li>
                <?php endif;?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif;?>
                <?php if ($pagep - 2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a></li><?php endif;?>
                <?php if ($pagep - 1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a></li><?php endif;?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep + 1 < ceil($totalFollowingUsers / $paginationLimit) + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li><?php endif;?>
                <?php if ($pagep + 2 < ceil($totalFollowingUsers / $paginationLimit) + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a></li><?php endif;?>

                <?php if ($pagep < ceil($totalFollowingUsers / $paginationLimit) - 2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo ceil($totalFollowingUsers / $paginationLimit); ?>"><?php echo ceil($totalFollowingUsers / $paginationLimit); ?></a></li>
                <?php endif;?>

                <?php if ($pagep < ceil($totalFollowingUsers / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=im_following&page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a></li>
                <?php endif;?>
            </ul>
        <?php endif;?>
     </div>
  </div>
</div> 
