<?php
if($logedIn == 0){
    header('Location: ' . route_url('404'));
}else{ 
 require_once 'includes/payment/vendor/autoload.php';
 if (!defined('INORA_METHODS_CONFIG')) {
     define('INORA_METHODS_CONFIG', realpath('includes/payment/paymentConfig.php'));
 }
// Get config data
$configData = configItem();
    include("themes/$currentTheme/payment-failed.php");
}
?>
