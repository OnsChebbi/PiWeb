<?php

namespace App\Form;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonAjoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etat',ChoiceType::class, [
                'choices'  => [ 'main'=>[
                    'non livré' => 'non livré',
                    'en cours' => 'en cours',
                    'livré' => 'livré',
                    ],],])
            //->add('etat')
            ->add('datelivraison')
            ->add('prixtotal',MoneyType::class,[
                'attr'=> [
                    'class'=>'form-control',
                    'placeholder'=>'prixtotal'
                ]])
           // ->add('prixtotal')
            ->add('livreur')
            ->add ('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
