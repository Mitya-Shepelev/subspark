<?php
include("../includes/inc.php");
if($logedIn == '1'){
    $userNotificationReadStatus = $userData['notification_read_status']; 
	$userMessageNotificationReadStatus = $userData['message_notification_read_status'];
    $totalNotifications = $iN->iN_GetNewNotificationSum($userID);
	$totalMessageNotifications = $iN->iN_GetNewMessageNotificationSum($userID);
    $checkVideoCall = $iN->iN_CheckVideoCall($userID);
    $checkVideoCallStatus = $iN->iN_CheckVideoCallStatus($userID);
    $data = array(
        'messageNotificationStatus' => $userMessageNotificationReadStatus,
        'notificationStatus' => $userNotificationReadStatus,
        'unReadedNotfications' => $totalNotifications,
        'unReadMessageNotifications' => $totalMessageNotifications,
        'videoCallFound' => $checkVideoCall,
        'acceptStatus' => $checkVideoCallStatus
    );
    $result =  json_encode( $data , JSON_UNESCAPED_UNICODE);	
    echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result); 
}
?> 