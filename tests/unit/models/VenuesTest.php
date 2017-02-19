<?php

namespace tests\models;

use app\models\Venues;
use Yii;


class VenuesTest extends \Codeception\Test\Unit
{

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    private $venue;
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /*
     *
     * return [
            [['user_id', 'venue_claim_code', 'venue_claimed', 'venue_type_id', 'venue_active'], 'integer'],
            [['venue_date_added', 'venue_claim_date', 'venue_claim_code_exp'], 'safe'],
            [['venue_image_url'], 'string'],
            [['venue_lat', 'venue_lon'], 'number'],
            [['venue_name', 'venue_google_place_id', 'venue_address_1', 'venue_address_2', 'venue_email'], 'string', 'max' => 100],
            [['venue_city'], 'string', 'max' => 20],
            [['venue_state'], 'string', 'max' => 2],
            [['venue_phone'], 'string', 'max' => 16],
            [['venue_google_place_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['venue_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\VenuesTypes::className(), 'targetAttribute' => ['venue_type_id' => 'id']]
        ];
     */

    // tests
    public function testValidateNewVenue()
    {
        $this->venue = Venues::create();

        $this->specify("ID of creating user is required", function () {
            $this->venue->user_id = null;
            $this->assertFalse($this->venue->validate(['user_id']));
        });

        $this->specify("Name of the venue is required",function(){
            $this->venue->venue_name = null;
            $this->assertFalse($this->venue->validate(['venue_name']));
        });

        $this->specify("Venue location is required",function(){
            $this->venue->venue_city = null;
            $this->venue->venue_state = null;
            $this->venue->venue_zip = null;
            $this->assertFalse($this->venue->validate(['venue_city','venue_state','venue_zip']));
        });
    }
}