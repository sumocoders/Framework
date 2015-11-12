<?php

namespace SumoCoders\FrameworkCoreBundle\Mail;

class MessageFactory
{
    /**
     * @var array
     */
    protected $sender = array();

    /**
     * @var array
     */
    protected $replyTo = array();

    /**
     * @var array
     */
    protected $to = array();

    /**
     * Set the default sender
     *
     * @param string      $email
     * @param string|null $name
     */
    public function setDefaultSender($email, $name = null)
    {
        if ($name !== null) {
            $this->sender = array($email => $name);
        } else {
            $this->sender = $email;
        }
    }

    /**
     * Set the default reply-to
     *
     * @param string      $email
     * @param string|null $name
     */
    public function setDefaultReplyTo($email, $name = null)
    {
        if ($name !== null) {
            $this->replyTo = array($email => $name);
        } else {
            $this->replyTo = $email;
        }
    }

    /**
     * Set the default to
     *
     * @param string      $email
     * @param string|null $name
     */
    public function setDefaultTo($email, $name = null)
    {
        if ($name !== null) {
            $this->to = array($email => $name);
        } else {
            $this->to = $email;
        }
    }

    /**
     * @return Message
     */
    public function createMessage()
    {
        $message = new Message();

        if (!empty($this->sender)) {
            $message->setFrom($this->sender);
        }

        if (!empty($this->replyTo)) {
            $message->setReplyTo($this->replyTo);
        }

        if (!empty($this->to)) {
            $message->setTo($this->to);
        }

        return $message;
    }
}
