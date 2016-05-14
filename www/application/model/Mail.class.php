<?php

namespace Devlabs\App;

/**
 * Class Mail
 * @package Devlabs\App
 */
class Mail
{
    public $fromEmail;
    public $toEmail;
    public $subject;
    public $message;

    /**
     * Method for sending an e-mail with the predefined properties
     */
    public function send()
    {
        $GLOBALS['mailgun']->sendMessage(
            getenv('MAILGUN_DOMAIN'),
            array(
                'h:x-mailgun-native-send'   => 'true',
                'from'                      => $this->fromEmail,
                'to'                        => $this->toEmail,
                'subject'                   => $this->subject,
                'html'                      => $this->message
            )
        );
    }
}