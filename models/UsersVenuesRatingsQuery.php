<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UsersVenuesRatings]].
 *
 * @see UsersVenuesRatings
 */
class UsersVenuesRatingsQuery extends \yii\db\ActiveQuery {
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UsersVenuesRatings[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UsersVenuesRatings|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
