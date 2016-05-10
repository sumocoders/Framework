<?php

namespace SumoCoders\FrameworkCoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AbstractImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'file',
                'file',
                [
                    'label_render' => false,
                    'horizontal_label_offset_class' => '',
                    'horizontal_input_wrapper_class' => '',
                ]
            )
            ->add(
                'alt',
                'text',
                ['required' => false, 'label' => 'forms.labels.alt']
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'SumoCoders\FrameworkCoreBundle\Entity\AbstractImage',
                'compound' => true,
                'preview_class' => '',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'image';
    }

    public function getParent()
    {
        if (!$this instanceof ImageType) {
            return 'image';
        }

        return 'file';
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!empty($options['preview_class'])) {
            $view->vars['preview_class'] = $options['preview_class'];
        }
    }
}
