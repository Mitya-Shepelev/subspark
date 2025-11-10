<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle);?></title>
    <?php
       include __DIR__ . "/layouts/header/meta.php";
       include __DIR__ . "/layouts/header/css.php";
       include __DIR__ . "/layouts/header/javascripts.php";
    ?>
</head>
<body data-adminfee="<?php echo $adminFee;?>" data-currencyleft="<?php echo $currencys[$defaultCurrency];?>">
<?php $page = 'moreposts'; if($logedIn == 0){ include __DIR__ . '/layouts/login_form.php'; }?>
<?php include __DIR__ . "/layouts/header/header.php";?>
<div class="landing_wrapper">
   <div class="landing_section_one flex_ tabing">
   <div class="landing_header_bg flex_ tabing dynamic-bg" data-img="<?php echo $base_url.$landingPageFirstImage;?>"></div>
        <div class="landing_section_in">
            <h1><?php echo $LANG['landing_title'];?></h1>
            <div class="landing_seciond_in_note"><?php echo $LANG['landing_desc'];?></div>
            <div class="landing_section_register">
                <div class="landing_reg flex_ tabing input-prepend">
                    <div class="input-group-text"><?php echo preg_replace( "#^[^:/.]*[:/]+#i", "", $base_url );?></div>
                    <input type="text" id="clName" class="landing_text" placeholder="<?php echo iN_HelpSecure($LANG['username']);?>">
                    <div class="i_singup_claim claimname"><?php echo $LANG['claim'];?></div>
                </div>
                <div class="error_report unmempt">
                   <?php echo iN_HelpSecure($LANG['username_should_not_be_empty']);?>
                </div>
                <div class="error_report unmexist">
                   <?php echo iN_HelpSecure($LANG['try_different_username']);?>
                </div>
                <div class="error_report invldcharctr">
                   <?php echo iN_HelpSecure($LANG['invalid_username']);?>
                </div>
            </div>
            <div class="landing_box_animation flex_ tabing ds-top-left ds-move-slower"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51'));?><?php echo $LANG['animation_box_subscriptions'];?></div>
            <div class="landing_box_animation flex_ tabing ds-btm-left ds-move-slow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?><?php echo $LANG['animation_box_premium_content'];?></div>
            <div class="landing_box_animation flex_ tabing ds-top-right ds-move-slower"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('136'));?><?php echo $LANG['animation_box_comissions'];?></div>
            <div class="landing_box_animation flex_ tabing ds-btm-right ds-move-slow"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('133'));?><?php echo $LANG['animation_box_live_streaming'];?></div>
        </div>
    
    <div class="area" >
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div >
    
   </div>
   
   <div class="landing_section_two">
      <div class="landing_arrow dynamic-bg" data-img="<?php echo $base_url.$landingpageFirstImageArrow;?>"></div>
      <div class="landing_section_two_in">
          <h2><?php echo $LANG['our_features_title'];?></h2>
          <div class="landing_features_list">
               <div class="flex_ tabing anlan">
               
               <div class="l_feature_box_container">
                   <img src="<?php echo $base_url.$landingpageFirstDesctiptionImage;?>">
                   <h3><?php echo $LANG['l_premium'];?></h3>
                   <div>
                     <?php echo $LANG['l_exlusive_contents'];?>
                   </div>
               </div>
                
               <div class="l_feature_box_container">
                   <img src="<?php echo $base_url.$landingpageSecondDesctiptionImage;?>">
                   <h3><?php echo $LANG['fan_club'];?></h3>
                   <div>
                      <?php echo $LANG['fan_club_desc'];?>
                   </div>
               </div>
                
               <div class="l_feature_box_container">
                   <img src="<?php echo $base_url.$landingpageThirdDesctiptionImage;?>">
                   <h3><?php echo $LANG['l_live_streamings'];?></h3>
                   <div>
                      <?php echo $LANG['l_live_streamings_desc'];?>
                   </div>
               </div>
                
               <div class="l_feature_box_container">
                   <img src="<?php echo $base_url.$landingpageFourthDesctiptionImage;?>">
                   <h3><?php echo $LANG['l_private_content'];?></h3>
                   <div>
                      <?php echo $LANG['l_private_content_desc'];?>
                   </div>
               </div>
               
               
               <div class="l_feature_box_container">
                   <img src="<?php echo $base_url.$landingpageFifthDesctiptionImage;?>">
                   <h3><?php echo $LANG['l_private_messages'];?></h3>
                   <div>
                   <?php echo $LANG['l_private_messages_desc'];?>
                   </div>
               </div>
               
               </div>
          </div>
      </div>
   </div>
   
   <div class="landing_section_three dynamic-bg" data-img="<?php echo $base_url.$landingPageSectionTwoBG;?>">
       <div class="landing_section_three_in flex_">
           <div class="landing_create_equal_box inmob"><img src="<?php echo $base_url.$landingSectionFeatureImage;?>"></div>
           <div class="landing_create_equal_box flex_ tabing column">
               <h2><?php echo $LANG['l_becomea_creator'];?></h2>
               <div>
                 <?php echo $LANG['l_join_our_community_now_and_start_growing_users'];?>
               </div>
           </div>
       </div>
   </div>
    
   <div class="landng_section_four">
       <div class="landng_section_four_in">
          <h2><?php echo iN_HelpSecure($LANG['best_creators_of_last_week']);?></h2>
          <div class="landing_features_list"> 
              <div class="creators_list_container">
                <?php
                $featuredCreators = $iN->iN_PopularUsersFromLastWeekInExplorePageLanding();
                if($featuredCreators){
                foreach($featuredCreators as $td){
                    $popularuserID = $td['post_owner_id'];
                    $uD = $iN->iN_GetUserDetails($popularuserID);
                    $popularUserAvatar = $iN->iN_UserAvatar($popularuserID, $base_url);
                    $creatorCover = $iN->iN_UserCover($popularuserID, $base_url);
                    $popularUserName = $td['i_username'];
                    $popularUserFullName = $td['i_user_fullname'];
                    $uPCategory = isset($uD['profile_category']) ? $uD['profile_category'] : NULL;
                    $totalPost = $iN->iN_TotalPosts($popularuserID);
                    $totalImagePost = $iN->iN_TotalImagePosts($popularuserID);
                    $totalVideoPosts = $iN->iN_TotalVideoPosts($popularuserID);
                    if($uPCategory){ $uCateg = '<div class="i_p_cards"> <div class="i_creator_category"><a href="'.iN_HelpSecure($base_url, FILTER_VALIDATE_URL).'creators?creator='.$uPCategory.'?>">'.html_entity_decode($iN->iN_SelectedMenuIcon('65')).$PROFILE_CATEGORIES[$uPCategory].'</a></div></div>';}else{$uCateg = '';}
                ?>
                    
                    <div class="creator_list_box_wrp">

                        <div class="creator_l_box flex_">
                            <a href="<?php echo $base_url.$popularUserName;?>">
                            <div class="creator_l_cover dynamic-bg" data-img="<?php echo iN_HelpSecure($creatorCover);?>"></div>
                            </a>
                            
                            <div class="creator_l_avatar_name">
                                <div class="creator_avatar_container">
                                   <a href="<?php echo $base_url.$popularUserName;?>">
                                     <div class="creator_avatar"><img src="<?php echo iN_HelpSecure($popularUserAvatar);?>"></div>
                                   </a>
                                </div>
                                <div class="creator_nm truncated">
                                   <a href="<?php echo $base_url.$popularUserName;?>">
                                      <?php echo iN_HelpSecure($iN->iN_Secure($popularUserFullName));?>
                                   </a>
                                </div>
                                <?php echo $uCateg;?>
                                
                                <div class="i_p_items_box_">
                                    <div class="i_btn_item_box transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('67'));?> <?php echo iN_HelpSecure($totalPost);?>
                                    </div>
                                    <div class="i_btn_item_box transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('68'));?> <?php echo iN_HelpSecure($totalImagePost);?>
                                    </div>
                                    <div class="i_btn_item_box transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52'));?> <?php echo iN_HelpSecure($totalVideoPosts);?>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                <?php  } } ?>
                </div> 
          </div>
       </div>
   </div>
    
   <div class="landing_section_five">
       <div class="landing_section_five_in">
           <h2><?php echo $LANG['creators_earning_simulator'];?></h2>
           <p><?php echo $LANG['calculate_how_much_can_earn'];?></p>
           <div class="ranges flex_ tabing">
            <div class="landing_create_equal_box">
                <label for="rangeNumberFollowers">
                <div class="smiulator_helper tabing_non_justify flex_">
                    <?php echo $LANG['l_number_of_followers'];?>
                    <span style="display:inline-block;width:28px;height:28px;margin:0 3px;"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('189'));?></span>
                    <span style="display:inline-block;width:28px;height:28px;margin:0 3px;"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('190'));?></span>
                    <span style="display:inline-block;width:28px;height:28px;margin:0 3px;"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('191'));?></span>
                    <div class="helper_right flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('43'));?><span id="numberFollowers">1000</span></div>
                </div>
                </label>
            </div>
            <div class="landing_create_equal_box">
                <div class="smiulator_helper tabing_non_justify flex_">
                    <?php echo $LANG['l_monthly_subscription_price'];?>
                    <div class="helper_right flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51'));?>₽<span id="monthlySubscription">190</span></div>
                </div>
            </div>
            </div>
           <div class="ranges_ flex_ tabing">
            <div class="landing_create_equal_box horizontally-stacked-slider">
                <input type="range" class="custom-range" value="0" min="1000" max="1000000" id="rangeNumberFollowers">
            </div>
            <div class="landing_create_equal_box horizontally-stacked-slider">
                <input type="range" class="custom-range" value="190" min="190" max="9500" id="rangeMonthlySubscription">
            </div>
            </div>
            <div class="landing_sec_">
                <h2>
                   <?php echo $LANG['per_month_calculate_earn'];?>
                </h2>
                <p><?php echo $LANG['not_for_calculate'];?></p>
            </div>
       </div>
   </div>
    
   <div class="landing_section_six">
     <div class="landing_section_six_in"> 
         <ul class="accordion">
             <?php
             $qa = $iN->iN_ListQuestionAnswerFromLanding();
             if($qa){
                foreach($qa as $qaData){
                    $qaTitle = $qaData['qa_title'];
                    $qaDesc = $qaData['qa_description'];
             ?>
                <li>
                    <a class="toggle" href="javascript:void(0);"><?php echo iN_HelpSecure($iN->iN_Secure($qaTitle));?></a>
                    <ul class="inner">
                    <li><?php echo iN_HelpSecure($iN->iN_Secure($qaDesc));?></li>
                    </ul>
                </li>
            <?php }
             }
            ?>
        </ul> 
     </div>
   </div>
   
<div class="landing-sticky-footer flex_ tabing">
  <div class="landing-sticky-footer-in flex_ tabing">
     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('66'));?><?php echo html_entity_decode($LANG['animatesignup']);?>
  </div>
</div>
<div class="footer_container_out"><?php include __DIR__ . "/layouts/footer.php";?></div>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/landingBg.js"></script>
</div> 
</body>
</html>
