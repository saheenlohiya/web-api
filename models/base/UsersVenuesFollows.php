<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "users_venues_follows".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $venue_id
 * @property string $user_venue_follow_date
 *
 * @property \app\models\Users $user
 * @property \app\models\Venues $venue
 * @property string $aliasModel
 */
abstract class UsersVenuesFollows extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_venues_follows';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'venue_id'], 'integer'],
            [['user_venue_follow_date'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Venues::className(), 'targetAttribute' => ['venue_id' => 'id']]
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
            'user_venue_follow_date' => 'User Venue Follow Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\Users::className(), ['id' => 'user_id'])->inverseOf('usersVenuesFollows');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(\app\models\Venues::className(), ['id' => 'venue_id'])->inverseOf('usersVenuesFollows');
    }


    
    /**
     * @inheritdoc
     * @return \app\models\UsersVenuesFollowsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\UsersVenuesFollowsQuery(get_called_class());
    }


}
