<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
    include("layouts/header/meta.php");
    include("layouts/header/css.php");
    include("layouts/header/javascripts.php");
    ?>
</head>
<body>
<?php
if ($logedIn == 0) {
    include 'layouts/login_form.php';
}

$completedLvlItem = $completedLvlItemBold = '';
$completedLvlItemTwo = $completedLvlItemBoldTwo = '';
$completedLvlItemTree = $completedLvlItemBoldTree = '';
$completedLvlItemFour = $completedLvlItemBoldFour = '';
$completedLvlItemFive = $completedLvlItemBoldFive = '';
$completedLvlWidth = '0%';

if ($certificationStatus === '0') {
    $completedLvlWidth = '0%';
    $completedLvlItem = 'i_completed_level_item';
    $completedLvlItemBold = 'i_completed_levet_item_bold';
} elseif ($certificationStatus === '1') {
    $completedLvlWidth = '24%';
    $completedLvlItem = $completedLvlItemTwo = 'i_completed_level_item';
    $completedLvlItemBold = $completedLvlItemBoldTwo = 'i_completed_levet_item_bold';
} elseif ($certificationStatus === '2' && $validationStatus === '1') {
    $completedLvlWidth = '44%';
    $completedLvlItem = $completedLvlItemTwo = $completedLvlItemTree = 'i_completed_level_item';
    $completedLvlItemBold = $completedLvlItemBoldTwo = $completedLvlItemBoldTree = 'i_completed_levet_item_bold';
} elseif ($certificationStatus === '2' && $validationStatus === '2' && $feesStatus === '2' && $payoutStatus === '0') {
    $completedLvlWidth = '64%';
    $completedLvlItem = $completedLvlItemTwo = $completedLvlItemTree = $completedLvlItemFour = 'i_completed_level_item';
    $completedLvlItemBold = $completedLvlItemBoldTwo = $completedLvlItemBoldTree = $completedLvlItemBoldFour = 'i_completed_levet_item_bold';
} elseif ($certificationStatus === '2' && $validationStatus === '2' && $feesStatus === '2' && $payoutStatus === '2') {
    $completedLvlWidth = '80%';
    $completedLvlItem = $completedLvlItemTwo = $completedLvlItemTree = $completedLvlItemFour = $completedLvlItemFive = 'i_completed_level_item';
    $completedLvlItemBold = $completedLvlItemBoldTwo = $completedLvlItemBoldTree = $completedLvlItemBoldFour = $completedLvlItemBoldFive = 'i_completed_levet_item_bold';
}
?>
<?php include("layouts/header/header.php"); ?>
<div class="wrapper bCreatorBg bCreator_padding">
    <div class="i_become_creator_container">
        <div class="i_become_creator_levels">
            <div class="i_become_creator_levels_title"><?php echo iN_HelpSecure($LANG['become_creator']); ?></div>
            <div class="i_levels_container">
                <div class="i_levels_container_position"></div>
                <div class="i_levels_container_position_lvl dynamic-bar" data-width="<?php echo iN_HelpSecure($completedLvlWidth); ?>"></div>

                <div class="i_complete_level">
                    <div class="i_complete_level_box <?php echo iN_HelpSecure($completedLvlItem); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14')); ?>
                    </div>
                    <div class="i_complete_level_name <?php echo iN_HelpSecure($completedLvlItemBold); ?>">
                        <?php echo iN_HelpSecure($LANG['certification']); ?>
                    </div>
                </div>

                <div class="i_complete_level">
                    <div class="i_complete_level_box <?php echo iN_HelpSecure($completedLvlItemTwo); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('47')); ?>
                    </div>
                    <div class="i_complete_level_name <?php echo iN_HelpSecure($completedLvlItemBoldTwo); ?>">
                        <?php echo iN_HelpSecure($LANG['validation']); ?>
                    </div>
                </div>

                <div class="i_complete_level">
                    <div class="i_complete_level_box <?php echo iN_HelpSecure($completedLvlItemTree); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('75')); ?>
                    </div>
                    <div class="i_complete_level_name <?php echo iN_HelpSecure($completedLvlItemBoldTree); ?>">
                        <?php echo iN_HelpSecure($LANG['conditions']); ?>
                    </div>
                </div>

                <div class="i_complete_level">
                    <div class="i_complete_level_box <?php echo iN_HelpSecure($completedLvlItemFour); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('76')); ?>
                    </div>
                    <div class="i_complete_level_name <?php echo iN_HelpSecure($completedLvlItemBoldFour); ?>">
                        <?php echo iN_HelpSecure($LANG['fees']); ?>
                    </div>
                </div>

                <div class="i_complete_level">
                    <div class="i_complete_level_box <?php echo iN_HelpSecure($completedLvlItemFive); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('77')); ?>
                    </div>
                    <div class="i_complete_level_name <?php echo iN_HelpSecure($completedLvlItemBoldFive); ?>">
                        <?php echo iN_HelpSecure($LANG['payouts']); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($certificationStatus == '0') {
            include("layouts/widgets/certification.php");
        } elseif ($certificationStatus == '1') {
            include("layouts/widgets/conditions.php");
        } elseif ($certificationStatus == '2' && $validationStatus == '1') {
            if ($subscriptionType == '2') {
                include "layouts/widgets/feesPoint.php";
            } elseif ($subscriptionType == '1') {
                include "layouts/widgets/fees.php";
            }
        } elseif ($certificationStatus == '2' && $validationStatus == '2' && $feesStatus == '2' && $payoutStatus == '0') {
            include("layouts/widgets/payout.php");
        } elseif ($certificationStatus == '2' && $validationStatus == '2' && $feesStatus == '2' && $payoutStatus == '2') {
            $iN->iN_UpdateVerificationAnswerReadStatus($userID);
            echo '
            <div class="i_become_creator_terms_box">
                <div class="certification_form_container">
                    <div class="creator_conguratulation_title">' . $LANG['conguratulation'] . '</div>
                    <div class="creator_conguratulation">' . $iN->iN_SelectedMenuIcon('82') . '</div>
                    <div class="creator_conguratulation_note">' . $LANG['conguratulation_note'] . '</div>
                </div>
            </div>
            ';
        }
        ?>
    </div>
</div> 
  <script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>themes/<?php echo iN_HelpSecure($currentTheme);?>/js/becomeCreator.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
</body>
</html>