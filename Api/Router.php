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
            '/admin' => ['AdminController@AdTest'],
        ];

        $availableRoutes = array_keys($urls);

        if (!in_array($current_link, $availableRoutes)) {
            header('HTTP/1.0 404 Not found');
            exit;
        }

        Router::tryRoute($current_link, $urls);
    }

    private static function tryRoute($current_link, $urls)
    {
        try {
            foreach ($urls as $index => $url) {
                if ($index != $current_link) {
                    continue;
                }

                $routeElement = explode('@', $url[0]);
                $className = $routeElement[0];
                $function = $routeElement[1];

                $class = "Api\Controllers\\$className";
                $object = new $class();
                $object->$function();
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
