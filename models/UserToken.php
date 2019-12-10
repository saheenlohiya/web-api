<?php

namespace app\models;

use Yii;
use app\models\base\UserToken as BaseUserToken;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_follows".
 */
class UserToken extends BaseUserToken {

   

    public static function create() {
        return new self;
    }

    public function behaviors() {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                
            ]
        );
    }

    public function rules() {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['user_id', 'token', 'token_type'], 'required']
            ]
        );
    }
    
    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($insert) {
//                $this->_setAutoResolution();
            }

            return true;
        } else {
            return false;
        }
    }

    
    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $last_inserted_data =  self::find()
                ->where(['id' => $this->id])
                ->asArray(true)
                ->all();
            
            ArrayHelper::merge($this, $last_inserted_data[0]);
        }

    }
    
    
    public function addUserToken($params = array()) {
        if ( !empty($params) && !is_null($params['token']) && !is_null($params['token_type']) && !is_null($params['user_id'])) {
            $addnewToken                = self::create();
            $addnewToken->user_id       = $params['user_id'];
            $addnewToken->token         = $params['token'];
            $addnewToken->token_type    = $params['token_type'];
            if($addnewToken->save()){
               return true; 
            }
        }
        return false;
    }
    
    public function removeUserToken($params = array()) {
        if ( !empty($params) && !is_null($params['token']) && !is_null($params['user_id'])) {
            $removetoken_result    = self::deleteAll(['token'=>$params['token'],'user_id'=>$params['user_id']]);
            if(!is_null($removetoken_result) && ($removetoken_result != 0)){   
                return true; 
            }
        }
        return false;
    }
    
    

}
