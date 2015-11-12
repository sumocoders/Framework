<?php

namespace SumoCoders\FrameworkCoreBundle\Mail;

class MessageFactory
{
    /**
     * @return Message
     */
    public static function createMessage()
    {
        $message = new Message();
        return $message;
    }
}
