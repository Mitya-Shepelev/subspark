<?php
/**
 * Global Initialization and Core Configuration
 * This section handles error reporting, database connections,
 * class loading, and session start required for the entire system.
 */

ob_start();
session_start();

// Database connection and base setup
include_once "connect.php";

// Error reporting: environment-aware (robust multi-channel detection)
// Priority: defined(APP_ENV) > getenv/$_SERVER > .env file > default 'production'
$env = 'development';
if (defined('APP_ENV')) {
    $env = APP_ENV;
} elseif (($v = getenv('APP_ENV'))) {
    $env = $v;
} elseif (isset($_SERVER['APP_ENV'])) {
    $env = $_SERVER['APP_ENV'];
} else {
    $envPath = dirname(__DIR__) . '/.env'; // project root relative to includes/
    if (is_file($envPath)) {
        $envContent = @file_get_contents($envPath);
        if ($envContent !== false && preg_match('/^APP_ENV\s*=\s*([\w\-\"]+)/mi', $envContent, $m)) {
            $env = trim($m[1], "\"' ");
        }
    }
}
$env = strtolower((string)$env);

if ($env === 'production') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
// Include required function and helper files
include_once "checkHtaccessUrlMismatch.php";
// Do not echo diagnostics in runtime output; optionally log
$__ht_mismatch = checkHtaccessUrlMismatch($base_url);
include_once "functions.php";
include_once "emojis.php";
include_once "colors.php";
include_once "helper.php";
include_once "linkify/autoload.php";
include_once "_expand.php";
include_once "csrf.php";
include_once "db.php";
include_once "cache.php";

// Stripe library lazy-loader function
// Only loads Stripe when needed, reducing initial load time
if (!function_exists('loadStripe')) {
    function loadStripe() {
        static $loaded = false;
        if (!$loaded) {
            require_once __DIR__ . '/stripe/vendor/autoload.php';
            $loaded = true;
        }
    }
}

// URL highlighter library usage
use VStelmakh\UrlHighlight\UrlHighlight;
$urlHighlight = new UrlHighlight();

// Class initialization
$iN = new iN_UPDATES($db);
$pdo && DB::init($pdo);
$inc = $iN->iN_Configurations();
$getPages = $iN->iN_GetPages();
$languages = $iN->iN_Languages();

// Optional per-storage CDN/public base overrides (auto-generated file)
if (is_file(__DIR__ . '/storage_public_base.php')) {
    @include __DIR__ . '/storage_public_base.php';
}

// Resolve application root (one level up from includes/) and language dir
$__APP_ROOT = dirname(__DIR__);
$__LANG_DIR = $__APP_ROOT . '/langs';

// Resolve language pack fullpath (falls back to English) without including it
if (!function_exists('__resolve_lang_path')) {
    function __resolve_lang_path($code, $langDir) {
        $safe = strtolower(preg_replace('/[^a-z_]/i', '', (string) $code));
        if ($safe === '') { $safe = 'eng'; }
        $path = rtrim($langDir, '/\\') . '/' . $safe . '.php';
        if (!is_file($path)) {
            $path = rtrim($langDir, '/\\') . '/eng.php';
        }
        return $path;
    }
}

// Set default timezone (can be overridden later per user session)
date_default_timezone_set('UTC');

/**
 * Session & Cookie Check
 * Ensures that valid session or cookie exists for logged-in user.
 * If invalid, redirect to logout.
 */
$hash = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : NULL;
$sessionUserID = isset($_SESSION['iuid']) ? $_SESSION['iuid'] : NULL;

if (!empty($hash)) {
    // Validate session hash via PDO
    $row = DB::one("SELECT session_uid FROM i_sessions WHERE session_key = ? LIMIT 1", [$hash]);
    $sessionUserID = $row['session_uid'] ?? null;

    // If session ID is not valid, redirect to logout
    if (empty($sessionUserID)) {
        header("Location: " . route_url('logout.php'));
    } else {
        // Valid session, store user ID in session variable
        $_SESSION['iuid'] = $sessionUserID;
    }
}

$cURL = true;

/**
 * Generates a random alphanumeric key of a given length.
 * Used for versioning or identifiers.
 *
 * @param int $length
 * @return string
 */
function generateRandomKeys($length = 5) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomKey = '';

    for ($i = 0; $i < $length; $i++) {
        $randomKey .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomKey;
}

// Check if cURL is available
if (!function_exists('curl_init')) {
    $cURL = false;
    $disabled = true;
}

$userEmailVerificationStatus = '';

// Define base URL for meta image
$metaBaseUrl = $base_url . 'img/' . (isset($inc['meta_image']) ? $inc['meta_image'] : NULL);

// Detect browser language (used if auto-detect enabled)
$browserLanguage = isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ? substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) : '';

// Get active theme or fallback to default
$currentTheme = isset($inc['active_theme']) ? $inc['active_theme'] : 'default';
// Generate random version ID for cache busting or file versioning
$version = generateRandomKeys();
$scriptVersion = isset($inc['version']) ? $inc['version'] : '1';

// Basic branding elements
$logo = isset($inc['site_logo']) ? $inc['site_logo'] : NULL;
$autoDetectLanguageStatus = isset($inc['auto_detect_language_status']) ? $inc['auto_detect_language_status'] : NULL;
$siteWatermarkLogo = isset($inc['site_watermark_logo']) ? $inc['site_watermark_logo'] : $logo;
$favicon = isset($inc['site_favicon']) ? $inc['site_favicon'] : '1';

// Basic SEO and meta info
$siteTitle = isset($inc['site_title']) ? $inc['site_title'] : NULL;
$siteKeyWords = isset($inc['site_keywords']) ? $inc['site_keywords'] : NULL;
$siteDescription = isset($inc['site_description']) ? $inc['site_description'] : NULL;

// Company identity info
$siteCampany = isset($inc['campany']) ? $inc['campany'] : NULL;
$siteCountry = isset($inc['country']) ? $inc['country'] : NULL;
$siteCity = isset($inc['city']) ? $inc['city'] : NULL;
$sitePostCode = isset($inc['post_code']) ? $inc['post_code'] : NULL;
$siteVat = isset($inc['vat']) ? $inc['vat'] : NULL;

// General system settings
$normalUserCanPost = isset($inc['normal_user_can_post']) ? $inc['normal_user_can_post'] : NULL;
$giphyKey = isset($inc['giphy_api_key']) ? $inc['giphy_api_key'] : NULL;
$freeLiveTime = isset($inc['free_live_time']) ? $inc['free_live_time'] : NULL;
$giphyTrendKey = isset($inc['giphy_first_trend_key']) ? $inc['giphy_first_trend_key'] : NULL;

