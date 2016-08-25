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

class ApiMappingType extends AbstractType
{
    protected $buttonAction;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Devlabs\SportifyBundle\Entity\ApiMapping',
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
            ->add('entityId', TextType::class, array(
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('entityType', TextType::class, array(
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('apiName', TextType::class, array(
                'label' => false,
                'error_bubbling' => true
            ))
            ->add('apiObjectId', TextType::class, array(
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
    }
}
