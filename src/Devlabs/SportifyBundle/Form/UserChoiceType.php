<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserChoiceType extends AbstractType
{
    protected $data;
    protected $choices;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Devlabs\SportifyBundle\Entity\User',
            'choices' => null
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->data = $options['data'];
        $this->choices = $options['choices'];

        $builder
            ->add('id', EntityType::class, array(
                'class' => 'DevlabsSportifyBundle:User',
                'choices' => $this->choices,
                'choice_label' => 'username',
                'label' => false,
                'data' => $this->data
            ))
        ;
    }
}
