<?php

namespace Goldfinch\FormHandler\Helpers;

use SilverStripe\Core\Environment;
use SilverStripe\Control\Director;
use Goldfinch\Service\GoogleRecaptcha;

class Rule
{
    public static function recaptcha() : callable
    {
        return function ($value) {

            $recaptcha = new GoogleRecaptcha(Environment::getEnv('GOOGLE_RECAPTCHA_SECRET_KEY'));

            // if(Director::isLive()) {

            //     $hostname = preg_replace('#^[^:/.]*[:/]+#i', '', Environment::getEnv('SS_BASE_URL'));
            //     // $hostname = Environment::getEnv('SS_BASE_URL');

            // } else if(isset($_SERVER['HTTP_ORIGIN']) && strpos($_SERVER['HTTP_ORIGIN'], 'localhost') >= 0) { // for localhost view (webpack)

            //     $hostname = $_SERVER['HTTP_HOST'];

            // } else {

            //     $hostname = Environment::getEnv('SS_BASE_URL');

            // }

            $resp = $recaptcha
                // ->setExpectedHostname($hostname)
                // ->setExpectedHostname('localhost')
                ->setExpectedHostname($_SERVER['HTTP_HOST'])
                ->verify($value, $_SERVER['REMOTE_ADDR']);

            // var_dump($resp->getErrorCodes());exit;

            if(!$resp->isSuccess()) {

                return ":attribute has been failed.".current($resp->getErrorCodes());
            }

        };
    }

    public static function inObject(...$args) : callable
    {
        return function ($value) use ($args) {

            $model = $args[0];
            $request = $args[1];

            if($value && !$model::get()->byID($value))
            {
                return 'Invalid ID of the :attribute';
            }
        };
    }
}
