<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', CheckboxType::class, array('required' => false), $this->getConfiguration("Adresse", ""))
            ->add('ville', CheckboxType::class, array('required' => false), $this->getConfiguration("Ville", ""))
            ->add('nom_client', CheckboxType::class, array('required' => false), $this->getConfiguration("Nom", ""))
            ->add('prenom', CheckboxType::class, array('required' => false), $this->getConfiguration("Prenom", ""))
            ->add('telephone', CheckboxType::class, array('required' => false), $this->getConfiguration("Telephone", ""))
        ;

        $builder
            ->add('nom_produit', CheckboxType::class, array('required' => false), $this->getConfiguration("Nom", ""))
            ->add('interval', CheckboxType::class, array('required' => false), $this->getConfiguration("Intervalle de prix", ""))
            ->add('product_code', CheckboxType::class, array('required' => false), $this->getConfiguration("Code produits", ""))
            ->add('rubriques', CheckboxType::class, array('required' => false), $this->getConfiguration("Rubriques", ""))
        ;

        $builder
            ->add('ca_average', CheckboxType::class, array('required' => false), $this->getConfiguration("Ca moyen", ""))
            ->add('ca_total', CheckboxType::class, array('required' => false), $this->getConfiguration("Ca total", ""))
            ->add('commandes', CheckboxType::class, array('required' => false), $this->getConfiguration("Nombre de commandes", ""))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
