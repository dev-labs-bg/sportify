<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TournamentType extends AbstractType
{
    protected $data;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->data = $options['data'];

        $builder
            ->add('id', EntityType::class, array(
                'class' => 'DevlabsSportifyBundle:Tournament',
                'choices' => $this->data['tournaments_joined'],
                'choice_label' => 'name',
                'label' => false,
                'data' => $this->data['tournament_selected']
            ))
        ;
    }
}
