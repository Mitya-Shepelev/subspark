<?php
$get_ip = $iN->iN_GetIPAddress();
$getIpRaw = $iN->iN_fetchDataFromURL("http://ip-api.com/json/$get_ip");
$getIpInfo = json_decode($getIpRaw, true);

$rCountryCode = $rUTimeZone = $rUserCity = $rUserLat = $rUserLon = '';

if (is_array($getIpInfo) && isset($getIpInfo['status']) && $getIpInfo['status'] === 'success' && 
    !empty($getIpInfo['regionName']) && !empty($getIpInfo['countryCode']) && 
    !empty($getIpInfo['timezone']) && !empty($getIpInfo['city'])) {
    
    $registerCountryCode   = $getIpInfo['countryCode'];
    $reigsetrUserTimeZone  = $getIpInfo['timezone'];
    $registerUserCity      = $getIpInfo['city'];
    $registerUserLatitude  = $getIpInfo['lat'];
    $registerUserLongitude = $getIpInfo['lon'];
} else {
    $registerCountryCode = $defaultLanguage;
}
?>