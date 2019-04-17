<?php
    //notify user of claim approvals
?>

<h1>Dear Support Manager</h1>

<p> Review received against a global venue. This is just a placeholder response.</p>
<div>
    <p>Name: <strong><?php echo $user->user_firstname?></strong></p>
    <p>Email: <strong><?php echo $user->user_email?></strong></p>
    <p> Service Rating: <strong><?php if(array_key_exists("venue_rating_cat_1",$attributes)){echo $attributes['venue_rating_cat_1'];} else { echo "Not Provided";} ?></strong></p>
    <p> Staff Rating: <strong><?php if(array_key_exists("venue_rating_cat_2",$attributes)){echo $attributes['venue_rating_cat_2'];} else { echo "Not Provided";} ?></strong></p>
    <p> Facility Rating: <strong><?php if(array_key_exists("venue_rating_cat_3",$attributes)){echo $attributes['venue_rating_cat_3'];} else { echo "Not Provided";} ?></strong></p>
    <p> Rating Comment: <strong><?php if(array_key_exists("venue_rating_comment",$attributes)){echo $attributes['venue_rating_comment'];} else { echo "Not Provided";} ?></strong></p>

</div>
<br><br>
<p>
    Reagrds,<br>
    The Tell Us Team
</p>
