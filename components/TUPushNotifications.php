<?php

namespace app\components;

use UrbanAirship\Airship;
use UrbanAirship\AirshipException;
use UrbanAirship\Push as P;
use UrbanAirship\Push\MultiPushRequest;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use UrbanAirship\UALog;


class TUPushNotifications {
    public $airship;
    public $audience;
    public $message;
    public $deviceTypes;

    public function __construct() {
        UALog::setLogHandlers(array(new StreamHandler("php://stdout", Logger::INFO)));
        $this->airship = new Airship(\Yii::$app->params['urbanairship']['key'], \Yii::$app->params['urbanairship']['secret']);
    }

    public static function create($message = "", $audience = P\all, $deviceTypes = P\all) {

        $instance = new self;

        if ($audience !== P\all) {
            //probably single device
            $audience = P\deviceToken($audience);
        }

        $instance->message = $message;
        $instance->audience = $audience;
        $instance->deviceTypes = $deviceTypes;

        return $instance;
    }

    public function send($multi = false) {
        if (!$multi) {
            return $this->_sendSingle();
        } else {
            return $this->_sendMulti();
        }
    }

    private function _sendSingle() {
        if ($this->message != '') {
            try {
                return $this->airship->push()
                    ->setAudience($this->audience)
                    ->setNotification(P\notification($this->message))
                    ->setDeviceTypes($this->deviceTypes)
                    ->send();
            } catch (AirshipException $e) {
                //print_r($e);
            }
        }

        return false;

    }

    private function _sendMulti() {

        if ($this->message != '') {
            try {
                $multiPushRequest = new MultiPushRequest($airship);
                $multiPushRequest->addPushRequest(
                    $this->airship->push()
                        ->setAudience($this->audience)
                        ->setNotification(P\notification($this->message))
                        ->setDeviceTypes($this->deviceTypes)
                );
                return $multiPushRequest->send();
            } catch (AirshipException $e) {
                //print_r($e);
            }
        }

        return false;

    }
}
