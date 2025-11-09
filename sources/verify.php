<?php
if ($logedIn == 1) {
    if (isset($_GET['v']) && !empty($_GET['v'])) {
        $activationCode = trim($_GET['v']);
        $checkCodeExist = $iN->iN_CheckVerCodeExist($activationCode);

        if ($checkCodeExist) {
            $affected = DB::exec("UPDATE i_users SET verify_key = '', email_verify_status = 'yes' WHERE verify_key = ?", [$activationCode]);

            if ($affected < 1) { 
                include("themes/$currentTheme/404.php");
                exit;
            }

            header("Location:$base_url");
            exit;
        } else {
            include("themes/$currentTheme/404.php");
            exit;
        }

    } else {
        include("themes/$currentTheme/404.php");
        exit;
    }
} else {
    header("Location:$base_url");
    exit;
}
?>
