<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('first_name', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'form-label',],])->add('last_name', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'form-label',],])->add('username', TextType::class, ['constraints' => [new Assert\NotBlank(),], 'label_attr' => ['class' => 'form-label',], 'attr' => ['class' => 'form-control']])->add('email', EmailType::class, ['attr' => ['class' => 'form-control'], 'label' => 'E-mail', 'label_attr' => ['class' => 'form-label',],])->add('gender', GenderType::class, ['expanded' => true,])->add('password', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'The password fields must match.', 'required' => true, 'first_options' => ['label' => 'Password', 'attr' => ['class' => 'form-control']], 'second_options' => ['label' => 'Repeat Password', 'attr' => ['class' => 'form-control']], 'constraints' => [new Assert\NotBlank(), new Assert\Length(['min' => 6, 'minMessage' => 'Your password should be at least {{ limit }} characters', 'max' => 4096,]),],]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class, 'constraints' => [new UniqueEntity(['fields' => 'username']),],]);
    }
}