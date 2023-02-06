<?php

namespace Api\Admin\AdminControllers;

use Api\Models\Repositories\CommentRepository;
use Api\Models\Repositories\MarkRepository;
use Api\Models\Repositories\PostRepository;
use Api\Models\Repositories\UserRepository;
use Api\Services\MailSender;

class CrudController
{
    public function deletePost()
    {
        $id = $_POST['id'];

        $owner_id = $_POST["user_id"];
        $message = $_POST["message"];
        $user = UserRepository::findUserById($owner_id);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "delete", "post", $message);

        PostRepository::deletePost($id);
        header('Location: \admin\posts');
    }

    public function deleteComment()
    {
        $id = $_POST['id'];

        $owner_id = $_POST["user_id"];
        $message = $_POST["message"];
        $user = UserRepository::findUserById($owner_id);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "delete", "comment", $message);

        CommentRepository::deleteComment($id);
        header('Location: \admin\comments');
    }

    public function deleteMark()
    {
        $id = $_POST['mark_id'];
        $owner_id = $_POST["user_id"];
        $message = $_POST["message"];
        $user = UserRepository::findUserById($owner_id);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "delete", "mark", $message);
        MarkRepository::deleteMark($id);
        header('Location: \admin\marks');
    }

    public function editUser()
    {
        $keyLogin = $_POST['keyLogin'];

        $message = $_POST["message"];
        $user = UserRepository::findUserByLogin($keyLogin);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "edit", "user data", $message);

        UserRepository::updateName($keyLogin, $_POST['name']);
        UserRepository::updateSalt($keyLogin, $_POST['salt']);
        UserRepository::updatePass($keyLogin, $_POST['password']);
        UserRepository::updateUserCookie($keyLogin, $_POST['cookie']);
        UserRepository::updateAvatar($keyLogin, $_POST['avatar']);
        UserRepository::updateRating($keyLogin, $_POST['rating']);
        UserRepository::updatePass_reset_code($keyLogin, $_POST['pass_reset_code']);
        $editor = UserRepository::findUserByLogin($_COOKIE['login']);
        if ($editor['is_admin']) {
            UserRepository::updateIs_editor($keyLogin, $_POST['is_editor']);
            echo 1;
        }
        UserRepository::updateLogin($keyLogin, $_POST['login']);

        header('Location: \admin\users');
    }

    public function editMark()
    {
        $mark_id = $_POST['mark_id'];

        $owner_id = $_POST["user_id"];
        $message = $_POST["message"];
        $user = UserRepository::findUserById($owner_id);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "edit", "mark", $message);

        MarkRepository::updateUser_id($mark_id, $_POST['user_id']);
        MarkRepository::updatePost_id($mark_id, $_POST['post_id']);
        MarkRepository::updateDate($mark_id, $_POST['mark_date']);


        header('Location: \admin\marks');
    }

    public function editPost()
    {
        $id = $_POST['id'];

        $owner_id = $_POST["user_id"];
        $message = $_POST["message"];
        $user = UserRepository::findUserById($owner_id);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "edit", "post", $message);

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

        $owner_id = $_POST["user_id"];
        $message = $_POST["message"];
        $user = UserRepository::findUserById($owner_id);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "edit", "comment", $message);

        CommentRepository::updateUser_id($id, $_POST['user_id']);
        CommentRepository::updatePost_id($id, $_POST['post_id']);
        CommentRepository::updateContent($id, $_POST['content']);
        CommentRepository::updateLiked($id, $_POST['liked']);
        CommentRepository::updateDate($id, $_POST['date']);


        header('Location: \admin\comments');
    }

    public function unbanUser()
    {
        $id = $_POST['id'];
        $user = UserRepository::findUserById($id);
        if (!$user['is_admin']) {
            UserRepository::unbanUser($id);
        }
        header('Location: \admin\users');
    }

    public function banUser()
    {
        $id = $_POST['id'];
        $ban_time = $_POST['ban_time'];
        $message = $_POST["message"];
        $user = UserRepository::findUserById($id);
        if (!$user['is_admin']) {
            UserRepository::banUser($id, $ban_time);
            MailSender::createUserNotificationMail($user['login'], $user['name'], "ban", "user on $ban_time hours", $message);
        }
        header('Location: \admin\users');
    }

    public function deleteUser()
    {
        $id = $_POST['id'];

        $owner_id = $id;
        $message = $_POST["message"];
        $user = UserRepository::findUserById($owner_id);
        MailSender::createUserNotificationMail($user['login'], $user['name'], "delete", "user", $message);

        UserRepository::deleteUser($id);
        header('Location: \admin\users');
    }
}
