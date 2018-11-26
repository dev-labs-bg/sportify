<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\CallbackTransformer;
use Devlabs\SportifyBundle\Form\DataTransformer\TournamentToIdTransformer;
use Devlabs\SportifyBundle\Form\DataTransformer\TeamToIdTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MatchEntityType extends AbstractType
{
    protected $manager;
    protected $otherData;
    protected $buttonAction;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Devlabs\SportifyBundle\Entity\Match',
            'button_action' => null,
            'other_data' => null
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->otherData = $options['other_data'];
        $this->buttonAction = $options['button_action'];

        $builder
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
            ->add('tournamentId', HiddenType::class, array(
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('datetime', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'error_bubbling' => true
            ))
            ->add('homeTeamId', TeamChoiceType::class, array(
                'choices' => $this->otherData['team']['choices'],
                'data' => $this->otherData['team']['home']
            ))
            ->add('awayTeamId', TeamChoiceType::class, array(
                'choices' => $this->otherData['team']['choices'],
                'data' => $this->otherData['team']['away']
            ))
            ->add('homeGoals', TextType::class, array(
                'required' => false,
                'error_bubbling' => true
            ))
            ->add('awayGoals', TextType::class, array(
                'required' => false,
                'error_bubbling' => true
            ))
            ->add('notificationSent', CheckboxType::class, array(
                'required' => false,
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

        // data transformations - string <-> object for the 'tournamentId' field
        $builder->get('tournamentId')
            ->addModelTransformer(new TournamentToIdTransformer($this->manager))
        ;

        // data transformations - string <-> object for the 'homeTeamId' field
//        $builder->get('homeTeamId')
//            ->addModelTransformer(new TeamToIdTransformer($this->manager))
//        ;

        // data transformations - string <-> object for the 'awayTeamId' field
//        $builder->get('awayTeamId')
//            ->addModelTransformer(new TeamToIdTransformer($this->manager))
//        ;

        // data transformations - string <-> boolean for the 'homeGoals' field
//        $builder->get('notificationSent')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($boolean) {
//                    // transform the boolean to a string
//                    return (string) $boolean;
//                },
//                function ($text) {
//                    // transform the string back to boolean
//                    return (boolean) $text;
//                }
//            ))
//        ;
    }
}
