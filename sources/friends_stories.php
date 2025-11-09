<?php 
if($agoraStatus == '1'){
include("themes/$currentTheme/layouts/friends_stories.php");
}else{
  header('Location: ' . route_url('404'));
} 
?>
