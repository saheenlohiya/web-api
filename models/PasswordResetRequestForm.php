<?php
 
namespace app\models;
 
use Yii;
use yii\base\Model;
 
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $user_email;
    public $reset_password_url;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_email', 'trim'],
            ['user_email', 'required'],
            ['user_email', 'email'],
            ['user_email', 'exist',
                'targetClass' => '\app\models\Users',
                'filter' => [],
                'message' => 'There is no user with such email.'
            ],
        ];
    }
 
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = Users::findOne([
            'user_email' => $this->user_email,
        ]);

        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->resettoken]);
        if(isset($this->reset_password_url) && !empty($this->reset_password_url)){
            $resetLink          = "http://localhost:6075/reset-password?token=".$user->resettoken;    
        }
        if (!$user) {
            return false;
        }
 
        if (!Users::isPasswordResetTokenValid($user->resettoken)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user, 'resetLink' => $resetLink]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'The TellUs App'])
            ->setTo($this->user_email)
            ->setSubject('Password reset for The TellUs App')
            ->send();
    }
 
}