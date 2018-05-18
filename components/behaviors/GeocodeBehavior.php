<?php
/**
 * GeocodeBehavior file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Geocode
 */

namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\db\BaseActiveRecord;
use Geocoder\Query\GeocodeQuery;

/**
 * GeocodeBehavior automatically geocodes the address in the owner model and
 * sets the specified latitude and longitude attributes when the owner model is
 * inserted.
 *
 * GeocodeBehavior uses the {@link https://github.com/geocoder-php/Geocoder Geocoder}
 * library and was inspired by the {@link https://github.com/geocoder-php/GeocodableBehavior GeocodableBehavior} for Propel.
 *
 * To use GeocodeBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use app\components\behaviors\GeocodeBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         GeocodeBehavior::className()
 *     ];
 * }
 * ```
 * or if you need to configure GeocodeBehavior:
 *
 * ```php
 * use app\components\behaviors\GeocodeBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => GeocodeBehavior::className(),
 *             <GecodeBehavior parameters as name => value pairs>
 *         ]
 *     ];
 * }
 *
 * The behavior also adds the following methods:
 * setCoordinates - sets the specified latitude and longitude attributes in the
 * owner model
 * getCordinates - returns an array with the latitude and longitude values
 * distanceTo - returns the distance in metres to the specified model from the
 * owner model using the Haversine formula
 * isGeocoded - returns a value indicating whether or not the owner model has
 * been geocoded
 */
class GeocodeBehavior extends Behavior
{
    /**
     * Earth mean radius in metres
     */
    const EARTH_MEAN_RADIUS = 6371000;

    /**
     * Maximum latitude value
     */
    const LATITUDE_MAX = 90;

    /**
     * Maximum longitude value
     */
    const LONGITUDE_MAX = 180;

    const TO_MILES = 0.0006;
    const TO_NAUTICAL_MILES = 0.0005;

    /**
     * @var empty|string|array If it is the empty the IP address of the user
     * (Yii::$app->request->userIP) is used.
     * If a sring it is the attribute to use as the parameter for geocoding an
     * IP address, e.g. `ip_address`.
     * If an array it is an array of columns to use as the parameter for
     * geocoding a postal address or the columns that receive reverse geocoding
     * results.
     * If an attribute is specified as a $name => $value pair $value is used as
     * the value for the attribute and not fetched from the model, e.g.
     *
     * ```php
     * [
     * 'street_address',
     * 'locality',
     * 'region',
     * 'postal_code',
     * 'country' => 'United Kingdom'
     * ]
     * ```
     *
     * will limit results to the United Kingdom. NOTE: this is for geocoding
     * only; if reverse geocoding the attribute will be written.
     */
    public $address = [
        'street_address',
        'locality',
        'region',
        'postal_code',
        'country'
    ];
    /**
     * @var string The attribute that contains or will receive the latitude value
     */
    public $latitudeAttribute = 'latitude';
    /**
     * @var string The attribute that contains or will receive the longitude value
     */
    public $longitudeAttribute = 'longitude';
    /**
     * @var string The HTTP adapter
     */
    public $httpAdapter = 'Client';
    /**
     * @var string The geocoding service provider
     */
    public $provider = 'Nominatim\Nominatim';

    private $_geocoder;