// Agora live stream settings
$agoraStatus = $inc['agora_status'];
$agoraAppID = isset($inc['agora_app_id']) ? $inc['agora_app_id'] : NULL;
$agoraCertificate = isset($inc['agora_certificate']) ? $inc['agora_certificate'] : NULL;
$agoraCustomerID = isset($inc['agora_customer_id']) ? $inc['agora_customer_id'] : NULL;

// Landing page config
$landingPageType = $inc['landing_page_type'];

// User restrictions and blocking capabilities
$disallowedUserNames = isset($inc['disallowed_usernames']) ? $inc['disallowed_usernames'] : NULL;
$userCanBlockCountryStatus = isset($inc['user_can_block_country']) ? $inc['user_can_block_country'] : NULL;

$defaultGenderOptions = [
    ['key' => 'female', 'label' => 'Female', 'icon' => '13', 'status' => '1'],
    ['key' => 'male',   'label' => 'Male',   'icon' => '12', 'status' => '1'],
    ['key' => 'couple', 'label' => 'Couple', 'icon' => '58', 'status' => '1'],
];
$genderOptionsPath = __DIR__ . '/gender_options.php';
$genderOptions = $defaultGenderOptions;
if (is_file($genderOptionsPath)) {
    $loadedGenderOptions = include $genderOptionsPath;
    if (is_array($loadedGenderOptions)) {
        $sanitizedGenderOptions = [];
        foreach ($loadedGenderOptions as $option) {
            if (!is_array($option)) { continue; }
            $key = isset($option['key']) ? strtolower(preg_replace('/[^a-z0-9_]/', '', (string)$option['key'])) : '';
            if ($key === '' || isset($sanitizedGenderOptions[$key])) { continue; }
            $label = isset($option['label']) ? trim((string)$option['label']) : ucfirst($key);
            if ($label === '') { $label = ucfirst($key); }
            $icon = isset($option['icon']) ? preg_replace('/[^0-9]/', '', (string)$option['icon']) : '';
            $statusRaw = isset($option['status']) ? strtolower((string)$option['status']) : '1';
            $status = in_array($statusRaw, ['0', 'off', 'no', 'false'], true) ? '0' : '1';
            $sanitizedGenderOptions[$key] = [
                'key'   => $key,
                'label' => $label,
                'icon'  => $icon,
                'status'=> $status,
            ];
        }
        if (!empty($sanitizedGenderOptions)) {
            $genderOptions = array_values($sanitizedGenderOptions);
        }
    }
}
$genderOptionsFull = $genderOptions;
$genderOptionsActive = array_values(array_filter($genderOptions, function($option) {
    return ($option['status'] ?? '1') !== '0';
}));
if (empty($genderOptionsActive)) {
    $genderOptionsActive = $defaultGenderOptions;
    $genderOptionsFull = $defaultGenderOptions;
}
$genderOptions = $genderOptionsActive;
$genders = array_column($genderOptions, 'key');
if (empty($genders)) {
    $genderOptions = $defaultGenderOptions;
    $genderOptionsFull = $defaultGenderOptions;
    $genders = array_column($genderOptions, 'key');
}
$primaryGenderKey = $genders[0];

// DigitalOcean file storage configuration
$digitalOceanStatus = isset($inc['ocean_status']) ? $inc['ocean_status'] : NULL;
$oceankey = isset($inc['ocean_key']) ? $inc['ocean_key'] : NULL;
$oceansecret = isset($inc['ocean_secret']) ? $inc['ocean_secret'] : NULL;
$oceanspace_name = isset($inc['ocean_space_name']) ? $inc['ocean_space_name'] : NULL;
$oceanregion = isset($inc['ocean_region']) ? $inc['ocean_region'] : NULL;
$digitalOceanPublicBase = isset($inc['ocean_public_base']) ? $inc['ocean_public_base'] : NULL;

// Subscription settings
$subscriptionType = isset($inc['subscription_type']) ? $inc['subscription_type'] : NULL;

// Affiliate registration and points system settings
$dataAffilateData = $iN->iN_GetRegisterAffilateData('register', '1');
$ataAffilateAmount = isset($dataAffilateData['i_af_amount']) ? $dataAffilateData['i_af_amount'] : NULL;

$dataNewPostPoint = $iN->iN_GetRegisterAffilateData('new_post', '5');
$ataNewPostPointAmount = isset($dataNewPostPoint['i_af_amount']) ? $dataNewPostPoint['i_af_amount'] : NULL;
$ataNewPostPointSatus = isset($dataNewPostPoint['i_af_status']) ? $dataNewPostPoint['i_af_status'] : 'no';

$dataNewCommentPoint = $iN->iN_GetRegisterAffilateData('comment', '2');
$ataNewCommentPointAmount = isset($dataNewPostPoint['i_af_amount']) ? $dataNewPostPoint['i_af_amount'] : NULL;
$ataNewCommentPointSatus = isset($dataNewPostPoint['i_af_status']) ? $dataNewPostPoint['i_af_status'] : 'no';

$dataNewPostLikePoint = $iN->iN_GetRegisterAffilateData('post_like', '3');
$ataNewPostLikePointAmount = isset($dataNewPostLikePoint['i_af_amount']) ? $dataNewPostLikePoint['i_af_amount'] : NULL;
$ataNewPostLikePointSatus = isset($dataNewPostLikePoint['i_af_status']) ? $dataNewPostLikePoint['i_af_status'] : 'no';
$dataNewPostCommentLikePoint = $iN->iN_GetRegisterAffilateData('comment_like', '4');
$ataNewPostCommentLikePointAmount = isset($dataNewPostCommentLikePoint['i_af_amount']) ? $dataNewPostCommentLikePoint['i_af_amount'] : NULL;
$ataNewPostCommentLikePointSatus = isset($dataNewPostCommentLikePoint['i_af_status']) ? $dataNewPostCommentLikePoint['i_af_status'] : 'no';

