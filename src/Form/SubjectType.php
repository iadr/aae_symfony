<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Asignatura'
            ])
            ->add('level',ChoiceType::class,[
                'label' =>'Curso',
                'choices'=> [
                '1° básico',
                '2° básico',
                '3° básico',
                '4° básico',
                '5° básico',
                '6° básico',
                '7° básico',
                '8° básico',
                '1° medio',
                '2° medio',
                '3° medio',
                '4° medio'
                ]
            ])
//            ->add('subjectTutor')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
