<?php

namespace SumoCoders\FrameworkCoreBundle\Form\Type;

use SumoCoders\FrameworkCoreBundle\ValueObject\AbstractFile;
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

class FileType extends AbstractType
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
                    function (AbstractFile $file = null) {
                        return $file;
                    },
                    function (AbstractFile $file = null) use ($options) {
                        if ($file === null) {
                            $fileClass = $options['file_class'];
                            if ($this->removeField !== null && $this->removeField->getData()) {
                                return $fileClass::fromUploadedFile();
                            }

                            return $fileClass::fromUploadedFile($this->fileField->getData());
                        }

                        if ($this->removeField !== null && $this->removeField->getData()) {
                            $file->markForDeletion();

                            return clone $file;
                        }

                        $file->setFile($this->fileField->getData());

                        // return a clone to make sure that doctrine will do the lifecycle callbacks
                        return clone $file;
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
                'file_class',
                'current_upload_label',
                'show_current_upload',
                'show_remove_current_upload',
                'remove_current_upload_label',
            ]
        );
        $resolver->setDefaults(
            [
                'data_class' => AbstractFile::class,
                'current_upload_label' => 'forms.labels.currentUpload',
                'remove_current_upload_label' => 'forms.labels.removeCurrentUpload',
                'compound' => true,
                'show_current_upload' => true,
                'show_remove_current_upload' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sumoFile';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['show_remove_current_upload'] = $options['show_remove_current_upload'] && $form->getData() !== null
                                   && !empty($form->getData()->getFileName());
        // if you need to have a file you shouldn't be allowed to remove it
        if ($options['required']) {
            $view->vars['show_remove_current_upload'] = false;
        }
        $view->vars['show_current_upload'] = $options['show_current_upload'];
        $view->vars['current_upload_label'] = $options['current_upload_label'];
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
