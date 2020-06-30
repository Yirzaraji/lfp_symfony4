<?php

namespace App\Controller;

use App\Controller\appcolorController;
use App\Entity\Ad;
use App\Entity\DynamicColor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends appcolorController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index()
    {

        /* appcolorController ma custom classe qui contient la query sql
        pour obtenir le champ color */
        $admin = $this->appcolor();

        //Va me chercher l'entité Ad
        $repo = $this->getDoctrine()->getRepository(Ad::class);

        //equivalent du Fetchall en pdo sql 'ici relié a l'entité Ad c'est egalement une Class (objet)'
        $ads = $repo->findAll();

        //Charge moi ce template puis va chercher interroger la DB
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
            'admin' => $admin,
        ]);
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
     * ajoute moi une annonce
     *
     * @Route("/ads/ajouter", name="ads_create")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    //Request est une class et $request est comme le $_POST
    public function create(Request $request)
    {
        //relier entite voulue a notre formulaire
        $ad = new Ad();

        $admin = $this->appcolor();

        $form = $this->createFormBuilder($ad)

            ->add('title', TextType::class, $this->getConfiguration("Titre", "Un super titre pour votre annonce"))
            ->add('slug', TextType::class, $this->getConfiguration("Adress web", "Adresse(automatique)"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Donnez une description a votre annonce"))
            ->add('introduction', TextType::class, $this->getConfiguration("introduction", "Donnez une description globale a votre annonce"))
            ->add('content', TextType::class, $this->getConfiguration("Description detaillée", "Une description qui donne envie de venir chez vous"))
            ->add('coverImage', UrlType::class, $this->getConfiguration("url de l'image principale", "Uploader une superbe image"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres", "Le nombre de chambre disponible"))

            ->getForm();
        //prepare requet sql en correlation avec les param de la function create
        $form->handleRequest($request);

        //si le form est soumis && si il est valid ->execute
        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();

            // l'auteur est utilisateur connecté au moment d ela creation d'annonce
            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();

            //Ajout un message succes ou erreur au formulaire mais ne marche pas
            $this->addFlash(
                'success',
                "l'annonce <strong>{$ad->getTitle()}</strong> a bien ete enregistrée"
            );

            //Redirige moi vers annonce novuellement crée via le slug
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug(),

            ]);

        }

        //Tips la presence de crochet indique un array
        return $this->render('ad/ajouter.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin,

        ]);

    }

    /**
     * Affiche moi une seule annonce via slug
     *
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show($slug)
    {

        //Va me chercher l'entité DynamicColor
        $repo2 = $this->getDoctrine()->getRepository(DynamicColor::class);

        //equivalent du Fetch en php natif 'ici relié a l'entité DynamicColor c'est egalement une Class (objet)'
        $admin = $repo2->findOneBy(
            array(),
            array('id' => 'DESC'));

        $repo = $this->getDoctrine()->getRepository(Ad::class);

        //Peut etre FindOneById par exemple se refere a l entite qui comprend une private ID
        //mais Ici je recupere l'annonce qui correspond au slug mais je pourrais faire avec un ID
        $ad = $repo->findOneBySlug($slug);

        //Tips la presence de crochet indique un array
        return $this->render('ad/show.html.twig', [

            'ad' => $ad,
            'admin' => $admin,

        ]);

    }

}
