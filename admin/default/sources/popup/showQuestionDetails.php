<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in">
       <div class="i_modal_content">
            <!--Modal Header-->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['question_det']);?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
            </div>
            <!--/Modal Header-->
            <div class="i_delete_post_description column positionRelative">
                <!---->
                <div class="purchase_post_details">
                    <div class="wallet-debit-confirm-container flex_ column">
                       <div class="contact_u_detail flex_ tabing margin_bottom_custom_css_js">
                          <div class="contact_u_d flex_"><?php echo iN_HelpSecure($LANG['the_person_asking']);?></div>
                          <div class="contact_u_d flex_ fw-300"><?php echo iN_HelpSecure($qDet['contact_full_name']);?></div>
                       </div>
                       <div class="contact_u_detail flex_ tabing margin_bottom_custom_css_js">
                          <div class="contact_u_d flex_"><?php echo iN_HelpSecure($LANG['u_asking_email']);?></div>
                          <div class="contact_u_d flex_ fw-300"><?php echo iN_HelpSecure($qDet['contact_email']);?></div>
                       </div>
                    </div>
                    <div class="withdraw_other_details border_one flex_ column fw-400">
                           <?php echo iN_HelpSecure($qDet['contact_message']);?>
                    </div>
                </div>
                <!---->
            </div>
            <!--Modal Header-->
            <div class="i_modal_g_footer flex_">
                <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['close']) ;?></div>
                <div class="answerMail transition"><a href="mailto:<?php echo $qDet['contact_email'];?>"><?php echo iN_HelpSecure($LANG['answer_question']) ;?></a></div>
            </div>
            <!--/Modal Header-->
       </div>
   </div>
   <!--/SHARE-->
</div>