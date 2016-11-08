<?php

namespace SumoCoders\FrameworkCoreBundle\Form\Type;

use SumoCoders\FrameworkCoreBundle\ValueObject\AbstractFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $event->getForm()->add(
                        'file',
                        SymfonyFileType::class,
                        [
                            'label_render' => false,
                            'horizontal_label_offset_class' => '',
                            'horizontal_input_wrapper_class' => '',
                            'required' => $event->getData() === null,
                        ]
                    );
                    $this->fileField = $event->getForm()->get('file');
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

                            return $fileClass::fromUploadedFile($this->fileField->getData());
                        }

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
            ['file_class', 'current_upload_label']
        );
        $resolver->setDefaults(
            [
                'data_class' => AbstractFile::class,
                'current_upload_label' => 'forms.labels.currentUpload',
                'compound' => true,
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
        $view->vars['current_upload_label'] = $options['current_upload_label'];
        if (!empty($options['preview_class'])) {
            $view->vars['preview_class'] = $options['preview_class'];
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
