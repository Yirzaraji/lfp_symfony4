<?php
namespace App\Controller;
use App\Entity\DynamicColor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class appcolorController extends AbstractController
{
    protected function appcolor()
    {

    //Va me chercher l'entité DynamicColor
    $repo = $this->getDoctrine()->getRepository(DynamicColor::class);
       
    //equivalent du Fetch en php natif 'ici relié a l'entité DynamicColor c'est egalement une Class (objet)'
    $admin = $repo->findOneBy(
        array(), 
        array('id' => 'DESC'));
    //$admin = $repo->find(4);

    //var_dump($admin);
    return $admin;

    }


}