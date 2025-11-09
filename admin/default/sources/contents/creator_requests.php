<?php
$totalUsers = $iN->iN_TotalVerificationRequests();
$totalPages = ceil($totalUsers / $paginationLimit);

if (isset($_GET["page-id"])) {
    $pagep = isset($_GET["page-id"]) ? (int)$_GET["page-id"] : 1;
    $pagep = preg_match('/^[0-9]+$/', $pagep) ? $pagep : '1';
} else {
    $pagep = '1';
}
?>
<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify">
    <div class="i_general_title_box">
      <?php echo iN_HelpSecure($LANG['creator_requests']) . ' (' . $totalUsers . ')'; ?>
    </div>

    <div class="i_general_row_box column flex_" id="general_conf">
      <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>

      <?php
      $allUsers = $iN->iN_AllVerficationRequestList($userID, $paginationLimit, $pagep);
      if ($allUsers) {
      ?>
      <div class="i_overflow_x_auto">
        <table class="border_one">
          <tr>
            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
            <th><?php echo iN_HelpSecure($LANG['user']); ?></th>
            <th><?php echo iN_HelpSecure($LANG['request_time']); ?></th>
            <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
          </tr>
          <?php
          foreach ($allUsers as $vData) {
              $vID = $vData['request_id'] ?? null;
              $verificationRequestedUserID = $vData['iuid_fk'] ?? null;
              $uData = $iN->iN_GetUserDetails($verificationRequestedUserID);
              $userUserName = $uData['i_username'] ?? null;
              $userUserFullName = $uData['i_user_fullname'] ?? null;
              $userAvatar = $iN->iN_UserAvatar($verificationRequestedUserID, $base_url);
              $userRegisteredTime = $vData['request_time'] ?? null;
              $crTime = date('Y-m-d H:i:s', $userRegisteredTime);
              $seeProfile = $base_url . $userUserName;
              $seeEditProfile = $base_url . 'admin/creator_requests?vID=' . $vID;
          ?>
          <tr class="transition trhover">
            <td><?php echo iN_HelpSecure($vID); ?></td>
            <td>
              <div class="t_od flex_ c6">
                <div class="t_owner_avatar border_two tabing flex_">
                  <img src="<?php echo iN_HelpSecure($userAvatar); ?>">
                </div>
                <div class="t_owner_user tabing flex_">
                  <a class="truncated" href="<?php echo iN_HelpSecure($seeProfile); ?>">
                    <?php echo iN_HelpSecure($userUserFullName); ?>
                  </a>
                </div>
              </div>
            </td>
            <td class="see_post_details">
              <div class="flex_ tabing_non_justify">
                <div class="tim flex_ tabing">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73')) . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
                </div>
              </div>
            </td>
            <td class="flex_ tabing_non_justify">
              <div class="flex_ tabing_non_justify">
                <div class="delu del_verf border_one transition tabing flex_" id="<?php echo iN_HelpSecure($vID); ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                </div>
                <div class="seePost c4 border_one transition tabing flex_" id="<?php echo iN_HelpSecure($vID); ?>">
                  <a class="tabing flex_" href="<?php echo iN_HelpSecure($seeEditProfile); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('10')) . iN_HelpSecure($LANG['check_request']); ?>
                  </a>
                </div>
                <div class="seePost c3 border_one transition tabing flex_" id="<?php echo iN_HelpSecure($vID); ?>">
                  <a class="tabing flex_" href="<?php echo iN_HelpSecure($seeProfile); ?>" target="blank_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('83')) . iN_HelpSecure($LANG['see_profile']); ?>
                  </a>
                </div>
              </div>
            </td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php } else {
        echo '<div class="no_creator_f_wrap flex_ tabing">
                <div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div>
                <div class="n_c_t">' . $LANG['no_user_found'] . '</div>
              </div>';
      } ?>
    </div>

    <div class="i_become_creator_box_footer tabing">
      <?php if ($totalPages > 0): ?>
      <ul class="pagination">
        <?php if ($pagep > 1): ?>
        <li class="prev">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $pagep - 1; ?>">
            <?php echo iN_HelpSecure($LANG['preview_page']); ?>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($pagep > 3): ?>
        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=1">1</a></li>
        <li class="dots">...</li>
        <?php endif; ?>

        <?php if ($pagep - 2 > 0): ?>
        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $pagep - 2; ?>"><?php echo $pagep - 2; ?></a></li>
        <?php endif; ?>

        <?php if ($pagep - 1 > 0): ?>
        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $pagep - 1; ?>"><?php echo $pagep - 1; ?></a></li>
        <?php endif; ?>

        <li class="currentpage active">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $pagep; ?>"><?php echo $pagep; ?></a>
        </li>

        <?php if ($pagep + 1 <= $totalPages): ?>
        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $pagep + 1; ?>"><?php echo $pagep + 1; ?></a></li>
        <?php endif; ?>

        <?php if ($pagep + 2 <= $totalPages): ?>
        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $pagep + 2; ?>"><?php echo $pagep + 2; ?></a></li>
        <?php endif; ?>

        <?php if ($pagep < $totalPages - 2): ?>
        <li class="dots">...</li>
        <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
        <?php endif; ?>

        <?php if ($pagep < $totalPages): ?>
        <li class="next">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/creator_requests?page-id=<?php echo $pagep + 1; ?>">
            <?php echo iN_HelpSecure($LANG['next_page']); ?>
          </a>
        </li>
        <?php endif; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
