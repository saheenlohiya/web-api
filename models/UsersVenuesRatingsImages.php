<?php

namespace app\models;

use Yii;
use \app\models\base\UsersVenuesRatingsImages as BaseUsersVenuesRatingsImages;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_ratings_images".
 */
class UsersVenuesRatingsImages extends BaseUsersVenuesRatingsImages
{

public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
             parent::rules(),
             [
                  # custom validation rules
             ]
        );
    }
}
