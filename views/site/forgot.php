<?php
//$this->pageTitle=Yii::$app->name . ' - Forgot Password';
// $this->breadcrumbs=array(
// 	'Forgot Password',
// );
?>
<?php if(Yii::$app->session->hasFlash('forgot')): ?>

<div class="flash-success">
	<?php echo Yii::$app->session->setFlash('error', 'There has nothing to be uploaded'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'forgot-form',
        'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
            Email : <input name="Lupa[email]" id="ContactForm_email" type="email">
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>