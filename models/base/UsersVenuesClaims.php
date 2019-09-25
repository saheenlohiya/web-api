<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "users_venues_claims".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $venue_id
 * @property string $venue_claim_claimer_name
 * @property string $venue_claim_claimer_email
 * @property string $venue_claim_claimer_phone
 * @property string $venue_claim_date
 * @property string $venue_claim_status
 * @property string $venue_claim_verified_date
 * @property integer $venue_claim_verify_admin
 * @property string $venue_claim_hash
 * @property integer $venue_claim_code
 * @property string $venue_claim_update_date
 *
 * @property \app\models\Users $user
 * @property \app\models\Venues $venue
 * @property string $aliasModel
 */
abstract class UsersVenuesClaims extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    const VENUE_CLAIM_STATUS_PENDING = 'pending';
    const VENUE_CLAIM_STATUS_ACTIVE = 'active';
    const VENUE_CLAIM_STATUS_SUSPENDED = 'suspended';
    var $enum_labels = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_venues_claims';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'venue_id', 'venue_claim_verify_admin', 'venue_claim_code'], 'integer'],
            [['venue_claim_date', 'venue_claim_verified_date', 'venue_claim_update_date'], 'safe'],
            [['venue_claim_status'], 'string'],
            [['venue_claim_claimer_name'], 'string', 'max' => 255],
            [['venue_claim_claimer_email'], 'string', 'max' => 100],
            [['venue_claim_claimer_phone'], 'string', 'max' => 20],
            [['venue_claim_hash'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            ['venue_claim_status', 'in', 'range' => [
                    self::VENUE_CLAIM_STATUS_PENDING,
                    self::VENUE_CLAIM_STATUS_ACTIVE,
                    self::VENUE_CLAIM_STATUS_SUSPENDED,
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
            'user_id' => 'User ID',
            'venue_id' => 'Venue ID',
            'venue_claim_claimer_name' => 'Venue Claim Claimer Name',
            'venue_claim_claimer_email' => 'Venue Claim Claimer Email',
            'venue_claim_claimer_phone' => 'Venue Claim Claimer Phone',
            'venue_claim_date' => 'Venue Claim Date',
            'venue_claim_status' => 'Venue Claim Status',
            'venue_claim_verified_date' => 'Venue Claim Verified Date',
            'venue_claim_verify_admin' => 'Venue Claim Verify Admin',
            'venue_claim_hash' => 'Venue Claim Hash',
            'venue_claim_code' => 'Venue Claim Code',
            'venue_claim_update_date' => 'Venue Claim Update Date',
        ];
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
    public function getVenue()
    {
        return $this->hasOne(\app\models\Venues::className(), ['id' => 'venue_id']);
    }


    
    /**
     * @inheritdoc
     * @return \app\models\UsersVenuesClaimsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\UsersVenuesClaimsQuery(get_called_class());
    }


    /**
     * get column venue_claim_status enum value label
     * @param string $value
     * @return string
     */
    public static function getVenueClaimStatusValueLabel($value){
        $labels = self::optsVenueClaimStatus();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column venue_claim_status ENUM value labels
     * @return array
     */
    public static function optsVenueClaimStatus()
    {
        return [
            self::VENUE_CLAIM_STATUS_PENDING => self::VENUE_CLAIM_STATUS_PENDING,
            self::VENUE_CLAIM_STATUS_ACTIVE => self::VENUE_CLAIM_STATUS_ACTIVE,
            self::VENUE_CLAIM_STATUS_SUSPENDED => self::VENUE_CLAIM_STATUS_SUSPENDED,
        ];
    }

}
