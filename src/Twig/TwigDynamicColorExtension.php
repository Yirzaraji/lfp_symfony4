<?php
namespace App\Twig;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\DynamicColor;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use App\Twig\TwigDynamicColorExtension;
use Doctrine\ORM\EntityManagerInterface;


class TwigDynamicColorExtension extends AbstractExtension{

   

    public function getFunctions()
    {
        return [
            new TwigFunction('colorExtension', [$this, 'queryColor'], ['is_safe']),
            new TwigFunction('colorQueryExtension', [$this, 'queryColorBis'], ['is_safe']),
        ];
    }

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        
    }

    public function queryColorBis(){
      
        $test = " Bis The twig function extension is working !";
      
       
        /* $em = $this->getManager();
        $myRepo = $em->getRepository(DynamicColor::class);

        $myRepo->findOneBy(
        array(), 
        array('id' => 'DESC'));



        return $myRepo;
     */
      

    }


    public function queryColor(){
        $test = "The twig function extension is working !";
        return $test;

    }

    public function getFilters()
    {
        return [
            new TwigFilter('badge', [$this, 'badgeFilter']),
        ];
    }

    public function badgeFilter($content):string{
        return '<span class="badge badge-primary">'. $content .'</span>';

    }

   


}