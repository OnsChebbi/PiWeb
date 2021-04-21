<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=> [
                    'class'=>'form-control',
                    'placeholder'=>'Nom'
                ]
            ])
            ->add('prix',MoneyType::class)

            ->add('description',TextareaType::class,[
                'attr'=> [
                    'class'=>'form-control',
                    'placeholder'=>'description'
                ]
            ])
            ->add('image',FileType::class,
                array('data_class'=>null,'required' => false,'mapped'=>false))
            ->add('Envoyer', SubmitType::class,[
                'attr'=> [
                    'class'=>'btn btn-primary'
                ]])
            ->add('Annuler', ResetType::class,[
                'attr'=> [
                    'class'=>'btn btn-primary'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
