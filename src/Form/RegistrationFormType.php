<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom' , TextType::class, [
                "label"=> "nom"
            ])
            ->add('prenom', TextType::class, [
                "label"=> "prenom"
            ] )
            ->add('email', EmailType::class, [
                "label"=> "email"])

            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096]),
                ],
            ])
            ->add('domaine',ChoiceType::class, [
                "label"=> "domaine",
                "choices"=> [
                    "cinéma" => "Cinéma",
                    "musique" => "Musique",
                    "photographie" => "photographie",
                    "cuisine" => "Cuisine",
                    "peinture" => "Peinture"
                ]  ])
            ->add('type' , ChoiceType::class, [
                "label"=> "type",
                "expanded"=>true,
                "choices"=> [
                    "artiste" => "Artiste",
                    "membre" => "Membre",
                ]])
            ->add('photo' , FileType::class, [
                "label"=> "photo"])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
