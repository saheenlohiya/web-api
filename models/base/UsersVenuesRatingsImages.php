<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "users_venues_ratings_images".
 *
 * @property integer $id
 * @property integer $user_venue_rating_id
 * @property string $user_venue_rating_image_url
 *
 * @property \app\models\UsersVenuesRatings $userVenueRating
 * @property string $aliasModel
 */
abstract class UsersVenuesRatingsImages extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_venues_ratings_images';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_venue_rating_id'], 'integer'],
            [['user_venue_rating_image_url'], 'string'],
            [['user_venue_rating_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\UsersVenuesRatings::className(), 'targetAttribute' => ['user_venue_rating_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_venue_rating_id' => 'User Venue Rating ID',
            'user_venue_rating_image_url' => 'User Venue Rating Image Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVenueRating()
    {
        return $this->hasOne(\app\models\UsersVenuesRatings::className(), ['id' => 'user_venue_rating_id']);
    }


    
    /**
     * @inheritdoc
     * @return \app\models\UsersVenuesRatingsImagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\UsersVenuesRatingsImagesQuery(get_called_class());
    }


}
