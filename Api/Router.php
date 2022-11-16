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
            '/api/posts' => ['PostsController@getPostsFromDatabase'],
            '/api/marks' => ['PostsController@getMarks'],
            '/api/searchPosts' => ['PostsController@searchPosts'],
            '/api/getCurrentPost' => ['PostsController@getCurrentPost'],
            '/api/MarkPost' => ['PostsController@MarkPost'],
            '/api/comments' => ['CommentsController@getCommentsOfPost'],
            '/api/registerUser' => ['UsersController@addUser'],
            '/api/authUser' => ['UsersController@authUser'],
            '/api/logout' => ['UsersController@logoutUser'],
            '/api/cookieAuth' => ['UsersController@authUserWithCookie'],
            '/api/getUserData' => ['UsersController@getUserData'],
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
