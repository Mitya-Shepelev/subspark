<div class="leftSticky mobile_left">
    <div class="i_left_container">
        <div class="leftSidebar_in">
            <div class="leftSidebarWrapper">
                <div class="btest">
                    <!-- Home Menu -->
                    <a href="<?php echo iN_HelpSecure($base_url); ?>">
                        <div class="i_left_menu_box transition">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('99')); ?>
                            <div class="m_tit"><?php echo iN_HelpSecure($LANG['home_page']); ?></div>
                        </div>
                    </a>
                    <!-- Reels Posts -->
                    <?php if (isset($reelsFeatureStatus) && (string)$reelsFeatureStatus === '1') : ?>
                        <a href="<?php echo iN_HelpSecure($base_url); ?>reels/">
                            <div class="i_left_menu_box transition">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('187')); ?>
                                <div class="m_tit"><?php echo iN_HelpSecure($LANG['reels']); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                    <!-- Trending Posts -->
                    <div class="i_left_menu_box transition g_feed" data-get="trendposts" data-type="moretrendposts">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('181')); ?>
                        <div class="m_tit"><?php echo iN_HelpSecure($LANG['trend_posts']); ?></div>
                    </div>

                    <?php if ($iN->iN_ShopStatus(1) === 'yes') { ?>
                        <!-- Marketplace -->
                        <a href="<?php echo iN_HelpSecure($base_url); ?>marketplace?cat=all">
                            <div class="i_left_menu_box transition">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158')); ?>
                                <div class="m_tit"><?php echo iN_HelpSecure($LANG['marketplace']); ?></div>
                            </div>
                        </a>
                    <?php } ?>

                    <?php if ($logedIn === '1') { ?>
                        <!-- Profile -->
                        <a href="<?php echo iN_HelpSecure($userProfileUrl); ?>">
                            <div class="i_left_menu_box transition">
                                <div class="i_left_menu_profile_avatar">
                                    <img src="<?php echo iN_HelpSecure($userAvatar); ?>" alt="<?php echo iN_HelpSecure($userFullName); ?>" />
                                </div>
                                <div class="m_tit"><?php echo iN_HelpSecure($LANG['profile']); ?></div>
                            </div>
                        </a>

                        <?php if ($feesStatus === '2') { ?>
                            <!-- Dashboard -->
                            <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=dashboard">
                                <div class="i_left_menu_box transition">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('35')); ?>
                                    <div class="m_tit"><?php echo iN_HelpSecure($LANG['dashboard']); ?></div>
                                </div>
                            </a>
                        <?php } ?>

                        <!-- Purchased Premium Posts -->
                        <div class="i_left_menu_box transition g_feed" data-get="purchasedpremiums" data-type="morepurchased">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('176')); ?>
                            <div class="m_tit"><?php echo iN_HelpSecure($LANG['purchased_premium_posts']); ?></div>
                        </div>

                        <?php if ($boostedPostEnableDisable === 'yes' && $iN->iN_CheckHaveBoostedPost($userID) > 0) { ?>
                            <!-- Boosted Posts -->
                            <div class="i_left_menu_box transition g_feed" data-get="boostedposts" data-type="moreboostedposts">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('178')); ?>
                                <div class="m_tit"><?php echo iN_HelpSecure($LANG['boosted_posts']); ?></div>
                            </div>
                        <?php } ?>

                        <!-- News Feed -->
                        <div class="i_left_menu_box transition g_feed" data-get="friends" data-type="moreposts">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('7')); ?>
                            <div class="m_tit"><?php echo iN_HelpSecure($LANG['newsfeed']); ?></div>
                        </div>

                        <!-- Explore -->
                        <div class="i_left_menu_box transition g_feed" data-get="allPosts" data-type="moreexplore">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('8')); ?>
                            <div class="m_tit"><?php echo iN_HelpSecure($LANG['explore']); ?></div>
                        </div>

                        <!-- Premium -->
                        <div class="i_left_menu_box transition g_feed" data-get="premiums" data-type="morepremium">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9')); ?>
                            <div class="m_tit"><?php echo iN_HelpSecure($LANG['premium']); ?></div>
                        </div>

                        <!-- Our Creators -->
                        <a href="<?php echo iN_HelpSecure($base_url); ?>creators">
                            <div class="i_left_menu_box transition">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('95')); ?>
                                <div class="m_tit"><?php echo iN_HelpSecure($LANG['our_creators']); ?></div>
                            </div>
                        </a>
                    <?php } ?>

                    <?php if ($agoraStatus === '1' && $page !== 'profile') { ?>
                        <?php if ($logedIn === '1' && $paidLiveStreamingStatus === '1' && $feesStatus === '2') { ?>
                            <!-- Paid Live Streaming -->
                            <div class="live_item_cont paidLive">
                                <div class="new_s_one new_s_first cNLive" data-type="paidLive">
                                    <div class="flex_ alignItem">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                                        <?php echo iN_HelpSecure($LANG['start_new_paid_live_stream']); ?>
                                    </div>
                                </div>
                                <a href="<?php echo iN_HelpSecure($base_url); ?>live_streams?live=paid">
                                    <div class="live_item transition">
                                        <div class="live_title flex_">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('133')); ?>
                                            <?php echo iN_HelpSecure($LANG['paid_live_streamings']); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ($logedIn === '1' && $freeLiveStreamingStatus === '1') { ?>
                            <!-- Free Live Streaming -->
                            <div class="live_item_cont freeLive">
                                <div class="new_s_one new_s_second cNLive" data-type="freeLive">
                                    <div class="flex_ alignItem">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                                        <?php echo iN_HelpSecure($LANG['start_new_free_live_stream']); ?>
                                    </div>
                                </div>
                                <a href="<?php echo iN_HelpSecure($base_url); ?>live_streams?live=free">
                                    <div class="live_item transition">
                                        <div class="live_title flex_">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('134')); ?>
                                            <?php echo iN_HelpSecure($LANG['free_live_streams']); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