    /**
     * @inheritdoc
     * Summary.
     * Description
     *
     * @param mixed $arg1 description
     * @return string return
     */
    public function init()
    {
        $httpAdapterClass = '\Http\Adapter\Guzzle6\\'.$this->httpAdapter;
        $providerClass = '\Geocoder\Provider\\'.$this->provider;
        $adapter = new $httpAdapterClass();
        $this->_geocoder = $providerClass::withOpenStreetMapServer($adapter);
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'geocode'
        ];
    }

    /**
     * Geocodes the model.
     * If geocoding is successfull the latitude and longitude attributes in the
     * owner model are set
     */
    public function geocode()
    {
        $result = $this->_geocoder->geocodeQuery(GeocodeQuery::create($this->address()));

        if ($result instanceof \Geocoder\Model\AddressCollection) {
            $address = $result->first();

            $this->setCoordinates($address->getCoordinates()->getLatitude(),$address->getCoordinates()->getLongitude());
        }
    }

    /**
     * Returns the coordinates of the model as an array of latitude and
     * longitude indexed by their attribute names
     *
     * @return array Coordinates of the model
     */
    public function getCoordinates()
    {
        return [
            $this->latitudeAttribute => $this->owner->{$this->latitudeAttribute},
            $this->longitudeAttribute => $this->owner->{$this->longitudeAttribute}
        ];
    }

    /**
     * Calculates the distance in metres between the given model and the owner
     * using the Haversine formula.
     *
     * @param \yii\base\Model $model The other model
     * @param null|string $latitudeAttribute The latitude attribute in the other
     * model. If NULL $this->latitudeAttribute is used.
     * @param null|string $longitudeAttribute The longitude attribute in the other
     * model. If NULL $this->longitudeAttribute is used.
     * @return float The distance in metres between the two models.
     */
    public function distanceTo(
        $model,
        $latitudeAttribute = null,
        $longitudeAttribute = null
    )
    {
        if (is_null($latitudeAttribute)) {
            $latitudeAttribute = $this->latitudeAttribute;
        }

        if (is_null($longitudeAttribute)) {
            $longitudeAttribute = $this->longitudeAttribute;
        }

        $latitudeFrom = $this->owner->{$this->latitudeAttribute};
        $latitudeTo = $model->$latitudeAttribute;
        $deltaLatitude = deg2rad($latitudeTo - $latitudeFrom);
        $deltaLongitude = deg2rad($model->$longitudeAttribute - $this->owner->{$this->longitudeAttribute});

        return self::EARTH_MEAN_RADIUS * 2 * asin(
            sqrt(pow(sin($deltaLatitude / 2), 2) +
            cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) *
            pow(sin($deltaLongitude / 2), 2))
        );
    }

    /**
     * Returns a value indicating whether or not the model has been geocoded
     *
     * @return boolean TRUE if the model has been geocoded, FALSE if not
     */
    public function isGeocoded()
    {
        return (
            !empty($this->owner->{$this->latitudeAttribute}) &&
            !empty($this->owner->{$this->longitudeAttribute})
        );
    }

    /**
     * Sets the latitude and longitude values.
     *
     * @param float $latitude The latitude value.
     * @param float $longitude The longitude value.
     * @throws \yii\base\InvalidParamException if $latitude or $longitude are
     * out of range
     */
    public function setCoordinates($latitude, $longitude)
    {
        if (abs($latitude) > self::LATITUDE_MAX) {
            throw new InvalidParamException('Invalid `latitude` value; [-'.(self::LATITUDE_MAX * -1).' <= $latitude <= '.self::LATITUDE_MAX.']');
        }

        if (abs($longitude) > self::LONGITUDE_MAX) {
            throw new InvalidParamException('Invalid `longitude` value; [-'.(self::LONGITUDE_MAX * -1).' <= $longitude <= '.self::LONGITUDE_MAX.']');
        }

        $this->owner->setAttributes([
            $this->latitudeAttribute  => $latitude,
            $this->longitudeAttribute => $longitude
        ], false);
    }

    /**
     * Returns the address to geocode. Either the current user's IP
     * addresss or an IP or postal address derived from the owner model.
     *
     * @return string the address to geocode
     */
    private function address()
    {
        if (empty($this->address)) {
            return Yii::$app->request->userIP;
        }

        if (is_string($this->address)) {
            return $this->owner->{$this->address};
        }

        $address = [];

        foreach ($this->address as $key => $value) {
            $address[] = (is_int($key)
                ? ArrayHelper::getValue($this->owner, $value)
                : $value
            );
        }

        return join(',', $address);
    }
}
