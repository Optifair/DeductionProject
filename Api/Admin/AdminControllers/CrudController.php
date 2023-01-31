<?php

namespace Api\Admin\AdminControllers;

use Api\Models\Repositories\CommentRepository;
use Api\Models\Repositories\MarkRepository;
use Api\Models\Repositories\PostRepository;
use Api\Models\Repositories\UserRepository;

class CrudController
{
    public function deleteUser()
    {
        $id = $_GET['id'];

        UserRepository::deleteUser($id);
        header('Location: \admin\users');
    }

    public function deletePost()
    {
        $id = $_GET['id'];

        PostRepository::deletePost($id);
        header('Location: \admin\posts');
    }

    public function deleteComment()
    {
        $id = $_GET['id'];

        CommentRepository::deleteComment($id);
        header('Location: \admin\comments');
    }

    public function deleteMark()
    {
        $id = $_GET['id'];

        MarkRepository::deleteMark($id);
        header('Location: \admin\marks');
    }

    public function editUser()
    {
        $keyLogin = $_POST['keyLogin'];
        UserRepository::updateName($keyLogin, $_POST['name']);
        UserRepository::updateSalt($keyLogin, $_POST['salt']);
        UserRepository::updatePass($keyLogin, $_POST['password']);
        UserRepository::updateUserCookie($keyLogin, $_POST['cookie']);
        UserRepository::updateAvatar($keyLogin, $_POST['avatar']);
        UserRepository::updateRating($keyLogin, $_POST['rating']);
        UserRepository::updatePass_reset_code($keyLogin, $_POST['pass_reset_code']);
        UserRepository::updateIsAdmin($keyLogin, $_POST['isAdmin']);
        UserRepository::updateLogin($keyLogin, $_POST['login']);

        header('Location: \admin\users');
    }

    public function editMark()
    {
        $mark_id = $_POST['mark_id'];
        MarkRepository::updateUser_id($mark_id, $_POST['user_id']);
        MarkRepository::updatePost_id($mark_id, $_POST['post_id']);
        MarkRepository::updateDate($mark_id, $_POST['mark_date']);


        header('Location: \admin\marks');
    }

    public function editPost()
    {
        $id = $_POST['id'];
        PostRepository::updateUser_id($id, $_POST['user_id']);
        PostRepository::updateTitle($id, $_POST['title']);
        PostRepository::updateContent($id, $_POST['content']);
        PostRepository::updateImage($id, $_POST['image']);
        PostRepository::updateDate($id, $_POST['date']);

        
        header('Location: \admin\posts');
    }

    public function editComment()
    {
        $id = $_POST['id'];
        CommentRepository::updateUser_id($id, $_POST['user_id']);
        CommentRepository::updatePost_id($id, $_POST['post_id']);
        CommentRepository::updateContent($id, $_POST['content']);
        CommentRepository::updateLiked($id, $_POST['liked']);
        CommentRepository::updateDate($id, $_POST['date']);


        header('Location: \admin\comments');
    }
}
