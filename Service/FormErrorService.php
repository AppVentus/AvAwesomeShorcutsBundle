<?php

namespace AppVentus\Awesome\ShortcutsBundle\Service;

use JMS\TranslationBundle\Annotation\Ignore;

/**
 * A service that give errors from a form as a string
 *
 * service: av.form_error_service
 *
 */
class FormErrorService
{
    protected $translator = null;

    /**
     * The constructor
     *
     * @param Translator $translator The translator service
     */
    public function __construct($translator, $debug)
    {
        $this->translator = $translator;
        $this->debug = $debug;
    }

    /**
     * Returns a string representation of all form errors (including children errors).
     *
     * This method should only be used in ajax calls.
     *
     * @param Form    $form         The form to parse
     * @param boolean $withChildren Do we parse the embedded forms
     *
     * @return string A string representation of all errors
     */
    public function getRecursiveReadableErrors($form, $withChildren = true)
    {
        $errors = '';

        //the errors of the fields
        foreach ($form->getErrors() as $error) {
            //the view contains the label identifier
            $view = $form->createView();
            $labelId = $view->vars['label'];

            //get the translated label
            if ($labelId !== null) {
                $label = $this->translator->trans(/** @Ignore */$labelId).': ';
            } else {
                $label = '';
            }

            //in case of dev mode, we display the item that is a problem
            $debug = $this->debug;
            if ($debug) {
                $cause = $error->getCause();
                if ($cause !== null) {
                    $causePropertyPath = $cause->getPropertyPath();
                    $errors .= ' '.$causePropertyPath;
                }
            }

            //add the error
            $errors .= $label.$error->getMessage()."\n";
        }

        //do we parse the children
        if ($withChildren) {
            //we parse the children
            foreach ($form->getIterator() as $key => $child) {
                if ($err = $this->getRecursiveReadableErrors($child, $withChildren)) {
                    $errors .= $err;
                }
            }
        }

        return $errors;
    }
}