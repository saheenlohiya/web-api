<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VenuesTypes]].
 *
 * @see VenuesTypes
 */
class VenuesTypesQuery extends \yii\db\ActiveQuery {
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VenuesTypes[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VenuesTypes|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
