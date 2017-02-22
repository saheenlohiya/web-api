<?php

namespace app\models;

use app\components\behaviors\IPAddressBehavior;
use app\components\behaviors\UUIDBehavior;
use \app\models\base\Users as BaseUsers;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

use app\components\behaviors\GeocodeBehavior;

/**
 * This is the model class for table "users".
 */
class Users extends BaseUsers
{

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

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_firstname' => 'Firstname',
            'user_lastname' => 'Lastname',
            'user_email' => 'Email',
            'user_phone' => 'Phone',
            'user_gender' => 'Gender',
            'user_dob' => 'Dob',
            'user_password' => 'Password',
            'user_address_1' => 'Address 1',
            'user_address_2' => 'Address 2',
            'user_city' => 'City',
            'user_state' => 'State',
            'user_facebook_account_id' => 'Facebook Account ID',
            'user_photo_url' => 'Photo Url',
            'user_ip_address' => 'Ip Address',
            'user_date_joined' => 'Date Joined',
            'user_date_modified' => 'Date Modified',
            'user_verification_code' => 'Verification Code',
            'user_active' => 'Active',
            'user_is_verified' => 'Is Verified',
        ];
    }

}
