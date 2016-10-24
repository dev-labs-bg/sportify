<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Devlabs\SportifyBundle\Form\DataTransformer\MatchToIdTransformer;
use Devlabs\SportifyBundle\Form\DataTransformer\UserToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PredictionType extends AbstractType
{
    protected $manager;
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
            'data_class' => 'Devlabs\SportifyBundle\Entity\Prediction',
            'button_action' => null
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buttonAction = $options['button_action'];

        $builder
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
            ->add('matchId', HiddenType::class, array(
                'invalid_message' => 'That is not a valid match id',
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('userId', HiddenType::class, array(
                'invalid_message' => 'That is not a valid user id',
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('homeGoals', TextType::class, array(
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('awayGoals', TextType::class, array(
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('action', HiddenType::class, array(
                'data' => $this->buttonAction,
                'mapped' => false
            ))
            ->add('button', SubmitType::class, array(
                'label' => $this->buttonAction
            ))
        ;

        // data transformations - string <-> object for the 'matchId' field
        $builder->get('matchId')
            ->addModelTransformer(new MatchToIdTransformer($this->manager))
        ;

        // data transformations - string <-> object for the 'userId' field
        $builder->get('userId')
            ->addModelTransformer(new UserToIdTransformer($this->manager))
        ;

        // data transformations - string <-> integer for the 'homeGoals' field
        $builder->get('homeGoals')
            ->addModelTransformer(new CallbackTransformer(
                function ($number) {
                    // transform the integer to a string
                    return (string) $number;
                },
                function ($text) {
                    // transform the string back to an integer
                    return (int) $text;
                }
            ))
        ;

        // data transformations - string <-> integer for the 'awayGoals' field
        $builder->get('awayGoals')
            ->addModelTransformer(new CallbackTransformer(
                function ($number) {
                    // transform the integer to a string
                    return (string) $number;
                },
                function ($text) {
                    // transform the string back to an integer
                    return (int) $text;
                }
            ))
        ;
    }
}
