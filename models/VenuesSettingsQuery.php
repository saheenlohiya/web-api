<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VenuesSettings]].
 *
 * @see VenuesSettings
 */
class VenuesSettingsQuery extends \yii\db\ActiveQuery {
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VenuesSettings[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VenuesSettings|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
