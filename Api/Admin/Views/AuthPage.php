<?php

namespace Api\Admin\Views;


class AuthPage implements IRenderable
{
    public static function render()
    {
        require "Templates/AuthTemplate.php";
    }

}