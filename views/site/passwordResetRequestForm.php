<?php
 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 
$this->title = 'Request password reset';
?>
 
<div class="site-request-password-reset">
    <div style="margin:auto;">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Please fill out your email. A link to reset password will be sent there.</p>
    </div>
    
    <div class="row">
        <div class="col-lg-5 center-align">
 
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'user_email')->textInput(['autofocus' => true]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-lg float-right']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>
