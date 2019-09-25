<?php
namespace app\components;

use Yii;

class OptionsAction extends \yii\rest\OptionsAction {
    public $collectionOptions = ['GET', 'POST', 'HEAD','PUT', 'OPTIONS'];
    public function run($id = null) {
        parent::run($id);
        Yii::$app->response->headers->set
        ('Access-Control-Allow-Methods',Yii::$app->getResponse()->getHeaders()
            ->get('Allow'));
    }
}