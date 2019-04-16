<?php
    //notify user of claim approvals
?>

<h1>Dear Support Manager</h1>

<p> Review Recieved against a global venue. Kinldy look into this.</p>
<div>
    <p>Name: <strong><?php echo $user->user_firstname?></strong></p>
    <p>Email: <strong><?php echo $user->user_email?></strong></p>
    <p> Attributes: <strong><?php echo json_encode($attributes) ?></strong></p>
</div>
<br><br>
<p>
    Reagrds,<br>
    The Tell Us Team
</p>
