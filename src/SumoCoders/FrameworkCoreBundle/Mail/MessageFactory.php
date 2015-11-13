<?php

namespace SumoCoders\FrameworkCoreBundle\Mail;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

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
     * @var EngineInterface
     */
    protected $template;

    /**
     * @var CssToInlineStyles
     */
    protected $cssToInlineStyles;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var string
     */
    protected $cssPath;

    /**
     * MessageFactory constructor.
     *
     * @param EngineInterface $template
     */
    public function __construct(EngineInterface $template, $templatePath, $cssPath)
    {
        $this->template = $template;
        $this->templatePath = $templatePath;
        $this->cssPath = $cssPath;
    }

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
     * Create a message
     *
     * @param string|null $subject
     * @param string|null $html
     * @param string|null $alternative
     * @return \Swift_Message
     */
    protected function createMessage($subject = null, $html = null, $alternative = null)
    {
        $message = $this->createDefaultMessage();

        if ($subject != '') {
            $message->setSubject($subject);
        }

        if ($html != '' && $alternative != '') {
            $message->setBody(
                $this->wrapInTemplate($html),
                'text/html'
            );
            $message->addPart($alternative, 'text/plain');
        } elseif ($html != '' && $alternative == '') {
            $message->setBody(
                $this->wrapInTemplate($html),
                'text/html'
            );
            $message->addPart(strip_tags($alternative), 'text/plain');
        } elseif ($html == '' && $alternative != '') {
            $message->setBody($alternative, 'text/plain');
        }

        return $message;
    }

    /**
     * Create a HTML message
     *
     * If no alternative is provided it will be generated automatically.
     * This is just an alias for createMessage
     *
     * @param string|null $subject
     * @param string|null $html
     * @param string|null $plainText
     * @return \Swift_Message
     */
    public function createHtmlMessage($subject = null, $html = null, $plainText = null)
    {
        return $this->createMessage($subject, $html, $plainText);
    }

    /**
     * Create a plain text message
     *
     * @param string|null $subject
     * @param string|null $body
     * @return \Swift_Message
     */
    public function createPlainTextMessage($subject = null, $body = null)
    {
        return $this->createMessage($subject, null, $body);
    }

    /**
     * @return \Swift_Message
     */
    public function createDefaultMessage()
    {
        $message = \Swift_Message::newInstance();

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

    /**
     * Wrap the given content in a nice default email template
     *
     * @param string $content
     * @return string
     */
    public function wrapInTemplate($content)
    {
        $css = file_get_contents($this->cssPath);
        $html = $this->template->render(
            $this->templatePath,
            array(
                'content' => $content,
                'css' => $css,
            )
        );

        $cssToInlineStyles = new CssToInlineStyles(
            $html,
            $css
        );

        $content = $cssToInlineStyles->convert();

        return $content;
    }
}
