<?php

//c'est le chemin qui concerné quand on travaille dans le controller
namespace App\Controller;

//class pour la response http
use Symfony\Component\HttpFoundation\Response;
use App\Controller\appcolorController;

//class pour la route
use Symfony\Component\Routing\Annotation\Route;
// class controlller import pour le extend
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\DynamicColor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class HomeController extends appcolorController{

    /**
     * on passe un param dans l'url genre de php echo
     * @route("/hello/{prenom}/age/{age}", name="hello")
     * @route("/hello", name="hello_base")
     * @route("/hello/age", name="hello_age")
     */
    public function hello($prenom = " anonyme ", $age = 0){
        return new Response ("Hello ".$prenom. " age ".$age);

    }

    private function getConfiguration($label, $placeholder){

        return[
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]

        ];

    }


    /**
     * @Route("/", name="homepage")
     * 
     */
    public function dynamic_color()
    {   
        
        /* appcolorController ma custom classe qui contient la query sql 
        pour obtenir le champ color */
        $admin = $this->appcolor();

        //var_dump($this->appcolor());

        //Charge moi ce template puis va chercher interroger la DB
        return $this->render(
            'home.html.twig',
             ['admin' => $admin,
             'monteste' => "testest"]
        );
    }

   
 


    /**
     * allow you to change templating color
     * @route("/admin", name="admin_form")
     * @return response
     */
    public function createFormColor(Request $request){

        /* appcolorController.php ma custom classe qui contient la query sql 
        pour obtenir le champ color */
        $admin = $this->appcolor();


        $color = new DynamicColor();

        $form = $this->createFormBuilder($color)
                ->add('color', ColorType::class, $this->getConfiguration("Couleur", "Choisissez votre couleur"))
                ->getform();

        $form->handleRequest($request);

        //si le form est soumis && si il est valid ->execute
        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($color);
            $manager->flush();
        }   

        return $this->render(
            'FormColor.html.twig',           
            ['admin'=> $admin, 'form' => $form->createView()]);
    }


}

?>
