<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Venues;
use yii\console\Controller;

class SmartVenueUpdaterController extends Controller
{

    public function actionIndex($venue_id)
    {
        $this->_updateVenueById($venue_id);
    }

    private function _updateVenueById($venue_id){
        if(!is_null($venue_id)){
            $venue = Venues::findOne($venue_id);

            if(!is_null($venue) && !is_null($venue->venue_website) && is_null($venue->venue_email)){
                $this->_attemptToUpdateEmailAddressFromWebsite($venue,$venue->venue_website);
            }
        }
    }

    private function _attemptToUpdateEmailAddressFromWebsite(Venues $venue,$website_address){
        //get the website homepage
        $homepage = file_get_contents($website_address);
        if($homepage){
            
        }
    }
}
