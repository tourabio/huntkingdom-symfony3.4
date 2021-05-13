<?php

namespace TrainingBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', ChoiceType::class,array('choices'=>array('Hunting'=>"hunting",
                'Fishing'=>"fishing"),))
            ->add('nom')
            ->add('description')
            ->add('image_animal', FileType::class)
            ->add('debutSaison', ChoiceType::class,array('choices'=>array('january'=>"1",
                'february'=>"2",
                'march'=>"3",'april'=>"4",'may'=>"5",'june'=>"6",'july'=>"7",'august'=>"8",'september'=>"9",'october'=>"10",'november'=>"11",'december'=>"12"),))
            ->add('finSaison', ChoiceType::class,array('choices'=>array('january'=>"1",
                'february'=>"2",
                'march'=>"3",'april'=>"4",'may'=>"5",'june'=>"6",'july'=>"7",'august'=>"8",'september'=>"9",'october'=>"10",'november'=>"11",'december'=>"12"),))
            ->add('Submit',SubmitType::class)
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TrainingBundle\Entity\Animal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'trainingbundle_animal';
    }


}