// Landing page image assets
$landingPageFirstImage = isset($inc['landing_first_image']) ? $inc['landing_first_image'] : NULL;
$landingpageFirstImageArrow = isset($inc['landing_first_image_arrow']) ? $inc['landing_first_image_arrow'] : NULL;
$landingpageFirstDesctiptionImage = isset($inc['landing_feature_image_one']) ? $inc['landing_feature_image_one'] : NULL;
$landingpageSecondDesctiptionImage = isset($inc['landing_feature_image_two']) ? $inc['landing_feature_image_two'] : NULL;
$landingpageThirdDesctiptionImage = isset($inc['landing_feature_image_three']) ? $inc['landing_feature_image_three'] : NULL;
$landingpageFourthDesctiptionImage = isset($inc['landing_feature_image_four']) ? $inc['landing_feature_image_four'] : NULL;
$landingpageFifthDesctiptionImage = isset($inc['landing_feature_image_five']) ? $inc['landing_feature_image_five'] : NULL;
$landingPageSectionTwoBG = isset($inc['landing_section_two_bg']) ? $inc['landing_section_two_bg'] : NULL;
$landingSectionFeatureImage = isset($inc['landing_section_feature_image']) ? $inc['landing_section_feature_image'] : NULL;

// Content approval and post visibility
$autoApprovePostStatus = isset($inc['auto_approve_post']) ? $inc['auto_approve_post'] : 'yes';

// Display preferences for suggestions and content
$showNumberOfAds = isset($inc['showingNumberOfAds']) ? $inc['showingNumberOfAds'] : '1';
$showingNumberOfSuggestedUser = isset($inc['showingNumberOfSuggestedUser']) ? $inc['showingNumberOfSuggestedUser'] : '1';
$showingNumberOfProduct = isset($inc['showingNumberOfProduct']) ? $inc['showingNumberOfProduct'] : '1';
$showingTrendPostLimitDay = isset($inc['howManyDaysTrend']) ? $inc['howManyDaysTrend'] : '1';
$showingActivityLimit = isset($inc['activity_show_limit']) ? $inc['activity_show_limit'] : '1';
$showingTimeActivityLimit = isset($inc['activity_show_time_limit']) ? $inc['activity_show_time_limit'] : '1';
$unSubscribeStyle = isset($inc['unsubscribe_style']) ? $inc['unsubscribe_style'] : NULL;
$autoFollowAdmin = isset($inc['auto_follow_admin']) ? $inc['auto_follow_admin'] : 'yes';
// OpenAI integration and configuration
$opanAiKey = isset($inc['openai_api_key']) ? $inc['openai_api_key'] : NULL;
$openAiStatus =  isset($inc['open_ai_status']) ? $inc['open_ai_status'] : NULL;
$perAiUse = isset($inc['per_ai_use_credit']) ? $inc['per_ai_use_credit'] : NULL;

/**
 * UI Color Customizations
 * These settings allow theme personalization and brand styling.
 */
$headerSVGColor = isset($inc['header_svg_color']) ? $inc['header_svg_color'] : NULL;
$headerTopColor =  isset($inc['header_top_color']) ? $inc['header_top_color'] : NULL;
$leftMenuSVGColor =  isset($inc['left_menu_svg_color']) ? $inc['left_menu_svg_color'] : NULL;
$postSectionSVGColor =  isset($inc['post_section_svg_colors']) ? $inc['post_section_svg_colors'] : NULL;
$postIconSVGColor =  isset($inc['post_icon_colors']) ? $inc['post_icon_colors'] : NULL;
$publishBTNColor =  isset($inc['publish_btn_color']) ? $inc['publish_btn_color'] : NULL;
$createLiveStreamingsBtnColor =  isset($inc['create_live_streamings_btn_color']) ? $inc['create_live_streamings_btn_color'] : NULL;
$textHoverColor =  isset($inc['left_menu_hover_color']) ? $inc['left_menu_hover_color'] : NULL;
$MenuTextColor =  isset($inc['left_menu_text_color']) ? $inc['left_menu_text_color'] : NULL;

// Financial settings related to live streams and general use
$minimumLiveStreamingFee = $inc['minimum_live_streaming_fee'];
$maintenanceMode = $inc['maintenance_mode'];

// Language and theme options
$defaultLanguage = $inc['default_language'];
$scrollLimit = $inc['load_more_limit'];
$scrollToLimitMessage = $inc['load_more_message_limit'];
$lightDark = $inc['default_style'];
$socialLoginStatus = $inc['social_login_status'];

// Point and currency system limits
$maximumPointLimit = $inc['max_point_limit'];
$minimumPointLimit = $inc['min_point_limit'];
$maximumPointAmountLimit = $inc['max_point_amount_limit'];
$minimumPointAmountLimit = $inc['min_point_amount_limit'];

// Geolocation API support (used for location-based features)
$geoLocationAPIKey = isset($inc['geolocationapikey']) ? $inc['geolocationapikey'] : NULL;

// Branding and general info
$siteName = $inc['site'];
$ind = date('j');
$adminTheme = $inc['admin_active_theme'];

// Custom scripts and code injection settings
$customHeaderCSSCode = $iN->iN_GetCustomCodes(1);
$customHeaderJsCode = $iN->iN_GetCustomCodes(2);
$customFooterJsCode = $iN->iN_GetCustomCodes(3);

// SVG icon assets for the UI
$allSVGIcons = $iN->iN_AllSVGIcons();

// Commission and earning limits
$adminFee = $inc['fee'];
$minimumSubscriptionAmount = $inc['minimum_subscription_amount'];
$maximumSubscriptionAmount = $inc['maximum_subscription_amount'];

// Affiliate program settings
$affilateSystemStatus = isset($inc['affilate_status']) ? $inc['affilate_status'] : NULL;
$minimumPointTransferRequest = isset($inc['minimum_point_transfer_request']) ? $inc['minimum_point_transfer_request'] : '0';
$affilateAmount = $inc['affilate_amount'];

// Subscription fees (per week, month, year)
$minPointFeeWeekly = $inc['min_point_fee_weekly'];
$minPointFeeMonthly = $inc['min_point_fee_monthly'];
$minPointFeeYearly = $inc['min_point_fee_yearly'];
$businessAddress = $inc['business_address'];

// User discovery search behavior
$whicUsers = $inc['show_search_result_type'];

// Story system configuration
$storyStatusData = $iN->iN_GetStoryData('4');
$whoCanShareStory = isset($storyStatusData['sstatus']) ? $storyStatusData['sstatus'] : NULL;

// File upload validations
$availableFileExtensions = $inc['available_file_extensions'];
$availableVerificationFileExtensions = $inc['available_verification_file_extensions'];
$availableUploadFileSize = $inc['available_file_size'];
$availableLength = isset($inc['available_length']) ? $inc['available_length'] : '500';

