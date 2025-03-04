<?php

/* @var $this yii\web\View */

$this->title = 'The TellUs App';
?>
<div class="site-index">
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
         <h4><i class="icon fa fa-check"></i>Success!</h4>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
         <h4><i class="icon fa fa-check"></i>Error!</h4>
         <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>
</div>
