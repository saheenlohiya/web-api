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

    const EVENT_RATINGS_ADDED = 'ratingsAdded';


    public static function create(){
        //setup event handlers
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
                [['user_id','venue_id'],'required'],
                [['venue_rating_cat_1','venue_rating_cat_2','venue_rating_cat_3','venue_rating_cat_4','venue_rating_cat_4','venue_rating_cat_5','venue_rating_cat_6'], 'integer','min'=> 1, 'max'=> 5],
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->_calcRatingAverage();

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

        //auto follow
        UsersVenuesFollows::create()->follow($this->user_id,$this->venue_id);
    }

    public function getRatingsByVenue($user_id,$venue_id){
        if(isset($user_id) && isset($venue_id)){
            return UsersVenuesRatings::find()
                ->where(['user_id'=>$user_id,'venue_id'=>$venue_id])
                ->with('usersVenuesRatingsResponses')
                ->orderBy(['venue_rating_date'=>SORT_DESC])
                ->all()
                ;
        }
    }


    private function _calcRatingAverage(){
        $sum = 0;
        $count = 0;

        //we have 6 categories so lets just do a simple loop to check them and update the average
        //we dont want to use any 0 value cats
        for($i=1;$i<=6;$i++){
            $cat = 'venue_rating_cat_'.$i;
            if($this->$cat > 0){
                $sum += $this->$cat;
                $count++;
            }
        }

        //now calculate the average to save
        $this->venue_rating_average = $sum/$count;
    }


}
