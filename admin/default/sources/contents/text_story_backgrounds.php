<?php
$totalStickers = $iN->iN_TotalStoryBgImage();
$totalPages = ceil($totalStickers / $paginationLimit);
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
          <?php echo iN_HelpSecure($LANG['manage_stickers']).'('.$totalStickers.')';?>
        </div>
        <!---->
        <!---->
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="new_svg_icon_wrapper margin_bottom_custom_css_js">
                <div class="newpa newstick inline_block"><div class="flex_ tabing_non_justify newSvgCode border_one">
                <form id="stBgUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/request/request.php">
                    <label for="id_fav" class="flex_ tabing">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91'));?><?php echo iN_HelpSecure($LANG['add_newb_story_bg_img']);?>
                        <input type="file" id="id_fav" name="uploading[]" data-id="stBgImage" data-type="sec_two" class="editAds_file">
                    </label>
                </form>
                </div>
            </div>
        </div>
        <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']);?></div>
        <?php
        $allSticker = $iN->iN_AllStoryBgList($userID, $paginationLimit, $pagep);
        if($allSticker){
        ?>
        <div class="i_overflow_x_auto">
            <table class="border_one">
                <tr>
                    <th><?php echo iN_HelpSecure($LANG['id']);?></th>
                    <th><?php echo iN_HelpSecure($LANG['story_bg_image']);?></th>
                    <th><?php echo iN_HelpSecure($LANG['status']);?></th>
                    <th><?php echo iN_HelpSecure($LANG['actions']);?></th>
                </tr>
        <?php
        foreach($allSticker as $sData){
            $stickerID = $sData['st_bg_id'];
            $stickerUrl = $sData['st_bg_img_url'];
            $stickerStatus = $sData['st_bg_status'];
            if (function_exists('storage_public_url')) {
                $filePathUrl = storage_public_url($stickerUrl);
            } else {
                $filePathUrl = $base_url . $stickerUrl;
            }
        ?>
        <tr class="transition trhover">
            <td><?php echo iN_HelpSecure($stickerID);?></td>
            <td class="see_post_details"><div class="flex_ tabing_non_justify sim truncated"><img src="<?php echo iN_HelpSecure($filePathUrl, FILTER_VALIDATE_URL);?>"></div></td>
            <td class="see_post_details">
               <div class="flex_ tabing_non_justify">
               <label class="el-switch el-switch-yellow" for="upStick<?php echo iN_HelpSecure($stickerID);?>">
                   <input type="checkbox" name="stickerStatus" class="upStick" id="upStick<?php echo iN_HelpSecure($stickerID);?>" data-id="<?php echo iN_HelpSecure($stickerID);?>" data-type="upStoryBg" <?php echo iN_HelpSecure($stickerStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"';?>>
                   <span class="el-switch-style"></span>
                </label>
               <div class="success_tick tabing flex_ sec_one upStick<?php echo iN_HelpSecure($stickerID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
               </div>
            </td>
            <td class="flex_ tabing_non_justify">
                <div class="flex_ tabing_non_justify">
                    <div class="delu del_stor_bg border_one transition tabing flex_" id="<?php echo iN_HelpSecure($stickerID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')).$LANG['delete'];?></div>
                </div>
            </td>
        </tr>
        <?php }?>
        </table>
            </div>
        <?php }else{echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['no_user_found'].'</div></div>';}?>

        </div>
        <!---->
    <div class="i_become_creator_box_footer tabing">
        <?php if (ceil($totalStickers / $paginationLimit) > 1): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($pagep-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalStickers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalStickers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalStickers / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo ceil($totalStickers / $paginationLimit); ?>"><?php echo ceil($totalStickers / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalStickers / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL);?>admin/text_story_backgrounds?page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
    <!---->
    </div>

</div>
<script type="text/javascript">
(function($) {
    "use strict";
$(document).on("change", " #id_fav", function(e) {
    e.preventDefault();
    var values = $("#logo").val();
    var id = $(this).attr("data-id");
    var type = $(this).attr("data-type");
    var data = { f: id, c: type };
    $("#stBgUploadForm").ajaxForm({
        type: "POST",
        data: data,
        delegation: true,
        cache: false,
        beforeSubmit: function() {
            $("#sec_logo").append('<div class="i_upload_progress"></div>');
        },
        uploadProgress: function(e, position, total, percentageComplete) {
            $('.i_upload_progress').width(percentageComplete + '%');
        },
        success: function(response) {
             if(response == '200'){
                location.reload();
             }else{
                $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                setTimeout(() => {
                    $(".nnauthority").remove();
                }, 5000);
             }
        },
        error: function() {}
    }).submit();
});
})(jQuery);
</script>
