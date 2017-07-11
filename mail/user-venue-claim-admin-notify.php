<?php
    //notify admins that a claim is made
use yii\helpers\Url;
?>

<h1>A user has claimed a venue</h1>

<a href="<?php echo Url::base(true);  ?>/v1/users-venues-claims/approve-claim?claim_hash=<?php echo $claim->venue_claim_hash ?>&claim_code=<?php echo $claim->venue_claim_code ?>&approved=1">Approve</a> |
<a href="<?php echo Url::base(true);  ?>/v1/users-venues-claims/approve-claim?claim_hash=<?php echo $claim->venue_claim_hash ?>&claim_code=<?php echo $claim->venue_claim_code ?>&approved=0">Deny</a>