<?php

namespace Api\Admin\AdminControllers;

use Api\Admin\Views\MainAdminPage;

class CrudController
{
    static function MainTemplate()
    {
        MainAdminPage::render();
    }

}
