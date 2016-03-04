<?php

namespace SumoCoders\FrameworkCoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

final class DateTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return DateType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'date_type' => 'normal',
                'maximum_date' => null,
                'minimum_date' => null,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['date_type'] = $options['date_type'];

        switch ($options['date_type']) {
            case 'start':
                if (!isset($options['minimum_date'])) {
                    throw new InvalidConfigurationException('minimum_date is missing');
                }
                $view->vars['minimum_date'] = $options['minimum_date'];
                break;
            case 'until':
                if (!isset($options['maximum_date'])) {
                    throw new InvalidConfigurationException('maximum_date is missing');
                }
                $view->vars['maximum_date'] = $options['maximum_date'];
                break;
            case 'range':
                if (!isset($options['minimum_date'])) {
                    throw new InvalidConfigurationException('minimum_date is missing');
                }
                if (!isset($options['maximum_date'])) {
                    throw new InvalidConfigurationException('maximum_date is missing');
                }
                $view->vars['maximum_date'] = $options['maximum_date'];
                $view->vars['minimum_date'] = $options['minimum_date'];
                break;
        }
    }
}
