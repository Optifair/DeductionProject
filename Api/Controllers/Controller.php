<?php

namespace Api\Controllers;

class Controller
{
    public const frontPath = "http://deductionproject:3000";

    public static function setCORSHeaders()
    {
        header("Access-Control-Allow-Origin: " . self::frontPath);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: access-control-allow-credentials, access-control-allow-origin, content-type");
        header("Access-Control-Allow-Methods: *");
    }


}
