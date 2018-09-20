<?php

namespace app\components;

use Yii;
use paragraph1\phpFCM\Recipient\Device;


class TUPushNotifications {
    public $message;

    public function __construct() {
    }

    public static function create($messageBody = "", $deviceToken) {

        $instance = new self;

        $note = Yii::$app->fcm->createNotification("TellUs", $messageBody);
        //$note->setBadge(0);

        $message = Yii::$app->fcm->createMessage();
        $message->addRecipient(new Device($deviceToken));
        $message->setNotification($note);

        $instance->message = $message;

        return $instance;
    }

    public function send() {
        if ($this->message != '') {
            try {
                return  $response = Yii::$app->fcm->send($this->message);
            } catch (Exception $e) {
                //print_r($e);
            }
        }

        return false;
    }
}
