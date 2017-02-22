<?php
namespace tests\models;


use app\models\UsersVenuesRatings;
use app\models\Venues;
use app\models\Users;

use Yii;

class UsersVenuesRatingsTest extends \Codeception\Test\Unit
{

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    private $user;
    private $venue;
    private $rating;


    protected function _before()
    {
        $this->user = Users::create();
        $this->venue = Venues::create();
        $this->rating = UsersVenuesRatings::create();
    }

    protected function _after()
    {
    }

    // tests
    public function testValidateAddNewRating()
    {
        $this->specify("User ID is required",function(){
            $this->rating->user_id = null;
            $this->assertFalse($this->rating->validate(['user_id']));
        });

        $this->specify("Venue ID is required",function(){
            $this->rating->venue_id = null;
            $this->assertFalse($this->rating->validate(['venue_id']));
        });

        $this->specify("Venue rating is required",function(){
            $this->rating->venue_rating = null;
            $this->assertFalse($this->rating->validate(['venue_rating']));
        });

        $this->specify("Venue rating must be an integer between 1 and 5 ",function(){
            $this->rating->venue_rating = 6;
            $this->assertFalse($this->rating->validate(['venue_rating']));
        });

        $this->specify("Can save rating",function(){

            $this->_createVenue();

            $this->rating->venue_rating = 5;
            $this->rating->user_id = $this->user->id;
            $this->rating->venue_id = $this->venue->id;
            $this->rating->venue_rating_comment = 'Test comment';
            $this->assertTrue($this->rating->save());

            $this->assertNotNull($this->rating->venue_rating_date);
        });
    }

    private function _createTestUser()
    {
        $this->user->user_firstname = 'Dwamian';
        $this->user->user_lastname = 'Mcleish';
        $this->user->user_email = 'test2@gmail.com';
        $this->user->user_phone = '8192189988';
        $this->user->user_password = Yii::$app->getSecurity()->generatePasswordHash('password');

        $this->user->save();
    }

    private function _createVenue()
    {
        //we need to create a user first
        $this->_createTestUser();

        $this->venue->user_id = $this->user->id;
        $this->venue->venue_name = "Test Venue";
        $this->venue->venue_email = "test_venue_email@testvenue.com";
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