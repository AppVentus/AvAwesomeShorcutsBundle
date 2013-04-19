<?php

namespace AppVentus\Awesome\ShortcutsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppVentus\JobBoardBundle\Entity\Mail;
use WhiteOctober\SwiftMailerDBBundle\EmailInterface;

/**
 * @author Leny BERNARD <leny@appventus.com>
 **/
abstract class AwesomeController extends BaseController
{
    protected $tool;

    public function dispatchEvent($eventName, Event $event = null)
    {
        $this->get('event_dispatcher')->dispatch($eventName, $event);
    }

    public function noty($content, $type = "success", $layout = "topRight")
    {
        $this->get('session')->getFlashBag()->add('noty', array('type'=>$type, 'layout'=>$layout ,'body'=>$content));
    }

    public function toastr($content, $type = "success", $layout = "bottom-left")
    {
        $this->get('session')->getFlashBag()->add('toastr', array('type'=>$type, 'layout'=>$layout ,'body'=>$content));
    }

    public function getUser()
    {
        if (null === $token = $this->container->get('security.context')->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    public function checkRoles($roles)
    {
        $userRoles = $this->getUser()->getRoles();
        $findArray = array();
        foreach($roles as $role){
            foreach($userRoles as $userRole){
                if($role == $userRole){
                    return true;
                }
            }
        }
        throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();

    }

    public function isGranted($attributes, $object = null)
    {
        return $this->get('security.context')->isGranted($attributes, $object);
    }

    public function setFlash($name, $message)
    {
        $this->get('session')->setFlash($name, $message);
    }

    public function getSession($name, $default = null)
    {
        return $this->get('session')->get($name, $default);
    }

    public function setSession($name, $value)
    {
        $this->get('session')->set($name, $value);
    }

    public function persistAndFlush($entity)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    public function removeAndFlush($entity)
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }

    public function createAndQueueMail($subject, $from, $to, $body, $contentType = null, $replyTo = null)
    {
        $controller = $this->getRequest()->attributes->get('_controller');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType)
            ;
        if ($replyTo != null) {
            $message->setReplyTo($replyTo);
        }

        $this->get('white_october.swiftmailer_db.spool')->queueMessage($message, $controller);
    }

    public function createAndSendMail($subject, $from, $to, $body, $contentType = null, $replyTo = null, $mailer = 'mailer')
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType)
            ;
        if ($replyTo != null) {
            $message->setReplyTo($replyTo);
        }
        $this->get($mailer)->send($message);
    }

    public function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }

    public function isGrantedOr403($attributes, $object = null, $message = null)
    {
        if ($this->get('security.context')->isGranted($attributes, $object)) {
            return;
        }

        throw $this->createAccessDeniedException($message);
    }

    public function getCurrentUserOr403()
    {
        $user = $this->getUser();

        if (null === $user) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        return $user;
    }

    public function redirectReferer()
    {
        $url = $this->container->get('request')->headers->get('referer');
        if (empty($url)) {
            $url = $this->container->get('router')->generate('home');
        }

        return new RedirectResponse($url);
    }

    public function isReferer($url)
    {
        return $url === $this->container->get('request')->headers->get('referer');
    }

    public function createJsonResponse($data, $status = 200)
    {
        return new Response(
            json_encode($data),
            $status,
            array('content-type' => 'application/json')
        );
    }

    public function isEventActiveOr404($event,$mode="ad_show") {
        return $event->isActive($mode);
    }


    public function findAdBySlugOr404($slug) {
        return $this->findEntityOr404('Ad',array('slug'=>$slug));
    }

    public function findAdOr404($id) {
        return $this->findEntityOr404('Ad',array('id'=>$id));
    }

    public function findEntityOr404($entity, $criteria) {
        if (method_exists($this, 'get'.$entity.'Repository')) {
            $obj = $this->{'get'.$entity.'Repository'}()->findOneBy($criteria);
        } else {
            throw new \BadMethodCallException(
                'Undefined method "get' . $entity . 'Repository". Please ' .
                'make sure both method and entity exist.'
            );
        }

        if (null === $obj) {
            throw $this->createNotFoundException(sprintf(
                '%s with parameter(s) %s couldn\'t be found',
                $entity,
                http_build_query($criteria)
            ));
        }

        return $obj;
    }

    public function createAccessDeniedException($message = 'Access Denied', \Exception $previous = null)
    {
        return new AccessDeniedException($message, $previous);
    }

    public function preExecute()
    {
        $this->tool = $this->get('av.tool');
        $this->urlizer = $this->get('gedmo.urlizer');
    }


    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return $bname.' '.$version.' '.$platform;
        // return array(
        //     'userAgent' => $u_agent,
        //     'name'      => $bname,
        //     'version'   => $version,
        //     'platform'  => $platform,
        //     'pattern'    => $pattern
        // );
    }



}
