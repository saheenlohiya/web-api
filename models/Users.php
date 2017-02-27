<?php

namespace app\models;

use app\components\behaviors\IPAddressBehavior;
use app\components\behaviors\UUIDBehavior;
use app\components\Mailer;
use \app\models\base\Users as BaseUsers;

use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

//use app\components\behaviors\GeocodeBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 *  @property-read Mailer $mailer
 */
class Users extends BaseUsers implements IdentityInterface
{

    const BEFORE_CREATE = 'beforeCreate';
    const AFTER_CREATE = 'afterCreate';
    const BEFORE_REGISTER = 'beforeRegister';
    const AFTER_REGISTER = 'afterRegister';
    const BEFORE_CONFIRM = 'beforeConfirm';
    const AFTER_CONFIRM = 'afterConfirm';

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($user_access_token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->user_auth_key;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($user_auth_key)
    {
        return $this->user_auth_key === $user_auth_key;
    }


    /**
     * Finds user by email
     * @param $email
     * @throws NotSupportedException
     */
    public static function findByEmail($email)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');

    }

    /**
     * @return Mailer
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }


    public static function create()
    {
        return new self;
    }

    public function behaviors()
    {
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
                ]

            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
                [['user_firstname', 'user_lastname', 'user_email', 'user_phone', 'user_password'], 'required'],
                ['user_email', 'email'],
            ]
        );
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->user_password = Yii::$app->getSecurity()->generatePasswordHash($this->user_password);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        //send welcome message
        if(!$this->mailer->sendWelcomeMessage($this)){
            Throw new Exception("Could not send welcome email");
        }

    }


}
