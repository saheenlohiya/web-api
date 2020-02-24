<?php

namespace app\components;

use app\helpers\Password;
use app\models\Users;
use app\models\UsersVenuesClaims;
use app\models\UsersVenuesRatings;
use app\models\Venues;
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

    /** @var string */
    protected $ratingNotifySubject;

    /**  @var string */
    protected $claimNotifyAdminSubject;

    /**  @var string */
    protected $claimNotifySubject;

    protected $claimStartedNotifyUserSubject;

    protected $newRatingNotificationSupportSubject;

    /**
     * @return mixed
     */
    public function getClaimStartedNotifyUserSubject()
    {
        if ($this->claimStartedNotifyUserSubject == null) {
            $this->setclaimStartedNotifyUserSubject("Your Tell Us business claim has been received");
        }

        return $this->claimStartedNotifyUserSubject;
    }

    /**
     * @param mixed $claimStartedNotifyUserSubject
     */
    public function setclaimStartedNotifyUserSubject($claimStartedNotifyUserSubject)
    {
        $this->claimStartedNotifyUserSubject = $claimStartedNotifyUserSubject;
    }

    /**
     * @return string
     */
    public function getWelcomeSubject()
    {
        if ($this->welcomeSubject == null) {
            $this->setWelcomeSubject('Welcome to Tell Us');
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

    public function getRatingNotificationSupportSubject()
    {
        if ($this->newRatingNotificationSupportSubject == null) {
            $this->setNewRatingNotificationSupportSubject('Global Venue Rating');
        }

        return $this->newRatingNotificationSupportSubject;
    }

    public function setNewRatingNotificationSupportSubject($newRatingNotificationSupportSubject)
    {
        $this->newRatingNotificationSupportSubject = $newRatingNotificationSupportSubject;
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

    /**
     * @return mixed
     */
    public function getRatingNotifySubject()
    {
        if ($this->ratingNotifySubject == null) {
            $this->setRatingNotifySubject('You have a new rating');
        }

        return $this->ratingNotifySubject;
    }

    /**
     * @param $ratingNotifySubject
     */
    public function setRatingNotifySubject($ratingNotifySubject)
    {
        $this->ratingNotifySubject = $ratingNotifySubject;
    }

    /**
     * @return mixed
     */
    public function getClaimNotifyAdminSubject()
    {
        if ($this->claimNotifyAdminSubject == null) {
            $this->setClaimNotifyAdminSubject('A User Has Claimed a Venue');
        }

        return $this->claimNotifyAdminSubject;
    }

    /**
     * @param $claimNotifyAdminSubject
     */
    public function setClaimNotifyAdminSubject($claimNotifyAdminSubject)
    {
        $this->claimNotifyAdminSubject = $claimNotifyAdminSubject;
    }


    /**
     * @return mixed
     */
    public function getClaimNotifySubject($approved)
    {
        if ($this->claimNotifySubject == null) {
            if($approved){
                $this->setClaimNotifySubject('Your Tell Us Claim Has Been Approved');
            }
            else{
                $this->setClaimNotifySubject('Your Attention is Needed Regarding Your Tell Us Claim');
            }

        }

        return $this->claimNotifySubject;
    }

    /**
     * @param $claimNotifySubject
     */
    public function setClaimNotifySubject($claimNotifySubject)
    {
        $this->claimNotifySubject = $claimNotifySubject;
    }


    /** @inheritdoc */
    public function init()
    {
        parent::init();
    }

    /**
     * Sends an email to a user after registration.
     *
     * @param Users $user
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
     * @param UsersVenuesClaims $claim
     * @return bool
     */
    public function notifyAdminOfClaim(UsersVenuesClaims $claim)
    {
        return $this->sendMessage(
            Yii::$app->params['adminEmail'],
            $this->getClaimNotifyAdminSubject(),
            'user-venue-claim-admin-notify',
            ['claim' => $claim]
        );
    }

    public function confirmUserClaimStarted(UsersVenuesClaims $claim)
    {
        return $this->sendMessage(
            $claim->venue_claim_claimer_email,
            $this->getClaimStartedNotifyUserSubject(),
            'user-venue-claim-started-confirmation',
            ['claim' => $claim]
        );
    }

    /**
     * @param $approved
     * @param $user
     * @param $venue
     * @return bool
     */
    public function notifyOfClaimApproval($approved, $user, $venue)
    {
        return $this->sendMessage(
            $user->user_email,
            $this->getClaimNotifySubject($approved),
            'user-venue-claim-notify',
            ['approved' => $approved, 'user' => $user, 'venue' => $venue]
        );
    }

    /**
     * Sends an email to the venue manager when a new rating is submitted
     * @param UsersVenuesRatings $rating
     * @param Venues $venue
     * @param Users $user
     * @return bool
     */

    public function sendRatingNotification(UsersVenuesRatings $rating, Venues $venue, Users $user)
    {
        return $this->sendMessage(
            $user->user_email,
            $this->getRatingNotifySubject(),
            'rating-notify',
            ['user' => $user, 'rating' => $rating, 'venue' => $venue]
        );
    }

        /**
     * Modify Function
     * Sends an email to the venue manager when a new rating is submitted
     * @param UsersVenuesRatings $rating
     * @param Venues $venue
     * @param email $email 
     * @return bool
     */

    public function sendRatingNotification_new(UsersVenuesRatings $rating, Venues $venue, $email)
    {
        return $this->sendMessage(
            $email,$this->getRatingNotifySubject(),
            'rating-notify',
            ['rating' => $rating, 'venue' => $venue]
        );
    }

    /**
     * Sends a new generated password to a user.
     *
     * @param Users $user
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
     * @param Users $user
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
     * @param Users $user
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
     * @param Users $user
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
     * @param array $params
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
            $this->sender = isset(Yii::$app->params['customerServiceEmail']) ?
                Yii::$app->params['customerServiceEmail']
                : 'no-reply@thetellusapp.com';
        }

        return $mailer->compose(['html' => $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();

        
        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
        

    }

    /**
     * Sends an email to a user with confirmation link.
     *
     * @param Users $user
     *
     * @return bool
     */
    public function sendRatingNotificationSupport($user, $attributes)
    {
        return $this->sendMessage(
            Yii::$app->params['adminEmail'],
            //"john@tellusintel.com",
            $this->getRatingNotificationSupportSubject(),
            'rating-notify-support',
            ['user' => $user, 'attributes'=>$attributes]
        );
    }
}
