<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "venues_admins".
 *
 * @property integer $id
 * @property integer $venue_id
 * @property integer $user_id
 * @property integer $venue_admin_level
 *
 * @property \app\models\Venues $venue
 * @property \app\models\Users $user
 * @property \app\models\VenuesCoupons[] $venuesCoupons
 * @property string $aliasModel
 */
abstract class VenuesAdmins extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'venues_admins';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['venue_id', 'user_id', 'venue_admin_level'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Users::className(), 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'venue_id' => 'Venue ID',
            'user_id' => 'User ID',
            'venue_admin_level' => '1000 is the highest level.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'venue_admin_level' => '1000 is the highest level.',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(\app\models\Venues::className(), ['id' => 'venue_id']);
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
    public function getVenuesCoupons()
    {
        return $this->hasMany(\app\models\VenuesCoupons::className(), ['venue_admin_id' => 'id']);
    }


    
    /**
     * @inheritdoc
     * @return \app\models\VenuesAdminsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\VenuesAdminsQuery(get_called_class());
    }


}
