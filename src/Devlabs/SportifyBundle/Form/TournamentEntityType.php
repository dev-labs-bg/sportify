<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TournamentEntityType extends AbstractType
{
//    protected $otherData;
    protected $buttonAction;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Devlabs\SportifyBundle\Entity\Tournament',
            'button_action' => null,
//            'other_data' => null
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $this->otherData = $options['other_data'];
        $this->buttonAction = $options['button_action'];

        $builder
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
            ->add('name', TextType::class, array(
                'error_bubbling' => true
            ))
            ->add('startDate', DateType::class, array(
                'widget' => 'single_text',
                'error_bubbling' => true
            ))
            ->add('endDate', DateType::class, array(
                'widget' => 'single_text',
                'error_bubbling' => true
            ))
            ->add('uploadFile', FileType::class, array(
                'label' => 'Upload logo',
                'error_bubbling' => true,
                'required' => false
            ))
//            ->add('championTeamId', TeamChoiceType::class, array(
//                'choices' => $this->otherData['team']['choices'],
//                'data' => $this->otherData['team']['data'],
//                'required' => false
//            ))
            ->add('action', HiddenType::class, array(
                'data' => $this->buttonAction,
                'mapped' => false
            ))
            ->add('button1', SubmitType::class, array(
                'label' => $this->buttonAction
            ))
        ;

        if ($this->buttonAction === 'EDIT') {
            $builder
                ->add('button2', SubmitType::class, array(
                    'label' => 'DELETE'
                ));
        }
    }
}
