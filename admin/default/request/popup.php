<?php
// Enable strict typing
declare(strict_types=1);

include_once '../../../includes/inc.php';
$statusValue = ['0', '1'];

if (isset($_POST['f']) && $logedIn === '1') {
    $type = $iN->iN_Secure($_POST['f']);

    if ($type === 'ddelPost' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deletePost.php';
    }
    if ($type === 'ddelQuest' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteQuestion.php';
    }
    if ($type === 'ddelReportP' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteReportedPost.php';
    }
    if ($type === 'ddelReportC' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteReportedComment.php';
    }
    if ($type === 'editSVGPopUp' && isset($_POST['svg'])) {
        $cID = $iN->iN_Secure($_POST['svg']);
        $alertType = $type;
        $getIconData = $iN->iN_GetSVGCodeFromID($cID);
        if ($getIconData) {
            include '../sources/popup/editSVG.php';
        }
    }
    if ($type === 'newSVGCode') {
        include '../sources/popup/newSVG.php';
    }
    if ($type === 'newProfileCategory') {
        include '../sources/popup/newPCategory.php';
    }
    if ($type === 'newPackage') {
        include '../sources/popup/newPackage.php';
    }
    if ($type === 'newBoostPackage') {
        include '../sources/popup/newBoostPackage.php';
    }
    if ($type === 'newLiveGiftCard') {
        include '../sources/popup/newLiveGiftCard.php';
    }
    if ($type === 'newFrameCard') {
        include '../sources/popup/newFrameCard.php';
    }
    if ($type === 'newSocialSite') {
        include '../sources/popup/newSocial.php';
    }
    if ($type === 'newWebSiteSocialSite') {
        include '../sources/popup/newWebsiteSocial.php';
    }
    if ($type === 'ddelPlan' && isset($_POST['id'])) {
        $planID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deletePlan.php';
    }
    if ($type === 'ddelLivePlan' && isset($_POST['id'])) {
        $planID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteLivePlan.php';
    }
    if ($type === 'ddelFramePlan' && isset($_POST['id'])) {
        $planID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteFramePlan.php';
    }
    if ($type === 'editLanguage' && isset($_POST['id'])) {
        $langID = $iN->iN_Secure($_POST['id']);
        include '../sources/popup/editLanguage.php';
    }
    if ($type === 'delLang' && isset($_POST['id'])) {
        $langID = $iN->iN_Secure($_POST['id']);
        include '../sources/popup/deleteLanguage.php';
    }
    if ($type === 'newLangauge') {
        include '../sources/popup/addNewLanguage.php';
    }
    if ($type === 'deleteUser' && isset($_POST['id'])) {
        $delUserID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteUser.php';
    }
    if ($type === 'deleteUserVerification' && isset($_POST['id'])) {
        $verfID = $iN->iN_Secure($_POST['id']);
        include '../sources/popup/deleteVerificationRequest.php';
    }
    if ($type === 'ddelPage' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deletePage.php';
    }
    if ($type === 'ddelQA' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteQA.php';
    }
    if ($type === 'editStickerUrl' && isset($_POST['sid'])) {
        $cID = $iN->iN_Secure($_POST['sid']);
        $alertType = $type;
        $getSData = $iN->iN_GetStickerDetailsFromID($cID);
        if ($getSData) {
            include '../sources/popup/editStickerUrl.php';
        }
    }
    if ($type === 'addNewStickerUrl') {
        include '../sources/popup/addNewStickerUrl.php';
    }
    if ($type === 'addNewAnnouncement') {
        include '../sources/popup/addNewAnnouncement.php';
    }
    if ($type === 'declineSure' && isset($_POST['did'])) {
        $declinedID = $iN->iN_Secure($_POST['did']);
        $checkPaymentRequestID = $iN->iN_CheckPaymentRequestIDExist($userID, $declinedID);
        if ($checkPaymentRequestID) {
            include '../sources/popup/declinePayment.php';
        }
    }
    if ($type === 'deletePayout' && isset($_POST['id'])) {
        $delUserID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deletePayout.php';
    }
    if ($type === 'showWithdrawDetails' && isset($_POST['id'])) {
        $withdrawID = $iN->iN_Secure($_POST['id']);
        $wDet = $iN->iN_GetUWithdrawalDetails($userID, $withdrawID, 'withdrawal');
        $wDetUserData = $iN->iN_GetUserDetails($wDet['iuid_fk']);
        $alertType = $type;
        include '../sources/popup/showWithdrawDetails.php';
    }
    if ($type === 'showQuestionDetails' && isset($_POST['id'])) {
        $questionID = $iN->iN_Secure($_POST['id']);
        $qDet = $iN->iN_GetUQuestionDetails($userID, $questionID);
        $alertType = $type;
        include '../sources/popup/showQuestionDetails.php';
    }
    if ($type === 'ddelAds' && isset($_POST['id'])) {
        $planID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteAds.php';
    }
    if ($type === 'deleteSticker' && isset($_POST['id'])) {
        $delStickerID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteSticker.php';
    }
    if ($type === 'deleteStoryBg' && isset($_POST['id'])) {
        $delStickerID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteStoryBg.php';
    }
    if ($type === 'newQA') {
        include '../sources/popup/newQA.php';
    }
    if ($type === 'editQuestionAnswer' && isset($_POST['sid'])) {
        $cID = $iN->iN_Secure($_POST['sid']);
        $alertType = $type;
        $getSData = $iN->iN_GetQADetailsFromID($cID);
        if ($getSData) {
            include '../sources/popup/editQA.php';
        }
    }
    if ($type === 'delete_storie_alert' && isset($_POST['id']) && $_POST['id'] !== '') {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        $checkStorieIDExist = $iN->iN_CheckStorieIDExistForAdmin($userID, $postID);
        if ($checkStorieIDExist) {
            include '../sources/popup/deleteStoryAlert.php';
        }
    }
    if ($type === 'deleteAnnouncement' && isset($_POST['id'])) {
        $deleteAnnouncementID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteAnnouncement.php';
    }
    if ($type === 'editAnnouncement' && isset($_POST['sid'])) {
        $cID = $iN->iN_Secure($_POST['sid']);
        $alertType = $type;
        $getaData = $iN->iN_GetAnnouncementDetailsFromID($userID, $cID);
        if ($getaData) {
            include '../sources/popup/editAnnouncement.php';
        }
    }
    if ($type === 'deleteProduct' && isset($_POST['id'])) {
        $delProdID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteProduct.php';
    }
    if ($type === 'editSocialLink' && isset($_POST['svg'])) {
        $socialID = $iN->iN_Secure($_POST['svg']);
        $alertType = $type;
        $sData = $iN->iN_GetSocialLinkDetails($userID, $socialID);
        include '../sources/popup/editSocialLink.php';
    }
    if ($type === 'editWSocialLink' && isset($_POST['svg'])) {
        $socialID = $iN->iN_Secure($_POST['svg']);
        $alertType = $type;
        $sData = $iN->iN_GetWbsiteSocialLinkDetails($userID, $socialID);
        include '../sources/popup/editWSocialLink.php';
    }
}

