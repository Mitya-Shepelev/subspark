<?php
include("../includes/inc.php");
if($geoLocationAPIKey){  
    $location = get_geolocation($geoLocationAPIKey, $ip);
    $decodedLocation = json_decode($location, true);
    $countrycode = isset($decodedLocation['country_code2']) ? $iN->iN_Secure($decodedLocation['country_code2']) : '';
    DB::exec("UPDATE i_users SET countryCode = ? WHERE iuid = ?", [$countrycode, (int)$userID]);
    function get_geolocation($geoLocationAPIKey, $ip, $lang = "en", $fields = "*", $excludes = "") {
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=".$geoLocationAPIKey."&ip=".$ip."&lang=".$lang."&fields=".$fields."&excludes=".$excludes;
        $cURL = curl_init();

        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: '.$_SERVER['HTTP_USER_AGENT']
        ));

        return curl_exec($cURL);
    }
}   
?>
