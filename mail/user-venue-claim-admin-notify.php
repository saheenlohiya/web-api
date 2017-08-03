<?php
    //notify admins that a claim is made
use yii\helpers\Url;
?>

<h1>A user has claimed a venue</h1>

<div>
    <p>Venue: <strong><a href="http://maps.google.com/?q= <?php echo urlencode($claim->venue->venue_name.','.$claim->venue->venue_address_1.' '.$claim->venue->venue_city.','.$claim->venue->venue_state) ?>"><?php echo $claim->venue->venue_name ?></a></strong></p>
    <p>Name: <strong><?php echo $claim->venue_claim_claimer_name?></strong></p>
    <p>Email: <strong><?php echo $claim->venue_claim_claimer_email?></strong></p>
    <p>Phone: <strong><?php echo $claim->venue_claim_claimer_phone?></strong></p>
    <p>Claim Date: <strong><?php echo date('m/d/Y H:i:s') ?></strong></p>

</div>

<a href="<?php echo Url::base(true);  ?>/v1/users-venues-claims/approve-claim?claim_hash=<?php echo $claim->venue_claim_hash ?>&claim_code=<?php echo $claim->venue_claim_code ?>&approved=1">Approve</a> |
<a href="<?php echo Url::base(true);  ?>/v1/users-venues-claims/approve-claim?claim_hash=<?php echo $claim->venue_claim_hash ?>&claim_code=<?php echo $claim->venue_claim_code ?>&approved=0">Deny</a>