<div class="i_become_creator_wrapper">
    <div class="i_become_creator_title"><?php echo iN_HelpSecure($LANG['become_creator']);?></div>
    <div class="i_become_title_mini"><?php echo iN_HelpSecure($LANG['registeration_free_fast']);?></div>
    <div class="i_become_ceator_link">
        <?php if($logedIn == '1'){ ?>
            <a href="<?php echo iN_HelpSecure($base_url).'creator/becomeCreator';?>"><?php echo iN_HelpSecure($LANG['become_creator']);?></a>
        <?php }else{ ?>
            <a class="loginForm"><?php echo iN_HelpSecure($LANG['become_creator']);?></a>
        <?php }?>
    </div>
    <div class="i_become_creator_icon">
       <div class="i_bicome"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9'));?></div>
    </div>
</div>