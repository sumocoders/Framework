<?php

namespace SumoCoders\FrameworkCoreBundle\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DatePickerController extends Controller
{
    /**
     * @Template()
     * @Route("/datepicker")
     */
    public function indexAction()
    {
        $form = $this->createFormBuilder()
            ->add(
                'date_example1',
                'date',
                array(
                    'widget' => 'choice',
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'date_example2',
                'date',
                array(
                    'widget' => 'text',
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'date_example3',
                'date',
                array(
                    'widget' => 'single_text',
                    'required' => false,
                    'data' => new \DateTime('20 june 1985 13:37:00'),
                )
            )
            ->add(
                'date_example4',
                'date',
                array(
                    'widget' => 'single_text',
                    'datepicker' => true,
                    'required' => false,
                    'data' => new \DateTime('20 june 1985 13:37:00'),
                )
            )
            ->add(
                'date_example5',
                'date',
                array(
                    'widget' => 'single_text',
                    'datepicker' => true,
                    'date_type' => 'normal',
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'date_example6',
                'date',
                array(
                    'widget' => 'single_text',
                    'datepicker' => true,
                    'date_type' => 'start',
                    'minimum_date' => new \DateTime('last monday'),
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'date_example7',
                'date',
                array(
                    'widget' => 'single_text',
                    'datepicker' => true,
                    'date_type' => 'until',
                    'maximum_date' => new \DateTime('next friday'),
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'date_example8',
                'date',
                array(
                    'widget' => 'single_text',
                    'datepicker' => true,
                    'date_type' => 'range',
                    'minimum_date' => new \DateTime('last monday'),
                    'maximum_date' => new \DateTime('next friday'),
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'datetime_example1',
                'datetime',
                array(
                    'widget' => 'choice',
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'datetime_example2',
                'datetime',
                array(
                    'widget' => 'text',
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'datetime_example3',
                'datetime',
                array(
                    'widget' => 'single_text',
                    'required' => false,
                    'data' => new \DateTime(),
                )
            )
            ->add(
                'time_example1',
                'time',
                array(
                    'widget' => 'choice',
                    'required' => false,
                )
            )
            ->add(
                'time_example2',
                'time',
                array(
                    'widget' => 'text',
                    'required' => false,
                )
            )
            ->add(
                'time_example3',
                'time',
                array(
                    'widget' => 'single_text',
                    'required' => false,
                )
            )
            ->add(
                'birthday_example1',
                'birthday',
                array(
                    'widget' => 'choice',
                    'required' => false,
                    'data' => new \DateTime('20 june 1985 13:37:00'),
                )
            )
            ->add(
                'birthday_example2',
                'birthday',
                array(
                    'widget' => 'text',
                    'required' => false,
                    'data' => new \DateTime('20 june 1985 13:37:00'),
                )
            )
            ->add(
                'birthday_example3',
                'birthday',
                array(
                    'widget' => 'single_text',
                    'required' => false,
                    'data' => new \DateTime('20 june 1985 13:37:00'),
                )
            )
            ->add(
                'birthday_example4',
                'birthday',
                array(
                    'widget' => 'single_text',
                    'datepicker' => true,
                    'required' => false,
                    'data' => new \DateTime('20 june 1985 13:37:00'),
                )
            )
            ->add(
                'save',
                'submit',
                array(
                    'label' => 'Ok',
                )
            )
            ->getForm();

        return array(
            'form' => $form->createView()
        );
    }
}
