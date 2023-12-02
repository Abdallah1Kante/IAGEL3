<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Contrat;
use App\Entity\TypeOffre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            // ->add('dateContrat')
            ->add('montant')
            ->add('dateDebut', DateType::class, [
                "format" => "dd/MMMM/yyyy",
            ])
            ->add('dateFin',DateType::class, [
                "format" => "dd/MMMM/yyyy", 
                ])
            ->add('etatReglement')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                "placeholder" => "Choisir un client"
            ])
            ->add('typeOffre', EntityType::class, [
                'class' => TypeOffre::class,
                'choice_label' => 'nom',
                "multiple" => false,
                'expanded' => true, // Use checkboxes
            ])
            ->add('voiture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
