<?php

namespace app\components\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/*
 * UUID Behavior will set your IP Address
 */

class IPAddressBehavior extends Behavior
{
    /**
     * Default -> ip
     * @var type
     */
    public $column = 'ip_address';

    /**
     * Override event()
     * @return type
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
        ];
    }

    /**
     * set beforeSave() -> UUID data
     */
    public function beforeSave()
    {
        //$ip_address = gethostbynamel(gethostname())[0];
        $ip = getenv('REMOTE_ADDR');
        $this->owner->{$this->column} = isset($ip_address)?$ip_address:'0.0.0.0';
    }

}
