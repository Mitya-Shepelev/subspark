<div class="i_admin_left">
  <div class="i_admin_left_menu_header flex_ tabing_non_justify">
    <div class="ad_le_i flex_ tabing border_two clps"><div class="cl_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('102'));?></div></div>
    <a class="flex_ tabing_non_justify" href="<?php echo iN_HelpSecure($base_url);?>"><img src="<?php echo iN_HelpSecure($siteLogoUrl);?>"><div class="dash_title flex_ tabing lm"><?php echo iN_HelpSecure($LANG['admin_dashboard']);?></div></a>
  </div>
  <div class="i_admin_menu_wrapper flex_ column">
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>index">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'index' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('107'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['dashboard']);?></div>
        </div>
      </a>
        <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['general', 'limits','website_settings','billing_informations','affiliate_settings','boost_package_settings','gender_settings']) ? "active_p" : ""; ?>" data-id="settings"><?php $cmrl = TRUE;if (!function_exists('curl_init')) {$cmrl = FALSE;}?>
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('108'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['settings']);?></div>
            <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
        </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['general', 'limits','website_settings', 'billing_informations','affiliate_settings','profile_categories_and_subcategories','boost_package_settings','gender_settings']) ? 'sub_in' : '' ?>" id="settings">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>website_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'website_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['website_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>general">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'general' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['general']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>gender_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'gender_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['gender_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>limits">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'limits' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['limits']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>billing_informations">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'billing_informations' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['billing_informations']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>affiliate_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'affiliate_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['affiliate_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>profile_categories_and_subcategories">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'profile_categories_and_subcategories' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['profile_categories_and_subcategories']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>boost_package_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'boost_package_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['boost_package_settings']);?></div>
              </div>
            </a>
        </div>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>transactions">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'transactions' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('179'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['transactions']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>point_earnings">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'point_earnings' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['all_point_earning']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>ai_generator">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'ai_generator' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('184'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_generate_ai_content']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>email_settings">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'email_settings' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('71'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['email_settings']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>live_streaming_settings">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'live_streaming_settings' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['live_streaming_settings']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_products">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'manage_products' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['u_products']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_boosted_posts">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'manage_boosted_posts' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('178'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_boosted_posts']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_subscriptions">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'manage_subscriptions' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_subscriptions']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_social_profiles">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'manage_social_profiles' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('126'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_social_profiles']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_website_social_profiles">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'manage_website_social_profiles' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('126'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_website_social_profiles']);?></div>
        </div>
      </a>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_announcement">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'manage_announcement' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('171'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_announcement']);?></div>
        </div>
      </a>
      <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['storage_settings', 'oceansettings', 'wasabi_settings', 'minio_settings', 'selectel_settings']) ? "active_p" : ""; ?>" data-id="storage_setting">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('109'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['storage']);?></div>
            <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
      </div>
      <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['storage_settings', 'oceansettings', 'wasabi_settings', 'minio_settings', 'selectel_settings']) ? 'sub_in' : '' ?>" id="storage_setting">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>storage_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'storage_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['s3_storage_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>oceansettings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'oceansettings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['digital_ocean_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>wasabi_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'wasabi_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['wasabi_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>minio_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'minio_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['minio_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>selectel_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'selectel_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['selectel_settings']);?></div>
              </div>
            </a>
        </div>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>contact_mails">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'contact_mails' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></div>
            <div class="flex_ lm">
                <?php echo iN_HelpSecure($LANG['questions_from_users']);?>
            </div>
            <?php if($iN->iN_CalculateAllUnreadQuestions() != '0'){?>
            <div class="flex_ tabing counterLeft"><?php echo iN_HelpSecure($iN->iN_CalculateAllUnreadQuestions());?></div>
            <?php } ?>
        </div>
      </a>
      <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['allPosts', 'premiumPosts','for_subscribers', 'awaiting_approval','storiePosts']) ? "active_p" : ""; ?>" data-id="manage_posts">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('110'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_posts']);?></div>
            <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
            <?php echo ($iN->iN_CalculateNonApprovedPosts() > 0) ? '<div class="pulse_not"></div>' : '';?>
      </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['allPosts', 'premiumPosts','for_subscribers', 'awaiting_approval','storiePosts']) ? 'sub_in' : '' ?>" id="manage_posts">

            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>awaiting_approval">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'awaiting_approval' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['awaiting_approval_posts']);?></div>
                <?php if($iN->iN_CalculateNonApprovedPosts() > 0){?><div class="flex_ tabing counterLeft"><?php echo $iN->iN_CalculateNonApprovedPosts();?></div><?php }?>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>storiePosts">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'storiePosts' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_storie_posts']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>allPosts">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'allPosts' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['posts']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>premiumPosts">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'premiumPosts' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['premium_posts']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>for_subscribers">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'for_subscribers' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['for_subscribers']);?></div>
              </div>
            </a>
        </div>
      <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['reported_posts', 'reported_comments']) ? "active_p" : ""; ?>" data-id="reported_posts">
          <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('32'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['reports']);?></div>
          <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
          <?php echo ($iN->iN_GetTotalReportedPost($userID) > 0 || $iN->iN_GetTotalReportedComment($userID) > 0) ? '<div class="pulse_not"></div>' : '';?>
      </div>
      <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['reported_posts', 'reported_comments']) ? 'sub_in' : '' ?>" id="reported_posts">

            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>reported_posts">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'reported_posts' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['reported_posts']);?> <div class="flex_ tabing counterLeft"><?php echo $iN->iN_GetTotalReportedPost($userID);?></div></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>reported_comments">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'reported_comments' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['reported_comments']);?> <div class="flex_ tabing counterLeft"><?php echo $iN->iN_GetTotalReportedComment($userID);?></div></div>
              </div>
            </a>

        </div>
      <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['customcssjs', 'svgicons','manage_landing_page','landing_question_answer','text_story_backgrounds']) ? "active_p" : ""; ?>" data-id="design">
          <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('117'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['design']);?></div>
          <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
      </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['customcolors','customcssjs', 'svgicons','manage_landing_page','landing_question_answer','text_story_backgrounds']) ? 'sub_in' : '' ?>" id="design">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>customcolors">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'customcolors' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['custom_colors']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>customcssjs">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'customcssjs' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['custom_css_js']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>svgicons">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'svgicons' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manageicons']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_landing_page">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'manage_landing_page' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_landing_page']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>landing_question_answer">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'landing_question_answer' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['landing_question_answer']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>text_story_backgrounds">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'text_story_backgrounds' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['text_story_backgrounds']);?></div>
              </div>
            </a>
        </div>
      <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['manage_point_packages','manage_point_packages_live','manage_point_settings']) ? "active_p" : ""; ?>" data-id="point">
          <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_point_feature']);?></div>
          <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
      </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['manage_point_packages','manage_point_packages_live','manage_point_settings','manage_frame_packages']) ? 'sub_in' : '' ?>" id="point">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_point_packages">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'manage_point_packages' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['point_packages_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_point_packages_live">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'manage_point_packages_live' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['live_point_packages_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_frame_packages">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'manage_frame_packages' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['frame_package_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_point_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'manage_point_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_point_settings']);?></div>
              </div>
            </a>
        </div>
      <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>languages">
        <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'languages' ? "active_p" : ""; ?>">
            <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('1'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['languages']);?></div>
        </div>
      </a>
      <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['manage_users','creator_requests','fake_user_generator']) ? "active_p" : ""; ?>" data-id="user">
          <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['users']);?></div>
          <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
          <?php echo $iN->iN_TotalVerificationRequests() > 1 ? '<div class="pulse_not"></div>' : '';?>
      </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['manage_users','creator_requests','fake_user_generator']) ? 'sub_in' : '' ?>" id="user">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_users">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'manage_users' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_users']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>creator_requests">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'creator_requests' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['creator_requests']);?></div>
                <?php if($iN->iN_TotalVerificationRequests() != '0'){?>
                <div class="flex_ tabing counterLeft"><?php echo iN_HelpSecure($iN->iN_TotalVerificationRequests());?></div>
                <?php } ?>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>fake_user_generator">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'fake_user_generator' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['fake_user_generator']);?></div>
              </div>
            </a>
        </div>
        <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>pages">
          <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'pages' ? "active_p" : ""; ?>">
              <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('124'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['pages']);?></div>
          </div>
        </a>
        <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_stickers">
          <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'manage_stickers' ? "active_p" : ""; ?>">
              <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('24'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_stickers']);?></div>
          </div>
        </a>
        <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>giphy_settings">
          <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'giphy' ? "active_p" : ""; ?>">
              <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('23'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['giphy_settings']);?></div>
          </div>
        </a>
      <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['payment_settings','paypal','bitpay','stripe','authorizenet','iyzico','razorpay','paystack','ccbill_subscription_settings','mercadopago','bankpayment']) ? "active_p" : ""; ?>" data-id="payments">
        <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['payment_methods']);?></div>
        <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
      </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['payment_settings','paypal','bitpay','stripe_subscribtion_settings','stripe','authorizenet','iyzico','razorpay','paystack','ccbill_subscription_settings','coinpayment_settings','mercadopago','bankpayment']) ? 'sub_in' : '' ?>" id="payments">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>payment_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'payment_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['payment_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>paypal">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'paypal' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['paypal_payment']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>stripe_subscribtion_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'stripe_subscribtion_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['stripe_payment_subs']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>coinpayment_settings">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'coinpayment_settings' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['coinpayment_settings']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>stripe">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'stripe' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['stripe_payment']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>authorizenet">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'authorizenet' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['authorizenet_payment']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>iyzico">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'iyzico' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['iyzico_payment']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>razorpay">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'razorpay' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['razorpay_payment']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>paystack">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'paystack' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['paystack_payment']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>mercadopago">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'mercadopago' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['mercadopago_payment']);?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>bankpayment">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'bankpayment' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['bankpayment']);?></div>
              </div>
            </a>
        </div>
        <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>social_logins">
          <div class="menu_item flex_ tabing_non_justify transition border_one <?php echo iN_HelpSecure($pageFor) == 'social_logins' ? "active_p" : ""; ?>">
              <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('126'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['social_logins']);?></div>
          </div>
        </a>
        <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['manage_withdrawals', 'manage_subscription_payments']) ? "active_p" : ""; ?>" data-id="wspayments">
          <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('127'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_payments']);?></div>
          <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
          <?php echo $iN->iN_TotalUsersWithdrawals() > 1 ? '<div class="pulse_not"></div>' : '';?>
        </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['manage_withdrawals', 'manage_subscription_payments']) ? 'sub_in' : '' ?>" id="wspayments">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>manage_withdrawals">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'manage_withdrawals' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['manage_withdrawals'])?><div class="flex_ tabing counterLeft"><?php echo $iN->iN_TotalUsersWithdrawals();?></div></div>
              </div>
            </a>
        </div>
        <div class="menu_item subCaller flex_ tabing_non_justify transition border_one <?php echo in_array($pageFor, ['create_advertisement', 'managa_advertisements']) ? "active_p" : ""; ?>" data-id="ads">
          <div class="flex_ tabing menu_svg"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('132'));?></div><div class="flex_ lm"><?php echo iN_HelpSecure($LANG['advertisement_']);?></div>
          <div class="sub_menu_arrow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
        </div>
        <div class="sub_menu_wrapper border_one flex_ column <?php echo in_array($pageFor, ['create_advertisement', 'managa_advertisements']) ? 'sub_in' : '' ?>" id="ads">
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>create_advertisement">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'create_advertisement' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['create_advertisement'])?></div>
              </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url).'admin/';?>managa_advertisements">
              <div class="sub_menu_item transition flex_ tabing_non_justify border_one <?php echo iN_HelpSecure($pageFor) == 'managa_advertisements' ? "active_p" : ""; ?>">
                <div class="flex_ lm"><?php echo iN_HelpSecure($LANG['managa_advertisements'])?></div>
              </div>
            </a>
        </div>
  </div>

  <div class="legal">
    <div class="copyright">
        Copyright © <?php echo date("Y"); ?>   <a href="javascript:void(0);"> <?php echo $siteName; ?> - </a> <?php echo $scriptVersion; ?>
    </div>
</div>
</div>
