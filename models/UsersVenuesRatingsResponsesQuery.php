<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UsersVenuesRatingsResponses]].
 *
 * @see UsersVenuesRatingsResponses
 */
class UsersVenuesRatingsResponsesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UsersVenuesRatingsResponses[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UsersVenuesRatingsResponses|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
