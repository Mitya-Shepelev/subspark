<?php  
if($logedIn == 0){
    header('Location: ' . route_url('404'));
}else{
$checkPageExist = $iN->iN_CheckpageExist($urlMatch);
include("themes/$currentTheme/contents.php");  
}
?>
