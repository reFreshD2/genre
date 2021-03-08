<?php

namespace App\Form;

use App\Entity\Feature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => '�������� ��������'])
            ->add('type', ChoiceType::class, [
                'label' => '��� ��������',
                'choices' => [
                    0 => false,
                    1 => false
                ],
                'choice_label' => function ($choice, $key) {
                    $label = '';
                    switch ($key) {
                        case Feature::QUALITATIVE:
                            $label = '������������ �������';
                            break;
                        case Feature::QUANTITATIVE:
                            $label = '�������������� �������';
                            break;
                    }
                    return $label;
                },
                'expanded' => true,
                'multiple' => false
            ])
            ->add('alias', TextType::class, ['label' => 'Alias ��� �����']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Feature::class,
        ]);
    }
}
