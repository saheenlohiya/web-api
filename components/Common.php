<?php

namespace app\components;


class Common
{
    public static function formatPhoneNumber($phoneNumber){
        return preg_replace('~[^0-9]+~', '', $phoneNumber);
    }
}