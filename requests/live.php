<?php
include("../includes/inc.php");
if(isset($_POST['f']) && $logedIn == '1'){
    $type = isset($_POST['f']) ? trim($_POST['f']) : '';
    if($type == 'live_calcul'){
      if(isset($_POST['lid']) && !empty($_POST['lid'])){
          $liveID = (int)($_POST['lid']);
          $checkLiveExist = $iN->iN_CheckLiveIDExist($liveID);
          if(!$checkLiveExist){
            $redirectUrl = $base_url;
            $data = array(
              'likeCount' => "",
              'onlineCount' => "",
              'time' => "",
              'finished' => $redirectUrl
              );
           $result =  json_encode( $data , JSON_UNESCAPED_UNICODE);
           echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
           exit();
         }
          $liveTime = $iN->iN_GetLastLiveFinishTimeFromID($liveID);
          $liData = $iN->iN_GetLiveStreamingDetailsByID($liveID);
          $liveStreamingType = $liData['live_type'];
          $currentTime = time();
          $redirectUrl = '';
          $liveCredit = isset($liData['live_credit']) ? $liData['live_credit'] : NULL;
          $liveCreatorID = $liData['live_uid_fk'];
          if($liveCredit && $userID == $liveCreatorID){
             $iN->iN_UpdateLiveStreamingTime($liveID);
          }
          $remaining = $liveTime - time();
          $remaminingTime = intval(date('i', $remaining));
          $liveRemainingTime =  html_entity_decode($iN->iN_SelectedMenuIcon('115')).iN_HelpSecure($remaminingTime).$LANG['minutes_left'];
          if($checkLiveExist){
            $json = array();
            $data = array(
               'likeCount' => $iN->iN_TotalLiveLiked($liveID),
               'onlineCount' => $iN->iN_OnlineLiveVideoUserCount($userID, $liveID),
               'time' => $liveRemainingTime,
               'finished' => $redirectUrl
               );
            $result =  json_encode( $data , JSON_UNESCAPED_UNICODE);
            echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
          }
      }
    }
    if ($type == 'liveLastMessage') {
        if (isset($_POST['idc']) && !empty($_POST['idc'])) {
            $liveID = (int)($_POST['idc']);
            $liveLastMessageID = (int)($_POST['lc']);
            $liveMessageList = $iN->iN_GetNewLiveMessage($liveID, $liveLastMessageID);
    
            if ($liveMessageList) {
                foreach ($liveMessageList as $lmData) {
                    $messageID = $lmData['cm_id'];
                    $messageLiveID = $lmData['cm_live_id'];
                    $messageLiveUserID = $lmData['cm_iuid_fk'];
                    $messageLiveTime = $lmData['cm_time'];
                    $liveMessage = rawurldecode($lmData['cm_message']);
                    $msgData = $iN->iN_GetUserDetails($messageLiveUserID);
                    $msgUserName = $base_url . $msgData['i_username'];
                    $msgUserFullName = $msgData['i_user_fullname'];
                    $giftSended = isset($lmData['cm_gift_type']) ? $lmData['cm_gift_type'] : null;
                    $giftIm = '';
                    $giftAtColor = '';
    
                    if ($giftSended) {
                        $getLiveGiftDataFromID = $iN->GetLivePlanDetails($giftSended);
                        $liveAnimationImage = isset($getLiveGiftDataFromID['gift_image']) ? $getLiveGiftDataFromID['gift_image'] : null;
                        $giftIm = "<span class='gift_attan'>" . iN_HelpSecure($LANG['send_you_a_gift']) . "</span><img src='" . $base_url . $liveAnimationImage . "'>";
                        $giftAtColor = 'live_t_color';
                    }
    
                    echo '
                    <div class="gElp9 flex_ tabing_non_justify eo2As mytransition cUq_' . iN_HelpSecure($messageID) . '" id="' . iN_HelpSecure($messageID) . '">
                        <a href="' . iN_HelpSecure($msgUserName) . '" target="_blank" class="'.$giftAtColor.'">' . iN_HelpSecure($msgUserFullName) . '</a>' . iN_HelpSecure($liveMessage) . $giftIm . '
                    </div>
                    ';
                }
            }
        }
    }
}
?>
