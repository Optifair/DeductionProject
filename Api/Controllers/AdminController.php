<?php

namespace Api\Controllers;

use Api\Admin\AdminControllers;

class AdminController extends Controller
{
    function AdTest()
    {
        AdminControllers\CrudController::MainTemplate();
    }

}
