<?php

namespace AppVentus\Awesome\ShortcutsBundle\Controller;

use AppVentus\Awesome\ShortcutsBundle\Controller\AwesomeController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use AppVentus\CmsBundle\Entity\Message;
use AppVentus\Awesome\ShortcutsBundle\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AwesomeController
{

    /**
    * @Route("/contact", name="Default_ContactForm")
    * @Template()
    */
    public function formAction()
    {

        $contactParameters = $this->container->getParameter('av_awesome_shorcuts');

                // $template = $contactParameters['template'];
                $from = $contactParameters['contact_form']['from'];
                $to = $contactParameters['contact_form']['to'];
                $subject = $contactParameters['contact_form']['subject'];
                $template = $contactParameters['contact_form']['template'];
                $mailTemplate = $contactParameters['contact_form']['mail_template'];
        $form = $this->get('form.factory')->create(new ContactType());
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                    $body = $this->renderView($mailTemplate, array(
                        'name' => $data->name,
                        'email' => $data->email,
                        'message' => $data->message,
                    ));
                $this->createAndSendMail($subject,
                 $from,
                 $to,
                 $body,
                 'text/html', $data->email);

                $this->toastr("Votre message à été envoyé");
                if($this->getRequest()->isXmlHttpRequest()){
                    return new Response("<strong>Votre message a bien été envoyé</strong>");
                }else{
                    return $this->redirectReferer();
                }
            }
        }

                if($this->getRequest()->isXmlHttpRequest()){
                            return $this->render($template, array(
                                        'form' => $form->createView()
                            ));
                }else{
                    $this->get('session')->getFlashBag()->add('modal', array(
                        "button_class"=>"hide",
                        "body"=>$this->renderView($template, array(
                                            'form' => $form->createView()
                                        )),
                        "title"=>"Contactez nous"
                        ));
                    return $this->redirectReferer();
                }

        return array(
                'form' => $form->createView()
            );
    }
}
