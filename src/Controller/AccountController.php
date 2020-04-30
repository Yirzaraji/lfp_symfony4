<?php

namespace App\Controller;

use App\Controller\appcolorController;
use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends appcolorController
{
    /**
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        /* appcolorController ma custom classe qui contient la query sql
        pour obtenir le champ color */
        //ATTENTION extend sur mon appcolorController qqui extend lui meme abstractController
        $admin = $this->appcolor();

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        dump($error);

        return $this->render('account/login.html.twig',
            ['hasError' => $error !== null,
                'username' => $username,
                'admin' => $admin]);
    }

    /**
     *
     * @route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout()
    {

    }

    /**
     * Permet d'enregistrer un nouvel user
     *@Route ("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $admin = $this->appcolor();
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        //prepare requet sql en correlation avec les param de la function create
        $form->handleRequest($request);

        //si le form est soumis && si il est valid ->execute
        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your account has been successfully created !'

            );

            return $this->redirectToRoute('account_login');

        }

        return $this->render('account/registration.html.twig',
            ['form' => $form->createView(),
                'admin' => $admin]);
    }

    /**
     * Affiche le formulaire de modification de profil
     *@Route ("/account/profile", name="account_profile")
     * @return Response
     */
    public function profile(Request $request)
    {

        $admin = $this->appcolor();
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Successfull !"
            );

        }

        return $this->render('account/profile.html.twig',
            ['form' => $form->createView(),
                'admin' => $admin]);
    }

    /**
     * Permet de modifier son mot de passe
     * @Route ("/account/password-update", name="account_password")
     * @return Response
     */
    public function updatePassword(request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {

        $admin = $this->appcolor();

        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();

        //Va chercher le form builder Type
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$passwordUpdate est l'entity. getOldPassword() dans le form builder est assovié au champ correspondant du formulaire
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {

                //if not true throw error here
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                //$passwordUpdate est l'entity. getNewPassword() dans le form builder (fini en TYPE) est associé au champ correspondant du formulaire
                $newPassword = $passwordUpdate->getNewPassword();
                var_dump($user);
                //pour que $encoder ne soit pas nul il faut indiquer l'interface
                $hash = $encoder->encodePassword($user, $newPassword);

                //setter qui va entrer la valeur du hash dans l'entity qui pourra ensuite etre persister
                $user->setHash($hash);

                //$manager marche seulement si l'injection de dependance objectmanager est passée
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Successfull !"
                );

                return $this->redirectToRoute('homepage');

            }

        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin,
        ]);

    }

    /**
     * Show user account
     * @Route ("/account", name="account_index")
     * @return Response
     */
    public function myAccount()
    {
        $admin = $this->appcolor();

        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
            'admin' => $admin,
        ]);

    }

}