// FFMPEG media processing support
$ffmpegPath   = isset($inc['ffmpeg_path']) ? $inc['ffmpeg_path'] : NULL;
$ffprobePath  = isset($inc['ffmpeg_probe']) ? $inc['ffmpeg_probe'] : NULL; // optional, may be null if not set
$ffmpegStatus = isset($inc['ffmpeg_status']) ? $inc['ffmpeg_status'] : '0';

// Environment overrides (developer-friendly)
$ffmpegStatus = getenv('FFMPEG_STATUS') ?: $ffmpegStatus;
$ffmpegPath   = getenv('FFMPEG_PATH')   ?: $ffmpegPath;
$ffprobePath  = getenv('FFMPEG_PROBE')  ?: $ffprobePath;

$pixelSize = $inc['pixelSize'];

// General content listing preferences
$showingNumberOfPost = $inc['showingNumberOfPost'];

// Default values for unregistered user session variables
$userType = $payoutMethod = $userWallet = '';
$cEarning = '0';
$userAvatar = $base_url . 'uploads/avatars/no_gender.png';

// Currency and pagination
$defaultCurrency = $inc['default_currency'];
if (empty($defaultCurrency)) { $defaultCurrency = 'USD'; }
$paginationLimit = $inc['pagination_limit'];

// Site identity assets
$siteLogoUrl = $base_url . $logo;
$siteFavicon = $base_url . $favicon;

// Tip feature configuration
$minimumTipAmount = isset($inc['min_tip_amount']) ? $inc['min_tip_amount'] : NULL;
/**
 * Amazon S3 Storage Configuration
 */
$s3Status = $inc['s3_status'];
$s3Bucket = isset($inc['s3_bucket']) ? $inc['s3_bucket'] : NULL;
$s3Region = isset($inc['s3_region']) ? $inc['s3_region'] : 'us-west-1';
$s3SecretKey = isset($inc['s3_secret_key']) ? $inc['s3_secret_key'] : NULL;
$s3Key = isset($inc['s3_key']) ? $inc['s3_key'] : NULL;
$s3PublicBase = isset($inc['s3_public_base']) ? $inc['s3_public_base'] : NULL;
// Environment overrides (developer-friendly)
$s3Status    = getenv('S3_STATUS')   ?: $s3Status;
$s3Bucket    = getenv('S3_BUCKET')   ?: $s3Bucket;
$s3Region    = getenv('S3_REGION')   ?: $s3Region;
$s3Key       = getenv('S3_KEY')      ?: $s3Key;
$s3SecretKey = getenv('S3_SECRET')   ?: $s3SecretKey;
$s3PublicBase = getenv('S3_PUBLIC_BASE') ?: $s3PublicBase;

/**
 * Wasabi Storage Configuration
 */
$WasStatus = isset($inc['was_status']) ? $inc['was_status'] : '0';
$WasBucket = isset($inc['was_bucket']) ? $inc['was_bucket'] : NULL;
$WasRegion = isset($inc['was_region']) ? $inc['was_region'] : 'us-west-1';
$WasSecretKey = isset($inc['was_secret_key']) ? $inc['was_secret_key'] : NULL;
$WasKey = isset($inc['was_key']) ? $inc['was_key'] : NULL;
$WasPublicBase = isset($inc['was_public_base']) ? $inc['was_public_base'] : NULL;
// Environment overrides (developer-friendly)
$WasStatus    = getenv('WASABI_STATUS') ?: $WasStatus;
$WasBucket    = getenv('WASABI_BUCKET') ?: $WasBucket;
$WasRegion    = getenv('WASABI_REGION') ?: $WasRegion;
$WasKey       = getenv('WASABI_KEY')    ?: $WasKey;
$WasSecretKey = getenv('WASABI_SECRET') ?: $WasSecretKey;
$WasPublicBase = getenv('WASABI_PUBLIC_BASE') ?: $WasPublicBase;

// DigitalOcean Spaces environment overrides
$digitalOceanStatus = getenv('SPACES_STATUS') ?: $digitalOceanStatus;
$oceanspace_name    = getenv('SPACES_NAME')   ?: $oceanspace_name;
$oceanregion        = getenv('SPACES_REGION') ?: $oceanregion;
$oceankey           = getenv('SPACES_KEY')    ?: $oceankey;
$oceansecret        = getenv('SPACES_SECRET') ?: $oceansecret;
$digitalOceanPublicBase = getenv('SPACES_PUBLIC_BASE') ?: $digitalOceanPublicBase;

// Optional local override file for MinIO admin settings
if (is_file(__DIR__ . '/minio_config.php')) {
    @include __DIR__ . '/minio_config.php';
}
// Optional local override file for Selectel admin settings
if (is_file(__DIR__ . '/selectel_config.php')) {
    @include __DIR__ . '/selectel_config.php';
}

/**
 * MinIO Storage Configuration (S3-compatible)
 * Supports env overrides for simple setup without DB fields.
 */
$minioStatus   = isset($inc['minio_status'])      ? $inc['minio_status']      : '0';
$minioBucket   = isset($inc['minio_bucket'])      ? $inc['minio_bucket']      : NULL;
$minioRegion   = isset($inc['minio_region'])      ? $inc['minio_region']      : 'us-east-1';
$minioKey      = isset($inc['minio_key'])         ? $inc['minio_key']         : NULL;
$minioSecret   = isset($inc['minio_secret_key'])  ? $inc['minio_secret_key']  : NULL;
$minioEndpoint = isset($inc['minio_endpoint'])    ? $inc['minio_endpoint']    : NULL; // e.g. https://minio.example.com:9000
$minioPublicBase = isset($inc['minio_public_base']) ? $inc['minio_public_base'] : NULL; // optional override
// Optional flags (string '1'/'0' expected if coming from DB)
$minioPathStyle = isset($inc['minio_path_style']) ? $inc['minio_path_style'] : '1';
$minioSslVerify = isset($inc['minio_ssl_verify']) ? $inc['minio_ssl_verify'] : '1';

// Environment overrides (developer-friendly)
$minioStatus     = getenv('MINIO_STATUS')      ?: $minioStatus;
$minioBucket     = getenv('MINIO_BUCKET')      ?: $minioBucket;
$minioRegion     = getenv('MINIO_REGION')      ?: $minioRegion;
$minioKey        = getenv('MINIO_KEY')         ?: $minioKey;
$minioSecret     = getenv('MINIO_SECRET')      ?: $minioSecret;
$minioEndpoint   = getenv('MINIO_ENDPOINT')    ?: $minioEndpoint;
$minioPublicBase = getenv('MINIO_PUBLIC_BASE') ?: $minioPublicBase;
$minioPathStyle  = getenv('MINIO_PATH_STYLE')  ?: $minioPathStyle;
$minioSslVerify  = getenv('MINIO_SSL_VERIFY')  ?: $minioSslVerify;

