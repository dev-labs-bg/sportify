<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournamentType extends AbstractType
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
     * Building the filter form based on the list of fields passed in by the 'fields' option
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->data = $options['data'];
        $this->buttonAction = $options['button_action'];

        $builder
            ->add('id', HiddenType::class, array(
                'data' => $this->data
            ))
            ->add('action', HiddenType::class, array(
                'data' => $this->buttonAction
            ))
            ->add('button', SubmitType::class, array(
                'label' => $this->buttonAction
            ))
        ;
    }
}
