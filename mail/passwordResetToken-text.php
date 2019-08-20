<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->resettoken]);
?>
Hello <?= $user->username ?>,
Follow the link below to reset your password:
<?= $resetLink ?>