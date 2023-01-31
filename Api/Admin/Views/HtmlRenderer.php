<?php

namespace Api\Admin\Views;


class HtmlRenderer
{
    public static function renderTemplates($templates)
    {
        foreach ($templates as &$template) {
            require "Templates/" . $template . ".php";
        }
    }
}