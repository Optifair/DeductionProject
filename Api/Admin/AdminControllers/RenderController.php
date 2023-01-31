<?php

namespace Api\Admin\AdminControllers;


use Api\Admin\Views\HtmlRenderer;

class RenderController
{
    static function renderAuthPage()
    {
        $error = $_GET['error'] ?? null;

        if (!AuthAdminController::cookieAuth()) {
            $templates = ['Html', 'AuthTemplate'];
            HtmlRenderer::renderTemplates($templates);
            if ($error) {
                if ($error = 'notFound')
                    HtmlRenderer::renderTemplates(['NotFoundAlert']);
            }
        } else {
            header('Location: \admin');
        }
    }

    static function renderMainPage()
    {
        if (AuthAdminController::cookieAuth()) {
            $templates = ['Html', 'Header', 'AdminInfo'];
            HtmlRenderer::renderTemplates($templates);
        } else {
            header('Location: \admin\authPage?error=notFound');
        }
    }

    static function renderUsersPage()
    {
        if (AuthAdminController::cookieAuth()) {
            $templates = ['Html', 'Header', 'UsersTemplate'];
            HtmlRenderer::renderTemplates($templates);
        } else {
            header('Location: \admin\authPage?error=notFound');
        }
    }

    static function renderPostsPage()
    {
        if (AuthAdminController::cookieAuth()) {
            $templates = ['Html', 'Header', 'PostsTemplate'];
            HtmlRenderer::renderTemplates($templates);
        } else {
            header('Location: \admin\authPage');
        }
    }

    static function renderCommentsPage()
    {
        if (AuthAdminController::cookieAuth()) {
            $templates = ['Html', 'Header', 'CommentsTemplate'];
            HtmlRenderer::renderTemplates($templates);
        } else {
            header('Location: \admin\authPage');
        }
    }

    static function renderMarksPage()
    {
        if (AuthAdminController::cookieAuth()) {
            $templates = ['Html', 'Header', 'MarksTemplate'];
            HtmlRenderer::renderTemplates($templates);
        } else {
            header('Location: \admin\authPage');
        }
    }

}
