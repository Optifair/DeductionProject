<?php

namespace Api;

use Exception;

class Router
{
    public static function routing($current_link)
    {
        if (strpos($current_link, '?') !== false) {
            $current_link = explode('?', $current_link)[0];
        }

        $urls = [
            '/api/posts' => ['PostsController@getPosts'],
            '/api/searchPosts' => ['PostsController@searchPosts'],
            '/api/getCurrentPost' => ['PostsController@getCurrentPost'],
            '/api/addPost' => ['PostsController@addPost'],
            '/api/marks' => ['MarksController@getMarks'],
            '/api/markPost' => ['MarksController@markPost'],
            '/api/comments' => ['CommentsController@getCommentsOfPost'],
            '/api/addComment' => ['CommentsController@addComment'],
            '/api/likeComment' => ['LikesController@likeComment'],
            '/api/registerUser' => ['UsersController@addUser'],
            '/api/authUser' => ['UsersController@authUser'],
            '/api/logout' => ['UsersController@logoutUser'],
            '/api/cookieAuth' => ['UsersController@authUserWithCookie'],
            '/api/getUserData' => ['UsersController@getUserData'],
            '/api/editUserData' => ['UsersController@editUserData'],
            '/api/editPassword' => ['UsersController@editPassword'],
            '/api/sendPasswordResetLink' => ['UsersController@sendPasswordResetLink'],
            '/api/checkPasswordResetKey' => ['UsersController@checkPasswordResetKey'],
        ];

        $admin_urls = [
            '/admin' => ['RenderController@renderMainPage'],
            '/admin/authPage' => ['RenderController@renderAuthPage'],
            '/admin/auth' => ['AuthAdminController@auth'],
            '/admin/logout' => ['AuthAdminController@logout'],
            '/admin/users' => ['RenderController@renderUsersPage'],
            '/admin/posts' => ['RenderController@renderPostsPage'],
            '/admin/comments' => ['RenderController@renderCommentsPage'],
            '/admin/marks' => ['RenderController@renderMarksPage'],
            '/admin/editUser' => ['CrudController@editUser'],
            '/admin/editMark' => ['CrudController@editMark'],
            '/admin/editComment' => ['CrudController@editComment'],
            '/admin/editPost' => ['CrudController@editPost'],
            '/admin/deleteMark' => ['CrudController@deleteMark'],
            '/admin/deleteComment' => ['CrudController@deleteComment'],
            '/admin/deleteUser' => ['CrudController@deleteUser'],
            '/admin/deletePost' => ['CrudController@deletePost'],
        ];

        $availableRoutes = array_keys($urls);
        $availableAdminRoutes = array_keys($admin_urls);


        if (in_array($current_link, $availableRoutes)) {
            Router::tryRoute($current_link, $urls, "Api\Controllers\\");
        } else if (in_array($current_link, $availableAdminRoutes)) {
            Router::tryRoute($current_link, $admin_urls, "Api\Admin\AdminControllers\\");
        } else {
            header('HTTP/1.0 404 Not found');
            exit;
        }


    }

    private static function tryRoute($current_link, $urls, $path_to_controller)
    {
        try {
            foreach ($urls as $index => $url) {
                if ($index != $current_link) {
                    continue;
                }

                $routeElement = explode('@', $url[0]);
                $className = $routeElement[0];
                $function = $routeElement[1];

                $class = $path_to_controller . $className;
                $object = new $class();
                $object->$function();
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
