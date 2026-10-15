<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Positive;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
            ])
            ->add('date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('informations', TextareaType::class, [
                'label' => 'Informations',
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Concert' => 'concert',
                    'Conférence' => 'conference',
                    'Atelier' => 'atelier',
                    'Sport' => 'sport',
                ],
            ])
            ->add('numberOfPlaces', IntegerType::class, [
                'label' => 'Nombre de places',
                'constraints' => [
                    new Positive(message: 'Le nombre de places doit être supérieur à 0.'),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de l’événement',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '5M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        mimeTypesMessage: 'Choisis une image JPG, PNG ou WebP.',
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
