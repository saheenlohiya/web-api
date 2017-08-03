<?php

namespace models;

use app\models\Users;
use app\models\UsersVenuesClaims;
use app\models\Venues;
use Codeception\Specify;
use Yii;

class UsersVenuesClaimsTest extends \Codeception\Test\Unit {

    use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    public $venue;
    public $user;
    public $user_venue_claim;

    protected function _before() {
        $this->venue = Venues::create();
        $this->user = Users::create();
        $this->user_venue_claim = UsersVenuesClaims::create();
    }

    protected function _after() {

    }

    // tests
    public function testClaimingVenueSetsPending() {

        $this->_createVenue();

        //let specify the required fields
        $this->specify("Venue ID is required", function () {
            $this->user_venue_claim->venue_id = null;
            $this->assertFalse($this->user_venue_claim->validate(['venue_id']));
        });

        $this->specify("User ID is required", function () {
            $this->user_venue_claim->user_id = null;
            $this->assertFalse($this->user_venue_claim->validate(['user_id']));
        });

        $this->specify("User Full Name is required", function () {

            $this->user_venue_claim->venue_claim_claimer_name = null;
            $this->assertFalse($this->user_venue_claim->validate(['venue_claim_claimer_name']));
        });

        $this->specify("User Email is required", function () {

            $this->user_venue_claim->venue_claim_claimer_email = null;
            $this->assertFalse($this->user_venue_claim->validate(['venue_claim_claimer_email']));
        });

        $this->specify("User Phone is required", function () {

            $this->user_venue_claim->venue_claim_claimer_phone = null;
            $this->assertFalse($this->user_venue_claim->validate(['venue_claim_claimer_phone']));
        });

        $this->user_venue_claim->venue_id = $this->venue->id;
        $this->user_venue_claim->user_id = $this->user->id;
        $this->user_venue_claim->venue_claim_claimer_name = $this->user->user_firstname . " " . $this->user->user_lastname;
        $this->user_venue_claim->venue_claim_claimer_email = $this->user->user_email;
        $this->user_venue_claim->venue_claim_claimer_phone = '888-888-8888';

        $this->user_venue_claim->save();

        //make sure date is set
        $this->assertNotNull($this->user_venue_claim->venue_claim_date);
        //make sure initial status is set and it == pending
        $this->assertNotNull($this->user_venue_claim->venue_claim_status);
        $this->assertEquals(UsersVenuesClaims::VENUE_CLAIM_STATUS_PENDING, $this->user_venue_claim->venue_claim_status);

        //make sure the claim hash is set
        $this->assertNotNull($this->user_venue_claim->venue_claim_hash);

        //make sure the claim code is set
        $this->assertNotNull($this->user_venue_claim->venue_claim_code);

    }


    public function testClaimNotifiesAdmins() {

    }

    public function testCannotReclaimVenue() {
        //make sure the venue is not already claimed
    }

    //setup required model setups
    private function _createTestUser() {
        $this->user->user_firstname = 'Dwamian';
        $this->user->user_lastname = 'Mcleish';
        $this->user->user_email = 'dmcleish112@gmail.com';
        $this->user->user_username = 'dwamianm';
        $this->user->user_phone = '8192189988';
        $this->user->user_zip = '78758';
        $this->user->user_gender = 'M';
        $this->user->user_dob = '10/08/1978';
        $this->user->user_password = Yii::$app->getSecurity()->generatePasswordHash('password');

        $this->user->save();
    }

    private function _createVenue() {
        //we need to create a user first
        $this->_createTestUser();

        $this->venue->user_id = $this->user->id;
        $this->venue->venue_name = "Test Venue";
        $this->venue->venue_email = "test_venue_email2@testvenue.com";
        $this->venue->venue_address_1 = "9185 Research Blvd";
        $this->venue->venue_city = "Austin";
        $this->venue->venue_state = "TX";
        $this->venue->venue_zip = "78758";
        $this->venue->venue_type_id = 1;

        $this->venue->address = [
            'street_address' => $this->venue->venue_address_1,
            'postal_code' => $this->venue->venue_zip
        ];

        $this->venue->save();
    }
}