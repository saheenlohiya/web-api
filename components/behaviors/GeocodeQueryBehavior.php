<?php
/**
 * GeocodeQueryBehavior file
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
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * GeocodeQueryBehavior adds three methods to the query:
 * byDistanceFrom - selects and orders records by the distance from the given geographical point
 * closestTo - selects the record closest to the given geographical point
 * distanceFrom - selects
 *
 * GeocodeQueryBehavior was inspired by the {@link https://github.com/geocoder-php/GeocodableBehavior GeocodableBehavior} for Propel.
 *
 * To use GeocodeQueryBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use app\components\behaviors\GeocodeQueryBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         GeocodeQueryBehavior::className()
 *     ];
 * }
 * ```
 * or if you need to configure GeocodeQueryBehavior:
 *
 * ```php
 * use app\components\behaviors\GeocodeQueryBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => GeocodeQueryBehavior::className(),
 *             'latitudeAttribute' => 'latitudeColumnName',
 *             'longitudeAttribute' => 'longitudeColumnName'
 *         ]
 *     ];
 * }
 */
class GeocodeQueryBehavior extends Behavior
{
    const DISTANCE_EXPRESSION = 'ABS({emr} * ACOS({cosLat} * COS(RADIANS({latAttr})) * COS(RADIANS({lngAttr}) - {lng}) + {sinLat} * SIN(RADIANS({latAttr}))))';

    /**
     * @var string The attribute that contains or will receive the latitude value
     */
    public $latitudeAttribute = 'latitude';
    /**
     * @var string The attribute that contains or will receive the longitude value
     */
    public $longitudeAttribute = 'longitude';

    private $_expression;

    /**
     * Initialises the object.
     * Initialises the expression.
     */
    public function init()
    {
        $this->_expression = strtr(self::DISTANCE_EXPRESSION, [
            '{emr}' => GeocodeBehavior::EARTH_MEAN_RADIUS,
            '{latAttr}' => $this->latitudeAttribute,
            '{lngAttr}' => $this->longitudeAttribute
        ]);
    }

    /**
     * Orders results by distance from a given geographical point.
     *
     * @param float $latitude The latitude of the geographical point.
     * @param float $longitude  The longitude of the geographical point.
     * @param array $select The columns to select. If empty only the distance is selected
     * @param integer $order SORT_ASC|SORT_DESC
     * @param string $property Name of the property that contains the distance value
     * @return \yii\db\ActiveQuery The ActiveQuery instance
     */
    public function byDistanceFrom($latitude, $longitude, $select = [], $order = SORT_ASC, $property = 'distance')
    {
        $select[$property] = $this->expression($latitude, $longitude);

        return $this->owner->select($select)->orderBy([
            $property => $order
        ]);
    }

    /**
     * Select the records closest to a given geographical point.
     *
     * @param float $latitude The latitude of the geographical point.
     * @param float $longitude  The longitude of the geographical point.
     * @return \yii\db\ActiveQuery The ActiveQuery instance
     */
    public function closestTo($latitude, $longitude)
    {
        $this->distanceFrom((new Query())->
        select(
            'MIN('.$this->expression($latitude, $longitude).')'),
            $latitude,
            $longitude,
            '='
        );
    }

    /**
     * Select records by distance from a given geographical point.
     *
     * @param float $distance The distance in metres between the origin and the
     * records to find.
     * @param float $latitude The latitude of the geographical point.
     * @param float $longitude The longitude of the geographical point.
     * @param string $operator Comparison operator
     * @return \yii\db\ActiveQuery The ActiveQuery instance
     */
    public function distanceFrom($distance, $latitude, $longitude, $operator = '<=')
    {
        return $this->owner->andWhere(
            [$operator, $this->expression($latitude, $longitude), $distance]
        );
    }

    /**
     * Finalises the distance expression
     *
     * @param float $latitude The latitude of the origin geographical point.
     * @param float $longitude The longitude of the origin geographical point.
     * @return string the finalised distance expression
     */
    private function expression($latitude, $longitude)
    {
        return strtr($this->_expression, [
            '{cosLat}' => cos(deg2rad($latitude)),
            '{lng}' => deg2rad($longitude),
            '{sinLat}' => sin(deg2rad($latitude))
        ]);
    }
}
