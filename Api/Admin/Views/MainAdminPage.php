<?php

namespace Api\Admin\Views;


class MainAdminPage implements IRenderable
{
    public static function render()
    {
        require "Templates/MainAdminTemplate.php";
    }

}