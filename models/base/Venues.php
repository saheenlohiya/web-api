<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "venues".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $venue_name
 * @property string $venue_google_place_id
 * @property string $venue_date_added
 * @property string $venue_date_modified
 * @property string $venue_address_1
 * @property string $venue_address_2
 * @property string $venue_city
 * @property string $venue_state
 * @property string $venue_zip
 * @property string $venue_phone
 * @property string $venue_email
 * @property string $venue_website
 * @property string $venue_lat
 * @property string $venue_lon
 * @property string $venue_claim_date
 * @property integer $venue_claim_code
 * @property string $venue_claim_code_exp
 * @property integer $venue_claimed
 * @property integer $venue_type_id
 * @property integer $venue_active
 * @property integer $venue_verified
 * @property string $venue_verified_date
 * @property string $venue_last_verified_date
 *
 * @property \app\models\UsersVenuesClaims[] $usersVenuesClaims
 * @property \app\models\UsersVenuesFollows[] $usersVenuesFollows
 * @property \app\models\UsersVenuesRatings[] $usersVenuesRatings
 * @property \app\models\Users $user
 * @property \app\models\VenuesTypes $venueType
 * @property \app\models\VenuesAdmins[] $venuesAdmins
 * @property \app\models\VenuesCoupons[] $venuesCoupons
 * @property \app\models\VenuesImages[] $venuesImages
 * @property \app\models\VenuesSettings $venuesSettings
 * @property string $aliasModel
 */
abstract class Venues extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'venues';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'venue_claim_code', 'venue_claimed', 'venue_type_id', 'venue_active', 'venue_verified'], 'integer'],
            [['venue_date_added', 'venue_date_modified', 'venue_claim_date', 'venue_claim_code_exp', 'venue_verified_date', 'venue_last_verified_date'], 'safe'],
            [['venue_website'], 'string'],
            [['venue_lat', 'venue_lon'], 'number'],
            [['venue_name', 'venue_google_place_id', 'venue_address_1', 'venue_address_2', 'venue_email'], 'string', 'max' => 100],
            [['venue_city'], 'string', 'max' => 20],
            [['venue_state'], 'string', 'max' => 2],
            [['venue_zip'], 'string', 'max' => 10],
            [['venue_phone'], 'string', 'max' => 16],
            [['venue_google_place_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['venue_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\VenuesTypes::className(), 'targetAttribute' => ['venue_type_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'venue_name' => 'Venue Name',
            'venue_google_place_id' => 'Venue Google Place ID',
            'venue_date_added' => 'Venue Date Added',
            'venue_date_modified' => 'Venue Date Modified',
            'venue_address_1' => 'Venue Address 1',
            'venue_address_2' => 'Venue Address 2',
            'venue_city' => 'Venue City',
            'venue_state' => 'Venue State',
            'venue_zip' => 'Venue Zip',
            'venue_phone' => 'Venue Phone',
            'venue_email' => 'Venue Email',
            'venue_website' => 'Venue Website',
            'venue_lat' => 'Venue Lat',
            'venue_lon' => 'Venue Lon',
            'venue_claim_date' => 'Venue Claim Date',
            'venue_claim_code' => 'Venue Claim Code',
            'venue_claim_code_exp' => 'Venue Claim Code Exp',
            'venue_claimed' => 'Venue Claimed',
            'venue_type_id' => 'Venue Type ID',
            'venue_active' => 'Venue Active',
            'venue_verified' => 'Venue Verified',
            'venue_verified_date' => 'Venue Verified Date',
            'venue_last_verified_date' => 'Venue Last Verified Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersVenuesClaims()
    {
        return $this->hasMany(\app\models\UsersVenuesClaims::className(), ['venue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersVenuesFollows()
    {
        return $this->hasMany(\app\models\UsersVenuesFollows::className(), ['venue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersVenuesRatings()
    {
        return $this->hasMany(\app\models\UsersVenuesRatings::className(), ['venue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenueType()
    {
        return $this->hasOne(\app\models\VenuesTypes::className(), ['id' => 'venue_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesAdmins()
    {
        return $this->hasMany(\app\models\VenuesAdmins::className(), ['venue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesCoupons()
    {
        return $this->hasMany(\app\models\VenuesCoupons::className(), ['venue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesImages()
    {
        return $this->hasMany(\app\models\VenuesImages::className(), ['venue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSettings()
    {
        return $this->hasOne(\app\models\VenuesSettings::className(), ['venue_id' => 'id']);
    }


    
    /**
     * @inheritdoc
     * @return \app\models\VenuesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\VenuesQuery(get_called_class());
    }


}
