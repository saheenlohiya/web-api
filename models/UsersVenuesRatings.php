<?php

namespace app\models;

use Yii;
use \app\models\base\UsersVenuesRatings as BaseUsersVenuesRatings;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_ratings".
 */
class UsersVenuesRatings extends BaseUsersVenuesRatings
{

    public static function create(){
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
                        ActiveRecord::EVENT_BEFORE_INSERT => ['venue_rating_date']
                    ],
                    // using datetime instead of UNIX timestamp:
                    'value' => new Expression('NOW()'),
                ]
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['user_id','venue_id','venue_rating_cat_1'],'required'],
                ['venue_rating_cat_1', 'integer','min'=> 1, 'max'=> 5],
            ]
        );
    }
}
