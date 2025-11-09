<!-- Primary Meta Tags -->
<base href="<?php echo iN_HelpSecure(route_url('')); ?>">
<meta name="title" content="<?php echo iN_HelpSecure($siteTitle);?>">
<meta name="description" content="<?php echo iN_HelpSecure($siteDescription);?>">
<meta name="keywords" content="<?php echo iN_HelpSecure($siteKeyWords);?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo iN_HelpSecure($metaBaseUrl);?>">
<meta property="og:title" content="<?php echo iN_HelpSecure($siteTitle);?>">
<meta property="og:description" content="<?php echo iN_HelpSecure($siteDescription);?>">
<meta property="og:image" content="<?php echo iN_HelpSecure($metaBaseUrl);?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo iN_HelpSecure($metaBaseUrl);?>">
<meta property="twitter:title" content="<?php echo iN_HelpSecure($siteTitle);?>">
<meta property="twitter:description" content="<?php echo iN_HelpSecure($siteDescription);?>">
<meta property="twitter:image" content="<?php echo iN_HelpSecure($metaBaseUrl);?>">

<meta name="theme-color" content="<?php echo $lightDark == 'light' ? '#f65169' : '#18191A';?>">
<link rel="shortcut icon" type="image/png" href="<?php echo iN_HelpSecure($siteFavicon);?>" sizes="128x128">
<link rel="manifest" href="<?php echo iN_HelpSecure($base_url);?>src/manifest.json">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="apple-touch-icon" sizes="192x192" href="<?php echo iN_HelpSecure($base_url);?>src/192x192.png">
