<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\Users;
use app\models\UsersVenuesClaims;

class UsersVenuesClaimsController extends TuBaseApiController {
    // We are using the regular web app modules:
    public $modelClass = 'app\models\UsersVenuesClaims';

    /**
     * @return array
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => TuQueryParamAuth::className(),
            'except' => ['approve-claim'],
            'optional' => []
        ];
        return $behaviors;
    }

    public function actionClaim($user_id, $venue_id, $venue_claim_claimer_name, $venue_claim_claimer_email, $venue_claim_claimer_phone, $venue_claim_status = UsersVenuesClaims::VENUE_CLAIM_STATUS_PENDING) {
        if($this->checkAuthorization($user_id)){
            return UsersVenuesClaims::create()->claim($user_id, $venue_id, $venue_claim_claimer_name, $venue_claim_claimer_email, $venue_claim_claimer_phone, $venue_claim_status);
        }
    }

    public function actionRemoveClaim($user_id, $venue_id) {
        if($this->checkAuthorization($user_id)){
            $result =   UsersVenuesClaims::create()->removeVenueClaimById($user_id, $venue_id);
            if($result == 1){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * @param $user_id
     * @return \app\models\UsersVenuesClaims[]|array
     */
    public function actionListByUser($user_id) {
        if($this->checkAuthorization($user_id)){
            return UsersVenuesClaims::create()->getVenueClaimsByUser($user_id);
        }
    }

}