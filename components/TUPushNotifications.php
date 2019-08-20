<?php

namespace app\components;

use Yii;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;


class TUPushNotifications {
    public $message;
    public $client;

    public function __construct() {
    }

    public static function create($messageBody = "", $deviceToken) {

        $instance = new self;
        
        
        $apiKey = 'AIzaSyDyOtE4I9elAGpAAPrxPOzChqTM6k7Z_do';
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        $note = new Notification('TellUs', $messageBody);
        $note->setBadge(1);

        $message = new Message();
        $message->addRecipient(new Device($deviceToken));
        $message->setNotification($note);

        $instance->message = $message;
        $instance->client = $client;
        return $instance;
        
    }

    public function send() {
        if ($this->message != '') {
            try {
                $response = $this->client->send($this->message);
               print_r((string) $response->getBody());
               return $response;
                //return  $response = Yii::$app->fcm->send($this->message);
            } catch (Exception $e) {
                print_r($e);
            }
        }

        return false;
    }
}
