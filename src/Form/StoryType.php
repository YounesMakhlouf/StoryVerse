<?php

namespace App\Form;

use App\Entity\Story;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class StoryType extends AbstractType
{
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Enter title',
                'required' => false,
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'required' => true,
                'choices' => $this->getGenreChoices(),
            ])
            ->add('firstContribution', TextareaType::class, [
                'label' => 'Your first contribution',
                'mapped' => false,
            ])
            ->add('storyImage', FileType::class, [
                'label' => 'Story Image (JPG or PNG file)',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG or PNG image',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save changes',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    private function getGenreChoices(): array
    {
        $storyGenres = $this->parameterBag->get('story_genres');
        $choices = [];
        foreach ($storyGenres as $genre => $description) {
            $choices[$genre] = $genre;
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Story::class,
        ]);
    }
}