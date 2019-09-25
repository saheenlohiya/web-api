<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Venues]].
 *
 * @see Venues
 */
class VenuesQuery extends \yii\db\ActiveQuery {
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Venues[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Venues|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
