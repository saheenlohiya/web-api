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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->user_password = Yii::$app->getSecurity()->generatePasswordHash($this->user_password);
            return true;
        } else {
            return false;
        }
    }

}
