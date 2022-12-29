<?php

namespace Api\Services;

class MailSender
{
    public static function sendMail($to, $link)
    {
        $subject = 'Reset Password';
        $message = "Hello, you have requested a password reset on your account at deductionproject. 
        Follow the link below if you want to do this. If you have not requested a password reset, 
        do not follow the link below." . "\r\n" . $link;

        $headers = 'From:deductionproject@mail.ru' . "\r\n" .
            'Reply-To:deductionproject@mail.ru' . "\r\n" .
            'X-Mailer:PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }
}
