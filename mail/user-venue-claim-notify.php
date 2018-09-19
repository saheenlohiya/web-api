<?php
    //notify user of claim approvals
?>

<h1>Dear User</h1>

<?php if(!$approved):?>
<p>We have reviewed your request to claim (TBD) and we will need additional information from you to finalize the claim of this business. Tell Us attempts to thoroughly review every request, to ensure there is never a fraudulant claiming of a business. Please contact us at 1.877.U.TELL.US (877-883-5587) or at support@thetellusapp.com</p>
<?php else: ?>
<p>Congratulations. You are now the proud owner of a claimed TellUs business. Please log into your account and review your list of businesses. We look forward to helping you create an incredible customer service environement.</p>
<?php endif; ?>
<br><br>
<p>
    Reagrds,<br>
    The Tell Us Team
</p>
