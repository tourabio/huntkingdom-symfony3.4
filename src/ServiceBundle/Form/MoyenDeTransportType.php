<?php

namespace ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoyenDeTransportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type')
            ->add('prixParJour')
            ->add('image',FileType::class, array('data_class' => null,'required' => false))
            ->add('marque')
            ->add('categorie', ChoiceType::class,array('choices'=>array('motorcycle'=>"motorcycle",
                'bike'=>"bike",
                'caravan'=>"caravan",
                'car'=>"car",
                'boat'=>"boat",
                'yacht'=>"yacht"),))
            ->add('Submit',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ServiceBundle\Entity\MoyenDeTransport'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'servicebundle_moyendetransport';
    }


}
