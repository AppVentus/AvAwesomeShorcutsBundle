<?php

namespace AppVentus\Awesome\ShortcutsBundle\Controller;

use AppVentus\Awesome\ShortcutsBundle\Form\ContactType;
use AppVentus\CmsBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AwesomeController
{
    /**
     * @Route("/", name="Default_ContactForm")
     * @Template()
     */
    public function formAction()
    {
        $contactParameters = $this->container->getParameter('av_awesome_shortcuts');

        // $template = $contactParameters['template'];
        $from = $contactParameters['contact_form']['from'];
        $to = $contactParameters['contact_form']['to'];
        $subject = $contactParameters['contact_form']['subject'];
        $template = $contactParameters['contact_form']['template'];
        $mailTemplate = $contactParameters['contact_form']['mail_template'];
        $form = $this->get('form.factory')->create(new ContactType());
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $body = $this->renderView($mailTemplate, [
                    'name'    => $data['name'],
                    'email'   => $data['email'],
                    'message' => $data['message'],
                ]);
                $this->createAndSendMail(
                    $subject,
                    $from,
                    $to,
                    $body,
                    'text/html',
                    $data['email']);

                // @todo Use a basic method to alert by using a config
                // $this->toastr("Votre message à été envoyé");
                if ($this->getRequest()->isXmlHttpRequest()) {
                    return new Response('<strong>Merci.</strong><br/>Votre message a bien été envoyé.');
                } else {
                    return $this->redirectReferer();
                }
            }
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->render($template, [
                        'form' => $form->createView(),
            ]);
        } else {
            $this->get('session')->getFlashBag()->add('modal', [
                'button_class' => 'hide',
                'body'         => $this->renderView($template, [
                                    'form' => $form->createView(),
                                    'body' => $contactParameters['contact_form']['modal_body_content'],
                                ]),
                'title'        => $contactParameters['contact_form']['modal_title'],
                ]);

            return $this->redirectReferer();
        }

        return [
                'form' => $form->createView(),
            ];
    }
}
