<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\CompanyRegistrationDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CompanyRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa firmy',
                'attr' => [
                    'placeholder' => 'Wpisz nazwę firmy',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nazwa firmy jest wymagana',
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adres siedziby',
                'attr' => [
                    'placeholder' => 'Wpisz adres siedziby',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Adres siedziby jest wymagany',
                    ]),
                ],
            ])
            ->add('nip', TextType::class, [
                'label' => 'NIP',
                'attr' => [
                    'placeholder' => 'Wpisz NIP (10 cyfr)',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'NIP jest wymagany',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'exactMessage' => 'NIP musi składać się z dokładnie 10 cyfr',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'NIP może zawierać tylko cyfry',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefon',
                'attr' => [
                    'placeholder' => 'Wpisz numer telefonu',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Numer telefonu jest wymagany',
                    ]),
                    new Regex([
                        'pattern' => '/^\+?[0-9\s]+$/',
                        'message' => 'Nieprawidłowy format numeru telefonu',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Wpisz adres email',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Adres email jest wymagany',
                    ]),
                ],
            ])
            ->add('ownerFirstName', TextType::class, [
                'label' => 'Imię właściciela',
                'attr' => [
                    'placeholder' => 'Wpisz imię właściciela',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Imię właściciela jest wymagane',
                    ]),
                ],
            ])
            ->add('ownerLastName', TextType::class, [
                'label' => 'Nazwisko właściciela',
                'attr' => [
                    'placeholder' => 'Wpisz nazwisko właściciela',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nazwisko właściciela jest wymagane',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Hasło',
                    'attr' => [
                        'placeholder' => 'Wpisz hasło',
                        'class' => 'form-control',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Hasło jest wymagane',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Hasło musi mieć co najmniej {{ limit }} znaków',
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                            'message' => 'Hasło musi zawierać przynajmniej jedną małą literę, jedną wielką literę i jedną cyfrę',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Powtórz hasło',
                    'attr' => [
                        'placeholder' => 'Powtórz hasło',
                        'class' => 'form-control',
                    ],
                ],
                'invalid_message' => 'Hasła muszą być takie same',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Akceptuję regulamin',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Musisz zaakceptować regulamin',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyRegistrationDto::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'registration_form',
        ]);
    }
}
