<?php
/**
 * Get cookie value safely
 */
function getCookieValue($cookieName) {
    return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : '';
}

// Check if the greeting message was already shown today
$isDayGreetingShown = getCookieValue('day_status');

// Get current hour
$currentHour = date('G');

// Initialize variables
$greetMessage = '';
$greetTitle = '';
$borderColor = '';
$cookieExpire = strtotime('tomorrow'); // Default expiration

// Morning greeting
if ($currentHour < 12) {
    $greetMessage = $morningMessages[array_rand($morningMessages)];
    $greetTitle = iN_HelpSecure($LANG['good_morning']) . ', ' . iN_HelpSecure($userFullName) .
        ' <img src="' . iN_HelpSecure($base_url . 'img/dayimages/morning.png') . '" alt="Morning">';
    $borderColor = 'i_day_wish_wrapper_border_color_3';
    $cookieExpire = strtotime('tomorrow 12:00');

// Afternoon greeting
} elseif ($currentHour >= 12 && $currentHour <= 18) {
    $greetMessage = $afternoonMessages[array_rand($afternoonMessages)];
    $greetTitle = iN_HelpSecure($LANG['good_afternoon']) . ', ' . iN_HelpSecure($userFullName) .
        ' <img src="' . iN_HelpSecure($base_url . 'img/dayimages/afternoon.jpg') . '" alt="Afternoon">';
    $borderColor = 'i_day_wish_wrapper_border_color_1';
    $cookieExpire = strtotime('tomorrow 18:00');

// Evening greeting
} elseif ($currentHour > 18 && $currentHour <= 24) {
    $greetMessage = $eveningMessages[array_rand($eveningMessages)];
    $greetTitle = iN_HelpSecure($LANG['good_evening']) . ', ' . iN_HelpSecure($userFullName) .
        ' <img src="' . iN_HelpSecure($base_url . 'img/dayimages/night.jpg') . '" alt="Evening">';
    $borderColor = 'i_day_wish_wrapper_border_color_2';
    $cookieExpire = strtotime('tomorrow 23:59');
}

// Show greeting if it was not shown yet
if ($isDayGreetingShown != 1) {
    ?>
    <div class="i_day_wishcontainer" role="region" aria-label="<?php echo html_entity_decode($greetMessage); ?>">
        <div class="i_day_wish_wrapper <?php echo htmlspecialchars($borderColor); ?>">
            <div class="i_day_wish_title">
                <?php echo html_entity_decode($greetTitle); ?>
            </div>
            <div class="i_day_wish_desc">
                <?php echo html_entity_decode($greetMessage); ?>
            </div>
        </div>
    </div>
    <?php
    // Set cookie to prevent showing again until the next period
    setcookie('day_status', 1, $cookieExpire, '/');
}
?>