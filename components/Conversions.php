<?php

namespace app\components;


class Conversions
{
    public static function meters_to_miles($meters){
        return $meters * 1609.34;
    }
}