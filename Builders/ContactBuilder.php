<?php
namespace Users\AppVentus\Awesome\ShortcutsBundle\Builders;

use  AppVentus\Awesome\ShortcutsBundle\Form\ContactType;

class ContactBuilder{
    protected $container;



    public function __construct($container){
        $this->container = $container
    }

    public function createMessage(){

        $form = $this->get('form.factory')->create(new ContactType());
        $request = $this->getRequest();

        if ('POST' === $this->container->get('request')->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                    $body = $this->renderView("AvAwesomeShortcutsBundle:Contact:email.html.twig", array(
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
                if($this->container->get('request')->isXmlHttpRequest()){
                    return new Response("<div class=\"alert alert-success\" style=\"text-align:center;\"><strong>Votre message a bien été envoyé</strong><br>Nous vous répondrons dans les plus brefs délais.<br><img src=\"/bundles/jobboard/images/apply/congrats.png\" alt=\"success\"/></div>");
                }else{
                    return $this->redirectReferer();
                }
            }
        return array(
            'template'=> '',
            'form'=> '',
            'action'=> '',
            );
    }

}
