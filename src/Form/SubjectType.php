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
                '1° básico' => 1,
                '2° básico' => 2,
                '3° básico' => 3,
                '4° básico' => 4,
                '5° básico' => 5,
                '6° básico' => 6,
                '7° básico' => 7,
                '8° básico' => 8,
                '1° medio' => 9,
                '2° medio' => 10,
                '3° medio' => 11,
                '4° medio' => 12
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
