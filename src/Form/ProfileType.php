<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ProfileType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
$builder
->add('username', TextType::class, [
'label' => 'Username',
    'required' => false,
])

    ->add('bio', TextType::class, [
        'label' => 'Bio',
        'required' => false,
    ])

    ->add('avatar', ChoiceType::class, [
        'choices' => [
            '(Male) Avatar 1' => 'https://cdn.icon-icons.com/icons2/582/PNG/512/man-2_icon-icons.com_55041.png',
            '(Male) Avatar 2' =>   'https://cdn.icon-icons.com/icons2/1879/PNG/512/iconfinder-8-avatar-2754583_120515.png',
            '(Male) Avatar 3' => 'https://cdn.icon-icons.com/icons2/1736/PNG/512/4043260-avatar-male-man-portrait_113269.png',
            '(Female) Avatar 1' =>'https://cdn.icon-icons.com/icons2/1736/PNG/512/4043247-1-avatar-female-portrait-woman_113261.png',
            '(Female) Avatar 2' => 'https://cdn.icon-icons.com/icons2/1736/PNG/512/4043251-avatar-female-girl-woman_113291.png',
            '(Female) Avatar 3' => 'https://cdn.icon-icons.com/icons2/1879/PNG/512/iconfinder-4-avatar-2754580_120522.png',
        ],
        'placeholder' => 'No Avatar',
        'required' => false,
        'attr' => [
            'style' => 'margin-bottom: 50px;',
        ],
    ])

->add('submit', SubmitType::class, [
'label' => 'Save changes',
'attr' => ['class' => 'btn btn-primary'],
])
;
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'modify-profile',
            'allow_extra_fields' => true,
            'extra_fields_message' => 'Unexpected field: {{ extra_fields }}',
            'csrf_protection' => false,

        ]);

}
}
