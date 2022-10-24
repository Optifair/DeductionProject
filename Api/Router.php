<?php

namespace Api;

use Api\Controllers;
use Services\DB;

class Router
{
    private static function routing($current_link, $urls)
    {
        try {
            foreach ($urls as $index => $url) {
                if ($index != $current_link) {
                    continue;
                }

                $routeElement = explode('@', $url[0]);
                $className = $routeElement[0];
                $function =  $routeElement[1];

                $class = "Api\Controllers\\$className";
                $object = new $class();
                $object->$function();
            }
        } catch(\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public static function routes($current_link)
    {
        if (strpos($current_link, '?') !== false) {
            $current_link = explode('?', $current_link)[0];
        }

        $urls = [
            '/api/posts' => ['PostsController@getPostsFromDatabase'],
            '/api/searchResult' => ['PostsController@getSearchResults'],
            '/api/getCurrentPost' => ['PostsController@getCurrentPost'],
        ];

        $availableRoutes = array_keys($urls);

        if (!in_array($current_link, $availableRoutes)) {
            header('HTTP/1.0 404 Not found');
            exit;
        }

        Router::routing($current_link, $urls);
    }
}
