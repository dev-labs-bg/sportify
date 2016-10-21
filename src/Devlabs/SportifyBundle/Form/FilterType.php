<?php

namespace Devlabs\SportifyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterType extends AbstractType
{
    protected $data;
    protected $fields;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'fields' => null
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
        $this->fields = $options['fields'];

        if (in_array('tournament', $this->fields)) {
            $builder
                ->add('tournament', TournamentChoiceType::class, array(
                    'choices' => $this->data['tournament']['choices'],
                    'data' => $this->data['tournament']['data']
                ));
        }

        if (in_array('user', $this->fields)) {
            $builder
                ->add('user', UserChoiceType::class, array(
                    'choices' => $this->data['user']['choices'],
                    'data' => $this->data['user']['data']
                ));
        }

        if (in_array('date_from', $this->fields)) {
            $builder
                ->add('date_from', TextType::class, array(
                    'label' => false,
                    'data' => $this->data['date_from']
                ));
        }

        if (in_array('date_to', $this->fields)) {
            $builder
                ->add('date_to', TextType::class, array(
                    'label' => false,
                    'data' => $this->data['date_to']
                ));
        }

        $builder
            ->add('button', SubmitType::class, array('label' => 'FILTER'))
        ;
    }
}
