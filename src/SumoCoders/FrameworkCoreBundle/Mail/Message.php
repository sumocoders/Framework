<?php

namespace SumoCoders\FrameworkCoreBundle\Mail;

class Message extends \Swift_Message
{
    /**
     * {@inheritdoc}
     */
    public static function newInstance($subject = null, $body = null, $contentType = null, $charset = null)
    {
        return new static($subject, $body, $contentType, $charset);
    }
}
