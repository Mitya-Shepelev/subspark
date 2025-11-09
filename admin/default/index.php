<?php

if ($logedIn == '0') {
    header('Location: ' . route_url(''));
}

if ($pageFor) {
    $todayIs = date('j');

    switch ($pageFor) {
        case 'index':
        case 'index.php':
            include 'sources/main.php';
            break;
        case 'limits':
        case 'limits.php':
            include 'sources/limits.php';
            break;
        case 'general':
        case 'general.php':
            include 'sources/general.php';
            break;
        case 'website_settings':
        case 'website_settings.php':
            include 'sources/website_settings.php';
            break;
        case 'gender_settings':
        case 'gender_settings.php':
            include 'sources/gender_settings.php';
            break;
        case 'billing_informations':
        case 'billing_informations.php':
            include 'sources/billing_informations.php';
            break;
        case 'affiliate_settings':
        case 'affiliate_settings.php':
            include 'sources/affiliate_settings.php';
            break;
        case 'manage_point_settings':
        case 'manage_point_settings.php':
            include 'sources/manage_point_settings.php';
            break;
        case 'email_settings':
        case 'email_settings.php':
            include 'sources/email_settings.php';
            break;
        case 'storage_settings':
        case 'storage_settings.php':
            include 'sources/storage_settings.php';
            break;
        case 'minio_settings':
        case 'minio_settings.php':
            include 'sources/minio_settings.php';
            break;
        case 'selectel_settings':
        case 'selectel_settings.php':
            include 'sources/selectel_settings.php';
            break;
        case 'oceansettings':
        case 'oceansettings.php':
            include 'sources/oceansettings.php';
            break;
        case 'wasabi_settings':
        case 'wasabi_settings.php':
            include 'sources/wasabi_settings.php';
            break;
        case 'awaiting_approval':
        case 'awaiting_approval.php':
            include 'sources/awaiting_approval.php';
            break;
        case 'allPosts':
        case 'allPosts.php':
            include 'sources/allPosts.php';
            break;
        case 'premiumPosts':
        case 'premiumPosts.php':
            include 'sources/premiumPosts.php';
            break;
        case 'for_subscribers':
        case 'for_subscribers.php':
            include 'sources/for_subscribers.php';
            break;
        case 'customcssjs':
        case 'customcssjs.php':
            include 'sources/customcssjs.php';
            break;
        case 'svgicons':
        case 'svgicons.php':
            include 'sources/svgicons.php';
            break;
        case 'giphy_settings':
        case 'giphy_settings.php':
            include 'sources/giphy_settings.php';
            break;
        case 'manage_point_packages':
        case 'manage_point_packages.php':
            include 'sources/manage_point_packages.php';
            break;
        case 'manage_point_packages_live':
        case 'manage_point_packages_live.php':
            include 'sources/manage_point_packages_live.php';
            break;
        case 'storiePosts':
        case 'storiePosts.php':
            include 'sources/storiePosts.php';
            break;
        case 'languages':
        case 'languages.php':
            include 'sources/languages.php';
            break;
        case 'manage_users':
        case 'manage_users.php':
            include 'sources/manage_users.php';
            break;
        case 'manage_social_profiles':
        case 'manage_social_profiles.php':
            include 'sources/manage_social_profiles.php';
            break;
        case 'manage_products':
        case 'manage_products.php':
            include 'sources/manage_products.php';
            break;
        case 'fake_user_generator':
        case 'fake_user_generator.php':
            include 'sources/fake_user_generator.php';
            break;
        case 'login_as_user':
        case 'login_as_user.php':
            include 'sources/login_as_user.php';
            break;
        case 'creator_requests':
        case 'creator_requests.php':
            include 'sources/creator_requests.php';
            break;
        case 'pages':
        case 'pages.php':
            include 'sources/pages.php';
            break;
        case 'manage_stickers':
        case 'manage_stickers.php':
            include 'sources/manage_stickers.php';
            break;
        case 'payment_settings':
        case 'payment_settings.php':
            include 'sources/payment_settings.php';
            break;
        case 'paypal':
        case 'paypal.php':
            include 'sources/paypal.php';
            break;
        case 'bitpay':
        case 'bitpay.php':
            include 'sources/bitpay.php';
            break;
        case 'stripe_subscribtion_settings':
        case 'stripe_subscribtion_settings.php':
            include 'sources/stripe_subscribtion_settings.php';
            break;
        case 'stripe':
        case 'stripe.php':
            include 'sources/stripe.php';
            break;
        case 'coinpayment_settings':
        case 'coinpayment_settings.php':
            include 'sources/coinpayment_settings.php';
            break;
        case 'authorizenet':
        case 'authorizenet.php':
            include 'sources/authorizenet.php';
            break;
        case 'iyzico':
        case 'iyzico.php':
            include 'sources/iyzico.php';
            break;
        case 'razorpay':
        case 'razorpay.php':
            include 'sources/razorpay.php';
            break;
        case 'paystack':
        case 'paystack.php':
            include 'sources/paystack.php';
            break;
        case 'mercadopago':
        case 'mercadopago.php':
            include 'sources/mercadopago.php';
            break;
        case 'bankpayment':
        case 'bankpayment.php':
            include 'sources/bankpayment.php';
            break;
        case 'social_logins':
        case 'social_logins.php':
            include 'sources/social_logins.php';
            break;
        case 'manage_withdrawals':
        case 'manage_withdrawals.php':
            include 'sources/manage_withdrawals.php';
            break;
        case 'manage_subscription_payments':
        case 'manage_subscription_payments.php':
            include 'sources/manage_subscription_payments.php';
            break;
        case 'create_advertisement':
        case 'create_advertisement.php':
            include 'sources/create_advertisement.php';
            break;
        case 'managa_advertisements':
        case 'managa_advertisements.php':
            include 'sources/managa_advertisements.php';
            break;
        case 'live_streaming_settings':
        case 'live_streaming_settings.php':
            include 'sources/live_streaming_settings.php';
            break;
        case 'manage_landing_page':
        case 'manage_landing_page.php':
            include 'sources/manage_landing_page.php';
            break;
        case 'landing_question_answer':
        case 'landing_question_answer.php':
            include 'sources/landing_question_answer.php';
            break;
        case 'ccbill_subscription_settings':
        case 'ccbill_subscription_settings.php':
            include 'sources/ccbill_subscription_settings.php';
            break;
        case 'contact_mails':
        case 'contact_mails.php':
            include 'sources/contact_mails.php';
            break;
        case 'reported_posts':
        case 'reported_posts.php':
            include 'sources/reported_posts.php';
            break;
        case 'reported_comments':
        case 'reported_comments.php':
            include 'sources/reported_comments.php';
            break;
        case 'manage_affilate_settings':
        case 'manage_affilate_settings.php':
            include 'sources/manage_affilate_settings.php';
            break;
        case 'text_story_backgrounds':
        case 'text_story_backgrounds.php':
            include 'sources/text_story_backgrounds.php';
            break;
        case 'manage_announcement':
        case 'manage_announcement.php':
            include 'sources/manage_announcement.php';
            break;
        case 'manage_website_social_profiles':
        case 'manage_website_social_profiles.php':
            include 'sources/manage_website_social_profiles.php';
            break;
        case 'profile_categories_and_subcategories':
        case 'profile_categories_and_subcategories.php':
            include 'sources/profile_categories_and_subcategories.php';
            break;
        case 'manage_boosted_posts':
        case 'manage_boosted_posts.php':
            include 'sources/manage_boosted_posts.php';
            break;
        case 'manage_subscriptions':
        case 'manage_subscriptions.php':
            include 'sources/manage_subscriptions.php';
            break;
        case 'boost_package_settings':
        case 'boost_package_settings.php':
            include 'sources/boost_package_settings.php';
            break;
        case 'transactions':
        case 'transactions.php':
            include 'sources/transactions.php';
            break;
        case 'point_earnings':
        case 'point_earnings.php':
            include 'sources/point_earnings.php';
            break;
        case 'customcolors':
        case 'customcolors.php':
            include 'sources/customcolors.php';
            break;
        case 'manage_frame_packages':
        case 'manage_frame_packages.php':
            include 'sources/manage_frame_packages.php';
            break;
        case 'ai_generator':
        case 'ai_generator.php':
            include 'sources/ai_generator.php';
            break;
        default:
            include 'sources/main.php';
            break;
    }
}
?>
