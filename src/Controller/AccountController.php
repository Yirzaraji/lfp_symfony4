<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Controller\appcolorController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



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
        [   'hasError' => $error !== null,
            'username' => $username,
            'admin' => $admin]);
    }

    /**
     * 
     * @route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){


    }

    /**
     * Undocumented function
     *@Route ("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder){

        $admin = $this->appcolor();
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        //prepare requet sql en correlation avec les param de la function create
        $form->handleRequest($request);
        

        //si le form est soumis && si il est valid ->execute
        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            /* $this->addFlash(
                'success',
                'Your account has been successfully created !'

            ); */

            return $this->redirectToRoute('account_login');

        }

        return $this->render('account/registration.html.twig',
        [   'form' => $form->createView(),
            'admin' => $admin]);
    }


}
