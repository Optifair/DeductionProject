<?php

namespace Api\Controllers;

class Controller
{
    public static function setCORSHeaders()
    {
        header("Access-Control-Allow-Origin: http://deductionproject:3000");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: access-control-allow-credentials, access-control-allow-origin, content-type");
        header("Access-Control-Allow-Methods: *");
    }
}
