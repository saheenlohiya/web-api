<?php

namespace tests\models;


use app\models\Users;
use app\models\UsersVenuesFollows;
use app\models\UsersVenuesRatings;
use app\models\UsersVenuesRatingsResponses;
use app\models\Venues;
use Yii;

class UsersVenuesRatingsTest extends \Codeception\Test\Unit {

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    private $user;
    private $venue;
    private $rating;


    protected function _before() {
        $this->user = Users::create();
        $this->venue = Venues::create();
        $this->rating = UsersVenuesRatings::create();
    }

    protected function _after() {
    }

    // tests
    public function testValidateAddNewRating() {
        $this->specify("User ID is required", function () {
            $this->rating->user_id = null;
            $this->assertFalse($this->rating->validate(['user_id']));
        });

        $this->specify("Venue ID is required", function () {
            $this->rating->venue_id = null;
            $this->assertFalse($this->rating->validate(['venue_id']));
        });

        $this->specify("Venue rating must be an integer between 1 and 5 ", function () {
            $this->rating->venue_rating_cat_1 = 6;
            $this->assertFalse($this->rating->validate(['venue_rating_cat_1']));
        });

        $this->specify("Can save rating", function () {

            $this->_createVenue();

            $this->rating->venue_rating_cat_1 = 5;
            $this->rating->user_id = $this->user->id;
            $this->rating->venue_id = $this->venue->id;
            $this->rating->venue_rating_comment = 'Test comment';

            $this->assertTrue($this->rating->save());

            //make sure the auto follow worked
            $this->assertNotNull(
                UsersVenuesFollows::create()->find()->where(['user_id' => $this->user->id, 'venue_id' => $this->venue->id])->one()
            );

            //see if the auto thread was created
            $this->assertNotNull(
                UsersVenuesRatingsResponses::create()->find()->where(['user_venue_rating_responding_user_id' => $this->user->id, 'user_venue_rating_id' => $this->rating->id])->one()
            );

            $this->assertNotNull($this->rating->venue_rating_date);
            $this->assertNotNull($this->rating->venue_rating_resolve_expiration);

        });
    }

    public function testListByVenue() {
        $this->_createVenue();
        $results = UsersVenuesRatings::create()->getRatingsByVenue($this->user->id, $this->venue->id);
        $this->assertNotNull(
            $results
        );
    }

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
            'city' => $this->venue->venue_city
        ];

        $this->venue->save();

        $this->assertNotNull($this->venue->venue_lat);
    }
}