<?php

namespace App\Form;

use App\Entity\User;
use App\form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre Prénom..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email","Votre email..."))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil","Uploadez votre photo..."))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe","Choissisez un bon pass..."))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Password confirmation","Confirm your password..."))
            ->add('introduction', TextType::class, $this->getConfiguration("introduction","En quelques mots..."))
            ->add('description', TextareaType::class, $this->getConfiguration("Description detaillée","Presentez vous en detail..."));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
