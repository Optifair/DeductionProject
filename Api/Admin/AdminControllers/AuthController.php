<?php

namespace Api\Admin\AdminControllers;

use Api\Admin\Views\AuthPage;

class AuthController
{
    static function Auth()
    {
        AuthPage::render();
    }

}
