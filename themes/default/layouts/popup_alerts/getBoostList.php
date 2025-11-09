<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="boostPostModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in modal_tip">
        <div class="i_modal_content">
            <!-- Boost Icon & Close -->
            <div class="boostListIconContainer">
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
                <div class="boostListIcon flex_ justify-content align-items-center" id="boostPostModalTitle">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('177')); ?>
                </div>
            </div>

            <!-- Boost Plans -->
            <div class="boostListContainer">
                <?php
                $purchasBoostPlan = $iN->iN_BoostPlans();
                if ($purchasBoostPlan) {
                    foreach ($purchasBoostPlan as $boostData) {
                        $planID = $boostData['plan_id'] ?? null;
                        $planName = $boostData['plan_name_key'] ?? null;
                        $planCreditAmount = $boostData['plan_amount'] ?? null;
                        $planAmount = $boostData['amount'] ?? null;
                        $planIcon = $boostData['plan_icon'] ?? null;
                        $planViewTime = $boostData['view_time']  ?? null;
                        $mnText = preg_replace('/{.*?}/', $planViewTime, $LANG['after_choose_boost_not']);
                        ?>
                        <div class="boost_plan_item flex_ tabing bThisP"
                             id="<?php echo iN_HelpSecure($planID); ?>"
                             data-post="<?php echo iN_HelpSecure($boostPostID); ?>"
                             role="button"
                             aria-label="<?php echo iN_HelpSecure($planName); ?>">
                            <div class="boost_plan_item_icon flex_ justify-content align-items-center">
                                <?php echo html_entity_decode($planIcon); ?>
                            </div>
                            <div class="boost_plan_item_name_boost_plan_description">
                                <div class="boost_plan_item_name"><?php echo iN_HelpSecure($planName); ?></div>
                                <div class="boost_plan_description"><?php echo html_entity_decode($mnText); ?></div>
                            </div>
                            <div class="boost_amount_item_icon flex_ justify-content align-items-center">
                                <?php echo iN_HelpSecure($planCreditAmount); ?>
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                            </div>
                        </div>
                    <?php }
                } ?>
                
                <!-- Info Section -->
                <div class="arrow"></div>
                <div class="boost_post_is border_bottom_radius">
                    <?php echo iN_HelpSecure($LANG['what_is_boost_post']); ?>
                </div>
                <div class="warning_boost_post flex_ justify-content align-items-center">
                    <?php echo html_entity_decode($LANG['warning_for_boost_point']); ?>
                </div>
            </div>
        </div>
    </div>
    <!--/SHARE-->
</div>