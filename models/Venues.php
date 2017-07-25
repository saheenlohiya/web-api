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
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues".
 */
class Venues extends BaseVenues {


    public $results = [];

    public function extraFields()
    {
        return ['venuesImages'];
    }

    /**
     * @return Venues
     */
    public static function create() {
        return new self;
    }

    /**
     * @return array
     */
    public function behaviors() {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => GeocodeBehavior::className(),

                    'address' => [
                        'street_address' => $this->venue_address_1,
                        'postal_code' => $this->venue_zip,
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
    public function rules() {
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
    public function beforeSave($insert) {
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
    public function getNearbyPlaces($latitude, $longitude, $radius = 5, $limit = 20) {

        //we will run a nearby places update first
        $updatePlaces = $this->_updateNearbyPlaces($latitude, $longitude, $radius, $limit);
        if ($updatePlaces) {
            //now search again assuming we now have an updated list
            $this->results = $this->getNearbySavedPlaces($latitude, $longitude, $radius, $limit);
        }

        return $this->results;

    }

    public function venue($venue_id) {
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
    public function getSearchPlaces($text, $latitude, $longitude, $radius = 5, $limit = 20) {
        //we will run a nearby places update first
        $ids = $this->_updateSearchedPlaces($text, $latitude, $longitude, $radius, $limit);
        if (count($ids) > 0) {
            //now search again assuming we now have an updated list
            $this->results = $this->getSearchedSavedPlaces($ids, $latitude, $longitude, $limit);
        }

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
    public function getNearbySavedPlaces($latitude, $longitude, $radius = 5, $limit = 20, $offset = 0) {
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
    public function getSearchedSavedPlaces(array $ids, $latitude, $longitude, $limit = 20, $offset = 0) {

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
     * @param $latitude
     * @param $longitude
     * @param int $radius
     * @param int $limit
     * @return bool
     */
    private function _updateNearbyPlaces($latitude, $longitude, $radius = 5, $limit = 20) {

        $radius = Conversions::meters_to_miles($radius);

        $search = new Search(['key' => Yii::$app->params['googleApiKey']]);
        $results = $search->nearby($latitude . "," . $longitude, ['rankby' => 'distance', 'types' => [], 'radius' => $radius]);

        $count = 0;

        //we only want to issue a db update when the results we get back is
        //greater than the results we have on file!
        if (isset($results['results']) && count($results['results'])) {
            //loop through the results and save to venues
            foreach ($results['results'] as $result) {
                if ($count >= $limit) break;
                $this->_saveNewGooglePlace($result);
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
     * @return array
     */
    private function _updateSearchedPlaces($keyword, $latitude, $longitude, $radius = 5, $limit = 20) {

        $radius = Conversions::meters_to_miles($radius);

        $search = new Search(['key' => Yii::$app->params['googleApiKey']]);
        $results = $search->radar($latitude . "," . $longitude, $radius, ['rankby' => 'distance', 'keyword' => $keyword]);
        $ids = [];

        //use a count to keep track of the limit so we dont overuse the places API
        $count = 0;

        //we only want to issue a db update when the results we get back is
        //greater than the results we have on file!
        if (isset($results['results']) && count($results['results'])) {
            //loop through the results and save to venues
            foreach ($results['results'] as $result) {
                if ($count >= $limit) break;
                $id = $this->_saveNewGooglePlace($result);
                if (!is_null($id)) {
                    array_push($ids, $id);
                }
                $count++;
            }
        }

        return $ids;

    }

    /**
     * @param $item
     * @return int|null|string
     * @throws \yii\db\Exception
     */
    private function _saveNewGooglePlace($item) {

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
    private function _getVenueTypeID($type) {
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
    private function _getAddressComponents($details) {

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
     */
    private function _saveGooglePlacesPhotos($id, $detail) {
        if (isset($detail) && isset($detail['photos']) && count($detail['photos'])) {
            foreach ($detail['photos'] as $photo) {
                if (count($photo) && isset($photo['photo_reference']) && count($photo['photo_reference'])) {
                    $venueImage = new VenuesImages();
                    $venueImage->venue_id = $id;
                    $venueImage->venue_image_url = "https://maps.googleapis.com/maps/api/place/photo?key=" . Yii::$app->params['googleApiKey'] . "&photoreference=" . $photo['photo_reference'] . "&maxwidth=800";
                    $venueImage->venue_image_date_added = date('Y-m-d H:i:s');
                    $venueImage->save();
                }
            }
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
