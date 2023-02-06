<?php

namespace Api\Services;

use Api\Services\PHPMailer\Exception;
use Api\Services\PHPMailer\PHPMailer;

class MailSender
{
    public static function createPasswordResetMail($email, $name, $link)
    {
        $title = "Password reset on deductionproject";
        $body = "Hello, <b>$name</b><br>   
                You requested a password reset at deductionproject. 
                Click the link below to change your password.<br>
                $link";
        self::sendMail($email, $title, $body);
    }

    private static function sendMail($email, $title, $body)
    {

        $mail = new PHPMailer();
        try {
            $mail->isSMTP();
            $mail->CharSet = "UTF-8";
            $mail->SMTPAuth = true;
            $mail->Debugoutput = function ($str) {
                $GLOBALS['status'][] = $str;
            };

            $mail->Subject = 'Password reset';
            $mail->Host = 'smtp.mail.ru';
            $mail->Username = 'deductionproject';
            $mail->Password = 'ZzgiHurRby7vQJyUK8bD';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('deductionproject@mail.ru', 'deductionproject');


            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $body;

            if ($mail->send()) {
                $result = "success";
            } else {
                $result = "error";
            }

        } catch (Exception $e) {
            $result = "error";
            $status = "Error status: {$mail->ErrorInfo}";
        }
    }

    public static function createUserNotificationMail($email, $name, $action, $item, $message)
    {
        $title = "User notification on deductionproject";
        $body = "Hello, <b>$name</b><br>   
                The administrators of our site have decided to 
                take measures about your actions on the site.
                We have $action your $item.<br>";
        if ($message != "") {
            $body = $body . "<br>This is a message from our admin:<br>
            $message";
        }
        self::sendMail($email, $title, $body);
    }
}
