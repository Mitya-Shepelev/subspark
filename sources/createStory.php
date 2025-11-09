<?php 
if($logedIn == '1'){
    if(isset($_GET['t']) && $_GET['t'] != ''){
       $storyType = trim($_GET['t']);
       if($storyType == 'image'){
           include("themes/$currentTheme/layouts/storyImageCreator.php");
       }else if($storyType == 'text'){
           include("themes/$currentTheme/layouts/storyTextCreator.php");
       }else{
        include("themes/$currentTheme/404.php");
       }
    }
}else{
    if($landingPageType == '1'){
        include("themes/$currentTheme/layouts/main.php");
    }else if($landingPageType == '2'){
        include("themes/$currentTheme/landing.php");
    } 
}
?>
