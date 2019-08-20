<?php

namespace app\api\modules\v1\traits;

trait ActiveRecordDbConnectionTrait {
    public static function getDb() {
        return \Yii::$app->db;
    }
}
