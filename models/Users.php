<?php

namespace app\models;

use app\components\behaviors\GeocodeBehavior;
use app\components\behaviors\IPAddressBehavior;
use app\components\behaviors\UUIDBehavior;
use app\components\Common;
use app\components\Mailer;
use app\models\base\Users as BaseUsers;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property-read Mailer $mailer
 */
class Users extends BaseUsers implements IdentityInterface {

    const BEFORE_CREATE = 'beforeCreate';
    const AFTER_CREATE = 'afterCreate';
    const BEFORE_REGISTER = 'beforeRegister';
    const AFTER_REGISTER = 'afterRegister';
    const BEFORE_CONFIRM = 'beforeConfirm';
    const AFTER_CONFIRM = 'afterConfirm';

    public $username;
    public $password;

    public function extraFields() {
        return ['usersVenuesFollows'];
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($user_access_token, $type = null) {
        return static::findOne(['user_access_token' => $user_access_token]);
    }

    public function fields() {
        $fields = parent::fields();

        // $fields['user_dob'] = function($model){
        //     return date('m/d/Y', strtotime($model->user_dob));
        // };

        // remove fields that contain sensitive information
        unset($fields['user_password'], $fields['user_verification_code'], $fields['uuid']);

        return $fields;
    }

    /**
     * Get user profile information
     * @param $user_id
     * @return Users|array|null
     */
    public static function me($user_id) {
        return static::find()->where(['id' => $user_id])->with(
            self::_getUserProfileRelations()
        )->asArray(true)->one();
    }

    private static function _getUserProfileRelations() {
        return [
            'usersVenuesFollows.venue.venuesImages',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey() {
        return $this->user_auth_key;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($user_auth_key) {
        return $this->user_auth_key === $user_auth_key;
    }


    /**
     * * Finds user by email
     * @param $user_email
     * @return static
     */
    public static function findByEmail($user_email) {
        return static::find()->where(['user_email' => $user_email])->with(
            self::_getUserProfileRelations()
        )->asArray(true)->one();
    }

    /**
     * @return Mailer
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer() {
        return \Yii::$container->get(Mailer::className());
    }


    public static function create() {
        return new self;
    }

    public function behaviors() {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['user_date_joined', 'user_date_modified'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['user_date_modified'],
                    ],
                    // using datetime instead of UNIX timestamp:
                    'value' => new Expression('NOW()'),
                ],

                [
                    'class' => UUIDBehavior::className(),
                    'column' => 'user_verification_code'
                ],

                [
                    'class' => UUIDBehavior::className(),
                    'column' => 'uuid'
                ],

                [
                    'class' => IPAddressBehavior::className(),
                    'column' => 'user_ip_address'
                ],
                [
                    'class' => GeocodeBehavior::className(),

                    'address' => [
                        'postal_code' => $this->user_zip,
                        'country' => 'United States'
                    ],
                    'latitudeAttribute' => 'user_lat',
                    'longitudeAttribute' => 'user_lon'

                ]

            ]
        );
    }

    public function rules() {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
                [['user_firstname', 'user_email', 'user_password', 'user_dob'], 'required'],
                [['user_email'], 'unique'],
                ['user_dob', 'date', 'format' => 'yyyy'],
                ['user_email', 'email'],
            ]
        );
    }

    public function beforeSave($insert) {

        if (parent::beforeSave($insert)) {
            if ($insert) {
                //set an api access token... why not
                $this->user_access_token = \Yii::$app->security->generateRandomString();
                //encrypt the password
                $this->user_password = Yii::$app->getSecurity()->generatePasswordHash($this->user_password);
            }

            //change the date format
            // $this->user_dob = date('Y-m-d', strtotime($this->user_dob));
            $this->user_phone = Common::formatPhoneNumber($this->user_phone);

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
            //send welcome message
            if (!$this->mailer->sendWelcomeMessage($this)) {
                Throw new Exception("Could not send welcome email");
            }
            $last_inserted_data =  self::find()
                ->where(['id' => $this->id])
                ->asArray(true)
                ->all();
            
            ArrayHelper::merge($this, $last_inserted_data[0]);
        }


    }

    /**
     * @inheritDoc
     */
    public function afterFind() {
        parent::afterFind();
        //make sure we return the date in its original ISO/ICU format
        // $this->user_dob = date('m/d/Y', strtotime($this->user_dob));
    }

    public static function findByPasswordResetToken($token)
    {
 
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
 
        return static::findOne([
            'resettoken' => $token
        ]);
    }
 
    public static function isPasswordResetTokenValid($token)
    {
 
        if (empty($token)) {
            return false;
        }
 
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function generatePasswordResetToken()
    {
        $this->resettoken = Yii::$app->security->generateRandomString() . '_' . time();
    }
 
    public function removePasswordResetToken()
    {
        $this->resettoken = null;
    }

    public function setPassword($password)
    {
        $this->user_password = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function getTeamByUser($user_id) {
        return self::find()
            ->where(['team_manager_id' => $user_id])
            ->with(['usersVenuesClaims', 'usersVenuesClaims.venue'])
            ->asArray(true)
            ->all();
    }
    
    
    public function updateMyProfile($user_id, $user_firstname, $user_lastname, $user_address_one, $user_city, $user_state, $user_zip)
    {
        if (!is_null($user_id) && !is_null($user_firstname) && !is_null($user_lastname)) {
            $update_query = "update users set user_firstname='$user_firstname',user_lastname='$user_lastname',user_address_1='$user_address_one',user_city='$user_city',user_state='$user_state',user_zip='$user_zip' where id='$user_id'";
            Yii::$app->db->createCommand($update_query)->execute();
            return true;
        }
        return false;
    }
    
     public function deleteTeamMemberById($team_member_id) {
        if (!is_null($team_member_id)) {
            $deleteRespond   = self::deleteAll(['id'=>$team_member_id]);
            if($deleteRespond){
                Yii::$app->db->createCommand()->update('users', ['team_manager_id' => NULL], 'team_manager_id="'.$team_member_id.'"')->execute();
                Yii::$app->db->createCommand()->update('users_venues_claims', ['user_id' => NULL], 'user_id="'.$team_member_id.'"')->execute();
                Yii::$app->db->createCommand()->update('users_venues_coupons', ['user_id' => NULL], 'user_id="'.$team_member_id.'"')->execute();
                Yii::$app->db->createCommand()->update('users_venues_follows', ['user_id' => NULL], 'user_id="'.$team_member_id.'"')->execute();
                Yii::$app->db->createCommand()->update('users_venues_ratings', ['user_id' => NULL], 'user_id="'.$team_member_id.'"')->execute();
                Yii::$app->db->createCommand()->update('users_venues_ratings_responses', ['user_venue_rating_responding_user_id' => NULL], 'user_venue_rating_responding_user_id="'.$team_member_id.'"')->execute();
                Yii::$app->db->createCommand()->update('venues', ['user_id' => NULL], 'user_id="'.$team_member_id.'"')->execute();
               return $deleteRespond;
            }
        }
        return false;
    }

    public function updatePasswordByResetToken($params) {
        if (!is_null($params['resettoken']) && !is_null($params['user_password'])) {
            $new_password       = Yii::$app->security->generatePasswordHash($params['user_password']);
            $newresultResponse  = Yii::$app->db->createCommand()->update('users', ['user_password' => $new_password, 'resettoken' => NULL], 'resettoken="'.$params['resettoken'].'"')->execute();
            if ($newresultResponse == 1) {
                return true;
            }
        }
        return false;
    }
   

}
