<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UsersVenuesClaims]].
 *
 * @see UsersVenuesClaims
 */
class UsersVenuesClaimsQuery extends \yii\db\ActiveQuery {
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UsersVenuesClaims[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UsersVenuesClaims|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
