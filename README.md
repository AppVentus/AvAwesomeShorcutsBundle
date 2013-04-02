AvAwesomeShorcutsBundle
=======================

## Installation

Cette procédure décrit l'installation du projet pour une utilisation dans une machine virtuelle vagrant.

### Récupération du bundle
#### Composer

Ajouter les lignes suivante dans votre composer.json :
    {
        "require": {
            "appventus/shortcuts-bundle": "dev-master"
        }
    }

Puis, executer la commande suivante :
    php composer.phar update

### Activer le bundle

Dans votre AppKernel.php ajouter les lignes suivante :

    <?php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new AppVentus\Awesome\ShortcutsBundle\AvAwesomeShorcutsBundle(),
        );
    }