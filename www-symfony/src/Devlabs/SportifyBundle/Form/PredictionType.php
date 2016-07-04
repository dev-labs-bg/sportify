<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Devlabs\SportifyBundle\Form\DataTransformer\MatchToIdTransformer;
use Devlabs\SportifyBundle\Form\DataTransformer\UserToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PredictionType extends AbstractType
{
    protected $manager;
    protected $data;
    protected $match;
    protected $prediction;
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
//            'match' => null,
//            'prediction' => null,
            'button_action' => null
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $this->data = $options['data'];
//        $this->match = $options['match'];
//        $this->prediction = $options['prediction'];
        $this->buttonAction = $options['button_action'];

//        $builder
//            ->add('matchId', HiddenType::class, array(
//                'data' => $this->match->getId()
//            ))
//            ->add('homeGoals', TextType::class, array(
//                'data' => $this->prediction->getHomeGoals(),
//                'label' => false
//            ))
//            ->add('awayGoals', TextType::class, array(
//                'data' => $this->prediction->getAwayGoals(),
//                'label' => false
//            ))
//            ->add('action', HiddenType::class, array(
//                'data' => $this->buttonAction,
//                'mapped' => false
//            ))
//            ->add('button', SubmitType::class, array(
//                'label' => $this->buttonAction
//            ))
//        ;

        $builder
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

        $builder->get('matchId')
            ->addModelTransformer(new MatchToIdTransformer($this->manager))
        ;

        $builder->get('userId')
            ->addModelTransformer(new UserToIdTransformer($this->manager))
        ;

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
