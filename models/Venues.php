<?php

namespace app\models;

use app\components\behaviors\GeocodeBehavior;
use app\components\Conversions;
use app\models\base\Venues as BaseVenues;
use dosamigos\google\places\Place;
use dosamigos\google\places\Search;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues".
 */
class Venues extends BaseVenues
{


    public $results = [];
    private $_ids = [];

    public function extraFields()
    {
        return ['venuesImages'];
    }

    /**
     * @return Venues
     */
    public static function create()
    {
        return new self;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => GeocodeBehavior::className(),

                    'address' => [
                        'street_address' => $this->venue_address_1,
                        'city' => $this->venue_city,
                        'country' => 'United States'
                    ],
                    'latitudeAttribute' => 'venue_lat',
                    'longitudeAttribute' => 'venue_lon'

                ],
                [
                    'class' => TimestampBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['venue_date_added']
                    ],
                    // using datetime instead of UNIX timestamp:
                    'value' => new Expression('NOW()'),
                ],
            ]
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['user_id', 'venue_name', 'venue_email', 'venue_address_1', 'venue_city', 'venue_state', 'venue_zip', 'venue_type_id'], 'required'],
                ['venue_email', 'email']
            ]
        );
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $latitude
     * @param $longitude
     * @param int $radius
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getNearbyPlaces($latitude, $longitude, $radius = 20, $limit = 50)
    {

        //we will run a nearby places update first
        $updatePlaces = $this->_updateNearbyPlaces($latitude, $longitude, $radius, $limit);
        if ($updatePlaces) {
            //now search again assuming we now have an updated list
            $this->results = $this->getNearbySavedPlaces($latitude, $longitude, $radius, $limit);
        }
        // $headers = Yii::$app->response->headers;
        // $headers->add('X-Pagination-Current-Page', '');
        // $headers->add('X-Pagination-Total-Count', '');
        // $headers->add('X-Pagination-Page-Count', '');
        // $headers->add('X-Pagination-Per-Page', '');
        return $this->results;

    }

    public function venue($venue_id)
    {
        return self::find()->where(['id' => $venue_id])->with(['venuesImages'])->asArray()->one();
    }

    /**
     * @param $text
     * @param $latitude
     * @param $longitude
     * @param int $radius
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSearchPlaces($text, $latitude, $longitude, $radius = 20, $limit = 50)
    {
        //we will run a nearby places update first
        $this->_updateSearchedPlaces($text, $latitude, $longitude, $radius, $limit);
        if (count($this->_ids) > 0) {
            //now search again assuming we now have an updated list
            $this->results = $this->getSearchedSavedPlaces($this->_ids, $latitude, $longitude, $limit);
        }
        // $headers = Yii::$app->response->headers;
        // $headers->add('X-Pagination-Current-Page', '');
        // $headers->add('X-Pagination-Total-Count', '');
        // $headers->add('X-Pagination-Page-Count', '');
        // $headers->add('X-Pagination-Per-Page', '');
        return $this->results;
    }

    /**
     * @param $latitude
     * @param $longitude
     * @param int $radius
     * @param int $limit
     * @param int $offset
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getNearbySavedPlaces($latitude, $longitude, $radius = 20, $limit = 50, $offset = 0)
    {
        $sql = "
        SELECT *,
        (
            3959 * acos(cos(radians(:lat)) 
            * cos(radians(venue_lat)) 
            * cos( radians(venue_lon) 
            - radians(:lon)) 
            + sin(radians(:lat)) 
            * sin(radians(venue_lat)))
        ) AS distance FROM venues HAVING distance <= :radius ORDER BY distance ASC LIMIT " . $offset . ", " . $limit;

        $params = [
            ':lat' => $latitude,
            ':lon' => $longitude,
            ':radius' => $radius
        ];

        //return active record instead
        return Venues::findBySql($sql, $params)->with(['venuesImages'])->asArray()->all();

    }

    /**
     * @param array $ids
     * @param $latitude
     * @param $longitude
     * @param int $limit
     * @param int $offset
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSearchedSavedPlaces(array $ids, $latitude, $longitude, $limit = 50, $offset = 0)
    {

        $ids = implode(",", $ids);

        $sql = "
        SELECT *,
        (
            3959 * acos(cos(radians(:lat)) 
            * cos(radians(venue_lat)) 
            * cos( radians(venue_lon) 
            - radians(:lon)) 
            + sin(radians(:lat)) 
            * sin(radians(venue_lat)))
        ) AS distance FROM venues 
        WHERE id IN (" . $ids . ")
        ORDER BY distance ASC LIMIT " . $offset . ", " . $limit;

        $params = [
            ':lat' => $latitude,
            ':lon' => $longitude
        ];

        //return active record instead
        return Venues::findBySql($sql, $params)->with(['venuesImages'])->asArray()->all();
    }

    /**
     * List venues that have activity. Pass in "onlyClaimed to
     * @param bool $onlyClaimed
     * @return array
     */
    public function listActiveVenues($onlyClaimed = false)
    {

        $query = new Query();
        $query->select(['v.*', 'IF(ISNULL(v.user_id),0,1) AS is_claimed','(SELECT COUNT(*) FROM users_venues_ratings uvr WHERE uvr.venue_id = v.id) AS total_messages','MAX(vr.`venue_rating_date`) AS last_rating_date'])
            ->from('venues v')
            ->join('JOIN', 'users_venues_ratings vr', 'vr.venue_id = v.id')
            ->groupBy(['v.id'])
            ->orderBy('v.venue_name')
        ;

        if ($onlyClaimed) {
            $query->having('is_claimed = 1');
        }

        return $query->all();

    }

    /**
     * SELECT v.id,
        v.venue_name,
        v.venue_lat,
        v.venue_lon,
        v.venue_address_1,
        v.venue_address_2,
        v.venue_city,
        v.venue_state,
        v.venue_zip,
        v.venue_website,
        uvc.venue_claim_claimer_name,
        uvc.venue_claim_claimer_email,
        uvc.venue_claim_claimer_phone,
        uvc.venue_claim_status,
        uvc.venue_claim_date,
        uvc.venue_claim_hash
        FROM users_venues_claims uvc 
        JOIN venues v ON v.id = uvc.venue_id
        WHERE uvc.venue_claim_status = 'pending'
        AND v.venue_claimed IS FALSE
     */

    public function listPendingClaimedVenues(){
        $query = new Query();
        $query->select([
            "v.venue_name",
            "v.venue_lat",
            "v.venue_lon",
            "v.venue_address_1",
            "v.venue_address_2",
            "v.venue_city",
            "v.venue_state",
            "v.venue_zip",
            "v.venue_website",
            "v.venue_claimed",
            "uvc.venue_claim_claimer_name",
            "uvc.venue_claim_claimer_email",
            "uvc.venue_claim_claimer_phone",
            "uvc.venue_claim_status",
            "uvc.venue_claim_date",
            "uvc.venue_claim_hash",
            "uvc.venue_claim_code"
        ])
        ->from('users_venues_claims uvc')
        ->join('JOIN','venues v','v.id = uvc.venue_id')
        ->where('uvc.venue_claim_status = "pending" AND (v.venue_claimed IS FALSE OR v.venue_claimed IS NULL)');

        return $query->all();

    }

    /**
     * @param $latitude
     * @param $longitude
     * @param int $radius
     * @param int $limit
     * @return bool
     */
    private function _updateNearbyPlaces($latitude, $longitude, $radius = 20, $limit = 50)
    {

        $radius = Conversions::meters_to_miles($radius);

        $search = new Search(['key' => Yii::$app->params['googleApiKey']]);
        $results = $search->nearby($latitude . "," . $longitude, ['rankby' => 'distance', 'types' => [], 'radius' => $radius]);

        $count = 0;

        //we only want to issue a db update when the results we get back is
        //greater than the results we have on file!
        if (isset($results['results']) && count($results['results'])) {
            //loop through the results and save to venues
            foreach ($results['results'] as $result) {
                $this->_saveNewGooglePlaceSummary($result);
                $count++;
            }
        }

        return true;

    }

    /**
     * @param $keyword
     * @param $latitude
     * @param $longitude
     * @param int $radius
     * @param int $limit
     * @param null $next_page_token
     */
    private function _updateSearchedPlaces($keyword, $latitude, $longitude, $radius = 50, $limit = 50, $next_page_token = null)
    {
        $radius = Conversions::meters_to_miles($radius);
        $search = new Search(['key' => Yii::$app->params['googleApiKey']]);
        $this->_nearby($search, $keyword, $latitude, $longitude, $radius, $limit, $next_page_token);
    }

    private function _nearby($search, $keyword, $latitude, $longitude, $radius = 50, $limit = 50, $next_page_token = null)
    {
        $results = $search->nearby($latitude . "," . $longitude, ['rankby' => 'distance', 'radius' => $radius, 'keyword' => $keyword, 'pagetoken' => $next_page_token]);

        //use a count to keep track of the limit so we dont overuse the places API
        $count = 0;

        //we only want to issue a db update when the results we get back is
        //greater than the results we have on file!
        if (isset($results['results']) && count($results['results'])) {
            //loop through the results and save to venues
            foreach ($results['results'] as $result) {
                $id = $this->_saveNewGooglePlaceSummary($result);
                if (!is_null($id)) {
                    $this->_ids[] = $id;
                }
                $count++;
            }

            if (isset($results['next_page_token']) && !is_null($results['next_page_token'])) {
                $this->_nearby($search, $keyword, $latitude, $longitude, $radius, $limit, $results['next_page_token']);
            }
        }
    }

    private function _saveNewGooglePlaceSummary($details)
    {
        $venue = Venues::find()->where(['venue_google_place_id' => $details['place_id']])->one();
        $venue_id = null;

        if (!isset($venue->id)) {

            $address_components = explode(",", $details['vicinity']);

            Yii::$app->db->createCommand()->insert('venues', [
                'venue_name' => $details['name'],
                'venue_google_place_id' => $details['place_id'],
                'venue_lat' => $details['geometry']['location']['lat'],
                'venue_lon' => $details['geometry']['location']['lng'],
                'venue_date_added' => date('Y-m-d H:i:s'),
                'venue_active' => 1,
                'venue_verified' => 0,
                'venue_verified_date' => null,
                'venue_last_verified_date' => null,
                'venue_address_1' => $address_components[0] ?? null,
                'venue_city' => $address_components[1] ?? null
            ])->execute();

            $id = Yii::$app->db->getLastInsertId();
            $this->_saveGooglePlacesPhotos($id, $details);
        } else {
            $venue_id = $venue->id;
        }

        return $venue_id;


    }

    /**
     * @param $item
     * @return int|null|string
     * @throws \yii\db\Exception
     */
    private function _saveNewGooglePlaceDetails($item)
    {

        $venue = Venues::find()->where(['venue_google_place_id' => $item['place_id']])->one();
        $venue_id = null;

        //first we have to make sure we dont have a place that exists with the same venue_google_place_id
        if (!isset($venue->id)) {
            //we wont use active records because we dont want to trigger the
            //geolocation behavior
            //need to get the details for this place...
            $place = new Place(['key' => Yii::$app->params['googleApiKey']]);
            $details = $place->details($item['place_id']);

            if (!isset($details['permanently_closed']) || !$details['permanently_closed']) {
                if ($details['status'] == 'OK') {
                    $details = $details['result'];

                    $address_components = $this->_getAddressComponents($details);

                    if (count($address_components)) {
                        Yii::$app->db->createCommand()->insert('venues', array_merge([
                            'venue_name' => $details['name'],
                            'venue_google_place_id' => $details['place_id'],
                            'venue_lat' => $details['geometry']['location']['lat'],
                            'venue_lon' => $details['geometry']['location']['lng'],
                            'venue_phone' => $details['formatted_phone_number'] ?? null,
                            'venue_type_id' => isset($details['types']) ? $this->_getVenueTypeID($details['types'][0]) : null,
                            'venue_date_added' => date('Y-m-d H:i:s'),
                            'venue_website' => $details['website'] ?? $details['url'],
                            'venue_active' => 1,
                            'venue_verified' => 1,
                            'venue_verified_date' => date('Y-m-d H:i:s'),
                            'venue_last_verified_date' => date('Y-m-d H:i:s')
                        ], $address_components))->execute();

                        $id = Yii::$app->db->getLastInsertId();
                        $this->_saveGooglePlacesPhotos($id, $details);


                        $venue_id = $id;
                    }
                }
            }
        } else {
            $venue_id = $venue->id;
        }

        return $venue_id;

    }

    /**
     * @param $type
     * @return int|null
     */
    private function _getVenueTypeID($type)
    {
        $type = VenuesTypes::find()->where(['venue_type_slug' => $type])->one();
        if ($type) {
            return $type->id;
        }

        return null;
    }

    /**
     * @param $details
     * @return array
     */
    private function _getAddressComponents($details)
    {

        $addr = [];
        $addr['venue_address_1'] = "";

        if (isset($details['address_components'])) {
            foreach ($details['address_components'] as $component) {
                if (isset($component['types']) && count($component['types'])) {
                    if ($component['types'][0] == 'postal_code') {
                        $addr['venue_zip'] = $component['long_name'];
                    }

                    if ($component['types'][0] == 'administrative_area_level_1') {
                        $addr['venue_state'] = $component['short_name'];
                    }

                    if ($component['types'][0] == 'locality') {
                        $addr['venue_city'] = $component['long_name'];
                    }

                    if ($component['types'][0] == 'street_number') {
                        $addr['venue_address_1'] = $component['long_name'];
                    }

                    if ($component['types'][0] == 'route') {
                        $addr['venue_address_1'] .= " " . $component['long_name'];
                    }

                }
            }
        }

        return $addr;
    }

    /**
     * @param $id
     * @param $detail
     * @throws \yii\db\Exception
     */
    private function _saveGooglePlacesPhotos($id, $detail)
    {
        if (isset($detail) && isset($detail['photos']) && count($detail['photos'])) {
            $photo_array = [];
            for ($i = 0; $i < count($detail['photos']); $i++) {
                if (isset($detail['photos'][$i])) {
                    $photo = $detail['photos'][$i];
                    if (count($photo) && isset($photo['photo_reference']) || count($photo['photo_reference'])) {
                        $photo_array[] = [
                            'venue_id' => $id,
                            'venue_image_url' => "https://maps.googleapis.com/maps/api/place/photo?key=" . Yii::$app->params['googleApiKey'] . "&photoreference=" . $photo['photo_reference'] . "&maxwidth=800",
                            'venue_image_date_added' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }

            if (count($photo_array)) {
                $columnNameArray = ['venue_id', 'venue_image_url', 'venue_image_date_added'];
                // below line insert all your record and return number of rows inserted
                Yii::$app->db->createCommand()
                    ->batchInsert(
                        'venues_images', $columnNameArray, $photo_array
                    )
                    ->execute();
            }

            $photo_array = null;
        }
    }

    //The following is handled by the 'venues_update_satisfaction_stats' stored procedure
    /**
     * Satisfaction is calculated using 2 factors.
     *
     * % resolved tickets (20% of satisfaction)
     * Avg rating % (80% of satisfaction)
     *
     * % Resolved tickets
     *
     * Get all ratings (total_ratings)
     * Get resolved ratings (total_resolved_ratings)
     * (total_resolved_ratings/total_ratings) * 100
     * - note: 0/0 is 100% resolved tickets (we need to decide)
     *
     * Avg rating %
     *
     * (Avg ratings/5) * 100
     * - 0/0 is 100% avg rating (we need to decide)
     * Satisfaction
     * ((% resolved tickets * 0.2) + (Avg rating % * 0.8))
     *
     * e.g.
     * % Resolved tickets = 60%
     * % avg ratings = 80%
     *
     * (60 * .2) + (80 * .8) = 12 + 64 = 76% Satisfaction
     */

}
