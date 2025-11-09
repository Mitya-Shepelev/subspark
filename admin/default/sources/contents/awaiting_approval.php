<?php
$totalnonApprovedPosts = $iN->iN_CalculateNonApprovedPosts();
$totalPages = ceil($totalnonApprovedPosts / $paginationLimit);

if (isset($_GET['page-id'])) {
    $pagep = isset($_GET['page-id']) ? (int)$_GET['page-id'] : 1;
    $pagep = preg_match('/^[0-9]+$/', $pagep) ? $pagep : '1';
} else {
    $pagep = '1';
}
?>
<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify">
    <div class="i_general_title_box">
      <?php echo iN_HelpSecure($LANG['awaiting_approval_posts']) . '(' . $totalnonApprovedPosts . ')'; ?>
    </div>

    <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
      <div class="warning_">
        <?php echo iN_HelpSecure($LANG['noway_desc']); ?>
      </div>

      <?php
      $nonApprovedPosts = $iN->iN_aWaitingApprovalOrApprovedPostsList($userID, $paginationLimit, $pagep);
      if ($nonApprovedPosts) {
      ?>
      <div class="i_overflow_x_auto">
        <table class="border_one">
          <tr>
            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
            <th><?php echo iN_HelpSecure($LANG['username']); ?></th>
            <th><?php echo iN_HelpSecure($LANG['status']); ?></th>
            <th><?php echo iN_HelpSecure($LANG['approve_or_decline']); ?></th>
          </tr>
          <?php foreach ($nonApprovedPosts as $nonPostData) {
              $postID = $nonPostData['post_id'] ?? null;
              $postOwnerID = $nonPostData['post_owner_id'] ?? null;
              $postOwnerAvatar = $iN->iN_UserAvatar($postOwnerID, $base_url);
              $postOwnerUserName = $nonPostData['i_username'] ?? null;
              $postOwnerUserFullName = $nonPostData['i_user_fullname'] ?? null;
              $postStatus = $nonPostData['post_status'] ?? null;

              if ($postStatus == '1') {
                  $pStatus = '<div class="flex_ tabing forpending c8 box_max_width">' . $LANG['approved_'] . '</div>';
              } elseif ($postStatus == '2') {
                  $pStatus = '<div class="flex_ tabing forpending c1 box_max_width">' . $LANG['pending_approve'] . '</div>';
              }
          ?>
          <tr>
            <td><?php echo iN_HelpSecure($postID); ?></td>
            <td>
              <div class="t_od flex_ c6">
                <div class="t_owner_avatar border_two tabing flex_">
                  <img src="<?php echo iN_HelpSecure($postOwnerAvatar); ?>">
                </div>
                <div class="t_owner_user tabing flex_">
                  <a class="truncated" href="<?php echo iN_HelpSecure($base_url) . $postOwnerUserName; ?>">
                    <?php echo iN_HelpSecure($postOwnerUserFullName); ?>
                  </a>
                </div>
              </div>
            </td>
            <td><?php echo html_entity_decode($pStatus); ?></td>
            <td class="see_post_details">
              <a class="border_one" href="<?php echo iN_HelpSecure($base_url) . 'admin/awaiting_approval?post=' . $postID; ?>">
                <?php echo iN_HelpSecure($LANG['see_post']); ?>
              </a>
            </td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php } else {
          echo '<div class="no_creator_f_wrap flex_ tabing">
                  <div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div>
                  <div class="n_c_t">' . $LANG['no_post_pending_approval'] . '</div>
                </div>';
      } ?>
    </div>

    <div class="i_become_creator_box_footer tabing">
      <?php if ($totalPages > 0): ?>
      <ul class="pagination">
        <?php if ($pagep > 1): ?>
        <li class="prev">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $pagep - 1; ?>">
            <?php echo iN_HelpSecure($LANG['preview_page']); ?>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($pagep > 3): ?>
        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=1">1</a></li>
        <li class="dots">...</li>
        <?php endif; ?>

        <?php if ($pagep - 2 > 0): ?>
        <li class="page">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $pagep - 2; ?>">
            <?php echo $pagep - 2; ?>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($pagep - 1 > 0): ?>
        <li class="page">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $pagep - 1; ?>">
            <?php echo $pagep - 1; ?>
          </a>
        </li>
        <?php endif; ?>

        <li class="currentpage active">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $pagep; ?>">
            <?php echo $pagep; ?>
          </a>
        </li>

        <?php if ($pagep + 1 <= $totalPages): ?>
        <li class="page">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $pagep + 1; ?>">
            <?php echo $pagep + 1; ?>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($pagep + 2 <= $totalPages): ?>
        <li class="page">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $pagep + 2; ?>">
            <?php echo $pagep + 2; ?>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($pagep < $totalPages - 2): ?>
        <li class="dots">...</li>
        <li class="end">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $totalPages; ?>">
            <?php echo $totalPages; ?>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($pagep < $totalPages): ?>
        <li class="next">
          <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/awaiting_approval?page-id=<?php echo $pagep + 1; ?>">
            <?php echo iN_HelpSecure($LANG['next_page']); ?>
          </a>
        </li>
        <?php endif; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
