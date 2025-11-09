<div class="create_product_form">
    <!--Creation Title-->
    <div class="now_creating">
        <?php echo iN_HelpSecure($LANG[$proof]);?>
    </div>
    <!--/Creation Title-->
    <!---->
    <div class="create_product_form_column flex_">
        <div class="input_title">
            <div class="input_title_title"><?php echo iN_HelpSecure($LANG['pr_name']);?></div>
            <input type="text" id="pr_name" class="prc" placeholder="What are you offering?" value="<?php echo iN_HelpSecure($LANG['content_creation_advice']);?>">
        </div>
        <div class="input_price">
            <div class="input_title_title"><?php echo iN_HelpSecure($LANG['pr_price']);?></div>
            <div class="relativePosition">
                <input type="text" id="pr_price" class="prc input_prc_padding" value="75">
                <span class="prc_currency flex_ tabing"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></span>
            </div>
        </div>
    </div>
    <!---->
    <div class="input_title_title"><?php echo iN_HelpSecure($LANG['pr_images']);?></div>
    <!---->
    <div class="create_product_form_column flex_">
        <div class="input_file_form">
            <div class="relativePosition">
            <form id="uploadprform" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url).'requests/request.php';?>">
                <label class="i_pr_file" for="i_pr_file flex_ tabing">
                    <div class="i_pr_btn flex_ tabing i_pr_height"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('165'));?></div>
                    <input type="file" id="i_pr_file" class="pr_file_" name="uploading[]" data-id="pr_upload" multiple="true">
                </label>
            </form>
            </div>
        </div>
        <div class="input_uploaded_images flex_ tabing">
        <form id="tupprloadform" class="options-form flex_ option_form_pls" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url).'requests/request.php';?>">
            <div class="input_uploaded"></div>
        </form>
        </div>
    </div>
    <!---->
    <div class="input_title_title"><?php echo iN_HelpSecure($LANG['upload_a_d_file']);?></div>
    <!---->
    <div class="create_product_form_column flex_">
        <div class="input_file_form">
            <div class="relativePosition upld">
            <form id="uploadprdform" class="options-form" method="post" action="<?php echo iN_HelpSecure($base_url).'requests/request.php';?>">
                <label class="i_prd_file" for="i_prd_file flex_ tabing">
                    <div class="i_pr_btn flex_ tabing i_pr_height"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('165'));?></div>
                    <input type="file" id="i_prd_file" class="prd_file_" name="uploading[]" data-id="prd_upload">
                </label>
            </form>
            </div>
        </div>
        <div class="input_uploaded_images flex_ tabing">
            <div class="input_uploaded_file">
                <!---->
                <div class="uploadedFileContainer flex_ tabing_non_justify">
                    <a class="fileLink">
                        <div class="flex_ tabing i_pr_height">
                            <div class="theFileIcon flex_ tabing"></div>
                            <div class="theFileName tabing_non_justify"></div>
                        </div>
                    </a>
                </div>
                <!---->
            </div>
        </div>
    </div>
    <!---->
    <!---->
    <div class="create_product_form_column">
        <div class="col-tit flex_ tabing_non_justify"><?php echo iN_HelpSecure($LANG['description']);?><span class="flex_ tabing_non_justify ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['pr_description_info']);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></span></div>
        <div class="col-textarea-box">
            <textarea class="col-textarea" id="pr_description" placeholder="<?php echo iN_HelpSecure($LANG['pr_description_placeholder']);?>"><?php echo iN_HelpSecure($LANG['bookazoomnot']);?></textarea>
        </div>
    </div>
    <!---->
    <!---->
    <div class="create_product_form_column">
        <div class="col-tit flex_ tabing_non_justify"><?php echo iN_HelpSecure($LANG['pr_conf_message']);?><span class="flex_ tabing_non_justify ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['pr_conf_info']);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></span></div>
        <div class="col-textarea-box">
            <textarea class="col-textarea" id="pr_conf"></textarea>
        </div>
    </div>
    <!---->
    <div class="i_warning"><?php echo iN_HelpSecure($LANG['please_fill_in_all_informations']);?></div>
    <div class="i_settings_wrapper_item successNot">
          <?php echo iN_HelpSecure($LANG['product_ready_for_the_published'])?>
      </div>
    <!---->
    <div class="create_product_form_column">
        <input type="hidden" id="uploadPrVal"><input type="hidden" id="uploadPrfVal">
         <div class="pr_save_btna"><?php echo iN_HelpSecure($LANG['create']);?></div>
    </div>
    <!---->
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>themes/<?php echo iN_HelpSecure($currentTheme);?>/js/digitaldownload.js?v=<?php echo iN_HelpSecure($version);?>"></script>