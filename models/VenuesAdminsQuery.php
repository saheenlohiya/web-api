<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VenuesAdmins]].
 *
 * @see VenuesAdmins
 */
class VenuesAdminsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VenuesAdmins[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VenuesAdmins|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
