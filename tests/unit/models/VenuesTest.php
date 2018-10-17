<?php

namespace tests\models;

use app\models\Users;
use app\models\Venues;
use Yii;


class VenuesTest extends \Codeception\Test\Unit {

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    private $venue;
    private $user;
    protected $tester;

    protected function _before() {
        $this->user = Users::create();
        $this->venue = Venues::create();
    }

    protected function _after() {
    }

    // tests
    public function testValidateNewVenue() {


        $this->specify("ID of creating user is required", function () {
            $this->venue->user_id = null;
            $this->assertFalse($this->venue->validate(['user_id']));
        });

        $this->specify("Name of the venue is required", function () {
            $this->venue->venue_name = null;
            $this->assertFalse($this->venue->validate(['venue_name']));
        });

        $this->specify("Venue email is required", function () {
            $this->venue->venue_email = null;
            $this->assertFalse($this->venue->validate(['venue_email']));
        });

        $this->specify("Venue type is required", function () {
            $this->venue->venue_type_id = null;
            $this->assertFalse($this->venue->validate(['venue_type_id']));
        });

        $this->specify("Venue state must be at most 2 characters", function () {
            $this->venue->venue_state = 'Texas';
            $this->assertFalse($this->venue->validate(['venue_state']));
        });

        $this->specify("Venue state fine when only 2 chars", function () {
            $this->venue->venue_state = 'TX';
            $this->assertTrue($this->venue->validate(['venue_state']));
        });

        $this->specify("Venue location is required", function () {
            $this->venue->venue_address_1 = null;
            $this->venue->venue_city = null;
            $this->venue->venue_state = null;
            $this->venue->venue_zip = null;
            $this->assertFalse($this->venue->validate(['venue_address_1']));
            $this->assertFalse($this->venue->validate(['venue_city']));
            $this->assertFalse($this->venue->validate(['venue_state']));
            $this->assertFalse($this->venue->validate(['venue_zip']));
        });

        $this->specify("Can save venue", function () {

            //we need to create a user first
            $this->_createTestUser();

            $this->venue->user_id = $this->user->id;
            $this->venue->venue_name = "Test Venue";
            $this->venue->venue_email = "test_venue_email23@testvenue.com";
            $this->venue->venue_address_1 = "9185 Research Blvd";
            $this->venue->venue_city = "Austin";
            $this->venue->venue_state = "TX";
            $this->venue->venue_zip = "78758";
            $this->venue->venue_type_id = 1;

            $this->assertTrue($this->venue->save());

            $this->assertNotNull($this->venue->venue_lat);
            $this->assertNotNull($this->venue->venue_lon);
        });

    }

    public function testCanListActiveVenues(){
        $venues = Venues::create();
        $results = $venues->listActiveVenues();

        $this->assertTrue(is_array($results));

        codecept_debug($results);
    }

    public function testCanSearchPlaces() {
//        $venues = Venues::create();
//        $savedPlaces = $venues->getSearchPlaces('lowes','30.267153', '-97.743061');
//
//        var_dump($savedPlaces);
//        exit;
    }

    public function testGetNearbyPlaces() {
//        $venues = Venues::create();
//        $savedPlaces = $venues->getNearbyPlaces('30.267153', '-97.743061',16093.4);
//        var_dump($savedPlaces);
//        exit;
//        $this->assertNotNull($savedPlaces);
//        exit;
    }


    private function _createTestUser() {
        $this->user->user_firstname = 'Dwamian';
        $this->user->user_lastname = 'Mcleish';
        $this->user->user_email = 'dmcleish554@gmail.com';
        $this->user->user_username = 'dwamianm';
        $this->user->user_phone = '8192189988';
        $this->user->user_zip = '78758';
        $this->user->user_gender = 'M';
        $this->user->user_dob = '10/08/1978';
        $this->user->user_password = Yii::$app->getSecurity()->generatePasswordHash('password');

        $this->user->save();
    }
}