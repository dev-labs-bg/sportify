<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChampionSelectType extends AbstractType
{
    protected $data;
    protected $buttonAction;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
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
            ->add('team', TeamChoiceType::class, array(
                'choices' => $this->data['team']['choices'],
                'data' => $this->data['team']['data']
            ))
        ;

        $builder
            ->add('action', HiddenType::class, array(
                'data' => $this->buttonAction
            ))
            ->add('button', SubmitType::class, array(
                'label' => $this->buttonAction
            ))
        ;
    }
}
