<?php   
if($logedIn == 1){ 
    include("themes/$currentTheme/verifyme.php");  
}else{
  header("Location:$base_url");
}
?> 