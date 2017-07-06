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
                [['venue_id', 'user_id'], 'required']
            ]
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
        if (parent::afterSave($insert, $changedAttributes)) {
            if ($insert) {
                //notify admins
                $this->_notifyAdmins();
            }
        }
    }

    private function _notifyAdmins() {
        //send email
        //send welcome message
        if (!$this->mailer->notifyAdminOfClaim($this)) {
            Throw new Exception("Could not notify admin");
        }
        //send text message
    }


}
