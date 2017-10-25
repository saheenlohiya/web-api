<?php

namespace tests\models;

use app\models\Users;
use app\models\UsersVenuesRatings;
use app\models\UsersVenuesRatingsResponses;
use app\models\Venues;
use Yii;

class UsersVenuesRatingsResponsesTest extends \Codeception\Test\Unit {

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    private $user;
    private $venue;
    private $rating;
    private $rating_response;


    protected function _before() {
        $this->user = Users::create();
        $this->venue = Venues::create();
        $this->rating = UsersVenuesRatings::create();
        $this->rating_response = UsersVenuesRatingsResponses::create();
    }

    protected function _after() {
    }

    // tests
    public function testCanCreateResponse() {
        $this->_rateVenue();

        $this->assertNotNull($this->rating);

        $this->specify("Venue rating id is required", function () {
            $this->rating_response->user_venue_rating_id = null;
            $this->assertFalse($this->rating_response->validate(['user_venue_rating_id']));
        });

        $this->specify("Responding user id is required", function () {
            $this->rating_response->user_venue_rating_responding_user_id = null;
            $this->assertFalse($this->rating_response->validate(['user_venue_rating_responding_user_id']));
        });

        $this->specify("Comment is required", function () {
            $this->rating_response->user_venue_rating_response = null;
            $this->assertFalse($this->rating_response->validate(['user_venue_rating_response']));
        });

        //now fill the vales and save
        $this->rating_response->user_venue_rating_id = $this->rating->id;
        $this->rating_response->user_venue_rating_responding_user_id = $this->rating->user_id;
        $this->rating_response->user_venue_rating_response = "Test Comment";

        $this->assertTrue($this->rating_response->save());

        $this->assertNotNull($this->rating_response->user_venue_rating_response_date,"The response date was not set");

    }

    public function testCanCloseTicketWithCloseKeyword() {

        $this->_rateVenue();

        $this->assertNotNull($this->rating);

        //now fill the vales and save
        $this->rating_response->user_venue_rating_id = $this->rating->id;
        $this->rating_response->user_venue_rating_responding_user_id = $this->rating->user_id;
        $this->rating_response->user_venue_rating_response = "#close";

        $this->assertTrue($this->rating_response->save());

        //we will need to look up the rating again to see if the resolution is set
        $rating_updated = UsersVenuesRatings::find()->where(['id' => $this->rating->id])->one();

        $this->assertEquals(1, $rating_updated->venue_rating_resolved);

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
        $this->user->user_device_token = 'e8783e28a8a95c664c53c6920ad5503d4b60b761f00bbd9e0bb6dcf762ee709b';
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
            'city' => $this->venue->venue_city
        ];

        $this->venue->save();

        $this->assertNotNull($this->venue->venue_lat);
    }

    private function _rateVenue() {
        $this->_createVenue();

        $this->rating->venue_rating_cat_1 = 5;
        $this->rating->venue_rating_cat_2 = 2;
        $this->rating->venue_rating_cat_3 = 5;
        $this->rating->user_id = $this->user->id;
        $this->rating->venue_id = $this->venue->id;
        $this->rating->venue_rating_comment = 'Test comment';

        $this->rating->save();
    }
}