<?php

namespace Api\Controllers;

use Services\DB;

class PostsController
{
    public $conn = null;

    public function __construct()
    {
        $this->conn = (new DB())->database();
    }

    public function getPosts()
    {
        try
        {
            // Getting data.
            $url = "https://jsonplaceholder.typicode.com/posts";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_ENCODING, 0);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);  
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));


            // Getting Images.

            $url = "https://jsonplaceholder.typicode.com/photos";

            $chImg = curl_init();
            curl_setopt($chImg, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($chImg, CURLOPT_HEADER, 0);
            curl_setopt($chImg, CURLOPT_ENCODING, 0);
            curl_setopt($chImg, CURLOPT_MAXREDIRS, 10);
            curl_setopt($chImg, CURLOPT_TIMEOUT, 30);
            curl_setopt($chImg, CURLOPT_CUSTOMREQUEST , "GET");
            curl_setopt($chImg, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($chImg, CURLOPT_URL, $url);
            curl_setopt($chImg, CURLOPT_FOLLOWLOCATION, TRUE);  
            curl_setopt($chImg, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));


            $responseData = json_decode(curl_exec($ch), true);
            $responseImages = json_decode(curl_exec($chImg), true);
            $newArray = [];

            // Combining Data.

            foreach($responseData as $resData)
            {
                if(isset($responseImages[$resData['id']]))
                {
                    $resData['image'] = $responseImages[$resData['id']]["url"];
                }

                $newArray[] = $resData; 
            }

            $this->savePostsToDatabase($newArray);

        }
        catch(\Exception $e)
        {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function savePostsToDatabase($posts = null)
    {
        echo "adsf";
        foreach($posts as $post)
        {
            $sql = "INSERT INTO posts(`user_id`, `title`, `content`, `image`)
                VALUES (
                    '".$post['userId']."',
                    '".$post['title']."',
                    '".$post['body']."',
                    '".$post['image']."')";

            if(mysqli_query($this->conn, $sql))
            {
                echo "New record created successfully";
            }
            else
            {
                echo "Error: ". $sql ."<br/>". mysqli_error($this->conn);
            }
        }

        mysqli_close($this->conn);
    }

    public function getPostsFromDatabase()
    {
        try
        {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");


            //echo "<pre>";
            $perPage = $_GET['limit'] ?? 5;
            $pageNumber = $_GET['offset'] ?? 0;
            $postsArray = [];


            $sql = "SELECT * FROM posts";
            $totalPosts = mysqli_num_rows(mysqli_query($this->conn, $sql));

            $sql = "SELECT * FROM posts ORDER BY id LIMIT $perPage OFFSET $pageNumber";
            $response = mysqli_query($this->conn, $sql);

            if($response)
            {
                while($row = mysqli_fetch_assoc($response))
                {
                    $postsArray['posts'][] = $row;
                }
            }
            else
            {
                echo "Error ". $sql. "<br/>" . mysqli_error($this->conn);
            }

            $postsArray['count'] = $totalPosts;

            mysqli_close($this->conn);

            echo json_encode($postsArray, JSON_PRETTY_PRINT);
            //return json_encode($postsArray, JSON_PRETTY_PRINT);
           
        }
        catch(\Exception $e)
        {
            var_dump($e->getMessage());
            exit;
        }
    }


    public function getSearchResults()
    {
        try
        {
            $this->getHeaders();


            $postsArray = [];
            $keyword = $_GET['keyword'] ?? null;

            if($keyword)
            {
                $sql = "SELECT id,title FROM posts WHERE title LIKE '%$keyword%' LIMIT 5";

                $response = mysqli_query($this->conn, $sql);

                if($response)
                {
                    while($row = mysqli_fetch_assoc($response))
                    {
                        $postsArray['posts'][] = $row;
                    }
                }
            }

            echo json_encode($postsArray, JSON_PRETTY_PRINT);

        }
        catch(\Exception $e)
        {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function getHeaders()
    {
          // Allow from any origin
        
          header("Access-Control-Allow-Origin: *");
          header('Access-Control-Allow-Credentials: true');
          header('Access-Control-Max-Age: 86400');    // cache for 1 day
          header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
          
          
          // Access-Control headers are received during OPTIONS requests
          if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
              
              if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                  // may also be using PUT, PATCH, HEAD etc
                  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
              
              if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                  header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
          
              exit(0);
          }
    }


    public function getCurrentTopic()
    {
        try
        {
            $this->getHeaders();
            $currentTopic = null;
            $id =  $_GET['id'] ?? null;

            if($id)
            {
                $sql = "SELECT * FROM posts WHERE id='".$id."'";

                $response = mysqli_query($this->conn, $sql);

                if($response)
                {
                    while($row = mysqli_fetch_assoc($response))
                    {
                        $currentTopic = $row;
                    }
                }
            }

            echo json_encode($currentTopic, JSON_PRETTY_PRINT);

        }
        catch(\Exception $e)
        {
            var_dump($e->getMessage);
            exit;
        }
    }

}