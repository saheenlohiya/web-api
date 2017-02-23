<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VenuesImages]].
 *
 * @see VenuesImages
 */
class VenuesImagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VenuesImages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VenuesImages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
