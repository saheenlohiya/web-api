<?php
namespace app\commands;


use yii\console\Controller;
use Yii;

class SetupController extends Controller
{
    public function actionSetupVenueTypes()
    {
        $defaultTypeArray = [
            'accounting',
            'art_gallery',
            'aquarium',
            'airport',
            'amusement_park',
            'atm',
            'bakery',
            'bank',
            'bar',
            'beauty_salon',
            'bicycle_store',
            'book_store',
            'bowling_alley',
            'bus_station',
            'cafe',
            'campground',
            'car_dealer',
            'car_rental',
            'car_repair',
            'car_wash',
            'casino',
            'cemetery',
            'church',
            'city_hall',
            'clothing_store',
            'convenience_store',
            'courthouse',
            'dentist',
            'department_store',
            'doctor',
            'electrician',
            'electronics_store',
            'embassy',
            'fire_station',
            'florist',
            'funeral_home',
            'furniture_store',
            'gas_station',
            'grocery',
            'gym',
            'hair_care',
            'hardware_store',
            'hindu_temple',
            'home_goods_store',
            'hospital',
            'insurance_agency',
            'jewelry_store',
            'laundry',
            'lawyer',
            'library',
            'liquor_store',
            'local_government_office',
            'locksmith',
            'lodging',
            'meal_delivery',
            'meal_takeaway',
            'mosque',
            'movie_rental',
            'movie_theater',
            'moving_company',
            'museum',
            'night_club',
            'painter',
            'park',
            'parking',
            'pet_store',
            'pharmacy',
            'physiotherapist',
            'plumber',
            'police',
            'post_office',
            'real_estate_agency',
            'restaurant',
            'roofing_contractor',
            'rv_park',
            'school',
            'shoe_store',
            'shopping_mall',
            'spa',
            'stadium',
            'storage',
            'store',
            'subway_station',
            'synagogue',
            'taxi_stand',
            'train_station',
            'transit_station',
            'travel_agency',
            'university',
            'veterinary_care',
            'zoo'
        ];

        //we will loop through the list above to create the initial types
        foreach($defaultTypeArray as $type){
            Yii::$app->db->createCommand()->insert('venues_types', [
                'venue_type_slug' => $type,
                'venue_type_name' => ucwords(str_replace('_',' ',$type)),
            ])->execute();
        }
    }

    public function addVenueType($type = 'generic_type')
    {

    }
}