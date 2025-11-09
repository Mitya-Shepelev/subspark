<?php
function checkHtaccessUrlMismatch($base_url)
{
    $htaccessPath = realpath(__DIR__ . '/../.htaccess'); // move one directory up

    if (!file_exists($htaccessPath)) {
        return;
    }

    $htaccessContent = file_get_contents($htaccessPath);
    preg_match_all('/ErrorDocument\s+\d+\s+(https?:\/\/[^\s]+)/i', $htaccessContent, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[1] as $errorUrl) {
            if (strpos($errorUrl, $base_url) !== 0) {
                echo '<pre>
====================================================
⚠️ INSTALLATION ERROR: DOMAIN MISMATCH DETECTED
====================================================

It looks like the domain name defined in your .htaccess file 
does not match your current website address.

This usually happens when the .htaccess file still contains 
"localhost" or "localhost:8888" instead of your actual domain.

----------------------------------------------------
DETECTED .htaccess URL:
' . htmlspecialchars($errorUrl) . '

EXPECTED URL (based on your current setup):
' . htmlspecialchars($base_url) . '
----------------------------------------------------

This mismatch can cause serious issues such as:
- Styles (CSS) not loading
- JavaScript not working
- Login page appearing broken

✅ To fix this, open your .htaccess file and replace any
   "localhost" or incorrect domain with your real website URL.

Example:
  Change this line:
    ErrorDocument 404 http://localhost:8888/404

  To this:
    ErrorDocument 404 ' . $base_url . '404

Once you fix the .htaccess file, reload the page.
====================================================
</pre>';
                exit();
            }
        }
    }
}
 
?>