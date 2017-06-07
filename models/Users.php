<?php

namespace app\models;

use app\components\behaviors\IPAddressBehavior;
use app\components\behaviors\UUIDBehavior;
use app\components\Mailer;
use \app\models\base\Users as BaseUsers;
use app\components\behaviors\GeocodeBehavior;

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
class Users extends BaseUsers implements IdentityInterface
{

    const BEFORE_CREATE = 'beforeCreate';
    const AFTER_CREATE = 'afterCreate';
    const BEFORE_REGISTER = 'beforeRegister';
    const AFTER_REGISTER = 'afterRegister';
    const BEFORE_CONFIRM = 'beforeConfirm';
    const AFTER_CONFIRM = 'afterConfirm';

    public $username;
    public $password;

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
        return static::findOne(['user_access_token' => $user_access_token]);
    }

    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['user_password'], $fields['user_verification_code'],$fields['uuid']);

        return $fields;
    }

    /**
     * Get user profile information
     * @param $user_id
     * @return Users|array|null
     */
    public static function me($user_id)
    {
        return static::find()->where(['id' => $user_id])->with(
            self::_getUserProfileRelations()
        )->asArray(true)->one();
    }

    private static function _getUserProfileRelations()
    {
        return [
            'usersVenuesFollows.venue',
//            'usersVenuesRatings.venue',
//            'usersVenuesRatings.usersVenuesRatingsResponses'
        ];
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
     * * Finds user by email
     * @param $user_email
     * @return static
     */
    public static function findByEmail($user_email)
    {
        return static::find()->where(['user_email' => $user_email])->with(
            self::_getUserProfileRelations()
        )->asArray(true)->one();
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

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
                [['user_firstname', 'user_lastname', 'user_email', 'user_password', 'user_zip', 'user_dob'], 'required'],
                ['user_dob', 'date', 'format' => 'M/d/yyyy'],
                ['user_email', 'email'],
            ]
        );
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            //encrypt the password
            $this->user_password = Yii::$app->getSecurity()->generatePasswordHash($this->user_password);
            //change the date format
            $this->user_dob = Yii::$app->formatter->asDate($this->user_dob, 'yyyy-MM-dd');
            //set an api access token... why not
            $this->user_access_token = \Yii::$app->security->generateRandomString();

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
        if (!$this->mailer->sendWelcomeMessage($this)) {
            Throw new Exception("Could not send welcome email");
        }

    }

    /**
     * @inheritDoc
     */
    public function afterFind()
    {
        parent::afterFind();

        //make sure we return the date in its original ISO/ICU format
        $this->user_dob = Yii::$app->formatter->asDate($this->user_dob, 'short');
    }


}
