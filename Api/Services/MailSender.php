<?php

namespace Api\Services;

use Api\Services\PHPMailer\Exception;
use Api\Services\PHPMailer\PHPMailer;

class MailSender
{
    public static function sendMail($email, $name, $link)
    {
        $title = "Password reset on deductionproject";
        $body = "Hello, <b>$name</b><br>   
                You requested a password reset at deductionproject. 
                Click the link below to change your password.<br>
                $link";
        $mail = new PHPMailer();
        try {
            $mail->isSMTP();
            $mail->CharSet = "UTF-8";
            $mail->SMTPAuth = true;
            $mail->Debugoutput = function ($str) {
                $GLOBALS['status'][] = $str;
            };

            $mail->Subject = 'Password reset';
            $mail->Host = 'smtp.m.ru';
            $mail->Username = 'user';
            $mail->Password = 'pass';
            $mail->SMTPSecure = '';
            $mail->Port = 1;
            $mail->setFrom('mail', 'name');


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
}
