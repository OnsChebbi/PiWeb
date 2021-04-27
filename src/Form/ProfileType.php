<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom' , TextType::class, [
                "label"=> "nom", 'data_class' => null
            ])
            ->add('prenom', TextType::class, [
                "label"=> "prenom", 'data_class' => null
            ] )
            ->add('email', EmailType::class, [
                "label"=> "email", 'data_class' => null])

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
                        'max' => 4096])
                ], 'data_class' => null
            ])
            ->add('domaine',ChoiceType::class, [
                "label"=> "domaine",
                "choices"=> [
                    "cinéma" => "Cinéma",
                    "musique" => "Musique",
                    "photographie" => "photographie",
                    "cuisine" => "Cuisine",
                    "peinture" => "Peinture"
                ]  , 'data_class' => null])
            ->add('type' , ChoiceType::class, [
                "label"=> "type",
                "expanded"=>true,
                "choices"=> [
                    "artiste" => "Artiste",
                    "membre" => "Membre",
                ], 'data_class' => null])
            ->add('photo' , FileType::class, [
                "label"=> "photo", 'data_class' => null])

            ->add('sumbit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
