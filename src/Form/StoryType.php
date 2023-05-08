<?php
namespace App\Form;


use App\Entity\Story;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class StoryType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
$builder
->add('Title', TextType::class, [
'label' => 'Title',
    'required' => false,
])
->add('genre', ChoiceType::class, [
    'label' => 'Genre',
    'required' => true,
    'choices' => [
        'Action' => 'Action',
        'Adventure' => 'Adventure',
        'Comedy' => 'Comedy',
        'Drama' => 'Drama',
        'Horror' => 'Horror',
        'Romance' => 'Romance',
        'Science Fiction' => 'Science Fiction',
        'Thriller' => 'Thriller',
    ],
])


->add('firstContribution', TextareaType::class, [
    'label' => 'Votre première contribution'
  ])

    ->add('storyImage', FileType::class, [
        'label' => 'Story Image (JPG or PNG file)',

        // unmapped means that this field is not associated to any entity property
        'mapped' => false,

        // make it optional so you don't have to re-upload the PDF file
        // every time you edit the Product details
        'required' => false,

        // unmapped fields can't define their validation using annotations
        // in the associated entity, so you can use the PHP constraint classes
        'constraints' => [
            new File([
                'maxSize' => '5M',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                ],
                'mimeTypesMessage' => 'Please upload a valid JPG or PNG image',
            ])
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
            'data_class' => Story::class,
            'csrf_token_id' => 'modify-profile',
            'allow_extra_fields' => true,
            'extra_fields_message' => 'Unexpected field: {{ extra_fields }}',
            'csrf_protection' => false,

        ]);

}
}
