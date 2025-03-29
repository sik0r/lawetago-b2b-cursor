<?php

declare(strict_types=1);

namespace App\Form\Backoffice;

use App\Entity\Advertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł ogłoszenia',
                'attr' => [
                    'placeholder' => 'Wprowadź tytuł ogłoszenia',
                    'class' => 'w-full p-2 border rounded-md'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'attr' => [
                    'placeholder' => 'Wprowadź szczegółowy opis oferowanych usług lawety',
                    'rows' => 6,
                    'class' => 'w-full p-2 border rounded-md'
                ],
            ])
            ->add('serviceArea', TextareaType::class, [
                'label' => 'Obszar obsługi',
                'attr' => [
                    'placeholder' => 'Wprowadź informacje o obszarze obsługi (miasta, rejony, promień obsługi)',
                    'rows' => 3,
                    'class' => 'w-full p-2 border rounded-md'
                ],
            ])
            ->add('servicesOffered', TextareaType::class, [
                'label' => 'Oferowane usługi',
                'attr' => [
                    'placeholder' => 'Wprowadź listę oferowanych usług (np. holowanie awaryjne, pomoc drogowa, transport pojazdów)',
                    'rows' => 3,
                    'class' => 'w-full p-2 border rounded-md'
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status ogłoszenia',
                'choices' => [
                    'Wersja robocza' => Advertisement::STATUS_DRAFT,
                    'Aktywne' => Advertisement::STATUS_ACTIVE, 
                    'Nieaktywne' => Advertisement::STATUS_INACTIVE,
                ],
                'attr' => [
                    'class' => 'w-full p-2 border rounded-md'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
} 