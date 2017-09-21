<?php

namespace SumoCoders\FrameworkCoreBundle\Form\Extension;

use IntlDateFormatter;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateTimeTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return DateTimeType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'format' => 'd/MM/y H:i',
                'maximum_date' => null,
                'minimum_date' => null,
            )
        );

        $resolver->setAllowedValues('widget', array(
            'single_text',
            'choice',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['maximum_date'] = $options['maximum_date'] ? IntlDateFormatter::formatObject($options['maximum_date'], $options['format']) : null;
        $view->vars['minimum_date'] = $options['minimum_date'] ? IntlDateFormatter::formatObject($options['minimum_date'], $options['format']) : null;
        $view->vars['format'] = $this->convertToJsFormat($options['format']);
        $view->vars['devider'] = (strpos($options['format'], '-') !== false) ? '-' : '/';
    }

    private function convertToJsFormat(string $intlFormat): string
    {
        return str_replace(['y', 'MM', 'd', 'H', 'i'], ['YYYY','MM', 'DD', 'HH', 'mm'], $intlFormat);
    }
}
