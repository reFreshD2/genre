<?php

namespace App\Form;

use App\Entity\Feature;
use App\Entity\PossibleValue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PossibleValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feature', EntityType::class, [
                'label' => 'Название признака',
                'class' => Feature::class,
                'placeholder' => 'Выберите признак'
            ])
            //->add('feature', TextType::class, ['label' => 'Тип признака', 'disabled' => true])
            ->add('value', TextareaType::class, [
                'label' => 'Возможное значение'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PossibleValue::class,
        ]);
    }
}
