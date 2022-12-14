<?php

namespace App\Controller;

use App\Entity\Oeuvres;
use App\filter\OeuvreFilter;
use App\Form\OeuvreFilterType;
use App\Repository\OeuvresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
   
/**
 * @Route("/",name="acceuil")
 */
    public function home(OeuvresRepository $oeuvresRepository):Response
    {
        $oeuvre=$oeuvresRepository->findByCategorie(1);
    
        return $this->render('main/home.html.twig',[
            "oeuvres"=>$oeuvre,
           
        ]);

    }


/**
 * @Route("/",name="filter")
 * 
 */
public function filter(Oeuvres $oeuvre){
    return $this->render('main/filter.html.twig',[
        "oeuvres"=>$oeuvre,
    ]);

}

}