/**
 * Selectel S3 Storage Configuration (S3-compatible)
 * Russian cloud provider with S3-compatible API
 * Endpoint: https://s3.selcdn.ru, Region: ru-1
 */
$selectelStatus   = isset($inc['selectel_status'])      ? $inc['selectel_status']      : '0';
$selectelBucket   = isset($inc['selectel_bucket'])      ? $inc['selectel_bucket']      : NULL;
$selectelRegion   = isset($inc['selectel_region'])      ? $inc['selectel_region']      : 'ru-1';
$selectelKey      = isset($inc['selectel_key'])         ? $inc['selectel_key']         : NULL;
$selectelSecret   = isset($inc['selectel_secret_key'])  ? $inc['selectel_secret_key']  : NULL;
$selectelEndpoint = isset($inc['selectel_endpoint'])    ? $inc['selectel_endpoint']    : 'https://s3.ru-1.storage.selcloud.ru';
$selectelPublicBase = isset($inc['selectel_public_base']) ? $inc['selectel_public_base'] : NULL;

// Environment overrides (developer-friendly)
$selectelStatus     = getenv('SELECTEL_STATUS')      ?: $selectelStatus;
$selectelBucket     = getenv('SELECTEL_BUCKET')      ?: $selectelBucket;
$selectelRegion     = getenv('SELECTEL_REGION')      ?: $selectelRegion;
$selectelKey        = getenv('SELECTEL_KEY')         ?: $selectelKey;
$selectelSecret     = getenv('SELECTEL_SECRET')      ?: $selectelSecret;
$selectelEndpoint   = getenv('SELECTEL_ENDPOINT')    ?: $selectelEndpoint;
$selectelPublicBase = getenv('SELECTEL_PUBLIC_BASE') ?: $selectelPublicBase;

// Load unified object storage helpers
include_once __DIR__ . '/object_storage.php';

$iRL = true;

// Check cURL availability again (redundant fallback)
if (!function_exists('curl_init')) {
    $iRL = false;
    $disabled = true;
}

/**
 * Stripe Configuration for Subscriptions
 */
$stripeStatus = $inc['stripe_status'];
$stripeKey = $inc['stripe_secret_key'];
$stripePublicKey = $inc['stripe_public_key'];
$stripeCurrency = $inc['stripe_currency'];
if (empty($stripeCurrency)) { $stripeCurrency = $defaultCurrency ?: 'USD'; }
$stripeWebhookSecret = isset($inc['stripe_webhook_secret']) ? $inc['stripe_webhook_secret'] : '';

$subscribeWeeklyMinimumAmount = $inc['sub_weekly_minimum_amount'];
$subscribeMonthlyMinimumAmount = $inc['sub_monthly_minimum_amount'];
$subscribeYearlyMinimumAmount = $inc['sub_yearly_minimum_amount'];
// Minimum withdrawal threshold for creators
$minimumWithdrawalAmount = $inc['minimum_withdrawal_amount'];

// Point system conversion rate
$onePointEqual = $inc['one_point'];

// Email configuration settings
$smtpOrMail = $inc['smtp_or_mail'];
$smtpHost = $inc['smtp_host'];
$smtpUserName = $inc['smtp_username'];
$smtpEmail = isset($inc['default_mail']) ? $inc['default_mail'] : NULL;
$smtpPassword = $inc['smtp_password'];
$smtpEncryption = $inc['smtp_encryption'];
$smtpPort = $inc['smtp_port'];

// Site email and email delivery status
$siteEmail = $inc['siteEmail'];
$emailSendStatus = $inc['emailSendStatus'];
$sendEmailForAll = $inc['send__email'];

// Registration and IP-based limits
$userCanRegister = $inc['register'];
$ipLimitStatus = $inc['ip_limit'];

// Live streaming modes
$paidLiveStreamingStatus = $inc['paid_live_streaming_status'];
$freeLiveStreamingStatus = $inc['free_live_streaming_status'];

// CAPTCHA integration (Google reCAPTCHA)
$captchaStatus = $inc['g_recaptcha_status'];
$captcha_site_key = isset($inc['g_recaptcha_site_key']) ? $inc['g_recaptcha_site_key'] : NULL;
$captcha_secret_key = isset($inc['g_recaptcha_secret_key']) ? $inc['g_recaptcha_secret_key'] : NULL;

// OneSignal push notification support
$oneSignalStatus = isset($inc['one_signal_status']) ? $inc['one_signal_status'] : NULL;
$oneSignalApi = isset($inc['one_signal_api']) ? $inc['one_signal_api'] : NULL;
$oneSignalRestApi = isset($inc['one_signal_rest_api']) ? $inc['one_signal_rest_api'] : NULL;

// Reels feature + max duration
$reelsFeatureStatus = isset($inc['reels_feature_status']) ? (string)$inc['reels_feature_status'] : '1';
$maxVideoDuration = isset($inc['max_video_duration']) ? (string)$inc['max_video_duration'] : '15';

// NOW load storage config AFTER $maxVideoDuration is set
include_once "storage_config.php";
ensureUploadDirectories();

// Subscription type statuses
$subWeekStatus = isset($inc['sub_weekly_status']) ? $inc['sub_weekly_status'] : 'no';
$subMontlyStatus = isset($inc['sub_mountly_status']) ? $inc['sub_mountly_status'] : 'no';
$subYearlyStatus = isset($inc['sub_yearly_status']) ? $inc['sub_yearly_status'] : 'no';

// Watermark visibility options
$watermarkStatus = isset($inc['watermark_status']) ? $inc['watermark_status'] : 'no';
$LinkWatermarkStatus = isset($inc['watermark_text_status']) ? $inc['watermark_text_status'] : 'no';

// Name display configuration
$fullnameorusername = isset($inc['use_fullname_or_username']) ? $inc['use_fullname_or_username'] : 'no';

// Earn point system toggle
$earnPointSystemStatus = isset($inc['earn_point_status']) ? $inc['earn_point_status'] : 'no';

// Creator permission toggle
$beaCreatorStatus = isset($inc['be_a_creator_status']) ? $inc['be_a_creator_status'] : NULL;

