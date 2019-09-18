<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UsersVenuesCoupons]].
 *
 * @see UsersVenuesCoupons
 */
class UsersVenuesCouponsQuery extends \yii\db\ActiveQuery {
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UsersVenuesCoupons[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UsersVenuesCoupons|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
