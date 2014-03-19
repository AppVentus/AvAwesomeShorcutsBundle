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
