<?php

namespace app\components;

use app\helpers\Password;
use app\models\Users;
use Yii;
use yii\base\Component;

/**
 * Mailer.
 *
 */
class Mailer extends Component
{
    /** @var string */
    public $viewPath = '@app/mail';

    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /** @var string */
    protected $welcomeSubject;

    /** @var string */
    protected $newPasswordSubject;

    /** @var string */
    protected $confirmationSubject;

    /** @var string */
    protected $reconfirmationSubject;

    /** @var string */
    protected $recoverySubject;

    /**
     * @return string
     */
    public function getWelcomeSubject()
    {
        if ($this->welcomeSubject == null) {
            $this->setWelcomeSubject('Welcome to TellUs');
        }

        return $this->welcomeSubject;
    }

    /**
     * @param string $welcomeSubject
     */
    public function setWelcomeSubject($welcomeSubject)
    {
        $this->welcomeSubject = $welcomeSubject;
    }

    /**
     * @return string
     */
    public function getNewPasswordSubject()
    {
        if ($this->newPasswordSubject == null) {
            $this->setNewPasswordSubject('Your password has been changed');
        }

        return $this->newPasswordSubject;
    }

    /**
     * @param string $newPasswordSubject
     */
    public function setNewPasswordSubject($newPasswordSubject)
    {
        $this->newPasswordSubject = $newPasswordSubject;
    }

    /**
     * @return string
     */
    public function getConfirmationSubject()
    {
        if ($this->confirmationSubject == null) {
            $this->setConfirmationSubject('Confirm account');
        }

        return $this->confirmationSubject;
    }

    /**
     * @param string $confirmationSubject
     */
    public function setConfirmationSubject($confirmationSubject)
    {
        $this->confirmationSubject = $confirmationSubject;
    }

    /**
     * @return string
     */
    public function getReconfirmationSubject()
    {
        if ($this->reconfirmationSubject == null) {
            $this->setReconfirmationSubject('Confirm email change');
        }

        return $this->reconfirmationSubject;
    }

    /**
     * @param string $reconfirmationSubject
     */
    public function setReconfirmationSubject($reconfirmationSubject)
    {
        $this->reconfirmationSubject = $reconfirmationSubject;
    }

    /**
     * @return string
     */
    public function getRecoverySubject()
    {
        if ($this->recoverySubject == null) {
            $this->setRecoverySubject('Complete password reset');
        }

        return $this->recoverySubject;
    }

    /**
     * @param string $recoverySubject
     */
    public function setRecoverySubject($recoverySubject)
    {
        $this->recoverySubject = $recoverySubject;
    }

    /** @inheritdoc */
    public function init()
    {
        parent::init();
    }

    /**
     * Sends an email to a user after registration.
     *
     * @param Users  $user
     *
     * @return bool
     */
    public function sendWelcomeMessage(Users $user)
    {
        return $this->sendMessage(
            $user->user_email,
            $this->getWelcomeSubject(),
            'user-account-welcome',
            ['user' => $user]
        );
    }

    /**
     * Sends a new generated password to a user.
     *
     * @param Users  $user
     * @param Password $password
     *
     * @return bool
     */
    public function sendGeneratedPassword(Users $user, $password)
    {
        return $this->sendMessage(
            $user->user_email,
            $this->getNewPasswordSubject(),
            'new_password',
            ['user' => $user, 'password' => $password]
        );
    }

    /**
     * Sends an email to a user with confirmation link.
     *
     * @param Users  $user
     *
     * @return bool
     */
    public function sendConfirmationMessage(Users $user)
    {
        return $this->sendMessage(
            $user->user_email,
            $this->getConfirmationSubject(),
            'confirmation',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with reconfirmation link.
     *
     * @param Users  $user
     *
     * @return bool
     */
    public function sendReconfirmationMessage(Users $user)
    {
        $email = $user->user_email;

        return $this->sendMessage(
            $email,
            $this->getReconfirmationSubject(),
            'reconfirmation',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with recovery link.
     *
     * @param Users  $user
     *
     * @return bool
     */
    public function sendRecoveryMessage(Users $user)
    {
        return $this->sendMessage(
            $user->user_email,
            $this->getRecoverySubject(),
            'recovery',
            ['user' => $user]
        );
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = [])
    {

        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ?
                Yii::$app->params['adminEmail']
                : 'no-reply@example.com';
        }

        return $mailer->compose(['html' => $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();

        /*
        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
        */

    }
}
