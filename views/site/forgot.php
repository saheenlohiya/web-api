<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<?php
$this->title=Yii::$app->name . ' - Forgot Password';
// $this->breadcrumbs=array(
// 	'Forgot Password',
// );
?>



<div class="form">
<div class="row">
            Email : <input name="Lupa[email]" id="ContactForm_email" type="email">
	</div>
<?php 
    $form = ActiveForm::begin([
        'id' => 'forgot-form',
        'enableClientValidation'=>true,
        'validateOnSubmit' => true, // this is redundant because it true by default
    ]);
    
    // ...
    
    
    ?>

	<div class="row">
            Email : <input name="Lupa[email]" id="ContactForm_email" type="email">
	</div>

	<div class="row buttons">
		<?php echo Html::submitButton('Submit'); ?>
	</div>

<?php ActiveForm::end(); ?>

</div><!-- form -->