<div class="creators_menu_wrapper">
    <div class="tabing">
        <?php
        $categories = $iN->iN_GetCategories();
        if ($categories) {
            foreach ($categories as $caData) {
                $categoryID = $caData['c_id'] ?? NULL;
                $categoryKey = $caData['c_key'] ?? NULL;
                $isActive = (iN_HelpSecure($pageCreator) === iN_HelpSecure($categoryKey)) ? 'active_pc' : '';
                $subCategories = $iN->iN_CheckAndGetSubCat($categoryID);
                $categoryUrl = iN_HelpSecure($base_url) . 'creators?creator=' . iN_HelpSecure($categoryKey);
                $categoryName = iN_HelpSecure($PROFILE_CATEGORIES[$categoryKey] ?? ucfirst($categoryKey));
                ?>
                
                <div class="creator_item transition <?php echo iN_HelpSecure($isActive); ?>">
                    <a href="<?php echo iN_HelpSecure($categoryUrl); ?>"><?php echo iN_HelpSecure($categoryName); ?></a>

                    <?php if ($subCategories): ?>
                        <div class="subcategoryname">
                            <?php foreach ($subCategories as $subData): 
                                $subKey = $subData['sc_key'] ?? NULL;
                                $subUrl = iN_HelpSecure($base_url) . 'creators?creator=' . iN_HelpSecure($subKey);
                                $subName = iN_HelpSecure($PROFILE_SUBCATEGORIES[$subKey] ?? ucfirst($subKey));
                                ?>
                                <div class="sub_m_item">
                                    <a href="<?php echo iN_HelpSecure($subUrl); ?>"><?php echo iN_HelpSecure($subName); ?></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>