if (isset($type)) {
    if ($type === 'deleteSocialSite' && isset($_POST['svg'])) {
        $socialID = $iN->iN_Secure($_POST['svg']);
        $alertType = $type;
        include '../sources/popup/deleteSocialSite.php';
    }
    if ($type === 'deleteSocialSiteW' && isset($_POST['svg'])) {
        $socialID = $iN->iN_Secure($_POST['svg']);
        $alertType = $type;
        include '../sources/popup/deleteSocialWSite.php';
    }
    if ($type === 'delSubCat' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteSubCat.php';
    }
    if ($type === 'delCatt' && isset($_POST['id'])) {
        $postID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteCatt.php';
    }
    if ($type === 'deleteBoostedPost' && isset($_POST['id'])) {
        $delProdID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteBoostedPost.php';
    }
    if ($type === 'ddelBoostPlan' && isset($_POST['id'])) {
        $planID = $iN->iN_Secure($_POST['id']);
        $alertType = $type;
        include '../sources/popup/deleteBoostPlan.php';
    }
    if ($type === 'getPaymentDetails' && isset($_POST['pyID'])) {
        $paymentID = $iN->iN_Secure($_POST['pyID']);
        $pData = $iN->iN_GetPaymentDetailsByID($paymentID, $userID);
        $planID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : null;
        $ImageFileID = isset($pData['bank_payment_image']) ? $pData['bank_payment_image'] : null;
        $wDetUserData = $iN->iN_GetUserDetails($pData['payer_iuid_fk']);
        $alertType = $type;
        include '../sources/popup/showBankPaymentDetails.php';
    }
    if ($type === 'callColors' && isset($_POST['id']) && !empty($_POST['id'])) {
        $colorFor = $iN->iN_Secure($_POST['id']);
        include '../sources/popup/colorPickers.php';
    }
}
?>
