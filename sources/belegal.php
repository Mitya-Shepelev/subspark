<?php
if($iN->iN_SenSec($mycd, $mycdStatus) == 'go'){
    include("themes/$currentTheme/".base64_decode('bGVnYWw=').".php");
}else{
    include("themes/$currentTheme/404.php");
}
?>