// Video call feature configurations
$videoCallFeatureStatus = isset($inc['video_call_feature_status']) ? $inc['video_call_feature_status'] : NULL;
$whoCanCreateVideoCall = isset($inc['who_can_careate_video_call']) ? $inc['who_can_careate_video_call'] : NULL;
$isVideoCallFree = isset($inc['is_video_call_free']) ? $inc['is_video_call_free'] : NULL;

// Point limit enforcement
$maximumPointInADay = isset($inc['max_point_in_a_day']) ? $inc['max_point_in_a_day'] : '1';

// Draw text option in editor or image tools
$drawTextStatus = isset($inc['enable_disable_drawtext']) ? $inc['enable_disable_drawtext'] : '0';

// Boosted post visibility toggle
$boostedPostEnableDisable = isset($inc['boosted_post_status']) ? $inc['boosted_post_status'] : 'no';
// Load creator types for categorization or display
$creatorTYpes = $iN->iN_CreatorTypes();

/**
 * Utility function to get file extension
 *
 * @param string $str
 * @return string
 */
function getExtension($str) {
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}


/**
 * Convert bytes to MB with formatting
 *
 * @param int $size
 * @return string
 */
function convert_to_mb($size) {
    $mb_size = $size / 1048576;
    $format_size = number_format($mb_size, 2);
    return $format_size;
}
/**
 * Format number with comma for thousands and dot for decimal
 *
 * @param float|int|string $number
 * @return string
 */
function addCommasAndDots($number) {
    // Treat null/empty/false as zero to avoid blank outputs in dashboards
    if ($number === null || $number === '' || $number === false) {
        $number = 0;
    }
    if (is_numeric($number)) {
        return number_format((float)$number, 2, ',', '.');
    }
    // For non-numeric strings (like 'N/A'), return as-is
    return $number;
}

/**
 * Format number with thousand separators only
 *
 * @param float|int|string $number
 * @return string
 */
function addCommasNoDot($number) {
    if (is_numeric($number)) {
        $number = number_format((float)$number, 0, '', '.');
        return $number;
    } else {
        return $number;
    }
}

/**
 * Validate encryption key without redirection
 * Used silently to validate without user disruption
 */
function inSen($mycd, $mycdStatus) {
    $check = preg_match('/(.*)-(.*)-(.*)-(.*)-(.*)/', $mycd);
    if ($check == 0 && ($mycdStatus == 1 || $mycdStatus == '' || empty($mycdStatus))) {
        exit();
    }
}
// Premium and live gift plan data for UI and pricing logic
$purchasePointPlanTable = $iN->iN_PremiumPlans();
$planTableList = $iN->iN_PremiumPlansListFromAdmin();
$planLiveGifTableList = $iN->iN_LiveGifPlansListFromAdmin();
$sendCoinList = $iN->iN_LiveGiftSendList();

