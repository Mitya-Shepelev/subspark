<?php if($validationStatus == '0'){?>
<div class="certification_terms">
    <div class="certification_terms_item verirication_timing_bg"></div>
    <div class="certification_terms_item">
        <div class="certificate_terms_item_item pendingTitle">
           <?php echo iN_HelpSecure($LANG['your_request_is_pending']);?>
        </div>
        <div class="certificate_terms_item_item">
          <?php echo iN_HelpSecure($LANG['you_will_notififed_when_it_is_processed']);?>
        </div>
    </div>
</div>
<?php }else if($validationStatus == '2'){$iN->iN_UpdateVerificationAnswerReadStatus($userID);?>
<div class="i_postFormContainer"><div class="certification_terms">
<div class="certification_terms_item verification_reject_bg"></div>
<div class="certification_terms_item">
    <div class="certificate_terms_item_item pendingTitle">
      <?php echo iN_HelpSecure($LANG['sorry_rejected']);?>
    </div>
    <div class="certificate_terms_item_item">
      <?php echo iN_HelpSecure($LANG['sorry_you_are_rejected']);?>
    </div>
</div>
</div></div>
<?php } else if($validationStatus == '1'){ $iN->iN_UpdateVerificationAnswerReadStatus($userID);?>
<div class="i_become_creator_terms_box">
<div class="certification_form_container">
   <div class="certification_form_title"><?php echo iN_HelpSecure($LANG['conditions']);?></div>
   <div class="certification_form_not"><?php echo iN_HelpSecure($LANG['readed_conditions']);?></div>
   <div class="certification_form_wrapper">
      <div class="condition_documentation"><?php echo iN_HelpSecure($creatorConditions['conditions_document']);?></div>
   </div>
</div>
</div>
<div class="i_become_creator_box_footer">
   <div class="i_nex_btn c_Next transition"><?php echo iN_HelpSecure($LANG['next']);?></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("body").on("click",".c_Next", function(){
        var type = 'acceptConditions';
        var data = 'f='+type;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                /*Do Something*/
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                }
            }
        });
    });
});
</script>
<?php } ?>