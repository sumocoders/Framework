<?php

namespace SumoCoders\FrameworkCoreBundle\Form\Type;

use SumoCoders\FrameworkCoreBundle\ValueObject\AbstractImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

    /** @var CheckboxType|null */
    private $removeField;

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

                    if ($options['show_remove_current_upload']) {
                        $this->removeField = $event->getForm()->add(
                            'remove',
                            CheckboxType::class,
                            [
                                'required' => false,
                                'label' => $options['remove_current_upload_label'],
                                'mapped' => false,
                            ]
                        );

                        $this->removeField = $event->getForm()->get('remove');
                    }
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
                            if ($this->removeField !== null && $this->removeField->getData()) {
                                return $imageClass::fromUploadedFile();
                            }

                            return $imageClass::fromUploadedFile($this->fileField->getData());
                        }

                        if ($this->removeField !== null && $this->removeField->getData()) {
                            $image->markForDeletion();

                            return clone $image;
                        }

                        $image->setFile($this->fileField->getData());

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
            [
                'image_class',
                'show_current_upload',
                'show_remove_current_upload',
                'remove_current_upload_label',
            ]
        );

        $resolver->setDefaults(
            [
                'data_class' => AbstractImage::class,
                'compound' => true,
                'preview_class' => '',
                'show_current_upload' => true,
                'show_remove_current_upload' => true,
                'remove_current_upload_label' => 'forms.labels.removeCurrentUpload',
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

    /**
     * @return string
     */
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
        $view->vars['show_current_upload'] = $options['show_current_upload'];
        $view->vars['show_remove_current_upload'] = $options['show_remove_current_upload'] && $form->getData() !== null
                                                    && !empty($form->getData()->getFileName());
        // if you need to have an image you shouldn't be allowed to remove it
        if ($options['required']) {
            $view->vars['show_remove_current_upload'] = false;
        }
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
