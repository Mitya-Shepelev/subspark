<?php 
if($agoraStatus == '1'){
include("themes/$currentTheme/layouts/live_streams.php");
}else{
  header('Location: ' . route_url('404'));
} 
?>
