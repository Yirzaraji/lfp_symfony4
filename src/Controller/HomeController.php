<?php

//c'est le chemin qui concerné quand on travaille dans le controller
namespace App\Controller;

//class pour la response http
use App\Controller\appcolorController;
use App\Entity\DynamicColor;
use App\Form\CsvType;

//class pour la route
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// class controlller import pour le extend
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends appcolorController
{

    /**
     * on passe un param dans l'url genre de php echo
     * @route("/hello/{prenom}/age/{age}", name="hello")
     * @route("/hello", name="hello_base")
     * @route("/hello/age", name="hello_age")
     */
    public function hello($prenom = " anonyme ", $age = 0)
    {
        return new Response("Hello " . $prenom . " age " . $age);

    }

    private function getConfiguration($label, $placeholder)
    {

        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ],

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

        //Charge moi ce template
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
    public function createFormColor(Request $request)
    {

        /* appcolorController.php ma custom classe qui contient la query sql
        pour obtenir le champ color */
        $admin = $this->appcolor();

        $color = new DynamicColor();

        $form = $this->createFormBuilder($color)
            ->add('color', ColorType::class, $this->getConfiguration("Couleur", "Choisissez votre couleur"))
            ->getform();

        $form->handleRequest($request);

        //si le form est soumis && si il est valid ->execute
        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($color);
            $manager->flush();
        }

        return $this->render(
            'FormColor.html.twig',
            ['admin' => $admin,
                'form' => $form->createView()]);
    }

    /**
     * collapse export date csv
     * @route("/csv", name="csv_form")
     * @return response
     *
     */
    public function csv(Request $request)
    {
        /* appcolorController.php ma custom classe qui contient la query sql
        pour obtenir le champ color */
        $admin = $this->appcolor();

        $form = $this->createForm(CsvType::class);

        $form->handleRequest($request);

        //si le form est soumis && si il est valid ->execute
        if ($form->isSubmitted()) {
            var_dump("test");

        }

        return $this->render(
            'csv.html.twig',
            [
                'form' => $form->createView(),
                'admin' => $admin,
            ]);
    }

}
