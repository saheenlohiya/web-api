<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "users".
 *
 * @property integer $id
 * @property string $user_firstname
 * @property string $user_lastname
 * @property string $user_email
 * @property string $user_phone
 * @property string $user_gender
 * @property string $user_dob
 * @property string $user_password
 * @property string $user_address_1
 * @property string $user_address_2
 * @property string $user_city
 * @property string $user_state
 * @property string $user_facebook_account_id
 * @property string $user_photo_url
 * @property string $user_ip_address
 * @property string $user_date_joined
 * @property string $user_date_modified
 * @property string $user_verification_code
 * @property integer $user_active
 * @property integer $user_is_verified
 *
 * @property \app\models\UsersVenuesCoupons[] $usersVenuesCoupons
 * @property \app\models\Venues[] $venues
 * @property \app\models\VenuesAdmins[] $venuesAdmins
 * @property string $aliasModel
 */
abstract class Users extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    const USER_GENDER_M = 'M';
    const USER_GENDER_F = 'F';
    var $enum_labels = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_gender', 'user_photo_url'], 'string'],
            [['user_dob', 'user_date_joined', 'user_date_modified'], 'safe'],
            [['user_active', 'user_is_verified'], 'integer'],
            [['user_firstname', 'user_lastname', 'user_facebook_account_id'], 'string', 'max' => 50],
            [['user_email', 'user_password', 'user_address_1', 'user_address_2'], 'string', 'max' => 100],
            [['user_phone'], 'string', 'max' => 20],
            [['user_city'], 'string', 'max' => 30],
            [['user_state'], 'string', 'max' => 2],
            [['user_ip_address'], 'string', 'max' => 16],
            [['user_verification_code'], 'string', 'max' => 32],
            [['user_email', 'user_phone'], 'unique', 'targetAttribute' => ['user_email', 'user_phone'], 'message' => 'The combination of User Email and User Phone has already been taken.'],
            ['user_gender', 'in', 'range' => [
                    self::USER_GENDER_M,
                    self::USER_GENDER_F,
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_firstname' => 'User Firstname',
            'user_lastname' => 'User Lastname',
            'user_email' => 'User Email',
            'user_phone' => 'User Phone',
            'user_gender' => 'User Gender',
            'user_dob' => 'User Dob',
            'user_password' => 'User Password',
            'user_address_1' => 'User Address 1',
            'user_address_2' => 'User Address 2',
            'user_city' => 'User City',
            'user_state' => 'User State',
            'user_facebook_account_id' => 'User Facebook Account ID',
            'user_photo_url' => 'User Photo Url',
            'user_ip_address' => 'User Ip Address',
            'user_date_joined' => 'User Date Joined',
            'user_date_modified' => 'User Date Modified',
            'user_verification_code' => 'User Verification Code',
            'user_active' => 'User Active',
            'user_is_verified' => 'User Is Verified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersVenuesCoupons()
    {
        return $this->hasMany(\app\models\UsersVenuesCoupons::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenues()
    {
        return $this->hasMany(\app\models\Venues::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesAdmins()
    {
        return $this->hasMany(\app\models\VenuesAdmins::className(), ['user_id' => 'id']);
    }


    
    /**
     * @inheritdoc
     * @return \app\models\UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\UsersQuery(get_called_class());
    }


    /**
     * get column user_gender enum value label
     * @param string $value
     * @return string
     */
    public static function getUserGenderValueLabel($value){
        $labels = self::optsUserGender();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column user_gender ENUM value labels
     * @return array
     */
    public static function optsUserGender()
    {
        return [
            self::USER_GENDER_M => self::USER_GENDER_M,
            self::USER_GENDER_F => self::USER_GENDER_F,
        ];
    }

}
