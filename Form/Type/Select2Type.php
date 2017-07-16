<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use AppVentus\Awesome\ShortcutsBundle\Form\Transformer\ArrayToStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Select2Type extends AbstractType
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ArrayToStringTransformer($this->em, $options['class'], $options['property']);
        $builder->addViewTransformer($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['enable_creation'] = $options['enable_creation'];
        $view->vars['tags'] = [];
        foreach ($view->vars['choices'] as $choice) {
            $view->vars['tags'][$choice->data->getId()] = $choice->data->__toString();
        }

        if ($view->vars['multiple']) {
            $view->vars['tag_values'] = '';
            foreach ($view->vars['value'] as $value) {
                $view->vars['tag_values'] .= $value;

                if ($value !== end($view->vars['value'])) {
                    $view->vars['tag_values'] .= ',';
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'select2';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'enable_creation' => true,
        ]);
    }
}
