<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
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
            'except' => [],
            'optional' => []
        ];
        return $behaviors;
    }

    public function actionClaim($user_id, $venue_id) {
        return UsersVenuesClaims::create()->claim($user_id, $venue_id);
    }

    public function actionApproveClaim($approved = false, $claim_hash, $claim_code) {
        return UsersVenuesClaims::create()->approveClaim($approved, $claim_hash, $claim_code);

    }
}