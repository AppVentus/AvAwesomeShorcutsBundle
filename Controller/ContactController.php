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
        $form = $this->get('form.factory')->create(new ContactType());
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                
                    $body = $this->renderView("AvAwesomeShorcutsBundle:Contact:email.html.twig", array(
                        'name' => $data->name,
                        'email' => $data->email,
                        'message' => $data->message,
                    ));
                    if($data->type == "commercial"){
                        $to = 'contact@agissonspourlemploi.fr';
                    }else{
                        $to = 'support@agissonspourlemploi.fr';
                    }
                $this->createAndSendMail("Agissons pour l'emploi : Nouveau message d'un utilisateur",
                 'noreply@agissonspourlemploi.fr', 
                 $to, 
                 $body, 
                 'text/html', $data->email);

                // $this->get('instant_mailer')->send($message);
                $this->noty("Votre message à été envoyé");
                if($this->getRequest()->isXmlHttpRequest()){
                    return new Response("<div class=\"alert alert-success\" style=\"text-align:center;\"><strong>Votre message a bien été envoyé</strong><br>Nous vous répondrons dans les plus brefs délais.<br><img src=\"/bundles/jobboard/images/apply/congrats.png\" alt=\"success\"/></div>");
                }else{
                    return $this->redirectReferer();
                }
            }
        }

        return array(
                'form' => $form->createView()
            );
    }
}