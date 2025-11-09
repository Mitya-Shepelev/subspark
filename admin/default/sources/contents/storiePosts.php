<?php
$totalnApprovedPosts = $iN->iN_CalculateAllStoriePosts();
$totalPages = ceil($totalnApprovedPosts / $paginationLimit);
if (isset($_GET["page-id"])) {
    $pagep  = isset($_GET["page-id"]) ? (int)$_GET["page-id"] : 1;
    if(preg_match('/^[0-9]+$/', $pagep)){
        $pagep = $pagep;
    }else{
        $pagep = '1';
    }
}else{
    $pagep = '1';
}
?>
<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <!---->
        <div class="i_general_title_box">
          <?php echo iN_HelpSecure($LANG['manage_storie_posts']).'('.$totalnApprovedPosts.')';?>
        </div>
        <!---->
        <!---->
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
        <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']);?></div>
        <?php
        $ApprovedPosts = $iN->iN_AllTypeStoriePostsList($userID, $paginationLimit, $pagep);
        if($ApprovedPosts){
        ?>
        <div class="i_overflow_x_auto">
                <table class="border_one">
                    <tr>
                        <th><?php echo iN_HelpSecure($LANG['id']);?></th>
                        <th><?php echo iN_HelpSecure($LANG['post_owner']);?></th>
                        <th><?php echo iN_HelpSecure($LANG['post_shared_time']);?></th>
                        <th><?php echo iN_HelpSecure($LANG['see_post']);?></th>
                        <th><?php echo iN_HelpSecure($LANG['status']);?></th>
                        <th><?php echo iN_HelpSecure($LANG['actions']);?></th>
                    </tr>
        <?php
        foreach($ApprovedPosts as $postData){
            $postID = $postData['s_id'] ?? null;
            $postOwnerID = $postData['uid_fk'] ?? null;
            $postOwnerAvatar = $iN->iN_UserAvatar($postOwnerID, $base_url);
            $postOwnerUserName = $postData['i_username'] ?? null;
            $postOwnerUserFullName = $postData['i_user_fullname'] ?? null;
            $postCreatedTime = $postData['created'] ?? null;
            $postStatus = $postData['status'] ?? null;
            $crTime = date('Y-m-d H:i:s',$postCreatedTime);
            $dif = time() - $postCreatedTime;
            $bir = date('Y-m-d H:i:s',$dif);
            $cDate = strtotime(date('Y-m-d H:i:s'));
            $oldDate = $postCreatedTime + 86400;

            if($postStatus == '1'){
                $timeTest = $LANG['continues_to_show'];
                $psClass = 'p_s_none_published';
                $p_Status = '<div class="'.$psClass.' flex_ tabing">'.$iN->iN_SelectedMenuIcon('123').$LANG['not_published_yet'].'</div>';
            }else if($postStatus == '2'){
                if($oldDate > $cDate){
                    $timeTest =  $LANG['continues_to_show'];
                    $statusIcon = $iN->iN_SelectedMenuIcon('154').$LANG['active'];
                    $psClass = 'p_s_active';
                }else{
                    $timeTest = $LANG['story_was_shown'];
                    $statusIcon = $iN->iN_SelectedMenuIcon('115').$timeTest;
                    $psClass = 'p_s_passed';
                }
                $p_Status = '<div class="'.$psClass.' flex_ tabing">'.$statusIcon.'</div>';
            }
            $seePostButton = $base_url.'admin/storiePosts?post='.$postID;
            $storieUploadedFilePath = $postData['uploaded_file_path'] ?? null;
            $storieUploadedfileExtension = $postData['uploaded_file_ext'] ?? null;
            $storieUploadedFileTumbnail = $postData['upload_tumbnail_file_path'] ?? null;
            $storieText = $postData['text'];
            if ($storieUploadedfileExtension == 'mp4') {
                if (function_exists('storage_public_url')) {
                    $filePathUrl = storage_public_url($storieUploadedFileTumbnail ?: $storieUploadedFilePath);
                } else {
                    $filePathUrl = $base_url . ($storieUploadedFileTumbnail ?: $storieUploadedFilePath);
                }
            } else {
                if (function_exists('storage_public_url')) {
                    $filePathUrl = storage_public_url($storieUploadedFileTumbnail ?: $storieUploadedFilePath);
                } else {
                    $filePathUrl = $base_url . ($storieUploadedFileTumbnail ?: $storieUploadedFilePath);
                }
            }
        ?>
        <tr class="transition trhover body_<?php echo iN_HelpSecure($postID);?>">
            <td><?php echo iN_HelpSecure($postID);?></td>
            <td>
                <div class="t_od flex_ c6">
                    <div class="t_owner_avatar border_two tabing flex_"><img src="<?php echo iN_HelpSecure($postOwnerAvatar);?>"></div>
                    <div class="t_owner_user tabing flex_"><a class="truncated" href="<?php echo iN_HelpSecure($base_url).$postOwnerUserName;?>"><?php echo iN_HelpSecure($postOwnerUserFullName);?></a></div>
                </div>
            </td>
            <td class="see_post_details"><div class="flex_ tabing_non_justify"><div class="tim flex_ tabing"><?php echo iN_HelpSecure($iN->iN_SelectedMenuIcon('73')).' '.TimeAgo::ago($crTime , date('Y-m-d H:i:s'));?></div></div></td>
            <td class="see_post_details">
                <div class="flex_ tabing_non_justify see_post lightgallery" data-id="<?php echo iN_HelpSecure($postID); ?>" data-src="<?php echo $filePathUrl; ?>">
                    <img src="<?php echo $filePathUrl; ?>" alt="">
                </div>
            </td>
            <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo html_entity_decode($p_Status);?></div></td>
            <td class="flex_ tabing">
                <div class="flex_ tabing_non_justify">
                    <div class="delps border_one transition" id="<?php echo iN_HelpSecure($postID);?>"><?php echo iN_HelpSecure($iN->iN_SelectedMenuIcon('28')).$LANG['delete'];?></div>
                </div>
            </td>
        </tr> 
        <?php }?>
        </table>
            </div>
        <?php }else{echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['no_story_post_yet'].'</div></div>';}?>
        </div>
        <!---->
    <div class="i_become_creator_box_footer tabing">
        <?php if (ceil($totalnApprovedPosts / $paginationLimit) > 0): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if (iN_HelpSecure($pagep) > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if (iN_HelpSecure($pagep)-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalnApprovedPosts / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalnApprovedPosts / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalnApprovedPosts / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo ceil($totalnApprovedPosts / $paginationLimit); ?>"><?php echo ceil($totalnApprovedPosts / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalnApprovedPosts / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>admin/storiePosts?page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
    <!---->
    </div>

</div> 
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/storiePostHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>
