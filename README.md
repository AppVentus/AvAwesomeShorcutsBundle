AvAwesomeShortcutsBundle
=======================

## Installation

Cette procédure décrit l'installation du projet pour une utilisation dans une machine virtuelle vagrant.

### Récupération du bundle
#### Composer

Ajouter les lignes suivantes dans votre composer.json :
    {
        "require": {
            "appventus/shortcuts-bundle": "dev-master"
        }
    }

Puis, executer la commande suivante :
    php composer.phar update

### Activer le bundle

Dans votre AppKernel.php ajouter les lignes suivantes :

    <?php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new AppVentus\Awesome\ShortcutsBundle\AvAwesomeShortcutsBundle(),
        );
    }

## Configuration
### Twig

Dans le fichier config.yml ajouter les lignes suivantes :
    # Twig Configuration
    twig:
        form:
            resources:
                - 'AvAwesomeShortcutsBundle::fields.html.twig'

Dans votre fichier de layout charger les fichiers suivants :
    '@AvAwesomeShortcutsBundle/Resources/public/css/datepicker.css'
    '@AvAwesomeShortcutsBundle/Resources/public/js/bootstrap-datepicker.js'


# Utilisation des raccourcis

Un service permet d'utiliser des fonctions utilisées dans de nombreuses applications.

Exemple dans un controller:

	$shorcutService = $this->get('av.shorcuts');
	$shorcutService->getSession(...
	$shorcutService->setSession(...
	$shorcutService->createAndQueueMail(...
	$shorcutService->createAndSendMail(...
Voir le fichier ShortcutService pour la liste complète des raccourcis et de leurs paramêtres.

# Le FormErrorService
Il est fréquent que l'on soumette des formulaires en ajax.

Dans ce cas on souhaite renvoyer les erreurs du formulaires en une chaîne de caractères.

Le FormErrorService permet de transformer les erreurs d'un formulaire (ainsi que ses sous-formulaires) en un string.

Exemple dans un controller:

		$form = ...  //some form

		if ($form->isValid()) {
			...
		} else {
			$formErrorService = $this->get('av.form_error_service');
			$errorsAsString = $formErrorService->getRecursiveReadableErrors($form);
		}

# Intégration avec AvAlertifyBundle

Ce bundle apporte son lot de raccourcis pour le bundle AvAlertify afin d'uniformiser toutes les alertes de votre application.
Au lieu d'utiliser :


    $this->get('session')->getFlashBag()->add('noty', array(
            'type'              => $type,
            'layout'            => $layout,
            'body'              => $content,
            'translationDomain' => $translationDomain
        )
    );

ou pire

    $this->session->getFlashBag()->add('success', 'Congratulations !');

On pourra désormais utiliser les racourcis suivants depuis le service av.shortcuts :

    $this->container->get('av.shortcuts')->congrat($content, $layout, $translationDomain);

ou dans tout controller héritant de notre AwesomeController :

    $this->congrat('Congratulations !');         // Success
    $this->warn('Careful, this is important !'); // Warning
    $this->inform('Did you know ?');             // Information
    $this->scold('Oups something went wrong !'); // Error


