<div
    class="i_product_post_body i_post_body body_<?php echo iN_HelpSecure($ProductID); ?>"
    id="<?php echo iN_HelpSecure($ProductID); ?>"
    data-last="<?php echo iN_HelpSecure($ProductID); ?>"
>
    <div class="i_product_wrp_p">
        <!-- Product Header -->
        <div class="i_product_wrp_header flex_">
            <div class="i_product_o_avatar">
                <img src="<?php echo iN_HelpSecure($pprofileAvatar); ?>" alt="Product Owner Avatar">
            </div>
            <div class="i_post_i_p flex_ tabing">
                <div class="i_post_username_p flex_ tabing">
                    <a href="<?php echo iN_HelpSecure($base_url . $productOwnerUserName); ?>">
                        <?php echo iN_HelpSecure($productOwnerUserFullName); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Image -->
        <a href="<?php echo iN_HelpSecure($SlugUrl); ?>">
            <div class="i_prod_p_i_c flex_ tabing">
                <img class="timp" src="<?php echo iN_HelpSecure($productDataImage); ?>" alt="Product Image">
            </div>
        </a>

        <!-- Product Details -->
        <div class="s_p_details">
            <div class="s_p_title" title="<?php echo iN_HelpSecure($ProductName); ?>">
                <a href="<?php echo iN_HelpSecure($SlugUrl); ?>">
                    <?php echo iN_HelpSecure($ProductName); ?>
                </a>
            </div>
            <div class="s_p_product_type <?php echo iN_HelpSecure($p__style); ?>">
                <?php echo iN_HelpSecure($LANG[$ProductType]); ?>
            </div>
            <div class="s_p_price">
                <?php echo iN_HelpSecure($currencys[$defaultCurrency]) . iN_HelpSecure($ProductPrice); ?>
            </div>
        </div>
    </div>
</div>