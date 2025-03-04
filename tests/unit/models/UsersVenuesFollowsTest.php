<?php

namespace tests\models;

use app\models\Users;
use app\models\UsersVenuesFollows;
use app\models\Venues;
use Yii;
use yii\base\Exception;

class UsersVenuesFollowsTest extends \Codeception\Test\Unit {

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    private $venue;
    private $user;
    private $users_venues_follows;

    protected function _before() {
        $this->user = Users::create();
        $this->venue = Venues::create();
        $this->users_venues_follows = UsersVenuesFollows::create();

    }

    protected function _after() {
    }

    // tests
    public function testValidateNewUserVenueFollow() {
        //create the test venue

        $this->specify("ID of following user is required", function () {
            $this->users_venues_follows->user_id = null;
            $this->assertFalse($this->users_venues_follows->validate(['user_id']));
        });

        $this->specify("ID of venue is required", function () {
            $this->users_venues_follows->venue_id = null;
            $this->assertFalse($this->users_venues_follows->validate(['venue_id']));
        });

        $this->_createVenue();

        $this->users_venues_follows->user_id = $this->user->id;
        $this->users_venues_follows->venue_id = $this->venue->id;

        $this->assertTrue($this->users_venues_follows->save());
        $this->assertNotNull($this->users_venues_follows->user_venue_follow_date);

        //now test that you can only follow a venue only once
        $newVenueFollow = UsersVenuesFollows::create();
        $newVenueFollow->user_id = $this->user->id;
        $newVenueFollow->venue_id = $this->venue->id;

        $this->assertFalse($newVenueFollow->save());

    }

    public function testUnfollowVenue() {

        $this->_createVenue();

        $this->users_venues_follows->user_id = $this->user->id;
        $this->users_venues_follows->venue_id = $this->venue->id;

        $newVenue = $this->users_venues_follows->save();
        $this->assertTrue($newVenue);

        //now remove the venue
        $this->assertNotNull(UsersVenuesFollows::unfollow($this->user->id,$this->venue->id));

    }

    private function _createTestUser() {
        $this->user->user_firstname = 'Dwamian';
        $this->user->user_lastname = 'Mcleish';
        $this->user->user_email = 'dmcleish323@gmail.com';
        $this->user->user_username = 'dwamianm';
        $this->user->user_phone = '8192189988';
        $this->user->user_zip = '78758';
        $this->user->user_gender = 'M';
        $this->user->user_dob = '10/08/1978';
        $this->user->user_password = Yii::$app->getSecurity()->generatePasswordHash('password');

        if (!$this->user->save()) {
            Throw new Exception("Could not save user");
        }

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
            'city' => $this->venue->venue_city
        ];

        $this->venue->save();

        $this->assertNotNull($this->venue->venue_lat);
    }

}