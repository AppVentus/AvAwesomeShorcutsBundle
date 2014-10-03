<?php

namespace AppVentus\Awesome\ShortcutsBundle\Service;

use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This service provides functions of the shortcut bundle.
 *
 * ref: av.shortcuts
 */
class ShortcutService
{
    protected $mailerSpool = null;
    protected $mailer = null;
    protected $session = null;
    protected $router = null;
    protected $alertParameters = null;

    /**
     * Constructor
     * @param unknown $mailerSpool
     * @param unknown $mailer
     * @param Session $session
     * @param Router  $router
     * @param array   $alertParameters
     */
    public function __construct($mailerSpool, $mailer, $session, Router $router, $alertParameters)
    {
        $this->mailerSpool = $mailerSpool;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->router = $router;
        $this->alertParameters = $alertParameters;
    }

    /**
     * Create a swift mail and queue it
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $body
     * @param string $contentType
     * @param string $replyTo
     */
    public function createAndQueueMail($subject, $from, $to, $body, $contentType = null, $replyTo = null)
    {
        $mailerSpool = $this->mailerSpool;

        //the mailer spool might not have been installed
        if ($mailerSpool === null) {
            throw \Exception('The mailer spool service is null, please check that the first argument of av.shorcuts exists. You might have not installed white_october.swiftmailer.');
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType);

        //add the reply to
        if ($replyTo != null) {
            $message->setReplyTo($replyTo);
        }

        //queue the message
        $mailerSpool->queueMessage($message);
    }

    /**
     * Create and mail and send it now
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $body
     * @param string $contentType
     * @param string $replyTo
     */
    public function createAndSendMail($subject, $from, $to, $body, $contentType = null, $replyTo = null)
    {
        $mailer = $this->mailer;

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType);

        //add the reply to
        if ($replyTo != null) {
            $message->setReplyTo($replyTo);
        }

        //send the message
        $mailer->send($message);
    }

    /**
     * Generate a random password
     *
     * @return string
     */
    public function randomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    /**
     * Get the session property
     * @param string $name
     * @param string $default
     *
     * @return string
     */
    public function getSession($name, $default = null)
    {
        $session = $this->session;

        return $session->get($name, $default);
    }

    /**
     * Set the session
     *
     * @param string $name
     * @param string $value
     */
    public function setSession($name, $value)
    {
        $session = $this->session;
        $session->set($name, $value);
    }

    /**
     * Generate the url
     *
     * @param string                $route
     * @param array                 $parameters
     * @param UrlGeneratorInterface $referenceType
     *
     * @return string The url
     */
    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $router = $this->router;

        $url = $router->generate($route, $parameters, $referenceType);

        return $url;
    }

    /**
     * Alert message to flashbag.
     * @param string $content           Captain Obvious ? We have to setup a content
     * @param string $type              Success or Error ? Warning or Info ? You choose !
     * @param string $layout            How the noty should be rendered ? topRight ? top Left ? bottom ? Go to doc to know more
     * @param string $translationDomain The translation domain to use to translate strings
     */
    public function alert($content, $type = 'success', $layout = null, $translationDomain = null)
    {
        $engine = $this->alertParameters['engine'];
        $layout = $layout === null ? $this->alertParameters['layout'] : $layout;
        $translationDomain = $translationDomain === null ? $this->alertParameters['translationDomain'] : $translationDomain;

        $this->setFlash(
            $engine,
            array(
                'type'              => $type,
                'layout'            => $layout,
                'body'              => $content,
                'translationDomain' => $translationDomain
            )
        );
    }

    /**
     * congrat user through flashbag : all happened successfully
     * @param string $content           The main message
     * @param string $layout            How the alert should be rendered ? topRight ? top Left ? bottom ? Go to doc to know more
     * @param string $translationDomain The translation domain to use to translate strings
     */
    public function congrat($content, $layout = null, $translationDomain = null)
    {
        $this->alert($content, 'success', $layout, $translationDomain);
    }

    /**
     * Warn user through flashbag: something requires attention
     * @param string $content           The main message
     * @param string $layout            How the alert should be rendered ? topRight ? top Left ? bottom ? Go to doc to know more
     * @param string $translationDomain The translation domain to use to translate strings
     */
    public function warn($content, $layout = null, $translationDomain = null)
    {
        $this->alert($content, 'warning', $layout, $translationDomain);
    }

    /**
     * Inform user through flashbag: someething have to be said
     * @param string $content           The main message
     * @param string $layout            How the alert should be rendered ? topRight ? top Left ? bottom ? Go to doc to know more
     * @param string $translationDomain The translation domain to use to translate strings
     */
    public function inform($content, $layout = null, $translationDomain = null)
    {
        $this->alert($content, 'info', $layout, $translationDomain);
    }

    /**
     * Scold user through flashbag: someething went wrong
     * @param string $content           The main message
     * @param string $layout            How the alert should be rendered ? topRight ? top Left ? bottom ? Go to doc to know more
     * @param string $translationDomain The translation domain to use to translate strings
     */
    public function scold($content, $layout = null, $translationDomain = null)
    {
        $this->alert($content, 'error', $layout, $translationDomain);
    }

    /**
     * Add thing to flashbag.
     * @param string $name
     * @param string $value
     */
    public function setFlash($name, $value)
    {
        $this->session->getFlashBag()->add($name, $value);
    }
}
