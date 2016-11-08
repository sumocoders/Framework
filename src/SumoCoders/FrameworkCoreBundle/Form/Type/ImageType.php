<?php

namespace SumoCoders\FrameworkCoreBundle\Form\Type;

use SumoCoders\FrameworkCoreBundle\ValueObject\AbstractImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType as SymfonyFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    /** @var SymfonyFileType */
    private $fileField;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($options) {
                    $event->getForm()->add(
                        'file',
                        SymfonyFileType::class,
                        [
                            'label_render' => false,
                            'horizontal_label_offset_class' => '',
                            'horizontal_input_wrapper_class' => '',
                            'required' => $event->getData() === null && $options['required'],
                        ]
                    );
                    $this->fileField = $event->getForm()->get('file');
                }
            )
            ->addModelTransformer(
                new CallbackTransformer(
                    function (AbstractImage $image = null) {
                        return $image;
                    },
                    function (AbstractImage $image = null) use ($options) {
                        if ($image === null) {
                            $imageClass = $options['image_class'];

                            return $imageClass::fromUploadedFile($this->fileField->getData());
                        }

                        // return a clone to make sure that doctrine will do the lifecycle callbacks
                        return clone $image;
                    }
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(
            ['image_class']
        );

        $resolver->setDefaults(
            [
                'data_class' => AbstractImage::class,
                'compound' => true,
                'preview_class' => '',
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'image';
    }

    public function getParent()
    {
        if (!$this instanceof self) {
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

        $view->vars['required'] = $form->getData() === null && $options['required'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
