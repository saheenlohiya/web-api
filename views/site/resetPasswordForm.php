<?php
 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 
$this->title = 'Reset password';
?>
 
<div class="site-reset-password">
    <div style="margin:auto;width:fit-content;margin-bottom:30px;">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Please choose your new password:</p>
    </div>
    <div class="row">
        <div class="col-lg-5 center-align">
 
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password_repeat')->passwordInput(['autofocus' => true]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg float-right']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>