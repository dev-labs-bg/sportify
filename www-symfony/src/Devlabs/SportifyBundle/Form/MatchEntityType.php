<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MatchEntityType extends AbstractType
{
    protected $data;
    protected $buttonAction;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Devlabs\SportifyBundle\Entity\Match',
            'button_action' => null
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->data = $options['data'];
        $this->buttonAction = $options['button_action'];

        $builder
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
            ->add('tournamentId', HiddenType::class, array(
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('$datetime', DateTimeType::class, array(
                'widget' => 'single_text',
                'error_bubbling' => true
            ))
            ->add('homeTeamId', TeamChoiceType::class, array(
                'choices' => $this->data['team']['choices'],
                'data' => $this->data['team']['data']
            ))
            ->add('awayTeamId', TeamChoiceType::class, array(
                'choices' => $this->data['team']['choices'],
                'data' => $this->data['team']['data']
            ))
            ->add('homeGoals', TextType::class, array(
                'error_bubbling' => true
            ))
            ->add('awayGoals', TextType::class, array(
                'error_bubbling' => true
            ))
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
