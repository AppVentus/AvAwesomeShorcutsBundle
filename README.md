[![AppVentus](https://github.com/AppVentus/AvAlertifyBundle/blob/master/Media/appventus.png)](http://appventus.com)

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/AppVentus/AvAwesomeShorcutsBundle)
[![License](https://img.shields.io/packagist/l/appventus/shortcuts-bundle.svg)](https://packagist.org/packages/appventus/shortcuts-bundle)
[![Version](https://img.shields.io/packagist/v/appventus/shortcuts-bundle.svg)](https://packagist.org/packages/appventus/shortcuts-bundle)
=============

Awesome Shortcuts Bundle
========================

## What is the point ?


This bundle allows you to easily add and use shortcuts for your dev apps.

## Install

This procedure describes the installation of the project for use in a virtual machine vagrant.

### Recovery Bundle
#### Composer

Add the following lines in your composer.json :

```
    {
        "require": {
            "appventus/shortcuts-bundle": "dev-master"
        }
    }
```

Then execute the following command:

```
    php composer.phar update
```

### Bundle activation

In your AppKernel.php add the following lines :

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

Add the following lines in Config.yml :

```
    # Twig Configuration
    twig:
        form:
            resources:
                - 'AvAwesomeShortcutsBundle::fields.html.twig'
```

In your layout file, load the following files :
```
    '@AvAwesomeShortcutsBundle/Resources/public/css/datepicker.css'
    '@AvAwesomeShortcutsBundle/Resources/public/js/bootstrap-datepicker.js'
```

# Using shortcuts

A service allows the use of functions used in many applications.

Eg. in a controller:

	$shorcutService = $this->get('av.shorcuts');
	$shorcutService->getSession(...
	$shorcutService->setSession(...
	$shorcutService->createAndQueueMail(...
	$shorcutService->createAndSendMail(...

View [ShortcutService](https://github.com/talbotseb/AvAwesomeShorcutsBundle/blob/master/Service/ShortcutService.php) file for a complete list of shortcuts and their parametres.

# FormErrorService

As it is common to submit forms by ajax, it is desired to return the error forms of a string

The FormErrorService transforms errors on a form (and its sub-forms) into a string.

Eg. in a controller:

		$form = ...  //some form

		if ($form->isValid()) {
			...
		} else {
			$formErrorService = $this->get('av.form_error_service');
			$errorsAsString = $formErrorService->getRecursiveReadableErrors($form);
		}

# Integration with [AvAlertifyBundle](https://github.com/AppVentus/AvAlertifyBundle)

This bundle brings a lot of shortcuts for AvAlertify bundle to standardize all alerts for your application.

Instead of using:

    $this->get('session')->getFlashBag()->add('noty', array(
            'type'              => $type,
            'layout'            => $layout,
            'body'              => $content,
            'translationDomain' => $translationDomain
        )
    );

or worse

    $this->session->getFlashBag()->add('success', 'Congratulations !');

We can now use the following shortcuts from the av.shortcuts Service :

    $this->container->get('av.shortcuts')->congrat($content, $layout, $translationDomain);

or any of our controller inheriting AwesomeController :

    $this->congrat('Congratulations !');         // Success
    $this->warn('Careful, this is important !'); // Warning
    $this->inform('Did you know ?');             // Information
    $this->scold('Oups something went wrong !'); // Error


