<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UsersVenuesFollows]].
 *
 * @see UsersVenuesFollows
 */
class UsersVenuesFollowsQuery extends \yii\db\ActiveQuery {
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UsersVenuesFollows[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UsersVenuesFollows|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
