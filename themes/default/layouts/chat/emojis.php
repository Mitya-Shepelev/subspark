<div class="Message_stickersContainer">
    <div class="emojis_Container">
        <div class="smileys">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['smileys']);?></div>
            <?php
            foreach ($smileys as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="gestures_and_bodyparts">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['gestures_and_bodyparts']);?></div>
            <?php
            foreach ($gesturesAndBodyParts as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="people_and_fantasy">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['people_and_fantasy']);?></div>
            <?php
            foreach ($peopleAndFantasy as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="clothing_and_accessories">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['clothing_and_accessories']);?></div>
            <?php
            foreach ($clothingAndAccessories as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="pale_emojis">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['pale_emojis']);?></div>
            <?php
            foreach ($paleEmojis as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="anmal_nature">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['anmal_nature']);?></div>
            <?php
            foreach ($animalAndNature as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="food_and_drink">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['food_and_drink']);?></div>
            <?php
            foreach ($foodnAndDrink as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="activity_and_sports">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['activity_and_sports']);?></div>
            <?php
            foreach ($activityAndSpotrs as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="travel_and_places">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['travel_and_places']);?></div>
            <?php
            foreach ($travelAndPlaces as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="objects">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['objects']);?></div>
            <?php
            foreach ($objects as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="symbols">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['symbols']);?></div>
            <?php
            foreach ($symbols as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="non_emoji_symbols">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['non_emoji_symbols']);?></div>
            <?php
            foreach ($nonEmojiSymbols as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
        <div class="flags">
          <div class="emTitle"><?php echo iN_HelpSecure($LANG['flags']);?></div>
            <?php
            foreach ($flags as $fitem) {
                echo '<div class="'.$importClass.' transition" data-emoji="'.$fitem.'" '.$importID.'>'.$fitem.'</div>';
            }
            ?>
        </div>
    </div> 
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>themes/<?php echo iN_HelpSecure($currentTheme);?>/js/slimscroll.js?v=<?php echo iN_HelpSecure($version);?>"></script>
</div>