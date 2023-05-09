<?php

namespace App\Form;
use App\Entity\Competition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            
            ->add('status', ChoiceType::class, [
                
                'choices' => [
                    'Open' => 'open',
                    'Closed' => 'closed',
                ],
            ])
            ->add('paid', ChoiceType::class, [
                'choices' => [
                    'Paid' => 'Paid',
                    'unpaid' => 'Unpaid'],
            ])
            ->add ('startsAt', DateTimeType::class, [
                'data_class' => \DateTimeImmutable::class,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ])
            ->add ('endsAt', DateTimeType::class, [
                'data_class' => \DateTimeImmutable::class,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ])

            ->add('imageFilename', FileType::class, [
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('save', SubmitType::class)
        ;
    }

     public function configureOptions(OptionsResolver $resolver): void
     {
        $resolver->setDefaults([
            'data_class' => Competition::class,
        ]);
    }
}
