<?php
namespace App\Helpers;

class GetView {

    public static function login(){
        $module = \Module::getUsedNow();
        dd($module);
    }
}