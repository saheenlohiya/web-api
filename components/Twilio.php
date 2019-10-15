<?php
namespace app\components;
use Yii;
use yii\base\Component;

class Twilio {
    public static function sendSms($message, $mobile) {
        \Yii::$app->response->format = 'json';
        $sid            = \Yii::$app->params['twilioSid'];  //accessing the above twillio credentials saved in params.php file
        $token          = \Yii::$app->params['twiliotoken'];
        $twilioNumber   = \Yii::$app->params['twilioNumber'];
        
        try {
            $client = new \Twilio\Rest\Client($sid, $token);
            $account = $client->api->v2010->accounts("AC36f4562c07cb61e93024fc8f1b36e55f")->fetch();
//            print_r($account);
//            print_r($account->sid);
//            die;
            $client->messages->create($mobile, [
                'from' => $twilioNumber,
                'body' => (string) $message
            ]);
            $response = [
                'success' => true,
                'msg' => ''
            ];
        } catch(\Exception $e){
            $response = [
                'success' => false,
                'msg' => $e->getMessage()
            ];
        }
        //print_r($response);die;
    }
}