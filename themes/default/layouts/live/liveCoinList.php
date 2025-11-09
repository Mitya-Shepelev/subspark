<!-- Gif Coin List -->
<div class="live_gif_coins_list">
    <div class="live_gif_coins_list_wrapper">
        <!-- SWIPER START -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper blackColor">
                <?php if (!empty($sendCoinList)) : ?>
                    <?php foreach ($sendCoinList as $planData) :
                        $planID = $planData['gift_id'] ?? null;
                        $planName = $planData['gift_name'] ?? null;
                        $planCreditAmount = $planData['gift_point'] ?? null;
                        $planAmount = $planData['gift_money_equal'] ?? null;
                        $planImage = isset($planData['gift_image']) ? $base_url . $planData['gift_image'] : null;
                    ?>
                        <div class="swiper-slide blackColor">
                            <div class="live_gift_coin_container co_<?php echo iN_HelpSecure($planID); ?>">
                                <div class="live_gift_coin_avatar">
                                    <img src="<?php echo iN_HelpSecure($planImage); ?>" alt="<?php echo iN_HelpSecure($planName); ?>" loading="lazy" width="42">
                                </div>
                                <div class="live_gift_hv">
                                    <div class="live_gift_coin_name">
                                        <?php echo iN_HelpSecure($planName); ?>
                                    </div>
                                    <div class="live_gift_coin_amount">
                                        <?php echo iN_HelpSecure($planCreditAmount) . ' ' . iN_HelpSecure($LANG['coins']); ?>
                                    </div>
                                </div>
                                <div class="live_gift_coin_btn">
                                    <div class="live_coin_btn flex_ tabing">
                                        <button
                                            type="button"
                                            class="live_coin_send transitions"
                                            data-tip="<?php echo iN_HelpSecure($planID); ?>"
                                            data-u="<?php echo iN_HelpSecure($liveCreator); ?>"
                                            aria-label="Send <?php echo iN_HelpSecure($planName); ?>"
                                        >
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="swiper-button-next sw" aria-label="Next slide"></div>
            <div class="swiper-button-prev sw" aria-label="Previous slide"></div>
        </div>
        <!-- SWIPER END -->
    </div>
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>themes/<?php echo iN_HelpSecure($currentTheme);?>/js/giftSwiperInit.js?v=<?php echo iN_HelpSecure($version);?>"></script>
