<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UsersVenuesRatingsImages]].
 *
 * @see UsersVenuesRatingsImages
 */
class UsersVenuesRatingsImagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UsersVenuesRatingsImages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UsersVenuesRatingsImages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
