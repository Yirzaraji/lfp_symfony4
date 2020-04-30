<?php

namespace App\Controller;

use App\Controller\appcolorController;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends appcolorController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     */
    public function index(User $user)
    {
        /* Herite de appColorContoller*/
        $admin = $this->appcolor();

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'admin' => $admin,
        ]);
    }
}
