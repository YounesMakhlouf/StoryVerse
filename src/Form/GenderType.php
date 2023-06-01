<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenderType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'Gender',
            'choices' => [
                'Male' => 'male',
                'Female' => 'female',
            ]
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
