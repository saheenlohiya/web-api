<?php

namespace app\models;

use app\components\Mailer;
use app\models\base\UsersVenuesClaims as BaseUsersVenuesClaims;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "users_venues_claims".
 *
 * @property-read Mailer $mailer
 */
class UsersVenuesClaims extends BaseUsersVenuesClaims {


    public static function create() {
        return new self;
    }

    /**
     * @return Mailer
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer() {
        return \Yii::$container->get(Mailer::className());
    }

    public function behaviors() {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules() {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['venue_id', 'user_id'], 'required']]
        );
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            if ($insert) {
                //stuff to happen before save
                $this->venue_claim_date = date('Y-m-d H:i:s');
                $this->venue_claim_status = self::VENUE_CLAIM_STATUS_PENDING;


                $this->venue_claim_code = rand(1000, 9999999);
                $this->venue_claim_hash = \Yii::$app->security->generateRandomString();

                //we need to make sure the claim hash is unique
                while (self::find()->where(['venue_claim_hash' => $this->venue_claim_hash])->exists()) {
                    $this->venue_claim_hash = \Yii::$app->security->generateRandomString();
                }

                //make sure the claim code is unique
                while (self::find()->where(['venue_claim_code' => $this->venue_claim_code])->exists()) {
                    $this->venue_claim_code = rand(1000000, 9999999);
                }

            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes) {

        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            //notify admins
            $this->_notifyAdmins();
        }
    }

    public function claim($user_id, $venue_id) {
        //make sure another claim doesnt exist
        if (!self::find()->where(['user_id' => $user_id, 'venue_id' => $venue_id])->exists()) {
            $newClaim = self::create();
            $newClaim->venue_id = $venue_id;
            $newClaim->user_id = $user_id;

            if ($newClaim->save()) {
                return $newClaim;
            }
        }

        return false;
    }

    public function approveClaim($approved = false, $claim_hash, $claim_code) {
        if ($approved) {
            return $this->_approveClaim($claim_hash, $claim_code);
        } else {
            return $this->_declineClaim($claim_hash, $claim_code);
        }
    }

    private function _approveClaim($venue_claim_hash, $venue_claim_code) {
        $claim = self::find()->where(['venue_claim_hash' => $venue_claim_hash, 'venue_claim_code' => $venue_claim_code])->one();

        if (!is_null($claim) && $claim->venue_claim_status == self::VENUE_CLAIM_STATUS_PENDING) {
            //first update the claim
            $claim->venue_claim_status = self::VENUE_CLAIM_STATUS_ACTIVE;
            $claim->venue_claim_verified_date = date('Y-m-d H:i:s');
            $claim->venue_claim_update_date = date('Y-m-d H:i:s');
            //update the user id for the venue
            $claim->venue->user_id = $claim->user_id;

            if ($claim->save()) {
                $this->_notifyOfClaimApproval(true, $claim->user, $claim->venue);
                return true;
            }
        }

        return false;
    }

    private function _declineClaim($venue_claim_hash, $venue_claim_code) {
        $claim = self::find()->where(['venue_claim_hash' => $venue_claim_hash, 'venue_claim_code' => $venue_claim_code])->one();

        if (!is_null($claim) && $claim->venue_claim_status == self::VENUE_CLAIM_STATUS_PENDING) {
            //first update the claim
            $claim->venue_claim_status = self::VENUE_CLAIM_STATUS_SUSPENDED;
            $claim->venue_claim_update_date = date('Y-m-d H:i:s');

            if ($claim->save()) {
                return true;
            }
        }

        return false;
    }


    private function _notifyAdmins() {
        //send email
        if (!$this->mailer->notifyAdminOfClaim($this)) {
            Throw new Exception("Could not notify admin");
        }
        //send text message
    }

    private function _notifyOfClaimApproval($approved, $user, $venue) {
        //send email
        if (!$this->mailer->notifyOfClaimApproval($approved, $user, $venue)) {
            Throw new Exception("Could not notify of claim approval");
        }
    }


}