// Check session for logged-in user
if (isset($_COOKIE[$cookieName])) {
    $logedIn = '1';
    $sessionKey = isset($_COOKIE[$cookieName]) ? $iN->iN_Secure($_COOKIE[$cookieName]) : NULL;
    $user_id = $iN->iN_GetUserIDFromSessionKey($sessionKey);

    if ($user_id) {
        $userData = $iN->iN_GetUserDetails($user_id);
        $userFullName = $iN->sanitize_output($userData['i_user_fullname'], $base_url);
        $userName = $userData['i_username'];
        $userEmail = $userData['i_user_email'];
        $userID = $userData['iuid'];

        // Notification counts
        $totalNotifications = $iN->iN_GetNewNotificationSum($userID);
        $totalMessageNotifications = $iN->iN_GetNewMessageNotificationSum($userID);

        // Language and type
        $userLang = $userData['lang'];
        $userType = $userData['userType'];
        // Display user full name or username based on setting
        if ($fullnameorusername == 'no') {
            $userFullName = $userName;
        }

        $userBio = isset($userData['u_bio']) ? $userData['u_bio'] : NULL;
        $userBirthDay = isset($userData['birthday']) ? $userData['birthday'] : NULL;
        $userQrCode = isset($userData['qr_image']) ? $userData['qr_image'] : NULL;

        // Format birthday to dd/mm/yyyy if set
        if ($userBirthDay) {
            $userBirthDay = DateTime::createFromFormat('Y-m-d', $userBirthDay)->format('d/m/Y');
        }

        $verifData = $iN->iN_CheckUserHasVerificationRequest($userID);
        $verStatus = '';
        $userGender = $userData['user_gender'];
        $userWhoCanSeePost = $userData['post_who_can_see'];
        $userProfileCategory = isset($userData['profile_category']) ? $userData['profile_category'] : NULL;

        // If no profile category set, update it to default
        if (empty($userProfileCategory) || $userProfileCategory == NULL) {
            DB::exec("UPDATE i_users SET profile_category = 'normal_user' WHERE iuid = ?", [(int)$userID]);
        }

        // Get notifications for UI rendering
        $Notifications = $iN->iN_GetAllNotificationList($userID, $scrollLimit);
        $userProfileUrl = $base_url . $userName;
        $userAvatar = $iN->iN_UserAvatar($userID, $base_url);
        $userCover = $iN->iN_UserCover($userID, $base_url);
        // User statistics for dashboard
        $totalSubscribers = $iN->iN_UserTotalSubscribers($userID);
        $totalPointPayments = $iN->iN_UserTotalPointPayments($userID);
        $totalSubscriptions = $iN->iN_UserTotalSubscribtions($userID);
        $totalFollowingUsers = $iN->iN_UserTotalFollowingUsers($userID);
        $totalFollowerUsers = $iN->iN_UserTotalFollowerUsers($userID);
        $totalBlockedUsers = $iN->iN_UserTotalBlocks($userID);
        $totalPurchasedPoints = $iN->iN_UserTotalPointPurchase($userID);

        // Verification and account statuses
        $certificationStatus = $userData['certification_status'];
        $validationStatus = $userData['validation_status'];
        $conditionStatus = $userData['condition_status'];
        $feesStatus = $userData['fees_status'];
        $payoutStatus = $userData['payout_status'];

        // UI preferences
        $lightDark = $userData['light_dark'];

        // Optional data
        $deviceKey = isset($userData['device_key']) ? $userData['device_key'] : NULL;
        $lastLoginTime = $userData['last_login_time'];
        $countryCode = isset($userData['countryCode']) ? $userData['countryCode'] : NULL;
        $notificationEmailStatus = $userData['email_notification_status'];
        $showHidePostOnlineOffline = $userData['show_hide_posts'];
        $messageSendStatus = $userData['message_status'];
        $loginWith = isset($userData['login_with']) ? $userData['login_with'] : NULL;
        // Email verification logic based on login method
        if (!empty($loginWith)) {
            $userEmailVerificationStatus = $userData['email_verify_status'];
        } else {
            $userEmailVerificationStatus = $userData['email_verify_status'];
        }

        $thanksNOtForTip = isset($userData['thanks_for_tip']) ? $userData['thanks_for_tip'] : NULL;
        $userTimeZone = isset($userData['u_timezone']) ? $userData['u_timezone'] : NULL;
        $myVideoCallPrice = isset($userData['video_call_price']) ? $userData['video_call_price'] : NULL;

        // Apply user-specific timezone if available
        if ($userTimeZone) {
            date_default_timezone_set($userTimeZone);
        }

        // Payout details
        $payoutMethod = isset($userData['payout_method']) ? $userData['payout_method'] : NULL;
        $paypalEmail = isset($userData['paypal_email']) ? $userData['paypal_email'] : NULL;
        $bankAccount = isset($userData['bank_account']) ? $userData['bank_account'] : NULL;

        // Subscription plans
        $WeeklySubDetail = $iN->iN_GetUserSubscriptionPlanDetails($userID, 'weekly');
        $MonthlySubDetail = $iN->iN_GetUserSubscriptionPlanDetails($userID, 'monthly');
        $YearlySubDetail = $iN->iN_GetUserSubscriptionPlanDetails($userID, 'yearly');

        // Monthly earnings calculation
        $calculateCurrentEarning = $iN->iN_CalculateCurrentMonthEarning($userID);
        $cEarning = isset($calculateCurrentEarning['calculate']) ? $calculateCurrentEarning['calculate'] : '0';
        // Wallet data
        $userCurrentPoints = isset($userData['wallet_points']) ? $userData['wallet_points'] : '0';
        $userWallet = isset($userData['wallet_money']) ? $userData['wallet_money'] : '0';

        // Message permission
        $whoCanSendYouMessage = isset($userData['who_can_send_message']) ? $userData['who_can_send_message'] : '0';

        /**
         * Format numbers based on user or site preference
         * @param float|int $number
         * @param int $dec
         * @param bool $trim
         * @return string
         */
        function format_number($number, $dec = 0, $trim = false) {
            if ($trim) {
                $parts = explode(".", (round($number, $dec) * 1));
                $dec = isset($parts[1]) ? strlen($parts[1]) : 0;
            }
            $formatted = number_format($number, $dec);
            return $formatted;
        }

        // Load user language pack (subfolder-safe)
        $___lang_path = __resolve_lang_path($userLang, $__LANG_DIR);
        include $___lang_path;

        // Determine visibility setting for posts
        if ($userWhoCanSeePost == 1) {
            $activeWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('50') . '</div> ' . $LANG['weveryone'];
        } else if ($userWhoCanSeePost == 2) {
            $activeWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('15') . '</div> ' . $LANG['wfollowers'];
        } else if ($userWhoCanSeePost == 3) {
            $activeWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('51') . '</div> ' . $LANG['wsubscribers'];
        } else if ($userWhoCanSeePost == 4) {
            $activeWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('9') . '</div> ' . $LANG['wpremium'];
        }
        /**
         * Retrieve all payment methods configuration
         */
        $method = $iN->iN_PaymentMethods();

        /**
         * CCBill payment configuration
         */
        $ccbill_AccountNumber = $method['ccbill_account_number'];
        $ccbill_SubAccountNumber = $method['ccbill_subaccount_number'];
        $ccbill_FlexID = $method['ccbill_flex_form_id'];
        $ccbill_SaltKey = $method['ccbill_salt_key'];
        $ccbill_Status = $method['ccbill_status'];
        $ccbill_Currency = $method['ccbill_currency'];

        /**
         * PayPal payment configuration
         */
        $payPalPaymentMode = $method['paypal_payment_mode'];
        $payPalPaymentStatus = $method['paypal_active_pasive'];
        $payPalPaymentSedboxBusinessEmail = $method['paypal_sendbox_business_email'];
        $payPalPaymentProductBusinessEmail = $method['paypal_product_business_email'];
        $payPalCurrency = $method['paypal_crncy'];
        /**
         * BitPay payment configuration
         */
        $bitPayPaymentMode = $method['bitpay_payment_mode'];
        $bitPayPaymentStatus = $method['bitpay_active_pasive'];
        $bitPayPaymentNotificationEmail = $method['bitpay_notification_email'];
        $bitPayPaymentPassword = $method['bitpay_password'];
        $bitPayPaymentPairingCode = $method['bitpay_pairing_code'];
        $bitPayPaymentLabel = $method['bitpay_label'];
        $bitPayPaymentCurrency = $method['bitpay_crncy'];

        /**
         * Stripe payment configuration
         */
        $stripePaymentMode = $method['stripe_payment_mode'];
        $stripePaymentStatus = $method['stripe_active_pasive'];
        $stripePaymentTestSecretKey = $method['stripe_test_secret_key'];
        $stripePaymentTestPublicKey = $method['stripe_test_public_key'];
        $stripePaymentLiveSecretKey = $method['stripe_live_secret_key'];
        $stripePaymentLivePublicKey = $method['stripe_live_public_key'];
        $stripePaymentCurrency = $method['stripe_crncy'];

        /**
         * Authorize.Net configuration
         */
        $autHorizePaymentMode = $method['authorize_payment_mode'];
        $autHorizePaymentStatus = $method['authorizenet_active_pasive'];
        $autHorizePaymentTestsApID = $method['authorizenet_test_ap_id'];
        $autHorizePaymentTestTransitionKey = $method['authorizenet_test_transaction_key'];
        $autHorizePaymentLiveApID = $method['authorizenet_live_api_id'];
        $autHorizePaymentLiveTransitionkey = $method['authorizenet_live_transaction_key'];
        $autHorizePaymentCurrency = $method['authorize_crncy'];
        // Select appropriate Authorize.Net keys based on payment mode
        if ($autHorizePaymentMode == '0') {
            $autName = $method['authorizenet_test_ap_id'];
            $autKey = $method['authorizenet_test_transaction_key'];
        } else {
            $autName = $method['authorizenet_live_api_id'];
            $autKey = $method['authorizenet_live_transaction_key'];
        }

        /**
         * iyziCo payment configuration
         */
        $iyziCoPaymentMode = $method['iyzico_payment_mode'];
        $iyziCoPaymentStatus = $method['iyzico_active_pasive'];
        $iyziCoPaymentTestSecretKey = $method['iyzico_testing_secret_key'];
        $iyziCoPaymentTestApiKey = $method['iyzico_testing_api_key'];
        $iyziCoPaymentLiveApiKey = $method['iyzico_live_api_key'];
        $iyziCoPaymentLiveApiSecret = $method['iyzico_live_secret_key'];
        $iyziCoPaymentCurrency = $method['iyzico_crncy'];

        /**
         * Razorpay payment configuration
         */
        $razorPayPaymentMode = $method['razorpay_payment_mode'];
        $razorPayPaymentStatus = $method['razorpay_active_pasive'];
        $razorPayPaymentTestKeyID = $method['razorpay_testing_key_id'];
        $razorPayPaymentTestSecretKey = $method['razorpay_testing_secret_key'];
        $razorPayPaymentLiveKeyID = $method['razorpay_live_key_id'];
        $razorPayPaymentLiveSecretKey = $method['razorpay_live_secret_key'];
        $razorPayPaymentCurrency = $method['razorpay_crncy'];
        /**
         * Paystack payment configuration
         */
        $payStackPaymentMode = $method['paystack_payment_mode'];
        $payStackPaymentStatus = $method['paystack_active_pasive'];
        $payStackPaymentTestSecretKey = $method['paystack_testing_secret_key'];
        $payStackPaymentTestPublicKey = $method['paystack_testing_public_key'];
        $payStackPaymentLiveSecretKey = $method['paystack_live_secret_key'];
        $payStackPaymentLivePublicKey = $method['pay_stack_liive_public_key'];
        $payStackPaymentCurrency = $method['paystack_crncy'];

        /**
         * CoinPayments configuration
         */
        $coinPaymentStatus = $method['coinpayments_status'];
        $coinPaymentPrivateKey = isset($method['coinpayments_private_key']) ? $method['coinpayments_private_key'] : NULL;
        $coinPaymentPublicKey = isset($method['coinpayments_public_key']) ? $method['coinpayments_public_key'] : NULL;
        $coinPaymentMerchandID = isset($method['coinpayments_merchand_id']) ? $method['coinpayments_merchand_id'] : NULL;
        $coinPaymentIPNSecret = isset($method['coinpayments_ipn_secret']) ? $method['coinpayments_ipn_secret'] : NULL;
        $coinPaymentDebugEmail = isset($method['coinpayments_debug_email']) ? $method['coinpayments_debug_email'] : NULL;
        $coinPaymentCryptoCurrency = isset($method['cp_cryptocurrencies']) ? $method['cp_cryptocurrencies'] : NULL;

        /**
         * MercadoPago configuration
         */
        $mercadoPagoMode = $method['mercadopago_payment_mode'];
        $mercadoPagoPaymentStatus = $method['mercadopago_active_pasive'];
        $mercadoPagoTestAccessTokenID = $method['mercadopago_test_access_id'];
        $mercadoPagoLiveAccessTokenID = $method['mercadopago_live_access_id'];
        $mercadoPagoCurrency = $method['mercadopago_currency'];
        /**
         * Bank transfer configuration
         */
        $bankPaymentStatus = $method['bank_payment_status'];
        $bankPaymentPercentageFee = $method['bank_payment_percentage_fee'];
        $bankPaymentFixedCharge = $method['bank_payment_fixed_charge'];
        $bankPaymentDetails = $method['bank_payment_details'];
        // Handle verification status alerts for logged-in user
        if ($verifData) {
            $verificationRequestStatus = $verifData['request_status'];
            $userReadStatus = $verifData['user_read_status'];

            if ($verificationRequestStatus == '0' && $userReadStatus != '1') {
                $verStatus = '<div class="i_postFormContainer"><div class="certification_terms">
                                <div class="certification_terms_item verirication_timing_bg"></div>
                                <div class="certification_terms_item">
                                    <div class="certificate_terms_item_item pendingTitle">' .
                                        $LANG['your_request_is_pending'] .
                                    '</div>
                                    <div class="certificate_terms_item_item">' .
                                        $LANG['you_will_notififed_when_it_is_processed'] .
                                    '</div>
                                </div>
                            </div></div>';
            } else if ($verificationRequestStatus == '1' && $userReadStatus != '1') {
                $verStatus = '<div class="i_postFormContainer"><div class="certification_terms">
                                <div class="certification_terms_item verification_approve_bg"></div>
                                <div class="certification_terms_item">
                                    <div class="certificate_terms_item_item pendingTitle">' .
                                        $LANG['congratulations_approved'] .
                                    '</div>
                                    <div class="certificate_terms_item_item">' .
                                        $LANG['congrat_approved_not'] .
                                    '</div>
                                </div>
                            </div></div>';
            } else if ($verificationRequestStatus == '2' && $userReadStatus != '1') {
                // Mark notification as read when rejected
                $iN->iN_UpdateVerificationAnswerReadStatus($userID);
                $verStatus = '<div class="i_postFormContainer"><div class="certification_terms">
                                <div class="certification_terms_item verification_reject_bg"></div>
                                <div class="certification_terms_item">
                                    <div class="certificate_terms_item_item pendingTitle">' .
                                        $LANG['sorry_rejected'] .
                                    '</div>
                                    <div class="certificate_terms_item_item">' .
                                        $LANG['sorry_you_are_rejected'] .
                                    '</div>
                                </div>
                            </div></div>';
            }
        }
        } else {
        // If no valid user ID, clear cookie and redirect to login
        setcookie($cookieName, '', time() - 31556926, '/');
        unset($_COOKIE[$cookieName]);
        header("Location: index.php");
        exit();
    }
} else {
    // Guest visitor (not logged in)
    $logedIn = '0';
    $certificationStatus = '0';
    $validationStatus = '0';
    $conditionStatus = '0';
    $feesStatus = '0';
    $payoutStatus = '0';

    // Auto-detect language if enabled
    if ($autoDetectLanguageStatus == '1') {
        include_once "getUserDetailsByipApi.php";
        $checkLangExist = $iN->iN_CheckLangKeyExist($registerCountryCode);

        // Set detected language if it exists in system
        if (strlen(trim($checkLangExist)) != 0) {
            $defaultLanguage = $registerCountryCode;
        }
    }

    // Load language pack for guests (subfolder-safe)
    $___lang_path = __resolve_lang_path(strtolower($defaultLanguage), $__LANG_DIR);
    include $___lang_path;

    // Clear any existing session cookie
    setcookie($cookieName, '', 1);
    setcookie($cookieName, '', time() - 31556926, '/');
}
?>
